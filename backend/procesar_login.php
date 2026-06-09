<?php
session_start();
include 'conexion.php';
$usuario = trim($_POST['usuario']);
$password = $_POST['password'];

try {
    $sql = "SELECT id, password FROM usuarios WHERE nombre = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$usuario]);
    $fila = $stmt->fetch();

    if ($fila) {
        if (password_verify($password, $fila['password'])) {
            $_SESSION['empresa_id'] = $fila['id'];
            header("Location: dashboard_empresa.php"); 
            exit(); 
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
} catch (PDOException $e) {
    echo "Error de base de datos: " . $e->getMessage();
}
?>