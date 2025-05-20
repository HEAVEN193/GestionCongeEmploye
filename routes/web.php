<?php

use Matteomcr\GestionCongeEmploye\Controllers\HomeController;
use Matteomcr\GestionCongeEmploye\Controllers\EmployeController;
use Matteomcr\GestionCongeEmploye\Controllers\DepartementController;
use Matteomcr\GestionCongeEmploye\Controllers\LeaveController;
use Matteomcr\GestionCongeEmploye\Controllers\OvertimeController;
use Matteomcr\GestionCongeEmploye\Controllers\ProfilController;
use Matteomcr\GestionCongeEmploye\Controllers\AuthController;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// HOME
$app->get('/', [HomeController::class, 'showHomePage']);
$app->get('/layout', [HomeController::class, 'showLayout']);

// PROFIL
$app->get('/profil', [ProfilController::class, 'showProfilPage']);

// EMPLOYES
$app->get('/employes', [EmployeController::class, 'showEmployesPage']);
$app->get('/form-add-employe', [EmployeController::class, 'showAddEmploye']);
$app->get('/form-update-employe/{id:[0-9]+}', [EmployeController::class, 'showUpdateEmploye']);
$app->post('/newEmploye', [EmployeController::class, 'addEmploye']);
$app->get('/deleteEmploye/{id:[0-9]+}', [EmployeController::class, 'deleteEmploye']);
$app->post('/updateEmploye/{id:[0-9]+}', [EmployeController::class, 'updateEmploye']);

// DEPARTEMENTS
$app->get('/departments', [DepartementController::class, 'showDepartmentsPage']);
$app->get('/form-add-departement', [DepartementController::class, 'showAddDepartement']);
$app->get('/form-update-departement/{id:[0-9]+}', [DepartementController::class, 'showUpdateDepartement']);
$app->post('/newDepartement', [DepartementController::class, 'addDepartement']);
$app->get('/deleteDepartment/{id:[0-9]+}', [DepartementController::class, 'deleteDepartment']);
$app->post('/updateDepartement/{id:[0-9]+}', [DepartementController::class, 'updateDepartement']);

// CONGES
$app->get('/leaves-page', [LeaveController::class, 'showLeavePage']);
$app->get('/form-add-leave', [LeaveController::class, 'showFormLeave']);
$app->post('/leave-request', [LeaveController::class, 'submitLeave']);
$app->post('/handle-leave-request/{id}', [LeaveController::class, 'handleLeaveRequest']);

// HEURES SUPP
$app->get('/overtimes', [OvertimeController::class, 'showOvertimePage']);
$app->get('/form-overtime', [OvertimeController::class, 'showFormOvertime']);
$app->post('/heuresupp', [OvertimeController::class, 'reportOvertime']);
$app->post('/validerHeureSupp/{id:[0-9]+}', [OvertimeController::class, 'validateOvertime']);
$app->post('/refuserHeureSupp/{id:[0-9]+}', [OvertimeController::class, 'rejectOvertime']);


// AUTHENTIFICATION
$app->get('/login', [AuthController::class, 'showLoginPage']);
$app->post('/login-attempt', [AuthController::class, 'login']);
$app->get('/logout', [AuthController::class, 'logout']);
