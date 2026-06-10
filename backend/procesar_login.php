<?php
session_start();
include 'conexion.php';

$usuario = trim($_POST['usuario'] ?? '');
$password = $_POST['password'] ?? '';

try {
    $sql = "SELECT id, password FROM usuarios WHERE nombre = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$usuario]);
    $fila = $stmt->fetch();

    if ($fila && password_verify($password, $fila['password'])) {
        $_SESSION['empresa_id'] = $fila['id'];
        echo "OK"; 
    } else {
        echo "Usuario o contraseña incorrectos.";
    }
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>