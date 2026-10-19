<?php
// Comprueba si todavía no se ha iniciado una sesión PHP.
if (session_status() === PHP_SESSION_NONE) {
    // Inicia la sesión para acceder a los datos del usuario.
    session_start();
}
// Comprueba que existen el tipo de usuario y su identificador,
// que el usuario está registrado y que su identificador es mayor que cero.
if (
    isset($_SESSION['tipo'], $_SESSION['usuario_id'])
    && $_SESSION['tipo'] === 'registrado'
    && (int) $_SESSION['usuario_id'] > 0
) {
    // Carga la cabecera correspondiente a los usuarios registrados.
    // __DIR__ representa la ruta absoluta de la carpeta de este archivo.
    require_once __DIR__ . '/headerregistrado.php';
} else {
    // Carga la cabecera para los usuarios que no están registrados.
    // require_once evita incluir el mismo archivo más de una vez.
    require_once __DIR__ . '/headerhome.php';
}
?>
<!DOCTYPE html>
<!-- Indica que el documento utiliza HTML5. -->
<html lang="es">
<!-- Establece el español como idioma del documento. -->

<head>
    <!-- Permite interpretar correctamente las tildes y otros caracteres especiales. -->
    <meta charset="UTF-8">
    <!-- Adapta el ancho de la página al dispositivo utilizado. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Proporciona una descripción de la página para los buscadores. -->
    <meta name="description" content="Nuestras camisetas térmicas">
    <!-- Identifica al autor indicado para esta página. -->
    <meta name="author" content="Usuario">
    <!-- Define el título que aparece en la pestaña del navegador. -->
    <title>Nuestras camisetas térmicas</title>
    <!-- Carga la hoja de estilos de Bootstrap 5.3.3. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Carga los estilos CSS propios de la tienda. -->
    <link rel="stylesheet" href="estilos/estilos.css">
</head>

