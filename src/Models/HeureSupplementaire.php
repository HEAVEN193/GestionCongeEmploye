<?php

namespace Matteomcr\GestionCongeEmploye\Models;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Matteomcr\GestionCongeEmploye\Models\Database;
use Matteomcr\GestionCongeEmploye\Models\Role;
use Matteomcr\GestionCongeEmploye\Models\Departement;
use Matteomcr\GestionCongeEmploye\Models\Employe;






use Exception;
use PDO;

/**
 * Classe représentant un utilisateur de l'application.
 * 
 * Cette classe gère les informations relatives à un utilisateur, telles que son pseudo, son email,
 * et son mot de passe. Elle permet également de récupérer les statistiques de l'utilisateur,
 * vérifier si un email existe déjà, et gérer les connexions et créations de comptes.
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


    public static function fetchAll() :array
    {
        $statement = Database::connection()->prepare("SELECT * FROM RELEVEHSUPP");
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetchAll();
    }

    public static function fetchById(int $id) :HeureSupplementaire|false
    {
        $statement = Database::connection()
        ->prepare("SELECT * FROM RELEVEHSUPP WHERE idHeureSupp = :id");
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetch();
    }

    public static function fetchByEmployeId(int $id) :array
    {
        $statement = Database::connection()->prepare("SELECT * FROM RELEVEHSUPP WHERE idEmploye = :id");
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetchAll();
    }

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
    
    
    
    

    
    public function getEmploye() : Employe|null
    {
        if(!$this->employe){
            $this->employe = $this->idEmploye ? Employe::fetchById($this->idEmploye) : null;
        }
        return $this->employe;
    }

    public static function getTotalOvertimeByUserId($id){
        $statement = Database::connection()->prepare(
            "SELECT SUM(NbreHeure) AS heures
            FROM RELEVEHSUPP
            WHERE Statut = 'Valide'AND idEmploye = :idEmploye");
        $statement->execute(['idEmploye' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public static function getOvertimereRejectedByUserId($id){
        $statement = Database::connection()->prepare(
            "SELECT SUM(NbreHeure) AS heures
            FROM RELEVEHSUPP
            WHERE Statut = 'Refuse'AND idEmploye = :idEmploye");
        $statement->execute(['idEmploye' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public static function getOvertimeConvertedToLeaveByUserId($id){
        $statement = Database::connection()->prepare(
            "SELECT SUM(NbreHeure) AS heures
            FROM RELEVEHSUPP
            WHERE Statut = 'Valide'AND idEmploye = :idEmploye AND ConversionType= 'conge' ");
        $statement->execute(['idEmploye' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public static function getOvertimeConvertedToPaymentByUserId($id){
        $statement = Database::connection()->prepare(
            "SELECT SUM(NbreHeure) AS heures
            FROM RELEVEHSUPP
            WHERE Statut = 'Valide'AND idEmploye = :idEmploye AND ConversionType= 'paiement' ");
        $statement->execute(['idEmploye' => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    

    
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
