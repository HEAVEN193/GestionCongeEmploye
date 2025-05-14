<?php
   use Matteomcr\GestionCongeEmploye\Models\Employe;
   use Matteomcr\GestionCongeEmploye\Models\Departement;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Relevés d'heures supplémentaires</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    html * {
      padding: 0;
      box-sizing: border-box;
    }
    body {
      background-color: #f8f9fa;
    }
    .header-title {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 1rem;
    }
    .filter-bar {
      background: white;
      padding: 1rem;
      border-radius: 0.5rem;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      margin-bottom: 1.5rem;
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      align-items: center;
    }
    .filter-select select {
      padding: 0.5rem;
      border-radius: 0.375rem;
      border: 1px solid #ced4da;
      font-size: 0.875rem;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="header-title">
      <h3 class="mb-0">
        <?php if (Employe::current()->getRole()->NomRole == 'Employe'): ?>  
          Mes soumissions d'heure supplémentaires
        <?php elseif(Employe::current()->getRole()->NomRole == 'Manager'): ?>
          Relevés d'heure supplémentaires du département
        <?php else: ?>
          Tous les relevés d'heure supplémentaires
        <?php endif; ?>
      </h3>
      <?php if (Employe::current()->getRole()->NomRole == 'Employe'): ?>  
        <a href="/form-heures-supp" class="btn btn-primary ms-auto">
          <i class="bi bi-calendar"></i> Soumettre un relevé
        </a>
      <?php endif; ?>
    </div>

    <?php if (Employe::current()->getRole()->NomRole == 'Administrateur'): ?>
    <form method="GET" action="/heures-supp">
      <div class="filter-bar">
        <div class="filter-select">
          <label for="departement" class="form-label">Filtrer par département :</label>
          <select name="departement" id="departement" onchange="this.form.submit()">
            <option value="">Tous les départements</option>
            <?php foreach (Departement::fetchAll() as $dep): ?>
              <option value="<?= $dep->idDepartement ?>" <?= isset($_GET['departement']) && $_GET['departement'] == $dep->idDepartement ? 'selected' : '' ?>>
                <?= htmlspecialchars($dep->NomDepartement) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </form>
    <?php endif; ?>

    <div class="card shadow-sm">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>Employé</th>
                <th>Date</th>
                <th>Heures</th>
                <th>Statut</th>
                <th>Conversion</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($heuresSupp as $releve): ?>
              <tr>
                <td><?= htmlspecialchars($releve->getEmploye()->Pseudo) ?></td>
                <td><?= htmlspecialchars($releve->DateSoumission) ?></td>
                <td><?= htmlspecialchars($releve->NbreHeure) ?></td>
                <td>
                  <?php if ($releve->Statut === 'Valide'): ?>
                    <span class="badge bg-success">Validée</span>
                  <?php elseif ($releve->Statut === 'Refuse'): ?>
                    <span class="badge bg-danger">Refusée</span>
                  <?php else: ?>
                    <span class="badge bg-warning text-dark">En attente</span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($releve->ConversionType) ?></td>
                <td class="text-end">
                  <?php if ($releve->Statut === 'En attente' && Employe::current()->getRole()->NomRole == 'Manager'): ?>
                    <div class="btn-group" role="group">
                      <form action="/validerHeureSupp/<?= $releve->idHeureSupp ?>" method="post" style="display:inline;">
                        <button type="submit" class="btn btn-success btn-sm">
                          <i class="bi bi-check"></i> Approuver
                        </button>
                      </form>
                      <form action="/refuserHeureSupp/<?= $releve->idHeureSupp ?>" method="post" style="display:inline;">
                        <button type="submit" class="btn btn-danger btn-sm">
                          <i class="bi bi-x"></i> Refuser
                        </button>
                      </form>
                    </div>
                  <?php else: ?>
                    <em class="text-muted">Action non disponible</em>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>
