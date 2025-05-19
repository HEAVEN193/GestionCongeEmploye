<?php 
use Matteomcr\GestionCongeEmploye\Models\Employe;
use Matteomcr\GestionCongeEmploye\Models\HeureSupplementaire;
use Matteomcr\GestionCongeEmploye\Models\Role;
use Matteomcr\GestionCongeEmploye\Models\Departement;
use Matteomcr\GestionCongeEmploye\Models\Conge;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="styles.css"> -->
     <style>
:root {
    --color-primary: #3B82F6;
    --color-success: #10B981;
    --color-danger: #EF4444;
    --color-warning: #F59E0B;
    --color-gray-50: #F9FAFB;
    --color-gray-100: #F3F4F6;
    --color-gray-200: #E5E7EB;
    --color-gray-700: #374151;
}

html * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

body {
    background-color: var(--color-gray-100);
}
h1{
    font-size: 1.5rem;
    font-weight: 600;
}
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding:30px;
}

.header {
    background-color: white;
    padding: 1rem;
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
.cards-container{
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    max-width: 1200px;
    margin: 30px auto;
}
.card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border-left: 4px solid;
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.pending-card {
    border-left-color: #3b82f6;
}

.approved-card {
    border-left-color: #10b981;
}

.total-card {
    border-left-color: #8b5cf6;
}

.overtime-card {
    border-left-color: #f97316;
}

.card-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.card-info h3 {
    color: #6b7280;
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 8px;
}

.card-value {
    font-size: 32px;
    font-weight: bold;
    color: #1f2937;
}

.card-icon {
    padding: 12px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pending-icon {
    background-color: #eff6ff;
    color: #3b82f6;
}

.approved-icon {
    background-color: #ecfdf5;
    color: #10b981;
}

.total-icon {
    background-color: #f5f3ff;
    color: #8b5cf6;
}

.overtime-icon {
    background-color: #fff7ed;
    color: #f97316;
}

.calendar {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.calendar-header {
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--color-gray-200);
}

.calendar-title {
    font-size: 1.5rem;
    font-weight: bold;
    color: var(--color-gray-700);
}

.calendar-nav {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 8px 16px;
    border: 1px solid var(--color-gray-200);
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.875rem;
    color: var(--color-gray-700);
    transition: all 0.2s;
}

.btn:hover {
    background: var(--color-gray-50);
}

.btn.active {
    background: var(--color-gray-100);
    color: var(--color-primary);
}

/* Filter Panel Styles */
.calendar-filters {
    position: relative;
    display: flex;
}

.filters-panel {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    background: white;
    border: 1px solid var(--color-gray-200);
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 16px;
    z-index: 10;
    min-width: 250px;
}

.filters-section h4 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--color-gray-700);
    margin-bottom: 12px;
}

.filter-options {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.filter-option {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: var(--color-gray-700);
    cursor: pointer;
}

.filter-option input[type="checkbox"] {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    border: 1px solid var(--color-gray-200);
    cursor: pointer;
    accent-color: var(--color-primary);
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

/* Calendar Content */
.calendar-content {
    padding: 20px;
}

.weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
    margin-bottom: 10px;
    text-align: center;
}

.weekdays span {
    padding: 10px;
    font-weight: 500;
    color: var(--color-gray-700);
}

/* Month View */
.month-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
}

.day {
    aspect-ratio: 1;
    padding: 8px;
    border: 1px solid var(--color-gray-200);
    border-radius: 6px;
    background: white;
}

.day.other-month {
    background: var(--color-gray-50);
    color: var(--color-gray-400);
}

.day.today {
    background: #EFF6FF;
    border-color: var(--color-primary);
}

/* Week View */
.week-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
    height: 600px;
}

.week-day {
    border: 1px solid var(--color-gray-200);
    border-radius: 6px;
    background: white;
    display: flex;
    flex-direction: column;
}

.week-day-header {
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid var(--color-gray-200);
    background: var(--color-gray-50);
}

.week-day-content {
    flex: 1;
    overflow-y: auto;
    padding: 8px;
}

/* Day View */
.day-grid {
    height: 600px;
    overflow-y: auto;
}

.day-view {
    display: flex;
    flex-direction: column;
}

.hour-slot {
    display: flex;
    min-height: 60px;
    border-bottom: 1px solid var(--color-gray-200);
}

.hour-time {
    width: 60px;
    padding: 8px;
    color: var(--color-gray-700);
    font-size: 0.875rem;
}

.hour-events {
    flex: 1;
    padding: 4px;
}

/* Events */
.event {
    margin: 2px 0;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.875rem;
}

