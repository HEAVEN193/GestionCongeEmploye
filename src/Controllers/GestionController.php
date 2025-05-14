<?php

namespace Matteomcr\GestionCongeEmploye\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Matteomcr\GestionCongeEmploye\Models\Employe;
use Matteomcr\GestionCongeEmploye\Models\Role;
use Matteomcr\GestionCongeEmploye\Models\Departement;
use Matteomcr\GestionCongeEmploye\Models\HeureSupplementaire;
use Matteomcr\GestionCongeEmploye\Models\Conge;



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
    
        if (!$user || $user->getRole()->NomRole !== "Administrateur") {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
    
        $employeId = $args['id'];

        // Vérifie si l'employé à supprimer existe
        $employeASupprimer = Employe::fetchById($employeId);
        if (!$employeASupprimer) {
            $_SESSION['error'] = "L'employé spécifié n'existe pas.";
            return $response->withHeader('Location', '/showEmploye')->withStatus(302);
        }
    
        try {
            Employe::delete($employeId);
            $_SESSION['success'] = "Employé supprimé avec succès.";
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
    
        return $response->withHeader('Location', '/showEmploye')->withStatus(302);
    }


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
        $user = Employe::current();

        if (!$user) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole != "Administrateur"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $idManager = ($_POST['manager'] === "0" || $_POST['manager'] === 0 || $_POST['manager'] == "null") ? null : $_POST['manager'];

        $departementToCreate = Departement::create($nom, $idManager);
        header('Location: /showDepartement');
        exit;
    }

    public function updateDepartement(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $user = Employe::current();

        if (!$user) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole != "Administrateur"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        $idDepartement = $args['id'];
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $idManager = ($_POST['manager'] === "0" || $_POST['manager'] === 0 || $_POST['manager'] == "null") ? null : $_POST['manager'];

        Departement::update($idDepartement, $nom, $idManager);

        header('Location: /showDepartement');
        exit;
    }

    public function deleteDepartement(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $user = Employe::current();

        if (!$user) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole != "Administrateur"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        
        $id = $args['id'];

        try {
            Departement::delete($id);
            $_SESSION['success'] = "Département supprimé avec succès.";
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        header('Location: /showDepartement');
        exit;
    }

    public function reportHeureSupp(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $date = $_POST['date'] ?? null;
        $heures = isset($_POST['heures']) ? (int)$_POST['heures'] : 0;
        $idEmploye = Employe::current()->idEmploye;
        $conversionType = $_POST['conversionType'] ?? null;

        $today = new \DateTime();
        $inputDate = new \DateTime($date);

        if ($inputDate > $today) {
            $_SESSION['error'] = "La date ne peut pas être dans le futur.";
            return $this->view->render($response, 'form-heure-supp.php');
        }

        // Vérifications
        if (empty($date) || empty($conversionType)) {
            $_SESSION['error'] = "Veuillez entrer une date, un nombre d'heures valide (> 0) et un type de conversion.";
            return $this->view->render($response, 'form-heure-supp.php');
        }

        $ratioConversion = $heures / 8;

    try {
        HeureSupplementaire::create($date, $heures, $ratioConversion, $idEmploye, $conversionType);
        return $response->withHeader('Location', '/heuresupp-manage-page')->withStatus(302);
    } catch (\Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        return $this->view->render($response, 'form-heure-supp.php');
    }
    }

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
            return $response->withHeader('Location', '/showEmploye')->withStatus(302);
        }

        $heureSupp->validate();

        header('Location: /heuresupp-manage-page');
        exit;
    }

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

        header('Location: /heuresupp-manage-page');
        exit;
    }

    public function submitConge(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
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
            return $this->view->render($response, 'form-add-conge.php');
        }

        try {
            Conge::create($user->idEmploye, $type, $dateDebut, $dateFin, $justification);
            return $response->withHeader('Location', '/conge-manage-page')->withStatus(302);
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return $this->view->render($response, 'form-add-conge.php', [
                'type' => $type,
                'dateDebut' => $dateDebut,
                'dateFin' => $dateFin,
                'justification' => $justification
            ]);
        }
    }

    public function approveLeave(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $user = Employe::current();

        if (!$user) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole != "Manager"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $idConge = $args['id'];
        $conge = Conge::fetchById($idConge);
        $conge->validate();
        header('Location: /conge-manage-page');
        exit;
    }

    public function rejectLeave(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $user = Employe::current();

        if (!$user) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole != "Manager"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        
        $idConge = $args['id'];
        $conge = Conge::fetchById($idConge);
        $conge->validate();
        header('Location: /conge-manage-page');
        exit;
    }


}