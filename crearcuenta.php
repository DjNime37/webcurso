<?php

// Comprueba si todavía no se ha iniciado una sesión PHP.
if (session_status() === PHP_SESSION_NONE) {

    // Inicia una sesión nueva o recupera la sesión existente para poder acceder a sus variables.
    session_start();
}

// Comprueba si existe la variable de sesión 'registro_duplicado' y si su valor es exactamente true.
// El resultado de esta comprobación se guarda en $registroDuplicado.
$registroDuplicado = isset($_SESSION['registro_duplicado']) && $_SESSION['registro_duplicado'] === true;

// Obtiene de la sesión los campos que ya están registrados en la base de datos.
// Si 'campos_duplicados' no existe o su valor es null, utiliza un array vacío.
// El resultado se guarda en $camposDuplicados.
$camposDuplicados = $_SESSION['campos_duplicados'] ?? [];

// Elimina la variable de sesión 'registro_duplicado'.
// Evita que el mismo aviso de registro duplicado vuelva a mostrarse cuando se cargue otra vez la página.
unset($_SESSION['registro_duplicado']);

// Elimina la variable de sesión 'campos_duplicados'.
// Evita reutilizar los campos duplicados en posteriores peticiones.
unset($_SESSION['campos_duplicados']);

?>
<!DOCTYPE html>
<html lang="es" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crear cuenta | Tienda de ropa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="estilos/headers.css">
    <link rel="stylesheet" href="estilos/botónregistrarse.css">
</head>

