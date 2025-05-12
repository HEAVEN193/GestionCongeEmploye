<?php

namespace Matteomcr\GestionCongeEmploye\Models;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Matteomcr\GestionCongeEmploye\Models\Database;
use Matteomcr\GestionCongeEmploye\Models\Role;
use Matteomcr\GestionCongeEmploye\Models\Departement;






use Exception;
use PDO;

/**
 * Classe représentant un utilisateur de l'application.
 * 
 * Cette classe gère les informations relatives à un utilisateur, telles que son pseudo, son email,
 * et son mot de passe. Elle permet également de récupérer les statistiques de l'utilisateur,
 * vérifier si un email existe déjà, et gérer les connexions et créations de comptes.
 */
class Employe
{

    public $idEmploye;

    public $Nom;

    public $Prenom;

    public $Pseudo;

    public $MotDePasse;

    public $Email;

    public $DateEmbauche;

    public $Statut;

    public $idRole;

    public $idDepartement;

    protected $role = null;

    protected $departement = null;


    public static function fetchAll() :array
    {
        $statement = Database::connection()->prepare("SELECT * FROM EMPLOYES");
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetchAll();
    }

    public static function fetchAllManager() :array
    {
        $statement = Database::connection()->prepare("SELECT * FROM EMPLOYES WHERE idRole = 2");
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetchAll();
    }

    public static function fetchById(int $id) :Employe|false
    {
        $statement = Database::connection()
        ->prepare("SELECT * FROM EMPLOYES WHERE idEmploye = :id");
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetch();
    }

    public static function fetchByDepartement(int $idDepartement) :array|false
    {
        $statement = Database::connection()
        ->prepare("SELECT * FROM EMPLOYES WHERE idDepartement = :id");
        $statement->execute([':id' => $idDepartement]);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetchAll();
    }


    /**
     * Récupère un employe en fonction de son adresse email.
     * 
     * @param string $email L'adresse email de l'utilisateur à rechercher.
     * 
     * @return Employe|false Retourne un objet Utilisateur si l'email est trouvé, ou false sinon.
     */
    public static function fetchByEmail(string $email) : Employe|false
    {
        $statement = Database::connection()->prepare("SELECT * FROM EMPLOYES WHERE Email = :email");
        $statement->execute([':email' => $email]);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetch();
    }

    public static function create($nom, $prenom, $pseudo, $mdp, $email, $dateEmbauche, $statut, $idrole, $iddepartement)
{
    try {
        $pdo = Database::connection();

        // 1. Vérifier les champs obligatoires
        if (empty($nom) || empty($prenom) || empty($pseudo) || empty($mdp) || empty($email) || empty($dateEmbauche)) {
            throw new \Exception("Tous les champs obligatoires doivent être remplis.");
        }

        // 2. Valider l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Format de l'adresse email invalide.");
        }

        // 3. Vérifier si l'email existe déjà
        if(self::emailAlreadyExist($email)){
            throw new \Exception("Cet email est déjà utilisé.");
        }


        // 5. Vérifier la longueur du mot de passe
        if (strlen($mdp) < 6) {
            throw new \Exception("Le mot de passe doit contenir au moins 6 caractères.");
        }

        // 6. Hasher le mot de passe
        $hashedPassword = password_hash($mdp, PASSWORD_DEFAULT);

        // 7. Préparer l'insertion
        $stmt = $pdo->prepare("INSERT INTO EMPLOYES 
            (Nom, Prenom, Pseudo, MotDePasse, Email, DateEmbauche, Statut, idRole, idDepartement) 
            VALUES 
            (:nom, :prenom, :pseudo, :mdp, :email, :dateEmbauche, :statut, :idrole, :iddepartement)");

        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':mdp', $hashedPassword);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':dateEmbauche', $dateEmbauche);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':idrole', $idrole);
        $stmt->bindParam(':iddepartement', $iddepartement);

        $stmt->execute();

        return $pdo->lastInsertId();

    } catch (\Exception $e) {
        // Tu peux loguer l'erreur ou la transmettre proprement
        throw new \Exception($e->getMessage());
    }
}

    

