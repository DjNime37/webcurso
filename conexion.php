<?php

$config = require __DIR__ . '/env.php';

// --- MODO DEBUG (Cambiar a false una vez testeado) ---
$mostrar_test_conexion = false;
// --------------------------------------------------

try {
// 2. Construcción del DSN (Data Source Name)
$dsn = "mysql:host=" . $config['database']['host'] .
";dbname=" . $config['database']['dbname'] .
";charset=" . $config['database']['charset'];
 
// 3. Creación de la instancia PDO
$conn = new PDO(
$dsn,
$config['database']['user'],
$config['database']['password']
);
 
// Configuración de errores (Lanza excepciones en vez de fallar en silencio)
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 4. TEST VISUAL (Solo si el modo debug está activo)
if ($mostrar_test_conexion) {
// Usamos estilos en línea para no depender de que cargue el CSS de Bootstrap
if (php_sapi_name() !== 'cli') {
echo '
<div style="background-color: #d1e7dd; color: #0f5132; padding: 10px; margin: 10px 0; border: 1px solid #badbcc; border-radius: 4px; font-family: sans-serif; font-size: 14px;">
<strong>✅ BBDD Conectada:</strong> ' . $config['database']['dbname'] . '
</div>';
}
}
 
} catch(PDOException $e) {
// Error fatal visual
echo '
<div style="background-color: #f8d7da; color: #842029; padding: 15px; margin: 20px; border: 1px solid #f5c2c7; border-radius: 4px; font-family: sans-serif;">
<h3 style="margin-top:0">❌ Error Fatal de Conexión</h3>
<p>No se pudo conectar con la base de datos.</p>
<hr>
<small><strong>Detalle técnico:</strong> ' . $e->getMessage() . '</small>
</div>';
 
// Matamos el script. No tiene sentido seguir cargando la web sin datos.
die();
}
?>

