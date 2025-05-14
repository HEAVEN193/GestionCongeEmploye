<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Ajouter un utilisateur</title>
    <style>
   body {
    font-family: Arial, sans-serif;
    margin: 0;
    background-color: #f4f4f4;
    padding: 20px;
}

.navbar {
    background-color: #333;
    overflow: hidden;
    padding: 0 20px;
}

.navbar a {
    color: white;
    float: left;
    display: block;
    text-align: center;
    padding: 20px;
    text-decoration: none;
}

.navbar a:hover {
    background-color: #ddd;
    color: black;
}

h1 {
    color: #333;
    text-align: center;
    margin: 20px 0 40px 0;
}

form {
    background-color: white;
    max-width: 500px;
    margin: 0 auto;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

label {
    font-weight: bold;
    color: #333;
}

input[type="text"],
input[type="date"], 
input[type="password"]{
    width: 100%;
    padding: 8px;
    margin: 8px 0 20px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box; /* Ajoute le padding et la bordure à la largeur totale */
}

button {
    background-color: #4CAF50; /* Vert */
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
}

button:hover {
    background-color: #45a049;
}

/* Style additionnel pour les erreurs de formulaire, si nécessaire */
.error {
    color: #f44336; /* Rouge */
    font-size: 0.875em;
}


    </style>
</head>
<body>

    <h1>Modifiez un employé</h1>
 

    <form action="/updateEmploye/<?= $employe->idEmploye ?>" method="post">
        

    <label for="nom">Nom:</label>
        <input type="text" id="lastName" name="nom" value="<?=  $employe->Nom ?>"required><br><br>

        <label for="prenom">Prénom:</label>
        <input type="text" id="firstName" name="prenom" value="<?= $employe->Prenom ?>" required><br><br>

        <label for="pseudo">Pseudo:</label>
        <input type="text" id="pseudo" name="pseudo" value="<?= $employe->Pseudo ?>" required><br><br>


        <label for="date_embauche" class="form-label">Date d'embauche</label>
        <input type="date" class="form-control" id="date_embauche" name="dateEmbauche"value="<?= $employe->DateEmbauche ?>" required>

        <label for="statut" class="form-label">Statut</label>
        <select id="statut" name="statut" required>

            <option value="actif" <?= $employe->Statut === 'actif' ? 'selected' : '' ?>> actif</option>
            <option value="inactif" <?= $employe->Statut === 'inactif' ? 'selected' : '' ?>> inactif</option>
        </select><br><br>

        <label for="role">Role:</label>
        <select id="role" name="role" required>
        <?php foreach ($roles as $role): ?>
            <option value="<?= $role->idRole ?>" <?= $role->idRole == $employe->idRole ? 'selected' : '' ?>>
                <?= htmlspecialchars($role->NomRole) ?>
            </option>
        <?php endforeach; ?>
        </select><br><br>
            
        <label>Departement:</label>

        <select id="departement" name="departement" required>
                <?php foreach ($departements as $departement): ?>
                <option value="<?= $departement->idDepartement ?>" <?= $departement->idDepartement == $employe->idDepartement ? 'selected' : '' ?>>
                    <?= htmlspecialchars($departement->NomDepartement) ?>
                </option>
            <?php endforeach; ?>
        </select>
      
        <br>

        <button type="submit" class="btn-add">Modifier</button>
        <a href="/showEmploye" class="btn btn-secondary" style="margin-left: 10px;">Annuler</a>

        <?php
            if(isset($_SESSION['error'])){
                echo '<div class="alert alert-danger" mb-4 role="alert">' .$_SESSION['error'] . '</div>';
                unset($_SESSION['error']); 
            } 
        ?>
    </form>

</body>