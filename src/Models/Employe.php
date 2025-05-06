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

    public static function fetchById(int $id) :Employe|false
    {
        $statement = Database::connection()
        ->prepare("SELECT * FROM EMPLOYES WHERE idEmploye = :id");
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetch();
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
            $stmt = $pdo->prepare("INSERT INTO EMPLOYES (Nom, Prenom, Pseudo, MotDePasse, Email, DateEmbauche, Statut, idRole, idDepartement)
                                   VALUES (:nom, :prenom, :pseudo, :mdp, :email, :dateEmbauche, :statut, :idrole, :iddepartement)");
    
            // Hasher le mot de passe
            $hashedPassword = password_hash($mdp, PASSWORD_DEFAULT);
    
            // Liaison des paramètres
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->bindParam(':mdp', $hashedPassword); // <- ici on met bien le mot de passe hashé
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':dateEmbauche', $dateEmbauche);
            $stmt->bindParam(':statut', $statut);
            $stmt->bindParam(':idrole', $idrole);
            $stmt->bindParam(':iddepartement', $iddepartement);
    
            // Exécution
            $stmt->execute();
    
            return $pdo->lastInsertId();
        } catch (\Exception $e) {
            throw new \Exception("Une erreur est survenue lors de la création du compte : " . $e->getMessage());
        }
    }
    

    public static function update($id, $nom, $prenom, $pseudo, $mdp, $email, $dateEmbauche, $statut, $idrole, $iddepartement)
    {
        try {
            $pdo = Database::connection();
    
            // Hasher le nouveau mot de passe
            $hashedPassword = password_hash($mdp, PASSWORD_DEFAULT);
    
            $stmt = $pdo->prepare("UPDATE EMPLOYES SET
                Nom = :nom,
                Prenom = :prenom,
                Pseudo = :pseudo,
                MotDePasse = :mdp,
                Email = :email,
                DateEmbauche = :dateEmbauche,
                Statut = :statut,
                idRole = :idrole,
                idDepartement = :iddepartement
                WHERE idEmploye = :id");
    
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
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
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Une erreur est survenue lors de la mise à jour de l'employé : " . $e->getMessage());
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



  



   

}
