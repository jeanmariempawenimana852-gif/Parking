<div id="right-panel" class="right-panel">
<header id="header" class="header">
            <div class="top-left">
                <div class="navbar-header" style="padding-top: 20px;color: green;">
                    <strong >Greenpark</strong>
                </div>
            </div><br>
            <div id="date-time" style="color: black; font-weight: bold;"></div>
            <div class="top-right">
                <div class="header-menu">
                    
                    <div class="header-left">
                        
                        <div class="form-inline">
                           
                        </div>

                     
                    </div>

                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="user-avatar rounded-circle" src="../admin/images/images.png" alt="User Avatar">
                        </a>

                        <div class="user-menu dropdown-menu">
                            <a class="nav-link" href="profile.php"><i class="fa fa- user"></i>Mon Profile</a>

                            <a class="nav-link" href="change-password.php"><i class="fa fa -cog"></i>Changer le mot de passe</a>

                            <a class="nav-link" href="logout.php"><i class="fa fa-power -off"></i>Se Déconnecter</a>
                        </div>
                    </div>

                </div>
            </div>
        </header>
        <script>
            function updateDateTime() {
    const now = new Date();
    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
    const dateTimeString = now.toLocaleString('fr-FR', options); // Format in French

    document.getElementById('date-time').textContent = dateTimeString;
}

// Update the date and time every second
setInterval(updateDateTime, 1000);

// Initial call to display immediately
updateDateTime();

        </script>