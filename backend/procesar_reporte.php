<?php
include __DIR__ . '/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha_reporte'];
    if (empty($fecha) || !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $fecha)) {
        die("Error: Formato de fecha inválido.");
    }
    $nombre = trim($_POST['nombre_completo']);
    $edad = intval($_POST['edad']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $referencias = trim($_POST['referencias']);
    $lat = floatval($_POST['latitud']);
    $lng = floatval($_POST['longitud']); 
    $ruta_carpeta = '/var/www/html/uploads/'; 
    $ruta_foto = $ruta_carpeta . time() . '_' . basename($_FILES['foto_reporte']['name']);
    $ruta_pdf = $ruta_carpeta . time() . '_' . basename($_FILES['archivo_predial']['name']);

    if (move_uploaded_file($_FILES['foto_reporte']['tmp_name'], $ruta_foto) && 
        move_uploaded_file($_FILES['archivo_predial']['tmp_name'], $ruta_pdf)) {
        try {
            $sql = "INSERT INTO reportes (nombre_completo, edad, fecha_reporte, telefono, email, referencias, latitud, longitud, foto_ruta, pdf_ruta) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$nombre, $edad, $fecha, $telefono, $email, $referencias, $lat, $lng, $ruta_foto, $ruta_pdf]);
            $nuevo_id = $conexion->lastInsertId();
            
            echo $nuevo_id; 
            exit();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
        }
    } else {
        echo "Error al subir archivos. Verifica la carpeta 'uploads'.";
    }
}
?>