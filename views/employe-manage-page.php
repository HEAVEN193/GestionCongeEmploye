<?php 
use Matteomcr\GestionCongeEmploye\Models\Employe;
?>
<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html *{
            padding: 0;
        }
body {
    font-family: Arial, sans-serif;
    margin: 0;
    /* background-color: #f4f4f4; */
}

h1 {
    color: #333;
    margin-bottom: 50px;
}

.container{
    height:100%;
    width: 80%;
    min-width: 600px;
    margin:auto;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
    
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 8px;
    text-align: left;
}

th {
    background-color: #333; /* Couleur plus sombre */
    color: white;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

.btn-edit, .btn-delete {
    color: white;
    border: none;
    cursor: pointer;
    display: inline-block;
    width: 30px; /* Carré */
    height: 30px;
    font-size: 14px;
    text-align: center;
}

.btn-edit {
    color: black;
    border: 1px solid grey;
    border-radius: 5px;
    height:30px;
    width: 30px;
}

.btn-delete {
    border-radius: 5px;
    background-color: #f44336; /* Rouge pour supprimer */
}

.btn-edit:before {
    content: "\270E"; /* Symbole d'édition */
    padding-left: 2px;
}

.btn-delete:before {
    content: "\2715"; /* Croix */
}

.navbar {
    background-color: #333; /* Couleur de fond */
    overflow: hidden; 
    width: 100%;
    height: 70px;
    margin-bottom: 30px;
}

.navbar a {
    color: white;
    float: left; /* Alignement à gauche */
    display: block; /* Affichage en bloc */
    color: white; /* Couleur du texte */
    text-align: center; /* Alignement du texte */
    padding: 27px 20px; /* Espacement autour du texte */
    text-decoration: none; /* Pas de soulignement */
    
}

.navbar a:hover {
    background-color: #ddd; /* Couleur de fond au survol */
    color: black; /* Couleur du texte au survol */
}

.btn-add {
    padding: 10px 20px;
    background-color: #4CAF50; /* Vert pour ajouter */
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 100px;
}

.btn-add:hover {
    background-color: #45a049;
}

.td-edit {
    display: flex;
    justify-content: space-around;
}
    </style>
</head>


<body>


<div class="container">
    <h1>Liste des employes</h1>


    <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Pseudo</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Departement</th>
                    <?php if (Employe::current() && Employe::current()->getRole()->NomRole == "Administrateur"): ?>
                    <th>Edition</th>
                    <?php endif; ?>


                </tr>
            </thead>
            <tbody>
            <?php foreach ($employes as $employe): ?>
                <tr>
                    <td><?= htmlspecialchars($employe->Nom) ?></td>
                    <td><?= htmlspecialchars($employe->Prenom) ?></td>
                    <td><?= htmlspecialchars($employe->Pseudo) ?></td>
                    <td><?= htmlspecialchars($employe->Email) ?></td>
                    <td><?= htmlspecialchars($employe->getRole()->NomRole) ?></td>
                    <td><?= htmlspecialchars($employe->getDepartement()->NomDepartement) ?></td>
                    <?php if (Employe::current() && Employe::current()->getRole()->NomRole == "Administrateur"): ?>

                    <td class="td-edit">
                        <span>
                            <a href="/form-update-employe/<?= $employe->idEmploye ?>">
                                <button class="btn-edit" ></button>
                            </a>
                        </span>
                        
                        <span>
                            <a href="/deleteEmploye/<?= $employe->idEmploye ?>">
                                <button class="btn-delete"></button>
                            </a>
                        </span>
                    </td> 
                    <?php endif; ?>

                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php
            if(isset($_SESSION['error'])){
                echo '<div class="alert alert-danger" mb-4 role="alert">' .$_SESSION['error'] . '</div>';
                unset($_SESSION['error']); 
            } 
        ?>
       <?php 
       if (Employe::current() && Employe::current()->getRole()->NomRole == "Administrateur"): ?>
        <a href="/form-add-employe" class="btn-add">Ajouter un utilisateur</a>
        <?php endif; ?>

    </div>
</body>

