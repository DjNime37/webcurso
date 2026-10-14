<?php
// Comprueba si todavía no se ha iniciado una sesión PHP.
if (session_status() === PHP_SESSION_NONE) {
    // Inicia una sesión nueva o recupera la sesión existente.
    session_start();
}
// Comprueba que las variables 'tipo' y 'usuario_id' existan en la sesión.
// El operador && exige que se cumplan todas las condiciones.
if (
    isset($_SESSION['tipo'], $_SESSION['usuario_id']) // Verifica que ambas variables estén definidas y que ninguna sea null.
    && $_SESSION['tipo'] === 'registrado'// Verifica que el tipo de usuario sea exactamente 'registrado'.
    && (int) $_SESSION['usuario_id'] > 0 // Convierte el identificador del usuario a número entero y comprueba que sea mayor que 0.
) {
    // Incluye la cabecera destinada a los usuarios registrados.
    require_once __DIR__ . '/headerregistrado.php'; // require_once evita incluir el mismo archivo más de una vez.
} else {
    // Si no se cumplen todas las condiciones anteriores,
    // incluye la cabecera destinada a los usuarios no registrados.
    require_once __DIR__ . '/headerhome.php';
}
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Nuestros chandales">
    <meta name="author" content="Usuario">
    <title>Nuestros chandales</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos/estilos.css">
</head>
<body>
    <main class="container py-5">
        <div class="row">
            <!-- PRODUCTO 1 -->
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="imagenes/chandals/chandalazul.jpeg" class="card-img-top p-3 rounded-5" alt="Chándal azul">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <strong>Chándal deportivo azul</strong>
                        </h5>
                        <p class="card-text text-muted small">
                            Chándal cómodo y ligero para deporte y uso diario.
                        </p>  <!-- Igual que en otros casos, pero solo para chándals. -->
                        <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                            <input type="hidden" name="id" value="1">
                            <label for="talla-chandal-azul" class="form-label">
                                Talla:
                            </label>
                            <select id="talla-chandal-azul" name="talla" class="form-select mb-3">
                                <option value="">Selecciona una talla</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                            </select>
                            <h5 class="mt-auto text-primary">
                                49.20 €
                            </h5>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <button type="button" class="btn btn-outline-secondary btn-restar">-</button>
                                <input type="number" name="cantidad"
                                    class="form-control text-center cantidad-producto"
                                    value="1" min="1" style="width: 70px;">
                                <button type="button" class="btn btn-outline-secondary btn-sumar">+</button>
                            </div>
                            <button type="submit" class="btn btn-primary btn-comprar" disabled>
                                Comprar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- PRODUCTO 2 -->
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="imagenes/chandals/chandalrojo.jpeg" class="card-img-top p-3 rounded-5" alt="Chándal rojo">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <strong>Chándal deportivo rojo</strong>
                        </h5>
                        <p class="card-text text-muted small">
                            Diseño deportivo con tejido cómodo y resistente.
                        </p>
                        <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                            <input type="hidden" name="id" value="2">
                            <label for="talla-chandal-rojo" class="form-label">
                                Talla:
                            </label>
                            <select id="talla-chandal-rojo" name="talla" class="form-select mb-3">
                                <option value="">Selecciona una talla</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                            </select>
                            <h5 class="mt-auto text-primary">
                                56.40 €
                            </h5>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <button type="button" class="btn btn-outline-secondary btn-restar">-</button>
                                <input type="number" name="cantidad"
                                    class="form-control text-center cantidad-producto"
                                    value="1" min="1" style="width: 70px;">
                                <button type="button" class="btn btn-outline-secondary btn-sumar">+</button>
                            </div>
                            <button type="submit" class="btn btn-primary btn-comprar" disabled>
                                Comprar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- PRODUCTO 3 -->
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="imagenes/chandals/chandalgris.jpeg" class="card-img-top p-3 rounded-5" alt="Chándal gris">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <strong>Chándal deportivo gris</strong>
                        </h5>
                        <p class="card-text text-muted small">
                            Chándal versátil para entrenamiento y tiempo libre.
                        </p>
                        <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                            <input type="hidden" name="id" value="3">
                            <label for="talla-chandal-gris" class="form-label">
                                Talla:
                            </label>
                            <select id="talla-chandal-gris" name="talla" class="form-select mb-3">
                                <option value="">Selecciona una talla</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                            </select>
                            <h5 class="mt-auto text-primary">
                                49.95 €
                            </h5>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <button type="button" class="btn btn-outline-secondary btn-restar">-</button>
                                <input type="number" name="cantidad"
                                    class="form-control text-center cantidad-producto"
                                    value="1" min="1" style="width: 70px;">
                                <button type="button" class="btn btn-outline-secondary btn-sumar">+</button>
                            </div>
                            <button type="submit" class="btn btn-primary btn-comprar" disabled>
                                Comprar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Están lo botones de comprar -->
    <script src="main.js"></script>
    <!-- FOOTER -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <!-- justify-content-center centra las 3 columnas horizontalmente -->
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
                            <a href="#" class="text-secondary text-decoration-none small text-hover-light">Inicio</a>
                        </li>
                        <li class="mb-2">
                            <a href="index.php" class="text-secondary text-decoration-none small text-hover-light">Productos</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-secondary text-decoration-none small text-hover-light">Preguntas
                                frecuentes</a>
                        </li>
                        <li class="mb-2">
                            <a href="contacto.php"
                                class="text-secondary text-decoration-none small text-hover-light">Contacto</a>
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
