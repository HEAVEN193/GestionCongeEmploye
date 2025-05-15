<?php
namespace Matteomcr\GestionCongeEmploye\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Matteomcr\GestionCongeEmploye\Models\Employe;
use Matteomcr\GestionCongeEmploye\Models\Role;
use Matteomcr\GestionCongeEmploye\Models\Departement;
use Matteomcr\GestionCongeEmploye\Models\HeureSupplementaire;
use Matteomcr\GestionCongeEmploye\Models\Conge;







class HomeController extends BaseController {
    public function showHomePage(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        // Si l'utilisateur n'est pas connécté -> renvoie page login
        if(Employe::current())
            return $this->view->render($response, 'home-page.php');
        else    
            return $response->withHeader('Location', '/login')->withStatus(302);
    }
    public function showLoginPage(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $html = $this->view->fetch('login-page.php');
        $response->getBody()->write($html);
        return $response;
    }

    public function showAllEmployes(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();

        if (!$user) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
    
        // Récupération des filtres GET
        $queryParams = $request->getQueryParams();
        $filterDepartement = $queryParams['departement'] ?? null;
        $filterRole = $queryParams['role'] ?? null;
    
        // Si Employé simple
        if ($user->getRole()->NomRole === "Employe") {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
    
        // Si Manager
        if ($user->getRole()->NomRole === "Manager") {
            $employes = Employe::fetchByDepartement($user->getDepartement()->idDepartement);
            if ($filterRole) {
                $employes = array_filter($employes, fn($e) => $e->idRole == $filterRole);
            }
        }
    
        // Si Admin → peut filtrer
        if ($user->getRole()->NomRole === "Administrateur") {
            $employes = Employe::fetchAll();
        
            // Appliquer les filtres
            if ($filterDepartement) {
                $employes = array_filter($employes, fn($e) => $e->idDepartement == $filterDepartement);
            }
            if ($filterRole) {
                $employes = array_filter($employes, fn($e) => $e->idRole == $filterRole);
            }
        }
    
        return $this->view->render($response, 'employe-manage-page.php', [
            'employes' => $employes,
            'departementFiltre' => $filterDepartement,
            'roleFiltre' => $filterRole
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

    public function showAddDepartement(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();

        if(!$user){
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole != "Administrateur"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        $managers = Employe::fetchAllManager();
        return $this->view->render($response, 'form-add-departement.php', ['managers' => $managers]);
    }

    public function showUpdateDepartement(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();

        if(!$user){
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole != "Administrateur"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        $idDepartement = $args['id'];
        $departement = Departement::fetchById($idDepartement);
        $managers = Employe::fetchAllManager();
        
        return $this->view->render($response, 'form-update-departement.php', ['departement' => $departement, 'managers' => $managers]);
    }

    public function showAllDepartements(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
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

    public function showHeureSuppManage(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();

        if (!$user) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
    
    
    
        // Si Employé simple
        if ($user->getRole()->NomRole === "Employe") {
            $heuresSupp = HeureSupplementaire::fetchByEmployeId($user->idEmploye);
        }
    
        // Si Manager
        if ($user->getRole()->NomRole === "Manager") {
            $heuresSupp = HeureSupplementaire::fetchByIdDepartement($user->getDepartement()->idDepartement);
            
        }
    
        // Si Admin → peut filtrer
        if ($user->getRole()->NomRole === "Administrateur") {
            $heuresSupp = HeureSupplementaire::fetchAll();
        
           
        }
    
        return $this->view->render($response, 'heureSupp-manage-page.php', [
            'heuresSupp' => $heuresSupp,
        ]);   
        // $employe = Employe::current();
        // $role = $employe->getRole()->NomRole;

        

        // if ($role === 'Administrateur') {
        //     $heuresSupp = HeureSupplementaire::fetchAll(); // admin → tout
        // }elseif($role=== 'Manager'){
        //     $departement = $employe->getDepartement();
        //     $heuresSupp = HeureSupplementaire::fetchByIdDepartement($departement->idDepartement);
        // }
        // else {
        //     $heuresSupp = Employe::current()->getOvertimeReport(); // employé → que les siennes
        // }
        // return $this->view->render($response, 'heureSupp-manage-page.php', ['heuresSupp' => $heuresSupp]);
    }

    public function showCongeManage(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $employe = Employe::current();
        $role = $employe->getRole()->NomRole;

        if ($role === 'Administrateur') {
            $conges = Conge::fetchAll(); // admin → tout
        }elseif($role=== 'Manager'){
            $departement = $employe->getDepartement();
            $conges = Conge::fetchByIdDepartement($departement->idDepartement);
        }
        else {
            $conges = Conge::fetchByEmployeId($employe->idEmploye); // employé → que les siennes
        }
        return $this->view->render($response, 'conge-manage-page.php', ['conges' => $conges]);


        $conges = Conge::fetchByEmployeId($employe->idEmploye);
        return $this->view->render($response, 'conge-manage-page.php', ['conges' => $conges]);
    }

    public function showFormConge(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();

        if(!$user){
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole == "Administrateur"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        return $this->view->render($response, 'form-add-conge.php');
    }

    public function showFormHeureSupp(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();

        if(!$user){
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole == "Administrateur"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        return $this->view->render($response, 'form-heure-supp.php');
    }

    public function showProfilPage(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        return $this->view->render($response, 'profil.php');
    }

    public function showLayout(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        return $this->view->render($response, 'layout.php');
    }
}
