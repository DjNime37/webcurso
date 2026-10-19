<?php
// Inicia la sesión PHP si todavía no existe una sesión activa.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Muestra los errores de PHP durante el desarrollo para facilitar la detección de problemas.
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Inicializa la variable PDO para evitar avisos de Intelephense
// si la conexión falla antes de asignarle un objeto PDO.
$pdo = null;
// Comprueba que el formulario se haya enviado mediante el método POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Si se accede al archivo sin enviar el formulario, vuelve a la página de creación de cuenta.
    header('Location: crearcuenta.php');
    // Detiene la ejecución para impedir que continúe el registro.
    exit;
}
try {
    // Establece la conexión con la base de datos tienda_ropa mediante PDO.
    $pdo = new PDO(
        'mysql:host=localhost;dbname=tienda_ropa;charset=utf8mb4',
        'root',
        '',
        [
            // Hace que PDO lance excepciones cuando se producen errores en las consultas.
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // Configura las consultas para que devuelvan resultados como arrays asociativos.
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    // Comprueba si existe la tabla usuarios y la crea si todavía no existe.
    // Los datos de dirección se almacenan en la tabla direcciones, no en usuarios.
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            nombre VARCHAR(100) NOT NULL,
            primer_apellido VARCHAR(100) NOT NULL,
            segundo_apellido VARCHAR(100) NOT NULL,
            usuario VARCHAR(50) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            fecha_nacimiento DATE NOT NULL,
            prefijo VARCHAR(10) NOT NULL,
            telefono VARCHAR(20) NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NULL,
            dni_hash CHAR(64) NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    // Comprueba si existe la tabla direcciones y la crea si no existe.
    // Esta tabla almacena las direcciones asociadas a cada usuario.
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS direcciones (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            usuario_id INT UNSIGNED NOT NULL,
            codigo_postal VARCHAR(10) NOT NULL,
            nombre_via VARCHAR(150) NOT NULL,
            numero VARCHAR(20) NOT NULL,
            piso VARCHAR(20) NULL,
            puerta VARCHAR(20) NULL,
            pais VARCHAR(100) NOT NULL,
            tipo VARCHAR(20) NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    // Comprueba si existe la tabla direcciones_historial y la crea si no existe.
    // Esta tabla conserva los datos históricos de las direcciones.
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS direcciones_historial (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            direccion_id INT UNSIGNED NOT NULL,
            usuario_id INT UNSIGNED NOT NULL,
            codigo_postal VARCHAR(10) NOT NULL,
            nombre_via VARCHAR(150) NOT NULL,
            numero VARCHAR(20) NOT NULL,
            piso VARCHAR(20) NULL,
            puerta VARCHAR(20) NULL,
            pais VARCHAR(100) NOT NULL,
            tipo VARCHAR(20) NOT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    // Inicia una transacción para que las operaciones de creación del usuario,
    // su dirección y el historial se confirmen juntas o se cancelen si hay un error.
    $pdo->beginTransaction();
    // Recoge el nombre enviado desde el formulario y elimina los espacios exteriores.
    $nombre = trim($_POST['nombre'] ?? '');
    // Recoge el primer apellido y elimina los espacios exteriores.
    $primer_apellido = trim($_POST['primer_apellido'] ?? '');
    // Recoge el segundo apellido y elimina los espacios exteriores.
    $segundo_apellido = trim($_POST['segundo_apellido'] ?? '');
    // Recoge el nombre de usuario y elimina los espacios exteriores.
    $usuario = trim($_POST['usuario'] ?? '');
    // Recoge el correo electrónico y elimina los espacios exteriores.
    $email = trim($_POST['email'] ?? '');
    // Recoge la contraseña sin modificarla mediante trim().
    $password = $_POST['password'] ?? '';
    // Recoge la confirmación de la contraseña sin modificarla mediante trim().
    $password_confirm = $_POST['password_confirm'] ?? '';
    // Recoge la fecha de nacimiento.
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    // Recoge el prefijo telefónico seleccionado.
    $prefijo = $_POST['prefijo'] ?? '';
    // Recoge el número de teléfono y elimina los espacios exteriores.
    $telefono = trim($_POST['telefono'] ?? '');
    // Recoge el código postal, que se guardará en la tabla direcciones.
    $codigo_postal = trim($_POST['codigo_postal'] ?? '');
    // Recoge el nombre de la calle, avenida u otra vía de la dirección.
    $nombre_via = trim($_POST['nombre_via'] ?? '');
    // Recoge el número del inmueble.
    $numero = trim($_POST['numero'] ?? '');
    // Recoge el piso; puede estar vacío si no se ha indicado.
    $piso = trim($_POST['piso'] ?? '');
    // Recoge la puerta; puede estar vacía si no se ha indicado.
    $puerta = trim($_POST['puerta'] ?? '');
    // Recoge el país seleccionado en el formulario.
    $pais = $_POST['pais'] ?? '';
    // Recoge el tipo de dirección seleccionado.
    $tipo = $_POST['tipo'] ?? '';
    // Recoge el número del DNI y elimina los espacios exteriores.
    $dni_numero = trim($_POST['dni_numero'] ?? '');
    // Recoge la letra del DNI y elimina los espacios exteriores.
    $dni_letra = trim($_POST['dni_letra'] ?? '');
    // Comprueba que todos los campos obligatorios tengan contenido.
    if (
        $nombre === '' ||
        $primer_apellido === '' ||
        $segundo_apellido === '' ||
        $usuario === '' ||
        $email === '' ||
        $password === '' ||
        $password_confirm === '' ||
        $fecha_nacimiento === '' ||
        $prefijo === '' ||
        $telefono === '' ||
        $codigo_postal === '' ||
        $nombre_via === '' ||
        $numero === '' ||
        $pais === '' ||
        $tipo === '' ||
        $dni_numero === '' ||
        $dni_letra === ''
    ) {
        // Cancela la transacción porque no se deben guardar datos incompletos.
        $pdo->rollBack();
        // Muestra un mensaje indicando que deben completarse los campos obligatorios.
        die('Todos los campos obligatorios deben estar completos.');
    }
    // Comprueba que el tipo de dirección sea uno de los dos valores permitidos.
    if ($tipo !== 'entrega' && $tipo !== 'facturación') {
        // Cancela la transacción si el tipo de dirección no es válido.
        $pdo->rollBack();
        // Muestra el mensaje de error correspondiente.
        die('El tipo de dirección no es válido.');
    }
    // Comprueba que la contraseña y su confirmación sean idénticas.
    if ($password !== $password_confirm) {
        // Cancela la transacción si las contraseñas no coinciden.
        $pdo->rollBack();
        // Informa de que las contraseñas introducidas son diferentes.
        die('Las contraseñas no coinciden.');
    }
    // Comprueba que el número del DNI contenga exactamente ocho cifras.
    if (!preg_match('/^[0-9]{8}$/', $dni_numero)) {
        // Cancela la transacción si el formato del número del DNI es incorrecto.
        $pdo->rollBack();
        // Informa del formato esperado para el número del DNI.
        die('El número del DNI debe contener exactamente 8 números.');
    }
    // Comprueba que la letra del DNI contenga exactamente un carácter alfabético.
    if (!preg_match('/^[A-Za-z]$/', $dni_letra)) {
        // Cancela la transacción si la letra no cumple el formato esperado.
        $pdo->rollBack();
        // Informa de que la letra del DNI no es válida.
        die('La letra del DNI no es válida.');
    }
    // Une el número y la letra para formar el DNI completo.
    $dni = $dni_numero . $dni_letra;
    // Genera un hash SHA-256 del DNI para comparar su valor sin guardar el DNI completo.
    $dni_hash = hash('sha256', $dni);
    // Busca usuarios que coincidan con alguno de los datos considerados duplicados.
    // Los datos de dirección se comprueban en la tabla direcciones,
    // no en la tabla usuarios.
    $stmtRegistro = $pdo->prepare("
        SELECT
            u.id,
            u.usuario,
            u.dni_hash,
            u.nombre,
            u.primer_apellido,
            u.segundo_apellido,
            u.email,
            u.telefono
        FROM usuarios u
        WHERE
            u.usuario = :usuario
            OR u.dni_hash = :dni_hash
            OR (
                u.nombre = :nombre
                AND u.primer_apellido = :primer_apellido
                AND u.segundo_apellido = :segundo_apellido
            )
            OR u.email = :email
            OR u.telefono = :telefono
    ");
    // Ejecuta la consulta sustituyendo los parámetros por los datos del formulario.
    $stmtRegistro->execute([
        ':usuario' => $usuario,
        ':dni_hash' => $dni_hash,
        ':nombre' => $nombre,
        ':primer_apellido' => $primer_apellido,
        ':segundo_apellido' => $segundo_apellido,
        ':email' => $email,
        ':telefono' => $telefono
    ]);
    // Recupera todos los registros de usuarios que coinciden con los datos comprobados.
    $registrosCoincidentes = $stmtRegistro->fetchAll();
    // Inicializa un array donde se guardarán los nombres de los campos duplicados.
    $camposDuplicados = [];
    // Recorre todos los usuarios encontrados para identificar qué datos coinciden.
    foreach ($registrosCoincidentes as $registro) {
        // Comprueba si el nombre de usuario ya pertenece a otro registro.
        if ($registro['usuario'] === $usuario) {
            $camposDuplicados[] = 'Nombre de usuario';
        }
        // Comprueba si el hash del DNI ya está registrado.
        if ($registro['dni_hash'] === $dni_hash) {
            $camposDuplicados[] = 'DNI';
        }
        // Comprueba si coinciden el nombre, el primer apellido y el segundo apellido.
        if (
            $registro['nombre'] === $nombre &&
            $registro['primer_apellido'] === $primer_apellido &&
            $registro['segundo_apellido'] === $segundo_apellido
        ) {
            $camposDuplicados[] = 'Nombre y apellidos';
        }
        // Comprueba si el correo electrónico ya está registrado.
        if ($registro['email'] === $email) {
            $camposDuplicados[] = 'Correo electrónico';
        }
        // Comprueba si el teléfono ya está registrado.
        if ($registro['telefono'] === $telefono) {
            $camposDuplicados[] = 'Teléfono';
        }
    }
    // Comprueba si ya existe una dirección que coincida en vía, número y piso.
    $stmtDireccionDuplicada = $pdo->prepare("
        SELECT
            id
        FROM direcciones
        WHERE
            nombre_via = :nombre_via
            AND numero = :numero
            AND (
                piso = :piso
                OR (piso IS NULL AND :piso2 IS NULL)
                OR (piso = '' AND :piso3 = '')
            )
        LIMIT 1
    ");
    // Ejecuta la comprobación utilizando los datos de la dirección introducida.
    $stmtDireccionDuplicada->execute([
        ':nombre_via' => $nombre_via,
        ':numero' => $numero,
        ':piso' => $piso,
        ':piso2' => $piso === '' ? null : $piso,
        ':piso3' => $piso
    ]);
    // Comprueba si la consulta ha encontrado una dirección coincidente.
    if ($stmtDireccionDuplicada->fetch()) {
        // Añade la dirección a la lista de datos duplicados.
        $camposDuplicados[] = 'Dirección (vía, número y piso)';
    }
    // Elimina los nombres repetidos y reorganiza los índices del array.
    $camposDuplicados = array_values(array_unique($camposDuplicados));
    // Comprueba si se ha encontrado al menos un dato duplicado.
    if (!empty($camposDuplicados)) {
        // Cancela la transacción para impedir que se cree el usuario.
        $pdo->rollBack();
        // Guarda en la sesión una marca que indica que existen datos duplicados.
        $_SESSION['registro_duplicado'] = true;
        // Guarda en la sesión los nombres de los campos que ya están registrados.
        $_SESSION['campos_duplicados'] = $camposDuplicados;
        // Redirige al formulario de creación de cuenta para mostrar el aviso correspondiente.
        header('Location: crearcuenta.php');
        // Detiene la ejecución después de la redirección.
        exit;
    }
    // Genera un hash seguro de la contraseña para no almacenarla en texto plano.
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    // Obtiene la fecha y hora actuales para registrar la creación de los datos.
    $created_at = date('Y-m-d H:i:s');
    // Prepara la consulta para insertar el nuevo usuario.
    // Los datos de dirección no se guardan en esta tabla.
    $stmtInsertar = $pdo->prepare("
        INSERT INTO usuarios (
            nombre,
            primer_apellido,
            segundo_apellido,
            usuario,
            email,
            password,
            fecha_nacimiento,
            prefijo,
            telefono,
            created_at,
            dni_hash
        ) VALUES (
            :nombre,
            :primer_apellido,
            :segundo_apellido,
            :usuario,
            :email,
            :password,
            :fecha_nacimiento,
            :prefijo,
            :telefono,
            :created_at,
            :dni_hash
        )
    ");
    // Ejecuta la inserción del usuario con los datos recogidos y validados.
    $stmtInsertar->execute([
        ':nombre' => $nombre,
        ':primer_apellido' => $primer_apellido,
        ':segundo_apellido' => $segundo_apellido,
        ':usuario' => $usuario,
        ':email' => $email,
        ':password' => $passwordHash,
        ':fecha_nacimiento' => $fecha_nacimiento,
        ':prefijo' => $prefijo,
        ':telefono' => $telefono,
        ':created_at' => $created_at,
        ':dni_hash' => $dni_hash
    ]);
    // Obtiene el identificador generado automáticamente para el nuevo usuario.
    $usuarioId = (int) $pdo->lastInsertId();
    // Prepara la consulta para insertar la dirección asociada al usuario recién creado.
    $stmtDireccion = $pdo->prepare("
        INSERT INTO direcciones (
            usuario_id,
            codigo_postal,
            nombre_via,
            numero,
            piso,
            puerta,
            pais,
            tipo,
            created_at,
            updated_at
        ) VALUES (
            :usuario_id,
            :codigo_postal,
            :nombre_via,
            :numero,
            :piso,
            :puerta,
            :pais,
            :tipo,
            :created_at,
            :updated_at
        )
    ");
    // Ejecuta la inserción de la dirección.
    // Si el piso o la puerta están vacíos, guarda NULL en esos campos.
    $stmtDireccion->execute([
        ':usuario_id' => $usuarioId,
        ':codigo_postal' => $codigo_postal,
        ':nombre_via' => $nombre_via,
        ':numero' => $numero,
        ':piso' => $piso === '' ? null : $piso,
        ':puerta' => $puerta === '' ? null : $puerta,
        ':pais' => $pais,
        ':tipo' => $tipo,
        ':created_at' => $created_at,
        ':updated_at' => $created_at
    ]);
    // Obtiene el identificador generado para la dirección recién insertada.
    $direccionId = (int) $pdo->lastInsertId();
    // Prepara la consulta para guardar una copia inicial de la dirección en el historial.
    $stmtHistorial = $pdo->prepare("
        INSERT INTO direcciones_historial (
            direccion_id,
            usuario_id,
            codigo_postal,
            nombre_via,
            numero,
            piso,
            puerta,
            pais,
            tipo,
            created_at
        ) VALUES (
            :direccion_id,
            :usuario_id,
            :codigo_postal,
            :nombre_via,
            :numero,
            :piso,
            :puerta,
            :pais,
            :tipo,
            :created_at
        )
    ");
    // Inserta en el historial los datos iniciales de la dirección.
    $stmtHistorial->execute([
        ':direccion_id' => $direccionId,
        ':usuario_id' => $usuarioId,
        ':codigo_postal' => $codigo_postal,
        ':nombre_via' => $nombre_via,
        ':numero' => $numero,
        ':piso' => $piso === '' ? null : $piso,
        ':puerta' => $puerta === '' ? null : $puerta,
        ':pais' => $pais,
        ':tipo' => $tipo,
        ':created_at' => $created_at
    ]);
    // Confirma la transacción y guarda definitivamente las inserciones realizadas.
    $pdo->commit();
    // La cuenta se ha creado correctamente.
    // A continuación se genera la página que muestra el banner de confirmación.
?>
    <!DOCTYPE html>
    <!-- Indica que el documento utiliza HTML5 y que su idioma es el español. -->
    <html lang="es">
    <head>
        <!-- Define la codificación de caracteres del documento. -->
        <meta charset="UTF-8">
        <!-- Adapta la página al ancho de la pantalla del dispositivo. -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Define el título de la página de confirmación. -->
        <title>Cuenta creada</title>
        <style>
            /* Define la apariencia del banner de confirmación de la cuenta. */
            #banner-registro-exitoso {
                /* Mantiene el banner fijo respecto a la ventana del navegador. */
                position: fixed;
                /* Sitúa el banner a 20 píxeles del borde superior. */
                top: 20px;
                /* Coloca el borde izquierdo del banner en el centro horizontal. */
                left: 50%;
                /* Desplaza el banner para centrarlo horizontalmente. */
                transform: translateX(-50%);
                /* Sitúa el banner por encima de los demás elementos de la página. */
                z-index: 9999;
                /* Define el espacio interior del banner. */
                padding: 15px 25px;
                /* Establece una anchura mínima para que el mensaje sea legible. */
                min-width: 280px;
                /* Centra el texto del mensaje. */
                text-align: center;
                /* Define el fondo verde claro del mensaje de éxito. */
                background-color: #d1e7dd;
                /* Define el color oscuro del texto. */
                color: #0f5132;
                /* Añade un borde verde claro alrededor del banner. */
                border: 1px solid #badbcc;
                /* Redondea las esquinas del banner. */
                border-radius: 8px;
                /* Añade una sombra para distinguir el banner del fondo. */
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
                /* Establece el tamaño del texto. */
                font-size: 16px;
                /* Muestra el mensaje en negrita. */
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <!-- Muestra el mensaje de éxito después de crear la cuenta. -->
        <div id="banner-registro-exitoso">
            Cuenta creada correctamente: redirigiendo a la página principal...
        </div>
        <!-- Pie de página con la presentación de la tienda y sus datos de contacto. -->
        <footer class="bg-dark text-light py-5 mt-5">
            <!-- Contenedor principal que agrupa las columnas del pie de página. -->
            <div class="container">
                <!-- Centra las tres columnas y establece el espacio entre ellas. -->
                <div class="row g-4 justify-content-center text-center">
                    <!-- COLUMNA 1: PRESENTACIÓN DE LA TIENDA. -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <!-- Muestra el nombre de la tienda. -->
                        <h5 class="text-uppercase text-primary mb-3">
                            <strong>Mi Tienda Streetwear</strong>
                        </h5>
                        <!-- Presenta una breve descripción comercial de la tienda. -->
                        <p class="text-secondary small">
                            Tu tienda de confianza para encontrar las mejores
                            prendas urbanas, camisetas oversize y zapatillas
                            exclusivas. Elevando tu estilo diario desde 2026.
                        </p>
                    </div>
                    <!-- COLUMNA 2: ENLACES DE INTERÉS. -->
                    <div class="col-6 col-md-6 col-lg-3">
                        <!-- Título de la lista de enlaces. -->
                        <h5 class="text-uppercase mb-3">
                            <strong>Enlaces de interés</strong>
                        </h5>
                        <!-- Lista de enlaces de navegación. -->
                        <ul class="list-unstyled">
                            <!-- Enlace a la página principal de la tienda. -->
                            <li class="mb-2">
                                <a href="/webcurso/" class="text-secondary text-decoration-none small text-hover-light">Inicio</a>
                            </li>
                            <!-- Enlace a la página de productos. -->
                            <li class="mb-2">
                                <a href="index.php" class="text-secondary text-decoration-none small text-hover-light">Productos</a>
                            </li>
                            <!-- Enlace reservado para las preguntas frecuentes. -->
                            <li class="mb-2">
                                <a href="#" class="text-secondary text-decoration-none small text-hover-light">Preguntas
                                    frecuentes</a>
                            </li>
                            <!-- Enlace a la página de contacto. -->
                            <li class="mb-2">
                                <a href="contacto.php"
                                    class="text-secondary text-decoration-none small text-hover-light">Contacto</a>
                            </li>
                        </ul>
                    </div>
                    <!-- COLUMNA 3: INFORMACIÓN DE CONTACTO. -->
                    <div class="col-6 col-md-6 col-lg-3">
                        <!-- Título de la sección que enlaza también con la página de contacto. -->
                        <h5 class="text-uppercase mb-3">
                            <strong>
                                <a href="contacto.php" id="contacto" class="text-decoration-none text-reset">Contacto</a>
                            </strong>
                        </h5>
                        <!-- Lista con la dirección, el teléfono y el correo electrónico. -->
                        <ul class="list-unstyled text-secondary small">
                            <!-- Dirección física de la tienda. -->
                            <li class="mb-2">📍 Calle Mayor, n.º 12, Huesca</li>
                            <!-- Teléfono de contacto. -->
                            <li class="mb-2">📞 +34 900 123 456</li>
                            <!-- Correo electrónico de soporte. -->
                            <li class="mb-2">✉️ soporte@mitienda.com</li>
                        </ul>
                    </div>
                <!-- Cierra la fila que contiene las tres columnas. -->
                </div>
                <!-- Línea horizontal que separa el pie de página del aviso legal. -->
                <hr class="border-secondary my-4">
                <!-- COPYRIGHT: información sobre los derechos reservados. -->
                <div class="row">
                    <!-- Columna que centra el texto de copyright. -->
                    <div class="col text-center">
                        <!-- Muestra el año y el nombre comercial de la tienda. -->
                        <p class="text-secondary small mb-0">
                            &copy; 2026 Mi Tienda Streetwear. Todos los derechos reservados.
                        </p>
                    </div>
                </div>
            <!-- Cierra el contenedor principal del pie de página. -->
            </div>
        <!-- Cierra el pie de página. -->
        </footer>
        <!-- Script que redirige a la página principal después de mostrar el banner. -->
        <script>
            // Programa la redirección para que se ejecute después de 2500 milisegundos.
            setTimeout(function() {
                // Navega a index.php cuando transcurre el tiempo establecido.
                window.location.href = 'index.php';
            }, 2500); // Deja 2,5 segundos para que el usuario pueda leer el mensaje.
        </script>
    </body>
    </html>
<?php
} catch (PDOException $e) {
    // Comprueba que PDO se haya inicializado y que exista una transacción sin confirmar.
    if ($pdo instanceof PDO && $pdo->inTransaction()) {
        // Cancela la transacción para evitar que queden operaciones parcialmente confirmadas.
        $pdo->rollBack();
    }
    // Muestra el error de base de datos durante el desarrollo.
    die('Error de base de datos: ' . $e->getMessage());
}