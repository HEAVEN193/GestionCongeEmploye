<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Ajouter un utilisateur</title>
    <style>
        html *{
            padding:0;
        }
   body {
    font-family: Arial, sans-serif;
    margin: 0;
    background-color: #f4f4f4;
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
input[type="password"],
input[type="email"]{
    width: 100%;
    padding: 8px;
    margin: 8px 0 20px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box; 
}

button {
    background-color: #4CAF50; 
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

.error {
    color: #f44336; /* Rouge */
    font-size: 0.875em;
}


    </style>
</head>
<body>


    <h1>Déclarer des heures supplémentaires</h1>
 

    <form action="/heuresupp" method="POST">
        
        <label for="date" class="date">Date</label>
        <input type="date" class="form-control" id="date" name="date" required>

        <label for="statut" class="form-label">Heure supplémentaires :</label>
        <input type="number" id="heures" name="heures" min ="0" step="1" class="form-control" required>
        <br><br>
        <label for="conversionType" class="form-label">Conversion des heures :</label>
        <select id="conversionType" name="conversionType" required>
            <option value="conge">Congé</option>
            <option value="paiement">Paiement</option>
        </select><br><br>
      
        <br>

        <button type="submit" class="btn-add">Envoyer</button>
        <a href="/overtimes" class="btn btn-secondary" style="margin-left: 10px;">Annuler</a>

        <?php
            if(isset($_SESSION['error'])){
                echo '<div class="alert alert-danger" mb-4 role="alert">' .$_SESSION['error'] . '</div>';
                unset($_SESSION['error']); 
            } 
        ?>
    </form>

</body>
</html>