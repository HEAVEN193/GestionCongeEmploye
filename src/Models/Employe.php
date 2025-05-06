<?php

namespace Matteomcr\GestionCongeEmploye\Models;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use Matteomcr\GestionCongeEmploye\Models\Database;




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

    /**
     * Récupère l'employe actuellement connecté.
     * 
     * Cette méthode utilise la session pour déterminer si un utilisateur est connecté.
     * Si un utilisateur est trouvé via son adresse email, il est retourné.
     * 
     * @return Employe|null Retourne l'utilisateur actuel ou null s'il n'est pas connecté.
     */
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

    /**
     * Récupère le role de l'utilisateur.
     * 
     * @return Role|null Le role ou null si aucun role n'est trouvée.
     */
    public function getRole() : Role|null
    {
        if(!$this->role){
            $this->role = $this->idRole ? Role::fetchById($this->idRole) : null;
        }
        return $this->role;
    }




    /**
     * Connecte un employe en vérifiant son email et mot de passe.
     * 
     * @param string $email L'adresse email de l'utilisateur.
     * @param string $password Le mot de passe de l'utilisateur.
     * 
     * @return array Les informations de l'utilisateur connecté.
     * @throws \Exception Si l'email ou le mot de passe est incorrect.
     */
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
