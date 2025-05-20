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


class OvertimeController extends BaseController{

    /**
     * Affiche les heures supplémentaires selon le rôle (employé, manager, admin).
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showOvertimePage(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();
        if (!$user) return $response->withHeader('Location', '/login')->withStatus(302);

        $role = $user->getRole()->NomRole;
        $heuresSupp = match ($role) {
            'Employe' => HeureSupplementaire::fetchByEmployeId($user->idEmploye),
            'Manager' => HeureSupplementaire::fetchByIdDepartement($user->getDepartement()->idDepartement),
            'Administrateur' => HeureSupplementaire::fetchAll()
        };

        return $this->view->render($response, 'overtimes-page.php', [
            'heuresSupp' => $heuresSupp,
        ]);
    }

    /**
     * Affiche le formulaire de soumission d'heures supplémentaires.
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showFormOvertime(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();
        if (!$user || $user->getRole()->NomRole === 'Administrateur') {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        return $this->view->render($response, 'form-overtime.php');
    }

        /**
     * Déclare une heure supplémentaire en s'assurant de la validité de la date et du format.
     *
     * @param ServerRequestInterface $request Requête HTTP
     * @param ResponseInterface $response Réponse HTTP
     * @param array $args Paramètres de route
     * @return ResponseInterface
     */
    public function reportOvertime(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $date = $_POST['date'] ?? null;
        $heures = isset($_POST['heures']) ? (int)$_POST['heures'] : 0;
        $idEmploye = Employe::current()->idEmploye;
        $conversionType = $_POST['conversionType'] ?? null;

        $today = new \DateTime();
        $inputDate = new \DateTime($date);

        if ($inputDate > $today) {
            $_SESSION['error'] = "La date ne peut pas être dans le futur.";
            return $this->view->render($response, 'form-overtime.php');
        }

        // Vérifications
        if (empty($date) || empty($conversionType)) {
            $_SESSION['error'] = "Veuillez entrer une date, un nombre d'heures valide (> 0) et un type de conversion.";
            return $this->view->render($response, 'form-overtime.php');
        }

        $ratioConversion = $heures / 8;

    try {
        HeureSupplementaire::create($date, $heures, $ratioConversion, $idEmploye, $conversionType);
        return $response->withHeader('Location', '/overtimes')->withStatus(302);
    } catch (\Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        return $this->view->render($response, 'form-overtime.php');
    }
    }

    /**
     * Valide une heure supplémentaire (action du manager).
     *
     * @param ServerRequestInterface $request Requête HTTP
     * @param ResponseInterface $response Réponse HTTP
     * @param array $args Paramètres contenant l'ID de l'heure supp.
     * @return ResponseInterface
     */
    public function validateOvertime(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $user = Employe::current();

        if (!$user) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole != "Manager"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $idHeureSupp = $args['id'];

        $heureSupp = HeureSupplementaire::fetchById($idHeureSupp);
        if (!$heureSupp) {
            return $response->withHeader('Location', '/employes')->withStatus(302);
        }

        $heureSupp->validate();

        header('Location: /overtimes');
        exit;
    }

    /**
     * Refuse une heure supplémentaire (action du manager).
     *
     * @param ServerRequestInterface $request Requête HTTP
     * @param ResponseInterface $response Réponse HTTP
     * @param array $args Paramètres contenant l'ID de l'heure supp.
     * @return ResponseInterface
     */
    public function rejectOvertime(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $user = Employe::current();

        if (!$user) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole != "Manager"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $idHeureSupp = $args['id'];
        $heureSupp = HeureSupplementaire::fetchById($idHeureSupp);
        $heureSupp->reject();

        header('Location: /overtimes');
        exit;
    }




}