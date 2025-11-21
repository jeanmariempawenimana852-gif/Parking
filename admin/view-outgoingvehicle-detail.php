<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['vpmsaid']==0)) {
  header('location:logout.php');
  } else{
   

?>

<!doctype html>

<html class="no-js" lang="">
<head>
   
    <title>Green park - Voir les Détails du Véhicule</title>
   

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

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

</head>
<body>
    <!-- Left Panel -->

  <?php include_once('includes/sidebar.php');?>

    <!-- Left Panel -->

    <!-- Right Panel -->

     <?php include_once('includes/header.php');?>

        <div class="breadcrumbs">
            <div class="breadcrumbs-inner">
                <div class="row m-0">
                    <div class="col-sm-4">
                        <div class="page-header float-left">
                            <div class="page-title">
                                <h1>Dashboard</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="page-header float-right">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="dashboard.php">Dashboard</a></li>
                                    <li><a href="manage-outgoingvehicle.php">Voir les Vehicules</a></li>
                                    <li class="active">Les Véhicules sortants</li>
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
                            <strong class="card-title">Voir les Véhicules sortants</strong>
                        </div>
                        <div class="card-body">
                  
              <?php
 $cid=$_GET['viewid'];
$ret=mysqli_query($con,"select * from vehicule where ID='$cid'");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>                       <table border="1" class="table table-bordered mg-b-0">
   
   <tr>
                                <th>Numéro de parking</th>
                                   <td><?php  echo $row['Numero_parking'];?></td>
                                   </tr>                             
<tr>
                                <th>Catégorie du véhicule</th>
                                   <td><?php  echo $row['Categorie_vehicule'];?></td>
                                   </tr>
                                   <tr>
                                <th>compagnie du véicule</th>
                                   <td><?php  echo $packprice= $row['compagnie_vehicule'];?></td>
                                   </tr>
                                <tr>
                                <th>Numéro d'Immatriculation du Véhicule</th>
                                   <td><?php  echo $row['plaque'];?></td>
                                   </tr>
                                   <tr>
                                    <th>Nom du Propriétaire</th>
                                      <td><?php  echo $row['Nom_proprietaire'];?></td>
                                  </tr>
                                      <tr>  
                                       <th>Contact du Propriétaire</th>
                                        <td><?php  echo $row['Telephone_proprietaire'];?></td>
                                    </tr>
                                    <tr>
                               <th>Temps d'entrée</th>
                                <td><?php  echo $row['temps_entree'];?></td>
                            </tr>
                                   <tr>
                               <th>Temps de sortie</th>
                                <td><?php  echo $row['temps_sortie'];?></td>
                            </tr>
                            <tr>
    <th>Remarque</th>
    <td><?php echo $row['Remark']; ?></td>
  </tr>
   <tr>
    <th>Status</th>
    <td><?php echo $row['Status']; ?></td>
  </tr>
<tr>
   <tr>
    <th>Frais de Parking</th>
   <td><?php echo $row['frais']; ?></td>
  </tr>


                           

</table>

                    </div>
                </div>
                

 


  

  

<?php } ?>
            </div>



        </div>
    </div><!-- .animated -->
</div><!-- .content -->

<div class="clearfix"></div>

<?php include_once('includes/footer.php');?>

</div><!-- /#right-panel -->

<!-- Right Panel -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
<script src="assets/js/main.js"></script>


</body>
</html>
<?php }  ?>