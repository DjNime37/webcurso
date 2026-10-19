<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Comprueba si el usuario está registrado.
$esRegistrado = (
    isset($_SESSION['tipo'], $_SESSION['usuario_id'])
    && $_SESSION['tipo'] === 'registrado'
    && (int) $_SESSION['usuario_id'] > 0
);

// Si es un usuario registrado, permite continuar con el formulario
// para registrar la dirección que necesita durante el checkout.
if (!$esRegistrado) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<!-- Indica que el documento utiliza HTML5 y que el idioma principal es el español. -->
<html lang="es">

<head>
    <!-- Define la codificación de caracteres. -->
    <meta charset="UTF-8">
   <!-- Adapta la página al dispositivo desde el que se visualiza. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- Título de la pestaña del navegador. -->
    <title>Datos de envío</title>
   <!-- Carga Bootstrap para las clases utilizadas en el pie de página. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <!-- Conserva la hoja de estilos original del formulario. -->
    <link rel="stylesheet" href="estilos/botónregistrarse.css">
</head>

<body>
    <!-- Muestra el encabezado principal de la página. -->
    <h1>Datos de envío</h1>
   <!-- Informa al usuario de que puede iniciar sesión. -->
    <p>
        ¿Ya tienes cuenta?
        <a href="login.php">Inicia sesión para ir más rápido</a>
    </p>
   <!-- Envía la dirección al controlador para guardarla en MySQL. -->
    <form id="registroForm" action="controlador/direccionescontroller.php" method="POST" autocomplete="off">
       <!-- Indica que se va a crear una dirección. -->
        <input type="hidden" name="accion" value="crear">
       <!-- Indica que el formulario procede del proceso de compra. -->
        <input type="hidden" name="desde_checkout" value="1">
       <!-- Etiqueta del código postal. -->
        <label for="codigo_postal">Código postal:</label>
       <!-- Campo obligatorio con cinco dígitos. -->
        <input
            type="text"
            id="codigo_postal"
            name="codigo_postal"
            placeholder="22001"
            pattern="[0-9]{5}"
            inputmode="numeric"
            title="Introduce 5 números"
            required>
       <!-- Etiqueta del nombre de la vía. -->
        <label for="nombre_via">Nombre de la vía:</label>
       <!-- Campo obligatorio para la calle o avenida. -->
        <input
            type="text"
            id="nombre_via"
            name="nombre_via"
            placeholder="Ejemplo: Calle 12 de Octubre"
            required>
       <!-- Etiqueta del número del inmueble. -->
        <label for="numero">Número:</label>
       <!-- Campo obligatorio para el número. -->
        <input
            type="text"
            id="numero"
            name="numero"
            placeholder="Ejemplo: 12"
            required>
       <!-- Etiqueta del piso. -->
        <label for="piso">Piso:</label>
       <!-- Campo opcional para el piso. -->
        <input
            type="text"
            id="piso"
            name="piso"
            placeholder="Ejemplo: 3º">
       <!-- Etiqueta de la puerta. -->
        <label for="puerta">Puerta:</label>
       <!-- Campo opcional para la puerta. -->
        <input
            type="text"
            id="puerta"
            name="puerta"
            placeholder="Ejemplo: B">
       <!-- Etiqueta del país. -->
        <label for="pais">País:</label>
       <!-- Selector obligatorio del país. -->
        <select id="pais" name="pais" required>
            <option value="">Seleccionar país</option>
            <option value="España">España</option>
            <option value="Francia">Francia</option>
            <option value="Portugal">Portugal</option>
            <option value="Italia">Italia</option>
            <option value="Alemania">Alemania</option>
            <option value="Reino Unido">Reino Unido</option>
            <option value="Argentina">Argentina</option>
            <option value="México">México</option>
            <option value="Colombia">Colombia</option>
            <option value="Chile">Chile</option>
            <option value="Perú">Perú</option>
        </select>
       <!-- Envía la dirección al controlador y continúa el checkout. -->
        <button type="submit">Finalizar pedido</button>
    </form>
   <!-- Pie de página con información de la tienda y enlaces de interés. -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row g-4 justify-content-center text-center">
               <!-- Columna de presentación de la tienda. -->
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
               <!-- Columna de enlaces de interés. -->
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
               <!-- Columna de datos de contacto. -->
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
           <!-- Separa el contenido del aviso de copyright. -->
            <hr class="border-secondary my-4">
           <!-- Aviso de derechos reservados. -->
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