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


$app->post('/newEmploye', [GestionController::class, 'addEmploye']);
$app->get('/delete/{id:[0-9]+}', [GestionController::class, 'deleteEmploye']);






$app->get('/login', [HomeController::class, 'showLoginPage']);
$app->post('/login-attempt', [AuthController::class, 'login']);

$app->get('/logout', [AuthController::class, 'logout']);
