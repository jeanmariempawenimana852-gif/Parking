<?php


session_start();
error_reporting(E_ALL); // Temporairement activé pour déboguer
include('includes/dbconnection.php');

if (strlen($_SESSION['vpmsaid']) == 0) {
    header('location:logout.php');
} else {
    // Fonction pour formater les montants
    function formatMoney($amount) {
        return number_format($amount, 2, '.', ' ');
    }

    // Date d'aujourd'hui
    $today = date('Y-m-d');
    
    // Statistiques générales
    $totalVehicles = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM vehicule"))['total'];
    
    // Correction de la requête pour les véhicules présents (erreur de guillemet non fermé)
    $vehiclesIn = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM vehicule WHERE Status = 'in'"))['total'];
    $vehiclesOut = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM vehicule WHERE Status = 'out'"))['total'];
    
    // Revenus
    $totalRevenueQuery = mysqli_query($con, "SELECT SUM(frais) as total FROM vehicule WHERE Status = 'out'");
    $totalRevenue = mysqli_fetch_assoc($totalRevenueQuery)['total'];
    $totalRevenue = $totalRevenue ? $totalRevenue : 0;
    
    // Revenus aujourd'hui
    $todayRevenueQuery = mysqli_query($con, "SELECT SUM(frais) as total FROM vehicule WHERE Status = 'out' AND DATE(temps_sortie) = '$today'");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="shortcut icon" href="https://i.imgur.com/QRAUqs9.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->
    <link href="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/jqvmap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/weathericons@2.1.0/css/weather-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    

       
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
            padding: 15px;
        }
        
        .dashboard {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .header h1 {
            color: var(--primary-dark);
            font-weight: 600;
            font-size: clamp(1.5rem, 5vw, 2rem);
        }
        
        .header .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .header .logo i {
            font-size: clamp(20px, 5vw, 28px);
            color: var(--primary);
        }
        
        .header .date {
            color: var(--text-light);
            font-size: 14px;
            text-align: right;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: var(--surface);
            border-radius: 14px;
            padding: 15px;
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
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .stat-icon i {
            font-size: 20px;
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
            font-size: clamp(18px, 4vw, 24px);
            font-weight: 600;
            line-height: 1.2;
        }
        
        .stat-label {
            color: var(--text-light);
            font-size: 13px;
        }
        
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        /* Ajustement pour les écrans très petits */
        @media (max-width: 360px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .chart-card {
            background-color: var(--surface);
            border-radius: 14px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        
        .chart-title {
            font-size: clamp(16px, 3vw, 18px);
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
            height: 250px;
            width: 100%;
        }
        
        /* Ajuster la hauteur du graphique sur les petits écrans */
        @media (max-width: 480px) {
            .chart-container {
                height: 200px;
            }
        }
        
        .recent-activity {
            background-color: var(--surface);
            border-radius: 14px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        
        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .activity-title {
            font-size: clamp(16px, 3vw, 18px);
            font-weight: 600;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .activity-title i {
            color: var(--primary);
        }
        
        .activity-table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 10px;
        }
        
        .activity-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px; /* Pour forcer le défilement horizontal sur petits écrans */
        }
        
        .activity-table th {
            text-align: left;
            padding: 10px 12px;
            background-color: #f1f5f9;
            color: var(--text-light);
            font-weight: 500;
            font-size: 13px;
            border-bottom: 2px solid var(--border);
            white-space: nowrap;
        }
        
        .activity-table td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
            white-space: nowrap;
        }
        
        .activity-table tr:hover {
            background-color: #f8fafc;
        }
        
        .status {
            display: inline-block;
            padding: 4px 8px;
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
            margin-top: 30px;
            text-align: center;
            color: var(--text-light);
            font-size: 13px;
        }
        
        .eco-badge {
            display: inline-flex;
            align-items: center;
            background-color: #d1fae5;
            color: #065f46;
            padding: 5px 10px;
            border-radius: 30px;
            font-size: 11px;
            margin-top: 8px;
        }
        
        .eco-badge i {
            margin-right: 5px;
        }
        
        /* Instructions pour le scroll horizontal */
        .swipe-instruction {
            display: none;
            font-size: 12px;
            text-align: center;
            color: var(--text-light);
            margin-bottom: 10px;
        }
        
        /* Afficher les instructions que sur mobile */
        @media (max-width: 768px) {
            .swipe-instruction {
                display: block;
            }
            
            body {
                padding: 10px;
            }
            
            .header {
                margin-bottom: 20px;
            }
            
            /* Pour les très petits écrans */
            .header .date {
                width: 100%;
                text-align: left;
                margin-top: 5px;
            }
        }
        
        /* Optimisations pour impression */
        @media print {
            .dashboard {
                max-width: 100%;
            }
            
            .chart-container {
                height: 200px;
                page-break-inside: avoid;
            }
            
            .stat-card,
            .chart-card,
            .recent-activity {
                break-inside: avoid;
            }
            
            body {
                padding: 0;
                background-color: white;
            }
        }
    </style>
</head>
<body>
 <script>
        jQuery(document).ready(function($) {
            "use strict";

            // Pie chart flotPie1
            var piedata = [
                { label: "Desktop visits", data: [[1,32]], color: '#5c6bc0'},
                { label: "Tab visits", data: [[1,33]], color: '#ef5350'},
                { label: "Mobile visits", data: [[1,35]], color: '#66bb6a'}
            ];

            $.plot('#flotPie1', piedata, {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        innerRadius: 0.65,
                        label: {
                            show: true,
                            radius: 2/3,
                            threshold: 1
                        },
                        stroke: {
                            width: 0
                        }
                    }
                },
                grid: {
                    hoverable: true,
                    clickable: true
                }
            });
            // Pie chart flotPie1  End
            // cellPaiChart
            var cellPaiChart = [
                { label: "Direct Sell", data: [[1,65]], color: '#5b83de'},
                { label: "Channel Sell", data: [[1,35]], color: '#00bfa5'}
            ];
            $.plot('#cellPaiChart', cellPaiChart, {
                series: {
                    pie: {
                        show: true,
                        stroke: {
                            width: 0
                        }
                    }
                },
                legend: {
                    show: false
                },grid: {
                    hoverable: true,
                    clickable: true
                }

            });
            // cellPaiChart End
            // Line Chart  #flotLine5
            var newCust = [[0, 3], [1, 5], [2,4], [3, 7], [4, 9], [5, 3], [6, 6], [7, 4], [8, 10]];

            var plot = $.plot($('#flotLine5'),[{
                data: newCust,
                label: 'New Data Flow',
                color: '#fff'
            }],
            {
                series: {
                    lines: {
                        show: true,
                        lineColor: '#fff',
                        lineWidth: 2
                    },
                    points: {
                        show: true,
                        fill: true,
                        fillColor: "#ffffff",
                        symbol: "circle",
                        radius: 3
                    },
                    shadowSize: 0
                },
                points: {
                    show: true,
                },
                legend: {
                    show: false
                },
                grid: {
                    show: false
                }
            });
            // Line Chart  #flotLine5 End
            // Traffic Chart using chartist
            if ($('#traffic-chart').length) {
                var chart = new Chartist.Line('#traffic-chart', {
                  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                  series: [
                  [0, 18000, 35000,  25000,  22000,  0],
                  [0, 33000, 15000,  20000,  15000,  300],
                  [0, 15000, 28000,  15000,  30000,  5000]
                  ]
              }, {
                  low: 0,
                  showArea: true,
                  showLine: false,
                  showPoint: false,
                  fullWidth: true,
                  axisX: {
                    showGrid: true
                }
            });

                chart.on('draw', function(data) {
                    if(data.type === 'line' || data.type === 'area') {
                        data.element.animate({
                            d: {
                                begin: 2000 * data.index,
                                dur: 2000,
                                from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
                                to: data.path.clone().stringify(),
                                easing: Chartist.Svg.Easing.easeOutQuint
                            }
                        });
                    }
                });
            }
            // Traffic Chart using chartist End
            //Traffic chart chart-js
            if ($('#TrafficChart').length) {
                var ctx = document.getElementById( "TrafficChart" );
                ctx.height = 150;
                var myChart = new Chart( ctx, {
                    type: 'line',
                    data: {
                        labels: [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul" ],
                        datasets: [
                        {
                            label: "Visit",
                            borderColor: "rgba(4, 73, 203,.09)",
                            borderWidth: "1",
                            backgroundColor: "rgba(4, 73, 203,.5)",
                            data: [ 0, 2900, 5000, 3300, 6000, 3250, 0 ]
                        },
                        {
                            label: "Bounce",
                            borderColor: "rgba(245, 23, 66, 0.9)",
                            borderWidth: "1",
                            backgroundColor: "rgba(245, 23, 66,.5)",
                            pointHighlightStroke: "rgba(245, 23, 66,.5)",
                            data: [ 0, 4200, 4500, 1600, 4200, 1500, 4000 ]
                        },
                        {
                            label: "Targeted",
                            borderColor: "rgba(40, 169, 46, 0.9)",
                            borderWidth: "1",
                            backgroundColor: "rgba(40, 169, 46, .5)",
                            pointHighlightStroke: "rgba(40, 169, 46,.5)",
                            data: [1000, 5200, 3600, 2600, 4200, 5300, 0 ]
                        }
                        ]
                    },
                    options: {
                        responsive: true,
                        tooltips: {
                            mode: 'index',
                            intersect: false
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        }

                    }
                } );
            }
            //Traffic chart chart-js  End
            // Bar Chart #flotBarChart
            $.plot("#flotBarChart", [{
                data: [[0, 18], [2, 8], [4, 5], [6, 13],[8,5], [10,7],[12,4], [14,6],[16,15], [18, 9],[20,17], [22,7],[24,4], [26,9],[28,11]],
                bars: {
                    show: true,
                    lineWidth: 0,
                    fillColor: '#ffffff8a'
                }
            }], {
                grid: {
                    show: false
                }
            });
            // Bar Chart #flotBarChart End
        });

        
            // translation js 

                        const translations = {
                fr: translationsFr,
                en: translationsEn
            };

            const languageSelector = document.getElementById('language');

            languageSelector.addEventListener('change', (event) => {
                const selectedLanguage = event.target.value;
                translatePage(selectedLanguage);
            });

            function translatePage(language) {
                const elementsToTranslate = document.querySelectorAll('[data-translate]');

                elementsToTranslate.forEach((element) => {
                    const key = element.getAttribute('data-translate');
                    if (translations[language][key]) {
                        element.textContent = translations[language][key];
                    }
                });
            }


            
        // Configuration des graphiques
        Chart.defaults.font.family = "'Poppins', 'sans-serif'";
        Chart.defaults.color = '#64748b';
        
        // Fonction pour rendre les graphiques responsives
        function createResponsiveCharts() {
            // Graphique des revenus
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
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
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
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
                                },
                                font: {
                                    size: window.innerWidth < 768 ? 10 : 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: window.innerWidth < 768 ? 10 : 12
                                }
                            }
                        }
                    }
                }
            });
            
            // Graphique des catégories
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            const categoryChart = new Chart(categoryCtx, {
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
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: window.innerWidth < 768 ? 10 : 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: window.innerWidth < 768 ? 10 : 12
                                },
                                callback: function(value, index) {
                                    const label = this.getLabelForValue(value);
                                    // Limiter la longueur des étiquettes sur petits écrans
                                    if (window.innerWidth < 768 && label.length > 8) {
                                        return label.substr(0, 7) + '...';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
            
            // Graphique horaire
            const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
            const hourlyChart = new Chart(hourlyCtx, {
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
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: window.innerWidth < 768 ? 10 : 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: window.innerWidth < 768 ? 10 : 12
                                }
                            }
                        }
                    }
                }
            });
            
            // Graphique de statut
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const statusChart = new Chart(statusCtx, {
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
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: window.innerWidth < 768 ? 11 : 12
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        }
        
        // Initialiser les graphiques
        document.addEventListener('DOMContentLoaded', function() {
            createResponsiveCharts();
            
            // Redimensionner les graphiques si la fenêtre est redimensionnée
            window.addEventListener('resize', function() {
                // Détruire et recréer les graphiques pour qu'ils s'adaptent
                Chart.helpers.each(Chart.instances, function(instance) {
                    instance.destroy();
                });
                createResponsiveCharts();
            });
        });
    
    </script>

    <div class="dashboard">

      <?php include_once('includes/sidebar.php');?>

        <?php include_once('includes/header.php');?>
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
                    <div class="stat-value"><?php echo formatMoney($todayRevenue); ?> $</div>
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
            <div class="swipe-instruction">
                <i class="fas fa-hand-point-right"></i> Faites défiler horizontalement pour voir toutes les données
            </div>
            <div class="activity-table-container">
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
                                <td><?php echo $vehicle['Numero_parking']; ?></td>
                                <td><?php echo $vehicle['plaque']; ?></td>
                                <td><?php echo $vehicle['Nom_proprietaire']; ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($vehicle['temps_entree'])); ?></td>
                                <td><?php echo $vehicle['temps_sortie'] ? date('d/m/Y H:i', strtotime($vehicle['temps_sortie'])) : '-'; ?></td>
                                <td><?php echo $vehicle['frais'] ? $vehicle['frais'] . ' $' : '-'; ?></td>
                                <td>
                                    <?php if ($vehicle['Status'] == 'out'): ?>
                                        <span class="status status-out">Sorti</span>
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
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="assets/js/main.js"></script>

    
    <script src="https://cdn.jsdelivr.net/npm/jquery.flot@0.8.3/jquery.flot.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flot-pie@1.0.0/src/jquery.flot.pie.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flot-spline@0.0.1/js/jquery.flot.spline.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/simpleweather@3.1.0/jquery.simpleWeather.min.js"></script>
    <script src="assets/js/init/weather-init.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.js"></script>
    <script src="assets/js/init/fullcalendar-init.js"></script>

    

    <script>
        // Configuration des graphiques
        Chart.defaults.font.family = "'Poppins', 'sans-serif'";
        Chart.defaults.color = '#64748b';
        
        // Fonction pour rendre les graphiques responsives
        function createResponsiveCharts() {
            // Graphique des revenus
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
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
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
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
                                },
                                font: {
                                    size: window.innerWidth < 768 ? 10 : 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: window.innerWidth < 768 ? 10 : 12
                                }
                            }
                        }
                    }
                }
            });
            
            // Graphique des catégories
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            const categoryChart = new Chart(categoryCtx, {
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
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: window.innerWidth < 768 ? 10 : 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: window.innerWidth < 768 ? 10 : 12
                                },
                                callback: function(value, index) {
                                    const label = this.getLabelForValue(value);
                                    // Limiter la longueur des étiquettes sur petits écrans
                                    if (window.innerWidth < 768 && label.length > 8) {
                                        return label.substr(0, 7) + '...';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
            
            // Graphique horaire
            const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
            const hourlyChart = new Chart(hourlyCtx, {
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
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: window.innerWidth < 768 ? 10 : 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: window.innerWidth < 768 ? 10 : 12
                                }
                            }
                        }
                    }
                }
            });
            
            // Graphique de statut
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const statusChart = new Chart(statusCtx, {
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
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: window.innerWidth < 768 ? 11 : 12
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        }
        
        // Initialiser les graphiques
        document.addEventListener('DOMContentLoaded', function() {
            createResponsiveCharts();
            
            // Redimensionner les graphiques si la fenêtre est redimensionnée
            window.addEventListener('resize', function() {
                // Détruire et recréer les graphiques pour qu'ils s'adaptent
                Chart.helpers.each(Chart.instances, function(instance) {
                    instance.destroy();
                });
                createResponsiveCharts();
            });
        });
    </script>
</body>
</html>
<?php
}
?>