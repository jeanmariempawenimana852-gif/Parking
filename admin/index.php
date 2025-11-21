<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['login']))
  {
    $adminuser=$_POST['nom_utilisateur'];
    $password=md5($_POST['motdepasse']);
    $query=mysqli_query($con,"select ID from tbladmin where nom_utilisateur='$adminuser' && motdepasse='$password' ");
    $ret=mysqli_fetch_array($query);
    if($ret>0){
      $_SESSION['vpmsaid']=$ret['ID'];
     header('location:dashboard.php');
    }
    else{
     echo "<script>alert('Identifiants invalides.');</script>";
    }
  }
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>GreenPark - Connexion</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #388E3C;
            --accent-color: #8BC34A;
            --light-color: #F1F8E9;
            --dark-color: #2E7D32;
            --text-color: #333333;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--light-color) 0%, #FFFFFF 100%);
            height: 100vh;
            margin: 0;
            padding: 0;
            color: var(--text-color);
        }
        
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            display: flex;
        }
        
        .login-image {
            background-image: url('/api/placeholder/500/700');
            background-size: cover;
            background-position: center;
            width: 45%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(46, 125, 50, 0.7);
        }
        
        .login-image-content {
            position: relative;
            z-index: 1;
            color: white;
            text-align: center;
            padding: 20px;
        }
        
        .login-form-container {
            width: 55%;
            padding: 40px;
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-logo img {
            max-height: 60px;
        }
        
        .login-heading {
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .form-control {
            border-radius: 30px;
            padding: 12px 20px;
            height: auto;
            background-color: #f9f9f9;
            border: 1px solid #eeeeee;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(139, 195, 74, 0.25);
            background-color: white;
        }
        
        .form-group label {
            font-weight: 500;
            margin-left: 10px;
            font-size: 14px;
        }
        
        .btn-login {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 30px;
            padding: 12px 20px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 15px;
        }
        
        .btn-login:hover, .btn-login:focus {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            box-shadow: 0 7px 20px rgba(76, 175, 80, 0.5);
            transform: translateY(-2px);
        }
        
        .forgot-password {
            text-align: right;
            margin-bottom: 15px;
        }
        
        .forgot-password a {
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .forgot-password a:hover {
            color: var(--dark-color);
            text-decoration: underline;
        }
        
        .home-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .home-link a {
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }
        
        .home-link a i {
            margin-right: 5px;
        }
        
        .home-link a:hover {
            color: var(--dark-color);
        }
        
        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
                max-width: 90%;
            }
            
            .login-image {
                width: 100%;
                height: 200px;
            }
            
            .login-form-container {
                width: 100%;
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-image">
                <div class="login-image-content">
                    <h2>Bienvenue sur GreenPark</h2>
                    <p>Votre solution de gestion de stationnement écologique</p>
                </div>
            </div>
            <div class="login-form-container">
                <div class="login-logo">
                    <img src="/api/placeholder/200/60" alt="GreenPark Logo">
                </div>
                <h3 class="login-heading">Connexion à votre compte</h3>
                <form method="post">
                    <div class="form-group">
                        <label for="nom_utilisateur"><i class="fas fa-user"></i> Nom d'utilisateur</label>
                        <input type="text" class="form-control" id="nom_utilisateur" name="nom_utilisateur" placeholder="Entrez votre nom d'utilisateur" required>
                    </div>
                    <div class="form-group">
                        <label for="motdepasse"><i class="fas fa-lock"></i> Mot de passe</label>
                        <input type="password" class="form-control" id="motdepasse" name="motdepasse" placeholder="Entrez votre mot de passe" required>
                    </div>
                    <div class="forgot-password">
                        <a href="forgot-password.php">Mot de passe oublié?</a>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary btn-login">
                        <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                    </button>
                </form>
                <div class="home-link">
                    <a href="../index.php"><i class="fas fa-home"></i> Retour à l'accueil</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>