.event.vacation { background: #DBEAFE; color: #1E40AF; }
.event.conge { background: #DBEAFE; color: #1E40AF; }
.event.conge.valide { background: #D1FAE5; color: #065F46; }
.event.conge.refuse { display: none; }

.event.evaluation { background: #FCE7F3; color: #9D174D; }
.event.reunion { background: #FEF3C7; color: #92400E; }
.event.ferie { background: #FEE2E2; color: #991B1B; }
.event.evenement { background: #F3E8FF; color: #6B21A8; }
/* .event.holiday { background: #D1FAE5; color: #065F46; } */

/* Legend */
.legend {
    padding: 20px;
    border-top: 1px solid var(--color-gray-200);
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

@media (max-width: 768px) {
    .calendar-header {
        flex-direction: column;
        gap: 10px;
    }

    .calendar-nav {
        width: 100%;
        justify-content: center;
    }
}
     </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="header-content">
                <div class="header-title">
                    <div class="card-icon stats-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960" width="30px" fill="#3B82F6"><path d="M520-640v-160q0-17 11.5-28.5T560-840h240q17 0 28.5 11.5T840-800v160q0 17-11.5 28.5T800-600H560q-17 0-28.5-11.5T520-640ZM120-480v-320q0-17 11.5-28.5T160-840h240q17 0 28.5 11.5T440-800v320q0 17-11.5 28.5T400-440H160q-17 0-28.5-11.5T120-480Zm400 320v-320q0-17 11.5-28.5T560-520h240q17 0 28.5 11.5T840-480v320q0 17-11.5 28.5T800-120H560q-17 0-28.5-11.5T520-160Zm-400 0v-160q0-17 11.5-28.5T160-360h240q17 0 28.5 11.5T440-320v160q0 17-11.5 28.5T400-120H160q-17 0-28.5-11.5T120-160Zm80-360h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360-280Z"/></svg>
                    </div>
                    <h1>Tableau de bord</h1>
                </div>
            </div>
        </header>
        <?php if (Employe::current() && Employe::current()->getRole()->NomRole == "Employe"): ?>
        <!-- Carte des congés en attente -->
         <div class="cards-container">
            <div class="card pending-card">
                <div class="card-content">
                    <div class="card-info">
                        <h3>Solde congés vacances</h3>
                        <p class="card-value">
                        <?php
                                    $resultat = Employe::current()->SoldeConge;
                                    echo $resultat . " jours";    
                                ?>
                        </p>
                    </div>
                    <div class="card-icon pending-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Carte des congés approuvés -->
            <div class="card approved-card">
                <div class="card-content">
                    <div class="card-info">
                        <h3>Solde congés heures supp.</h3>
                        <p class="card-value">
                        <?php
                            $resultat = floor(Employe::current()->SoldeCongeHeureSupp);
                            echo $resultat . " jours";    
                        ?>
                        </p>
                    </div>
                    <div class="card-icon approved-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                </div>
            </div>
                <div class="card overtime-card">
                <div class="card-content">
                    <div class="card-info">
                        <h3>Heures supplémentaires</h3>
                        <p class="card-value">
                        <?php
                            $resultat = Employe::current()->getTotalOvertime();
                            $heures = isset($resultat['heures']) ? $resultat['heures'] : 0;
                            echo $heures . " heures";    
                        ?>
                        </p>
                    </div>
                    <div class="card-icon overtime-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>



        <div class="calendar">
            <div class="calendar-header">
                <h2 class="calendar-title">Janvier 2025</h2>
                <div class="calendar-nav">
                    <div class="btn-group">
                        <button class="btn active" data-view="month">Mois</button>
                        <button class="btn" data-view="week">Semaine</button>
                        <button class="btn" data-view="day">Jour</button>
                    </div>
                    <button class="btn" id="prevBtn">&lt;</button>
                    <button class="btn" id="todayBtn">Aujourd'hui</button>
                    <button class="btn" id="nextBtn">&gt;</button>
                </div>
                <div class="calendar-filters">
                    <button class="btn" id="filterButton">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                        Types
                    </button>
                    <div class="filters-panel" id="filtersPanel" style="display: none;">
                        <div class="filters-section">
                            <h4>Types d'événements</h4>
                            <div class="filter-options">
                                <label class="filter-option">
                                    <input type="checkbox" value="conge" checked> Congés
                                </label>
                                <label class="filter-option">
                                    <input type="checkbox" value="reunion" checked> Réunions
                                </label>
                                <label class="filter-option">
                                    <input type="checkbox" value="evaluation" checked> Évaluations
                                </label>
                                <label class="filter-option">
                                    <input type="checkbox" value="ferie" checked> Jours fériés
                                </label>
                                <label class="filter-option">
                                    <input type="checkbox" value="evenement" checked> Événements
                                </label>
                            </div>
                        </div>
                    </div>

                    <?php if (Employe::current() && Employe::current()->getRole()->NomRole == "Administrateur"): ?>
                        <form method="GET" action="/" class="filter-select">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>

                            <select name="departement" onchange="this.form.submit()">
                                <option value="">Tous les départements</option>
                                <?php foreach (Departement::fetchAll() as $dep): ?>
                                    <option value="<?= $dep->idDepartement ?>" 
                                        <?= isset($departementFiltre) && $departementFiltre == $dep->idDepartement ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($dep->NomDepartement) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div id="calendarContent" class="calendar-content">
                <div class="weekdays">
                <span>Lun</span>
                    <span>Mar</span>
                    <span>Mer</span>
                    <span>Jeu</span>
                    <span>Ven</span>
                    <span>Sam</span>
                    <span>Dim</span>
                </div>
                <div id="calendarGrid"></div>
            </div>

            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color" style="background: #3b82f6;"></div>
                    <span>Congés en attente</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #10b981;"></div>
                    <span>Congé approuvé</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #f59e0b;"></div>
                    <span>Réunions</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #ec4899;"></div>
                    <span>Évaluations</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #ef4444;"></div>
                    <span>Jour fériés</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: #8b5cf6;"></div>
                    <span>Evénement</span>
                </div>
            </div>
        </div>
    </div>

    <script>
    class Calendar {
    constructor() {
        this.currentDate = new Date();
        this.currentView = 'month';
        this.activeFilters = new Set(['conge', 'reunion', 'evaluation', 'ferie', 'evenement']);
     
        this.events = <?= json_encode($eventsFromPHP) ?>;
        this.events = this.events.concat([
            { date: '2025-05-06', type: 'reunion', title: 'Réunion' },
            { date: '2025-05-08', type: 'evaluation', title: 'Evaluation mensuelle' },
            { date: '2025-05-22', type: 'ferie', title: 'Fête du travail' },
            { date: '2025-05-12', type: 'evenement', title: 'Evénement' },
        ]);

        console.log(this.events);
        this.setupEventListeners();
        this.render();
    }

    setupEventListeners() {
        // Navigation buttons
        document.getElementById('prevBtn').addEventListener('click', () => this.navigate('prev'));
        document.getElementById('nextBtn').addEventListener('click', () => this.navigate('next'));
        document.getElementById('todayBtn').addEventListener('click', () => {
            this.currentDate = new Date();
            this.render();
        });

        // View buttons
        document.querySelectorAll('[data-view]').forEach(button => {
            button.addEventListener('click', (e) => {
                document.querySelectorAll('[data-view]').forEach(btn => btn.classList.remove('active'));
                e.target.classList.add('active');
                this.currentView = e.target.dataset.view;
                this.render();
            });
        });

        // Filter button
        const filterButton = document.getElementById('filterButton');
        const filtersPanel = document.getElementById('filtersPanel');
        
        filterButton.addEventListener('click', (e) => {
            e.stopPropagation();
            filtersPanel.style.display = filtersPanel.style.display === 'none' ? 'block' : 'none';
        });

        document.addEventListener('click', (e) => {
            if (!filterButton.contains(e.target) && !filtersPanel.contains(e.target)) {
                filtersPanel.style.display = 'none';
            }
        });

        // Filter checkboxes
        document.querySelectorAll('.filter-option input').forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                if (checkbox.checked) {
                    this.activeFilters.add(checkbox.value);
                } else {
                    this.activeFilters.delete(checkbox.value);
                }
                this.render();
            });
        });
    }

    navigate(direction) {
        switch(this.currentView) {
            case 'month':
                this.currentDate.setMonth(this.currentDate.getMonth() + (direction === 'prev' ? -1 : 1));
                break;
            case 'week':
                this.currentDate.setDate(this.currentDate.getDate() + (direction === 'prev' ? -7 : 7));
                break;
            case 'day':
                this.currentDate.setDate(this.currentDate.getDate() + (direction === 'prev' ? -1 : 1));
                break;
        }
        this.render();
    }

    getEventsForDate(date) {
        // Format the date to YYYY-MM-DD, handling timezone offset
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const dateString = `${year}-${month}-${day}`;
        
        return this.events.filter(event => 
            event.date === dateString && 
            this.activeFilters.has(event.type)
        );
    }

    render() {
        const container = document.getElementById('calendarContent');
        const grid = document.getElementById('calendarGrid');
        grid.innerHTML = '';

        // Update title
        const titleFormat = {
            month: { month: 'long', year: 'numeric' },
            week: { day: 'numeric', month: 'long', year: 'numeric' },
            day: { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }
        };
        
        document.querySelector('.calendar-title').textContent = 
            this.currentDate.toLocaleDateString('fr-FR', titleFormat[this.currentView]);

        switch(this.currentView) {
            case 'month':
                this.renderMonth(grid);
                break;
            case 'week':
                this.renderWeek(grid);
                break;
            case 'day':
                this.renderDay(grid);
                break;
        }
    }

    renderMonth(container) {
        container.className = 'month-grid';
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        
        // Get the day of the week for the first day (0 = Sunday)
        let firstDayIndex = firstDay.getDay();
        // Convert to Monday-based index (0 = Monday)
        firstDayIndex = firstDayIndex === 0 ? 6 : firstDayIndex - 1;
        
        const days = [];
        const totalDays = firstDayIndex + lastDay.getDate();
        const totalWeeks = Math.ceil(totalDays / 7);
        
        for (let i = 0; i < totalWeeks * 7; i++) {
            const date = new Date(year, month, i - firstDayIndex + 1);
            const isOtherMonth = date.getMonth() !== month;
            
            const dayEl = document.createElement('div');
            dayEl.className = `day${isOtherMonth ? ' other-month' : ''}`;
            
            if (date.toDateString() === new Date().toDateString()) {
                dayEl.classList.add('today');
            }
            
            const dayNumber = document.createElement('div');
            dayNumber.className = 'day-number';
            dayNumber.textContent = date.getDate();
            dayEl.appendChild(dayNumber);
            
            const events = this.getEventsForDate(date);
            if (events.length > 0) {
                const eventsContainer = document.createElement('div');
                eventsContainer.className = 'events';
                
                events.forEach(event => {
                    const eventEl = document.createElement('div');
                    eventEl.className = `event ${event.type}`;
                    console.log(event.statut)
                    if(event.statut == "Valide"){
                        eventEl.className += ` valide`;
                    }
                    if(event.statut == "En attente"){
                        eventEl.className += ` attente`;
                    }
                    if(event.statut == "Refuse"){
                        eventEl.className += ` refuse`;
                    }
                    eventEl.textContent = event.title;
                    eventsContainer.appendChild(eventEl);
                });
                
                dayEl.appendChild(eventsContainer);
            }
            
            container.appendChild(dayEl);
        }
    }

    renderWeek(container) {
        container.className = 'week-grid';
        const weekStart = new Date(this.currentDate);
        // Adjust to start from Monday
        const currentDay = weekStart.getDay();
        const diff = currentDay === 0 ? -6 : 1 - currentDay;
        weekStart.setDate(weekStart.getDate() + diff);

        for (let i = 0; i < 7; i++) {
            const date = new Date(weekStart);
            date.setDate(weekStart.getDate() + i);
            
            const dayEl = document.createElement('div');
            dayEl.className = 'week-day';
            
            const header = document.createElement('div');
            header.className = 'week-day-header';
            header.textContent = date.toLocaleDateString('fr-FR', {
                weekday: 'short',
                day: 'numeric'
            });
            dayEl.appendChild(header);
            
            const content = document.createElement('div');
            content.className = 'week-day-content';
            
            const events = this.getEventsForDate(date);
            events.forEach(event => {
                const eventEl = document.createElement('div');
                eventEl.className = `event ${event.type}`;
                eventEl.textContent = event.title;
                content.appendChild(eventEl);
            });
            
            dayEl.appendChild(content);
            container.appendChild(dayEl);
        }
    }

    renderDay(container) {
        container.className = 'day-grid';
        
        const dayEl = document.createElement('div');
        dayEl.className = 'day-view';
        
        const events = this.getEventsForDate(this.currentDate);
        
        for (let hour = 0; hour < 24; hour++) {
            const hourEl = document.createElement('div');
            hourEl.className = 'hour-slot';
            
            const timeEl = document.createElement('div');
            timeEl.className = 'hour-time';
            timeEl.textContent = `${hour.toString().padStart(2, '0')}:00`;
            hourEl.appendChild(timeEl);
            
            const eventsEl = document.createElement('div');
            eventsEl.className = 'hour-events';
            
            const hourEvents = events.filter(event => {
                const eventDate = new Date(event.date);
                return eventDate.getHours() === hour;
            });
            
            hourEvents.forEach(event => {
                const eventEl = document.createElement('div');
                eventEl.className = `event ${event.type}`;
                eventEl.textContent = event.title;
                eventsEl.appendChild(eventEl);
            });
            
            hourEl.appendChild(eventsEl);
            dayEl.appendChild(hourEl);
        }
        
        container.appendChild(dayEl);
    }
}

document.addEventListener('DOMContentLoaded', () => new Calendar());
    </script>
</body>
</html>