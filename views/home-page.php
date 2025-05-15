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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Calendrier</title>
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
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        body {
            background-color: #f3f4f6;
            padding: 20px;
        }

        .calendar {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .calendar-header {
            background: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e5e7eb;
        }

        .calendar-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1f2937;
        }

        .calendar-nav {
            display: flex;
            gap: 10px;
        }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      /* padding: 0 1rem; */
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

    .calendar-filters {
    position: relative;
}

.filters-panel {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 8px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 16px;
    z-index: 10;
    min-width: 250px;
}

.filters-section {
    margin-bottom: 16px;
}

.filters-section h4 {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.filter-options {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-option {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #4b5563;
    cursor: pointer;
}

.filter-option input[type="checkbox"] {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    border: 1px solid #d1d5db;
    cursor: pointer;
}

/* Modification du style des boutons de vue */
.view-buttons {
    display: flex;
    gap: 4px;
    margin-right: 12px;
}

.view-button {
    padding: 6px 12px;
    border: 1px solid #e5e7eb;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.875rem;
    color: #374151;
    transition: all 0.2s;
}

.view-button.active {
    background: #eff6ff;
    color: #2563eb;
    border-color: #2563eb;
}

        .btn {
            padding: 8px 16px;
            border: 1px solid #e5e7eb;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.875rem;
            color: #374151;
            transition: all 0.2s;
        }

        .btn:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        .btn-group {
            display: flex;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
        }

        .btn-group .btn {
            border: none;
            border-radius: 0;
            border-right: 1px solid #e5e7eb;
        }

        .btn-group .btn:last-child {
            border-right: none;
        }

        .btn-group .btn.active {
            background: #f3f4f6;
            color: #2563eb;
        }

        .calendar-grid {
            padding: 20px;
        }

        /* Vue semaine */
.calendar-week {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
    height: 600px;
}

.week-day {
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    background: white;
    display: flex;
    flex-direction: column;
}

.week-day-header {
    padding: 8px;
    text-align: center;
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
}

.week-day-content {
    flex: 1;
    overflow-y: auto;
    padding: 8px;
}

/* Vue jour */
.calendar-day {
    height: 600px;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    background: white;
}

.day-header {
    padding: 16px;
    text-align: center;
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
}

.day-content {
    height: calc(100% - 60px);
    overflow-y: auto;
    padding: 16px;
}

.hour-slot {
    display: flex;
    align-items: center;
    padding: 8px;
    border-bottom: 1px solid #e5e7eb;
}

.hour-label {
    width: 60px;
    color: #6b7280;
    font-size: 0.875rem;
}

.hour-events {
    flex: 1;
    min-height: 40px;
}

        .weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
            margin-bottom: 10px;
        }

        .weekdays span {
            text-align: center;
            font-weight: 500;
            color: #6b7280;
            font-size: 0.875rem;
            padding: 10px;
        }

        .days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
        }

        .day {
            aspect-ratio: 1;
            padding: 8px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }

        .day:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        .day.today {
            background: #eff6ff;
            border-color: #3b82f6;
            color: #2563eb;
            font-weight: bold;
        }

        .day.other-month {
            color: #9ca3af;
            background: #f9fafb;
        }

        .day-number {
            font-size: 0.875rem;
            margin-bottom: 4px;
            position: relative;
            z-index: 2;
        }

        .events {
            display: flex;
            flex-direction: column;
            gap: 2px;
            margin-top: 4px;
            position: relative;
            z-index: 2;
        }

        .event {
            padding: 4px 6px;
            border-radius: 4px;
            font-size: 0.75rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .event::before {
            content: '';
            display: block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .event.vacation { 
            background: rgba(219, 234, 254, 0.8);
            color: #1e40af;
        }
        .event.vacation::before { background: #3b82f6; }

        .event.sick { 
            background: rgba(254, 226, 226, 0.8);
            color: #991b1b;
        }
        .event.sick::before { background: #ef4444; }

        .event.personal { 
            background: rgba(243, 232, 255, 0.8);
            color: #6b21a8;
        }
        .event.personal::before { background: #8b5cf6; }

        .event.overtime { 
            background: rgba(255, 247, 237, 0.8);
            color: #9a3412;
        }
        .event.overtime::before { background: #f97316; }

        .event.holiday { 
            background: rgba(220, 252, 231, 0.8);
            color: #166534;
        }
        .event.holiday::before { background: #10b981; }

        .event.meeting { 
            background: rgba(254, 243, 199, 0.8);
            color: #92400e;
        }
        .event.meeting::before { background: #f59e0b; }

        .event.evaluation { 
            background: rgba(252, 231, 243, 0.8);
            color: #9d174d;
        }
        .event.evaluation::before { background: #ec4899; }

        .day-indicator {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
        }

        .day.has-holiday .day-indicator { background: #10b981; }
        .day.has-vacation .day-indicator { background: #3b82f6; }
        .day.has-meeting .day-indicator { background: #f59e0b; }
        .day.has-evaluation .day-indicator { background: #ec4899; }

        .event-count {
            position: absolute;
            bottom: 4px;
            right: 4px;
            background: #6b7280;
            color: white;
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 10px;
            z-index: 2;
        }

        .legend {
            padding: 20px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            color: #4b5563;
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

            .day {
                font-size: 0.75rem;
            }

            .events {
                display: none;
            }

            .event-count {
                display: block;
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
        </div>
      </div>
    </header>
    <div class="calendar">
        <div class="calendar-header">
            <h2 class="calendar-title">Janvier 2025</h2>
            <div class="calendar-nav">
                <div class="btn-group">
                    <button class="btn active">Mois</button>
                    <button class="btn">Semaine</button>
                    <button class="btn">Jour</button>
                </div>
                <button class="btn" id="prevMonth">&lt;</button>
                <button class="btn">Aujourd'hui</button>
                <button class="btn" id="nextMonth">&gt;</button>
            </div>
                    <div class="calendar-filters">
            <button class="btn" id="filterButton">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
                Filtres
            </button>
            <div class="filters-panel" id="filtersPanel" style="display: none;">
                <div class="filters-section">
                    <h4>Types d'événements</h4>
                    <div class="filter-options">
                        <label class="filter-option">
                            <input type="checkbox" value="vacation" checked> Congés
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" value="sick" checked> Maladie
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" value="personal" checked> Personnel
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" value="holiday" checked> Jours fériés
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" value="meeting" checked> Réunions
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" value="evaluation" checked> Évaluations
                        </label>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="calendar-grid">
            <div class="weekdays">
                <span>Dim</span>
                <span>Lun</span>
                <span>Mar</span>
                <span>Mer</span>
                <span>Jeu</span>
                <span>Ven</span>
                <span>Sam</span>
            </div>
            <div class="days" id="daysGrid"></div>
        </div>

        <div class="legend">
            <div class="legend-item">
                <div class="legend-color" style="background: #3b82f6;"></div>
                <span>Congés</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #ef4444;"></div>
                <span>Arrêt maladie</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #8b5cf6;"></div>
                <span>Personnel</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #10b981;"></div>
                <span>Jours fériés</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #f59e0b;"></div>
                <span>Réunions</span>
            </div>
            <div class="legend-item">
                <div class="legend-color" style="background: #ec4899;"></div>
                <span>Évaluations</span>
            </div>
        </div>
    </div>
  </div>


    <script>
        class Calendar {
            constructor() {
                this.currentDate = new Date();
                this.currentView = 'month'; // 'month', 'week', 'day'
                this.activeFilters = new Set(['vacation', 'sick', 'personal', 'holiday', 'meeting', 'evaluation']);
                this.events = [
                    { date: '2025-01-15', type: 'vacation', title: 'Congés annuels' },
                    { date: '2025-01-10', type: 'sick', title: 'Arrêt maladie' },
                    { date: '2025-01-01', type: 'holiday', title: 'Jour de l\'an' },
                    { date: '2025-01-20', type: 'personal', title: 'RDV personnel' },
                    { date: '2025-01-05', type: 'meeting', title: 'Réunion d\'équipe' },
                    { date: '2025-01-12', type: 'evaluation', title: 'Évaluation annuelle' },
                    { date: '2025-01-25', type: 'vacation', title: 'Congés' },
                    { date: '2025-01-25', type: 'meeting', title: 'Point projet' },
                    { date: '2025-01-15', type: 'meeting', title: 'Réunion client' }
                ];

                this.initializeCalendar();
                this.setupEventListeners();
            }

            initializeCalendar() {
                this.renderCalendar();
            }

            setupEventListeners() {
                document.getElementById('prevMonth').addEventListener('click', () => {
                    this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                    this.renderCalendar();
                });

                document.getElementById('nextMonth').addEventListener('click', () => {
                    this.currentDate.setMonth(this.currentDate.getMonth() + 1);
                    this.renderCalendar();
                });
                // Gestion du bouton de filtres
                const filterButton = document.getElementById('filterButton');
                const filtersPanel = document.getElementById('filtersPanel');
                
                filterButton.addEventListener('click', () => {
                    filtersPanel.style.display = filtersPanel.style.display === 'none' ? 'block' : 'none';
                });

                // Fermer le panel si on clique en dehors
                document.addEventListener('click', (e) => {
                    if (!filterButton.contains(e.target) && !filtersPanel.contains(e.target)) {
                        filtersPanel.style.display = 'none';
                    }
                });

                // Gestion des checkboxes de filtres
                const filterCheckboxes = document.querySelectorAll('.filter-option input');
                filterCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', () => {
                        if (checkbox.checked) {
                            this.activeFilters.add(checkbox.value);
                        } else {
                            this.activeFilters.delete(checkbox.value);
                        }
                        this.renderCalendar();
                    });
                });

                // Gestion des boutons de vue
                const viewButtons = document.querySelectorAll('.btn-group .btn');
                viewButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        viewButtons.forEach(btn => btn.classList.remove('active'));
                        button.classList.add('active');
                        this.currentView = button.textContent.toLowerCase();
                        this.renderCalendar();
                    });
                });

                      // Gestion des boutons de vue
              document.querySelectorAll('.btn-group .btn').forEach(button => {
                  button.addEventListener('click', (e) => {
                      document.querySelectorAll('.btn-group .btn').forEach(btn => {
                          btn.classList.remove('active');
                      });
                      e.target.classList.add('active');
                      this.currentView = e.target.textContent.toLowerCase();
                      this.renderCalendar();
                  });
              });

              // Bouton Aujourd'hui
              document.querySelector('.btn:nth-child(2)').addEventListener('click', () => {
                  this.currentDate = new Date();
                  this.renderCalendar();
              });
            }

            renderCalendar() {
        const container = document.querySelector('.calendar-grid');
        container.innerHTML = '';

        switch(this.currentView) {
            case 'mois':
                container.appendChild(this.renderMonth());
                break;
            case 'semaine':
                container.appendChild(this.renderWeek());
                break;
            case 'jour':
                container.appendChild(this.renderDay());
                break;
        }
    }

    renderWeek() {
        const weekContainer = document.createElement('div');
        weekContainer.className = 'calendar-week';

        const weekStart = new Date(this.currentDate);
        weekStart.setDate(weekStart.getDate() - weekStart.getDay());

        for(let i = 0; i < 7; i++) {
            const currentDate = new Date(weekStart);
            currentDate.setDate(weekStart.getDate() + i);

            const dayElement = document.createElement('div');
            dayElement.className = 'week-day';

            const header = document.createElement('div');
            header.className = 'week-day-header';
            header.textContent = currentDate.toLocaleDateString('fr-FR', {
                weekday: 'short',
                day: 'numeric'
            });

            const content = document.createElement('div');
            content.className = 'week-day-content';

            const events = this.getEventsForDate(currentDate);
            events.forEach(event => {
                const eventElement = document.createElement('div');
                eventElement.className = `event ${event.type}`;
                eventElement.textContent = event.title;
                content.appendChild(eventElement);
            });

            dayElement.appendChild(header);
            dayElement.appendChild(content);
            weekContainer.appendChild(dayElement);
        }

        return weekContainer;
    }

    renderDay() {
        const dayContainer = document.createElement('div');
        dayContainer.className = 'calendar-day';

        const header = document.createElement('div');
        header.className = 'day-header';
        header.textContent = this.currentDate.toLocaleDateString('fr-FR', {
            weekday: 'long',
            day: 'numeric',
            month: 'long'
        });

        const content = document.createElement('div');
        content.className = 'day-content';

        for(let hour = 0; hour < 24; hour++) {
            const hourSlot = document.createElement('div');
            hourSlot.className = 'hour-slot';

            const hourLabel = document.createElement('div');
            hourLabel.className = 'hour-label';
            hourLabel.textContent = `${hour}:00`;

            const hourEvents = document.createElement('div');
            hourEvents.className = 'hour-events';

            const events = this.getEventsForDate(this.currentDate);
            events.forEach(event => {
                const eventElement = document.createElement('div');
                eventElement.className = `event ${event.type}`;
                eventElement.textContent = event.title;
                hourEvents.appendChild(eventElement);
            });

            hourSlot.appendChild(hourLabel);
            hourSlot.appendChild(hourEvents);
            content.appendChild(hourSlot);
        }

        dayContainer.appendChild(header);
        dayContainer.appendChild(content);

        return dayContainer;
    }

            getMonthData() {
                const year = this.currentDate.getFullYear();
                const month = this.currentDate.getMonth();
                
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                
                const firstDayIndex = firstDay.getDay();
                const lastDayIndex = lastDay.getDay();
                
                const prevLastDay = new Date(year, month, 0);
                
                const days = [];
                
                // Previous month's days
                for (let x = firstDayIndex; x > 0; x--) {
                    days.push({
                        date: new Date(year, month - 1, prevLastDay.getDate() - x + 1),
                        isOtherMonth: true
                    });
                }
                
                // Current month's days
                for (let i = 1; i <= lastDay.getDate(); i++) {
                    days.push({
                        date: new Date(year, month, i),
                        isOtherMonth: false
                    });
                }
                
                // Next month's days
                for (let j = 1; j <= 6 - lastDayIndex; j++) {
                    days.push({
                        date: new Date(year, month + 1, j),
                        isOtherMonth: true
                    });
                }
                
                return days;
            }

            getEventsForDate(date) {
                const dateString = date.toISOString().split('T')[0];
                return this.events.filter(event => 
                    event.date === dateString && 
                    this.activeFilters.has(event.type)
                );
            }

            renderCalendar() {
                const daysGrid = document.getElementById('daysGrid');
                const monthTitle = document.querySelector('.calendar-title');
                
                // Update month title
                monthTitle.textContent = this.currentDate.toLocaleDateString('fr-FR', {
                    month: 'long',
                    year: 'numeric'
                });
                
                // Clear previous days
                daysGrid.innerHTML = '';
                
                // Get all days to display
                const days = this.getMonthData();
                
                // Render each day
                days.forEach(({ date, isOtherMonth }) => {
                    const dayElement = document.createElement('div');
                    dayElement.className = `day${isOtherMonth ? ' other-month' : ''}`;
                    
                    // Check if it's today
                    const today = new Date();
                    if (date.toDateString() === today.toDateString()) {
                        dayElement.classList.add('today');
                    }
                    
                    // Add day indicator
                    const indicator = document.createElement('div');
                    indicator.className = 'day-indicator';
                    dayElement.appendChild(indicator);
                    
                    // Add day number
                    const dayNumber = document.createElement('div');
                    dayNumber.className = 'day-number';
                    dayNumber.textContent = date.getDate();
                    dayElement.appendChild(dayNumber);
                    
                    // Add events
                    const events = this.getEventsForDate(date);
                    if (events.length > 0) {
                        // Add event type classes to day
                        events.forEach(event => {
                            dayElement.classList.add(`has-${event.type}`);
                        });

                        const eventsContainer = document.createElement('div');
                        eventsContainer.className = 'events';
                        
                        events.slice(0, 2).forEach(event => {
                            const eventElement = document.createElement('div');
                            eventElement.className = `event ${event.type}`;
                            eventElement.textContent = event.title;
                            eventsContainer.appendChild(eventElement);
                        });
                        
                        if (events.length > 2) {
                            const eventCount = document.createElement('div');
                            eventCount.className = 'event-count';
                            eventCount.textContent = `+${events.length - 2}`;
                            dayElement.appendChild(eventCount);
                        }
                        
                        dayElement.appendChild(eventsContainer);
                    }
                    
                    daysGrid.appendChild(dayElement);
                });
            }
        }

        // Initialize calendar when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new Calendar();
        });
    </script>
</body>
</html>