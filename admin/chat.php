<?php
session_start();
error_reporting(E_ALL); // Temporairement activé pour déboguer
include('includes/dbconnection.php');

if (strlen($_SESSION['vpmsaid']) == 0) {
    header('location:logout.php');
    exit(); // Ajout d'un exit après la redirection
} else {
    // Fonction pour formater les montants
    function formatMoney($amount) {
        return number_format($amount, 2, '.', ' ');
    }

    // Date d'aujourd'hui
    $today = date('Y-m-d');
    
    // Statistiques générales
    $totalVehicles = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM vehicule"))['total'];
    
    // Correction de la requête pour les véhicules présents
    $vehiclesIn = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM vehicule WHERE Status = ' '"))['total'];
    $vehiclesOut = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM vehicule WHERE Status = 'Out'"))['total'];
    
    // Revenus
    $totalRevenueQuery = mysqli_query($con, "SELECT SUM(frais) as total FROM vehicule WHERE Status = 'Out'");
    $totalRevenue = mysqli_fetch_assoc($totalRevenueQuery)['total'];
    $totalRevenue = $totalRevenue ? $totalRevenue : 0;
    
    // Revenus aujourd'hui
    $todayRevenueQuery = mysqli_query($con, "SELECT SUM(frais) as total FROM vehicule WHERE Status = 'Out' AND DATE(temps_sortie) = '$today'");
    $todayRevenue = mysqli_fetch_assoc($todayRevenueQuery)['total'];
    $todayRevenue = $todayRevenue ? $todayRevenue : 0;
    
    // Revenus des 7 derniers jours
    $revenueQuery = mysqli_query($con, "SELECT DATE(temps_sortie) as date, SUM(frais) as total 
                                    FROM vehicule 
                                    WHERE Status = 'out' AND temps_sortie >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
                                    GROUP BY DATE(temps_sortie) 
                                    ORDER BY date ASC");
    
    $dailyRevenue = [];
    $dailyLabels = [];
    
    if ($revenueQuery) {
        while ($row = mysqli_fetch_assoc($revenueQuery)) {
            $dailyLabels[] = date('d/m', strtotime($row['date']));
            $dailyRevenue[] = $row['total'];
        }
    }
    
    // Si moins de 7 jours de données, compléter avec des zéros
    if (count($dailyLabels) < 7) {
        $missingDays = 7 - count($dailyLabels);
        for ($i = 0; $i < $missingDays; $i++) {
            array_unshift($dailyLabels, date('d/m', strtotime("-" . ($missingDays - $i) . " days")));
            array_unshift($dailyRevenue, "0");
        }
    }
    
    // Statistiques par catégorie de véhicule
    $categoryQuery = mysqli_query($con, "SELECT Categorie_vehicule, COUNT(*) as count 
                                     FROM vehicule 
                                     GROUP BY Categorie_vehicule 
                                     ORDER BY count DESC");
    
    $categories = [];
    $categoryCounts = [];
    
    if ($categoryQuery) {
        while ($row = mysqli_fetch_assoc($categoryQuery)) {
            $categories[] = $row['Categorie_vehicule'] ? $row['Categorie_vehicule'] : 'Non défini';
            $categoryCounts[] = $row['count'];
        }
    }
    
    // S'assurer qu'il y a au moins une catégorie
    if (empty($categories)) {
        $categories = ['Aucune donnée'];
        $categoryCounts = [0];
    }
    
    // Statistiques des heures d'affluence
    $hourlyQuery = mysqli_query($con, "SELECT HOUR(temps_entree) as hour, COUNT(*) as count 
                                    FROM vehicule 
                                    GROUP BY HOUR(temps_entree) 
                                    ORDER BY hour ASC");
    
    $hours = [];
    $hourlyCounts = [];
    
    if ($hourlyQuery) {
        while ($row = mysqli_fetch_assoc($hourlyQuery)) {
            $hours[] = $row['hour'] . 'h';
            $hourlyCounts[] = $row['count'];
        }
    }
    
    // S'assurer qu'il y a au moins une heure
    if (empty($hours)) {
        $hours = ['0h', '6h', '12h', '18h'];
        $hourlyCounts = [0, 0, 0, 0];
    }
    
    // Dernières entrées/sorties
    $recentVehicles = mysqli_query($con, "SELECT * FROM vehicule ORDER BY ID DESC LIMIT 10");
    
    // Vérifier s'il y a des erreurs dans les requêtes
    if (!$recentVehicles) {
        echo "<div style='color:red'>Erreur dans la dernière requête: " . mysqli_error($con) . "</div>";
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - GreenPark</title>
    <!-- Correction: Mise à jour vers Font Awesome 6 avec la bonne URL -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Correction: Chart.js mise à jour avec integrity et crossorigin -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js" integrity="sha384-HxN+Ck47eBnPAQwrYTKWkGEkgQQQPZRG2tTQZYfxKvZ9ieZKtYKrR0/VPDUrUXw" crossorigin="anonymous"></script>

    <link rel="apple-touch-icon" href="assets/images/logo.png">
    <link rel="shortcut icon" href="assets/images/logo.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <link href="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/jqvmap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/weathericons@2.1.0/css/weather-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --secondary: #1e40af;
            --background: #f8fafc;
            --surface: #ffffff;
            --text: #334155;
            --text-light: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --info: #3b82f6;
            --border: #e2e8f0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background);
            color: var(--text);
            line-height: 1.6;
            padding: 20px;
        }
        
        .dashboard {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: var(--primary-dark);
            font-weight: 600;
        }
        
        .header .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .header .logo i {
            font-size: 28px;
            color: var(--primary);
        }
        
        .header .date {
            color: var(--text-light);
            font-size: 14px;
            text-align: right;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: var(--surface);
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .stat-icon i {
            font-size: 24px;
            color: white;
        }
        
        .icon-vehicles {
            background-color: var(--primary);
        }
        
        .icon-in {
            background-color: var(--info);
        }
        
        .icon-out {
            background-color: var(--secondary);
        }
        
        .icon-revenue {
            background-color: var(--success);
        }
        
        .stat-info {
            flex-grow: 1;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 600;
            line-height: 1.2;
        }
        
        .stat-label {
            color: var(--text-light);
            font-size: 14px;
        }
        
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        @media (max-width: 1100px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .chart-card {
            background-color: var(--surface);
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .chart-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .chart-title i {
            color: var(--primary);
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        .recent-activity {
            background-color: var(--surface);
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .activity-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .activity-title i {
            color: var(--primary);
        }
        
        .activity-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .activity-table th {
            text-align: left;
            padding: 12px 15px;
            background-color: #f1f5f9;
            color: var(--text-light);
            font-weight: 500;
            font-size: 14px;
            border-bottom: 2px solid var(--border);
        }
        
        .activity-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
        }
        
        .activity-table tr:hover {
            background-color: #f8fafc;
        }
        
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-align: center;
        }
        
        .status-in {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-out {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            color: var(--text-light);
            font-size: 14px;
        }
        
        .eco-badge {
            display: inline-flex;
            align-items: center;
            background-color: #d1fae5;
            color: #065f46;
            padding: 5px 10px;
            border-radius: 30px;
            font-size: 12px;
            margin-top: 10px;
        }
        
        .eco-badge i {
            margin-right: 5px;
        }
        
        @media print {
            .dashboard {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

   <?php include_once('includes/sidebar.php');?>

   <?php include_once('includes/header.php');?>
   
    <div class="dashboard">
        <div class="header">
            <div class="logo">
                <i class="fas fa-leaf"></i>
                <h1>GreenPark Dashboard</h1>
            </div>
            <div class="date">
                <p><?php echo date('l, d F Y'); ?></p>
                <p><?php echo date('H:i'); ?></p>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon icon-vehicles">
                    <i class="fas fa-car"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $totalVehicles; ?></div>
                    <div class="stat-label">Total des véhicules</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon icon-in">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $vehiclesIn; ?></div>
                    <div class="stat-label">Véhicules présents</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon icon-out">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $vehiclesOut; ?></div>
                    <div class="stat-label">Véhicules sortis</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon icon-revenue">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo formatMoney($todayRevenue); ?> Fbu</div>
                    <div class="stat-label">Revenus aujourd'hui</div>
                </div>
            </div>
        </div>
        
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <h2 class="chart-title"><i class="fas fa-chart-line"></i> Revenus des 7 derniers jours</h2>
                </div>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            
            <div class="chart-card">
                <div class="chart-header">
                    <h2 class="chart-title"><i class="fas fa-car-side"></i> Catégories de véhicules</h2>
                </div>
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
            
            <div class="chart-card">
                <div class="chart-header">
                    <h2 class="chart-title"><i class="fas fa-clock"></i> Heures d'affluence</h2>
                </div>
                <div class="chart-container">
                    <canvas id="hourlyChart"></canvas>
                </div>
            </div>
            
            <div class="chart-card">
                <div class="chart-header">
                    <h2 class="chart-title"><i class="fas fa-chart-pie"></i> Statut des véhicules</h2>
                </div>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="recent-activity">
            <div class="activity-header">
                <h2 class="activity-title"><i class="fas fa-history"></i> Activité récente</h2>
            </div>
            <div style="overflow-x: auto;">
                <table class="activity-table">
                    <thead>
                        <tr>
                            <th>N° Parking</th>
                            <th>Immatriculation</th>
                            <th>Propriétaire</th>
                            <th>Entrée</th>
                            <th>Sortie</th>
                            <th>Frais</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recentVehicles && mysqli_num_rows($recentVehicles) > 0): ?>
                            <?php while ($vehicle = mysqli_fetch_assoc($recentVehicles)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($vehicle['Numero_parking']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['plaque']); ?></td>
                                <td><?php echo htmlspecialchars($vehicle['Nom_proprietaire']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($vehicle['temps_entree'])); ?></td>
                                <td><?php echo $vehicle['temps_sortie'] ? date('d/m/Y H:i', strtotime($vehicle['temps_sortie'])) : '-'; ?></td>
                                <td><?php echo $vehicle['frais'] ? formatMoney($vehicle['frais']) . ' Fbu' : '-'; ?></td>
                                <td>
                                    <?php if ($vehicle['Status'] == 'Out'): ?>
                                        <span class="status status-Out">Sorti</span>
                                    <?php else: ?>
                                        <span class="status status-in">Présent</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center;">Aucune activité récente</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="footer">
            <p>© <?php echo date('Y'); ?> GreenPark - Tableau de bord de gestion</p>
            <div class="eco-badge">
                <i class="fas fa-leaf"></i> Alimenté par énergie écologique
            </div>
        </div>
    </div>

    <?php include_once('includes/footer.php');?>

    <!-- Scripts corrigés avec intégrité et crossorigin -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js" integrity="sha384-UG8ao2jwOWB7/oDdObZc6ItJmwUkR/PfMyt9Qs5AwX7PsnYn1CRKCTWyncPTWvaS" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js" integrity="sha384-HxN+Ck47eBnPAQwrYTKWkGEkgQQQPZRG2tTQZYfxKvZ9ieZKtYKrR0/VPDUrUXw" crossorigin="anonymous"></script>

    <!-- Scripts du thème -->
    <script src="assets/js/main.js"></script>

    <script>
        // Configuration des graphiques - mise à jour pour Chart.js v4
        const chartDefaults = {
            font: {
                family: "'Poppins', 'sans-serif'"
            },
            color: '#64748b'
        };
        
        // Graphique des revenus
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($dailyLabels); ?>,
                datasets: [{
                    label: 'Revenus ($)',
                    data: <?php echo json_encode($dailyRevenue); ?>,
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderColor: '#10b981',
                    borderWidth: 2,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return value + ' $';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Graphique des catégories
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($categories); ?>,
                datasets: [{
                    label: 'Nombre de véhicules',
                    data: <?php echo json_encode($categoryCounts); ?>,
                    backgroundColor: [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6',
                        '#ec4899'
                    ],
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Graphique horaire
        const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
        new Chart(hourlyCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($hours); ?>,
                datasets: [{
                    label: 'Entrées par heure',
                    data: <?php echo json_encode($hourlyCounts); ?>,
                    backgroundColor: 'rgba(79, 70, 229, 0.2)',
                    borderColor: '#4f46e5',
                    borderWidth: 2,
                    tension: 0.4,
                    pointBackgroundColor: '#4f46e5',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Graphique de statut
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Présent', 'Sorti'],
                datasets: [{
                    data: [<?php echo $vehiclesIn; ?>, <?php echo $vehiclesOut; ?>],
                    backgroundColor: [
                        '#3b82f6',
                        '#10b981'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '70%'
            }
        });
    </script>
</body>
</html>
<?php
}
?>