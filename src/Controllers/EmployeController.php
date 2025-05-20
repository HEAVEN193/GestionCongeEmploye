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


class EmployeController extends BaseController{

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

        return $this->view->render($response, 'employes-page.php', [
            'employes' => $employes,
            'departementFiltre' => $deptFilter,
            'roleFiltre' => $roleFilter
        ]);
    }

     /**
     * Affiche le formulaire d'ajout d'un nouvel employé.
     *
     * @param ServerRequestInterface $request La requête HTTP entrante.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @param array $args Paramètres supplémentaires passés dans la route (non utilisés ici).
     * @return ResponseInterface La réponse contenant le rendu de la vue du formulaire.
     */
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

    /**
     * Affiche le formulaire de mise à jour d'un employé existant.
     *
     * @param ServerRequestInterface $request La requête HTTP entrante.
     * @param ResponseInterface $response La réponse HTTP à retourner.
     * @param array $args Paramètres de la route, contenant obligatoirement l'identifiant de l'employé ('id').
     * @return ResponseInterface La réponse contenant le rendu de la vue du formulaire de mise à jour.
     */
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

     /**
     * Ajoute un employé après vérification des champs requis.
     *
     * @param ServerRequestInterface $request Requête HTTP entrante
     * @param ResponseInterface $response Réponse HTTP à retourner
     * @param array $args Paramètres de route
     * @return ResponseInterface
     */
    public function addEmploye(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        // Récupération et nettoyage des champs
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $dateEmbauche = $_POST['dateEmbauche'] ?? null;
        $statut = $_POST['statut'] ?? null;
        $idRole = $_POST['role'] ?? null;
        $idDepartement = $_POST['departement'] ?? null;

           // Vérification des champs obligatoires
        if (
        empty($nom) || empty($prenom) || empty($pseudo) || empty($password)
        || empty($email) || empty($dateEmbauche) || empty($statut)
        || empty($idRole) || empty($idDepartement)
        ) {
            $_SESSION['error'] = "Veuillez remplir tous les champs obligatoires.";
            return $this->view->render($response, 'form-add-employe.php', [
                'nom' => $nom,
                'prenom' => $prenom,
                'pseudo' => $pseudo,
                'dateEmbauche' => $dateEmbauche,
                'email' => $email,
                'roles' => Role::fetchAll(),
                'departements' => Departement::fetchAll()
            ]);
        }


        try {
            Employe::create($nom, $prenom, $pseudo, $password, $email, $dateEmbauche, $statut, $idRole, $idDepartement);
            $_SESSION['success'] = "Employé ajouté avec succès.";
            return $response->withHeader('Location', '/employes')->withStatus(302);
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return $this->view->render($response, 'form-add-employe.php', [
                'nom' => $nom,
                'prenom' => $prenom,
                'pseudo' => $pseudo,
                'dateEmbauche' => $dateEmbauche,
                'email' => $email,
                'roles' => Role::fetchAll(),
                'departements' => Departement::fetchAll()
            ]);
        }
    }


    /**
     * Supprime un employé en fonction de son identifiant.
     *
     * @param ServerRequestInterface $request Requête HTTP
     * @param ResponseInterface $response Réponse HTTP
     * @param array $args Paramètres contenant l'ID de l'employé
     * @return ResponseInterface
     */
    public function deleteEmploye(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $user = Employe::current();
    
        if (!$user || $user->getRole()->NomRole !== "Administrateur") {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
    
        $employeId = $args['id'];

        // Vérifie si l'employé à supprimer existe
        $employeASupprimer = Employe::fetchById($employeId);
        if (!$employeASupprimer) {
            $_SESSION['error'] = "L'employé spécifié n'existe pas.";
            return $response->withHeader('Location', '/employes')->withStatus(302);
        }
    
        try {
            Employe::delete($employeId);
            $_SESSION['success'] = "Employé supprimé avec succès.";
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
    
        return $response->withHeader('Location', '/employes')->withStatus(302);
    }

    /**
     * Met à jour les informations d'un employé.
     *
     * @param ServerRequestInterface $request Requête HTTP
     * @param ResponseInterface $response Réponse HTTP
     * @param array $args Paramètres contenant l'ID de l'employé
     * @return ResponseInterface
     */
    public function updateEmploye(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $user = Employe::current();

        if (!$user) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole != "Administrateur"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $employeId = $args['id'];

        // Nettoyage des champs modifiables uniquement
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
        $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_STRING);
        $dateEmbauche = $_POST['dateEmbauche'] ?? null;
        $statut = $_POST['statut'] ?? null;
        $idRole = $_POST['role'] ?? null;
        $idDepartement = $_POST['departement'] ?? null;

        try {
            Employe::update($employeId, $nom, $prenom, $pseudo, $dateEmbauche, $statut, $idRole, $idDepartement);
            return $response->withHeader('Location', '/employes')->withStatus(302);
        }catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
    
            return $this->view->render($response, 'form-update-employe.php', [
                'employe' => Employe::fetchById($employeId),
                'roles' => Role::fetchAll(),
                'departements' => Departement::fetchAll()
            ]);
        }
    }

}