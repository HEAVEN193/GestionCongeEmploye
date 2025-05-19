<?php

namespace Matteomcr\GestionCongeEmploye\Models;

use Matteomcr\GestionCongeEmploye\Models\Database;
use PDO;

/**
 * Classe représentant un rôle dans le système (ex. : employé, manager, administrateur).
 * Permet de récupérer les rôles depuis la base de données.
 */
class Role {
    public $idRole;
    public $NomRole;
    
    /**
     * Récupère tous les rôles disponibles.
     * @return Role[] Liste des rôles.
     */
    public static function fetchAll() {
        $statement = Database::connection()->prepare("SELECT * FROM ROLES");
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetchAll();
    }

    /**
     * Récupère un rôle spécifique par son identifiant.
     * @param int $id Identifiant du rôle.
     * @return Role|false Le rôle correspondant ou false s'il n'existe pas.
     */
    public static function fetchById(int $id): Role|false
    {
        $statement = Database::connection()->prepare("SELECT * FROM ROLES WHERE idRole = :id");
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetch();
    }

}

?>