<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['vpmsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $parkingnumber = mt_rand(100000000, 999999999);
        $catename = $_POST['catename']; 
        $vehcomp = $_POST['compagnie_vehicule'];
        $vehreno = $_POST['plaque']; 
        $ownername = $_POST['Nom_proprietaire'];
        $ownercontno = $_POST['Telephone_proprietaire'];

        // Get current time for parking duration calculation
        $entryTime = date('Y-m-d H:i:s'); // Current time
        $hourlyRate = 5000; // Fee per hour

        // Insert vehicle details into the database
        $query = mysqli_query($con, "INSERT INTO vehicule (Numero_parking, plaque, Categorie_vehicule, compagnie_vehicule, Telephone_proprietaire, Nom_proprietaire, temps_entree, frais) VALUES ('$parkingnumber', '$vehreno', '$catename', '$vehcomp', '$ownercontno', '$ownername', '$entryTime', '$hourlyRate')");
        
        if ($query) {
            echo "<script>alert('Les détails d'entrée du véhicule ont été ajoutés');</script>";
            echo "<script>window.location.href ='manage-incomingvehicle.php'</script>";
        } else {
            echo "<script>alert('Une erreur s'est produite. Veuillez réessayer.');</script>";
        }
    }
?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <title>Green park - Ajouter Véhicule</title>
    <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="shortcut icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
</head>
<body>
    <?php include_once('includes/sidebar.php'); ?>
    <?php include_once('includes/header.php'); ?>

    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Tableau de bord</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="dashboard.php">Tableau de bord</a></li>
                                <li><a href="add-vehicle.php">Véhicule</a></li>
                                <li class="active">Ajouter Véhicule</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>Ajouter des </strong> Véhicules
                        </div>
                        <div class="card-body card-block">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="select" class="form-control-label">Selectionner</label></div>
                                    <div class="col-12 col-md-9">
                                        <select name="catename" id="catename" class="form-control" required>
                                            <option value="0">Selectionner la Catégorie</option>
                                            <?php
                                            $query = mysqli_query($con, "SELECT * FROM categories");
                                            while ($row = mysqli_fetch_array($query)) {
                                            ?>
                                                <option value="<?php echo $row['Categorie_vehicule']; ?>"><?php echo $row['Categorie_vehicule']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class="form-control-label">compagnie du Véhicule</label></div>
                                    <div class="col-12 col-md-9"><input type="text" id="vehcomp" name="compagnie_vehicule" class="form-control" placeholder="compagnie du Véhicule" required></div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class="form-control-label">Plaque</label></div>
                                    <div class="col-12 col-md-9"><input type="text" id="vehreno" name="plaque" class="form-control" placeholder="Plaque" required></div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class="form-control-label">Nom du propriétaire</label></div>
                                    <div class="col-12 col-md-9"><input type="text" id="ownername" name="Nom_proprietaire" class="form-control" placeholder="Nom du propriétaire" required></div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3"><label for="text-input" class="form-control-label">Contact du propriétaire</label></div>
                                    <div class="col-12 col-md-9"><input type="text" id="ownercontno" name="Telephone_proprietaire" class="form-control" placeholder="Contact du propriétairer" required maxlength="10" pattern="[0-9]+"></div>
                                </div>
                                <p style="text-align: center;">
                                    <button type="submit" class="btn btn-primary btn-sm" name="submit">Ajouter</button>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .animated -->
    </div><!-- .content -->

    <div class="clearfix"></div>
    <?php include_once('includes/footer.php'); ?>
</div><!-- /#right-panel -->

<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>
<?php } ?>