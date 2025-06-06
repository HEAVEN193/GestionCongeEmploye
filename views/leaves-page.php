<?php 
use Matteomcr\GestionCongeEmploye\Models\Employe;
use Matteomcr\GestionCongeEmploye\Models\HeureSupplementaire;
use Matteomcr\GestionCongeEmploye\Models\Role;
use Matteomcr\GestionCongeEmploye\Models\Departement;
use Matteomcr\GestionCongeEmploye\Models\Conge;
?>

<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --color-primary: #3B82F6;
      --color-primary-dark: #2563EB;
      --color-success: #10B981;
      --color-success-light: #D1FAE5;
      --color-danger: #EF4444;
      --color-danger-light: #FEE2E2;
      --color-warning: #F59E0B;
      --color-warning-light: #FEF3C7;
      --color-gray-50: #F9FAFB;
      --color-gray-100: #F3F4F6;
      --color-gray-200: #E5E7EB;
      --color-gray-300: #D1D5DB;
      --color-gray-400: #9CA3AF;
      --color-gray-500: #6B7280;
      --color-gray-600: #4B5563;
      --color-gray-700: #374151;
      --color-gray-800: #1F2937;
      --color-gray-900: #111827;
    }

    html * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
      background-color: var(--color-gray-100);
      color: var(--color-gray-900);
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 1rem;
    }

    .header {
      background-color: white;
      padding: 1.5rem;
      border-radius: 0.5rem;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      margin-bottom: 2rem;
    }

    .header-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .header-title {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .header-title svg {
        color: var(--color-primary);
    }

    .header-title h1 {
      font-size: 1.5rem;
      font-weight: 600;
    }

    .btn-primary {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.625rem 1rem;
      background-color: var(--color-primary-dark);
      color: white;
      border: none;
      border-radius: 0.5rem;
      font-size: 0.875rem;
      cursor: pointer;
    }
    /* Filter bar styles */
    .filter-bar {
        background: white;
        padding: 1rem;
        border-radius: 0.5rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
        justify-content: end;
    }

    .search-box {
        flex: 1;
        min-width: 200px;
        position: relative;
    }

    .search-box svg {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--color-gray-400);
    }

    .search-box input {
        width: 100%;
        padding: 0.5rem 0.75rem 0.5rem 2.5rem;
        border: 1px solid var(--color-gray-200);
        border-radius: 0.375rem;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--color-blue-500);
        box-shadow: 0 0 0 3px var(--color-blue-100);
    }

    .filter-group {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;

    }

    .filter-select {
        position: relative;
    }

    .filter-select svg {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--color-gray-400);
        pointer-events: none;
    }

    .filter-select select {
        padding: 0.5rem 2rem 0.5rem 2.5rem;
        border: 1px solid var(--color-gray-200);
        border-radius: 0.375rem;
        font-size: 0.875rem;
        background-color: white;
        cursor: pointer;
        appearance: none;
    }

    .filter-select select:focus {
        outline: none;
        border-color: var(--color-blue-500);
        box-shadow: 0 0 0 3px var(--color-blue-100);
    }

    .filter-select::after {
        content: '';
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 4px solid var(--color-gray-400);
        pointer-events: none;
    }
    .filter-date {
        display: flex;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        align-items: center;
    }

    .filter-date label {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: #333;
    }

    .filter-date input[type="date"] {
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.2s ease;
    }

    .filter-date input[type="date"]:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.2);
    }

    .btn-add {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background-color: var(--color-blue-600);
    color: white;
    border: none;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s;
  }

  .btn-add:hover {
      background-color: var(--color-blue-500);
  }

    .main {
      padding: 1.5rem 0;
    }

    .employee-list {
      background: white;
      border-radius: 0.5rem;
      box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 0.75rem 1rem;
      font-size: 0.875rem;
      border-bottom: 1px solid var(--color-gray-200);
    }

    .pseudo {
        font-family: monospace;
        color: var(--color-gray-500);
    }

    .text-light {
        color: var(--color-gray-500);
    }

    .actions{
        white-space: nowrap;
        text-align: right;
    }

    .badge {
      display: inline-flex;
      padding: 0.25rem 0.75rem;
      border-radius: 9999px;
      font-size: 0.75rem;
      font-weight: 500;
    }

    .badge-success {
      background-color: var(--color-success-light);
      color: var(--color-success);
    }

    .badge-danger {
      background-color: var(--color-danger-light);
      color: var(--color-danger);
    }

    .badge-warning {
      background-color: var(--color-warning-light);
      color: var(--color-warning);
    }

    .badge-etat {
        background-color: var(--color-gray-100);
        color: var(--color-gray-800);
    }

    .btn-icon {
      display: inline-flex;
      justify-content: center;
      align-items: center;
      padding: 0.375rem;
      border: none;
      border-radius: 0.375rem;
      cursor: pointer;
    }

    .btn-success {
    color: var(--color-success);
    background-color: var(--color-success-light);
    }

    .btn-success:hover {
        background-color: var(--color-success-light);
        filter: brightness(0.95);
    }

    .btn-danger {
        color: var(--color-danger);
        background-color: var(--color-danger-light);
    }

    .btn-danger:hover {
        background-color: var(--color-danger-light);
        filter: brightness(0.95);
    }

    .text-muted {
        color: var(--color-gray-400);
        font-style: italic;
        font-size: 0.875rem;
    }
  </style>
