<?php
// Comprueba si la sesión de PHP todavía no se ha iniciado.
if (session_status() === PHP_SESSION_NONE) {
    // Inicia la sesión para poder acceder a las variables almacenadas en $_SESSION.
    session_start();
}
// Guarda la ruta de la página del carrito de la tienda.
$carritoUrl = '/webcurso/vista/carrito.php';
// Inicializa el contador del número total de artículos del carrito.
$totalArticulosCarrito = 0;
// Comprueba si existe un pedido finalizado y si su valor no está vacío.
if (!empty($_SESSION['pedido_finalizado'])) {
    // Si el pedido está finalizado, utiliza los productos guardados en carrito_pedido.
    // Esto permite mantener visible el contador de artículos del pedido confirmado.
    // Si la variable no existe o es null, utiliza un array vacío.
    $carritoContador = $_SESSION['carrito_pedido'] ?? [];
} else {
    // Si el pedido no está finalizado, utiliza los productos del carrito habitual.
    // Si la variable de sesión no existe o es null, utiliza un array vacío.
    $carritoContador = $_SESSION['carrito'] ?? [];
}
// Comprueba que la variable del contador contenga un array antes de recorrerlo.
if (is_array($carritoContador)) {
    // Recorre todos los productos almacenados en el carrito seleccionado.
    foreach ($carritoContador as $producto) {
        // Obtiene la cantidad del producto actual.
        // Si no existe el campo 'cantidad' o es null, utiliza 0.
        // Convierte el resultado a un número entero y lo suma al total de artículos.
        $totalArticulosCarrito += (int) ($producto['cantidad'] ?? 0);
    }
}
?>
<!-- Carga la hoja de estilos general de la tienda. -->
<link rel="stylesheet" href="/webcurso/estilos/estilos.css">
<!-- Carga la hoja de estilos específica de las cabeceras. -->
<link rel="stylesheet" href="/webcurso/estilos/headers.css">
<!-- Define la cabecera principal de la página. -->
<header>
    <!-- Crea la barra de navegación de Bootstrap.
         navbar-expand-lg permite expandirla en pantallas grandes.
         navbar-dark adapta los elementos a fondos oscuros.
         bg-dark establece un fondo oscuro. -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <!-- Contenedor que ocupa todo el ancho disponible. -->
        <div class="container-fluid">
            <!-- Enlace de la marca que lleva a la página principal de la tienda. -->
            <a class="navbar-brand" href="/webcurso/index.php">
                <!-- Muestra el nombre de la tienda con un degradado de varios colores.
                     background define el degradado.
                     background-clip limita el fondo al texto.
                     -webkit-background-clip permite aplicar el efecto en navegadores compatibles.
                     color: transparent hace transparente el color del texto para mostrar el degradado. -->
                <strong style="background: linear-gradient(90deg, red, orange, yellow, green, cyan, blue, violet); background-clip: text; -webkit-background-clip: text; color: transparent;">
                    TIENDA DE ROPA
                </strong>
            </a>
            <!-- Cierra el enlace de la marca. -->
            <!-- Botón que permite desplegar la navegación en pantallas pequeñas. -->
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Mostrar navegación">
                <!-- Icono de Bootstrap que representa el menú desplegable. -->
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Cierra el botón del menú desplegable. -->
            <!-- Contenedor de los elementos de navegación que se contrae en pantallas pequeñas.
                 El identificador navbarNav coincide con data-bs-target del botón anterior. -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Lista de enlaces de navegación.
                     me-auto aplica un margen automático a la derecha para separar los enlaces del área de usuario. -->
                <ul class="navbar-nav me-auto">
                    <!-- Elemento de navegación para acceder al inicio. -->
                    <li class="nav-item">
                        <a class="nav-link" href="/webcurso/index.php">Inicio</a>
                    </li>
                    <!-- Elemento de navegación para acceder a la categoría Chándals. -->
                    <li class="nav-item">
                        <a class="nav-link" href="/webcurso/chandals.php">Chándals</a>
                    </li>
                    <!-- Elemento de navegación para acceder a la categoría Deportivas. -->
                    <li class="nav-item">
                        <a class="nav-link" href="/webcurso/deportivas.php">Deportivas</a>
                    </li>
                    <!-- Elemento de navegación para acceder a la categoría Reproductores. -->
                    <li class="nav-item">
                        <a class="nav-link" href="/webcurso/reproductores.php">Reproductores</a>
                    </li>
                    <!-- Elemento de navegación para acceder a la categoría Térmicas. -->
                    <li class="nav-item">
                        <a class="nav-link" href="/webcurso/termicas.php">Térmicas</a>
                    </li>
                </ul>
                <!-- Cierra la lista de enlaces de navegación. -->
                <!-- Agrupa los datos del usuario y el enlace del carrito.
                     d-flex alinea los elementos en una fila.
                     align-items-center los centra verticalmente.
                     gap-3 establece separación entre ellos. -->
                <div class="usuario-acceso d-flex align-items-center gap-3">
                    <?php
                    // Comprueba que exista la variable de sesión 'tipo'
                    // y que su valor sea exactamente 'registrado'.
                    if (
                        isset($_SESSION['tipo']) &&
                        $_SESSION['tipo'] === 'registrado'
                    ):
                    ?>
                        <!-- Muestra el icono de usuario y su nombre si ha iniciado sesión. -->
                        <span class="text-white">
                            👤
                            <?php
                            // Obtiene el nombre de usuario de la sesión.
                            // Si 'usuario' no existe o es null, utiliza 'username'.
                            // Si tampoco existe, muestra una cadena vacía.
                            // htmlspecialchars evita que el nombre se interprete como HTML.
                            echo htmlspecialchars(
                                $_SESSION['usuario'] ?? $_SESSION['username'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>
                        </span>
                        <!-- Enlace a la página donde el usuario registrado puede gestionar sus direcciones. -->
                        <a
                            href="/webcurso/direcciones.php"
                            class="text-white text-decoration-none">
                            Mis direcciones
                        </a>
                        <!-- Enlace que solicita cerrar la sesión mediante el parámetro logout=1.
                             La lógica que procesa ese parámetro se encuentra en index.php. -->
                        <a
                            href="/webcurso/index.php?logout=1"
                            class="text-white text-decoration-none">
                            Cerrar sesión
                        </a>
                    <?php else: ?>
                        <!-- Si el usuario no cumple la condición de registrado, muestra el acceso de invitado. -->
                        <!-- Identifica al usuario como invitado. -->
                        <span class="text-white">👤 Invitado</span>
                        <!-- Enlace a la página de inicio de sesión. -->
                        <a
                            href="/webcurso/login.php"
                            class="text-white text-decoration-none">
                            Iniciar sesión
                        </a>
                    <?php endif; ?>
                    <!-- Finaliza la condición que diferencia al usuario registrado del invitado. -->
                    <!-- Enlace al carrito de compra.
                         La URL se obtiene de $carritoUrl y se escapa para utilizarla en HTML. -->
                    <a
                        href="<?= htmlspecialchars($carritoUrl, ENT_QUOTES, 'UTF-8') ?>"
                        class="carrito-header"
                        title="Carrito"
                        aria-label="Carrito de compra">
                        <!-- Icono visual del carrito. -->
                        🛒
                        <?php
                        // Comprueba si el total de artículos es mayor que cero.
                        if ($totalArticulosCarrito > 0):
                        ?>
                            <!-- Muestra una insignia roja con el número total de artículos.
                                 bg-danger aplica el color rojo de Bootstrap. -->
                            <span class="badge bg-danger">
                                <?= $totalArticulosCarrito ?>
                            </span>
                        <?php endif; ?>
                        <!-- Si el carrito está vacío, no muestra la insignia numérica. -->
                    </a>
                    <!-- Cierra el enlace del carrito. -->
                </div>
                <!-- Cierra el área de usuario y carrito. -->
            </div>
            <!-- Cierra el contenedor desplegable de navegación. -->
        </div>
        <!-- Cierra el contenedor principal de la barra de navegación. -->
    </nav>
    <!-- Cierra la barra de navegación. -->
</header>
<!-- Cierra la cabecera principal de la página. -->
