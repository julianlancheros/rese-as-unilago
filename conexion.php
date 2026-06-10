<?php
// conexion.php - Conexión a PostgreSQL en Render
// La variable DATABASE_URL es inyectada AUTOMÁTICAMENTE por Render

$database_url = getenv('DATABASE_URL');

// Si NO está en Render (pruebas locales), usa las credenciales manualmente
if (!$database_url) {
    // Credenciales para pruebas LOCALES (NO subir a GitHub sin cuidar)
    $database_url = "postgresql://db_resenas_unilago_user:68s4D3X5DYhrTXM5MHUpQB5M1iYPWzFq@dpg-d8kbnksvikkc73crpg10-a.oregon-postgres.render.com:5432/db_resenas_unilago";
}

try {
    // Parsear la URL de conexión de PostgreSQL
    $db = parse_url($database_url);
    
    $host = $db['host'];
    $port = $db['port'] ?? '5432';
    $dbname = ltrim($db['path'], '/');
    $user = $db['user'];
    $password = $db['pass'] ?? '';
    
    // DSN para PDO_PGSQL con SSL requerido
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Opcional: descomentar para pruebas
    // echo "✅ Conexión exitosa a PostgreSQL";
    
} catch (PDOException $e) {
    die("❌ Error de conexión a la base de datos: " . $e->getMessage());
}
?>
    die("❌ Error de conexión a la base de datos: " . $e->getMessage());
}
?>
