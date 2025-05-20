<?php
namespace Matteomcr\GestionCongeEmploye\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Matteomcr\GestionCongeEmploye\Models\Employe;
use Matteomcr\GestionCongeEmploye\Models\Role;
use Matteomcr\GestionCongeEmploye\Models\Departement;
use Matteomcr\GestionCongeEmploye\Models\HeureSupplementaire;
use Matteomcr\GestionCongeEmploye\Models\Conge;
use Exception;
use DateTime;


class LeaveController extends BaseController{
    
    /**
     * Affiche les congés avec filtres pour admin/manager/employé.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showLeavePage(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $employe = Employe::current();
        $role = $employe->getRole()->NomRole;

        if (!$employe) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        $queryParams = $request->getQueryParams();
        $filterDepartement = $queryParams['departement'] ?? null;
        $filterType = $queryParams['type'] ?? null;
        $filterDateDebut = $queryParams['dateDebut'] ?? null;
        $filterDateFin = $queryParams['dateFin'] ?? null;


        if ($role === 'Administrateur') {
            $conges = Conge::fetchAll(); // Charge tout

            if ($filterDepartement) {
                $conges = array_filter($conges, fn($c) => $c->getEmploye()->idDepartement == $filterDepartement);
            }

            if ($filterType) {
                $conges = array_filter($conges, fn($c) => $c->TypeConge == $filterType);
            }

            if ($filterDateDebut && $filterDateFin) {
                $conges = array_filter($conges, fn($c) =>
                    $c->DateDebut >= $filterDateDebut && $c->DateFin <= $filterDateFin
                );
            }
        }
        elseif($role=== 'Manager'){
            $departement = $employe->getDepartement();
            $conges = Conge::fetchByDepartementId($departement->idDepartement);
            if ($filterType) {
                $conges = array_filter($conges, fn($c) => $c->TypeConge == $filterType);
            }

            if ($filterDateDebut && $filterDateFin) {
                $conges = array_filter($conges, fn($c) =>
                    $c->DateDebut >= $filterDateDebut && $c->DateFin <= $filterDateFin
                );
            }
        }
        else {
            $conges = Conge::fetchByEmployeId($employe->idEmploye); // employé -> que les siennes
        }
        return $this->view->render($response, 'leaves-page.php', [
            'conges' => $conges,
            'departementFiltre' => $filterDepartement,
            'typeFiltre' => $filterType
        ]);
    }

    /**
     * Affiche le formulaire de demande de congé (interdit aux admins).
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showFormLeave(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();
        if (!$user || $user->getRole()->NomRole === 'Administrateur') {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        return $this->view->render($response, 'form-add-leave.php');
    }

    /**
     * Soumet une demande de congé pour un employé connecté.
     *
     * @param ServerRequestInterface $request Requête HTTP
     * @param ResponseInterface $response Réponse HTTP
     * @param array $args Paramètres de route
     * @return ResponseInterface
     */
    public function submitLeave(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $user = Employe::current();

        if (!$user) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        $data = $_POST;

        $type = $data['typeConge'] ?? null;
        $dateDebut = $data['dateDebut'] ?? null;
        $dateFin = $data['dateFin'] ?? null;
        $justification = $data['justification'] ?? null;

        // Vérification des champs requis
        if (empty($type) || empty($dateDebut) || empty($dateFin)) {
            $_SESSION['error'] = "Veuillez remplir tous les champs obligatoires.";
            return $this->view->render($response, 'form-add-leave.php');
        }

        try {
            Conge::create($user->idEmploye, $type, $dateDebut, $dateFin, $justification);
            return $response->withHeader('Location', '/leaves-page')->withStatus(302);
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return $this->view->render($response, 'form-add-leave.php', [
                'type' => $type,
                'dateDebut' => $dateDebut,
                'dateFin' => $dateFin,
                'justification' => $justification
            ]);
        }
    }

    /**
     * Gère une demande de congé (validation ou refus par le manager).
     *
     * @param ServerRequestInterface $request Requête HTTP
     * @param ResponseInterface $response Réponse HTTP
     * @param array $args Paramètres contenant l'ID du congé
     * @return ResponseInterface
     */
    public function handleLeaveRequest(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $user = Employe::current();
        if (!$user || $user->getRole()->NomRole !== "Manager") {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
    
        $idConge = $args['id'];
        $data = $request->getParsedBody();
        $action = $data['action'] ?? '';
        $commentaire = $data['commentaire'] ?? '';
    
        $conge = Conge::fetchById($idConge);
    
        if ($action === 'valider') {
            $conge->validate($commentaire);
        } elseif ($action === 'refuser') {
            $conge->reject($commentaire);
        } else {
            $_SESSION['error'] = "Action invalide.";
        }
    
        return $response->withHeader('Location', '/leaves-page')->withStatus(302);
    }


}