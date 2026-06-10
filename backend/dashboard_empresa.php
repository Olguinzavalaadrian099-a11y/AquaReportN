<?php
session_start();
include 'conexion.php'; 
if (!isset($_SESSION['empresa_id'])) {
    header("Location: login.php");
    exit();
}
$stmt = $conexion->query("SELECT * FROM reportes ORDER BY id_reporte DESC");
$reportes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AquaReport - Reportes</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }

        .page-bg { 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background-image: url('frontend/img/fondo3.png'); 
            background-size: cover; 
            background-position: center; 
            filter: brightness(0.4); 
            z-index: -1; 
        }

        body { 
            padding-top: 200px; 
            min-height: 100vh; 
            color: #fff; 
        }

        nav { 
            background: linear-gradient(to bottom, rgba(7, 27, 52, 0.98) 0%, rgba(7, 27, 52, 0.85) 45%, rgba(7, 27, 52, 0.45) 75%, rgba(7, 27, 52, 0) 100%); 
            padding: 1rem 2rem; 
            position: fixed; 
            width: 100%; 
            top: 0; 
            left: 0; 
            z-index: 1000; 
            backdrop-filter: blur(1px); 
        }

        .nav-container { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            max-width: 1200px; 
            margin: 0 auto; 
        }

        .logo { 
            height: clamp(140px, 18vh, 240px); 
            width: auto; 
            object-fit: contain; 
        }

        .nav-links { 
            display: flex; 
            gap: 2rem; 
            list-style: none; 
        }

        .nav-links a { 
            color: white; 
            text-decoration: none; 
            font-weight: 500; 
            background: linear-gradient(-45deg, #63A4FF 0%, #83EAF1 100%); 
            -webkit-background-clip: text; 
            background-clip: text; 
            -webkit-text-fill-color: transparent; 
        }

        .container { 
            max-width: 900px; 
            margin: 0 auto; 
            padding: 20px; 
        }

        .report-card { 
            width: 100%; 
            margin: 0 auto 30px auto; 
            background: rgba(10, 25, 45, 0.70); 
            border: 1px solid rgba(255, 255, 255, 0.12); 
            border-radius: 24px; 
            padding: 30px; 
            display: grid; 
            grid-template-columns: 180px 1fr; 
            gap: 40px; 
            align-items: center; 
            backdrop-filter: blur(20px); 
        }

        .report-id { 
            font-size: 1.8rem; 
            font-weight: 900; 
            background: linear-gradient(-45deg, #63A4FF 0%, #83EAF1 100%); 
            -webkit-background-clip: text; 
            background-clip: text; 
            -webkit-text-fill-color: transparent; 
            display: block; 
        }

        .info-data { 
            display: flex; 
            flex-direction: column; 
            gap: 15px; 
        }

        .map-box { 
            height: 160px; 
            border-radius: 20px; 
            background: #071b34; 
        }

        .select-estado { 
            padding: 12px 30px; 
            border-radius: 100px; 
            border: none; 
            background: linear-gradient(-45deg, #63A4FF 0%, #83EAF1 100%); color: #ffffff; 
            font-weight: bold; cursor: pointer; 
            text-transform: uppercase; 
        }

        h1 { 
            text-align: center; 
            margin-bottom: 50px; 
            font-size: 3rem; 
            background: linear-gradient(-45deg, #63A4FF 0%, #83EAF1 100%); 
            -webkit-background-clip: text; 
            background-clip: text; 
            -webkit-text-fill-color: transparent; 
        }
    </style>
</head>
<body>
    <div class="page-bg"></div>
    <nav>
        <div class="nav-container">
            <img src="../frontend/img/logo.png" alt="Logo" class="logo">
            <ul class="nav-links">
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h1>Reportes Recibidos</h1>
        <?php foreach($reportes as $row) { ?>
        <div class="report-card">
            <div id="map-<?php echo $row['id_reporte']; ?>" class="map-box"></div>
            <div class="info-data">
                <div>
                    <span class="report-id">Reporte #<?php echo $row['id_reporte']; ?></span>
                    <p><strong>Usuario:</strong> <?php echo htmlspecialchars($row['nombre_completo']); ?></p>
                </div>
                <div>
                    <p class="coords">Lat: <?php echo $row['latitud']; ?> | Lon: <?php echo $row['longitud']; ?></p>
                    <select id="select-<?php echo $row['id_reporte']; ?>" class="select-estado" onchange="actualizarEstado(<?php echo $row['id_reporte']; ?>, this.value)">
                        <option value="Pendiente" <?php if($row['estado'] == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
                        <option value="En proceso" <?php if($row['estado'] == 'En proceso') echo 'selected'; ?>>En proceso</option>
                        <option value="Resuelto" <?php if($row['estado'] == 'Resuelto') echo 'selected'; ?>>Resuelto</option>
                    </select>
                </div>
            </div>
        </div>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            var map = L.map('map-<?php echo $row['id_reporte']; ?>', {zoomControl: false, dragging: false}).setView([<?php echo $row['latitud']; ?>, <?php echo $row['longitud']; ?>], 15);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {subdomains: 'abcd', maxZoom: 19}).addTo(map);
            L.marker([<?php echo $row['latitud']; ?>, <?php echo $row['longitud']; ?>]).addTo(map);
        </script>
        <?php } ?>
    </div>
    <script>
    function actualizarEstado(id, nuevoEstado) {
        let formData = new FormData();
        formData.append('id', id);
        formData.append('estado', nuevoEstado);
        
        let selectElement = document.getElementById('select-' + id);
        
        selectElement.style.transition = "background 0.3s";
        selectElement.style.background = "#ffffff"; 
    
        fetch('procesar_estado.php', { 
            method: 'POST', 
            body: formData 
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "Éxito") {
                selectElement.style.background = "linear-gradient(-45deg, #63A4FF 0%, #83EAF1 100%)";
            } else {
                alert("Error al actualizar: " + data);
            }
        })
        .catch(error => alert("Error de conexión"));
    }
    </script>
</body>
</html>