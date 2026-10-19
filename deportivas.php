<?php
// Comprueba si todavía no se ha iniciado una sesión PHP.
if (session_status() === PHP_SESSION_NONE) {
    // Inicia una sesión nueva o recupera la sesión existente para poder consultar los datos guardados en $_SESSION.
    session_start();
}
// Comprueba si existen las dos variables de sesión: 'tipo', que identifica el tipo de usuario, y 'usuario_id', que contiene el identificador del usuario.
if (
    // Verifica que ambas variables existan y no tengan valor null.
    isset($_SESSION['tipo'], $_SESSION['usuario_id'])
    // Comprueba que el tipo de usuario sea exactamente 'registrado'.
    && $_SESSION['tipo'] === 'registrado'
    // Convierte el identificador a un número entero y comprueba que sea mayor que cero.
    && (int) $_SESSION['usuario_id'] > 0
) {
    // Si se cumplen todas las condiciones anteriores, incluye la cabecera destinada a los usuarios logeados.
    require_once __DIR__ . '/headerregistrado.php'; // __DIR__ obtiene la ruta del directorio del archivo actual.require_once evita incluir de nuevo el mismo archivo
} else {
    // Si alguna de las condiciones no se cumple, incluye la cabecera destinada a los usuarios no registrados o no loegados.
    require_once __DIR__ . '/headerhome.php';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Nuestras deportivas">
    <meta name="author" content="Usuario">
    <title>Nuestras deportivas</title>
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
                    <img src="imagenes/deportivas/nikes.jpeg" class="card-img-top p-3 rounded-5" alt="Deportivas Nike">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <strong>Deportivas Nike</strong>
                        </h5>
                        <p class="card-text text-muted small">
                            Zapatillas deportivas Nike cómodas y resistentes para uso diario y deportivo.
                        </p>
                        <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                            <input type="hidden" name="id" value="4">
                            <label for="talla-nike" class="form-label">
                                Talla:
                            </label>
                            <select id="talla-nike" name="talla" class="form-select mb-3">
                                <option value="">Selecciona una talla</option>
                                <option value="38">38</option>
                                <option value="39">39</option>
                                <option value="40">40</option>
                                <option value="41">41</option>
                                <option value="42">42</option>
                                <option value="43">43</option>
                                <option value="44">44</option>
                                <option value="45">45</option>
                            </select>
                            <h5 class="mt-auto text-primary">
                                45.65 €
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
                    <img src="imagenes/deportivas/sketches.jpeg" class="card-img-top p-3 rounded-5"
                        alt="Deportivas Skechers">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <strong>Deportivas Skechers</strong>
                        </h5>
                        <p class="card-text text-muted small">
                            Zapatillas Skechers ligeras y confortables para caminar y realizar actividades deportivas.
                        </p>
                        <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                            <input type="hidden" name="id" value="5">
                            <label for="talla-skechers" class="form-label">
                                Talla:
                            </label>
                            <select id="talla-skechers" name="talla" class="form-select mb-3">
                                <option value="">Selecciona una talla</option>
                                <option value="38">38</option>
                                <option value="39">39</option>
                                <option value="40">40</option>
                                <option value="41">41</option>
                                <option value="42">42</option>
                                <option value="43">43</option>
                                <option value="44">44</option>
                                <option value="45">45</option>
                            </select>
                            <h5 class="mt-auto text-primary">
                                87.24 €
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
                    <img src="imagenes/deportivas/zapatillas.jpeg" class="card-img-top p-3 rounded-5"
                        alt="Zapatillas deportivas">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <strong>Zapatillas deportivas</strong>
                        </h5>
                        <p class="card-text text-muted small">
                            Zapatillas deportivas versátiles para entrenamiento y uso diario.
                        </p>
                        <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                            <input type="hidden" name="id" value="6">
                            <label for="talla-zapatillas" class="form-label">
                                Talla:
                            </label>
                            <select id="talla-zapatillas" name="talla" class="form-select mb-3">
                                <option value="">Selecciona una talla</option>
                                <option value="38">38</option>
                                <option value="39">39</option>
                                <option value="40">40</option>
                                <option value="41">41</option>
                                <option value="42">42</option>
                                <option value="43">43</option>
                                <option value="44">44</option>
                                <option value="45">45</option>
                            </select>
                            <h5 class="mt-auto text-primary">
                                46.15 €
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
            <!-- PRODUCTO 4 -->
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="imagenes/deportivas/unas.jpeg" class="card-img-top p-3 rounded-5"
                        alt="Zapatillas deportivas">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <strong>Zapatillas deportivas</strong>
                        </h5>
                        <p class="card-text text-muted small">
                            Modelo deportivo cómodo y ligero para diferentes actividades.
                        </p>
                        <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                            <input type="hidden" name="id" value="7">
                            <label for="talla-unas" class="form-label">
                                Talla:
                            </label>
                            <select id="talla-unas" name="talla" class="form-select mb-3">
                                <option value="">Selecciona una talla</option>
                                <option value="38">38</option>
                                <option value="39">39</option>
                                <option value="40">40</option>
                                <option value="41">41</option>
                                <option value="42">42</option>
                                <option value="43">43</option>
                                <option value="44">44</option>
                                <option value="45">45</option>
                            </select>
                            <h5 class="mt-auto text-primary">
                                91.75 €
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
    <!-- Botones de comprar -->
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