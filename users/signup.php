<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['submit']))
  {
    $fname=$_POST['Prenom'];
    $lname=$_POST['Nom'];
    $contno=$_POST['telephone'];
    $email=$_POST['Email'];
    $password=md5($_POST['motdepasse']);

    $ret=mysqli_query($con, "select Email from utilisateurs where Email='$email' || telephone='$contno'");
    $result=mysqli_fetch_array($ret);
    if($result>0){

echo '<script>alert("Ce numéro de téléphone ou email est déjà associé à un autre compte")</script>';
    }
    else{
    $query=mysqli_query($con, "insert into utilisateurs(Prenom, Nom, telephone, Email, motdepasse) value('$fname', '$lname','$contno', '$email', '$password' )");
    if ($query) {
    
    
     header('location:login.php');
  }
  else
    {
      echo '<script>alert("Une erreur s\'est produite. Veuillez réessayer")</script>';
    }
}
}
  ?>
<!doctype html>
<html class="no-js" lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Parking - Créer un compte</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            /*background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);*/
            min-height: 100vh;
            background: #ffff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="%2322c55e" opacity="0.1"><animate attributeName="opacity" values="0.1;0.3;0.1" dur="3s" repeatCount="indefinite"/></circle><circle cx="80" cy="30" r="1.5" fill="%2316a34a" opacity="0.1"><animate attributeName="opacity" values="0.1;0.4;0.1" dur="2s" repeatCount="indefinite"/></circle><circle cx="60" cy="70" r="2.5" fill="%2315803d" opacity="0.1"><animate attributeName="opacity" values="0.1;0.2;0.1" dur="4s" repeatCount="indefinite"/></circle></svg>') repeat;
            z-index: -1;
        }

        .signup-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            padding: 40px;
            width: 100%;
            max-width: 500px;
            margin: 20px;
            position: relative;
            border: 1px solid rgba(34, 197, 94, 0.1);
        }

        .signup-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #22c55e, #16a34a, #15803d);
            border-radius: 20px 20px 0 0;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            box-shadow: 0 10px 30px rgba(34, 197, 94, 0.3);
        }

        .logo-icon i {
            font-size: 35px;
            color: white;
        }

        .logo-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .logo-subtitle {
            font-size: 16px;
            color: #6b7280;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #22c55e;
            font-size: 16px;
        }

        .form-control {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f9fafb;
            font-family: 'Poppins', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: #22c55e;
            background: white;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }

        .form-control:hover {
            border-color: #16a34a;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
            font-size: 16px;
        }

        .password-toggle:hover {
            color: #22c55e;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 8px 25px rgba(34, 197, 94, 0.3);
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #16a34a, #15803d);
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(34, 197, 94, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .links-section {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .links-section a {
            color: #22c55e;
            text-decoration: none;
            font-weight: 500;
            margin: 0 15px;
            transition: color 0.3s ease;
        }

        .links-section a:hover {
            color: #16a34a;
            text-decoration: underline;
        }

        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .success-message {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .signup-container {
                padding: 30px 20px;
                margin: 10px;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .logo-title {
                font-size: 24px;
            }

            .links-section a {
                display: block;
                margin: 5px 0;
            }
        }

        /* Animation */
        .signup-container {
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script type="text/javascript">
        function checkpass() {
            if(document.signup.password.value != document.signup.repeatpassword.value) {
                alert('Les mots de passe ne correspondent pas');
                document.signup.repeatpassword.focus();
                return false;
            }
            return true;
        }

        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</head>
<body>
    <div class="signup-container">
        <div class="logo-section">
            <div class="logo-icon">
                <i class="fas fa-car"></i>
            </div>
            <h1 class="logo-title">Green Parking</h1>
            <p class="logo-subtitle">Créez votre compte</p>
        </div>

        <form method="post" name="signup" onsubmit="return checkpass();">
            <div class="form-row">
                <div class="form-group">
                    <label for="firstname">Prénom</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="firstname" name="Prenom" placeholder="Votre prénom" required class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastname">Nom</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="lastname" name="Nom" placeholder="Votre nom" required class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="mobilenumber">Numéro de téléphone</label>
                <div class="input-wrapper">
                    <i class="fas fa-phone"></i>
                    <input type="text" id="mobilenumber" name="telephone" maxlength="8" pattern="[0-9]{8}" placeholder="12345678" required class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="email">Adresse email</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="Email" placeholder="votre@email.com" required class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="motdepasse" placeholder="Votre mot de passe" required class="form-control">
                    <i class="fas fa-eye password-toggle" id="togglePassword1" onclick="togglePassword('password', 'togglePassword1')"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="repeatpassword">Confirmer le mot de passe</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="repeatpassword" name="repeatpassword" placeholder="Confirmez votre mot de passe" required class="form-control">
                    <i class="fas fa-eye password-toggle" id="togglePassword2" onclick="togglePassword('repeatpassword', 'togglePassword2')"></i>
                </div>
            </div>

            <button type="submit" name="submit" class="submit-btn">
                <i class="fas fa-user-plus" style="margin-right: 8px;"></i>
                CRÉER MON COMPTE
            </button>
        </form>

        <div class="links-section">
            <a href="login.php">
                <i class="fas fa-sign-in-alt" style="margin-right: 5px;"></i>
                Déjà inscrit ? Se connecter
            </a>
            <a href="forgot-password.php">
                <i class="fas fa-key" style="margin-right: 5px;"></i>
                Mot de passe oublié ?
            </a>
        </div>
    </div>
</body>
</html>