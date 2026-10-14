<!DOCTYPE html>
<html lang="es-ES">
<head>
    <meta charset="utf-8">
    <meta name="description" content="Página de contacto de la tienda de ropa online">
    <meta name="author" content="Usuario">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="estilos/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Contacto - Tienda de ropa online</title>
</head>
<body>
    <?php
    // Comprueba si todavía no se ha iniciado una sesión PHP.
    if (session_status() === PHP_SESSION_NONE) {
        // Inicia una sesión nueva o recupera la sesión existente.
        session_start();
    }
    // Comprueba si existe la variable 'tipo' en la sesión y si su valor es exactamente 'registrado'.
    if (
        isset($_SESSION['tipo']) &&
        $_SESSION['tipo'] === 'registrado'
    ) {
        require_once __DIR__ . '/headerregistrado.php'; // Para usuarios ya logeado=ya registrado.
    } else {
        require_once __DIR__ . '/headerhome.php'; // Para usuarios no logeados, ¿no registrados?
    }
    ?>
    <!-- FORMULARIO DE CONTACTO -->
    <main class="contacto-container container py-5">
        <div class="contacto-card mx-auto">
            <div class="contacto-cabecera text-center">
                <div class="contacto-icono">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <h1 class="fw-bold mb-2">CONTACTO</h1>
                <p class="text-secondary mb-0">
                    ¿Tienes alguna consulta? Estamos aquí para ayudarte.
                </p>
            </div>
            <form action="/procesar" method="post" class="contacto-form">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">
                            Nombre <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="nombre"
                            name="nombre"
                            placeholder="Introduce tu nombre"
                            autocomplete="given-name"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="apellidos" class="form-label">
                            Apellidos <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="apellidos"
                            name="apellidos"
                            placeholder="Introduce tus apellidos"
                            autocomplete="family-name"
                            required>
                    </div>
                    <div class="col-12">
                        <label for="email" class="form-label">
                            Correo electrónico <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa-solid fa-at"></i>
                            </span>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="nombre@correo.com"
                                autocomplete="email"
                                required>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="telefono" class="form-label">
                            Teléfono
                        </label>
                        <input
                            type="tel"
                            class="form-control"
                            id="telefono"
                            name="telefono"
                            placeholder="Tu número de teléfono"
                            autocomplete="tel"
                            disabled>
                    </div>
                    <div class="col-12">
                        <label for="mensaje" class="form-label">
                            Mensaje
                        </label>
                        <textarea
                            class="form-control"
                            id="mensaje"
                            name="mensaje"
                            rows="5"
                            placeholder="Cuéntanos cómo podemos ayudarte..."
                            disabled></textarea>
                    </div>
                    <div class="col-12">
                        <p class="text-secondary small mb-3">
                            <i class="fa-solid fa-circle-info me-1"></i>
                            Los campos marcados con * son obligatorios.
                        </p>
                        <button
                            type="submit"
                            id="submitButton"
                            class="btn btn-contacto w-100"
                            disabled>
                            <i class="fa-solid fa-paper-plane me-2"></i>
                            Enviar mensaje
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>
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
                            <a href="/webcurso/" class="text-secondary text-decoration-none small text-hover-light">Inicio</a>
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
    <!-- SCRIPT AL FINAL -->
    <script src="main.js"></script>
</body>
</html>