<?php
// backup_mongo.php - Respaldar datos de PostgreSQL a MongoDB Atlas

require_once 'conexion.php';

// Configuración de MongoDB Atlas (usar variable de entorno en Render)
$mongo_uri = getenv('MONGODB_URI');

if (!$mongo_uri) {
    die("❌ Error: Variable MONGODB_URI no configurada en Render\n");
}

echo "🔄 Iniciando backup de PostgreSQL a MongoDB Atlas...\n\n";

// 1. Obtener todas las reseñas de PostgreSQL
$sql = "SELECT * FROM resenas ORDER BY id";
$stmt = $pdo->query($sql);
$resenas = $stmt->fetchAll();

if (count($resenas) === 0) {
    echo "📭 No hay datos para respaldar.\n";
    exit;
}

echo "📊 Se encontraron " . count($resenas) . " reseñas en PostgreSQL.\n";

try {
    // 2. Conectar a MongoDB Atlas
    require_once 'vendor/autoload.php'; // Si usas Composer
    // Si no usas Composer, necesitas instalar la extensión mongodb y usar:
    // $mongoClient = new MongoDB\Client($mongo_uri);
    
    $mongoClient = new MongoDB\Client($mongo_uri);
    $database = $mongoClient->selectDatabase('unilago_backup');
    $collection = $database->selectCollection('resenas_backup');
    
    // 3. Transformar datos para MongoDB (formato documento)
    $documentos = [];
    foreach ($resenas as $resena) {
        $documentos[] = [
            'postgres_id' => $resena['id'],
            'nombre_producto' => $resena['nombre_producto'],
            'categoria' => $resena['categoria'],
            'calificacion' => $resena['calificacion'],
            'reseña' => $resena['reseña'],
            'fecha_analisis' => $resena['fecha_analisis'],
            'backup_date' => new MongoDB\BSON\UTCDateTime(time() * 1000)
        ];
    }
    
    // 4. Borrar backup anterior y guardar nuevo
    $deleteResult = $collection->deleteMany([]);
    echo "🗑️  Backup anterior eliminado: " . $deleteResult->getDeletedCount() . " documentos.\n";
    
    $insertResult = $collection->insertMany($documentos);
    
    echo "\n✅ Backup completado exitosamente\n";
    echo "📊 Documentos respaldados: " . $insertResult->getInsertedCount() . "\n";
    echo "🕒 Fecha del backup: " . date('Y-m-d H:i:s') . "\n";
    
} catch (Exception $e) {
    echo "❌ Error en backup a MongoDB: " . $e->getMessage() . "\n";
}
?>