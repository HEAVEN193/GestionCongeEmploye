<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    html *{
        padding: 0;
    }
</style>
</head>

<body>
    


<h5 class="mb-4 fw-semibold">Profile</h5>

<div class="d-flex justify-content-center align-items-start mt-5 px-3">
  <div class="card border shadow-sm rounded-4 w-100" style="max-width: 900px; min-height: 500px;">
    <div class="card-body p-4">

      <div class="d-flex align-items-center mb-4 ">
        <img src="https://randomuser.me/api/portraits/men/32.jpg" class="rounded-circle me-3" width="90" height="90" alt="Photo">
        <div>
          <h5 class="mb-0 fw-bold"><?php
                        use Matteomcr\GestionCongeEmploye\Models\Employe;

                        if(Employe::current())
                            echo Employe::current()->Nom; 
                        else
                            echo "LOGIN";
                        ?>
            </h5>
          <div class="text-muted mb-1">
          <?php
                        if(Employe::current())
                            echo Employe::current()->Statut; 
                        else
                            echo "LOGIN";
                ?>
          </div>
          <div class="d-flex align-items-center mb-1">
            <span class="me-2"><i class="bi bi-envelope"></i> 
            <?php
                        if(Employe::current())
                            echo Employe::current()->Email; 
                        else
                            echo "LOGIN";
                ?>
        </span>
          </div>
          <div class="d-flex align-items-center mb-1">
            <span class="me-2"><i class="bi bi-briefcase"></i> 
            <?php
                        if(Employe::current())
                            echo Employe::current()->getDepartement()->NomDepartement; 
                        else
                            echo "LOGIN";
                ?></span>
          </div>
          <div class="d-flex align-items-center">
            <span class="me-2"><i class="bi bi-people"></i> Membre de l'équipe</span>
          </div>
        </div>
        <span class="ms-auto">
          <span class="badge bg-success rounded-circle" style="width:12px; height:12px;"></span>
        </span>
      </div>

      <hr>

      <h6 class="mb-2">A propos</h6>
      <p class="text-muted mb-4">Dedicated team member working with colleagues to achieve project goals and company objectives.</p>

      <div class="row">
        <div class="col-md-6 mb-3">
          <div class="border rounded-3 p-3 h-100">
            <h6 class="mb-3">Solde de congés</h6>
            <div>Vacances <strong>15 days</strong></div>
            <div>Arrêt maladie <strong>10 days</strong></div>
            <div>Personnel <strong>5 days</strong></div>
            <div>Heure supplémentaire <strong>8 hours</strong></div>
          </div>
        </div>
        <div class="col-md-6 mb-3">
          <div class="border rounded-3 p-3 h-100">
            <h6 class="mb-3">Utilisation cette année</h6>
            <div>Vacances utilisé <strong>5 jours</strong></div>
            <div>Arrêt utilisé <strong>2 jours</strong></div>
            <div>Personnel utilisé <strong>1 jour</strong></div>
            <div>Heures supplémentaire <strong>12 heures</strong></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>