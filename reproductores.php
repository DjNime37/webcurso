<?php
// Comprueba si todavía no se ha iniciado una sesión PHP.
if (session_status() === PHP_SESSION_NONE) {
    // Inicia una sesión nueva o recupera la sesión existente.
    session_start();
}

// Comprueba que el usuario tenga una sesión registrada válida.
if (
    isset($_SESSION['tipo'], $_SESSION['usuario_id']) &&
    $_SESSION['tipo'] === 'registrado' &&
    (int) $_SESSION['usuario_id'] > 0
) {
    // Incluye la cabecera para usuarios registrados.
    require_once __DIR__ . '/headerregistrado.php';
} else {
    // Incluye la cabecera para usuarios no registrados.
    require_once __DIR__ . '/headerhome.php';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Nuestros reproductores MP4">
    <meta name="author" content="Usuario">

    <title>Nuestros reproductores MP4</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS de la tienda -->
    <link rel="stylesheet" href="estilos/estilos.css">
</head>

<body>
    <main class="container py-5">
        <div class="row">

            <!-- PRODUCTO 8 -->
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="imagenes/mp4/azul.jpeg"
                        class="card-img-top p-3 rounded-5"
                        alt="Reproductor MP4 azul">

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <strong>Reproductor MP4 azul</strong>
                        </h5>

                        <p class="card-text text-muted small">
                            Reproductor multimedia compacto y portátil.<br>
                            Capacidad: 16 GB
                        </p>

                        <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                            <input type="hidden" name="id" value="8">

                            <h5 class="mt-auto text-primary">
                                38,00 €
                            </h5>

                            <div class="d-flex align-items-center gap-2 mb-2">
                                <button type="button" class="btn btn-outline-secondary btn-restar">−</button>

                                <input type="number"
                                    name="cantidad"
                                    class="form-control text-center cantidad-producto"
                                    value="1"
                                    min="1"
                                    style="width: 70px;">

                                <button type="button" class="btn btn-outline-secondary btn-sumar">+</button>
                            </div>

                            <button type="submit" class="btn btn-primary btn-comprar">
                                Comprar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- PRODUCTO 9 -->
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="imagenes/mp4/negro.jpeg"
                        class="card-img-top p-3 rounded-5"
                        alt="Reproductor MP4 negro">

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <strong>Reproductor MP4 negro</strong>
                        </h5>

                        <p class="card-text text-muted small">
                            Reproductor multimedia portátil con diseño elegante.<br>
                            Capacidad: 16 GB
                        </p>

                        <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                            <input type="hidden" name="id" value="9">

                            <h5 class="mt-auto text-primary">
                                29,99 €
                            </h5>

                            <div class="d-flex align-items-center gap-2 mb-2">
                                <button type="button" class="btn btn-outline-secondary btn-restar">−</button>

                                <input type="number"
                                    name="cantidad"
                                    class="form-control text-center cantidad-producto"
                                    value="1"
                                    min="1"
                                    style="width: 70px;">

                                <button type="button" class="btn btn-outline-secondary btn-sumar">+</button>
                            </div>

                            <button type="submit" class="btn btn-primary btn-comprar">
                                Comprar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- PRODUCTO 10 -->
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="imagenes/mp4/wiki.jpeg"
                        class="card-img-top p-3 rounded-5"
                        alt="Reproductor MP4 multimedia">

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <strong>Reproductor MP4 multimedia</strong>
                        </h5>

                        <p class="card-text text-muted small">
                            Reproductor compacto para música y vídeo.<br>
                            Capacidad: 32 GB
                        </p>

                        <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                            <input type="hidden" name="id" value="10">

                            <h5 class="mt-auto text-primary">
                                24.75 €
                            </h5>

                            <div class="d-flex align-items-center gap-2 mb-2">
                                <button type="button" class="btn btn-outline-secondary btn-restar">−</button>

                                <input type="number"
                                    name="cantidad"
                                    class="form-control text-center cantidad-producto"
                                    value="1"
                                    min="1"
                                    style="width: 70px;">

                                <button type="button" class="btn btn-outline-secondary btn-sumar">+</button>
                            </div>

                            <button type="submit" class="btn btn-primary btn-comprar">
                                Comprar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript original de la tienda -->
    <script src="main.js"></script>

    <!-- FOOTER -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row g-4 justify-content-center text-center">

                <!-- Columna 1 -->
                <div class="col-12 col-md-6 col-lg-3">
                    <h5 class="text-uppercase text-primary mb-3">
                        <strong>Mi Tienda Streetwear</strong>
                    </h5>

                    <p class="text-secondary small">
                        Tu tienda de confianza para encontrar las mejores
                        prendas urbanas, camisetas oversize y zapatillas
                        exclusivas. Elevando tu estilo diario desde 2026.
                    </p>
                </div>

                <!-- Columna 2 -->
                <div class="col-6 col-md-6 col-lg-3">
                    <h5 class="text-uppercase mb-3">
                        <strong>Enlaces de interés</strong>
                    </h5>

                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="/webcurso/" class="text-secondary text-decoration-none small text-hover-light">Inicio</a>
                        </li>

                        <li class="mb-2">
                            <a href="index.php" class="text-secondary text-decoration-none small text-hover-light">Productos</a>
                        </li>

                        <li class="mb-2">
                            <a href="#" class="text-secondary text-decoration-none small text-hover-light">Preguntas frecuentes</a>
                        </li>

                        <li class="mb-2">
                            <a href="contacto.php" class="text-secondary text-decoration-none small text-hover-light">Contacto</a>
                        </li>
                    </ul>
                </div>

                <!-- Columna 3 -->
                <div class="col-6 col-md-6 col-lg-3">
                    <h5 class="text-uppercase mb-3">
                        <strong>
                            <a href="contacto.php" id="contacto" class="text-decoration-none text-reset">Contacto</a>
                        </strong>
                    </h5>

                    <ul class="list-unstyled text-secondary small">
                        <li class="mb-2">📍 Calle Mayor, n.º 12, Huesca</li>
                        <li class="mb-2">📞 +34 900 123 456</li>
                        <li class="mb-2">✉️ soporte@mitienda.com</li>
                    </ul>
                </div>

            </div>

            <hr class="border-secondary my-4">

            <!-- COPYRIGHT -->
            <div class="row">
                <div class="col text-center">
                    <p class="text-secondary small mb-0">
                        &copy; 2026 Mi Tienda Streetwear. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>