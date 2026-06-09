<?php
$host = "tu-host-de-neon.com"; 
$db   = "neondb";             
$user = "tu_usuario";        
$pass = "tu_password";         
$port = "5432";               
$dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";

try {
    $conexion = new PDO($dsn, $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión a Neon: " . $e->getMessage();
}
?>