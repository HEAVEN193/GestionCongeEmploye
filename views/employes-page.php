<?php 
use Matteomcr\GestionCongeEmploye\Models\Employe;
use Matteomcr\GestionCongeEmploye\Models\Departement;
use Matteomcr\GestionCongeEmploye\Models\Role;


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
    justify-content: flex-end;
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
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
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
                <h1>Gestion des Employés</h1>
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
                
                
                <form method="GET" action="/employes">
                    <div class="filter-group">
                        
                        
                        <div class="filter-select">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                            <div class="filter-select">
                                <select name="role" onchange="this.form.submit()">
                                    <option value="">Tous les rôles</option>
                                    <?php foreach (Role::fetchAll() as $role): ?>
                                        <option value="<?= $role->idRole ?>" 
                                        <?= isset($roleFiltre) && $roleFiltre == $role->idRole ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($role->NomRole) ?>
                                    </option>
                                    <?php endforeach; ?>
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

                    <?php if (Employe::current() && Employe::current()->getRole()->NomRole == "Administrateur"): ?>

                        <a href="/form-add-employe" class="btn-add d-inline-flex align-items-center gap-1">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Ajouter
                        </a>
                    <?php endif; ?>

                </div>
            </div>

            <div class="employee-list">
                <table>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Pseudo</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Département</th>
                            <?php if (Employe::current() && Employe::current()->getRole()->NomRole == "Administrateur"): ?>
                            <th>Actions</th>
                            <?php endif; ?>

                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($employes as $employe): ?>
                        <tr>
                            <td><?= htmlspecialchars($employe->Nom) ?></td>
                            <td><?= htmlspecialchars($employe->Prenom) ?></td>
                            <td class="pseudo"><?= htmlspecialchars($employe->Pseudo) ?></td>
                            <td class="email"><?= htmlspecialchars($employe->Email) ?></td>
                            <td><span class="badge badge-role"><?= htmlspecialchars($employe->getRole()->NomRole) ?></span></td>
                            <td><span class="badge badge-department"><?= htmlspecialchars($employe->getDepartement()->NomDepartement) ?></span></td>
                            <?php if (Employe::current() && Employe::current()->getRole()->NomRole == "Administrateur"): ?>

                            <td class="actions">
                                <a href="/form-update-employe/<?= $employe->idEmploye ?>">
                                    <button class="btn-icon btn-edit" title="Modifier">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                        </svg>
                                    </button></a>
                                <a href="/deleteEmploye/<?= $employe->idEmploye ?>">
                                    <button class="btn-icon btn-delete" title="Supprimer" onclick="return confirm('Supprimer cette utilisateur ?')">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18"></path>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </a>
                            </td>
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
       
               
            </div>
        </div>
       
    </main>
</body>

