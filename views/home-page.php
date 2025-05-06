
<head>
  <meta charset="UTF-8">
  <title>Dashboard TimeOff</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
 <!-- Bootstrap (optionnel si tu veux styliser autour) -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- FullCalendar CSS -->
<!-- <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css" rel="stylesheet" /> -->

<!-- Moment.js pour gestion des dates -->
<!-- <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script> -->

<!-- FullCalendar JS -->
<!-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.js"></script> -->

  

  <style>
    html *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
      background-color: #f8f9fa;
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


      <!-- Main content -->
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
          <!-- Calendar -->
          <div class="col-md-8">
            <h5>May 2025</h5>
            
            <?php
                use Matteomcr\GestionCongeEmploye\Models\Employe;
                echo Employe::current()->getRole()->NomRole;
            ?>
            <div id="kt_docs_fullcalendar_basic"></div>


          </div>

          <!-- Recent Requests -->
          <div class="col-md-4">
            <h5>Recent Leave Requests</h5>
            <ul class="list-group">
              <li class="list-group-item">
                <strong>Personal Leave</strong><br>
                Mar 10, 2025 – Mar 10, 2025<br>
                <span class="event-badge badge-rejected">Rejected</span>
                <small class="d-block text-muted">High workload during this period</small>
              </li>
              <li class="list-group-item">
                <strong>Personal Leave</strong><br>
                Feb 1, 2025 – Feb 5, 2025<br>
                <span class="event-badge badge-pending">Pending</span>
                <small class="d-block text-muted">Family event</small>
              </li>
              <li class="list-group-item">
                <strong>Vacation Leave</strong><br>
                Jan 15, 2025 – Jan 20, 2025<br>
                <span class="event-badge badge-approved">Approved</span>
                <small class="d-block text-muted">Approved as requested</small>
              </li>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </div>
  <!-- <script src="/js/FullCalendar.js"></script> -->
</body>
