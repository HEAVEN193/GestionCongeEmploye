<?php

namespace Matteomcr\GestionCongeEmploye\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Matteomcr\GestionCongeEmploye\Models\Employe;
use Matteomcr\GestionCongeEmploye\Models\Role;
use Matteomcr\GestionCongeEmploye\Models\Departement;

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
}