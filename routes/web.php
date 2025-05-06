<?php

use Matteomcr\GestionCongeEmploye\Controllers\HomeController;
use Matteomcr\GestionCongeEmploye\Controllers\AuthController;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/', [HomeController::class, 'showHomePage']);

$app->get('/login', [HomeController::class, 'showLoginPage']);
$app->post('/login-attempt', [AuthController::class, 'login']);

$app->get('/logout', [AuthController::class, 'logout']);
