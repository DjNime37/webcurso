<?php
// Inicia o recupera la sesión PHP para poder guardar y consultar datos del usuario.
session_start();
// Comprueba si el formulario se ha enviado mediante el método POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoge el nombre de usuario enviado desde el formulario.
    $usuario = $_POST['usuario'];
    // Recoge la contraseña enviada desde el formulario.
    $password = $_POST['password'];
    // Crea un array vacío para almacenar los errores de validación.
    $errors = [];
    // Comprueba que el nombre de usuario tenga entre 4 y 20 caracteres
    // y que solo contenga letras permitidas, números o guiones bajos.
    if (
        strlen($usuario) < 4 ||
        strlen($usuario) > 20 ||
        !preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÜüÑñ0-9_]+$/', $usuario)
    ) {
        // Guarda un mensaje de error asociado al campo del nombre de usuario.
        $errors['usuario'] = 'El nombre de usuario debe tener entre 4 y 20 caracteres y solo puede contener letras, números, guiones bajos y caracteres especiales (áéíóúüñÁÉÍÓÚÜÑ).';
    }
    // Comprueba que la contraseña tenga al menos 8 caracteres,
    // incluyendo al menos una letra y un número.
    if (
        strlen($password) < 8 ||
        !preg_match('/^(?=.*[A-Za-z])(?=.*\d).+$/', $password)
    ) {
        // Guarda un mensaje de error asociado al campo de la contraseña.
        $errors['password'] = 'La contraseña debe tener al menos 8 caracteres, incluyendo al menos una letra y un número.';
    }
    // Comprueba si se ha producido algún error de validación.
    if (!empty($errors)) {
        // Guarda los errores en la sesión para mostrarlos después de la redirección.
        $_SESSION['errors'] = $errors;
        // Devuelve al usuario al formulario de inicio de sesión.
        header('Location: iniciosesión.php');
        // Detiene la ejecución del script después de redirigir.
        exit;
    }
    // Inicia un bloque para gestionar posibles errores de conexión o consultas SQL.
    try {
        // Crea una conexión PDO con la base de datos tienda_ropa.
        // Utiliza MySQL en localhost, codificación utf8mb4,
        // el usuario root y una contraseña vacía.
        $pdo = new PDO(
            'mysql:host=localhost;dbname=tienda_ropa;charset=utf8mb4',
            'root',
            ''
        );
        // Configura PDO para que lance excepciones cuando se produzca un error SQL.
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Prepara una consulta para buscar el usuario en la tabla usuarios.
        // El signo ? representa un parámetro que se proporcionará posteriormente.
        $sql = "SELECT id, usuario, password
                FROM usuarios
                WHERE usuario = ?";
        // Prepara la consulta SQL para ejecutarla de forma parametrizada.
        $stmt = $pdo->prepare($sql);
        // Ejecuta la consulta sustituyendo el signo ? por el nombre de usuario.
        $stmt->execute([$usuario]);
        // Recupera la primera fila encontrada como un array asociativo.
        // Si no existe el usuario, devuelve false.
        $usuarioBD = $stmt->fetch(PDO::FETCH_ASSOC);
        // Comprueba que el usuario exista y que la contraseña introducida
        // coincida con el hash almacenado en la base de datos.
        if ($usuarioBD && password_verify($password, $usuarioBD['password'])) {
            // Indica en la sesión que el usuario se ha autenticado correctamente.
            $_SESSION['authenticated'] = true;
            // Guarda el nombre del usuario autenticado en la sesión.
            $_SESSION['username'] = $usuarioBD['usuario'];
            // Guarda el identificador del usuario registrado en la sesión.
            $_SESSION['usuario_id'] = $usuarioBD['id'];
            // Guarda el tipo de usuario para identificarlo como usuario registrado.
            $_SESSION['tipo'] = 'registrado';
            // Guarda el nombre de usuario para mostrarlo posteriormente en la cabecera.
            $_SESSION['usuario'] = $usuarioBD['usuario'];
            // Redirige al usuario a su página principal después del inicio de sesión.
            header('Location: usuarioregistrado.php');
            // Detiene la ejecución del script después de redirigir.
            exit;
        } else {
            // Guarda un error si el usuario no existe o la contraseña no coincide.
            $_SESSION['errors']['auth'] = 'O el nombre de usuario o contraseña no coincide';
            // Redirige al usuario al formulario de inicio de sesión.
            header('Location: iniciosesión.php');
            // Detiene la ejecución del script después de redirigir.
            exit;
        }
    } catch (PDOException $e) {
        // Guarda un mensaje genérico si se produce un error relacionado con PDO.
        // No muestra los detalles internos de la base de datos al usuario.
        $_SESSION['errors']['auth'] = 'Error al conectar con la base de datos.';
        // Redirige al usuario al formulario de inicio de sesión.
        header('Location: iniciosesión.php');
        // Detiene la ejecución del script después de redirigir.
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Define la codificación de caracteres para mostrar correctamente tildes y ñ. -->
    <meta charset="UTF-8">
    <!-- Adapta el ancho de la página al dispositivo desde el que se visita. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Establece el título que aparece en la pestaña del navegador. -->
    <title>Registro de usuario</title>
    <!-- Carga la hoja de estilos propia del formulario de inicio de sesión. -->
    <link rel="stylesheet" href="estilos/botóniniciosesión.css">
    <!-- Carga Bootstrap 5.3.3 para utilizar sus estilos y componentes. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Incluye la cabecera para los usuarios que todavía no han iniciado sesión. -->
    <?php include 'headerhome.php'; ?>
    <!-- Muestra el título principal de la página. -->
    <h1>Iniciar sesión</h1>
    <!-- Comprueba si existen errores guardados en la sesión. -->
    <?php if (isset($_SESSION['errors'])): ?>
        <!-- Contenedor que agrupa los mensajes de error. -->
        <div class="errores">
            <!-- Recorre todos los errores almacenados en la sesión. -->
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <!-- Muestra cada error escapando los caracteres HTML para evitar su interpretación como código. -->
                <p><?php echo htmlspecialchars($error); ?></p>
                <!-- Finaliza el recorrido de los errores. -->
            <?php endforeach; ?>
        </div>
        <!-- Elimina los errores de la sesión para que no vuelvan a mostrarse al visitar otra vez la página. -->
        <?php unset($_SESSION['errors']); ?>
        <!-- Finaliza la comprobación de errores. -->
    <?php endif; ?>
    <!-- Formulario que envía los datos a esta misma página mediante POST. -->
    <form id="iniciosesiónForm" action="iniciosesión.php" method="POST" autocomplete="off">
        <!-- Etiqueta descriptiva del campo del nombre de usuario. -->
        <label for="usuario">Nombre de usuario:</label>
        <!-- Campo para introducir el nombre de usuario. -->
        <input
            type="text"
            id="usuario"
            name="usuario"
            placeholder="Ejemplo: juan123"
            pattern="[A-Za-zÁÉÍÓÚáéíóúÜüÑñ0-9_]{4,20}"
            title="Entre 4 y 20 caracteres."
            required>
        <!-- Etiqueta descriptiva del campo de la contraseña. -->
        <label for="password">Contraseña:</label>
        <!-- Campo que oculta visualmente los caracteres de la contraseña. -->
        <input
            type="password"
            id="password"
            name="password"
            placeholder="Mínimo 8 caracteres"
            pattern="(?=.*[A-Za-z])(?=.*\d).{8,}"
            title="Mínimo 8 caracteres, al menos una letra y un número"
            required>
        <!-- Botón que envía el formulario para iniciar sesión. -->
        <button type="submit">Iniciar sesión</button>
        <!-- Finaliza el formulario. -->
    </form>
    <!-- Pie de página oscuro con texto claro y espaciado vertical de Bootstrap. -->
    <footer class="bg-dark text-light py-5 mt-5">
        <!-- Contenedor que centra el contenido y limita su ancho. -->
        <div class="container">
            <!-- Organiza las tres columnas y centra su contenido horizontalmente. -->
            <div class="row g-4 justify-content-center text-center">
                <!-- COLUMNA 1: nombre y descripción de la tienda. -->
                <div class="col-12 col-md-6 col-lg-3">
                    <!-- Nombre de la tienda destacado en mayúsculas. -->
                    <h5 class="text-uppercase text-primary mb-3">
                        <strong>Mi Tienda Streetwear</strong>
                    </h5>
                    <!-- Descripción general de los productos y de la tienda. -->
                    <p class="text-secondary small">
                        Tu tienda de confianza para encontrar las mejores
                        prendas urbanas, camisetas oversize y zapatillas
                        exclusivas. Elevando tu estilo diario desde 2026.
                    </p>
                </div>
                <!-- COLUMNA 2: enlaces de navegación. -->
                <div class="col-6 col-md-6 col-lg-3">
                    <!-- Título de la sección de enlaces. -->
                    <h5 class="text-uppercase mb-3">
                        <strong>Enlaces de interés</strong>
                    </h5>
                    <!-- Lista de enlaces del pie de página. -->
                    <ul class="list-unstyled">
                        <!-- Enlace de inicio. -->
                        <li class="mb-2">
                            <a href="#" class="text-secondary text-decoration-none small text-hover-light">Inicio</a>
                        </li>
                        <!-- Enlace al catálogo principal de productos. -->
                        <li class="mb-2">
                            <a href="index.php" class="text-secondary text-decoration-none small text-hover-light">Productos</a>
                        </li>
                        <!-- Enlace destinado a las preguntas frecuentes. -->
                        <li class="mb-2">
                            <a href="#" class="text-secondary text-decoration-none small text-hover-light">Preguntas frecuentes</a>
                        </li>
                        <!-- Enlace a la página de contacto. -->
                        <li class="mb-2">
                            <a href="contacto.php" class="text-secondary text-decoration-none small text-hover-light">Contacto</a>
                        </li>
                    </ul>
                </div>
                <!-- COLUMNA 3: información de contacto. -->
                <div class="col-6 col-md-6 col-lg-3">
                    <!-- Título que también enlaza a la página de contacto. -->
                    <h5 class="text-uppercase mb-3">
                        <strong>
                            <a href="contacto.php" id="contacto" class="text-decoration-none text-reset">Contacto</a>
                        </strong>
                    </h5>
                    <!-- Lista con la dirección, el teléfono y el correo electrónico. -->
                    <ul class="list-unstyled text-secondary small">
                        <li class="mb-2">📍 Calle Mayor, n.º 12, Huesca</li>
                        <li class="mb-2">📞 +34 900 123 456</li>
                        <li class="mb-2">✉️ soporte@mitienda.com</li>
                    </ul>
                </div>
            </div>
            <!-- Línea divisoria entre el contenido y el aviso de derechos de autor. -->
            <hr class="border-secondary my-4">
            <!-- Sección del aviso de derechos de autor. -->
            <div class="row">
                <div class="col text-center">
                    <!-- Muestra el año y el titular de los derechos de autor. -->
                    <p class="text-secondary small mb-0">
                        &copy; 2026 Mi Tienda Streetwear. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>