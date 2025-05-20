<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Demande de congé</title>
  <style>
    html *{
        padding: 0;
    }

    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #f4f4f4;
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
    select,
    textarea {
      width: 100%;
      padding: 8px;
      margin: 8px 0 20px 0;
      display: inline-block;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    textarea {
      resize: vertical;
      min-height: 80px;
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
      color: #f44336;
      font-size: 0.875em;
    }
  </style>
</head>
<body>

  <h1>Faire une demande de congé</h1>

  <form action="/leave-request" method="POST">
    <label for="typeConge">Type de congé :</label>
    <select id="typeConge" name="typeConge" required>
      <option value="vacances">Vacances</option>
      <option value="conversion">Heures supplémentaires</option>
    </select>

    <label for="dateDebut">Date de début :</label>
    <input type="date" id="dateDebut" name="dateDebut" required>

    <label for="dateFin">Date de fin :</label>
    <input type="date" id="dateFin" name="dateFin" required>

    <label for="justification">Justification (optionnelle) :</label>
    <textarea id="justification" name="justification" placeholder="Ex : voyage, rendez-vous médical, etc."></textarea>

    <button type="submit">Envoyer la demande</button>
    <a href="/leaves-page" class="btn btn-secondary" style="margin-left: 10px;">Annuler</a>
    <?php
            if(isset($_SESSION['error'])){
                echo '<div class="alert alert-danger" mb-4 role="alert">' .$_SESSION['error'] . '</div>';
                unset($_SESSION['error']); 
            } 
        ?>
  </form>

</body>
</html>
