<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nuevo_estado = $_POST['estado'];

    try {
        $sql = "UPDATE reportes SET estado = ? WHERE id_reporte = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$nuevo_estado, $id]);
        
        echo "Éxito";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>