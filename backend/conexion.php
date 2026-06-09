<?php
$host = "ep-sweet-river-aqtaqe4w-pooler.c-8.us-east-1.aws.neon.tech";
$db   = "neondb"; 
$user = "neondb_owner";
$pass = "npg_ZDzaj3VXy7Oh"; 
$port = "5432";

$dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";

try {
    $conexion = new PDO($dsn, $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>