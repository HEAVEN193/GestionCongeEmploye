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


    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $userId = $args['id'];
        $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
        $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
        $pseudo = filter_input(INPUT_POST, 'pseudo', FILTER_SANITIZE_STRING);
        $birthdate = filter_input(INPUT_POST, 'birthdate', FILTER_SANITIZE_STRING);     
        $idCity = $_POST['city'] ?? 0;            
        $activities = $_POST['activity'] ?? []; 

        User::update($pseudo, $lastName, $firstName, $birthdate, $idCity, $userId);
        Practice::delete($userId);

        foreach ($activities as $activityId) {
            Practice::insert($userId, $activityId);
        }

        header('Location: /');
        exit;

    }
}