public static function update($id, $nom, $prenom, $pseudo, $dateEmbauche, $statut, $idrole, $iddepartement)
{
    try {
        $pdo = Database::connection();

        // Vérification des champs obligatoires
        if (
            empty($id) || empty($nom) || empty($prenom) || empty($pseudo)
            || empty($dateEmbauche) || empty($statut)
            || empty($idrole) || empty($iddepartement)
        ) {
            throw new \Exception("Tous les champs obligatoires doivent être remplis.");
        }

        // Vérification de l'unicité du pseudo (hors soi-même)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM EMPLOYES WHERE Pseudo = :pseudo AND idEmploye != :id");
        $stmt->execute(['pseudo' => $pseudo, 'id' => $id]);
        if ($stmt->fetchColumn() > 0) {
            throw new \Exception("Ce pseudo est déjà utilisé par un autre employé.");
        }

        // Mise à jour (email et mot de passe non touchés)
        $stmt = $pdo->prepare("
            UPDATE EMPLOYES SET
                Nom = :nom,
                Prenom = :prenom,
                Pseudo = :pseudo,
                DateEmbauche = :dateEmbauche,
                Statut = :statut,
                idRole = :idrole,
                idDepartement = :iddepartement
            WHERE idEmploye = :id
        ");

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':dateEmbauche', $dateEmbauche);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':idrole', $idrole);
        $stmt->bindParam(':iddepartement', $iddepartement);

        $stmt->execute();
        return true;

    } catch (\Exception $e) {
        throw new \Exception($e->getMessage());
    }
}


    public static function delete($employeId) {
        $pdo = Database::connection();
        $statement = $pdo->prepare("DELETE FROM EMPLOYES WHERE idEmploye = ?");
        $statement->execute([$employeId]);
    }
    


    
    public static function current(): Employe|null
    {
        static $current = null;

        if (!$current) {
            $email = $_SESSION['user'] ?? null;

            if ($email != null) {
                $current = $email ? static::fetchByEmail($email) : new static;
                
            }
        }

        return $current;
    }

    public function getRole() : Role|null
    {
        if(!$this->role){
            $this->role = $this->idRole ? Role::fetchById($this->idRole) : null;
        }
        return $this->role;
    }

    public function getDepartement() : Departement|null
    {
        if(!$this->departement){
            $this->departement = $this->idDepartement ? Departement::fetchById($this->idDepartement) : null;
        }
        return $this->departement;
    }

    public function getOvertimeReport() : array
    {
        return HeureSupplementaire::fetchByEmployeId($this->idEmploye);
    }

    public function getTotalOvertime(){
        return HeureSupplementaire::getTotalOvertimeByUserId($this->idEmploye);
    }

    public function getOvertimeRejected(){
        return HeureSupplementaire::getOvertimereRejectedByUserId($this->idEmploye);
    }

    public function getOvertimeConvertedToLeave(){
        return HeureSupplementaire::getOvertimeConvertedToLeaveByUserId($this->idEmploye);
    }

    public function getOvertimeConvertedToPayment(){
        return HeureSupplementaire::getOvertimeConvertedToPaymentByUserId($this->idEmploye);
    }




    public static function login($email, $password)
    {
        $pdo = Database::connection();

        $stmt = $pdo->prepare("SELECT * FROM EMPLOYES WHERE Email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            // Vérifie si le mot de passe correspond
            if (password_verify($password, $user['MotDePasse'])) {
                return $user;
            } else {
                throw new \Exception("Email ou mot de passe incorrect !");
            }
        } else {
            // Email n'existe pas
            throw new \Exception("Email ou mot de passe incorrect !");
        }
    }

    public static function emailAlreadyExist($email): bool
    {
        $statement = Database::connection()->prepare("SELECT * FROM EMPLOYES WHERE Email = :email");
        $statement->execute([':email' => $email]);
        $user = $statement->fetch(\PDO::FETCH_ASSOC);

        return $user ? true : false;
    }



  



   

}
