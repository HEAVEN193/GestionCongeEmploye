<?php
namespace Matteomcr\GestionCongeEmploye\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Matteomcr\GestionCongeEmploye\Models\Employe;

use Exception;


class AuthController extends BaseController{

    /**
     * Connecte un Employe.
     * 
     * @param ServerRequestInterface $request La requête HTTP.
     * @param ResponseInterface $response La réponse HTTP.
     * @param array $args Les arguments de la route.
     * 
     * @return ResponseInterface La réponse HTTP.
     */

    public function login(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
         // Récupère les données entrées par l'utilisateur
         $data = $request->getParsedBody();
         $email = filter_var($data['email'] ?? null, FILTER_SANITIZE_EMAIL);
         $password = filter_var($data['password'] ?? null, FILTER_SANITIZE_STRING);
 
         // Si les informations ne sont pas complètes
         if (empty($email) || empty($password)) {
             $_SESSION['error'] = "Veuillez remplir tous les champs.";
             return $this->view->render($response, 'login-page.php');
         }
 
         // Tentative d'authentification
         try {
             $user = Employe::login($email, $password);
             if ($user) {
                 $_SESSION['user'] = $user['Email'];
                 return $this->view->render($response, 'home-page.php');
             }
         } catch (Exception $e) {
             $_SESSION['error'] = $e->getMessage();
             $html = $this->view->fetch('login-page.php');
            $response->getBody()->write($html);
            return $response;
         }

    }

    /**
     * Déconnecte un utilisateur.
     * 
     * @return void
     */
    public function logout(): void
    {
        if (isset($_SESSION['user'])) {
            $_SESSION = [];

            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            session_destroy();
        }
        header('Location: /');
        exit;
    }

    


}