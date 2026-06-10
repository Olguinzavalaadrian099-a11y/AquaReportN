<?php
include 'conexion.php';

try {
    // Intentamos una inserción simple
    $sql = "INSERT INTO usuarios (nombre, password, rol) VALUES ('test', '123', 'admin')";
    $conexion->exec($sql);
    echo "¡Inserción exitosa! Revisa tu panel Neon ahora.";
} catch (PDOException $e) {
    // Si hay un error, nos lo mostrará en pantalla
    echo "Error crítico: " . $e->getMessage();
}
?>