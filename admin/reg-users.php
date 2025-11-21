<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['vpmsaid']==0)) {
  header('location:logout.php');
  } else{
// For deleting    
if($_GET['del']){
$catid=$_GET['del'];
mysqli_query($con,"delete from utilisateurs where ID ='$catid'");
echo "<script>alert('Data Deleted');</script>";
echo "<script>window.location.href='reg-users.php'</script>";
          }


  ?>
<!doctype html>

<html class="no-js" lang="">
<head>
   
    <title>Green park - Gestion des utilisateurs</title>

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
                                <h1>Tableau de bord</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="page-header float-right">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="dashboard.php">Tableau de bord</a></li>
                                    <li><a href="reg-users.php">Utilisateurs enregistrés</a></li>
                                    <li class="active">Utilisateurs enregistrés</li>
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
                            <strong class="card-title">Utilisateurs enregistrés</strong>
                        </div>
                        <div class="card-body">
                             <table class="table">
                <thead>
                                        <tr>
                                            <tr>
                  <th>NO</th>
            
                 
                    <th>Nom</th>
                   <th>Téléphone</th>
                   <th>Email</th>
                <th>Date d'enregistrement</th>
                <th>Action</th>
                </tr>
                                        </tr>
                                        </thead>
               <?php
$ret=mysqli_query($con,"select *from  utilisateurs");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
              
                <tr>
                  <td><?php echo $cnt;?></td>
            
                 
                  <td><?php  echo $row['Prenom'];?> <?php  echo $row['Nom'];?></td>
                  <td><?php  echo $row['telephone'];?></td>
                  <td><?php  echo $row['Email'];?></td>
                  <td><?php  echo $row['date_enregistrement'];?></td>
                  <td><a href="reg-users.php?del=<?php echo $row['ID'];?>" class="btn btn-danger" onClick="return confirm('Êtes-vous sûr de vouloir supprimer ?')"><span data-translate="delete">supprimer</span></a></td>
                </tr>
                <?php 
$cnt=$cnt+1;
}?>
              </table>

                    </div>
                </div>
            </div>



        </div>
    </div><!-- .animated -->
</div><!-- .content -->

<div class="clearfix"></div>

 <script src="translate/en.js"></script>
<script src="translate/fr.js"></script>

<script>
     // translation js 
const translations = {
    fr: translationsFr,
    en: translationsEn
};

document.addEventListener('DOMContentLoaded', () => {
    const languageSelector = document.getElementById('language');
    languageSelector.addEventListener('change', (event) => {
        const selectedLanguage = event.target.value;
        translatePage(selectedLanguage);
    });

    // Initial translation
    translatePage(languageSelector.value);
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
</script>

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