<?php
// conexion.php - Conexión directa a PostgreSQL en Render

//Credenciales de la base de datos
$host = "dpg-d8kbnksvikkc73crpg10-a.oregon-postgres.render.com";
$port = "5432";
$dbname = "db_resenas_unilago";
$user = "db_resenas_unilago_user";
$password = "68s4D3X5DYhrTXM5MHUpQB5M1iYPWzFq";

try {
    // DSN para PDO_PGSQL (sin SSL para evitar errores)
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Mensaje de éxito (opcional, puedes comentarlo después)
    // echo "✅ Conexión exitosa a PostgreSQL";
    
} catch (PDOException $e) {
    die("❌ Error de conexión a la base de datos: " . $e->getMessage());
}
?>