</head>

<body>
  <div class="container">
    <header class="header">
      <div class="header-content">
        <div class="header-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <polyline points="12 6 12 12 16 14"/>
          </svg>
          <?php if (Employe::current() && Employe::current()->getRole()->NomRole == "Employe"): ?>
            <h1>Mes demandes de congés</h1>
          <?php else: ?>
              <h1>Demandes de congés</h1>
          <?php endif; ?>


        </div>

        <?php if (Employe::current() && Employe::current()->getRole()->NomRole != "Administrateur"): ?>
        <a href="/form-add-leave">
          <button class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="5" x2="12" y2="19"/>
              <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Soumettre une demande
          </button>
        </a>
          
          <?php endif; ?>
      </div>
    </header>
      <?php if (Employe::current() && Employe::current()->getRole()->NomRole != "Employe"): ?>
        <div class="filter-bar">
                
                
                <form method="GET" action="/leaves-page">
                    <div class="filter-group">
                        
                    <!-- Filtre période -->
                    <div class="filter-date">
                      <label for="dateDebut">Du : </label>
                      <input type="date" name="dateDebut" id="dateDebut" value="<?= htmlspecialchars($_GET['dateDebut'] ?? '') ?>" onchange="this.form.submit()">
                  </div>

                  <div class="filter-date">
                      <label for="dateFin">Au : </label>
                      <input type="date" name="dateFin" id="dateFin" value="<?= htmlspecialchars($_GET['dateFin'] ?? '') ?>" onchange="this.form.submit()">
                  </div>
                        
                        <div class="filter-select">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                            <div class="filter-select">
                                <select name="type" onchange="this.form.submit()">
                                    <option value="">Tous les congé</option>
                                    <option value="vacances" <?= isset($typeFiltre) && $typeFiltre == "vacances" ? 'selected' : '' ?>>vacances</option>
                                    <option value="conversion" <?= isset($typeFiltre) && $typeFiltre == "conversion" ? 'selected' : '' ?>>conversion</option>
                                </select>
                            </div>
                        </div>
                        <?php if (Employe::current() && Employe::current()->getRole()->NomRole == "Administrateur"): ?>

                        <div class="filter-select">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>

                            <div class="filter-select">
                                <select name="departement" onchange="this.form.submit()">
                                    <option value="">Tous les départements</option>
                                    <?php foreach (Departement::fetchAll() as $dep): ?>
                                        <option value="<?= $dep->idDepartement ?>" 
                                        <?= isset($departementFiltre) && $departementFiltre == $dep->idDepartement ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($dep->NomDepartement) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                        </div>
                      <?php endif; ?>

                      </form>
                      
                      
                      
                    </div>
                  </div>
                  <?php endif; ?>
          
                  
    <main class="main">
      <div class="employee-list">
        <table>
          <thead>
            <tr>
              <th>Employé</th>
              <th>Type</th>
              <th>Durée</th>
              <th>Date début</th>
              <th>Date fin</th>
              <th>Statut</th>
              <th>Etat</th>
              <th>Justification</th>
              <?php if (Employe::current()->getRole()->NomRole === 'Manager'): ?>
              <th class="actions">Actions</th>
              <?php endif; ?>
              <?php if (Employe::current()->getRole()->NomRole === 'Employe'): ?>
              <th class="commentaire">Commentaire</th>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($conges as $conge): ?>
            <tr>
              <td class="pseudo"><?= htmlspecialchars($conge->getEmploye()->Pseudo) ?></td>
              <td><?= htmlspecialchars($conge->TypeConge) ?></td>
              <td><?= htmlspecialchars($conge->getDuree()). " jours" ?></td>
              <td><?= htmlspecialchars($conge->DateDebut)?></td>
              <td><?= htmlspecialchars($conge->DateFin)?></td>
              <td>
                <?php if ($conge->Statut === 'Valide'): ?>
                    <span class="badge badge-success">Validée</span>
                <?php elseif ($conge->Statut === 'Refuse'): ?>
                    <span class="badge badge-danger">Refusée</span>
                <?php else: ?>
                    <span class="badge badge-warning">En attente</span>
                <?php endif; ?>
                </td>

              <td>
                <?php $etat = $conge->getEtat(); ?>

                <?php if ($etat === 'a venir'): ?>
                    <span class="badge bg-secondary">À venir</span>
                <?php elseif ($etat === 'en cours'): ?>
                    <span class="badge bg-primary">En cours</span>
                <?php elseif ($etat === 'passe'): ?>
                    <span class="badge bg-dark">Passé</span>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($conge->Justification) ?></td>
              <?php if (Employe::current()->getRole()->NomRole === 'Manager'): ?>
              <td class="actions">
                <?php if ($conge->Statut === 'En attente'): ?>
            
                  <form action="/handle-leave-request/<?= $conge->idConge ?>" method="POST" style="display:inline;">
                      <textarea name="commentaire" placeholder="Commentaire..." class="form-control mb-1" rows="1"></textarea>

                      <!-- Champ caché pour connaître l'action choisie -->
                      <input type="hidden" name="action" value="" id="action-<?= $conge->idConge ?>">

                      <!-- Bouton Valider -->
                      <button type="submit" class="btn-icon btn-success"
                          onclick="document.getElementById('action-<?= $conge->idConge ?>').value='valider'">
                          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                              <polyline points="20 6 9 17 4 12"/>
                              </svg>
                      </button>

                      <!-- Bouton Refuser -->
                      <button type="submit" class="btn-icon btn-danger"
                          onclick="document.getElementById('action-<?= $conge->idConge ?>').value='refuser'">
                          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                      </button>
                  </form>

                <?php else: ?>
                <span class="text-muted">Action non disponible</span>
                <?php endif; ?>
              </td>
              
              <?php endif; ?>
              
              <?php if (Employe::current()->getRole()->NomRole === 'Employe'): ?>
              <td>
              <?php $commentaire = $conge->CommentaireManager ?? $conge->CommentaireValidation ?? null; ?>

              <?php if (!empty($commentaire)): ?>
                  <?= htmlspecialchars($commentaire) ?>
              <?php else: ?>
                  <span class="text-muted">Aucun commentaire</span>
              <?php endif; ?>
              </td>
              <?php endif; ?>

            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger mt-3" role="alert">
          <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
        <?php endif; ?>
      </div>
    </main>
  </div>
</body>