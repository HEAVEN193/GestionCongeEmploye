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
    public $NbreJourDemande;

    /**
     * Crée une nouvelle demande de congé.
     */
    public static function create($idEmploye, $typeConge, $dateDebut, $dateFin, $justification = null)
    {
        if (empty($idEmploye) || empty($typeConge) || empty($dateDebut) || empty($dateFin)) {
            throw new Exception("Tous les champs obligatoires doivent être remplis.");
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


        $pdo = Database::connection();

        $stmt = $pdo->prepare("INSERT INTO CONGES (idEmploye, TypeConge, DateDebut, DateFin, Justification, NbreJourDemande) VALUES (:idEmploye, :type, :debut, :fin, :justification, :duree)");

        $stmt->bindParam(':idEmploye', $idEmploye);
        $stmt->bindParam(':type', $typeConge);
        $stmt->bindParam(':debut', $dateDebut);
        $stmt->bindParam(':fin', $dateFin);
        $stmt->bindParam(':justification', $justification);
        $stmt->bindParam(':duree', $duree);

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
     * Récupère les congés d'un employé.
     */
    public static function fetchByEmployeId($idEmploye): array
    {
        $stmt = Database::connection()->prepare("SELECT * FROM CONGES WHERE idEmploye = :id");
        $stmt->execute([':id' => $idEmploye]);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, static::class);
        return $stmt->fetchAll();
    }

    /**
     * Met à jour le statut d'un congé (validé/refusé).
     */
    public static function updateStatut($idConge, $statut): bool
    {
        $pdo = Database::connection();

        if (!in_array($statut, ['Valide', 'Refuse'])) {
            throw new Exception("Statut de congé invalide.");
        }

        $stmt = $pdo->prepare("UPDATE CONGES SET Statut = :statut WHERE idConge = :id");
        return $stmt->execute([
            ':statut' => $statut,
            ':id' => $idConge
        ]);
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

}
