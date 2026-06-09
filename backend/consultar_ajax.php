<?php
include 'conexion.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$sql = "SELECT estado FROM reportes WHERE id_reporte = ? AND nombre_completo = ?";
$stmt = $conexion->prepare($sql);
$stmt->execute([$id, $nombre]);
$fila = $stmt->fetch();
if ($fila) {
    echo "<p style='color:white;'><strong>Estado:</strong> <span style='color:#83EAF1; font-weight:bold;'>" . strtoupper($fila['estado']) . "</span></p>";
} else {
    echo "<p style='color:#ff6f6f;'>ID o Nombre incorrectos.</p>";
}
?>