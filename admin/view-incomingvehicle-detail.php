<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['vpmsaid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $cid = $_GET['viewid'];
        $remark = $_POST['remark'];
        $status = $_POST['status'];
        $parkingcharge = $_POST['frais'];

        $query = mysqli_query($con, "UPDATE vehicule SET Remark='$remark', Status='$status', frais='$parkingcharge' WHERE ID='$cid'");
        if ($query) {
            echo "<script>alert('Tous les remarques ont été mises à jour');</script>";
        } else {
            echo "<script>alert('Quelque chose a mal tourné. Veuillez réessayer');</script>";
        }
    }
?>
<!doctype html>
<html class="no-js" lang="fr">
<head>
    <title data-translate="title">Green park - Voir les Détails du Véhicule</title>
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
                            <h1 data-translate="dashboard">Tableau de bord</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="dashboard.php" data-translate="dashboard">Tableau de bord</a></li>
                                <li><a href="manage-incomingvehicle.php" data-translate="view_vehicle">Voir le Véhicule</a></li>
                                <li class="active" data-translate="incoming_vehicle">Véhicule Entrant</li>
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
                            <strong class="card-title" data-translate="view_incoming_vehicle">Voir le Véhicule Entrant</strong>
                        </div>
                        <div class="card-body">
                            <?php
                            $cid = $_GET['viewid'];
                            $ret = mysqli_query($con, "SELECT * FROM vehicule WHERE ID='$cid'");
                            while ($row = mysqli_fetch_array($ret)) {
                                $entryTime = strtotime($row['temps_entree']);
                                $currentTime = time();
                                $duration = $currentTime - $entryTime;
                                $hoursParked = ceil($duration / 3600);
                                $rate_per_hour = 5000; // Tarif exemple
                                $parkingcharge = $hoursParked * $rate_per_hour;
                            ?>
                                <table border="1" class="table table-bordered mg-b-0">
                                    <tr>
                                        <th data-translate="parking">Numéro de Parking</th>
                                        <td><?php echo $row['Numero_parking']; ?></td>
                                    </tr>
                                    <tr>
                                        <th data-translate="vehicle_category">Catégorie de Véhicule</th>
                                        <td><?php echo $row['Categorie_vehicule']; ?></td>
                                    </tr>
                                    <tr>
                                        <th data-translate="vehicle_company">Nom de la Compagnie de Véhicule</th>
                                        <td><?php echo $row['compagnie_vehicule']; ?></td>
                                    </tr>
                                    <tr>
                                        <th data-translate="registration_number">Numéro d'Immatriculation</th>
                                        <td><?php echo $row['plaque']; ?></td>
                                    </tr>
                                    <tr>
                                        <th data-translate="owner_name">Nom du Propriétaire</th>
                                        <td><?php echo $row['Nom_proprietaire']; ?></td>
                                    </tr>
                                    <tr>
                                        <th data-translate="owner_contact">Numéro de Contact du Propriétaire</th>
                                        <td><?php echo $row['Telephone_proprietaire']; ?></td>
                                    </tr>
                                    <tr>
                                        <th data-translate="in_time">Heure d'Entrée</th>
                                        <td><?php echo $row['temps_entree']; ?></td>
                                    </tr>
                                    <tr>
                                        <th data-translate="status">Statut</th>
                                        <td><?php echo ($row['Status'] == "") ? "Véhicule Entrant" : "Véhicule Sortant"; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <table class="table mb-0">
                            <?php if ($row['Status'] == "") { ?>
                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                    <tr>
                                        <th data-translate="remark">Remarque:</th>
                                        <td>
                                            <textarea name="remark" rows="6" cols="14" class="form-control" required="true"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th data-translate="parking_charge">Frais de Parking:</th>
                                        <td>
                                            <input type="text" name="frais" value="<?php echo $parkingcharge; ?>" class="form-control" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th data-translate="status">Statut:</th>
                                        <td>
                                            <select name="status" class="form-control" required="true">
                                                <option value="Out" data-translate="outgoing_vehicle">Véhicule Sortant</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align: center;">
                                            <button type="submit" class="btn btn-primary btn-sm" name="submit" data-translate="update">Mettre à Jour</button>
                                        </td>
                                    </tr>
                                </form>
                            <?php } else { ?>
                                <table border="1" class="table table-bordered mg-b-0">
                                    <tr>
                                        <th data-translate="remark">Remarque</th>
                                        <td><?php echo $row['Remark']; ?></td>
                                    </tr>
                                    <tr>
                                        <th data-translate="parking_fee">Frais de Parking</th>
                                        <td><?php echo $row['frais']; ?> FBU</td>
                                    </tr>
                                </table>
                            <?php } ?>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div><!-- .animated -->
    </div><!-- .content -->

    <div class="clearfix"></div>
    <?php include_once('includes/footer.php'); ?>
</div><!-- /#right-panel -->

<script src="translate/en.js"></script>
<script src="translate/fr.js"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
<?php } ?>