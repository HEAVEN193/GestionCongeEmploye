<?php
   use Matteomcr\GestionCongeEmploye\Models\Employe;
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Leave Requests</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    html *{
        padding: 0;
    }
</style>
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
      <?php if (Employe::current()->getRole()->NomRole == 'Employe'): ?>  
      <h5 class="mb-0">Mes soumissions d'heure supplémentaires</h5>
      <?php elseif(Employe::current()->getRole()->NomRole == 'Manager'): ?>
        <h5 class="mb-0">Relevé d'heure supplémentaires du département</h5>
      <?php else: ?>
        <h5 class="mb-0">Tout les relevé d'heure supplémentaires</h5>


        <?php endif; ?>

        <?php if (Employe::current()->getRole()->NomRole == 'Employe'): ?>  
        <a href="/form-conge" class="btn btn-primary">
          <i class="bi bi-calendar"></i> Soumettre un relevé
        </a>
        <?php endif; ?>

      </div>
      <div class="card-body">
        <!-- Aucun résultat -->
        <!-- <div class="text-center py-5 text-muted">Aucune demande trouvée</div> -->

        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th scope="col">Type</th>
                <th scope="col">Durée</th>
                <th scope="col">Date début</th>
                <th scope="col">Date fin</th>
                <th scope="col">Statut</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($conges as $conges): ?>
            <tr>
                <td><?= htmlspecialchars($conges->TypeConge) ?></td>
                <td><?= htmlspecialchars($conges->NbreJourDemande) ?></td>
                <td><?= htmlspecialchars($conges->DateDebut) ?></td>
                <td><?= htmlspecialchars($conges->DateFin) ?></td>
                <td><?= htmlspecialchars($conges->Statut) ?></td>
                <td>
                <?php if ($conges->Statut === 'Valide'): ?>
                    <span class="badge bg-success">Validée</span>
                <?php elseif ($conges->Statut === 'Refuse'): ?>
                    <span class="badge bg-danger">Refusée</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark">En attente</span>
                <?php endif; ?>
                </td>

                <td class="text-end">
                <?php if ($conges->Statut === 'En attente' && Employe::current()->getRole()->NomRole != 'Employe'): ?>
                    <div class="btn-group" role="group">
                        <form action="/validerConge/<?= $conges->idConge ?>" method="post" style="display:inline;">
                            <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-check"></i> Approuver
                            </button>
                        </form>
                        <form action="/refuserConge/<?= $conges->idConge ?>" method="post" style="display:inline;">
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
