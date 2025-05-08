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
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
        $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);  
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);     
        $dateEmbauche = $_POST['dateEmbauche'] ?? 0;            
        $statut = $_POST['statut'] ?? [];
        $idRole = $_POST['role'] ?? [];
        $idDepartement = $_POST['departement'] ?? [];

        $userToCreate = Employe::create($nom, $prenom, $pseudo, $password, $email, $dateEmbauche, $statut, $idRole, $idDepartement);
        header('Location: /showEmploye');
        exit;
    }


    public function deleteEmploye(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $employeId = $args['id'];
        Employe::delete($employeId);
        header('Location: /showEmploye');
        exit;
    }


    public function updateEmploye(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $employeId = $args['id'];
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
        $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);  
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);     
        $dateEmbauche = $_POST['dateEmbauche'] ?? 0;            
        $statut = $_POST['statut'] ?? [];
        $idRole = $_POST['role'] ?? [];
        $idDepartement = $_POST['departement'] ?? [];

        Employe::update($employeId, $nom, $prenom, $pseudo, $password, $email, $dateEmbauche, $statut, $idRole, $idDepartement);

        header('Location: /showEmploye');
        exit;
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