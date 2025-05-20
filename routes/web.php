<?php

use Matteomcr\GestionCongeEmploye\Controllers\HomeController;
use Matteomcr\GestionCongeEmploye\Controllers\AuthController;
use Matteomcr\GestionCongeEmploye\Controllers\GestionController;



use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/', [HomeController::class, 'showHomePage']);
$app->get('/layout', [HomeController::class, 'showLayout']);

$app->get('/employes', [HomeController::class, 'showEmployesPage']);
$app->get('/form-add-employe', [HomeController::class, 'showAddEmploye']);
$app->get('/form-update-employe/{id:[0-9]+}', [HomeController::class, 'showUpdateEmploye']);

$app->get('/departments', [HomeController::class, 'showDepartmentsPage']);
$app->get('/form-add-departement', [HomeController::class, 'showAddDepartement']);
$app->get('/form-update-departement/{id:[0-9]+}', [HomeController::class, 'showUpdateDepartement']);

$app->get('/overtimes', [HomeController::class, 'showOvertimePage']);
$app->get('/leaves-page', [HomeController::class, 'showLeavePage']);
$app->get('/form-add-leave', [HomeController::class, 'showFormLeave']);

$app->post('/leave-request', [GestionController::class, 'submitLeave']);

$app->get('/form-overtime', [HomeController::class, 'showFormOvertime']);
$app->get('/profil', [HomeController::class, 'showProfilPage']);

$app->post('/newEmploye', [GestionController::class, 'addEmploye']);
$app->get('/deleteEmploye/{id:[0-9]+}', [GestionController::class, 'deleteEmploye']);
$app->post('/updateEmploye/{id:[0-9]+}', [GestionController::class, 'updateEmploye']);


$app->post('/newDepartement', [GestionController::class, 'addDepartement']);
$app->get('/deleteDepartment/{id:[0-9]+}', [GestionController::class, 'deleteDepartment']);
$app->post('/updateDepartement/{id:[0-9]+}', [GestionController::class, 'updateDepartement']);

$app->post('/heuresupp', [GestionController::class, 'reportOvertime']);

$app->post('/validerHeureSupp/{id:[0-9]+}', [GestionController::class, 'validateOvertime']);
$app->post('/refuserHeureSupp/{id:[0-9]+}', [GestionController::class, 'rejectOvertime']);

$app->post('/validerConge/{id:[0-9]+}', [GestionController::class, 'approveLeave']);
$app->post('/refuserConge/{id:[0-9]+}', [GestionController::class, 'rejectLeave']);


$app->get('/login', [HomeController::class, 'showLoginPage']);
$app->post('/login-attempt', [AuthController::class, 'login']);

$app->get('/logout', [AuthController::class, 'logout']);
