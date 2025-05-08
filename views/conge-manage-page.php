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
        <h5 class="mb-0">Liste des demandes de congés</h5>
        <a href="/leave/new" class="btn btn-primary">
          <i class="bi bi-calendar"></i> Nouvelle demande
        </a>
      </div>
      <div class="card-body">
        <!-- Aucun résultat -->
        <!-- <div class="text-center py-5 text-muted">Aucune demande trouvée</div> -->

        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th scope="col">Employé</th>
                <th scope="col">Type</th>
                <th scope="col">Durée</th>
                <th scope="col">Dates</th>
                <th scope="col">Statut</th>
                <th scope="col" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Jean Dupont</td>
                <td>Vacances</td>
                <td>5 jours</td>
                <td>10 mai 2025 - 14 mai 2025</td>
                <td><span class="badge bg-warning text-dark">En attente</span></td>
                <td class="text-end">
                  <div class="btn-group" role="group">
                    <button class="btn btn-success btn-sm">
                      <i class="bi bi-check"></i> Approuver
                    </button>
                    <button class="btn btn-danger btn-sm">
                      <i class="bi bi-x"></i> Refuser
                    </button>
                  </div>
                </td>
              </tr>
              <!-- Autres lignes ici -->
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
