
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Park - Sidebar</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/themify-icons/0.1.2/css/themify-icons.css" rel="stylesheet">
    <style>
        /* Thème Green Park */
        :root {
            --green-primary: #28a745;
            --green-secondary: #20c997;
            --green-dark: #1e7e34;
            --green-light: #d4edda;
            --green-hover: #218838;
        }

        .left-panel {
            background: linear-gradient(135deg, #2d5a27 0%, #3d7c47 100%);
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .navbar-nav {
            width: 100%;
        }

        .navbar-nav .nav-link {
            color: #ffffff !important;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 5px 10px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .navbar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
            color: var(--green-light) !important;
        }

        .navbar-nav .active .nav-link {
            background: var(--green-light);
            color: var(--green-dark) !important;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        /* Icônes vertes */
        .menu-icon {
            color: var(--green-secondary) !important;
            font-size: 18px;
            margin-right: 12px;
            width: 24px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .navbar-nav .active .menu-icon {
            color: var(--green-dark) !important;
            transform: scale(1.1);
        }

        .navbar-nav .nav-link:hover .menu-icon {
            color: #ffffff !important;
            transform: scale(1.2);
        }

        /* Dropdown styling */
        .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            margin-top: 5px;
        }

        .dropdown-item {
            color: var(--green-dark);
            padding: 12px 20px;
            border-radius: 6px;
            margin: 3px 8px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: var(--green-light);
            color: var(--green-dark);
            transform: translateX(5px);
        }

        .dropdown-item .menu-icon {
            color: var(--green-primary) !important;
            margin-right: 10px;
        }

        .dropdown-toggle::after {
            color: var(--green-secondary);
            margin-left: auto;
        }

        /* Animation pour les icônes */
        @keyframes iconPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .navbar-nav .active .menu-icon {
            animation: iconPulse 2s infinite;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .left-panel {
                position: fixed;
                width: 100%;
                z-index: 1000;
            }
        }

        /* Effet de survol spécial pour Green Park */
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--green-secondary);
            transform: scaleY(0);
            transition: transform 0.3s ease;
            border-radius: 0 4px 4px 0;
        }

        
        a{
            text-decoration: none;
        }
    </style>
</head>
<body>
    <aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active">
                    <a href="dashboard.php"><i class="menu-icon fa fa-tachometer-alt"></i><span data-translate="dashboard">Tableau de bord</span></a>
                </li>

                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-car"></i><span data-translate="cat">Catégorie de Véhicule</span> </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="menu-icon fa fa-car"></i><a href="add-category.php"><span data-translate="addcat">Ajouter des Catégories</span></a></li>
                        <li><i class="menu-icon fa fa-car"></i><a href="manage-category.php"> <span data-translate="managecat">Gestion des Catégories</span></a></li>
                    </ul>
                </li>
                <li>
                    <a href="add-vehicle.php"> <i class="menu-icon fa fa-plus"></i><span data-translate="add">Ajouter le Véhicule</span> </a>
                </li>
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-tasks"></i> <span data-translate="manage">Gérer le Véhicule</span> </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="menu-icon fa fa-arrow-down text-success"></i><a href="manage-incomingvehicle.php"> <span data-translate="in">Gérer les Véhicules Entrants</span> </a></li>
                        <li><i class="menu-icon fa fa-arrow-up text-info"></i><a href="manage-outgoingvehicle.php"> <span data-translate="out">Gérer les Véhicules Sortants</span> </a></li>
                    </ul>
                </li>
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-chart-bar"></i> <span data-translate="rep">Rapports</span> </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="menu-icon fa fa-calendar-alt"></i><a href="bwdates-report-ds.php"> <span data-translate="report">Rapports entre Dates</span> </a></li>
                    </ul>
                </li>
                <li>
                    <a href="search-vehicle.php"> <i class="menu-icon fa fa-search"></i> <span data-translate="search">Rechercher un Véhicule</span> </a>
                </li>
                <li>
                    <a href="reg-users.php" > <i class="menu-icon ti-user"></i> <span data-translate="users">Utilisateurs Registrés</span> </a>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
</aside>