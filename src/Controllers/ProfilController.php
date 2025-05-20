<?php
namespace Matteomcr\GestionCongeEmploye\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Matteomcr\GestionCongeEmploye\Models\Employe;

use Exception;


class ProfilController extends BaseController{

       /**
     * Affiche la page de profil de l'utilisateur courant.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showProfilPage(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();
        if (!$user) return $response->withHeader('Location', '/login')->withStatus(302);
        return $this->view->render($response, 'profil.php');
    }

}