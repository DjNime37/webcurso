<?php
// Carga la configuración del entorno desde el archivo env.php.
// __DIR__ obtiene la ruta absoluta del directorio donde está este archivo.
// La configuración devuelta se guarda en la variable $config.
$config = require __DIR__ . '/env.php';
// MODO DEBUG: controla si se muestra un mensaje visual cuando la conexión con la base de datos funciona.
$mostrar_test_conexion = false; // false significa que el mensaje de conexión correcta no se mostrará. Cambiar a true permite visualizar el resultado de la conexión.
try {
    // Construye el DSN (Data Source Name), que contiene los datos necesarios para que PDO pueda conectarse a una base de datos y ejecutar consultas SQL desde tu código
    // Se utiliza el controlador MySQL, el servidor, la base de datos y el conjunto de caracteres definidos en env.php.
    $dsn = "mysql:host=" . $config['database']['host'] .
        // Añade al DSN el nombre de la base de datos que se utilizará.
        ";dbname=" . $config['database']['dbname'] .
        // Añade al DSN el conjunto de caracteres para la comunicación con MySQL, por ejemplo, utf8mb4 si así está configurado.
        ";charset=" . $config['database']['charset'];
    // Crea una nueva conexión PDO con la base de datos.
    // La conexión creada se guarda en la variable $conn.
    $conn = new PDO(
        $dsn, // El primer argumento contiene los datos de conexión (DSN).
        $config['database']['user'], // El segundo argumento indica el nombre de usuario de MySQL.
        $config['database']['password'] // El tercer argumento indica la contraseña de ese usuario.
    );
    // Configura PDO para que genere excepciones cuando se produzcan errores en las operaciones con la base de datos.
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // ->: permite acceder a un método o una propiedad del objeto.
    // PDO::ERRMODE_EXCEPTION establece que PDO lance una excepción cuando se produzca un error en una operación compatible.
    // Identifica la opción que controla cómo PDO gestiona los errores
    // Comprueba si el modo debug está activado.
    if ($mostrar_test_conexion) { // Si la variable vale false, no se ejecuta este bloque.
        // Comprueba que PHP no se esté ejecutando desde la consola.
        // 'cli' identifica la ejecución desde la línea de comandos.
        // El mensaje HTML solo se genera si no se está en ese entorno.
        if (php_sapi_name() !== 'cli') { // php_sapi_name() devuelve el tipo de entorno de ejecución.
            // Muestra un mensaje HTML indicando que la conexión con la base de datos se ha establecido correctamente.
            // Los estilos CSS están escritos directamente en el HTML para no depender de Bootstrap ni de otra hoja de estilos.
            // Se muestra también el nombre de la base de datos utilizada.
            echo '
            <div style="background-color: #d1e7dd; color: #0f5132; padding: 10px; margin: 10px 0; border: 1px solid #badbcc; border-radius: 4px; font-family: sans-serif; font-size: 14px;">
                <strong>✅ BBDD Conectada:</strong> ' . $config['database']['dbname'] . '
            </div>';
        }
    }
} catch (PDOException $e) {
    // Captura los errores de conexión o las excepciones PDO que se produzcan dentro del bloque try.
    // El objeto $e contiene información sobre el error ocurrido.
    // Muestra un mensaje HTML indicando que se ha producido un error al conectar con la base de datos.
    // Los estilos están escritos directamente en el HTML para que el mensaje no dependa de Bootstrap.
    echo '
    <div style="background-color: #f8d7da; color: #842029; padding: 15px; margin: 20px; border: 1px solid #f5c2c7; border-radius: 4px; font-family: sans-serif;">
        <h3 style="margin-top:0">❌ Error Fatal de Conexión</h3>
        <p>No se pudo conectar con la base de datos.</p>
        <hr>
        <small><strong>Detalle técnico:</strong> ' . $e->getMessage() . '</small>
    </div>';
    // Detiene la ejecución del script inmediatamente.
    // Evita que el resto de la aplicación continúe funcionando
    // cuando la conexión con la base de datos ha fallado.
    die();
}
