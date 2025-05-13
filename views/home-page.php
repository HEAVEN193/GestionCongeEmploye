
<?php 
use Matteomcr\GestionCongeEmploye\Models\Employe; 
use Matteomcr\GestionCongeEmploye\Models\Conge; 

$employe = Employe::current();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimeOff | Gestion des Congés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>

    html *{
      padding: 0;
    }
    .row{
      justify-content: center;
    }
    /* Main content */
    .main-container {
        padding-top: 1.5rem;
        max-width: 80%;
        
    }
    .badge{
      height: 25px;
    }

    /* Calendar */
    .calendar-table {
        table-layout: fixed;
    }

    .calendar-table th,
    .calendar-table td {
        text-align: center;
        vertical-align: top;
        height: 100px;
        padding: 0.5rem;
    }

    .calendar-table td {
        position: relative;
    }

    /* Card customization */
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .sidebar {
            position: static;
            height: auto;
            padding-top: 0;
        }
        
        main {
            margin-left: 0 !important;
        }
    }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
         

            <!-- Main content -->
             <div class="main-container">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Tableau de bord</h1>
                </div>

                <!-- Stats cards -->
                <div class="row g-4 mb-4">
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card border-start border-primary border-4">
                            <div class="card-body">
                                <h6 class="card-title text-muted">Congés en attente</h6>
                                <h2 class="card-text">
                                  <?php
                                    echo $employe->countCongesEnAttente();
                                  ?>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card border-start border-success border-4">
                            <div class="card-body">
                                <h6 class="card-title text-muted">Congés approuvés</h6>
                                <h2 class="card-text">
                                <?php
                                    echo $employe->countCongesApprouves();
                                  ?>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card border-start border-info border-4">
                            <div class="card-body">
                                <h6 class="card-title text-muted">Demandes total</h6>
                                <h2 class="card-text">
                                <?php
                                    echo $employe->countTotalConges();
                                  ?>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card border-start border-warning border-4">
                            <div class="card-body">
                                <h6 class="card-title text-muted">Heures supp.</h6>
                                <h2 class="card-text">
                                <?php
                                    echo $employe->getTotalOvertime()['heures'] ;
                                  ?>
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendar and Recent Requests -->
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-0">Mai 2025</h5>
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-outline-secondary btn-sm">Mois</button>
                                    <button class="btn btn-outline-secondary btn-sm">Semaine</button>
                                    <button class="btn btn-outline-secondary btn-sm">Jour</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered calendar-table">
                                    <thead>
                                        <tr>
                                            <th>Dim</th>
                                            <th>Lun</th>
                                            <th>Mar</th>
                                            <th>Mer</th>
                                            <th>Jeu</th>
                                            <th>Ven</th>
                                            <th>Sam</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Calendar rows -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Demandes récentes</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="mb-1">Congé personnel</h6>
                                                <small class="text-muted">10 Mar 2025 - 10 Mar 2025</small>
                                            </div>
                                            <span class="badge bg-danger">Refusé</span>
                                        </div>
                                    </div>
                                    <!-- More requests... -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!-- 
<head>
  <meta charset="UTF-8">
  <title>Dashboard TimeOff</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    html *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
      background-color: #f8f9fa;
      min-height: 100vh;
      overflow-x: hidden;

    }
    
    .calendar-cell {
      border: 1px solid #dee2e6;
      height: 80px;
      text-align: center;
      vertical-align: top;
    }
    .event-badge {
      display: inline-block;
      padding: 3px 8px;
      border-radius: 4px;
      font-size: 12px;
      color: white;
    }
    .badge-rejected {
      background-color: #dc3545;
    }
    .badge-pending {
      background-color: #ffc107;
      color: black;
    }
    .badge-approved {
      background-color: #28a745;
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">


      <div class="col-md-10 p-4">
        
        <h3>Dashboard</h3>
        <div class="row my-4">
          
          <div class="col-md-3">
            <div class="card text-center">
              <div class="card-body">
                <h5>Pending Leaves</h5>
                <h3>1</h3>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card text-center">
              <div class="card-body">
                <h5>Approved Leaves</h5>
                <h3>1</h3>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card text-center">
              <div class="card-body">
                <h5>Total Requests</h5>
                <h3>3</h3>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card text-center">
              <div class="card-body">
                <h5>Overtime Hours</h5>
                <h3>7.0</h3>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-8">
            <h5>May 2025</h5>
            
            <div id="kt_docs_fullcalendar_basic"></div>


          </div>

          <div class="col-md-4">
            <h5>Demande de congés récentes</h5>
            <ul class="list-group">
              <li class="list-group-item">
                <strong>Conversion heure supp</strong><br>
                Mar 10, 2025 – Mar 10, 2025<br>
                <span class="event-badge badge-rejected">Refusé</span>
                <small class="d-block text-muted">beaucoup travaillé cette période</small>
              </li>
              <li class="list-group-item">
                <strong>Vacances</strong><br>
                Feb 1, 2025 – Feb 5, 2025<br>
                <span class="event-badge badge-pending">En attente</span>
                <small class="d-block text-muted">Evénement famillial</small>
              </li>
              <li class="list-group-item">
                <strong>Vacances</strong><br>
                Jan 15, 2025 – Jan 20, 2025<br>
                <span class="event-badge badge-approved">Accepté</span>
                <small class="d-block text-muted">Requêtes accépté</small>
              </li>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </div>
</body> -->


