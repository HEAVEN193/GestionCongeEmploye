<?php 
use Matteomcr\GestionCongeEmploye\Models\Employe;
use Matteomcr\GestionCongeEmploye\Models\HeureSupplementaire;
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
      --color-red-50: #FEF2F2;
    --color-red-500: #EF4444;
    --color-red-600: #DC2626;
    --color-blue-50: #EFF6FF;
    --color-blue-100: #DBEAFE;
    --color-blue-500: #3B82F6;
    --color-blue-600: #2563EB;
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
    .btn-icon {
        padding: 0.25rem;
        border: none;
        background: none;
        border-radius: 9999px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-edit {
        color: var(--color-blue-600);
    }

    .btn-edit:hover {
        background-color: var(--color-blue-50);
    }

    .btn-delete {
        color: var(--color-red-500);
    }

    .btn-delete:hover {
        background-color: var(--color-red-50);
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
          <h1>Liste des département</h1>
       


        </div>
        <?php if (Employe::current() && Employe::current()->getRole()->NomRole == "Administrateur"): ?>
        <a href="/form-add-departement">
          <button class="btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="5" x2="12" y2="19"/>
              <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Ajouter un departement
          </button>
        </a>
        <?php endif; ?>
      </div>
    </header>

    <main class="main">
      <div class="employee-list">
        <table>
          <thead>
            <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Manager</th>
            <?php if (Employe::current() && Employe::current()->getRole()->NomRole == "Administrateur"): ?>
            <th>Edition</th>
            <?php endif; ?>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($departements as $departement): ?>
                <tr>
                    <td><?= htmlspecialchars($departement->idDepartement) ?></td>
                    <td><?= htmlspecialchars($departement->NomDepartement) ?></td>
                    <td><?= $departement->getManager() ? htmlspecialchars($departement->getManager()->Pseudo) : 'Aucun' ?></td>

                    <?php if (Employe::current() && Employe::current()->getRole()->NomRole == "Administrateur"): ?>

                    <td class="actions">
                        <span>
                            <a href="/form-update-departement/<?= $departement->idDepartement ?>">
                            <button class="btn-icon btn-edit" title="Modifier">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                        </svg>
                                    </button></a>
                        </span>
                        
                        <span>
                            <a href="/deleteDepartement/<?= $departement->idDepartement ?>">
                            <button class="btn-icon btn-delete" title="Supprimer">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18"></path>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                        </svg>
                                    </button></a>
                        </span>
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