<body>
    <!-- Contenedor principal que centra el contenido y añade espacio vertical. -->
    <main class="container py-5">
        <!-- Fila de Bootstrap que contiene las tres tarjetas de productos. -->
        <div class="row">
            <!-- PRODUCTO 1: camiseta térmica azul. -->
            <!-- En pantallas pequeñas ocupa toda la fila, en medianas media fila
                 y en pantallas grandes un tercio de la fila. -->
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <!-- Tarjeta del producto con altura completa y sombra ligera. -->
                <div class="card h-100 shadow-sm">
                    <!-- Imagen de la camiseta térmica azul.
                         alt proporciona una descripción de la imagen. -->
                    <img src="imagenes/termicas/azul.jpeg" class="card-img-top p-3 rounded-5" alt="Térmica azul">
                    <!-- Cuerpo de la tarjeta organizado mediante Flexbox. -->
                    <div class="card-body d-flex flex-column">
                        <!-- Nombre del producto. -->
                        <h5 class="card-title">
                            <strong>Térmica azul</strong>
                        </h5>
                        <!-- Descripción breve del producto. -->
                        <p class="card-text text-muted small">
                            Prenda térmica diseñada para conservar el calor corporal.
                        </p>
                        <!-- Formulario que envía los datos al controlador del carrito.
                             POST transmite los datos en el cuerpo de la petición.
                             target="_blank" abre el resultado en otra pestaña. -->
                        <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                            <!-- Identificador del producto que recibe el controlador. -->
                            <input type="hidden" name="id" value="11">
                            <!-- Etiqueta asociada al selector de tallas mediante su atributo for. -->
                            <label for="talla-azul" class="form-label">
                                Talla:
                            </label>
                            <!-- Selector de tallas disponibles para la camiseta azul. -->
                            <select id="talla-azul" name="talla" class="form-select mb-3">
                                <!-- Opción inicial que solicita seleccionar una talla. -->
                                <option value="">Selecciona una talla</option>
                                <!-- Tallas que puede seleccionar el usuario. -->
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                            </select>
                            <!-- Precio de la camiseta térmica azul. -->
                            <h5 class="mt-auto text-primary">
                                29.95 €
                            </h5>
                            <!-- Contenedor de los controles para modificar la cantidad. -->
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <!-- Botón para restar una unidad.
                                     btn-restar permite que main.js identifique el botón. -->
                                <button type="button" class="btn btn-outline-secondary btn-restar">-</button>
                                <!-- Campo numérico que indica la cantidad seleccionada.
                                     Empieza en una unidad y no permite valores inferiores a uno. -->
                                <input type="number" name="cantidad" class="form-control text-center cantidad-producto"
                                    value="1" min="1" style="width: 70px;">
                                <!-- Botón para sumar una unidad.
                                     btn-sumar permite que main.js identifique el botón. -->
                                <button type="button" class="btn btn-outline-secondary btn-sumar">+</button>
                            </div>
                            <!-- Botón que envía el formulario para comprar.
                                 disabled hace que inicialmente no se pueda pulsar.
                                 main.js puede gestionar su habilitación según la selección. -->
                            <button type="submit" class="btn btn-primary btn-comprar" disabled>
                                Comprar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- PRODUCTO 2: camiseta térmica gris. -->
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <!-- Tarjeta del producto con sombra y altura completa. -->
                <div class="card h-100 shadow-sm">
                    <!-- Imagen de la camiseta térmica gris. -->
                    <img src="imagenes/termicas/gris.jpeg" class="card-img-top p-3 rounded-5" alt="Térmica gris">
                    <!-- Contenedor de la información del producto. -->
                    <div class="card-body d-flex flex-column">
                        <!-- Nombre del producto. -->
                        <h5 class="card-title">
                            <strong>Térmica gris</strong>
                        </h5>
                        <!-- Descripción breve de la camiseta gris. -->
                        <p class="card-text text-muted small">
                            Tejido térmico cómodo para días fríos.
                        </p>
                        <!-- Formulario para enviar el producto al controlador del carrito. -->
                        <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                            <!-- Identificador del producto gris. -->
                            <input type="hidden" name="id" value="12">
                            <!-- Etiqueta del selector de tallas. -->
                            <label for="talla-gris" class="form-label">
                                Talla:
                            </label>
                            <!-- Selector de tallas de la camiseta gris. -->
                            <select id="talla-gris" name="talla" class="form-select mb-3">
                                <!-- Opción inicial que indica que debe seleccionarse una talla. -->
                                <option value="">Selecciona una talla</option>
                                <!-- Tallas disponibles. -->
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                            </select>
                            <!-- Precio de la camiseta térmica gris. -->
                            <h5 class="mt-auto text-primary">
                                56.40 €
                            </h5>
                            <!-- Controles para modificar la cantidad del producto. -->
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <!-- Botón para restar una unidad. -->
                                <button type="button" class="btn btn-outline-secondary btn-restar">-</button>
                                <!-- Campo numérico para indicar la cantidad que se desea comprar. -->
                                <input type="number" name="cantidad" class="form-control text-center cantidad-producto"
                                    value="1" min="1" style="width: 70px;">
                                <!-- Botón para sumar una unidad. -->
                                <button type="button" class="btn btn-outline-secondary btn-sumar">+</button>
                            </div>
                            <!-- Botón de compra inicialmente deshabilitado. -->
                            <button type="submit" class="btn btn-primary btn-comprar" disabled>
                                Comprar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- PRODUCTO 3: camiseta térmica negra. -->
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <!-- Tarjeta del producto con sombra y altura completa. -->
                <div class="card h-100 shadow-sm">
                    <!-- Imagen de la camiseta térmica negra. -->
                    <img src="imagenes/termicas/negra.jpeg" class="card-img-top p-3 rounded-5" alt="Térmica negra">
                    <!-- Contenedor de la información del producto. -->
                    <div class="card-body d-flex flex-column">
                        <!-- Nombre del producto. -->
                        <h5 class="card-title">
                            <strong>Térmica negra</strong>
                        </h5>
                        <!-- Descripción breve de la camiseta negra. -->
                        <p class="card-text text-muted small">
                            Prenda térmica ligera y confortable para invierno.
                        </p>
                        <!-- Formulario para enviar los datos al controlador del carrito. -->
                        <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                            <!-- Identificador del producto negro. -->
                            <input type="hidden" name="id" value="13">
                            <!-- Etiqueta del selector de tallas. -->
                            <label for="talla-termica-negra" class="form-label">
                                Talla:
                            </label>
                            <!-- Selector de tallas de la camiseta negra. -->
                            <select id="talla-termica-negra" name="talla" class="form-select mb-3">
                                <!-- Opción inicial que solicita elegir una talla. -->
                                <option value="">Selecciona una talla</option>
                                <!-- Tallas disponibles. -->
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                            </select>
                            <!-- Precio de la camiseta térmica negra. -->
                            <h5 class="mt-auto text-primary">
                                29.70 €
                            </h5>
                            <!-- Controles para seleccionar la cantidad. -->
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <!-- Botón para restar una unidad. -->
                                <button type="button" class="btn btn-outline-secondary btn-restar">-</button>
                                <!-- Campo numérico para indicar la cantidad del producto. -->
                                <input type="number" name="cantidad" class="form-control text-center cantidad-producto"
                                    value="1" min="1" style="width: 70px;">
                                <!-- Botón para sumar una unidad. -->
                                <button type="button" class="btn btn-outline-secondary btn-sumar">+</button>
                            </div>
                            <!-- Botón para enviar el formulario de compra.
                                 Está deshabilitado inicialmente. -->
                            <button type="submit" class="btn btn-primary btn-comprar" disabled>
                                Comprar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Carga el JavaScript de Bootstrap para sus componentes interactivos. -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- PIE DE PÁGINA: contiene información de la tienda y enlaces útiles. -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <!-- Organiza las tres columnas y centra su contenido horizontalmente. -->
            <div class="row g-4 justify-content-center text-center">
                <!-- COLUMNA 1: nombre y descripción de la tienda. -->
                <div class="col-12 col-md-6 col-lg-3">
                    <!-- Nombre de la tienda destacado en mayúsculas. -->
                    <h5 class="text-uppercase text-primary mb-3">
                        <strong>Mi Tienda Streetwear</strong>
                    </h5>
                    <!-- Descripción general de los productos y la tienda. -->
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
<!-- Este archivo gestiona los controles de cantidad y los botones de compra. -->
<script src="main.js"></script>

</html>