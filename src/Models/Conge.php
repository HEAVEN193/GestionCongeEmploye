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

    public $NbreJoursAnnuel;

    public $DateDebut;

    public $DateFin;

    public $TypeConge;

    public $Etat;

    public $NbreJourDemande;

    public $NbreJourRestant;

    public $idEmploye;


    protected $employe = null;


    public static function fetchAll() :array
    {
        $statement = Database::connection()->prepare("SELECT * FROM CONGES");
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetchAll();
    }

    public static function fetchById(int $id):Conge|false
    {
        $statement = Database::connection()
        ->prepare("SELECT * FROM CONGES WHERE idConge = :id");
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetch();
    }

    public static function fetchByEmployeId(int $id) :array
    {
        $statement = Database::connection()->prepare("SELECT * FROM CONGES WHERE idEmploye = :id");
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetchAll();
    }

    public static function fetchByIdDepartement(int $id) :array
    {
        $statement = Database::connection()->prepare("SELECT rs.*
            FROM CONGES cs
            JOIN EMPLOYES e ON cs.idEmploye = e.idEmploye
            WHERE e.idDepartement = (
                SELECT idDepartement
                FROM EMPLOYES
                WHERE idEmploye = :id
            )");
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetchAll();
    }




    public static function create($NbreJoursAnnuel, $DateDebut, $DateFin, $TypeConge, $NbreJourDemande, $NbreJourRestant, $idEmploye)
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("INSERT INTO CONGES (NbreJoursAnnuel, DateDebut, DateFin, TypeConge, NbreJourDemande, NbreJourRestant, idEmploye)
                                   VALUES (:NbreJoursAnnuel, :DateDebut, :DateFin, :TypeConge, :NbreJourDemande, :NbreJourRestant, :idEmploye)");
    
            // Liaison des paramètres
            $stmt->bindParam(':NbreJoursAnnuel', $NbreJoursAnnuel);
            $stmt->bindParam(':DateDebut', $DateDebut);
            $stmt->bindParam(':DateFin', $DateFin);
            $stmt->bindParam(':TypeConge', $TypeConge); 
            $stmt->bindParam(':NbreJourDemande', $NbreJourDemande); 
            $stmt->bindParam(':NbreJourRestant', $NbreJourRestant); 
            $stmt->bindParam(':idEmploye', $idEmploye); 

   
    
            // Exécution
            $stmt->execute();
    
            return $pdo->lastInsertId();
        } catch (\Exception $e) {
            throw new \Exception("Une erreur est survenue lors de la création du conges : " . $e->getMessage());
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
    

    
    public function validate(){
        try {
            $pdo = Database::connection();
    
            $stmt = $pdo->prepare("UPDATE RELEVEHSUPP SET Statut = 'Valide'
                WHERE idHeureSupp = :id");
    
            $stmt->bindParam(':id', $this->idHeureSupp, PDO::PARAM_INT);

            $stmt->execute();
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Une erreur est survenue lors de la validation du relevé des heures supplémentaire : " . $e->getMessage());
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
