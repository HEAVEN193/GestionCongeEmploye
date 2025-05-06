<?php
namespace Matteomcr\GestionCongeEmploye\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Matteomcr\GestionCongeEmploye\Models\Employe;
use Matteomcr\GestionCongeEmploye\Models\Role;
use Matteomcr\GestionCongeEmploye\Models\Departement;





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
        $html = $this->view->fetch('login-page.php');
        $response->getBody()->write($html);
        return $response;
    }

    public function showAllEmployes(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $employes = Employe::fetchAll();
        return $this->view->render($response, 'employe-manage-page.php', ['employes' => $employes]);
    }

    public function showAddEmploye(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $roles = Role::fetchAll();
        $departements = Departement::fetchAll();
        return $this->view->render($response, 'form-add-employe.php', ['roles' => $roles, 'departements' => $departements]);
    }

    public function showLayout(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        return $this->view->render($response, 'layout.php');
    }
}
