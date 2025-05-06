<?php
namespace Matteomcr\GestionCongeEmploye\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Matteomcr\GestionCongeEmploye\Models\Employe;




class HomeController extends BaseController {
    public function showHomePage(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        // Si l'utilisateur n'est pas connécté -> renvoie page login
        if(Employe::current())
            return $this->view->render($response, 'home-page.php');
        else    
            return $response->withHeader('Location', '/login')->withStatus(302);
    }
    public function showLoginPage(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        return $this->view->render($response, 'login-page.php');
    }
}
