<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir y sanitizar datos
    $nombre_producto = trim($_POST['nombre_producto'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $calificacion = intval($_POST['calificacion'] ?? 0);
    $reseña = trim($_POST['reseña'] ?? '');
    
    // Validaciones
    $errores = [];
    if (empty($nombre_producto)) $errores[] = "El nombre del producto es obligatorio";
    if (empty($reseña)) $errores[] = "La reseña no puede estar vacía";
    if ($calificacion < 1 || $calificacion > 5) $errores[] = "Calificación inválida (1-5)";
    
    if (count($errores) > 0) {
        echo "<h2>❌ Errores encontrados:</h2><ul>";
        foreach ($errores as $error) echo "<li>$error</li>";
        echo "</ul><a href='index.html'>← Volver al formulario</a>";
        exit;
    }
    
    // Insertar en PostgreSQL
    $sql = "INSERT INTO resenas (nombre_producto, categoria, calificacion, reseña, fecha_analisis) 
            VALUES (:nombre, :categoria, :calificacion, :reseña, CURRENT_DATE)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre_producto,
        ':categoria' => $categoria,
        ':calificacion' => $calificacion,
        ':reseña' => $reseña
    ]);
    
    echo "<h2>✅ ¡Reseña guardada exitosamente!</h2>";
    echo "<p>Gracias por compartir tu opinión sobre <strong>" . htmlspecialchars($nombre_producto) . "</strong>.</p>";
    echo "<a href='index.html'>➕ Registrar otra reseña</a><br>";
    echo "<a href='consultar_resenas.php'>📖 Ver todas las reseñas</a>";
} else {
    header('Location: index.html');
    exit;
}
?>