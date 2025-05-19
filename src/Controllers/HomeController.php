<?php
namespace Matteomcr\GestionCongeEmploye\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Matteomcr\GestionCongeEmploye\Models\Employe;
use Matteomcr\GestionCongeEmploye\Models\Role;
use Matteomcr\GestionCongeEmploye\Models\Departement;
use Matteomcr\GestionCongeEmploye\Models\HeureSupplementaire;
use Matteomcr\GestionCongeEmploye\Models\Conge;
use DateTime;





class HomeController extends BaseController {

    /*------------------------------------------------*/
    /*---------------- TABLEAUX DE BORD ---------------*/
    /*------------------------------------------------*/

     /**
     * Affiche la page d'accueil avec les congés selon le rôle de l'utilisateur.
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showHomePage(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();
        if (!$user) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
    
        $query = $request->getQueryParams();
        $deptFilter = $query['departement'] ?? null;
    
        // Récupération initiale des congés selon le rôle
        $conges = match ($user->getRole()->NomRole) {
            'Employe' => Conge::fetchByEmployeId($user->idEmploye),
            'Manager' => Conge::fetchByDepartementId($user->getDepartement()->idDepartement),
            'Administrateur' => Conge::fetchAll(),
        };
    
        // Filtrage manuel si admin et un filtre est défini
        if ($user->getRole()->NomRole === 'Administrateur' && $deptFilter) {
            $conges = array_filter($conges, fn($c) => $c->getEmploye()->idDepartement == $deptFilter);
        }
    
        // Génération des événements
        $events = [];
        foreach ($conges as $conge) {
            $employe = $conge->getEmploye();
            foreach ($this->getDateRange($conge->DateDebut, $conge->DateFin) as $date) {
                $events[] = [
                    'date' => $date,
                    'statut' => $conge->Statut,
                    'title' => "Congé " . $employe->Pseudo,
                    'type' => "conge"
                ];
            }
        }
    
        return $this->view->render($response, 'home-page.php', [
            'eventsFromPHP' => $events,
            'departementFiltre' => $deptFilter
        ]);
    }

    /*------------------------------------------------*/
    /*------------------- LOGIN PAGE -----------------*/
    /*------------------------------------------------*/
    /**
     * Affiche la page de connexion (sans layout).
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showLoginPage(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $html = $this->view->fetch('login-page.php');
        $response->getBody()->write($html);
        return $response;
    }

    /*------------------------------------------------*/
    /*------------------- EMPLOYES -------------------*/
    /*------------------------------------------------*/
    /**
     * Affiche la liste des employés avec filtres par rôle et département.
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showEmployesPage(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();
        if (!$user) return $response->withHeader('Location', '/login')->withStatus(302);
        if ($user->getRole()->NomRole === 'Employe') return $response->withHeader('Location', '/')->withStatus(302);

        $query = $request->getQueryParams();
        $roleFilter = $query['role'] ?? null;
        $deptFilter = $query['departement'] ?? null;

        $employes = match ($user->getRole()->NomRole) {
            'Manager' => array_filter(
                Employe::fetchByDepartement($user->getDepartement()->idDepartement),
                fn($e) => !$roleFilter || $e->idRole == $roleFilter
            ),
            'Administrateur' => array_filter(
                Employe::fetchAll(),
                fn($e) => (!$deptFilter || $e->idDepartement == $deptFilter) && (!$roleFilter || $e->idRole == $roleFilter)
            )
        };

        return $this->view->render($response, 'employe-manage-page.php', [
            'employes' => $employes,
            'departementFiltre' => $deptFilter,
            'roleFiltre' => $roleFilter
        ]);
    }

    public function showAddEmploye(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();

        if(!$user){
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole != "Administrateur"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        $roles = Role::fetchAll();
        $departements = Departement::fetchAll();
        return $this->view->render($response, 'form-add-employe.php', ['roles' => $roles, 'departements' => $departements]);
    }

    public function showUpdateEmploye(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        
        $user = Employe::current();

        if(!$user){
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole != "Administrateur"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $employeId = $args['id'];
        $employe = Employe::fetchById($employeId);
        $roles = Role::fetchAll();
        $departements = Departement::fetchAll();
        return $this->view->render($response, 'form-update-employe.php', ['employe' => $employe, 'roles'=> $roles, 'departements' => $departements]);
    }

    /*------------------------------------------------*/
    /*------------------- DEPARTEMENT ----------------*/
    /*------------------------------------------------*/

    /**
     * Affiche la page d'ajout d'un département (réservé aux administrateurs).
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showAddDepartement(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();
        if (!$user || $user->getRole()->NomRole !== "Administrateur") {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        $managers = Employe::fetchAllManager();
        return $this->view->render($response, 'form-add-departement.php', ['managers' => $managers]);
    }

    /**
     * Affiche le formulaire de mise à jour d'un département (réservé aux administrateurs).
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showUpdateDepartement(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();
        if (!$user || $user->getRole()->NomRole !== "Administrateur") {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $departement = Departement::fetchById($args['id']);
        $managers = Employe::fetchAllManager();

        return $this->view->render($response, 'form-update-departement.php', ['departement' => $departement, 'managers' => $managers]);
    }

    /**
     * Affiche la liste de tous les départements (Admin et Manager).
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showDepartementsPage(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();

        if(!$user){
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole == "Employe"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        $departements = Departement::fetchAll();
        return $this->view->render($response, 'departement-manage-page.php', ['departements' => $departements]);
    }

    /*------------------------------------------------*/
    /*-------------- HEURE SUPPLÉMENTAIRE ------------*/
    /*------------------------------------------------*/

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

        return $this->view->render($response, 'heureSupp-manage-page.php', [
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
        return $this->view->render($response, 'form-heure-supp.php');
    }

    /*------------------------------------------------*/
    /*--------------------- CONGES ------------------*/
    /*------------------------------------------------*/
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
        return $this->view->render($response, 'conge-manage-page.php', [
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
        return $this->view->render($response, 'form-add-conge.php');
    }

    /*------------------------------------------------*/
    /*--------------------- PROFILE ------------------*/
    /*------------------------------------------------*/
    /**
     * Affiche la page de profil de l'utilisateur courant.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showProfilPage(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();
        if (!$user) return $response->withHeader('Location', '/login')->withStatus(302);
        return $this->view->render($response, 'profil.php');
    }

    /*------------------------------------------------*/
    /*--------------------- LAYOUT ------------------*/
    /*------------------------------------------------*/
    /**
     * Affiche le layout de base (non protégé).
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showLayout(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        return $this->view->render($response, 'layout.php');
    }

    /*------------------------------------------------*/
    /*-------------------- UTILITIES ------------------*/
    /*------------------------------------------------*/
    /**
     * Génère un tableau de dates entre deux dates.
     *
     * @param string $start Date de début au format Y-m-d.
     * @param string $end Date de fin au format Y-m-d.
     * @return array Liste des dates entre les deux bornes (incluses).
     */
    private function getDateRange(string $start, string $end): array {
        $dates = [];
        $current = new DateTime($start);
        $end = new DateTime($end);
    
        while ($current <= $end) {
            $dates[] = $current->format('Y-m-d');
            $current->modify('+1 day');
        }
    
        return $dates;
    }
}
