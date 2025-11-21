<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['vpmsuid']==0)) {
  header('location:logout.php');
  } else{
   if(isset($_POST['submit']))
  {
    $uid=$_SESSION['vpmsuid'];
    $fname=$_POST['Prenom'];
    $lname=$_POST['Nom'];
    $query=mysqli_query($con, "update utilisateurs set Prenom='$fname', Nom='$lname' where ID='$uid'");
    if ($query) {
 echo '<script>alert("Profil mis à jour avec succès.")</script>';
echo '<script>window.location.href=profile.php</script>';
  }
  else
    {
      echo '<script>alert("Une erreur est survenue. essayer plus tard.")</script>';
    }
}
  ?>
<!doctype html>
<html class="no-js" lang="">
<head>
    
    <title>GreenPark - Profil utilisateur</title>
   

    <link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
    <link rel="shortcut icon" href="https://i.imgur.com/QRAUqs9.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="../admin/assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="../admin/assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->

</head>
<body>
   <?php include_once('includes/sidebar.php');?>
    <!-- Right Panel -->

   <?php include_once('includes/header.php');?>

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
                                    <li><a href="profile.php">Profil</a></li>
                                    <li class="active">Profil d'utilisateur</li>
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
                    <div class="col-lg-6">
                        <div class="card">
                            
                           
                        </div> <!-- .card -->

                    </div><!--/.col-->

              

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <strong>Profile </strong> utilisateur
                            </div>
                            <div class="card-body card-block">
                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                   
                                   <?php
$uid=$_SESSION['vpmsuid'];
$ret=mysqli_query($con,"select * from utilisateurs where ID='$uid'");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {
?> 
                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Prénom</label></div>
                                        <div class="col-12 col-md-9"> <input type="text" name="Prenom" required="true" class="form-control" value="<?php  echo $row['Prenom'];?>">
                                            <br></div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="email-input" class=" form-control-label">Nom</label></div>
                                        <div class="col-12 col-md-9"><input type="text" name="Nom" required="true" class="form-control" value="<?php  echo $row['Nom'];?>"></div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="password-input" class=" form-control-label">Téléphone</label></div>
                                        <div class="col-12 col-md-9"> <input type="text" name="telephone" maxlength="10" pattern="[0-9]{10}" readonly="true" class="form-control" value="<?php  echo $row['telephone'];?>"></div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="disabled-input" class=" form-control-label">Addresse email</label></div>
                                        <div class="col-12 col-md-9"><input type="email" name="Email" required="true" class="form-control" value="<?php  echo $row['Email'];?>"  readonly="true"></div>
                                    </div>
                                  
                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="disabled-input" class=" form-control-label">Date d'enregistrement </label></div>
                                        <div class="col-12 col-md-9"><input type="text" name="regdate" value="<?php  echo $row['date_enregistrement'];?>"  readonly="true" class="form-control"></div>
                                    </div>
                                    <?php } ?>
                                   <p style="text-align: center;"> <button type="submit" class="btn btn-primary btn-sm" name="submit" >Modifier</button></p>
                                </form>
                            </div>
                            
                        </div>
                        
                    </div>

                    <div class="col-lg-6">
                        
                  
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
<script src="../admin/assets/js/main.js"></script>


</body>
</html>
<?php }  ?>