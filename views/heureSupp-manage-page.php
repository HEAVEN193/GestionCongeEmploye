<?php 
use Matteomcr\GestionCongeEmploye\Models\Employe;
use Matteomcr\GestionCongeEmploye\Models\Departement;
use Matteomcr\GestionCongeEmploye\Models\Role;
use Matteomcr\GestionCongeEmploye\Models\HeureSupplementaire;



?>
<head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html *{
            padding: 0;
        }
/* Base styles */
:root {
    --color-primary: #3B82F6;
    --color-primary-dark: #2563EB;
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
    --color-red-50: #FEF2F2;
    --color-red-500: #EF4444;
    --color-red-600: #DC2626;
    --color-blue-50: #EFF6FF;
    --color-blue-100: #DBEAFE;
    --color-blue-500: #3B82F6;
    --color-blue-600: #2563EB;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
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

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    background-color: var(--color-gray-100);
    color: var(--color-gray-900);
    line-height: 1.5;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* Header styles */
.header {
    background: linear-gradient(to right, var(--color-blue-600), var(--color-blue-500));
    color: white;
    padding: 1rem;
    box-shadow: var(--shadow-md);
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

.header-title h1 {
    font-size: 1.5rem;
    font-weight: bold;
}

.header-date {
    font-size: 0.875rem;
    opacity: 0.8;
}

/* Main content styles */
.main {
    padding: 1.5rem 0;
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

/* Button styles */
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

/* Employee list styles */
.employee-list {
    background: white;
    border-radius: 0.5rem;
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background-color: var(--color-gray-50);
    padding: 0.75rem 1rem;
    text-align: left;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--color-gray-700);
    border-bottom: 1px solid var(--color-gray-200);
}

td {
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    border-bottom: 1px solid var(--color-gray-200);
}

tr:hover {
    background-color: var(--color-gray-50);
}

.pseudo {
    font-family: monospace;
    color: var(--color-gray-500);
}

.email {
    color: var(--color-gray-500);
}

.badge {
    display: inline-flex;
    align-items: center;
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

.badge-role {
    background-color: var(--color-blue-50);
    color: var(--color-blue-600);
}

.badge-department {
    background-color: var(--color-gray-100);
    color: var(--color-gray-800);
}

.actions {
    white-space: nowrap;
    text-align: right;
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

.table-footer {
    padding: 0.75rem 1rem;
    background-color: var(--color-gray-50);
    font-size: 0.875rem;
    color: var(--color-gray-500);
    text-align: right;
    border-top: 1px solid var(--color-gray-200);
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .filter-bar {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-group {
        flex-direction: column;
    }

    .filter-select {
        width: 100%;
    }

    .filter-select select {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        text-align: center;
    }

    .header-title {
        justify-content: center;
    }

    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
}
    </style>
</head>


<body>

        <div class="container header-content">
            <div class="header-title">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                <h1>Gestion des heures supplémentaires</h1>
            </div>
            <div class="header-date">
                <?php if (Employe::current() && Employe::current()->getRole()->NomRole == "Administrateur"): ?>
                Système d'Administration • 
                <?php else: ?>
                    Système Manager • 
                    <?php endif; ?>

                <span id="current-date"></span>
            </div>
        </div>
        

    <main class="main">
        <div class="container">
            <div class="filter-bar">
                <div class="search-box">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" placeholder="Rechercher un employé...">
                </div>
                
            

                    <?php if (Employe::current() && Employe::current()->getRole()->NomRole == "Administrateur"): ?>

                    <a href="/form-add-employe">
                        <button class="btn-add">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Ajouter
                        </button>
                    </a>
                    <?php endif; ?>

                </div>

            <div class="employee-list">
                <table>
                    <thead>
                        <tr>
                            <th>Employé</th>
                            <th>Date</th>
                            <th>Heure</th>
                            <th>Statut</th>
                            <th>Conversion</th>
                            <?php if (Employe::current()->getRole()->NomRole === 'Manager'): ?>
                            <th class="actions">Actions</th>
                            <?php endif; ?>

                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($heuresSupp as $releve): ?>
                        <tr>
                            <td><?= htmlspecialchars($releve->getEmploye()->Pseudo) ?></td>
                            <td class="email"><?= htmlspecialchars($releve->DateSoumission) ?></td>
                            <td class="email"><?= htmlspecialchars($releve->NbreHeure) ?></td>
                            <td>
                                <?php if ($releve->Statut === 'Valide'): ?>
                                  <span class="badge badge-success">Validée</span>
                                <?php elseif ($releve->Statut === 'Refuse'): ?>
                                  <span class="badge badge-danger ">Refusée</span>
                                <?php else: ?>
                                  <span class="badge badge-warning text-dark">En attente</span>
                                <?php endif; ?>
                              </td>
                            </td>

                            <td><span class="email"><?= htmlspecialchars($releve->ConversionType) ?></span></td>
                            
                            <?php if (Employe::current()->getRole()->NomRole === 'Manager'): ?>
                            <td class="actions">
                              <?php if ($releve->Statut === 'En attente'): ?>
                                <a href="/validerHeureSupp/<?= $releve->idHeureSupp ?>">
                                    <button class="btn-icon btn-edit" title="Modifier">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>

                                    </button></a>
                                <a href="/refuserHeureSupp/<?= $releve->idHeureSupp ?>">
                                    <button class="btn-icon btn-delete" title="Supprimer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>

                                    </button></a>
                                <?php else: ?>
                                  <span >Action non disponible</span>
                                  
                                </td>
                                <?php endif; ?>
                                <?php endif; ?>
                        </tr>
                        <!-- More employee rows would be dynamically added here -->
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php
                    if(isset($_SESSION['error'])){
                        echo '<div class="alert alert-danger" mb-4 role="alert">' .$_SESSION['error'] . '</div>';
                        unset($_SESSION['error']); 
                    } 
                ?>
       
                <!-- <div class="table-footer">
                    <span>1 employé au total</span>
                </div> -->
            </div>
        </div>
    </main>
</body>

