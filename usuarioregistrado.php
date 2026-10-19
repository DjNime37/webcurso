<?php
// Inicia la sesión para acceder a las variables almacenadas durante la navegación del usuario.
session_start();
// Comprueba que exista el tipo de usuario y que sea exactamente 'registrado'.
// Si no cumple la condición, redirige al usuario al inicio y detiene la ejecución.
if (
    !isset($_SESSION['tipo']) ||
    $_SESSION['tipo'] !== 'registrado'
) {
    // Redirige al usuario a la página principal.
    header('Location: index.php');
    // Detiene la ejecución para impedir que se muestre el contenido protegido.
    exit;
}
// Incluye el archivo del modelo que contiene la clase Carrito.
// __DIR__ representa el directorio de este archivo.
// require_once evita incluir el mismo archivo varias veces.
require_once __DIR__ . '/modelo/carrito.php';
// Crea una conexión PDO con la base de datos tienda_ropa.
// localhost indica que MySQL se encuentra en el equipo local.
// root es el usuario de la base de datos y la contraseña está vacía.
// utf8mb4 permite almacenar y recuperar caracteres Unicode.
$pdo = new PDO(
    'mysql:host=localhost;dbname=tienda_ropa;charset=utf8mb4',
    'root',
    ''
);
// Configura PDO para que lance excepciones cuando se produzcan errores de base de datos.
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// Crea un objeto de la clase Carrito y le proporciona la conexión a la base de datos.
$carrito = new Carrito($pdo);
// Recupera los productos del carrito del usuario registrado.
// La implementación de este método se encuentra en modelo/carrito.php.
$carrito->cargarCarritoRegistrado();
// Inicializa a cero el contador de unidades del carrito.
$totalArticulosCarrito = 0;
// Comprueba que la variable de sesión 'carrito' exista y contenga un array.
if (
    isset($_SESSION['carrito']) &&
    is_array($_SESSION['carrito'])
) {
    // Recorre todos los productos almacenados en el carrito.
    foreach ($_SESSION['carrito'] as $producto) {
        // Suma las unidades de cada producto.
        // Si no existe la clave 'cantidad', utiliza cero como valor predeterminado.
        // El operador (int) convierte el valor a un número entero.
        $totalArticulosCarrito += (int) ($producto['cantidad'] ?? 0);
    }
}
?>
<!DOCTYPE html>
<!-- Declara que el documento utiliza HTML5. -->
<html lang="es-ES">
<!-- Establece el español de España como idioma del documento. -->
<head>
    <!-- Define la codificación para mostrar correctamente las tildes y otros caracteres. -->
    <meta charset="utf-8">
    <!-- Describe el contenido general de la tienda. -->
    <meta name="description" content="Tienda de ropa online">
    <!-- Identifica al autor indicado para la página. -->
    <meta name="author" content="Hacker39">
    <!-- Define palabras clave relacionadas con los productos de la tienda. -->
    <meta name="keywords" content="ropa, moda, camisetas, chaquetas, zapatillas, streetwear">
    <!-- Adapta el diseño al ancho de móviles, tabletas y ordenadores. -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Carga la hoja de estilos de Bootstrap 5.3.3. -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <!-- Define el texto que aparece en la pestaña del navegador. -->
    <title>Tienda de ropa online</title>
    <!-- Carga Font Awesome para utilizar iconos, incluido el del carrito. -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Carga los estilos generales de la tienda. -->
    <link rel="stylesheet" href="estilos/estilos.css">
    <!-- Carga los estilos específicos de las cabeceras. -->
    <link rel="stylesheet" href="estilos/headers.css">
    <!-- Carga los estilos del botón de inicio de sesión. -->
    <link rel="stylesheet" href="estilos/botóniniciosesión.css">
