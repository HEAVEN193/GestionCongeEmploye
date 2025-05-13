<?php

namespace Matteomcr\GestionCongeEmploye\Models;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Matteomcr\GestionCongeEmploye\Models\Database;
use Matteomcr\GestionCongeEmploye\Models\Role;
use Matteomcr\GestionCongeEmploye\Models\Departement;
use Matteomcr\GestionCongeEmploye\Models\Employe;
use Matteomcr\GestionCongeEmploye\Models\HeureSupplementaire;







use Exception;
use PDO;

/**
 * Classe représentant un utilisateur de l'application.
 * 
 * Cette classe gère les informations relatives à un utilisateur, telles que son pseudo, son email,
 * et son mot de passe. Elle permet également de récupérer les statistiques de l'utilisateur,
 * vérifier si un email existe déjà, et gérer les connexions et créations de comptes.
 */
class Conge
{
    public $idConge;
    public $idEmploye;
    public $TypeConge;
    public $DateDebut;
    public $DateFin;
    public $Justification;
    public $Statut;

    protected $employe = null;


    /**
     * Crée une nouvelle demande de congé.
     */
    public static function create($idEmploye, $typeConge, $dateDebut, $dateFin, $justification = null)
    {
        if (empty($idEmploye) || empty($typeConge) || empty($dateDebut) || empty($dateFin)) {
            throw new Exception("Tous les champs obligatoires doivent être remplis.");
        }
        $employe = Employe::fetchById($idEmploye);

        if (!$employe) {
            throw new \Exception("Employé introuvable.");
        }

        $today = new \DateTime();
        $start = new \DateTime($dateDebut);
        $end = new \DateTime($dateFin);

        if ($dateFin < $dateDebut) {
            throw new Exception("La date de fin ne peut pas être antérieure à la date de début.");
        }

        if ($start < $today) {
            throw new Exception("La date de début du congé doit être ultérieure à aujourd'hui.");
        }

        if ($end < $start) {
            throw new Exception("La date de fin ne peut pas être antérieure à la date de début.");
        }

        // Calcul de la durée en jours
        $interval = $start->diff($end);
        $duree = $interval->days + 1;

        if ($typeConge === 'conversion') {
            if ($employe->SoldeCongeHeureSupp < $duree) {
                throw new Exception("Vous n'avez pas assez de jours issus des heures supplémentaires.");
            }
        } elseif ($typeConge === 'vacances') {
            if ($employe->SoldeConge < $duree) {
                throw new Exception("Vous n'avez pas assez de jours de congé classiques.");
            }
        } else {
            throw new Exception("Type de congé invalide.");
        }



        $pdo = Database::connection();

        $stmt = $pdo->prepare("INSERT INTO CONGES (idEmploye, TypeConge, DateDebut, DateFin, Justification) VALUES (:idEmploye, :type, :debut, :fin, :justification)");

        $stmt->bindParam(':idEmploye', $idEmploye);
        $stmt->bindParam(':type', $typeConge);
        $stmt->bindParam(':debut', $dateDebut);
        $stmt->bindParam(':fin', $dateFin);
        $stmt->bindParam(':justification', $justification);

        $stmt->execute();

        return $pdo->lastInsertId();
    }

    /**
     * Récupère tous les congés.
     */
    public static function fetchAll(): array
    {
        $stmt = Database::connection()->prepare("SELECT * FROM CONGES");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, static::class);
        return $stmt->fetchAll();
    }

     /**
     * Récupère un congé par son ID.
     */
    public static function fetchById(int $id): Conge|false
    {
        $stmt = Database::connection()->prepare("SELECT * FROM CONGES WHERE idConge = :id");
        $stmt->execute([':id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, static::class);
        return $stmt->fetch();
    }

    /**
     * Récupère les congés d'un employé.
     */
    public static function fetchByEmployeId($idEmploye): array
    {
        $stmt = Database::connection()->prepare("SELECT * FROM CONGES WHERE idEmploye = :id");
        $stmt->execute([':id' => $idEmploye]);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, static::class);
        return $stmt->fetchAll();
    }

    public static function fetchByIdDepartement(int $idEmploye) : array
    {
        $stmt = Database::connection()->prepare("
            SELECT c.*
            FROM CONGES c
            JOIN EMPLOYES e ON c.idEmploye = e.idEmploye
            WHERE e.idDepartement = (
                SELECT idDepartement
                FROM EMPLOYES
                WHERE idEmploye = :id
            )
        ");
        $stmt->execute([':id' => $idEmploye]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $stmt->fetchAll();
    }

    public function validate(): bool
    {
        try {
            $pdo = Database::connection();

            // 1. Mettre à jour le statut du congé
            $stmt = $pdo->prepare("UPDATE CONGES SET Statut = 'Valide' WHERE idConge = :id");
            $stmt->execute([':id' => $this->idConge]);

            // 2. Déduire le bon solde selon le type
            $champ = null;

            if ($this->TypeConge === 'conversion') {
                $champ = 'soldeCongeHeureSupp';
            } elseif ($this->TypeConge === 'vacances') {
                $champ = 'soldeConge';
            } else {
                throw new \Exception("Type de congé invalide.");
            }

            $stmt = $pdo->prepare("
                UPDATE EMPLOYES
                SET $champ = $champ - :duree
                WHERE idEmploye = :idEmploye
            ");

            $duree = $this->getDuree(); 

            $stmt->bindParam(':duree', $duree);
            $stmt->bindParam(':idEmploye', $this->idEmploye);
            $stmt->execute();

            return true;

        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la validation du congé : " . $e->getMessage());
        }
    }

    public function reject(): bool
    {
        try {
            $pdo = Database::connection();

            $stmt = $pdo->prepare("UPDATE CONGES SET Statut = 'Refuse' WHERE idConge = :id");
            $stmt->bindParam(':id', $this->idConge, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getEmploye() : Employe|null
    {
        if(!$this->employe){
            $this->employe = $this->idEmploye ? Employe::fetchById($this->idEmploye) : null;
        }
        return $this->employe;
    }

   

    public function getEtat(): string
    {
        $today = new \DateTime();
        $debut = new \DateTime($this->DateDebut);
        $fin = new \DateTime($this->DateFin);

        if ($today < $debut) {
            return "a venir";
        } elseif ($today > $fin) {
            return "passe";
        } else {
            return "en cours";
        }
    }

    public function getDuree(): int {
        $start = new \DateTime($this->DateDebut);
        $end = new \DateTime($this->DateFin);
        return $start->diff($end)->days + 1;
    }
    
    public function getJoursRestants(): int {
        $today = new \DateTime();
        $start = new \DateTime($this->DateDebut);
        return max(0, $today->diff($start)->days);
    }

    public static function countPending(): int
    {
        $stmt = Database::connection()->prepare("SELECT COUNT(*) FROM CONGES WHERE Statut = 'en_attente'");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public static function countApproved(): int
    {
        $stmt = Database::connection()->prepare("SELECT COUNT(*) FROM CONGES WHERE Statut = 'valide'");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public static function countTotal(): int
    {
        $stmt = Database::connection()->prepare("SELECT COUNT(*) FROM CONGES");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

}
