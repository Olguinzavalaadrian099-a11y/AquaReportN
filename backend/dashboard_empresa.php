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
    <link rel="stylesheet" href="/frontend/css/index.css">
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
            background-image: url('/frontend/img/fondo3.png'); 
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
            padding: 10px 2rem; 
            position: fixed; 
            width: 100%;
            min-height: 80px;
            height: auto; 
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
            padding: 10px;
            flex-wrap: nowrap;
            gap: 15px;
        }

        .logo { 
            height: clamp(140px, 18vh, 240px);
            width: auto; 
            object-fit: contain; 
        }

        .nav-links { 
            display: flex; 
            gap: 1rem; 
            list-style: none;
            z-index: 1001; 
            white-space: nowrap;
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
            width: 160px;
            margin: 15px auto 0 auto;
            padding: 10px; 
            border-radius: 100px; 
            border: none; 
            background: linear-gradient(-45deg, #63A4FF 0%, #83EAF1 100%); color: #ffffff; 
            font-weight: bold; cursor: pointer; 
            text-transform: uppercase; 
            font-size: 0.7rem;
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

        .container-loader {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loader {
            position: relative;
            width: 200px;
            height: 200px;
            perspective: 800px;
        }

        .crystal {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 60px;
            height: 60px;
            opacity: 0;
            transform-origin: bottom center;
            transform: translate(-50%, -50%) rotateX(45deg) rotateZ(0deg);
            animation: spin 4s linear infinite, emerge 2s ease-in-out infinite alternate,
                fadeIn 0.3s ease-out forwards;
            border-radius: 10px;
            visibility: hidden;
        }

        @keyframes spin {
            from {
                transform: translate(-50%, -50%) rotateX(45deg) rotateZ(0deg);
            }
            to {
                transform: translate(-50%, -50%) rotateX(45deg) rotateZ(360deg);
            }
        }

        @keyframes emerge {
            0%,
            100% {
                transform: translate(-50%, -50%) scale(0.5);
                opacity: 0;
            }
            50% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            to {
                visibility: visible;
                opacity: 0.8;
            }
        }

        .crystal:nth-child(1) {
            background: linear-gradient(45deg, #003366, #336699);
            animation-delay: 0s;
        }

        .crystal:nth-child(2) {
            background: linear-gradient(45deg, #003399, #3366cc);
            animation-delay: 0.3s;
        }

        .crystal:nth-child(3) {
            background: linear-gradient(45deg, #0066cc, #3399ff);
            animation-delay: 0.6s;
        }

        .crystal:nth-child(4) {
            background: linear-gradient(45deg, #0099ff, #66ccff);
            animation-delay: 0.9s;
        }

        .crystal:nth-child(5) {
            background: linear-gradient(45deg, #33ccff, #99ccff);
            animation-delay: 1.2s;
        }

        .crystal:nth-child(6) {
            background: linear-gradient(45deg, #66ffff, #ccffff);
            animation-delay: 1.5s;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(7, 27, 52, 0.6); 
            backdrop-filter: blur(10px);             
            -webkit-backdrop-filter: blur(10px);
            display: none;                           
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loader-box {
            background: rgba(7, 27, 52, 0.9);
            padding: 25px 30px;
            border-radius: 24px;
            border: 1px solid rgba(111, 197, 255, 0.2);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            text-align: center;
            max-width: 250px;
        }

        .loader-text {
            color: #33ccff;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .container-loader .loader {
            margin: 20px 0;
        }

        @media (max-width: 768px) {
            .menu-btn {
                display: block !important; 
                z-index: 1001;
            }

            .nav-links {
                display: none; 
                flex-direction: column;
                position: absolute;
                top: 70px;
                right: 20px;
                background: rgba(10, 25, 45, 0.95);
                padding: 20px;
                border-radius: 15px;
                border: 1px solid rgba(111, 197, 255, 0.3);
                box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            }

            .nav-links.active {
                display: flex !important; 
            }
        }

        .leaflet-control-attribution {
            display: none !important;
        }

        .menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 2rem;
            cursor: pointer;
        }

    </style>
</head>
<body>
    <div class="modal-overlay" id="loader-modal">
        <div class="loader-box">
            <div class="container-loader"> <div class="loader">
                    <div class="crystal"></div>
                    <div class="crystal"></div>
                    <div class="crystal"></div>
                    <div class="crystal"></div>
                    <div class="crystal"></div>
                    <div class="crystal"></div>
                </div>
            </div>
            <p class="loader-text" id="loader-message">Procesando...</p>
        </div>
    </div>
    <div class="page-bg"></div>
    <nav>
        <div class="nav-container">
            <img src="../frontend/img/logo.png" alt="Logo" class="logo">
            <button class="menu-btn" id="menuBtn">☰</button>
            <ul class="nav-links" id="navLinks">
                <li><a href="logout.php" class="btn-logout-loader" >Cerrar Sesión</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h1>Reportes Recibidos</h1>
        <?php foreach($reportes as $row) { ?>
        <div class="report-card">
            <div id="map-<?php echo $row['id_reporte']; ?>" class="map-box"></div>
            <div class="info-data">
                <span class="report-id">Reporte #<?php echo $row['id_reporte']; ?></span>
                <p><strong>Usuario:</strong> <?php echo htmlspecialchars($row['nombre_completo']); ?></p>
                            <p style="font-size: 0.85rem; color: #ffffff; margin-top: -10px;">
                    Lat: <?php echo $row['latitud']; ?>, Lon: <?php echo $row['longitud']; ?>
                </p>
                <div style="font-size: 0.85rem; color: #ccc; margin-top: 8px; line-height: 1.4; border-left: 2px solid #63A4FF; padding-left: 10px;">
                    <p><strong>Ref:</strong> <?php echo htmlspecialchars($row['referencias']); ?></p>
                    <p style="margin-top: 5px; opacity: 0.8;">
                        <strong>Fecha:</strong> <?php echo date("d/m/Y", strtotime($row['fecha_reporte'])); ?>
                    </p>
                </div>
                <select id="select-<?php echo $row['id_reporte']; ?>" class="select-estado" onchange="actualizarEstado(<?php echo $row['id_reporte']; ?>, this.value)">
                    <option value="Pendiente" <?php if($row['estado'] == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
                    <option value="En proceso" <?php if($row['estado'] == 'En proceso') echo 'selected'; ?>>En proceso</option>
                    <option value="Resuelto" <?php if($row['estado'] == 'Resuelto') echo 'selected'; ?>>Resuelto</option>
                </select>
            </div>
        </div>
        <?php } ?>
    </div>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        <?php foreach($reportes as $row) { ?>
            var map = L.map('map-<?php echo $row['id_reporte']; ?>', {zoomControl: false, dragging: false})
                       .setView([<?php echo $row['latitud']; ?>, <?php echo $row['longitud']; ?>], 14);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '' }).addTo(map);
            L.marker([<?php echo $row['latitud']; ?>, <?php echo $row['longitud']; ?>]).addTo(map);
        <?php } ?>
        function actualizarEstado(id, nuevoEstado) {
            document.getElementById('loader-modal').style.display = 'flex';
            
            let formData = new FormData();
            formData.append('id', id);
            formData.append('estado', nuevoEstado);
            
            fetch('procesar_estado.php', { method: 'POST', body: formData })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === "Éxito") {
                    setTimeout(() => {
                        location.reload();
                    }, 2500);
                } else {
                    alert("Error: " + data);
                    document.getElementById('loader-modal').style.display = 'none';
                }
            })
            .catch(() => {
                alert("Error de conexión");
                document.getElementById('loader-modal').style.display = 'none';
            });
        }

        const btnLogout = document.querySelector('.btn-logout-loader');
        const loaderModal = document.getElementById('loader-modal');
        const loaderMessage = document.getElementById('loader-message');

        if (btnLogout) {
            btnLogout.addEventListener('click', function(e) {
                e.preventDefault(); 
                
                loaderModal.style.display = 'flex';
                if (loaderMessage) loaderMessage.innerText = "Cerrando sesión...";

                setTimeout(() => {
                    window.location.href = this.getAttribute('href');
                }, 2000);
            });
        }
        const menuBtn = document.getElementById('menuBtn');
        const navLinks = document.getElementById('navLinks');

        menuBtn.addEventListener("click", () => {
            navLinks.classList.toggle("active");
            menuBtn.textContent = navLinks.classList.contains("active") ? "×" : "☰";
        });
    </script>
</body>
</html>