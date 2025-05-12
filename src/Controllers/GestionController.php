<?php

namespace Matteomcr\GestionCongeEmploye\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Matteomcr\GestionCongeEmploye\Models\Employe;
use Matteomcr\GestionCongeEmploye\Models\Role;
use Matteomcr\GestionCongeEmploye\Models\Departement;
use Matteomcr\GestionCongeEmploye\Models\HeureSupplementaire;


class GestionController extends BaseController {


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
            return $response->withHeader('Location', '/showEmploye')->withStatus(302);
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


    public function deleteEmploye(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $user = Employe::current();

        if(!$user){
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole != "Administrateur"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        
        $employeId = $args['id'];
        Employe::delete($employeId);
        header('Location: /showEmploye');
        exit;
    }


    public function updateEmploye(ServerRequestInterface $request, ResponseInterface $response, array $args) {
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
            return $response->withHeader('Location', '/showEmploye')->withStatus(302);
        }catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
    
            return $this->view->render($response, 'form-update-employe.php', [
                'employe' => Employe::fetchById($employeId),
                'roles' => Role::fetchAll(),
                'departements' => Departement::fetchAll()
            ]);
        }
    }

    public function addDepartement(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $idManager = ($_POST['manager'] === "0" || $_POST['manager'] === 0 || $_POST['manager'] == "null") ? null : $_POST['manager'];

        $departementToCreate = Departement::create($nom, $idManager);
        header('Location: /showDepartement');
        exit;
    }

    public function updateDepartement(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $idDepartement = $args['id'];
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $idManager = ($_POST['manager'] === "0" || $_POST['manager'] === 0 || $_POST['manager'] == "null") ? null : $_POST['manager'];

        Departement::update($idDepartement, $nom, $idManager);

        header('Location: /showDepartement');
        exit;
    }

    public function deleteDepartement(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $idDepartement = $args['id'];
        Departement::delete($idDepartement);
        header('Location: /showDepartement');
        exit;
    }

    public function reportHeureSupp(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $date = $_POST['date'] ?? 0;
        $heures = isset($_POST['heures']) ? (int)$_POST['heures'] : 0;
        $idEmploye = Employe::current()->idEmploye;
        $ratioConversion = $heures / 8; 
        $conversionType = $_POST['conversionType'] ?? [];


        $reportToCreate = HeureSupplementaire::create($date, $heures, $ratioConversion, $idEmploye, $conversionType);
        header('Location: /heuresupp-manage-page');
        exit;
    }

    public function validateOvertime(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $idHeureSupp = $args['id'];
        $heureSupp = HeureSupplementaire::fetchById($idHeureSupp);
        $heureSupp->validate();

        header('Location: /heuresupp-manage-page');
        exit;
    }

    public function rejectOvertime(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $idHeureSupp = $args['id'];
        $heureSupp = HeureSupplementaire::fetchById($idHeureSupp);
        $heureSupp->reject();

        header('Location: /heuresupp-manage-page');
        exit;
    }
}