<?php
session_start();
include 'conexion.php';
if (!isset($_SESSION['empresa_id'])) {
    header("Location: login.php");
    exit();
}
$stmt = $conexion->query("SELECT * FROM reportes ORDER BY id_reporte DESC");
$reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            background-image: url('img/fondo3.png'); 
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
            display: block; 
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
            transition: 0.4s ease; 
            position: relative; 
            background: linear-gradient(-45deg, #63A4FF 0%, #83EAF1 100%); 
            -webkit-background-clip: text; 
            background-clip: text; 
            -webkit-text-fill-color: transparent; 
        }

        .nav-links a:not(:hover) { 
            -webkit-text-fill-color: white; 
        }

        .nav-links a::after { 
            content: ""; 
            position: absolute; 
            left: 0; 
            bottom: -6px; 
            width: 0%; 
            height: 2px; 
            background: linear-gradient(-45deg, #63A4FF 0%, #83EAF1 100%); 
            transition: 0.4s ease; 
        }

        .nav-links a:hover::after { width: 100%; }

        .container { 
            max-width: 900px; 
            margin: 0 auto; 
            padding: 20px; 
        }

        h1 { 
            text-align: center; 
            margin-bottom: 40px; 
            font-size: 2.5rem; 
            color: #fff; 
            text-shadow: 0 0 15px rgba(111, 197, 255, 0.5);
        }
        
        .report-card {
            width: 100%;
            max-width: 900px;
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
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4); 
            transition: all 0.4s ease;
        }

        .report-card:hover {
            border-color: rgba(111, 197, 255, 0.5);
            background: rgba(10, 25, 45, 0.80); 
        }

        .report-id {
            font-size: 1.8rem;
            font-weight: 900;
            background: linear-gradient(-45deg, #63A4FF 0%, #83EAF1 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            display: block;
            margin-bottom: 10px;
        }

        .info-data { 
            display: flex; 
            flex-direction: column; 
            gap: 15px; 
        }

        .map-box { 
            height: 160px; 
            border-radius: 20px; 
            overflow: hidden;
            background: #071b34; 
            border: 1px solid rgba(255, 255, 255, 0.1);
            filter: brightness(0.8) contrast(1.1); 
            transition: filter 0.3s ease;
        }

        .map-box:hover {
            filter: brightness(1); 
        }

        .select-estado {
            appearance: none;
            -webkit-appearance: none;
            padding: 12px 30px;
            border-radius: 100px;
            border: none;
            background: linear-gradient(-45deg, #63A4FF 0%, #83EAF1 100%);
            color: #ffffff;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
            font-size: 0.8rem;
            margin-top: 10px;
        }
        h1 {
            text-align: center;
            margin-bottom: 50px;
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            background: linear-gradient(-45deg, #63A4FF 0%, #83EAF1 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 5px 15px rgba(99, 164, 255, 0.2);
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
        <?php while($row = $resultado->fetch_assoc()) { 
            $estado = str_replace(' ', '_', $row['estado']); ?>
        <div class="report-card">
    <div id="map-<?php echo $row['id_reporte']; ?>" class="map-box"></div>
        <div class="info-data">
            <div>
                <span class="report-id">Reporte #<?php echo $row['id_reporte']; ?></span>
                <p><strong>Usuario:</strong> <?php echo htmlspecialchars($row['nombre_completo']); ?></p>
            </div>
            
            <div>
                <p class="coords">Lat: <?php echo $row['latitud']; ?> | Lon: <?php echo $row['longitud']; ?></p>
                
                <select id="select-<?php echo $row['id_reporte']; ?>" 
                        class="select-estado" 
                        onchange="actualizarEstado(<?php echo $row['id_reporte']; ?>, this.value)">
                    <option value="Pendiente" <?php if($row['estado'] == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
                    <option value="En proceso" <?php if($row['estado'] == 'En proceso') echo 'selected'; ?>>En proceso</option>
                    <option value="Resuelto" <?php if($row['estado'] == 'Resuelto') echo 'selected'; ?>>Resuelto</option>
                </select>
            </div>
        </div>
    </div>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            var map = L.map('map-<?php echo $row['id_reporte']; ?>', {
                zoomControl: false, 
                dragging: false,
                attributionControl: false 
            }).setView([<?php echo $row['latitud']; ?>, <?php echo $row['longitud']; ?>], 15);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                subdomains: 'abcd',
                maxZoom: 19
            }).addTo(map);

            L.marker([<?php echo $row['latitud']; ?>, <?php echo $row['longitud']; ?>]).addTo(map);
        </script>
        <?php } ?>
    </div>
    <script>
    function actualizarEstado(id, nuevoEstado) {
        let select = document.getElementById('select-' + id);
        let formData = new FormData();
        formData.append('id', id);
        formData.append('estado', nuevoEstado);
        
        fetch('procesar_estado.php', { method: 'POST', body: formData })
        .then(response => {
            if(response.ok) {
                select.className = 'select-estado st-' + nuevoEstado.replace(' ', '_');
            }
        });
    }
    </script>
</body>
</html>