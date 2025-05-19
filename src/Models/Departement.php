<?php

namespace Matteomcr\GestionCongeEmploye\Models;

use Matteomcr\GestionCongeEmploye\Models\Database;
use PDO;

/**
 * Classe représentant un département au sein de l'entreprise.
 * Gère la création, mise à jour, suppression et récupération des départements ainsi que leur manager associé.
 */
class Departement {

    public $idDepartement;

    public $NomDepartement;

    public $idManager;
    
    protected $manager = null;

    /**
     * Récupère tous les départements.
     * @return Departement[]
     */
    public static function fetchAll() {
        $statement = Database::connection()->prepare("SELECT * FROM DEPARTEMENT");
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetchAll();
    }

    /**
     * Récupère un département par son ID.
     * @param int $id
     * @return Departement|false
     */
    public static function fetchById(int $id): Departement|false
    {
        $statement = Database::connection()->prepare("SELECT * FROM DEPARTEMENT WHERE idDepartement = :id");
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetch();
    }

    /**
     * Récupère l'employé manager du département s'il existe.
     * @return Employe|null
     */
    public function getManager() : Employe|null
    {
        if(!$this->manager){
            $this->manager = $this->idManager ? Employe::fetchById($this->idManager) : null;
        }
        return $this->manager;
    }

    /**
     * Supprime un département s'il n'est plus utilisé.
     * @param int $idDepartement
     * @throws \Exception
     */
    public static function delete($idDepartement) {
        $pdo = Database::connection();

        // Vérifie s'il existe des employés liés à ce département
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM EMPLOYES WHERE idDepartement = :id");
        $stmt->execute([':id' => $idDepartement]);

        if ($stmt->fetchColumn() > 0) {
            throw new \Exception("Impossible de supprimer ce département : des employés y sont encore assignés.");
        }

        // Vérifie s'il est assigné comme manager dans un autre département
        $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM DEPARTEMENT WHERE idDepartement = :id AND idManager IS NOT NULL");
        $stmt2->execute([':id' => $idDepartement]);

        if ($stmt2->fetchColumn() > 0) {
            throw new \Exception("Ce département est encore managé. Veuillez d'abord retirer le manager.");
        }
        // Suppression si tout est OK
        $stmt = $pdo->prepare("DELETE FROM DEPARTEMENT WHERE idDepartement = :id");
        $stmt->execute([':id' => $idDepartement]);
    }

    /**
     * Met à jour les informations d'un département.
     * @param int $id
     * @param string $nom
     * @param int|null $idManager
     * @return bool
     * @throws \Exception
     */
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

    /**
     * Crée un nouveau département.
     * @param string $nom
     * @param int|null $idManager
     * @return int ID du département créé
     * @throws \Exception
     */
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