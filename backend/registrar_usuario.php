<?php
include 'conexion.php';

$nombre_usuario = 'adrian_olguin'; 
$password_plano = 'admin123'; 
$rol = 'admin';

$password_hash = password_hash($password_plano, PASSWORD_DEFAULT);

try {
    $sql = "INSERT INTO usuarios (nombre, password, rol) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$nombre_usuario, $password_hash, $rol]);
    echo "¡Usuario '$nombre_usuario' creado correctamente!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>