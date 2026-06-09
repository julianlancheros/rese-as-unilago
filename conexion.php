<?php
// conexion.php - Conexión a PostgreSQL en Render
// La variable DATABASE_URL es inyectada AUTOMÁTICAMENTE por Render

$database_url = getenv('DATABASE_URL');

if (!$database_url) {
    // Fallback para pruebas locales (NO subas credenciales reales a GitHub)
    $database_url = "postgresql://usuario:contraseña@host:5432/nombre_bd";
}

try {
    // Parsear la URL de conexión de PostgreSQL
    // Formato: postgresql://usuario:contraseña@host:5432/nombre_bd
    $db = parse_url($database_url);
    
    $host = $db['host'];
    $port = $db['port'] ?? '5432';
    $dbname = ltrim($db['path'], '/');
    $user = $db['user'];
    $password = $db['pass'] ?? '';
    
    // DSN para PDO_PGSQL [citation:8]
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Para debugging (opcional, quitar en producción)
    // echo "✅ Conexión exitosa a PostgreSQL";
    
} catch (PDOException $e) {
    die("❌ Error de conexión a la base de datos: " . $e->getMessage());
}
?>