<?php

namespace Matteomcr\GestionCongeEmploye\Models;

use Matteomcr\GestionCongeEmploye\Models\Database;
use Matteomcr\GestionCongeEmploye\Models\Role;
use Matteomcr\GestionCongeEmploye\Models\Departement;
use Matteomcr\GestionCongeEmploye\Models\Employe;
use Exception;
use PDO;

/**
 * Classe représentant une heure supplémentaire déclarée par un employé.
 * Permet de créer, récupérer et valider ou refuser les heures supplémentaires.
 */
class HeureSupplementaire
{

    public $idHeureSupp;

    public $DateSoumission;

    public $NbreHeure;

    public $Statut;

    public $RatioConversion;

    public $idEmploye;

    public $ConversionType;

    protected $employe = null;


     /**
     * Récupère toutes les heures supplémentaires.
     * @return HeureSupplementaire[]
     */
    public static function fetchAll() :array
    {
        $statement = Database::connection()->prepare("SELECT * FROM RELEVEHSUPP");
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetchAll();
    }

    /**
     * Récupère une heure supplémentaire par son identifiant.
     * @param int $id
     * @return HeureSupplementaire|false
     */
    public static function fetchById(int $id) :HeureSupplementaire|false
    {
        $statement = Database::connection()
        ->prepare("SELECT * FROM RELEVEHSUPP WHERE idHeureSupp = :id");
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetch();
    }

    /**
     * Récupère toutes les heures supplémentaires d'un employé.
     * @param int $id Identifiant de l'employé.
     * @return HeureSupplementaire[]
     */
    public static function fetchByEmployeId(int $id) :array
    {
        $statement = Database::connection()->prepare("SELECT * FROM RELEVEHSUPP WHERE idEmploye = :id");
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetchAll();
    }

