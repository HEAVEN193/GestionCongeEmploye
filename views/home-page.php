<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Page d'accueil</h1>
    <h2>Bienvenue</h2>
    <?php
        use Matteomcr\GestionCongeEmploye\Models\Employe;

        if(Employe::current()){
            echo Employe::current()->Pseudo; 
            echo '<a href="/logout"><button class="btn" id="btnUserLogout">Se déconnecter</button></a>';
        }
        else{
            echo "LOGIN";
        }
    ?>

</body>
</html>