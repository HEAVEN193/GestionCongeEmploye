<?php

use Matteomcr\GestionCongeEmploye\Controllers\HomeController;
use Matteomcr\GestionCongeEmploye\Controllers\AuthController;
use Matteomcr\GestionCongeEmploye\Controllers\GestionController;



use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/', [HomeController::class, 'showHomePage']);
$app->get('/layout', [HomeController::class, 'showLayout']);

$app->get('/showEmploye', [HomeController::class, 'showAllEmployes']);
$app->get('/form-add-employe', [HomeController::class, 'showAddEmploye']);
$app->get('/form-update-employe/{id:[0-9]+}', [HomeController::class, 'showUpdateEmploye']);

$app->get('/showDepartement', [HomeController::class, 'showAllDepartements']);
$app->get('/form-add-departement', [HomeController::class, 'showAddDepartement']);

$app->get('/form-update-departement/{id:[0-9]+}', [HomeController::class, 'showUpdateDepartement']);
$app->get('/heuresupp-manage-page', [HomeController::class, 'showHeureSuppManage']);
$app->get('/conge-manage-page', [HomeController::class, 'showCongeManage']);
$app->get('/form-conge', [HomeController::class, 'showFormConge']);

$app->post('/demande-conge', [GestionController::class, 'submitConge']);

$app->get('/form-heures-supp', [HomeController::class, 'showFormHeureSupp']);
$app->get('/profil', [HomeController::class, 'showProfilPage']);

$app->post('/newEmploye', [GestionController::class, 'addEmploye']);
$app->get('/deleteEmploye/{id:[0-9]+}', [GestionController::class, 'deleteEmploye']);
$app->post('/updateEmploye/{id:[0-9]+}', [GestionController::class, 'updateEmploye']);


$app->post('/newDepartement', [GestionController::class, 'addDepartement']);
$app->get('/deleteDepartement/{id:[0-9]+}', [GestionController::class, 'deleteDepartement']);
$app->post('/updateDepartement/{id:[0-9]+}', [GestionController::class, 'updateDepartement']);

$app->post('/heuresupp', [GestionController::class, 'reportHeureSupp']);

$app->post('/validerHeureSupp/{id:[0-9]+}', [GestionController::class, 'validateOvertime']);
$app->post('/refuserHeureSupp/{id:[0-9]+}', [GestionController::class, 'rejectOvertime']);


$app->get('/login', [HomeController::class, 'showLoginPage']);
$app->post('/login-attempt', [AuthController::class, 'login']);

$app->get('/logout', [AuthController::class, 'logout']);
