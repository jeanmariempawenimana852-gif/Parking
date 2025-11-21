<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['vpmsaid']) == 0) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Parking Receipt</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            color: #334155;
            margin: 0;
            padding: 20px;
        }

        .receipt-container {
            max-width: 600px;
            margin: auto;
            border: 1px solid #cbd5e1;
            border-radius: 16px;
            background-color: white;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            position: relative;
        }

        .logo {
            margin-bottom: 15px;
        }

        .logo i {
            font-size: 40px;
            color: #10b981;
        }

        .header h2 {
            margin: 0;
            color: #10b981;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .header p {
            color: #64748b;
            margin: 5px 0;
        }
        
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #10b981, transparent);
            margin: 20px 0;
        }

        .info-section {
            margin-top: 25px;
        }

        .section-title {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .section-title i {
            margin-right: 10px;
            color: #10b981;
        }

        .section-title h4 {
            margin: 0;
            color: #10b981;
            font-weight: 600;
            font-size: 18px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .info-row {
            margin-bottom: 12px;
        }

        .full-width {
            grid-column: span 2;
        }

        .info-label {
            font-weight: 500;
            color: #64748b;
            display: block;
            margin-bottom: 4px;
            font-size: 14px;
        }

        .info-value {
            font-weight: 500;
            color: #334155;
            font-size: 16px;
        }

        .qr-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 30px 0;
        }
        
        .qr-container {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            display: inline-block;
        }

        .qr-container img {
            display: block;
            max-width: 150px;
            height: auto;
        }
        
        .qr-caption {
            font-size: 14px;
            color: #64748b;
            margin-top: 10px;
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #64748b;
            padding-top: 20px;
            border-top: 1px dashed #e2e8f0;
        }

        .eco-label {
            display: inline-flex;
            align-items: center;
            background-color: #d1fae5;
            color: #065f46;
            padding: 5px 10px;
            border-radius: 30px;
            font-size: 12px;
            margin-top: 10px;
        }
        
        .eco-label i {
            margin-right: 5px;
        }

        .print-button {
            text-align: center;
            margin-top: 25px;
        }

        .print-button button {
            background-color: #10b981;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.25);
        }

        .print-button button:hover {
            background-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(16, 185, 129, 0.3);
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .status-incoming {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-outgoing {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .payment-info {
            background-color: #f8fafc;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
            border-left: 4px solid #10b981;
        }

        @media print {
            body {
                background-color: white;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .receipt-container {
                box-shadow: none;
                border: 1px solid #e2e8f0;
                max-width: 100%;
            }
            
            .print-button {
                display: none;
            }
            
            .qr-container {
                border: 2px solid #e2e8f0;
                page-break-inside: avoid;
            }
            
            /* Ensure QR code prints well */
            .qr-container img {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
<?php
$cid = $_GET['vid'];
$ret = mysqli_query($con, "SELECT * FROM vehicule WHERE ID='$cid'");
while ($row = mysqli_fetch_array($ret)) {
?>
<div class="receipt-container" id="exampl">
    <div class="header">
        <div class="logo">
            <i class="fa fa-leaf"></i>
        </div>
        <h2>GreenPark Parking</h2>
        <p>Kiriri chaussée du P.L Rwagasore, Bujumbura</p>
        <p><i class="fa fa-phone"></i> +25768505909</p>
    </div>

    <div class="divider"></div>

    <div class="info-section">
        <div class="section-title">
            <i class="fa fa-car"></i>
            <h4>Informations Véhicule</h4>
        </div>
        
        <div class="info-grid">
            <div class="info-row">
                <span class="info-label">N° Parking</span>
                <span class="info-value"><?php echo $row['Numero_parking']; ?></span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Catégorie</span>
                <span class="info-value"><?php echo $row['Categorie_vehicule']; ?></span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Compagnie</span>
                <span class="info-value"><?php echo $row['compagnie_vehicule']; ?></span>
            </div>
            
            <div class="info-row">
                <span class="info-label">N° d'immatriculation/Plaque</span>
                <span class="info-value"><?php echo $row['plaque']; ?></span>
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="section-title">
            <i class="fa fa-user"></i>
            <h4>Informations Propriétaire</h4>
        </div>
        
        <div class="info-grid">
            <div class="info-row">
                <span class="info-label">Nom</span>
                <span class="info-value"><?php echo $row['Nom_proprietaire']; ?></span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Téléphone</span>
                <span class="info-value"><?php echo $row['Telephone_proprietaire']; ?></span>
            </div>
        </div>
    </div>
    
    <div class="info-section">
        <div class="section-title">
            <i class="fa fa-clock-o"></i>
            <h4>Détails de Stationnement</h4>
        </div>
        
        <div class="info-grid">
            <div class="info-row">
                <span class="info-label">Heure d'entrée</span>
                <span class="info-value"><?php echo $row['temps_entree']; ?></span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Statut</span>
                <span class="info-value">
                    <?php if($row['Status'] == "") { ?>
                        <span class="status-badge status-incoming"><i class="fa fa-sign-in"></i> Véhicule Entrant</span>
                    <?php } else { ?>
                        <span class="status-badge status-outgoing"><i class="fa fa-sign-out"></i> Véhicule Sortant</span>
                    <?php } ?>
                </span>
            </div>

            <?php if ($row['Remark'] != "") { ?>
            <div class="info-row">
                <span class="info-label">Heure de sortie</span>
                <span class="info-value"><?php echo $row['temps_sortie']; ?></span>
            </div>
            
            <div class="info-row full-width payment-info">
                <span class="info-label"><i class="fa fa-money"></i> Frais de stationnement</span>
                <span class="info-value" style="font-size: 18px; color: #10b981; font-weight: 600;"><?php echo $row['frais']; ?> Fbu</span>
                <div style="margin-top: 5px; font-size: 13px; color: #64748b;">
                    <?php echo $row['Remark']; ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <div class="qr-section">
        <div class="section-title">
            <i class="fa fa-qrcode"></i>
            <h4>Code QR</h4>
        </div>
        <div class="qr-container">
            <?php
                $qrData = "Parking ID: " . $row['ID'] . "\n" .
                          "Plaque: " . $row['plaque'] . "\n" .
                          "Propriétaire: " . $row['Nom_proprietaire'] . "\n" .
                          "Entrée: " . $row['temps_entree'];
                $qrEncoded = urlencode($qrData);
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=180x180&margin=10&data=$qrEncoded";
            ?>
            <img src="<?php echo $qrUrl; ?>" alt="QR Code">
        </div>
        <p class="qr-caption">Scannez pour voir les informations du véhicule</p>
    </div>

    <div class="footer">
        <div>Merci d'avoir choisi GreenPark!</div>
        <div class="eco-label">
            <i class="fa fa-leaf"></i> Alimenté par lucky257
        </div>
    </div>

    <div class="print-button">
        <button onclick="CallPrint()">
            <i class="fa fa-print"></i> Imprimer le reçu
        </button>
    </div>
</div>

<script>
function CallPrint() {
    var prtContent = document.getElementById("exampl");
    var WinPrint = window.open('', '', 'width=800,height=900');
    WinPrint.document.write('<html><head><title>GreenPark - Reçu de Stationnement</title>');
    WinPrint.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">');
    WinPrint.document.write('<style>');
    
    // Add all styles again for the print window
    var styles = document.getElementsByTagName('style');
    for (var i = 0; i < styles.length; i++) {
        WinPrint.document.write(styles[i].innerHTML);
    }
    
    WinPrint.document.write('</style></head><body>');
    WinPrint.document.write(prtContent.innerHTML);
    WinPrint.document.write('</body></html>');
    WinPrint.document.close();
    WinPrint.focus();
    setTimeout(function() { 
        WinPrint.print();
        WinPrint.close();
    }, 500);
}
</script>
<?php } ?>
</body>
</html>
<?php } ?>