<body class="d-flex flex-column h-100 bg-light">

    <!-- Ya explicado -->
    <?php
    if (
        isset($_SESSION['tipo'], $_SESSION['usuario_id'])
        && $_SESSION['tipo'] === 'registrado'
        && (int) $_SESSION['usuario_id'] > 0
    ) {
        require_once __DIR__ . '/headerregistrado.php';
    } else {
        require_once __DIR__ . '/headerhome.php';
    }
    ?>

    <!-- MAIN: Contenido central extensible -->
    <main class="flex-shrink-0 my-5 w-100">
        <div class="container d-flex flex-column align-items-center">

            <h1 class="fw-bold text-dark text-center mb-4 fs-2">Crear cuenta</h1>

            <?php
            // Comprueba si $registroDuplicado es verdadero. Si lo es, muestra el banner informativo.
            if ($registroDuplicado):
            ?>

                <!-- Contenedor HTML que muestra el aviso de registro duplicado. -->
                <div id="banner-registro">

                    <!-- Muestra el mensaje principal del aviso en negrita. -->
                    <strong>Usuario ya registrado.</strong>

                    <?php
                    // Comprueba si el array $camposDuplicados contiene algún elemento.
                    // Si está vacío, no muestra la lista de datos coincidentes.
                    if (!empty($camposDuplicados)):
                    ?>

                        <!-- Añade dos saltos de línea para separar el mensaje de la explicación. -->
                        <br><br>

                        <?php
                        // Cuenta cuántos campos coinciden con datos ya registrados.
                        // Si solo hay uno, muestra el mensaje en singular.
                        if (count($camposDuplicados) === 1):
                        ?>
                            El dato que coincide es:

                        <?php
                        // Si hay más de un campo coincidente, muestra el mensaje en plural.
                        else:
                        ?>
                            Los datos que coinciden son:
                        <?php endif; ?>

                        <!-- Inicia una lista HTML para mostrar los campos coincidentes. -->
                        <ul>

                            <?php
                            // Recorre el array $camposDuplicados.
                            // En cada vuelta, guarda el campo actual en la variable $campo.
                            foreach ($camposDuplicados as $campo):
                            ?>

                                <!-- Muestra el campo como un elemento de la lista htmlspecialchars() convierte caracteres especiales para evitar que el contenido
                                 se interprete como HTML ENT_QUOTES convierte comillas simples y dobles. UTF-8 indica la codificación utilizada. -->
                                <li><?= htmlspecialchars($campo, ENT_QUOTES, 'UTF-8') ?></li>

                            <?php endforeach; ?>
                            <!-- Finaliza el recorrido de todos los campos coincidentes. -->

                        </ul>


                    <?php endif; ?>
                    <!-- Finaliza la comprobación de si hay campos duplicados. -->

                </div>
                <!-- Cierra el contenedor del aviso. -->

            <?php endif; ?>
            <!-- Finaliza la comprobación de $registroDuplicado. -->

            <div class="form-card">
                <form id="registroForm" action="registro.php" method="POST" autocomplete="off">

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ejemplo: Juan Carlos" pattern="[A-Za-zÁÉÍÓÚáéíóúÜüÑñ ]+" title="Solo letras (incluidas ñ), tildes, diéresis y espacios" required>
                    </div>

                    <div class="mb-3">
                        <label for="primer_apellido" class="form-label fw-bold">Primer apellido:</label>
                        <input type="text" id="primer_apellido" name="primer_apellido" class="form-control" placeholder="Ejemplo: De la Torre" pattern="[A-Za-zÁÉÍÓÚáéíóúÜüÑñ \-]+" title="Solo letras (incluidas ñ), tildes, diéresis, espacios y guiones" required>
                    </div>

                    <div class="mb-3">
                        <label for="segundo_apellido" class="form-label fw-bold">Segundo apellido:</label>
                        <input type="text" id="segundo_apellido" name="segundo_apellido" class="form-control" placeholder="Ejemplo: San Martín" pattern="[A-Za-zÁÉÍÓÚáéíóúÜüÑñ \-]+" title="Solo letras (incluidas ñ), tildes, diéresis, espacios y guiones" required>
                    </div>

                    <div class="mb-3">
                        <label for="usuario" class="form-label fw-bold">Nombre de usuario:</label>
                        <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Ejemplo: juan123" pattern="[A-Za-zÁÉÍÓÚáéíóúÜüÑñ0-9_]{4,20}" title="Entre 4 y 20 caracteres." required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Correo electrónico:</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="usuario@gmail.com" pattern="[a-zA-Z0-9._%+\-]+@(gmail|hotmail|yahoo|outlook|icloud|msn)\.(com|es|org|net)" title="Introduce un correo válido con un dominio conocido (ej. @gmail.com, @hotmail.es, @yahoo.com, ...)" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Contraseña:</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Mínimo 8 caracteres" pattern="(?=.*[A-Za-z])(?=.*\d).{8,}" title="Mínimo 8 caracteres, al menos una letra y un número" required>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirm" class="form-label fw-bold">Repetir contraseña:</label>
                        <input type="password" id="password_confirm" name="password_confirm" class="form-control" placeholder="Repite tu contraseña" required>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_nacimiento" class="form-label fw-bold">Fecha de nacimiento:</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">DNI:</label>
                        <div class="row g-2">
                            <div class="col-8">
                                <input type="text" id="dni_numero" name="dni_numero" class="form-control" placeholder="12345678" pattern="[0-9]{8}" maxlength="8" inputmode="numeric" title="Introduce exactamente 8 números" required>
                            </div>
                            <div class="col-4">
                                <input type="text" id="dni_letra" name="dni_letra" class="form-control" placeholder="Z" pattern="[A-Z]" maxlength="1" title="Introduce la letra del DNI en mayúsculas" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="prefijo" class="form-label fw-bold">Prefijo telefónico:</label>
                        <select id="prefijo" name="prefijo" class="form-select" required>
                            <option value="">Seleccionar prefijo</option>
                            <option value="+34">España (+34)</option>
                            <option value="+33">Francia (+33)</option>
                            <option value="+351">Portugal (+351)</option>
                            <option value="+39">Italia (+39)</option>
                            <option value="+49">Alemania (+49)</option>
                            <option value="+44">Reino Unido (+44)</option>
                            <option value="+54">Argentina (+54)</option>
                            <option value="+52">México (+52)</option>
                            <option value="+57">Colombia (+57)</option>
                            <option value="+56">Chile (+56)</option>
                            <option value="+51">Perú (+51)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label fw-bold">Teléfono:</label>
                        <input type="tel" id="telefono" name="telefono" class="form-control" placeholder="600123456" pattern="[0-9]{9}" inputmode="numeric" title="Introduce 9 números" required>
                    </div>

                    <div class="mb-3">
                        <label for="codigo_postal" class="form-label fw-bold">Código postal:</label>
                        <input type="text" id="codigo_postal" name="codigo_postal" class="form-control" placeholder="22001" pattern="[0-9]{5}" inputmode="numeric" title="Introduce 5 números" required>
                    </div>

                    <div class="mb-3">
                        <label for="nombre_via" class="form-label fw-bold">Nombre de la vía:</label>
                        <input type="text" id="nombre_via" name="nombre_via" class="form-control" placeholder="Ejemplo: Calle 12 de Octubre" pattern="[A-Za-zÁÉÍÓÚáéíóúÜüÑñ0-9 \-\.]+" title="Permite letras, números, espacios, puntos y guiones" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="numero" class="form-label fw-bold">Número:</label>
                            <input type="text" id="numero" name="numero" class="form-control" placeholder="Ejemplo: 12" pattern="[0-9]+" inputmode="numeric" title="Solo se permiten números" required>
                        </div>
                        <div class="col-3">
                            <label for="piso" class="form-label fw-bold">Piso:</label>
                            <input type="text" id="piso" name="piso" class="form-control" placeholder="3º">
                        </div>
                        <div class="col-3">
                            <label for="puerta" class="form-label fw-bold">Puerta:</label>
                            <input type="text" id="puerta" name="puerta" class="form-control" placeholder="B">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="pais" class="form-label fw-bold">País:</label>
                        <select id="pais" name="pais" class="form-select" required>
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
                    </div>

                    <div class="mb-4">
                        <label for="tipo" class="form-label fw-bold">Tipo de dirección:</label>
                        <select id="tipo" name="tipo" class="form-select" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="entrega">Entrega</option>
                            <option value="facturación">Facturación</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-dark-custom w-100 fw-bold">Crear cuenta</button>

                </form>
            </div>

        </div>
    </main>

    <!-- FOOTER: Abajo del todo de ancho completo -->
    <footer class="mt-auto bg-dark text-light py-5 w-100">
        <div class="container">
            <div class="row g-4 justify-content-center text-center">

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

                <div class="col-6 col-md-6 col-lg-3">
                    <h5 class="text-uppercase mb-3">
                        <strong>Enlaces de interés</strong>
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="/webcurso/index.php" class="text-secondary text-decoration-none small text-hover-light">
                                Inicio
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="/webcurso/index.php" class="text-secondary text-decoration-none small text-hover-light">
                                Productos
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-secondary text-decoration-none small text-hover-light">
                                Preguntas frecuentes
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="/webcurso/contacto.php" class="text-secondary text-decoration-none small text-hover-light">
                                Contacto
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-6 col-md-6 col-lg-3">
                    <h5 class="text-uppercase mb-3">
                        <strong>
                            <a href="/webcurso/contacto.php" id="contacto" class="text-decoration-none text-reset">
                                Contacto
                            </a>
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

            <div class="row">
                <div class="col text-center">
                    <p class="text-secondary small mb-0">
                        &copy; 2026 Mi Tienda Streetwear. Todos los derechos reservados.
                    </p>
                </div>
            </div>

        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>