    /**
     * Récupère toutes les heures supplémentaires associées au département d'un employé.
     * @param int $id Identifiant de l'employé.
     * @return HeureSupplementaire[]
     */
    public static function fetchByIdDepartement(int $id) :array
    {
        $statement = Database::connection()->prepare("SELECT rs.*
            FROM RELEVEHSUPP rs
            JOIN EMPLOYES e ON rs.idEmploye = e.idEmploye
            WHERE e.idDepartement = (
                SELECT idDepartement
                FROM EMPLOYES
                WHERE idEmploye = :id
            )");
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetchAll();
    }

    /**
     * Crée une déclaration d'heure supplémentaire.
     * @param string $date Date de soumission.
     * @param float $NbreHeure Nombre d'heures soumises.
     * @param float $ratio Ratio de conversion appliqué.
     * @param int $idEmploye Identifiant de l'employé.
     * @param string $conversionType Type de conversion (paiement/congé).
     * @return int Identifiant de la déclaration créée.
     */
    public static function create($date, $NbreHeure, $ratio, $idEmploye, $conversionType)
    {
        try {
            if ($NbreHeure <= 0) {
                throw new \Exception("Le nombre d'heures supplémentaires doit être supérieur à 0.");
            }
    
            $pdo = Database::connection();
            $stmt = $pdo->prepare("INSERT INTO RELEVEHSUPP (DateSoumission, NbreHeure, RatioConversion, idEmploye, ConversionType)
                                    VALUES (:dateSoumission, :nbreHeure, :ratioConversion, :idEmploye, :conversionType)");
    
            // Liaison des paramètres
            $stmt->bindParam(':dateSoumission', $date);
            $stmt->bindParam(':nbreHeure', $NbreHeure);
            $stmt->bindParam(':ratioConversion', $ratio);
            $stmt->bindParam(':idEmploye', $idEmploye); 
            $stmt->bindParam(':conversionType', $conversionType); 
    
            // Exécution
            $stmt->execute();
    
            return $pdo->lastInsertId();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Retourne l'objet Employe associé à cette déclaration.
     * @return Employe|null
     */
    public function getEmploye() : Employe|null
    {
        if(!$this->employe){
            $this->employe = $this->idEmploye ? Employe::fetchById($this->idEmploye) : null;
        }
        return $this->employe;
    }

    /**
     * Calcule le total des heures supplémentaires validées d'un employé.
     * @param int $id Identifiant de l'employé.
     * @return array Résultat contenant la somme.
     */
    public static function getTotalOvertimeByUserId($id){
        $statement = Database::connection()->prepare(
            "SELECT SUM(NbreHeure) AS heures
            FROM RELEVEHSUPP
            WHERE Statut = 'Valide'AND idEmploye = :idEmploye");
        $statement->execute(['idEmploye' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Calcule le total des heures supplémentaires refusées d'un employé.
     * @param int $id Identifiant de l'employé.
     * @return array Résultat contenant la somme.
     */
    public static function getOvertimereRejectedByUserId($id){
        $statement = Database::connection()->prepare(
            "SELECT SUM(NbreHeure) AS heures
            FROM RELEVEHSUPP
            WHERE Statut = 'Refuse'AND idEmploye = :idEmploye");
        $statement->execute(['idEmploye' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Calcule le total des heures converties en congés.
     * @param int $id Identifiant de l'employé.
     * @return array Résultat contenant la somme.
     */
    public static function getOvertimeConvertedToLeaveByUserId($id){
        $statement = Database::connection()->prepare(
            "SELECT SUM(NbreHeure) AS heures
            FROM RELEVEHSUPP
            WHERE Statut = 'Valide'AND idEmploye = :idEmploye AND ConversionType= 'conge' ");
        $statement->execute(['idEmploye' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Calcule le total des heures converties en paiement.
     * @param int $id Identifiant de l'employé.
     * @return array Résultat contenant la somme.
     */
    public static function getOvertimeConvertedToPaymentByUserId($id){
        $statement = Database::connection()->prepare(
            "SELECT SUM(NbreHeure) AS heures
            FROM RELEVEHSUPP
            WHERE Statut = 'Valide'AND idEmploye = :idEmploye AND ConversionType= 'paiement' ");
        $statement->execute(['idEmploye' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Valide une déclaration d'heure supplémentaire.
     * Met à jour le statut et augmente le solde de congés si conversion.
     * @return bool Succès ou échec.
     * @throws Exception En cas d'erreur.
     */
    public function validate() {
        try {
            $pdo = Database::connection();
    
            // 1. Mettre à jour le statut à "Valide"
            $stmt = $pdo->prepare("UPDATE RELEVEHSUPP SET Statut = 'Valide' WHERE idHeureSupp = :id");
            $stmt->bindParam(':id', $this->idHeureSupp, PDO::PARAM_INT);
            $stmt->execute();
    
            // 2. Si conversion en congé, ajouter des jours au solde de l'employé
            if ($this->ConversionType == 'conge') {
                // Conversion : 8h = 1 jour 
                $joursAjoutes = $this->NbreHeure / 8.0;
    
                if ($joursAjoutes > 0) {
                    $stmt = $pdo->prepare("UPDATE EMPLOYES SET soldeCongeHeureSupp = soldeCongeHeureSupp + :jours WHERE idEmploye = :idEmploye");
                    $stmt->bindParam(':jours', $joursAjoutes, PDO::PARAM_INT);
                    $stmt->bindParam(':idEmploye', $this->idEmploye, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
    
            return true;
    
        } catch (\Exception $e) {
            throw new \Exception("Une erreur est survenue lors de la validation du relevé des heures supplémentaires : " . $e->getMessage());
        }
    }
    

    /**
     * Refuse une déclaration d'heure supplémentaire.
     * @return bool Succès ou échec.
     * @throws Exception En cas d'erreur.
     */
    public function reject(){
        try {
            $pdo = Database::connection();
    
            $stmt = $pdo->prepare("UPDATE RELEVEHSUPP SET Statut = 'Refuse'
                WHERE idHeureSupp = :id");
    
            $stmt->bindParam(':id', $this->idHeureSupp, PDO::PARAM_INT);

            $stmt->execute();
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Une erreur est survenue lors de la validation du relevé des heures supplémentaire : " . $e->getMessage());
        }
    }



    



  



   

}
