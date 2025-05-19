<?php use Matteomcr\GestionCongeEmploye\Models\Employe; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Profil Utilisateur</title>
    <style>
        html * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background-color: #f3f4f6;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .header-banner {
            height: 200px;
            background: linear-gradient(to right, #3b82f6, #2563eb);
            border-radius: 12px 12px 0 0;
        }

        .profile-content {
            position: relative;
            margin-top: -80px;
            padding: 0 20px;
        }

        .profile-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            display: flex;
            gap: 24px;
            align-items: flex-start;
        }

        .profile-image-container {
            position: relative;
        }

        .profile-image {
            width: 128px;
            height: 128px;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }

        .status-indicator {
            position: absolute;
            bottom: 8px;
            right: 8px;
            width: 16px;
            height: 16px;
            background: #34d399;
            border: 2px solid white;
            border-radius: 50%;
        }

        .profile-info {
            flex-grow: 1;
        }

        .profile-name {
            font-size: 32px;
            font-weight: bold;
            color: #1f2937;
        }

        .profile-role {
            color: #6b7280;
            font-size: 18px;
            margin-top: 4px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-top: 24px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .info-icon {
            padding: 8px;
            border-radius: 8px;
            background: #eff6ff;
        }

        .info-icon.blue { background: #eff6ff; color: #3b82f6; }
        .info-icon.purple { background: #f5f3ff; color: #8b5cf6; }
        .info-icon.green { background: #ecfdf5; color: #10b981; }
        .info-icon.orange { background: #fff7ed; color: #f97316; }

        .info-text {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 14px;
            color: #6b7280;
        }

        .info-value {
            color: #374151;
        }

        .about-section {
            margin-top: 32px;
            padding-top: 32px;
            border-top: 1px solid #e5e7eb;
        }

        .about-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .about-text {
            color: #4b5563;
            line-height: 1.5;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-top: 32px;
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stats-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .leave-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .leave-item {
            padding: 16px;
            border-radius: 8px;
        }

        .leave-item.blue { background: #eff6ff; }
        .leave-item.green { background: #ecfdf5; }
        .leave-item.purple { background: #f5f3ff; }
        .leave-item.orange { background: #fff7ed; }

        .leave-label {
            font-size: 14px;
            color: #6b7280;
        }

        .leave-value {
            font-size: 24px;
            font-weight: bold;
            margin-top: 4px;
        }

        .leave-value.blue { color: #3b82f6; }
        .leave-value.green { color: #10b981; }
        .leave-value.purple { color: #8b5cf6; }
        .leave-value.orange { color: #f97316; }

        .progress-container {
            margin-top: 16px;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .progress-bar {
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 4px;
        }

        .progress-fill.blue { background: #3b82f6; }
        .progress-fill.green { background: #10b981; }
        .progress-fill.purple { background: #8b5cf6; }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-banner"></div>
        <div class="profile-content">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-image-container">
                        <img src="https://static.vecteezy.com/system/resources/thumbnails/030/504/836/small_2x/avatar-account-flat-isolated-on-transparent-background-for-graphic-and-web-design-default-social-media-profile-photo-symbol-profile-and-people-silhouette-user-icon-vector.jpg" alt="Photo de profil" class="profile-image">
                        <span class="status-indicator"></span>
                    </div>
                    
                    <div class="profile-info">
                        <h2 class="profile-name"> 
                          <?php
                            if(Employe::current())
                              echo Employe::current()->Prenom . " " . Employe::current()->Nom ; 
                          ?>
                        </h2>
                        <p class="profile-role">
                        <?php
                            if(Employe::current())
                              echo Employe::current()->getRole()->NomRole; 
                          ?>
                        </p>
                        
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-icon blue">📧</div>
                                <div class="info-text">
                                    <span class="info-label">Email</span>
                                    <span class="info-value">
                                    <?php
                                      if(Employe::current())
                                        echo Employe::current()->Email; 
                                    ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-icon purple">💼</div>
                                <div class="info-text">
                                    <span class="info-label">Département</span>
                                    <span class="info-value">
                                    <?php
                                      if(Employe::current())
                                        echo Employe::current()->getDepartement()->NomDepartement; 
                                    ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-icon green">👥</div>
                                <div class="info-text">
                                    <span class="info-label">Rôle</span>
                                    <span class="info-value">
                                    <?php
                                      if(Employe::current())
                                        echo Employe::current()->getRole()->NomRole; 
                                    ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-icon orange">📖</div>
                                <div class="info-text">
                                    <span class="info-label">Statut</span>
                                    <span class="info-value">
                                    <?php
                                      if(Employe::current())
                                        echo Employe::current()->Statut; 
                                    ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="about-section">
                    <h3 class="about-title">À propos</h3>
                    <p class="about-text">
                        Membre dévoué de l'équipe travaillant en collaboration avec ses collègues pour atteindre les objectifs du projet et de l'entreprise.
                    </p>
                </div>
                <?php if (Employe::current() && Employe::current()->getRole()->NomRole == "Employe"): ?>
                
                <div class="stats-grid">
                    <div class="stats-card">
                        <h4 class="stats-title">Solde de congés</h4>
                        <div class="leave-grid">
                            <div class="leave-item blue">
                                <span class="leave-label">Congés vacances</span>
                                <p class="leave-value blue">
                                <?php
                                    $resultat = Employe::current()->SoldeConge;
                                    echo $resultat . " jours";    
                                ?>
                                </p>
                            </div>
                            <div class="leave-item green">
                                <span class="leave-label">Congé heures supp.</span>
                                <p class="leave-value green">
                                <?php
                                    $resultat = floor(Employe::current()->SoldeCongeHeureSupp);
                                    echo $resultat . " jours";    
                                ?>
                                </p>
                            </div>
                            <div class="leave-item purple">
                                <span class="leave-label">Heure supp. (argent)</span>
                                <p class="leave-value purple">
                                <?php
                                    $resultat = Employe::current()->getOvertimeConvertedToPayment();
                                    $heures = isset($resultat['heures']) ? $resultat['heures'] : 0;
                                    echo $heures . " heures";     
                                ?>
                                </p>
                            </div>
                            <div class="leave-item orange">
                                <span class="leave-label">Heures supp.</span>
                                <p class="leave-value orange">
                                <?php
                                    $resultat = Employe::current()->getTotalOvertime();
                                    $heures = isset($resultat['heures']) ? $resultat['heures'] : 0;
                                    echo $heures . " heures";    
                                ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stats-card">
                        <h4 class="stats-title">Utilisation cette année</h4>
                        <div class="progress-container">
                            <div class="progress-header">
                                <span>Congés vacances utilisés</span>
                                <span>
                                <?php
                                    $soldeConge = Employe::current()->SoldeConge;
                                    $total = 40;
                                    $percentage = ($total > 0) ? ($soldeConge / $total) * 100 : 0;
                                    echo $soldeConge . "/" . $total;    
                                ?>
                                </span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill blue" style="width: <?= $percentage ?>%"></div>
                            </div>
                        </div>
                        
                      
                        <div class="progress-container">
                            <div class="progress-header">
                                <span>Heures supplémentaire(s) convertit en congé</span>
                                <span>
                                <?php
                                    $totalOvertime = Employe::current()->getTotalOvertime();
                                    $convertedLeave = Employe::current()->getOvertimeConvertedToLeave();
                                
                                    $totalHours = isset($totalOvertime['heures']) ? $totalOvertime['heures'] : 0;
                                    $convertToLeave = isset($convertedLeave['heures']) ? $convertedLeave['heures'] : 0;
                                
                                    $percentages = ($totalHours > 0) ? ($convertToLeave / $totalHours) * 100 : 0;
                                
                                    echo $convertToLeave . "/" . $totalHours; 
                                ?>
                                </span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill purple" style="width: <?= $percentages ?>%"></div>
                            </div>
                        </div>

                        <div class="progress-container">
                            <div class="progress-header">
                                <span>Heures supplémentaire(s) convertit en argent</span>
                                <span>
                                <?php
                                      $converted = Employe::current()->getOvertimeConvertedToPayment();
                                      $convertToPay = isset($converted['heures']) ? $converted['heures'] : 0;
                                      $totalHours = isset($totalHours) ? $totalHours : 0;
                                  
                                      $percentages = ($totalHours > 0) ? ($convertToPay / $totalHours) * 100 : 0;
                                      echo $convertToPay . "/" . $totalHours; 
                                ?>
                                </span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill green" style="width: <?= $percentages ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
          <?php endif; ?>

                <form action="/logout" method="get">
              <button type="submit" class="btn btn-outline-danger mt-4">Se déconnecter</button>
              </form>
            </div>
        </div>
    </div>
</body>
</html>