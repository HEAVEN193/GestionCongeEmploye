<?php

namespace Matteomcr\GestionCongeEmploye\Models;

use Matteomcr\GestionCongeEmploye\Models\Database;
use Matteomcr\GestionCongeEmploye\Models\Role;
use Matteomcr\GestionCongeEmploye\Models\Departement;
use Exception;
use PDO;

/**
 * Classe représentant un employé de l'entreprise.
 * Gère les opérations de création, récupération, modification et suppression des employés.
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

    public $SoldeConge;

    public $SoldeCongeHeureSupp;

    public $idRole;

    public $idDepartement;

    protected $role = null;

    protected $departement = null;

    /**
     * Récupère tous les employés.
     * @return Employe[] Liste de tous les employés.
     */
    public static function fetchAll() :array
    {
        $statement = Database::connection()->prepare("SELECT * FROM EMPLOYES");
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetchAll();
    }

    /**
     * Récupère tous les employés ayant le rôle de manager.
     * @return Employe[] Liste des managers.
     */
    public static function fetchAllManager() :array
    {
        $statement = Database::connection()->prepare("SELECT * FROM EMPLOYES WHERE idRole = 2");
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetchAll();
    }

    /**
     * Récupère un employé par son identifiant.
     * @param int $id L'identifiant de l'employé.
     * @return Employe|false L'objet Employe ou false si introuvable.
     */
    public static function fetchById(int $id) :Employe|false
    {
        $statement = Database::connection()
        ->prepare("SELECT * FROM EMPLOYES WHERE idEmploye = :id");
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetch();
    }

    /**
     * Récupère tous les employés d'un département donné.
     * @param int $idDepartement Identifiant du département.
     * @return Employe[]|false Liste des employés ou false.
     */
    public static function fetchByDepartement(int $idDepartement) :array|false
    {
        $statement = Database::connection()
        ->prepare("SELECT * FROM EMPLOYES WHERE idDepartement = :id");
        $statement->execute([':id' => $idDepartement]);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetchAll();
    }


    /**
     * Récupère un employé en fonction de son adresse email.
     * @param string $email L'adresse email de l'utilisateur à rechercher.
     * @return Employe|false Retourne un objet Employe si l'email est trouvé, ou false sinon.
     */
    public static function fetchByEmail(string $email) : Employe|false
    {
        $statement = Database::connection()->prepare("SELECT * FROM EMPLOYES WHERE Email = :email");
        $statement->execute([':email' => $email]);
        $statement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
        return $statement->fetch();
    }

    /**
     * Crée un nouvel employé et retourne son identifiant.
     * @param string $nom
     * @param string $prenom
     * @param string $pseudo
     * @param string $mdp
     * @param string $email
     * @param string $dateEmbauche
     * @param int $statut
     * @param int $idrole
     * @param int $iddepartement
     * @return int ID du nouvel employé créé.
     */
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

    
    /**
     * Met à jour les informations d'un employé existant.
     * @param int $id
     * @param string $nom
     * @param string $prenom
     * @param string $pseudo
     * @param string $dateEmbauche
     * @param int $statut
     * @param int $idrole
     * @param int $iddepartement
     * @return bool Succès ou échec de l'opération.
     */
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

    /**
     * Supprime un employé après vérification qu'il n'est pas manager.
     * @param int $employeId Identifiant de l'employé à supprimer.
     */
    public static function delete($employeId) {

        $pdo = Database::connection();
        // Vérifier s’il est manager d’un département
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM DEPARTEMENT WHERE idManager = :id");
        $stmt->execute([':id' => $employeId]);

        if ($stmt->fetchColumn() > 0) {
            throw new \Exception("Impossible de supprimer le manager d’un département.");
        }
        
        $statement = $pdo->prepare("DELETE FROM EMPLOYES WHERE idEmploye = ?");
        $statement->execute([$employeId]);
    }
    

    /**
     * Retourne l'utilisateur actuellement connecté (depuis la session).
     * @return Employe|null L'utilisateur connecté ou null.
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
     * Retourne le rôle associé à l'employé.
     * @return Role|null Le rôle de l'employé.
     */
    public function getRole() : Role|null
    {
        if(!$this->role){
            $this->role = $this->idRole ? Role::fetchById($this->idRole) : null;
        }
        return $this->role;
    }

    /**
     * Retourne le département associé à l'employé.
     * @return Departement|null Le département de l'employé.
     */
    public function getDepartement() : Departement|null
    {
        if(!$this->departement){
            $this->departement = $this->idDepartement ? Departement::fetchById($this->idDepartement) : null;
        }
        return $this->departement;
    }

    /**
     * Retourne le relevé des heures supplémentaires de l'employé.
     * @return array Liste des heures supplémentaires.
     */
    public function getOvertimeReport() : array
    {
        return HeureSupplementaire::fetchByEmployeId($this->idEmploye);
    }

    /**
     * Retourne le total des heures supplémentaires de l'employé.
     * @return mixed Total des heures supplémentaires.
     */
    public function getTotalOvertime(){
        return HeureSupplementaire::getTotalOvertimeByUserId($this->idEmploye);
    }

    /**
     * Retourne les heures supplémentaires refusées de l'employé.
     * @return mixed Heures refusées.
     */
    public function getOvertimeRejected(){
        return HeureSupplementaire::getOvertimereRejectedByUserId($this->idEmploye);
    }

    /**
     * Retourne les heures supplémentaires converties en congés.
     * @return mixed Heures converties en congé.
     */
    public function getOvertimeConvertedToLeave(){
        return HeureSupplementaire::getOvertimeConvertedToLeaveByUserId($this->idEmploye);
    }

    /**
     * Retourne les heures supplémentaires converties en paiement.
     * @return mixed Heures converties en paiement.
     */
    public function getOvertimeConvertedToPayment(){
        return HeureSupplementaire::getOvertimeConvertedToPaymentByUserId($this->idEmploye);
    }



    /**
     * Authentifie un employé via son email et mot de passe.
     * @param string $email
     * @param string $password
     * @return array|false Données de l'utilisateur ou exception.
     * @throws Exception Si l'authentification échoue.
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

    /**
     * Vérifie si un email est déjà utilisé par un autre employé.
     * @param string $email
     * @return bool true si l'email existe, false sinon.
     */
    public static function emailAlreadyExist($email): bool
    {
        $statement = Database::connection()->prepare("SELECT * FROM EMPLOYES WHERE Email = :email");
        $statement->execute([':email' => $email]);
        $user = $statement->fetch(\PDO::FETCH_ASSOC);
        return $user ? true : false;
    }

    /**
     * Compte les congés en attente pour l'employé.
     * @return int Nombre de congés en attente.
     */
    public function countCongesEnAttente(): int
    {
        $stmt = Database::connection()->prepare("SELECT COUNT(*) FROM CONGES WHERE Statut = 'en_attente' AND idEmploye = :id");
        $stmt->execute([':id' => $this->idEmploye]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Compte les congés approuvés pour l'employé.
     * @return int Nombre de congés approuvés.
     */
    public function countCongesApprouves(): int
    {
        $stmt = Database::connection()->prepare("SELECT COUNT(*) FROM CONGES WHERE Statut = 'valide' AND idEmploye = :id");
        $stmt->execute([':id' => $this->idEmploye]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Compte le nombre total de congés de l'employé.
     * @return int Nombre total de congés.
     */
    public function countTotalConges(): int
    {
        $stmt = Database::connection()->prepare("SELECT COUNT(*) FROM CONGES WHERE idEmploye = :id");
        $stmt->execute([':id' => $this->idEmploye]);
        return (int) $stmt->fetchColumn();
    }



  



   

}
