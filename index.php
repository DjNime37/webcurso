<?php
// Inicia una sesión nueva o recupera la sesión existente.
// Permite acceder a las variables almacenadas en $_SESSION, como el tipo de usuario, su identificador o su nombre.
// Por ejemplo, si una página anterior guardó $_SESSION['username'] = 'hacker39', después de ejecutar session_start() otra página puede consultar ese dato
session_start(); // Prepara la sesión para poder acceder a los datos del usuario que están guardados en ella.
// Comprueba si la cookie 'estado_usuario' existe y contiene el valor 'registrado'. Si se cumplen ambas condiciones,
// asigna 'registrado' a $estadoUsuario; en caso contrario, asigna 'no_registrado'.
$estadoUsuario = (
    // 1. Comprueba que la cookie 'estado_usuario' exista y que su valor no sea null.
    isset($_COOKIE['estado_usuario'])
    // 2. Comprueba que el valor de la cookie sea exactamente la cadena de texto 'registrado'. El operador && exige que se cumplan ambas condiciones.
    && $_COOKIE['estado_usuario'] === 'registrado'
    // 3. Evalúa el resultado de las dos condiciones anteriores. El operador ternario ? : permite elegir entre dos valores.
    // Si ambas condiciones son verdaderas, asigna 'registrado'; // Si alguna condición es falsa, asigna 'no_registrado'.
)
    ? 'registrado'
    : 'no_registrado';