</head>
<body>
    <!-- CABECERA: muestra la navegación correspondiente al usuario registrado. -->
    <?php require_once __DIR__ . '/headerregistrado.php'; ?>
    <!-- MENSAJE DE BIENVENIDA -->
    <div
        id="mensaje-bienvenida"
        class="mensaje-bienvenida">
        <!-- Icono decorativo de saludo. -->
        <span>👋</span>
        <!-- Texto de bienvenida al usuario que ha iniciado sesión. -->
        Bienvenido,
        <?php
        // Recupera el nombre del usuario desde la sesión.
        // Si no existe, utiliza el texto 'Usuario'.
        // htmlspecialchars evita que el nombre se interprete como código HTML.
        // ENT_QUOTES protege también las comillas simples y dobles.
        // UTF-8 establece la codificación de los caracteres.
        echo htmlspecialchars(
            $_SESSION['usuario'] ?? 'Usuario',
            ENT_QUOTES,
            'UTF-8'
        );
        ?>
    </div>
    <!-- BANNER PUBLICITARIO DE NIKE -->
    <div
        id="banner-nike"
        class="banner-publicidad">
        <!-- Botón que permite cerrar el banner publicitario. -->
        <button
            class="cerrar-banner"
            type="button"
            aria-label="Cerrar publicidad">
            ×
        </button>
        <!-- Imagen publicitaria de Nike. -->
        <img
            src="imagenes/nike.jpg"
            alt="Publicidad Nike">
    </div>
    <!-- SEGUNDO BANNER PUBLICITARIO: ADIDAS -->
    <div
        id="banner-adidas"
        class="banner-publicidad banner-segundo">
        <!-- Botón que permite cerrar el segundo banner. -->
        <button
            class="cerrar-banner"
            type="button"
            aria-label="Cerrar publicidad">
            ×
        </button>
        <!-- Imagen publicitaria de Adidas. -->
        <img
            src="imagenes/adidas.jpg"
            alt="Publicidad Adidas">
    </div>
    <!-- CONTENIDO PRINCIPAL DE LA PÁGINA -->
    <main class="content-container mt-4">
        <!-- CARRUSEL DE IMÁGENES:
             Bootstrap permite cambiar automáticamente entre las imágenes.
             mb-4 añade un margen inferior.
             data-bs-interval establece cinco segundos entre diapositivas. -->
        <div
            id="carouselExampleIndicators"
            class="carousel slide mb-4"
            data-bs-ride="carousel"
            data-bs-interval="5000">
            <!-- INDICADORES DEL CARRUSEL:
                 cada botón permite seleccionar una diapositiva concreta. -->
            <div class="carousel-indicators">
                <!-- Indicador de la primera diapositiva, activa inicialmente. -->
                <button
                    type="button"
                    data-bs-target="#carouselExampleIndicators"
                    data-bs-slide-to="0"
                    class="active"
                    aria-current="true"
                    aria-label="Diapositiva 1">
                </button>
                <!-- Indicador de la segunda diapositiva. -->
                <button
                    type="button"
                    data-bs-target="#carouselExampleIndicators"
                    data-bs-slide-to="1"
                    aria-label="Diapositiva 2">
                </button>
                <!-- Indicador de la tercera diapositiva. -->
                <button
                    type="button"
                    data-bs-target="#carouselExampleIndicators"
                    data-bs-slide-to="2"
                    aria-label="Diapositiva 3">
                </button>
                <!-- Indicador de la cuarta diapositiva. -->
                <button
                    type="button"
                    data-bs-target="#carouselExampleIndicators"
                    data-bs-slide-to="3"
                    aria-label="Diapositiva 4">
                </button>
                <!-- Indicador de la quinta diapositiva. -->
                <button
                    type="button"
                    data-bs-target="#carouselExampleIndicators"
                    data-bs-slide-to="4"
                    aria-label="Diapositiva 5">
                </button>
                <!-- Indicador de la sexta diapositiva. -->
                <button
                    type="button"
                    data-bs-target="#carouselExampleIndicators"
                    data-bs-slide-to="5"
                    aria-label="Diapositiva 6">
                </button>
                <!-- Indicador de la séptima diapositiva. -->
                <button
                    type="button"
                    data-bs-target="#carouselExampleIndicators"
                    data-bs-slide-to="6"
                    aria-label="Diapositiva 7">
                </button>
                <!-- Indicador de la octava diapositiva. -->
                <button
                    type="button"
                    data-bs-target="#carouselExampleIndicators"
                    data-bs-slide-to="7"
                    aria-label="Diapositiva 8">
                </button>
                <!-- Indicador de la novena diapositiva. -->
                <button
                    type="button"
                    data-bs-target="#carouselExampleIndicators"
                    data-bs-slide-to="8"
                    aria-label="Diapositiva 9">
                </button>
                <!-- Indicador de la décima diapositiva. -->
                <button
                    type="button"
                    data-bs-target="#carouselExampleIndicators"
                    data-bs-slide-to="9"
                    aria-label="Diapositiva 10">
                </button>
                <!-- Indicador de la undécima diapositiva. -->
                <button
                    type="button"
                    data-bs-target="#carouselExampleIndicators"
                    data-bs-slide-to="10"
                    aria-label="Diapositiva 11">
                </button>
                <!-- Indicador de la duodécima diapositiva. -->
                <button
                    type="button"
                    data-bs-target="#carouselExampleIndicators"
                    data-bs-slide-to="11"
                    aria-label="Diapositiva 12">
                </button>
                <!-- Indicador de la decimotercera diapositiva. -->
                <button
                    type="button"
                    data-bs-target="#carouselExampleIndicators"
                    data-bs-slide-to="12"
                    aria-label="Diapositiva 13">
                </button>
            </div>
            <!-- IMÁGENES DEL CARRUSEL:
                 carousel-inner agrupa las diapositivas.
                 rounded-3 redondea las esquinas y shadow-sm añade una sombra ligera. -->
            <div class="carousel-inner rounded-3 shadow-sm">
                <!-- CHÁNDALS -->
                <!-- Primera imagen: chándal azul.
                     active indica que es la diapositiva inicial. -->
                <div class="carousel-item active">
                    <img
                        src="imagenes/chandals/chandalazul.jpeg"
                        class="d-block w-100"
                        alt="Chándal azul">
                </div>
                <!-- Segunda imagen: chándal rojo. -->
                <div class="carousel-item">
                    <img
                        src="imagenes/chandals/chandalrojo.jpeg"
                        class="d-block w-100"
                        alt="Chándal rojo">
                </div>
                <!-- Tercera imagen: chándal gris. -->
                <div class="carousel-item">
                    <img
                        src="imagenes/chandals/chandalgris.jpeg"
                        class="d-block w-100"
                        alt="Chándal gris">
                </div>
                <!-- DEPORTIVAS -->
                <!-- Cuarta imagen: deportivas Nike. -->
                <div class="carousel-item">
                    <img
                        src="imagenes/deportivas/nikes.jpeg"
                        class="d-block w-100"
                        alt="Deportivas Nike">
                </div>
                <!-- Quinta imagen: deportivas Skechers. -->
                <div class="carousel-item">
                    <img
                        src="imagenes/deportivas/sketches.jpeg"
                        class="d-block w-100"
                        alt="Deportivas Skechers">
                </div>
                <!-- Sexta imagen: zapatillas deportivas. -->
                <div class="carousel-item">
                    <img
                        src="imagenes/deportivas/zapatillas.jpeg"
                        class="d-block w-100"
                        alt="Zapatillas deportivas">
                </div>
                <!-- Séptima imagen: zapatillas deportivas Pro. -->
                <div class="carousel-item">
                    <img
                        src="imagenes/deportivas/unas.jpeg"
                        class="d-block w-100"
                        alt="Zapatillas deportivas Pro">
                </div>
                <!-- REPRODUCTORES MP4 -->
                <!-- Octava imagen: reproductor MP4 azul. -->
                <div class="carousel-item">
                    <img
                        src="imagenes/mp4/azul.jpeg"
                        class="d-block w-100"
                        alt="Reproductor MP4 azul">
                </div>
                <!-- Novena imagen: reproductor MP4 negro. -->
                <div class="carousel-item">
                    <img
                        src="imagenes/mp4/negro.jpeg"
                        class="d-block w-100"
                        alt="Reproductor MP4 negro">
                </div>
                <!-- Décima imagen: reproductor MP4 multimedia. -->
                <div class="carousel-item">
                    <img
                        src="imagenes/mp4/wiki.jpeg"
                        class="d-block w-100"
                        alt="Reproductor MP4 multimedia">
                </div>
                <!-- CAMISETAS TÉRMICAS -->
                <!-- Undécima imagen: ropa térmica azul. -->
                <div class="carousel-item">
                    <img
                        src="imagenes/termicas/azul.jpeg"
                        class="d-block w-100"
                        alt="Ropa térmica azul">
                </div>
                <!-- Duodécima imagen: ropa térmica gris. -->
                <div class="carousel-item">
                    <img
                        src="imagenes/termicas/gris.jpeg"
                        class="d-block w-100"
                        alt="Ropa térmica gris">
                </div>
                <!-- Decimotercera imagen: ropa térmica negra. -->
                <div class="carousel-item">
                    <img
                        src="imagenes/termicas/negra.jpeg"
                        class="d-block w-100"
                        alt="Ropa térmica negra">
                </div>
            </div>
            <!-- BOTÓN PARA RETROCEDER UNA DIAPOSITIVA. -->
            <button
                class="carousel-control-prev"
                type="button"
                data-bs-target="#carouselExampleIndicators"
                data-bs-slide="prev">
                <!-- Icono visual de la flecha anterior. -->
                <span
                    class="carousel-control-prev-icon"
                    aria-hidden="true">
                </span>
                <!-- Texto accesible para lectores de pantalla. -->
                <span class="visually-hidden">
                    Anterior
                </span>
            </button>
            <!-- BOTÓN PARA AVANZAR UNA DIAPOSITIVA. -->
            <button
                class="carousel-control-next"
                type="button"
                data-bs-target="#carouselExampleIndicators"
                data-bs-slide="next">
                <!-- Icono visual de la flecha siguiente. -->
                <span
                    class="carousel-control-next-icon"
                    aria-hidden="true">
                </span>
                <!-- Texto accesible para lectores de pantalla. -->
                <span class="visually-hidden">
                    Siguiente
                </span>
            </button>
        </div>
        <!-- CONTENEDOR DE LA SECCIÓN DE PRODUCTOS.
             my-5 añade márgenes verticales. -->
        <div class="container my-5">
            <!-- Título de la sección que presenta los productos de la tienda. -->
            <h2 class="text-center mb-4">
                Nuestros productos
            </h2>
            <!-- Fila de Bootstrap donde se distribuyen las tarjetas. -->
            <div class="row">
                <!-- PRODUCTO 1: CHÁNDAL AZUL. -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <!-- Tarjeta que ocupa toda la altura disponible y tiene sombra. -->
                    <div class="card h-100 shadow-sm">
                        <!-- Imagen del producto. -->
                        <img
                            src="imagenes/chandals/chandalazul.jpeg"
                            class="card-img-top p-3 rounded-5"
                            alt="Chándal azul">
                        <!-- Cuerpo de la tarjeta organizado verticalmente. -->
                        <div class="card-body d-flex flex-column">
                            <!-- Nombre del producto. -->
                            <h5 class="card-title">
                                <strong>
                                    Chándal deportivo azul
                                </strong>
                            </h5>
                            <!-- Descripción breve del producto. -->
                            <p class="card-text text-muted small">
                                Chándal cómodo y ligero para deporte y uso diario.
                            </p>
                            <!-- Formulario que envía los datos al controlador del carrito.
                                 target="_blank" abre la respuesta en otra pestaña. -->
                            <form
                                action="controlador/carritocontroller.php"
                                method="POST"
                                target="_blank">
                                <!-- Identificador del producto que recibe el controlador. -->
                                <input
                                    type="hidden"
                                    name="id"
                                    value="1">
                                <!-- Etiqueta asociada al selector de tallas. -->
                                <label
                                    for="talla-chandal-azul"
                                    class="form-label">
                                    Talla:
                                </label>
                                <!-- Selector que permite elegir la talla del chándal. -->
                                <select
                                    id="talla-chandal-azul"
                                    name="talla"
                                    class="form-select mb-3">
                                    <!-- Opción inicial para solicitar una talla. -->
                                    <option value="">
                                        Selecciona una talla
                                    </option>
                                    <!-- Tallas disponibles para seleccionar. -->
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                </select>
                                <!-- Precio del chándal azul. -->
                                <h5 class="mt-auto text-primary">
                                    49,20 €
                                </h5>
                                <!-- Controles para aumentar o reducir la cantidad. -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <!-- Botón de resta gestionado por main.js. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-restar">
                                        −
                                    </button>
                                    <!-- Campo numérico para indicar las unidades que se desean comprar.
                                         value establece la cantidad inicial y min evita valores inferiores a uno. -->
                                    <input
                                        type="number"
                                        name="cantidad"
                                        class="form-control text-center cantidad-producto"
                                        value="1"
                                        min="1"
                                        style="width: 70px;">
                                    <!-- Botón de suma gestionado por main.js. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-sumar">
                                        +
                                    </button>
                                </div>
                                <!-- Botón para enviar el formulario de compra.
                                     Está deshabilitado inicialmente. -->
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-comprar"
                                    disabled>
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- PRODUCTO 2: CHÁNDAL ROJO. -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Imagen del chándal rojo. -->
                        <img
                            src="imagenes/chandals/chandalrojo.jpeg"
                            class="card-img-top p-3 rounded-5"
                            alt="Chándal rojo">
                        <div class="card-body d-flex flex-column">
                            <!-- Nombre del producto. -->
                            <h5 class="card-title">
                                <strong>
                                    Chándal deportivo rojo
                                </strong>
                            </h5>
                            <!-- Descripción del producto. -->
                            <p class="card-text text-muted small">
                                Diseño deportivo con tejido cómodo y resistente.
                            </p>
                            <!-- Formulario de compra del chándal rojo. -->
                            <form
                                action="controlador/carritocontroller.php"
                                method="POST"
                                target="_blank">
                                <!-- Identificador del chándal rojo. -->
                                <input
                                    type="hidden"
                                    name="id"
                                    value="2">
                                <!-- Etiqueta del selector de tallas. -->
                                <label
                                    for="talla-chandal-rojo"
                                    class="form-label">
                                    Talla:
                                </label>
                                <!-- Selector de tallas. -->
                                <select
                                    id="talla-chandal-rojo"
                                    name="talla"
                                    class="form-select mb-3">
                                    <option value="">
                                        Selecciona una talla
                                    </option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                </select>
                                <!-- Precio del chándal rojo. -->
                                <h5 class="mt-auto text-primary">
                                    56,40 €
                                </h5>
                                <!-- Controles de cantidad. -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <!-- Resta una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-restar">
                                        −
                                    </button>
                                    <!-- Indica la cantidad del producto. -->
                                    <input
                                        type="number"
                                        name="cantidad"
                                        class="form-control text-center cantidad-producto"
                                        value="1"
                                        min="1"
                                        style="width: 70px;">
                                    <!-- Suma una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-sumar">
                                        +
                                    </button>
                                </div>
                                <!-- Botón de compra inicialmente deshabilitado. -->
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-comprar"
                                    disabled>
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- PRODUCTO 3: CHÁNDAL GRIS. -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Imagen del chándal gris. -->
                        <img
                            src="imagenes/chandals/chandalgris.jpeg"
                            class="card-img-top p-3 rounded-5"
                            alt="Chándal gris">
                        <div class="card-body d-flex flex-column">
                            <!-- Nombre del producto. -->
                            <h5 class="card-title">
                                <strong>
                                    Chándal deportivo gris
                                </strong>
                            </h5>
                            <!-- Descripción del producto. -->
                            <p class="card-text text-muted small">
                                Chándal versátil para entrenamiento y tiempo libre.
                            </p>
                            <!-- Formulario de compra del chándal gris. -->
                            <form
                                action="controlador/carritocontroller.php"
                                method="POST"
                                target="_blank">
                                <!-- Identificador del chándal gris. -->
                                <input
                                    type="hidden"
                                    name="id"
                                    value="3">
                                <!-- Etiqueta del selector de tallas. -->
                                <label
                                    for="talla-chandal-gris"
                                    class="form-label">
                                    Talla:
                                </label>
                                <!-- Selector de tallas disponibles. -->
                                <select
                                    id="talla-chandal-gris"
                                    name="talla"
                                    class="form-select mb-3">
                                    <option value="">
                                        Selecciona una talla
                                    </option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                </select>
                                <!-- Precio del chándal gris. -->
                                <h5 class="mt-auto text-primary">
                                    49,95 €
                                </h5>
                                <!-- Controles para modificar la cantidad. -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <!-- Botón para restar una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-restar">
                                        −
                                    </button>
                                    <!-- Campo numérico de cantidad. -->
                                    <input
                                        type="number"
                                        name="cantidad"
                                        class="form-control text-center cantidad-producto"
                                        value="1"
                                        min="1"
                                        style="width: 70px;">
                                    <!-- Botón para sumar una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-sumar">
                                        +
                                    </button>
                                </div>
                                <!-- Botón para comprar el producto. -->
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-comprar"
                                    disabled>
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- PRODUCTO 4: DEPORTIVAS NIKE. -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Imagen de las deportivas Nike. -->
                        <img
                            src="imagenes/deportivas/nikes.jpeg"
                            class="card-img-top p-3 rounded-5"
                            alt="Deportivas Nike">
                        <div class="card-body d-flex flex-column">
                            <!-- Nombre del producto. -->
                            <h5 class="card-title">
                                <strong>
                                    Deportivas Nike
                                </strong>
                            </h5>
                            <!-- Descripción del producto. -->
                            <p class="card-text text-muted small">
                                Zapatillas deportivas cómodas para entrenamiento y uso diario.
                            </p>
                            <!-- Formulario de compra de las deportivas Nike. -->
                            <form
                                action="controlador/carritocontroller.php"
                                method="POST"
                                target="_blank">
                                <!-- Identificador del producto. -->
                                <input
                                    type="hidden"
                                    name="id"
                                    value="4">
                                <!-- Etiqueta del selector de tallas. -->
                                <label
                                    for="talla-nike"
                                    class="form-label">
                                    Talla:
                                </label>
                                <!-- Selector de tallas numéricas. -->
                                <select
                                    id="talla-nike"
                                    name="talla"
                                    class="form-select mb-3">
                                    <option value="">
                                        Selecciona una talla
                                    </option>
                                    <option value="38">38</option>
                                    <option value="39">39</option>
                                    <option value="40">40</option>
                                    <option value="41">41</option>
                                    <option value="42">42</option>
                                    <option value="43">43</option>
                                    <option value="44">44</option>
                                    <option value="45">45</option>
                                </select>
                                <!-- Precio de las deportivas Nike. -->
                                <h5 class="mt-auto text-primary">
                                    79,90 €
                                </h5>
                                <!-- Controles para seleccionar la cantidad. -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <!-- Botón para reducir la cantidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-restar">
                                        −
                                    </button>
                                    <!-- Cantidad de unidades que se enviará al controlador. -->
                                    <input
                                        type="number"
                                        name="cantidad"
                                        class="form-control text-center cantidad-producto"
                                        value="1"
                                        min="1"
                                        style="width: 70px;">
                                    <!-- Botón para aumentar la cantidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-sumar">
                                        +
                                    </button>
                                </div>
                                <!-- Botón de compra inicialmente deshabilitado. -->
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-comprar"
                                    disabled>
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- PRODUCTO 5: DEPORTIVAS SKECHERS. -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Imagen de las deportivas Skechers. -->
                        <img
                            src="imagenes/deportivas/sketches.jpeg"
                            class="card-img-top p-3 rounded-5"
                            alt="Deportivas Skechers">
                        <div class="card-body d-flex flex-column">
                            <!-- Nombre del producto. -->
                            <h5 class="card-title">
                                <strong>
                                    Deportivas Skechers
                                </strong>
                            </h5>
                            <!-- Descripción del producto. -->
                            <p class="card-text text-muted small">
                                Diseño ligero con gran comodidad para el día a día.
                            </p>
                            <!-- Formulario de compra de las deportivas Skechers. -->
                            <form
                                action="controlador/carritocontroller.php"
                                method="POST"
                                target="_blank">
                                <!-- Identificador del producto. -->
                                <input
                                    type="hidden"
                                    name="id"
                                    value="5">
                                <!-- Etiqueta del selector de tallas. -->
                                <label
                                    for="talla-skechers"
                                    class="form-label">
                                    Talla:
                                </label>
                                <!-- Selector de tallas disponibles. -->
                                <select
                                    id="talla-skechers"
                                    name="talla"
                                    class="form-select mb-3">
                                    <option value="">
                                        Selecciona una talla
                                    </option>
                                    <option value="38">38</option>
                                    <option value="39">39</option>
                                    <option value="40">40</option>
                                    <option value="41">41</option>
                                    <option value="42">42</option>
                                    <option value="43">43</option>
                                    <option value="44">44</option>
                                    <option value="45">45</option>
                                </select>
                                <!-- Precio de las deportivas Skechers. -->
                                <h5 class="mt-auto text-primary">
                                    69,90 €
                                </h5>
                                <!-- Controles de cantidad. -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <!-- Botón para restar una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-restar">
                                        −
                                    </button>
                                    <!-- Campo numérico para indicar las unidades. -->
                                    <input
                                        type="number"
                                        name="cantidad"
                                        class="form-control text-center cantidad-producto"
                                        value="1"
                                        min="1"
                                        style="width: 70px;">
                                    <!-- Botón para sumar una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-sumar">
                                        +
                                    </button>
                                </div>
                                <!-- Botón para comprar el producto. -->
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-comprar"
                                    disabled>
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- PRODUCTO 6: ZAPATILLAS STREETWEAR. -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Imagen de las zapatillas Streetwear. -->
                        <img
                            src="imagenes/deportivas/zapatillas.jpeg"
                            class="card-img-top p-3 rounded-5"
                            alt="Zapatillas Streetwear">
                        <div class="card-body d-flex flex-column">
                            <!-- Nombre del producto. -->
                            <h5 class="card-title">
                                <strong>
                                    Zapatillas Streetwear
                                </strong>
                            </h5>
                            <!-- Descripción del producto. -->
                            <p class="card-text text-muted small">
                                Diseño moderno y cómodo para uso diario.
                            </p>
                            <!-- Formulario de compra de las zapatillas Streetwear. -->
                            <form
                                action="controlador/carritocontroller.php"
                                method="POST"
                                target="_blank">
                                <!-- Identificador del producto. -->
                                <input
                                    type="hidden"
                                    name="id"
                                    value="6">
                                <!-- Etiqueta del selector de tallas. -->
                                <label
                                    for="talla-streetwear"
                                    class="form-label">
                                    Talla:
                                </label>
                                <!-- Selector de tallas numéricas. -->
                                <select
                                    id="talla-streetwear"
                                    name="talla"
                                    class="form-select mb-3">
                                    <option value="">
                                        Selecciona una talla
                                    </option>
                                    <option value="38">38</option>
                                    <option value="39">39</option>
                                    <option value="40">40</option>
                                    <option value="41">41</option>
                                    <option value="42">42</option>
                                    <option value="43">43</option>
                                    <option value="44">44</option>
                                    <option value="45">45</option>
                                </select>
                                <!-- Precio de las zapatillas. -->
                                <h5 class="mt-auto text-primary">
                                    74,10 €
                                </h5>
                                <!-- Controles para cambiar la cantidad. -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <!-- Botón para reducir las unidades. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-restar">
                                        −
                                    </button>
                                    <!-- Campo numérico de cantidad. -->
                                    <input
                                        type="number"
                                        name="cantidad"
                                        class="form-control text-center cantidad-producto"
                                        value="1"
                                        min="1"
                                        style="width: 70px;">
                                    <!-- Botón para aumentar las unidades. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-sumar">
                                        +
                                    </button>
                                </div>
                                <!-- Botón para enviar la compra. -->
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-comprar"
                                    disabled>
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- PRODUCTO 7: ZAPATILLAS DEPORTIVAS PRO. -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Imagen de las zapatillas deportivas Pro. -->
                        <img
                            src="imagenes/deportivas/unas.jpeg"
                            class="card-img-top p-3 rounded-5"
                            alt="Zapatillas deportivas Pro">
                        <div class="card-body d-flex flex-column">
                            <!-- Nombre del producto. -->
                            <h5 class="card-title">
                                <strong>
                                    Zapatillas deportivas Pro
                                </strong>
                            </h5>
                            <!-- Descripción del producto. -->
                            <p class="card-text text-muted small">
                                Modelo deportivo con diseño moderno y confortable.
                            </p>
                            <!-- Formulario de compra de las zapatillas Pro. -->
                            <form
                                action="controlador/carritocontroller.php"
                                method="POST"
                                target="_blank">
                                <!-- Identificador del producto. -->
                                <input
                                    type="hidden"
                                    name="id"
                                    value="7">
                                <!-- Etiqueta del selector de tallas. -->
                                <label
                                    for="talla-pro"
                                    class="form-label">
                                    Talla:
                                </label>
                                <!-- Selector de tallas disponibles. -->
                                <select
                                    id="talla-pro"
                                    name="talla"
                                    class="form-select mb-3">
                                    <option value="">
                                        Selecciona una talla
                                    </option>
                                    <option value="39">39</option>
                                    <option value="40">40</option>
                                    <option value="41">41</option>
                                    <option value="42">42</option>
                                    <option value="43">43</option>
                                    <option value="44">44</option>
                                    <option value="45">45</option>
                                </select>
                                <!-- Precio de las zapatillas deportivas Pro. -->
                                <h5 class="mt-auto text-primary">
                                    84,90 €
                                </h5>
                                <!-- Controles para modificar la cantidad. -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <!-- Botón para restar una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-restar">
                                        −
                                    </button>
                                    <!-- Campo numérico de cantidad. -->
                                    <input
                                        type="number"
                                        name="cantidad"
                                        class="form-control text-center cantidad-producto"
                                        value="1"
                                        min="1"
                                        style="width: 70px;">
                                    <!-- Botón para sumar una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-sumar">
                                        +
                                    </button>
                                </div>
                                <!-- Botón de compra. -->
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-comprar"
                                    disabled>
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- PRODUCTO 8: REPRODUCTOR MP4 AZUL. -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Imagen del reproductor MP4 azul. -->
                        <img
                            src="imagenes/mp4/azul.jpeg"
                            class="card-img-top p-3 rounded-5"
                            alt="Reproductor MP4 azul">
                        <div class="card-body d-flex flex-column">
                            <!-- Nombre del producto. -->
                            <h5 class="card-title">
                                <strong>
                                    Reproductor MP4 azul
                                </strong>
                            </h5>
                            <!-- Descripción y capacidad del reproductor. -->
                            <p class="card-text text-muted small">
                                Reproductor multimedia compacto y portátil.<br>
                                Capacidad: 16 GB
                            </p>
                            <!-- Formulario de compra del reproductor azul. -->
                            <form
                                action="controlador/carritocontroller.php"
                                method="POST"
                                target="_blank">
                                <!-- Identificador del producto. -->
                                <input
                                    type="hidden"
                                    name="id"
                                    value="8">
                                <!-- Precio del reproductor. -->
                                <h5 class="mt-auto text-primary">
                                    35,75 €
                                </h5>
                                <!-- Controles para modificar la cantidad. -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <!-- Botón para restar una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-restar">
                                        −
                                    </button>
                                    <!-- Campo numérico de cantidad. -->
                                    <input
                                        type="number"
                                        name="cantidad"
                                        class="form-control text-center cantidad-producto"
                                        value="1"
                                        min="1"
                                        style="width: 70px;">
                                    <!-- Botón para sumar una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-sumar">
                                        +
                                    </button>
                                </div>
                                <!-- Botón para enviar el formulario de compra. -->
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-comprar">
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- PRODUCTO 9: REPRODUCTOR MP4 NEGRO. -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Imagen del reproductor MP4 negro. -->
                        <img
                            src="imagenes/mp4/negro.jpeg"
                            class="card-img-top p-3 rounded-5"
                            alt="Reproductor MP4 negro">
                        <div class="card-body d-flex flex-column">
                            <!-- Nombre del producto. -->
                            <h5 class="card-title">
                                <strong>
                                    Reproductor MP4 negro
                                </strong>
                            </h5>
                            <!-- Descripción y capacidad del reproductor. -->
                            <p class="card-text text-muted small">
                                Reproductor multimedia portátil con diseño elegante.<br>
                                Capacidad: 16 GB
                            </p>
                            <!-- Formulario de compra del reproductor negro. -->
                            <form
                                action="controlador/carritocontroller.php"
                                method="POST"
                                target="_blank">
                                <!-- Identificador del producto. -->
                                <input
                                    type="hidden"
                                    name="id"
                                    value="9">
                                <!-- Precio del reproductor. -->
                                <h5 class="mt-auto text-primary">
                                    44,00 €
                                </h5>
                                <!-- Controles de cantidad. -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <!-- Botón para restar una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-restar">
                                        −
                                    </button>
                                    <!-- Campo numérico de cantidad. -->
                                    <input
                                        type="number"
                                        name="cantidad"
                                        class="form-control text-center cantidad-producto"
                                        value="1"
                                        min="1"
                                        style="width: 70px;">
                                    <!-- Botón para sumar una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-sumar">
                                        +
                                    </button>
                                </div>
                                <!-- Botón de compra. -->
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-comprar">
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- PRODUCTO 10: REPRODUCTOR MP4 MULTIMEDIA. -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Imagen del reproductor MP4 multimedia. -->
                        <img
                            src="imagenes/mp4/wiki.jpeg"
                            class="card-img-top p-3 rounded-5"
                            alt="Reproductor MP4 multimedia">
                        <div class="card-body d-flex flex-column">
                            <!-- Nombre del producto. -->
                            <h5 class="card-title">
                                <strong>
                                    Reproductor MP4 multimedia
                                </strong>
                            </h5>
                            <!-- Descripción y capacidad del reproductor. -->
                            <p class="card-text text-muted small">
                                Reproductor compacto para música y vídeo.<br>
                                Capacidad: 32 GB
                            </p>
                            <!-- Formulario de compra del reproductor multimedia. -->
                            <form
                                action="controlador/carritocontroller.php"
                                method="POST"
                                target="_blank">
                                <!-- Identificador del producto. -->
                                <input
                                    type="hidden"
                                    name="id"
                                    value="10">
                                <!-- Precio del reproductor. -->
                                <h5 class="mt-auto text-primary">
                                    49,90 €
                                </h5>
                                <!-- Controles para modificar la cantidad. -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <!-- Botón para restar una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-restar">
                                        −
                                    </button>
                                    <!-- Campo numérico de cantidad. -->
                                    <input
                                        type="number"
                                        name="cantidad"
                                        class="form-control text-center cantidad-producto"
                                        value="1"
                                        min="1"
                                        style="width: 70px;">
                                    <!-- Botón para sumar una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-sumar">
                                        +
                                    </button>
                                </div>
                                <!-- Botón para enviar el formulario de compra. -->
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-comprar">
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- PRODUCTO 11: ROPA TÉRMICA AZUL. -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Imagen de la prenda térmica azul. -->
                        <img
                            src="imagenes/termicas/azul.jpeg"
                            class="card-img-top p-3 rounded-5"
                            alt="Ropa térmica azul">
                        <div class="card-body d-flex flex-column">
                            <!-- Nombre del producto. -->
                            <h5 class="card-title">
                                <strong>
                                    Ropa térmica azul
                                </strong>
                            </h5>
                            <!-- Descripción de la prenda. -->
                            <p class="card-text text-muted small">
                                Prenda térmica diseñada para conservar el calor corporal.
                            </p>
                            <!-- Formulario de compra de la prenda azul. -->
                            <form
                                action="controlador/carritocontroller.php"
                                method="POST"
                                target="_blank">
                                <!-- Identificador del producto. -->
                                <input
                                    type="hidden"
                                    name="id"
                                    value="11">
                                <!-- Etiqueta del selector de tallas. -->
                                <label
                                    for="talla-termica-azul"
                                    class="form-label">
                                    Talla:
                                </label>
                                <!-- Selector de tallas disponibles. -->
                                <select
                                    id="talla-termica-azul"
                                    name="talla"
                                    class="form-select mb-3">
                                    <option value="">
                                        Selecciona una talla
                                    </option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                </select>
                                <!-- Precio de la prenda térmica azul. -->
                                <h5 class="mt-auto text-primary">
                                    29,95 €
                                </h5>
                                <!-- Controles para modificar la cantidad. -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <!-- Botón para restar una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-restar">
                                        −
                                    </button>
                                    <!-- Campo numérico de cantidad. -->
                                    <input
                                        type="number"
                                        name="cantidad"
                                        class="form-control text-center cantidad-producto"
                                        value="1"
                                        min="1"
                                        style="width: 70px;">
                                    <!-- Botón para sumar una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-sumar">
                                        +
                                    </button>
                                </div>
                                <!-- Botón de compra inicialmente deshabilitado. -->
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-comprar"
                                    disabled>
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- PRODUCTO 12: ROPA TÉRMICA GRIS. -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Imagen de la prenda térmica gris. -->
                        <img
                            src="imagenes/termicas/gris.jpeg"
                            class="card-img-top p-3 rounded-5"
                            alt="Ropa térmica gris">
                        <div class="card-body d-flex flex-column">
                            <!-- Nombre del producto. -->
                            <h5 class="card-title">
                                <strong>
                                    Ropa térmica gris
                                </strong>
                            </h5>
                            <!-- Descripción de la prenda. -->
                            <p class="card-text text-muted small">
                                Tejido térmico cómodo para días fríos.
                            </p>
                            <!-- Formulario de compra de la prenda gris. -->
                            <form
                                action="controlador/carritocontroller.php"
                                method="POST"
                                target="_blank">
                                <!-- Identificador del producto. -->
                                <input
                                    type="hidden"
                                    name="id"
                                    value="12">
                                <!-- Etiqueta del selector de tallas. -->
                                <label
                                    for="talla-termica-gris"
                                    class="form-label">
                                    Talla:
                                </label>
                                <!-- Selector de tallas disponibles. -->
                                <select
                                    id="talla-termica-gris"
                                    name="talla"
                                    class="form-select mb-3">
                                    <option value="">
                                        Selecciona una talla
                                    </option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                </select>
                                <!-- Precio de la prenda térmica gris. -->
                                <h5 class="mt-auto text-primary">
                                    29,95 €
                                </h5>
                                <!-- Controles para modificar la cantidad. -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <!-- Botón para restar una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-restar">
                                        −
                                    </button>
                                    <!-- Campo numérico de cantidad. -->
                                    <input
                                        type="number"
                                        name="cantidad"
                                        class="form-control text-center cantidad-producto"
                                        value="1"
                                        min="1"
                                        style="width: 70px;">
                                    <!-- Botón para sumar una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-sumar">
                                        +
                                    </button>
                                </div>
                                <!-- Botón de compra inicialmente deshabilitado. -->
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-comprar"
                                    disabled>
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- PRODUCTO 13: ROPA TÉRMICA NEGRA. -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Imagen de la prenda térmica negra. -->
                        <img
                            src="imagenes/termicas/negra.jpeg"
                            class="card-img-top p-3 rounded-5"
                            alt="Ropa térmica negra">
                        <div class="card-body d-flex flex-column">
                            <!-- Nombre del producto. -->
                            <h5 class="card-title">
                                <strong>
                                    Ropa térmica negra
                                </strong>
                            </h5>
                            <!-- Descripción de la prenda. -->
                            <p class="card-text text-muted small">
                                Prenda térmica ligera y confortable para invierno.
                            </p>
                            <!-- Formulario de compra de la prenda negra. -->
                            <form
                                action="controlador/carritocontroller.php"
                                method="POST"
                                target="_blank">
                                <!-- Identificador del producto. -->
                                <input
                                    type="hidden"
                                    name="id"
                                    value="13">
                                <!-- Etiqueta del selector de tallas. -->
                                <label
                                    for="talla-termica-negra"
                                    class="form-label">
                                    Talla:
                                </label>
                                <!-- Selector de tallas disponibles. -->
                                <select
                                    id="talla-termica-negra"
                                    name="talla"
                                    class="form-select mb-3">
                                    <option value="">
                                        Selecciona una talla
                                    </option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                </select>
                                <!-- Precio de la prenda térmica negra. -->
                                <h5 class="mt-auto text-primary">
                                    29,95 €
                                </h5>
                                <!-- Controles para modificar la cantidad. -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <!-- Botón para restar una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-restar">
                                        −
                                    </button>
                                    <!-- Campo numérico de cantidad. -->
                                    <input
                                        type="number"
                                        name="cantidad"
                                        class="form-control text-center cantidad-producto"
                                        value="1"
                                        min="1"
                                        style="width: 70px;">
                                    <!-- Botón para sumar una unidad. -->
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary btn-sumar">
                                        +
                                    </button>
                                </div>
                                <!-- Botón de compra inicialmente deshabilitado. -->
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-comprar"
                                    disabled>
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- SECCIÓN DE PREGUNTAS FRECUENTES. -->
            <div class="status-notice">
                <div class="container my-5">
                    <!-- Título de la sección de preguntas frecuentes. -->
                    <h2 class="text-center mb-4">
                        Preguntas frecuentes
                    </h2>
                    <!-- Acordeón de Bootstrap que permite desplegar las respuestas. -->
                    <div
                        class="accordion"
                        id="accordionFAQ">
                        <!-- PREGUNTA FRECUENTE 1. -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <!-- Botón que abre o cierra la primera respuesta. -->
                                <button
                                    class="accordion-button"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne"
                                    aria-expanded="true"
                                    aria-controls="collapseOne">
                                    <strong>
                                        ¿Cuáles son los métodos de pago disponibles?
                                    </strong>
                                </button>
                            </h2>
                            <!-- Primera respuesta, visible inicialmente.
                                 data-bs-parent permite coordinar el acordeón. -->
                            <div
                                id="collapseOne"
                                class="accordion-collapse collapse show"
                                data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    Aceptamos tarjetas de crédito y débito
                                    (Visa, Mastercard), PayPal, Apple Pay y
                                    transferencias bancarias directas.
                                    Todos nuestros pagos se procesan de forma
                                    100 % segura.
                                </div>
                            </div>
                        </div>
                        <!-- PREGUNTA FRECUENTE 2. -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <!-- Botón para mostrar u ocultar la segunda respuesta. -->
                                <button
                                    class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo"
                                    aria-expanded="false"
                                    aria-controls="collapseTwo">
                                    <strong>
                                        ¿Cuánto tarda en llegar mi pedido?
                                    </strong>
                                </button>
                            </h2>
                            <!-- Segunda respuesta, oculta inicialmente. -->
                            <div
                                id="collapseTwo"
                                class="accordion-collapse collapse"
                                data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    Los envíos estándar a toda la península
                                    tardan entre 24 y 48 horas laborables.
                                    Si realizas tu pedido antes de las 14:00,
                                    saldrá de nuestro almacén el mismo día.
                                </div>
                            </div>
                        </div>
                        <!-- PREGUNTA FRECUENTE 3. -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <!-- Botón para mostrar u ocultar la tercera respuesta. -->
                                <button
                                    class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapseThree"
                                    aria-expanded="false"
                                    aria-controls="collapseThree">
                                    <strong>
                                        ¿Cómo puedo realizar una devolución?
                                    </strong>
                                </button>
                            </h2>
                            <!-- Tercera respuesta, oculta inicialmente. -->
                            <div
                                id="collapseThree"
                                class="accordion-collapse collapse"
                                data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    Dispones de 30 días naturales desde la
                                    recepción de tu compra para solicitar una
                                    devolución gratuita. Solo tienes que acceder
                                    a tu panel de usuario o rellenar nuestro
                                    formulario de contacto solicitando la
                                    etiqueta de envío.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- ESPACIO RESERVADO PARA EL PIE DE PÁGINA. -->
    <!-- Carga el JavaScript de Bootstrap para los componentes interactivos,
         como el carrusel y el acordeón. -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>
    <!-- CONTROLES FLOTANTES DE NAVEGACIÓN. -->
    <div class="flechas-navegacion">
        <!-- Botón que permite volver a la parte superior de la página.
             Su comportamiento depende del JavaScript que lo gestione. -->
        <button
            id="btn-subir"
            class="btn-flecha"
            type="button"
            title="Ir arriba"
            aria-label="Ir arriba">
            ↑
        </button>
        <!-- Botón que permite desplazarse a la parte inferior de la página. -->
        <button
            id="btn-bajar"
            class="btn-flecha"
            type="button"
            title="Ir abajo"
            aria-label="Ir abajo">
            ↓
        </button>
    </div>
    <!-- Carga el archivo JavaScript propio de la tienda.
         En él se pueden gestionar los botones de compra, las cantidades,
         los banners y los controles de navegación. -->
    <script src="main.js"></script>
    <!-- PIE DE PÁGINA CON INFORMACIÓN Y ENLACES DE LA TIENDA. -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <!-- Distribuye las columnas del pie de página y centra el contenido. -->
            <div class="row g-4 justify-content-center text-center">
                <!-- COLUMNA 1: nombre y descripción de la tienda. -->
                <div class="col-12 col-md-6 col-lg-3">
                    <!-- Nombre comercial de la tienda. -->
                    <h5 class="text-uppercase text-primary mb-3">
                        <strong>
                            Mi Tienda Streetwear
                        </strong>
                    </h5>
                    <!-- Descripción de los productos que ofrece la tienda. -->
                    <p class="text-secondary small">
                        Tu tienda de confianza para encontrar las mejores
                        prendas urbanas, camisetas oversize y zapatillas
                        exclusivas. Elevando tu estilo diario desde 2026.
                    </p>
                </div>
                <!-- COLUMNA 2: enlaces de interés. -->
                <div class="col-6 col-md-6 col-lg-3">
                    <!-- Título de la lista de enlaces. -->
                    <h5 class="text-uppercase mb-3">
                        <strong>
                            Enlaces de interés
                        </strong>
                    </h5>
                    <!-- Lista de enlaces de navegación. -->
                    <ul class="list-unstyled">
                        <!-- Enlace a la página principal. -->
                        <li class="mb-2">
                            <a
                                href="/webcurso/"
                                class="text-secondary text-decoration-none small text-hover-light">
                                Inicio
                            </a>
                        </li>
                        <!-- Enlace reservado a la sección de productos. -->
                        <li class="mb-2">
                            <a
                                href="#"
                                class="text-secondary text-decoration-none small text-hover-light">
                                Productos
                            </a>
                        </li>
                        <!-- Enlace reservado a las preguntas frecuentes. -->
                        <li class="mb-2">
                            <a
                                href="#"
                                class="text-secondary text-decoration-none small text-hover-light">
                                Preguntas frecuentes
                            </a>
                        </li>
                        <!-- Enlace a la página de contacto. -->
                        <li class="mb-2">
                            <a
                                href="contacto.php"
                                class="text-secondary text-decoration-none small text-hover-light">
                                Contacto
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- COLUMNA 3: datos de contacto. -->
                <div class="col-6 col-md-6 col-lg-3">
                    <!-- Título que también enlaza a la página de contacto. -->
                    <h5 class="text-uppercase mb-3">
                        <strong>
                            <a
                                href="contacto.php"
                                id="contacto"
                                class="text-decoration-none text-reset">
                                Contacto
                            </a>
                        </strong>
                    </h5>
                    <!-- Lista con la dirección, el teléfono y el correo electrónico. -->
                    <ul class="list-unstyled text-secondary small">
                        <li class="mb-2">
                            📍 Calle Mayor, n.º 12, Huesca
                        </li>
                        <li class="mb-2">
                            📞 +34 900 123 456
                        </li>
                        <li class="mb-2">
                            ✉️ soporte@mitienda.com
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Línea que separa los enlaces del aviso de derechos de autor. -->
            <hr class="border-secondary my-4">
            <!-- SECCIÓN DE COPYRIGHT. -->
            <div class="row">
                <div class="col text-center">
                    <!-- Muestra el año y el titular de los derechos de autor. -->
                    <p class="text-secondary small mb-0">
                        &copy; 2026 Mi Tienda Streetwear.
                        Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>