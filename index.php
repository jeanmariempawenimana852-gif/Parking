<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenPark | Système de Gestion de Stationnement</title>
    <!-- Font Awesome icons -->
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2ecc71;
            --secondary-color: #27ae60;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --accent-color: #3498db;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, rgba(46,204,113,0.1) 0%, rgba(39,174,96,0.1) 100%);
        }
        
        .login-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .login-card {
            max-width: 450px;
            width: 100%;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }
        
        .logo {
            margin-bottom: 1.5rem;
        }
        
        .logo h1 {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0;
        }
        
        .logo p {
            color: var(--dark-color);
            opacity: 0.8;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }
        
        .logo-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .btn-access {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 30px;
            font-weight: 500;
            font-size: 1.1rem;
            margin: 0.75rem 0;
            transition: all 0.3s ease;
            width: 100%;
            box-shadow: 0 4px 10px rgba(46, 204, 113, 0.2);
        }
        
        .btn-access:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(46, 204, 113, 0.3);
            color: white;
        }
        
        .access-divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
        }
        
        .access-divider .line {
            flex: 1;
            height: 1px;
            background-color: #e0e0e0;
        }
        
        .access-divider .text {
            padding: 0 1rem;
            color: #999;
            font-size: 0.9rem;
        }
        
        footer {
            text-align: center;
            padding: 1.5rem;
            background-color: white;
            color: var(--dark-color);
            font-size: 0.9rem;
            border-top: 1px solid rgba(0,0,0,0.05);
        }
        
        .animated {
            animation: fadeInUp 0.6s ease forwards;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .delay-1 {
            animation-delay: 0.2s;
        }
        
        .delay-2 {
            animation-delay: 0.4s;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card animated">
            <div class="logo">
                <i class="fas fa-leaf logo-icon"></i>
                <h1>GreenPark</h1>
                <p>Système de Gestion de Stationnement</p>
            </div>
            
            <div class="animated delay-1">
                <a href="users/login.php" class="btn btn-access d-block">
                    <i class="fas fa-user me-2"></i>Espace Utilisateurs
                </a>
            </div>
            
            <div class="access-divider">
                <div class="line"></div>
                <div class="text">ou</div>
                <div class="line"></div>
            </div>
            
            <div class="animated delay-2">
                <a href="admin/index.php" class="btn btn-access d-block" style="background: var(--dark-color);">
                    <i class="fas fa-cog me-2"></i>Espace Administration
                </a>
            </div>
        </div>
    </div>
    
    <footer>
        <p class="mb-0">&copy; 2025 GreenPark. Tous droits réservés.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Script -->
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Add animation class to elements when they come into view
        function animateOnScroll() {
            const elements = document.querySelectorAll('.feature-box, .section-title');
            
            elements.forEach(element => {
                const elementPosition = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (elementPosition < windowHeight - 100) {
                    element.classList.add('animated');
                }
            });
        }
        
        // Call on scroll
        window.addEventListener('scroll', animateOnScroll);
        
        // Call once on page load
        window.addEventListener('load', animateOnScroll);
    </script>
</body>
</html>