// Crea o actualiza la cookie 'estado_usuario' en el navegador.
// Guarda el estado del usuario y establece cuánto tiempo dura, en qué rutas se envía y qué restricciones de seguridad aplica.
setcookie( //recibe tres argumentos:
    // 1. Nombre de la cookie que se guardará en el navegador.
    'estado_usuario',
    // 2. Valor que se almacenará en la cookie. Es una variable que se pasa como argumento a la función setcookie()
    $estadoUsuario, //    Contiene 'registrado' o 'no_registrado', según el valor asignado previamente a $estadoUsuario.
    // 3. Opciones que configuran el comportamiento de la cookie.
    [
        // La cookie caduca dentro de 3600 segundos (1 hora).
        'expires' => time() + 3600,
        // La cookie se enviará en las peticiones a cualquier ruta del sitio web, ya que '/' representa la raíz del sitio.
        'path' => '/',
        // En producción, se recomienda true cuando se utiliza HTTPS.
        'secure' => false, // false permite enviar la cookie tanto por HTTP como por HTTPS.
        'httponly' => false, // false permite que JavaScript pueda acceder a la cookie. Si fuera true, JavaScript no podría leerla directamente.
        // 'Lax' limita el envío de la cookie en ciertas peticiones
        // entre sitios diferentes, ayudando a reducir ataques CSRF.
        'samesite' => 'Lax'
    ]
);
// Comprueba si se ha solicitado cerrar la sesión mediante la dirección URL, por ejemplo: index.php?logout=1.
// Si se ha solicita el cierre de sesión:
// 1. Solicita eliminar la cookie que guarda el estado del usuario.
// 2. Vacía las variables almacenadas en $_SESSION.
// 3. Solicita eliminar la cookie que identifica la sesión PHP.
// 4. Destruye la sesión en el servidor.
// 5. Redirige al usuario a index.php y detiene el código.
if (
    // 1. Comprueba que exista el parámetro 'logout' en la URL.
    isset($_GET['logout'])
    // 2. Comprueba que su valor sea exactamente la cadena '1'. El operador && exige que se cumplan ambas condiciones.
    && $_GET['logout'] === '1'
    // Si ambas condiciones se cumplen, ejecuta el bloque de cierre.
) {
    // 1. SOLICITAR LA ELIMINACIÓN DE LA COOKIE DE ESTADO
    setcookie( //envía instrucciones al navegador para modificar o eliminar una cookie. No la borra directamente del servidor.
        'estado_usuario',  // Nombre de la cookie que se quiere eliminar.
        'no_registrado', // Valor que se asigna en esta petición. Como la cookie va a caducar, este valor no se conserva.
        // Opciones que determinan la caducidad y el comportamiento.
        [
            'expires' => time() - 3600, // time() devuelve la hora actual en formato timestamp.
            // La ruta debe coincidir con la utilizada al crearla.
            // '/' permite que la cookie se aplique a todo el sitio.
            'path' => '/',
            // false permite enviar la cookie por HTTP y HTTPS.
            'secure' => false,
            'httponly' => false, // false permite que JavaScript acceda a la cookie.
            // Limita el envío de la cookie en ciertas peticiones entre sitios diferentes.
            'samesite' => 'Lax'
        ]
    );
    // 2. VACIAR LAS VARIABLES DE LA SESIÓN
    $_SESSION = []; // contiene los datos de la sesión, como el nombre de usuario, su identificador o su tipo: vacía lo que había antes.
    // 3. COMPROBAR SI PHP UTILIZA COOKIES DE SESIÓN: si el resultado es verdadero, se ejecuta el bloque siguiente.
    if (ini_get('session.use_cookies')) { // Comprueba si la sesión utiliza cookies para transmitir su identificador al navegador.
        // Recupera la configuración de la cookie de sesión actual: la opción secure y la opción httponly, entre otros datos.
        $params = session_get_cookie_params(); // Puede contener la ruta, el dominio,
        // 4. SOLICITAR LA ELIMINACIÓN DE LA COOKIE DE SESIÓN
        // El tercer argumento contiene las opciones de la cookie.
        setcookie(
            session_name(), // session_name() devuelve el nombre de la cookie de sesión configurada en PHP, que normalmente es 'PHPSESSID'.
            //Devolver= obtener un resultado y se lo entrega al código que la ha llamado para que pueda utilizarlo.
            '', // establece un valor vacío.
            [
                // Establece una fecha pasada para solicitar que la cookie de sesión caduque.
                'expires' => time() - 42000,
                // Reutiliza la ruta de la cookie original.
                'path' => $params['path'], //de todo lo almacenado en $params accede al valor asociado a la clave 'path' dentro del array $params
                // y $params contiene las opciones de configuración de la cookie de sesión, obtenidas anteriormente mediante session_get_cookie_params()
                // Reutiliza el dominio de la cookie original.
                'domain' => $params['domain'],
                // Mantiene la configuración original de HTTPS.
                'secure' => $params['secure'],
                // Mantiene la configuración original de httponly.
                'httponly' => $params['httponly'],
                // Si no existe, utiliza 'Lax' como valor alternativo.
                // El operador ?? utiliza 'Lax' si la clave 'samesite' no existe o su valor es null.
                'samesite' => $params['samesite'] ?? 'Lax'
            ]
        );
    }
    // 5. DESTRUIR LA SESIÓN EN EL SERVIDOR
    // Elimina los datos asociados a la sesión actual.
    // Se diferencia de $_SESSION = [] porque aquí se solicita destruir la sesión almacenada en el servidor.
    // No elimina productos ni pedidos guardados en MySQL.
    session_destroy();
    // 6. REDIRIGIR AL INICIO Y DETENER LA EJECUCIÓN
    // header() envía una cabecera HTTP que indica al navegador que debe dirigirse a index.php.
    header('Location: index.php');
    // Detiene inmediatamente la ejecución del archivo PHP. Así se evita que continúe ejecutándose el código posterior.
    exit;
}
// ============================================================
// 5. SELECCIONAR LA CABECERA QUE SE MOSTRARÁ
// ============================================================
// Comprueba el valor de la cookie recibida en esta petición.
// Si existe y contiene exactamente 'registrado', incluye la cabecera destinada a usuarios registrados; En cualquier otro caso, incluye la cabecera de inicio.
if (
    // 1. Comprueba que la cookie 'estado_usuario' exista y que su valor no sea null.
    isset($_COOKIE['estado_usuario'])
    // 2. Comprueba que el valor de la cookie sea exactamente la cadena de texto 'registrado'.
    && $_COOKIE['estado_usuario'] === 'registrado'
    // 3. Si ambas condiciones se cumplen, ejecuta el bloque del if.
) {
    include 'headerregistrado.php';
} else {
    // Incluye y ejecuta headerhome.php, que contiene la cabecera
    // destinada a usuarios no registrados.
    include 'headerhome.php';
}
// RESUMEN DEL FUNCIONAMIENTO
// 1. Inicia o recupera la sesión PHP.
// 2. Lee la cookie 'estado_usuario'.
// 3. Renueva la cookie con una caducidad de una hora.
// 4. Si recibe logout=1, solicita eliminar las cookies, vacía las variables de sesión y destruye la sesión.
// 5. Redirige al inicio cuando se cierra la sesión.
// 6. Si no se ha cerrado la sesión, incluye la cabecera registrada o la cabecera de inicio según la cookie.
?>
<!DOCTYPE html>
<html lang="es-ES">
<head>
    <meta charset="utf-8">
    <meta name="description" content="Tienda de ropa online">
    <!-- Las letras (título) que se ve en la pestaña del navegador -->
    <meta name="author" content="Hacker39">
    <!-- Es información para el navegador y otros sistemas, no contenido visual. -->
    <!-- Indica qué palabras clave están relacionadas con el contenido de la página. -->
    <meta name="keywords" content="ropa, moda, camisetas, chaquetas, zapatillas, streetwear">
    <!-- Sirve para que la página se adapte correctamente a móviles, tablets y ordenadores. -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Esto hace que Bootstrap esté disponible y que funcionen sus clases. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="estilos/estilos.css">
    <title>Tienda de ropa online</title>
    <!-- Para usar el icono del carrito -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <!-- BANNERS PUBLICITARIOS -->
    <div id="banner-nike" class="banner-publicidad">
        <button class="cerrar-banner" type="button" aria-label="Cerrar publicidad">×</button>
        <!-- aria-label: cuando el elemento no tiene texto visible que explique su función. -->
        <img src="imagenes/nike.jpg" alt="Publicidad Nike">
    </div>
    <div id="banner-adidas" class="banner-publicidad banner-segundo">
        <button class="cerrar-banner" type="button" aria-label="Cerrar publicidad">×</button>
        <img src="imagenes/adidas.jpg" alt="Publicidad Adidas">
    </div>
    <!-- CONTENIDO PRINCIPAL -->
    <main class="content-container mt-4">
        <!-- Contenedor del carrusel de imágenes -->
        <div id="carouselExampleIndicators" class="carousel slide mb-4" data-bs-ride="carousel" data-bs-interval="5000">
            <!-- Carrosussel de imágenes -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Diapositiva 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                    aria-label="Diapositiva 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                    aria-label="Diapositiva 3"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3"
                    aria-label="Diapositiva 4"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4"
                    aria-label="Diapositiva 5"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="5"
                    aria-label="Diapositiva 6"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="6"
                    aria-label="Diapositiva 7"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="7"
                    aria-label="Diapositiva 8"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="8"
                    aria-label="Diapositiva 9"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="9"
                    aria-label="Diapositiva 10"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="10"
                    aria-label="Diapositiva 11"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="11"
                    aria-label="Diapositiva 12"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="12"
                    aria-label="Diapositiva 13"></button>
            </div>
            <!-- Contenedor de imágenes del carrusel -->
            <div class="carousel-inner rounded-3 shadow-sm">
                <!-- CHÁNDALS -->
                <div class="carousel-item active">
                    <img src="imagenes/chandals/chandalazul.jpeg" class="d-block w-100" alt="Chándal azul">
                </div>
                <div class="carousel-item">
                    <img src="imagenes/chandals/chandalrojo.jpeg" class="d-block w-100" alt="Chándal rojo">
                </div>
                <div class="carousel-item">
                    <img src="imagenes/chandals/chandalgris.jpeg" class="d-block w-100" alt="Chándal gris">
                </div>
                <!-- DEPORTIVAS -->
                <div class="carousel-item">
                    <img src="imagenes/deportivas/nikes.jpeg" class="d-block w-100" alt="Deportivas Nike">
                </div>
                <div class="carousel-item">
                    <img src="imagenes/deportivas/sketches.jpeg" class="d-block w-100" alt="Deportivas Skechers">
                </div>
                <div class="carousel-item">
                    <img src="imagenes/deportivas/zapatillas.jpeg" class="d-block w-100" alt="Zapatillas deportivas">
                </div>
                <div class="carousel-item">
                    <img src="imagenes/deportivas/unas.jpeg" class="d-block w-100" alt="Zapatillas deportivas Pro">
                </div>
                <!-- MP4 -->
                <div class="carousel-item">
                    <img src="imagenes/mp4/azul.jpeg" class="d-block w-100" alt="Reproductor MP4 azul">
                </div>
                <div class="carousel-item">
                    <img src="imagenes/mp4/negro.jpeg" class="d-block w-100" alt="Reproductor MP4 negro">
                </div>
                <div class="carousel-item">
                    <img src="imagenes/mp4/wiki.jpeg" class="d-block w-100" alt="Reproductor MP4 multimedia">
                </div>
                <!-- TÉRMICAS -->
                <div class="carousel-item">
                    <img src="imagenes/termicas/azul.jpeg" class="d-block w-100" alt="Ropa térmica azul">
                </div>
                <div class="carousel-item">
                    <img src="imagenes/termicas/gris.jpeg" class="d-block w-100" alt="Ropa térmica gris">
                </div>
                <div class="carousel-item">
                    <img src="imagenes/termicas/negra.jpeg" class="d-block w-100" alt="Ropa térmica negra">
                </div>
            </div>
            <!-- CONTROLES DEL CARRUSEL -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
        <!-- CONTENEDOR DE LA SECCIÓN DE PRODUCTOS -->
        <div class="container my-5">
            <h2 class="text-center mb-4">Nuestros productos</h2>
            <div class="row">
                <!-- PRODUCTO 1 -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="imagenes/chandals/chandalazul.jpeg" class="card-img-top p-3 rounded-5"
                            alt="Chándal azul">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><strong>Chándal deportivo azul</strong></h5>
                            <p class="card-text text-muted small">
                                Chándal cómodo y ligero para deporte y uso diario.
                            </p> <!-- Lo que se toca para hacer la compra -->
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
                                <h5 class="mt-auto text-primary">49,20 €</h5>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <button type="button" class="btn btn-outline-secondary btn-restar">−</button>
                                    <input type="number" name="cantidad"
                                        class="form-control text-center cantidad-producto" value="1" min="1"
                                        style="width: 70px;">
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
                        <img src="imagenes/chandals/chandalrojo.jpeg" class="card-img-top p-3 rounded-5"
                            alt="Chándal rojo">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><strong>Chándal deportivo rojo</strong></h5>
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
                                <h5 class="mt-auto text-primary">56,40 €</h5>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <button type="button" class="btn btn-outline-secondary btn-restar">−</button>
                                    <input type="number" name="cantidad"
                                        class="form-control text-center cantidad-producto" value="1" min="1"
                                        style="width: 70px;">
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
                        <img src="imagenes/chandals/chandalgris.jpeg" class="card-img-top p-3 rounded-5"
                            alt="Chándal gris">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><strong>Chándal deportivo gris</strong></h5>
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
                                <h5 class="mt-auto text-primary">49,95 €</h5>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <button type="button" class="btn btn-outline-secondary btn-restar">−</button>
                                    <input type="number" name="cantidad"
                                        class="form-control text-center cantidad-producto" value="1" min="1"
                                        style="width: 70px;">
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
                        <img src="imagenes/deportivas/nikes.jpeg" class="card-img-top p-3 rounded-5"
                            alt="Deportivas Nike">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><strong>Deportivas Nike</strong></h5>
                            <p class="card-text text-muted small">
                                Zapatillas deportivas cómodas para entrenamiento y uso diario.
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
                                <h5 class="mt-auto text-primary">79,90 €</h5>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <button type="button" class="btn btn-outline-secondary btn-restar">−</button>
                                    <input type="number" name="cantidad"
                                        class="form-control text-center cantidad-producto" value="1" min="1"
                                        style="width: 70px;">
                                    <button type="button" class="btn btn-outline-secondary btn-sumar">+</button>
                                </div>
                                <button type="submit" class="btn btn-primary btn-comprar" disabled>
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- PRODUCTO 5 -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="imagenes/deportivas/sketches.jpeg" class="card-img-top p-3 rounded-5"
                            alt="Deportivas Skechers">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><strong>Deportivas Skechers</strong></h5>
                            <p class="card-text text-muted small">
                                Diseño ligero con gran comodidad para el día a día.
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
                                <h5 class="mt-auto text-primary">69,90 €</h5>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <button type="button" class="btn btn-outline-secondary btn-restar">−</button>
                                    <input type="number" name="cantidad"
                                        class="form-control text-center cantidad-producto" value="1" min="1"
                                        style="width: 70px;">
                                    <button type="button" class="btn btn-outline-secondary btn-sumar">+</button>
                                </div>
                                <button type="submit" class="btn btn-primary btn-comprar" disabled>
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- PRODUCTO 6 -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="imagenes/deportivas/zapatillas.jpeg" class="card-img-top p-3 rounded-5"
                            alt="Zapatillas Streetwear">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><strong>Zapatillas Streetwear</strong></h5>
                            <p class="card-text text-muted small">
                                Diseño moderno y cómodo para uso diario.
                            </p>
                            <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                                <input type="hidden" name="id" value="6">
                                <label for="talla-streetwear" class="form-label">
                                    Talla:
                                </label>
                                <select id="talla-streetwear" name="talla" class="form-select mb-3">
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
                                <h5 class="mt-auto text-primary">74,10 €</h5>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <button type="button" class="btn btn-outline-secondary btn-restar">−</button>
                                    <input type="number" name="cantidad"
                                        class="form-control text-center cantidad-producto" value="1" min="1"
                                        style="width: 70px;">
                                    <button type="button" class="btn btn-outline-secondary btn-sumar">+</button>
                                </div>
                                <button type="submit" class="btn btn-primary btn-comprar" disabled>
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- PRODUCTO 7 -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="imagenes/deportivas/unas.jpeg" class="card-img-top p-3 rounded-5"
                            alt="Zapatillas deportivas Pro">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><strong>Zapatillas deportivas Pro</strong></h5>
                            <p class="card-text text-muted small">
                                Modelo deportivo con diseño moderno y confortable.
                            </p>
                            <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                                <input type="hidden" name="id" value="7">
                                <label for="talla-pro" class="form-label">
                                    Talla:
                                </label>
                                <select id="talla-pro" name="talla" class="form-select mb-3">
                                    <option value="">Selecciona una talla</option>
                                    <option value="39">39</option>
                                    <option value="40">40</option>
                                    <option value="41">41</option>
                                    <option value="42">42</option>
                                    <option value="43">43</option>
                                    <option value="44">44</option>
                                    <option value="45">45</option>
                                </select>
                                <h5 class="mt-auto text-primary">84,90 €</h5>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <button type="button" class="btn btn-outline-secondary btn-restar">−</button>
                                    <input type="number" name="cantidad"
                                        class="form-control text-center cantidad-producto" value="1" min="1"
                                        style="width: 70px;">
                                    <button type="button" class="btn btn-outline-secondary btn-sumar">+</button>
                                </div>
                                <button type="submit" class="btn btn-primary btn-comprar" disabled>
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- PRODUCTO 8 -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <img src="imagenes/mp4/azul.jpeg" class="card-img-top p-3 rounded-5" alt="Reproductor MP4 azul">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><strong>Reproductor MP4 azul</strong></h5>
                        <p class="card-text text-muted small">
                            Reproductor multimedia compacto y portátil.<br>
                            Capacidad: 16 GB
                        </p>
                        <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                            <input type="hidden" name="id" value="8">
                            <h5 class="mt-auto text-primary">35,75 €</h5>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <button type="button" class="btn btn-outline-secondary btn-restar">−</button>
                                <input type="number" name="cantidad" class="form-control text-center cantidad-producto"
                                    value="1" min="1" style="width: 70px;">
                                <button type="button" class="btn btn-outline-secondary btn-sumar">+</button>
                            </div>
                            <button type="submit" class="btn btn-primary btn-comprar">
                                Comprar
                            </button>
                        </form>
                    </div>
                </div>
                <!-- PRODUCTO 9 -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="imagenes/mp4/negro.jpeg" class="card-img-top p-3 rounded-5"
                            alt="Reproductor MP4 negro">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><strong>Reproductor MP4 negro</strong></h5>
                            <p class="card-text text-muted small">
                                Reproductor multimedia portátil con diseño elegante.<br>
                                Capacidad: 16 GB
                            </p>
                            <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                                <input type="hidden" name="id" value="9">
                                <h5 class="mt-auto text-primary">44,00 €</h5>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <button type="button" class="btn btn-outline-secondary btn-restar">−</button>
                                    <input type="number" name="cantidad"
                                        class="form-control text-center cantidad-producto" value="1" min="1"
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
                        <img src="imagenes/mp4/wiki.jpeg" class="card-img-top p-3 rounded-5"
                            alt="Reproductor MP4 multimedia">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><strong>Reproductor MP4 multimedia</strong></h5>
                            <p class="card-text text-muted small">
                                Reproductor compacto para música y vídeo.<br>
                                Capacidad: 32 GB
                            </p>
                            <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                                <input type="hidden" name="id" value="10">
                                <h5 class="mt-auto text-primary">49,90 €</h5>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <button type="button" class="btn btn-outline-secondary btn-restar">−</button>
                                    <input type="number" name="cantidad"
                                        class="form-control text-center cantidad-producto" value="1" min="1"
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
                <!-- PRODUCTO 11 -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="imagenes/termicas/azul.jpeg" class="card-img-top p-3 rounded-5"
                            alt="Ropa térmica azul">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><strong>Ropa térmica azul</strong></h5>
                            <p class="card-text text-muted small">
                                Prenda térmica diseñada para conservar el calor corporal.
                            </p>
                            <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                                <input type="hidden" name="id" value="11">
                                <label for="talla-termica-azul" class="form-label">
                                    Talla:
                                </label>
                                <select id="talla-termica-azul" name="talla" class="form-select mb-3">
                                    <option value="">Selecciona una talla</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                </select>
                                <h5 class="mt-auto text-primary">29,95 €</h5>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <button type="button" class="btn btn-outline-secondary btn-restar">−</button>
                                    <input type="number" name="cantidad"
                                        class="form-control text-center cantidad-producto" value="1" min="1"
                                        style="width: 70px;">
                                    <button type="button" class="btn btn-outline-secondary btn-sumar">+</button>
                                </div>
                                <button type="submit" class="btn btn-primary btn-comprar" disabled>
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- PRODUCTO 12 -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="imagenes/termicas/gris.jpeg" class="card-img-top p-3 rounded-5"
                            alt="Ropa térmica gris">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><strong>Ropa térmica gris</strong></h5>
                            <p class="card-text text-muted small">
                                Tejido térmico cómodo para días fríos.
                            </p>
                            <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                                <input type="hidden" name="id" value="12">
                                <label for="talla-termica-gris" class="form-label">
                                    Talla:
                                </label>
                                <select id="talla-termica-gris" name="talla" class="form-select mb-3">
                                    <option value="">Selecciona una talla</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                </select>
                                <h5 class="mt-auto text-primary">29,95 €</h5>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <button type="button" class="btn btn-outline-secondary btn-restar">−</button>
                                    <input type="number" name="cantidad"
                                        class="form-control text-center cantidad-producto" value="1" min="1"
                                        style="width: 70px;">
                                    <button type="button" class="btn btn-outline-secondary btn-sumar">+</button>
                                </div>
                                <button type="submit" class="btn btn-primary btn-comprar" disabled>
                                    Comprar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- PRODUCTO 13 -->
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="imagenes/termicas/negra.jpeg" class="card-img-top p-3 rounded-5"
                            alt="Ropa térmica negra">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><strong>Ropa térmica negra</strong></h5>
                            <p class="card-text text-muted small">
                                Prenda térmica ligera y confortable para invierno.
                            </p>
                            <form action="controlador/carritocontroller.php" method="POST" target="_blank">
                                <input type="hidden" name="id" value="13">
                                <label for="talla-termica-negra" class="form-label">
                                    Talla:
                                </label>
                                <select id="talla-termica-negra" name="talla" class="form-select mb-3">
                                    <option value="">Selecciona una talla</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                </select>
                                <h5 class="mt-auto text-primary">29,95 €</h5>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <button type="button" class="btn btn-outline-secondary btn-restar">−</button>
                                    <input type="number" name="cantidad"
                                        class="form-control text-center cantidad-producto" value="1" min="1"
                                        style="width: 70px;">
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
            <!-- PREGUNTAS FRECUENTES -->
            <div class="status-notice">
                <div class="container my-5">
                    <h2 class="text-center mb-4">
                        Preguntas frecuentes
                    </h2>
                    <div class="accordion" id="accordionFAQ">
                        <!-- FAQ 1 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <strong>
                                        ¿Cuáles son los métodos de pago disponibles?
                                    </strong>
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show"
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
                        <!-- FAQ 2 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <strong>
                                        ¿Cuánto tarda en llegar mi pedido?
                                    </strong>
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
                                <div class="accordion-body">
                                    Los envíos estándar a toda la península
                                    tardan entre 24 y 48 horas laborables.
                                    Si realizas tu pedido antes de las 14:00,
                                    saldrá de nuestro almacén el mismo día.
                                </div>
                            </div>
                        </div>
                        <!-- FAQ 3 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    <strong>
                                        ¿Cómo puedo realizar una devolución?
                                    </strong>
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFAQ">
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
    </main>
    <!-- Agregamos text-center en la fila para centrar todo el contenido -->
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
    <!-- JS Bootstrap y Script principal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- BOTONES FLOTANTES DE NAVEGACIÓN -->
    <div class="flechas-navegacion">
        <button id="btn-subir" class="btn-flecha" type="button" title="Ir arriba" aria-label="Ir arriba">
            ↑
        </button>
        <button id="btn-bajar" class="btn-flecha" type="button" title="Ir abajo" aria-label="Ir abajo">
            ↓
        </button>
    </div>
    <script src="main.js"></script>
</body>
</html>