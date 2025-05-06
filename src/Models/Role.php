<?php

namespace Matteomcr\GestionCongeEmploye\Models;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Matteomcr\GestionCongeEmploye\Models\Database;

class Role {
    public $idRole;
    public $NomRole;
    

    public static function fetchAll() {
        $statement = Database::connection()->prepare("SELECT * FROM ROLES");
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetchAll();
    }

    public static function fetchById(int $id): Role|false
    {
        $statement = Database::connection()->prepare("SELECT * FROM ROLES WHERE idRole = :id");
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetch();
    }

}

?>