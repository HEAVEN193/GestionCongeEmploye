<?php
namespace Matteomcr\GestionCongeEmploye\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Matteomcr\GestionCongeEmploye\Models\Employe;
use Matteomcr\GestionCongeEmploye\Models\Role;
use Matteomcr\GestionCongeEmploye\Models\Departement;
use Matteomcr\GestionCongeEmploye\Models\HeureSupplementaire;
use Matteomcr\GestionCongeEmploye\Models\Conge;
use Exception;
use DateTime;


class DepartementController extends BaseController{

       /**
     * Affiche la page d'ajout d'un département (réservé aux administrateurs).
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showAddDepartement(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();
        if (!$user || $user->getRole()->NomRole !== "Administrateur") {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        $managers = Employe::fetchAllManager();
        return $this->view->render($response, 'form-add-departement.php', ['managers' => $managers]);
    }

    /**
     * Affiche le formulaire de mise à jour d'un département (réservé aux administrateurs).
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showUpdateDepartement(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();
        if (!$user || $user->getRole()->NomRole !== "Administrateur") {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $departement = Departement::fetchById($args['id']);
        $managers = Employe::fetchAllManager();

        return $this->view->render($response, 'form-update-departement.php', ['departement' => $departement, 'managers' => $managers]);
    }

    /**
     * Affiche la liste de tous les départements (Admin et Manager).
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showDepartmentsPage(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();

        if(!$user){
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole == "Employe"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        $departements = Departement::fetchAll();
        return $this->view->render($response, 'departments-page.php', ['departements' => $departements]);
    }

    /**
     * Ajoute un département avec un nom et un manager optionnel.
     *
     * @param ServerRequestInterface $request Requête HTTP
     * @param ResponseInterface $response Réponse HTTP
     * @param array $args Paramètres de route
     * @return ResponseInterface
     */
    public function addDepartement(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $user = Employe::current();

        if (!$user) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole != "Administrateur"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $idManager = ($_POST['manager'] === "0" || $_POST['manager'] === 0 || $_POST['manager'] == "null") ? null : $_POST['manager'];

        $departementToCreate = Departement::create($nom, $idManager);
        header('Location: /departments');
        exit;
    }

    /**
     * Met à jour un département existant.
     *
     * @param ServerRequestInterface $request Requête HTTP
     * @param ResponseInterface $response Réponse HTTP
     * @param array $args Paramètres contenant l'ID du département
     * @return ResponseInterface
     */
    public function updateDepartement(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $user = Employe::current();

        if (!$user) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole != "Administrateur"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        $idDepartement = $args['id'];
        $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
        $idManager = ($_POST['manager'] === "0" || $_POST['manager'] === 0 || $_POST['manager'] == "null") ? null : $_POST['manager'];

        Departement::update($idDepartement, $nom, $idManager);

        header('Location: /departments');
        exit;
    }

    /**
     * Supprime un département selon son identifiant.
     *
     * @param ServerRequestInterface $request Requête HTTP
     * @param ResponseInterface $response Réponse HTTP
     * @param array $args Paramètres contenant l'ID du département
     * @return ResponseInterface
     */
    public function deleteDepartment(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $user = Employe::current();

        if (!$user) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        if($user->getRole()->NomRole != "Administrateur"){
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        
        $id = $args['id'];

        try {
            Departement::delete($id);
            $_SESSION['success'] = "Département supprimé avec succès.";
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        header('Location: /departments');
        exit;
    }


}