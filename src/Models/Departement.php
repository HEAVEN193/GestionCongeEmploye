<?php

namespace Matteomcr\GestionCongeEmploye\Models;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Matteomcr\GestionCongeEmploye\Models\Database;

class Departement {
    public $idDepartement;
    public $NomDepartement;
    

    public static function fetchAll() {
        $statement = Database::connection()->prepare("SELECT * FROM DEPARTEMENT");
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetchAll();
    }

    public static function fetchById(int $id): Departement|false
    {
        $statement = Database::connection()->prepare("SELECT * FROM DEPARTEMENT WHERE idDepartement = :id");
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetch();
    }

}

?>