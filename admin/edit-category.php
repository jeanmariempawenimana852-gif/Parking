<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['vpmsaid'])==0) {
  header('location:logout.php');
  } else{

if(isset($_POST['submit']))
  {
    $aid=$_SESSION['vpmsaid'];
    $catname=$_POST['catename'];
    $eid=$_GET['editid'];
   
    // CORRECTION : Utiliser le bon nom de colonne 'Categorie_vehicule'
    $query=mysqli_query($con, "update categories set Categorie_vehicule='$catname' where ID='$eid'");
    if ($query) {
        echo "<script>alert('Catégorie modifiée avec succès!');</script>";
        echo "<script>window.location.href='manage-category.php';</script>";
    }
    else
    {
        echo "<script>alert('Erreur lors de la modification. Veuillez réessayer.');</script>";
    }
  }
?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <title>Greenpark - Gestion des Catégories</title>
    
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
   <?php include_once('includes/sidebar.php');?>
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
                                    <li><a href="manage-category.php">Catégories</a></li>
                                    <li class="active">Modifier la Catégorie</li>
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
                    <?php
                    // Vérifier si editid existe
                    if(isset($_GET['editid']) && !empty($_GET['editid'])) {
                        $cid = intval($_GET['editid']); // Sécuriser l'ID
                        $ret = mysqli_query($con, "select * from categories where ID='$cid'");
                        
                        if(mysqli_num_rows($ret) > 0) {
                            while ($row = mysqli_fetch_array($ret)) {
                    ?>
                    
                    <!-- Affichage de la catégorie actuelle -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <strong><i class="fa fa-info-circle"></i> Catégorie actuelle</strong>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <strong>Nom actuel :</strong> <?php echo htmlspecialchars($row['Categorie_vehicule']); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire de modification -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <strong><i class="fa fa-edit"></i> Modifier</strong> la catégorie
                            </div>
                            <div class="card-body card-block">
                                <form action="" method="post" class="form-horizontal">
                                    <div class="row form-group">
                                        <div class="col col-md-3">
                                            <label for="catename" class="form-control-label">Nom de la catégorie <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <input type="text" 
                                                   id="catename" 
                                                   name="catename" 
                                                   class="form-control" 
                                                   placeholder="Entrez le nom de la catégorie" 
                                                   required="true" 
                                                   value="<?php echo htmlspecialchars($row['Categorie_vehicule']); ?>"
                                                   maxlength="120">
                                            <small class="form-text text-muted">Maximum 120 caractères</small>
                                        </div>
                                    </div>
                                    
                                    <div class="row form-group">
                                        <div class="col-12">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-success btn-lg" name="submit">
                                                    <i class="fa fa-save"></i> Modifier la catégorie
                                                </button>
                                                <a href="manage-category.php" class="btn btn-secondary btn-lg ml-3">
                                                    <i class="fa fa-arrow-left"></i> Retour
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <?php 
                            }
                        } else {
                            echo '<div class="col-12"><div class="alert alert-danger">Catégorie introuvable!</div></div>';
                        }
                    } else {
                        echo '<div class="col-12"><div class="alert alert-warning">ID de catégorie manquant!</div></div>';
                    }
                    ?>
                </div>
            </div>
        </div>

    <div class="clearfix"></div>
    <?php include_once('includes/footer.php');?>

</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
<script src="assets/js/main.js"></script>

<!-- Script pour confirmation avant modification -->
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    if (!confirm('Êtes-vous sûr de vouloir modifier cette catégorie ?')) {
        e.preventDefault();
    }
});
</script>

</body>
</html>
<?php } ?>