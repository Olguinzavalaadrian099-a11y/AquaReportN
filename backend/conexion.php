<?php
$database_url = "postgresql://neondb_owner:npg_ZDzaj3VXy7Oh@ep-sweet-river-aqtaqe4w-pooler.c-8.us-east-1.aws.neon.tech/neondb?sslmode=require&channel_binding=require";

try {
    $url = parse_url($database_url);
    $host = $url['host'];
    $db   = ltrim($url['path'], '/');
    $user = $url['user'];
    $pass = $url['pass'];
    $port = $url['port'];
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";
    $conexion = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

} catch (PDOException $e) {
    die("Error de conexión a Neon: " . $e->getMessage());
}
?>