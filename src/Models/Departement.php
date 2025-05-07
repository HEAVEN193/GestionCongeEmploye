<?php

namespace Matteomcr\GestionCongeEmploye\Models;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Matteomcr\GestionCongeEmploye\Models\Database;

use PDO;

class Departement {
    public $idDepartement;
    public $NomDepartement;
    public $idManager;

    protected $manager = null;

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

    public function getManager() : Employe|null
    {
        if(!$this->manager){
            $this->manager = $this->idManager ? Employe::fetchById($this->idManager) : null;
        }
        return $this->manager;
    }

    public static function delete($idDepartement) {
        $pdo = Database::connection();
        $statement = $pdo->prepare("DELETE FROM DEPARTEMENT WHERE idDepartement = ?");
        $statement->execute([$idDepartement]);
    }

    public static function update($id, $nom, $idManager)
    {
        try {
            $pdo = Database::connection();
    
            $stmt = $pdo->prepare("UPDATE DEPARTEMENT SET
                NomDepartement = :nom,
                idManager = :idManager
                WHERE idDepartement = :id");
    
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':idManager', $idManager);

            $stmt->execute();
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Une erreur est survenue lors de la mise à jour du departements : " . $e->getMessage());
        }
    }

    public static function create($nom, $idManager)
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("INSERT INTO DEPARTEMENT (NomDepartement, idManager) VALUES (:nom, :idManager)");
    
            // Liaison des paramètress
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':idManager', $idManager);
    
            // Exécution
            $stmt->execute();
    
            return $pdo->lastInsertId();
        } catch (\Exception $e) {
            throw new \Exception("Une erreur est survenue lors de la création du departement : " . $e->getMessage());
        }
    }

}

?>