<?php
namespace Matteomcr\GestionCongeEmploye\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Matteomcr\GestionCongeEmploye\Models\Employe;
use Matteomcr\GestionCongeEmploye\Models\Role;
use Matteomcr\GestionCongeEmploye\Models\Departement;
use Matteomcr\GestionCongeEmploye\Models\HeureSupplementaire;
use Matteomcr\GestionCongeEmploye\Models\Conge;
use DateTime;





class HomeController extends BaseController {

     /**
     * Affiche la page d'accueil avec les congés selon le rôle de l'utilisateur.
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showHomePage(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        $user = Employe::current();
        if (!$user) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
    
        $query = $request->getQueryParams();
        $deptFilter = $query['departement'] ?? null;
        $employeFilter = $query['employe'] ?? null;

    
        // Récupération initiale des congés selon le rôle
        $conges = match ($user->getRole()->NomRole) {
            'Employe' => Conge::fetchByEmployeId($user->idEmploye),
            'Manager' => Conge::fetchByDepartementId($user->getDepartement()->idDepartement),
            'Administrateur' => Conge::fetchAll(),
        };
    
        // Filtrage manuel si admin et un filtre est défini
        if ($user->getRole()->NomRole === 'Administrateur' && $deptFilter) {
            $conges = array_filter($conges, fn($c) => $c->getEmploye()->idDepartement == $deptFilter);
        }

        if ($user->getRole()->NomRole === 'Administrateur' && $employeFilter) {
            $conges = array_filter($conges, function ($conge) use ($employeFilter) {
                return $conge->getEmploye()->idEmploye == $employeFilter;
            });
        }
    
        // Génération des événements
        $events = [];
        foreach ($conges as $conge) {
            $employe = $conge->getEmploye();
            foreach ($this->getDateRange($conge->DateDebut, $conge->DateFin) as $date) {
                $events[] = [
                    'date' => $date,
                    'statut' => $conge->Statut,
                    'title' => "Congé " . $employe->Pseudo,
                    'type' => "conge"
                ];
            }
        }
    
        return $this->view->render($response, 'home-page.php', [
            'eventsFromPHP' => $events,
            'departementFiltre' => $deptFilter,
            'employeFiltre' => $employeFilter
        ]);
    }

  


    /**
     * Affiche le layout de base (non protégé).
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function showLayout(ServerRequestInterface $request, ResponseInterface $response, array $args) : ResponseInterface
    {
        return $this->view->render($response, 'layout.php');
    }

    /*------------------------------------------------*/
    /*-------------------- UTILITIES ------------------*/
    /*------------------------------------------------*/
    /**
     * Génère un tableau de dates entre deux dates.
     *
     * @param string $start Date de début au format Y-m-d.
     * @param string $end Date de fin au format Y-m-d.
     * @return array Liste des dates entre les deux bornes (incluses).
     */
    private function getDateRange(string $start, string $end): array {
        $dates = [];
        $current = new DateTime($start);
        $end = new DateTime($end);
    
        while ($current <= $end) {
            $dates[] = $current->format('Y-m-d');
            $current->modify('+1 day');
        }
    
        return $dates;
    }
}
