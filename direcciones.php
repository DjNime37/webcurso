<?php
// Comprueba si todavía no se ha iniciado una sesión PHP.
if (session_status() === PHP_SESSION_NONE) {
    // Inicia una sesión nueva o recupera la sesión existente.
    session_start();
}
// Comprueba si no existe la variable 'tipo' en la sesión o si su valor es distinto de 'registrado'= Si no...
if (
    !isset($_SESSION['tipo']) || // !: significa «NO»; invierte el resultado de una condición.
    $_SESSION['tipo'] !== 'registrado'
) {
    // Redirige al usuario a la página de inicio de sesión si no cumple la condición de usuario registrado.
    header('Location: iniciosesión.php');
    // Detiene la ejecución del script para evitar que se siga ejecutando el código después de la redirección.
    exit;
}
// Comprueba si no existe el identificador del usuario en la sesión.
if (!isset($_SESSION['usuario_id'])) {
    // Redirige a la página principal si falta el identificador.
    header('Location: index.php');
    // Detiene la ejecución del script.
    exit;
}
// Carga el archivo que contiene la clase Direcciones, encargada de gestionar las operaciones relacionadas con las direcciones.
// __DIR__ representa la ruta del directorio del archivo actual.
require_once __DIR__ . '/modelo/direcciones.php'; // require_once evita incluir de nuevo este archivo si ya se ha incluido.
// Inicializa la variable que contendrá la dirección que se desea modificar.
$direccionEditar = null; // null indica que todavía no se ha seleccionado ninguna dirección.
// Inicializa un array vacío para almacenar las direcciones del usuario.
$direcciones = [];
// Inicializa un array vacío para almacenar el historial de direcciones.
$historialDirecciones = [];
// Recupera de la sesión el mensaje relacionado con las direcciones.
// Si no existe o su valor es null, asigna null a la variable.
$mensajeDireccion = $_SESSION['mensaje_direccion'] ?? null;
// Elimina el mensaje de la sesión para que no vuelva a mostrarse automáticamente en las siguientes peticiones.
unset($_SESSION['mensaje_direccion']);
// Inicia un bloque que permite capturar excepciones si se produce un error durante las operaciones incluidas en él.
try {
    // Crea una conexión PDO con la base de datos MySQL.
    // host=localhost indica que MySQL está en el equipo local.
    // dbname=tienda_ropa indica la base de datos que se utilizará.
    // charset=utf8mb4 establece el conjunto de caracteres de la conexión.
    // 'root' es el usuario de MySQL.
    // '' indica que la contraseña está vacía.
    $pdo = new PDO(
        'mysql:host=localhost;dbname=tienda_ropa;charset=utf8mb4',
        'root',
        ''
    );
    // Configura PDO para que genere excepciones cuando se produzcan errores en las operaciones compatibles con este modo.
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );
    // Crea un objeto de la clase Direcciones y le entrega la conexión PDO.
    // Así, el modelo puede utilizar la conexión para consultar o modificar las direcciones almacenadas en la base de datos.
    $direccionesModelo = new Direcciones($pdo);
    // Recupera el identificador del usuario desde la sesión
    // y lo convierte a un número entero.
    $usuarioId = (int) $_SESSION['usuario_id'];
    /*
     * COMPROBACIÓN DE SI SE HA PULSADO «MODIFICAR».
     * Se comprueba si la URL contiene el parámetro 'modificar'.
     * Por ejemplo: direcciones.php?modificar=3
     */
    if (isset($_GET['modificar'])) {
        // Obtiene el identificador de la dirección desde la URL y lo convierte a un número entero.
        $direccionId = (int) $_GET['modificar']; // =que dirección quiero cambiar; ej direcciones.php?modificar=3
        // Comprueba que el identificador de la dirección sea mayor que cero.
        if ($direccionId > 0) {
            // Solicita al modelo la dirección indicada =modelo/direcciones.
            // Se pasan tanto el identificador de la dirección como el identificador del usuario.
            // El modelo debe comprobar que la dirección pertenece a ese usuario.
            $direccionEditar = $direccionesModelo->obtenerDireccion( // Llama al método obtenerDirecciones() del modelo Direcciones.
                $direccionId,
                $usuarioId
            );
            // Comprueba si el modelo no ha encontrado una dirección válida. También puede ocurrir si la dirección no pertenece al usuario.
            if (!$direccionEditar) {
                // Guarda un mensaje de error para mostrarlo en la página.
                $mensajeDireccion = [
                    'tipo' => 'danger', // 'tipo' identifica la categoría visual del mensaje.
                    'texto' => 'La dirección indicada no existe o no pertenece a tu usuario.' // 'texto' contiene el mensaje que se mostrará al usuario.
                ];
            }
        }
    }
    /*
 * OBTENER TODAS LAS DIRECCIONES
 * Solicita al modelo las direcciones asociadas al usuario actual.
 */
    // Guarda el resultado devuelto por el método en la variable $direcciones.
    // Obtiene las direcciones del usuario.
    $direcciones = $direccionesModelo->obtenerDirecciones(
        $usuarioId
    );
    // Comprueba si ya existe una dirección de entrega.
    $existeEntrega = false;
    foreach ($direcciones as $direccion) {
        $tipoDireccion = strtolower(
            trim((string) ($direccion['tipo'] ?? ''))
        );
        if ($tipoDireccion === 'entrega') {
            $existeEntrega = true;
            break;
        }
    }
    /*
 * OBTENER HISTORIAL
 * Recupera el historial de cada dirección del usuario.
 */
    // En cada iteración, la variable $direccion contiene una dirección.
    foreach ($direcciones as $direccion) { // Recorre todas las direcciones almacenadas en $direcciones.
        // Guarda el historial devuelto, en $historialDirecciones.
        $historialDirecciones[$direccion['id']] = // Utiliza el identificador de la dirección como clave del array $historialDirecciones para asociar
            // cada dirección con su historial.
            // Obtiene el historial correspondiente a esta dirección y al usuario indicado llamando a obtenerHistorial del modelo.
            $direccionesModelo->obtenerHistorial(
                (int) $direccion['id'], // Convierte el identificador de la dirección a un número entero y pasa también el identificador del usuario
                // para identificar al propietario cuyo historial se quiere consultar.
                $usuarioId
            );
    }
    // Captura las excepciones PDO que se produzcan en el bloque try.
} catch (PDOException $e) {
    // Detiene la ejecución y muestra el error técnico dentro de <pre>, que conserva los espacios y saltos de línea del texto.
    // htmlspecialchars() convierte caracteres especiales en entidades HTML para evitar que el mensaje se interprete como código HTML.
    // ENT_QUOTES convierte las comillas simples y dobles. Es una medida de protección al mostrar texto dentro de HTML.
    // UTF-8 especifica la codificación del texto.
    die('<pre>' .
        htmlspecialchars(
            $e->getMessage(),
            ENT_QUOTES,
            'UTF-8'
        ) .
        '</pre>');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">
    <meta
        name="description"
        content="Mis direcciones">
    <meta
        name="author"
        content="Usuario">
    <title>Mis direcciones</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link
        rel="stylesheet"
        href="estilos/estilos.css">
</head>

<body>
    <?php include 'headerregistrado.php'; ?> <!-- El suario ha iniciado sesión -->
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="text-center mb-4">
                    Mis direcciones
                </h1>
                <!-- MENSAJES -->
                <?php
                // Comprueba si $mensajeDireccion contiene un valor verdadero. Si existe un mensaje, se muestra el bloque HTML siguiente.
                if ($mensajeDireccion):
                ?>
                    <!-- Contenedor del mensaje de aviso de Bootstrap.
         La clase alert crea el cuadro del aviso. -->
                    <div
                        class="alert alert-<?php echo htmlspecialchars( // mostrar un texto en la página web de forma segura.
                                                //echo: muestra el resultado en la página HTML.
                                                // Sin htmlspecialchars(), el navegador podría interpretar la etiqueta <script> y ejecutar su contenido.
                                                // El navegador muestra literalmente el contenido.
                                                $mensajeDireccion['tipo'], // Obtiene el tipo de aviso, por ejemplo 'success' o 'danger'.
                                                // Convierte las comillas simples y dobles en entidades HTML.
                                                ENT_QUOTES,
                                                // Indica que el texto utiliza la codificación UTF-8.
                                                'UTF-8'
                                            ); ?> alert-dismissible fade show"
                        role="alert">
                        <?php
                        // Obtiene el texto del mensaje que se va a mostrar al usuario.
                        echo htmlspecialchars(
                            $mensajeDireccion['texto'], // Obtiene el texto del mensaje que quieres mostrar.
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>
                        <!-- type="button" indica que el botón no envía el formulario. Cierra el formulario-->
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"> <!--permite cerrar el aviso mediante JavaScript de Bootstrap. -->
                        </button>
                    </div>
                <?php endif; ?> <!-- Finaliza la condición: si no hay mensaje, no se muestra el aviso. -->
                <!-- FORMULARIO: contenedor visual que agrupa los formularios para modificar una dirección existente o añadir una nueva. -->
                <div class="card shadow-sm mb-5">
                    <!-- Cuerpo de la tarjeta donde se muestra el formulario. -->
                    <div class="card-body">
                        <?php
                        // Comprueba si $direccionEditar contiene una dirección existente a modificar.
                        // Si existe una dirección válida, muestra el formulario de modificación.
                        // Si no existe, se mostrará el formulario para añadir una nueva dirección.
                        if ($direccionEditar):
                        ?>
                            <!-- FORMULARIO PARA MODIFICAR UNA DIRECCIÓN YA EXISTENTE. -->
                            <!-- Título que identifica el formulario de modificación. -->
                            <h2 class="h4 mb-4">
                                Modificar dirección
                            </h2>
                            <!-- Envía los datos al controlador de direcciones mediante POST. -->
                            <form
                                action="controlador/direccionescontroller.php"
                                method="POST">
                                <!-- Indica al controlador que la operación solicitada es modificar. -->
                                <input
                                    type="hidden"
                                    name="accion"
                                    value="modificar">
                                <!-- Envía el identificador de la dirección que se va a modificar.
                     El cast (int) convierte el valor a un número entero. -->
                                <input
                                    type="hidden"
                                    name="direccion_id"
                                    value="<?php echo (int) $direccionEditar['id']; ?>">
                                <!-- CÓDIGO POSTAL: permite consultar y modificar el código postal de la dirección seleccionada. -->
                                <div class="mb-3">
                                    <!-- Asocia la etiqueta con el campo codigo_postal. -->
                                    <label
                                        for="codigo_postal"
                                        class="form-label">
                                        Código postal:
                                    </label>
                                    <!-- Campo obligatorio de cinco dígitos; inputmode="numeric" sugiere un teclado numérico en dispositivos compatibles -->
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="codigo_postal"
                                        name="codigo_postal"
                                        placeholder="22001"
                                        pattern="[0-9]{5}"
                                        inputmode="numeric"
                                        title="Introduce 5 números"
                                        <!-- Establece el valor inicial del campo del código postal. -->
                                    value="<?php echo htmlspecialchars(
                                                $direccionEditar['codigo_postal'] ?? '',
                                                // Obtiene el código postal de la dirección que se está modificando.
                                                // Si no existe o su valor es null, utiliza una cadena vacía ('').
                                                ENT_QUOTES,
                                                // Convierte las comillas simples y dobles en entidades HTML
                                                // para evitar que interfieran con el atributo HTML value.
                                                'UTF-8'
                                                // Especifica la codificación de caracteres para interpretar
                                                // correctamente las tildes, la ñ y otros caracteres especiales.
                                            ); ?>"
                                    required>
                                    <!-- Obliga a rellenar el campo antes de poder enviar el formulario. -->
                                </div>
                                <!-- NOMBRE DE LA VÍA: permite consultar y modificar el nombre de la calle, avenida u otra vía. -->
                                <div class="mb-3">
                                    <!-- Etiqueta del campo nombre_via. -->
                                    <label
                                        for="nombre_via"
                                        class="form-label">
                                        Nombre de la vía:
                                    </label>
                                    <!-- Campo obligatorio que limita los caracteres mediante pattern en la validación del navegador. -->
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="nombre_via"
                                        name="nombre_via"
                                        placeholder="Ejemplo: Calle 12 de Octubre"
                                        pattern="[A-Za-zÁÉÍÓÚáéíóúÜüÑñ0-9 \-\.]+"
                                        title="Permite letras, números, espacios, puntos y guiones"
                                        value="<?php echo htmlspecialchars(
                                                    $direccionEditar['nombre_via'] ?? '', // Obtiene el nombre de la vía de la dirección que se está modificando.
                                                    // Si el índice 'nombre_via' no existe o su valor es null, utiliza una cadena vacía ('') como valor predeterminado.
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ); ?>"
                                        required>
                                </div>
                                <!-- NÚMERO: permite consultar y modificar el número del inmueble. -->
                                <div class="mb-3">
                                    <!-- Etiqueta del campo numero. -->
                                    <label
                                        for="numero"
                                        class="form-label">
                                        Número:
                                    </label>
                                    <!-- Campo obligatorio que admite únicamente dígitos según el patrón de validación del navegador. -->
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="numero"
                                        name="numero"
                                        placeholder="Ejemplo: 12"
                                        pattern="[0-9]+"
                                        inputmode="numeric"
                                        title="Solo se permiten números"
                                        value="<?php echo htmlspecialchars(
                                                    $direccionEditar['numero'] ?? '',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ); ?>"
                                        required>
                                </div>
                                <!-- PISO: permite consultar y modificar el piso. Es opcional porque no todos los inmuebles tienen uno. -->
                                <div class="mb-3">
                                    <label
                                        for="piso"
                                        class="form-label">
                                        Piso:
                                    </label>
                                    <!-- Muestra el piso actual o una cadena vacía si no existe. -->
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="piso"
                                        name="piso"
                                        placeholder="Ejemplo: 3º"
                                        value="<?php echo htmlspecialchars(
                                                    $direccionEditar['piso'] ?? '',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ); ?>">
                                </div>
                                <!-- PUERTA: permite consultar y modificar la puerta. Este campo también es opcional. -->
                                <div class="mb-3">
                                    <label
                                        for="puerta"
                                        class="form-label">
                                        Puerta:
                                    </label>
                                    <!-- Muestra la puerta actual o una cadena vacía si no existe. -->
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="puerta"
                                        name="puerta"
                                        placeholder="Ejemplo: B"
                                        value="<?php echo htmlspecialchars(
                                                    $direccionEditar['puerta'] ?? '',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ); ?>">
                                </div>
                                <!-- PAÍS: permite seleccionar el país asociado a la dirección. -->
                                <div class="mb-3">
                                    <label
                                        for="pais"
                                        class="form-label">
                                        País:
                                    </label>
                                    <!-- Lista desplegable que muestra el país de la dirección y permite seleccionar otro país. -->
                                    <select
                                        class="form-select"
                                        id="pais"
                                        name="pais"
                                        required>
                                        <!-- Opción inicial que se utiliza si no hay país seleccionado. -->
                                        <option value="">
                                            Seleccionar país
                                        </option>
                                        <?php
                                        // Define el array de países disponibles en el selector.
                                        $paises = [
                                            'España',
                                            'Francia',
                                            'Portugal',
                                            'Italia',
                                            'Alemania',
                                            'Reino Unido',
                                            'Argentina',
                                            'México',
                                            'Colombia',
                                            'Chile',
                                            'Perú'
                                        ];
                                        ?>
                                        <?php
                                        // Recorre el array de países para generar una opción por país.
                                        foreach ($paises as $pais):
                                        ?>
                                            <option
                                                value="<?php echo htmlspecialchars(
                                                            $pais,
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ); ?>"
                                                <?php
                                                // Marca como seleccionada la opción que coincide con el país guardado en la dirección actual.
                                                echo (
                                                    ($direccionEditar['pais'] ?? '') === $pais
                                                ) ? 'selected' : '';
                                                ?>>
                                                <!-- Muestra el nombre del país como texto de la opción. -->
                                                <?php echo htmlspecialchars(
                                                    $pais,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- TIPO DE DIRECCIÓN: permite elegir según las direcciones existentes. -->
                                <div class="mb-4">
                                    <label for="tipo" class="form-label">
                                        Tipo de dirección:
                                    </label>

                                    <select name="tipo" id="tipo" required>
                                        <?php if (!$existeEntrega): ?>
                                            <option value="entrega">Entrega</option>
                                        <?php endif; ?>

                                        <option value="facturacion">Facturación</option>
                                    </select>
                                </div>

                                <!-- BOTONES DEL FORMULARIO DE MODIFICACIÓN. -->
                                <div class="text-center">
                                    <!-- Envía el formulario para guardar los cambios. -->
                                    <button
                                        type="submit"
                                        class="btn btn-primary me-2">
                                        Guardar cambios
                                    </button>
                                    <!-- Vuelve a la página de direcciones sin enviar el formulario. -->
                                    <a
                                        href="direcciones.php"
                                        class="btn btn-secondary">
                                        Cancelar
                                    </a>
                                </div>
                            </form>
                        <?php else: ?>
                            <!-- NUEVA DIRECCIÓN: se muestra cuando no hay ninguna dirección
                 seleccionada para modificar. -->
                            <h2 class="h4 mb-4">
                                Añadir nueva dirección
                            </h2>
                            <!-- Envía los datos al controlador para crear una dirección. -->
                            <form
                                action="controlador/direccionescontroller.php"
                                method="POST">
                                <!-- Indica al controlador que debe crear una dirección nueva. -->
                                <input
                                    type="hidden"
                                    name="accion"
                                    value="crear">
                                <!-- Los campos de creación solicitan los datos de una nueva dirección; por eso no incluyen valores precargados. -->
                                <!-- CÓDIGO POSTAL: solicita un código postal de cinco dígitos. -->
                                <div class="mb-3">
                                    <label
                                        for="codigo_postal"
                                        class="form-label">
                                        Código postal:
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="codigo_postal"
                                        name="codigo_postal"
                                        placeholder="22001"
                                        pattern="[0-9]{5}"
                                        inputmode="numeric"
                                        title="Introduce 5 números"
                                        required>
                                </div>
                                <!-- NOMBRE DE LA VÍA: solicita la calle o vía de la dirección. -->
                                <div class="mb-3">
                                    <label
                                        for="nombre_via"
                                        class="form-label">
                                        Nombre de la vía:
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="nombre_via"
                                        name="nombre_via"
                                        placeholder="Ejemplo: Calle 12 de Octubre"
                                        pattern="[A-Za-zÁÉÍÓÚáéíóúÜüÑñ0-9 \-\.]+"
                                        title="Permite letras, números, espacios, puntos y guiones"
                                        required>
                                </div>
                                <!-- NÚMERO: solicita el número del inmueble. -->
                                <div class="mb-3">
                                    <label
                                        for="numero"
                                        class="form-label">
                                        Número:
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="numero"
                                        name="numero"
                                        placeholder="Ejemplo: 12"
                                        pattern="[0-9]+"
                                        inputmode="numeric"
                                        title="Solo se permiten números"
                                        required>
                                </div>
                                <!-- PISO: solicita el piso, si corresponde. -->
                                <div class="mb-3">
                                    <label
                                        for="piso"
                                        class="form-label">
                                        Piso:
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="piso"
                                        name="piso"
                                        placeholder="Ejemplo: 3º">
                                </div>
                                <!-- PUERTA: solicita la puerta, si corresponde. -->
                                <div class="mb-3">
                                    <label
                                        for="puerta"
                                        class="form-label">
                                        Puerta:
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="puerta"
                                        name="puerta"
                                        placeholder="Ejemplo: B">
                                </div>
                                <!-- PAÍS: permite elegir uno de los países disponibles. -->
                                <div class="mb-3">
                                    <label
                                        for="pais"
                                        class="form-label">
                                        País:
                                    </label>
                                    <select
                                        class="form-select"
                                        id="pais"
                                        name="pais"
                                        required>
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
                                <!-- TIPO DE DIRECCIÓN: permite elegir entre entrega y facturación. -->
                                <div class="mb-4">
                                    <label
                                        for="tipo"
                                        class="form-label">
                                        Tipo de dirección:
                                    </label>
                                    <select
                                        class="form-select"
                                        id="tipo"
                                        name="tipo"
                                        required>

                                        <?php if (!$existeEntrega): ?>
                                            <option value="entrega">Entrega</option>
                                        <?php endif; ?>

                                        <option value="facturación" selected>Facturación</option>

                                    </select>
                                </div>
                                <!-- Botón que envía los datos para guardar la nueva dirección. -->
                                <div class="text-center">
                                    <button
                                        type="submit"
                                        class="btn btn-primary">
                                        Guardar dirección
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                        <!-- Finaliza la condición: muestra el formulario de modificación si existe una dirección seleccionada;
                         de lo contrario, muestra el formulario de creación. -->
                    </div>
                    <!-- Cierra el cuerpo de la tarjeta. -->
                </div>
                <!-- Cierra la tarjeta que contiene ambos formularios. -->
                <!-- DIRECCIONES REGISTRADAS: sección desplegable que contiene las direcciones guardadas del usuario. -->
                <details class="mt-4">
                    <!-- Crea un bloque desplegable con margen superior de Bootstrap. -->
                    <summary style="cursor: pointer;">
                        <!-- Define el texto que permite abrir o cerrar el bloque; el cursor cambia al pasar por encima. -->
                        Ver direcciones registradas <!-- La flechita desplegable -->
                    </summary>
                    <div class="mt-4">
                        <!-- Añade un contenedor con margen superior para separar el contenido del título. -->
                        <?php if (empty($direcciones)): ?>
                            <!-- Comprueba si el array $direcciones está vacío, es decir, si no hay direcciones registradas. -->
                            <div class="alert alert-info">
                                <!-- Muestra un aviso informativo con los estilos de Bootstrap. -->
                                No tienes ninguna dirección registrada.
                            </div>
                        <?php else: ?>
                            <!-- Si existen direcciones, ejecuta el siguiente bloque. -->
                            <?php foreach ($direcciones as $direccion): ?>
                                <!-- Recorre todas las direcciones y guarda cada una en la variable $direccion. -->
                                <div class="card mb-4"> <!-- El desplegable de tarjetas con los cambios. -->
                                    <!-- Crea una tarjeta para mostrar una dirección y añade margen inferior. -->
                                    <div class="card-body">
                                        <!-- Contiene el contenido interno de la tarjeta. -->
                                        <?php
                                        // Utiliza el identificador de la dirección como clave del array.
                                        // Si no existe ese historial, asigna un array vacío.
                                        $historial = // Obtiene el historial correspondiente a la dirección actual.
                                            $historialDirecciones[$direccion['id']] ?? []; // ?? []: si no existe esa clave o su valor es null, devuelve un array vacío.
                                        // Cuenta cuántas versiones de la dirección hay guardadas en el historial.
                                        $numeroVersiones = count($historial);
                                        ?>
                                        <h5 class="card-title">
                                            <!-- Muestra un título cuyo contenido depende del número de versiones. -->
                                            <?php if ($numeroVersiones > 1): ?>
                                                <!-- Comprueba si existen más de una versión en el historial. -->
                                                Seguimiento de la dirección
                                                <!-- Muestra este título cuando hay varias versiones. -->
                                            <?php else: ?>
                                                <!-- Se ejecuta si no hay más de una versión. -->
                                                Dirección registrada
                                                <!-- Muestra este título cuando solo existe una versión o ninguna modificación registrada. -->
                                            <?php endif; ?>
                                            <!-- Finaliza la condición del título. -->
                                        </h5>
                                        <?php if ($numeroVersiones > 1): ?>
                                            <!-- Si hay varias versiones, muestra el historial de modificaciones. -->
                                            <!-- HISTORIAL: versiones anteriores de la dirección. -->
                                            <?php
                                            // Crea un array vacío donde se guardarán las versiones que se mostrarán.
                                            $historialVisible = [];
                                            // Inicializa la variable que almacenará los datos de la versión anterior.
                                            $anterior = null;
                                            // Recorre todas las versiones del historial.
                                            foreach ($historial as $historico) {
                                                // Define los campos que se utilizarán para comparar las direcciones.
                                                $camposDireccion = [
                                                    'nombre_via',
                                                    'numero',
                                                    'piso',
                                                    'puerta',
                                                    'codigo_postal',
                                                    'pais',
                                                    'tipo'
                                                ];
                                                // Inicializa un array para guardar los datos normalizados de la versión actual.
                                                $direccionNormalizada = []; // elimina los espacios sobrantes y convirtiendo los valores a texto.
                                                // Recorre los campos definidos anteriormente.
                                                foreach ($camposDireccion as $campo) {
                                                    // Obtiene el valor del campo; si no existe o es null, utiliza una cadena vacía.
                                                    // Convierte el valor a texto y elimina los espacios al principio y al final.
                                                    $direccionNormalizada[$campo] = trim(
                                                        (string) ($historico[$campo] ?? '')
                                                    );
                                                }
                                                // No mostrar versiones consecutivas idénticas.
                                                if (
                                                    // Comprueba que ya exista una versión anterior para comparar.
                                                    $anterior !== null &&
                                                    // Compara todos los campos normalizados de ambas versiones.
                                                    $direccionNormalizada === $anterior
                                                ) {
                                                    // Si ambas versiones son idénticas, omite esta iteración del bucle.
                                                    continue;
                                                }
                                                // Añade la versión actual al historial que se mostrará.
                                                $historialVisible[] = $historico;
                                                // Guarda los datos normalizados para compararlos con la siguiente versión.
                                                $anterior = $direccionNormalizada;
                                            }
                                            // Recorre únicamente las versiones que se han seleccionado para mostrar.
                                            // $indice indica la posición de cada versión y $historico contiene sus datos.
                                            foreach ($historialVisible as $indice => $historico):
                                            ?>
                                                <div class="direccion-historial">
                                                    <!-- Contenedor visual de una versión del historial. -->
                                                    <div class="direccion-icono">
                                                        <!-- Muestra un icono diferente según la posición de la versión. -->
                                                        <?= $indice === 0 ? '📍' : '✏️' ?>
                                                        <!-- Utiliza un pin para la primera versión y un lápiz para las siguientes. -->
                                                    </div>
                                                    <div class="direccion-contenido">
                                                        <!-- Agrupa los datos de esta versión de la dirección. -->
                                                        <strong>
                                                            <!-- Destaca el nombre de la versión. -->
                                                            <?= $indice === 0
                                                                ? 'Dirección inicial'
                                                                : 'Modificación ' . $indice ?>
                                                            <!-- Si el índice es 0, muestra Dirección inicial.
                                                 En caso contrario, muestra Modificación y el índice correspondiente. -->
                                                        </strong>
                                                        <div>
                                                            <!-- Muestra el nombre de la vía. -->
                                                            <strong>Nombre de la vía:</strong>
                                                            <?= htmlspecialchars($historico['nombre_via'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                                            <!-- Obtiene el nombre de la vía y escapa los caracteres especiales para mostrarlo de forma segura en HTML. -->
                                                        </div>
                                                        <div>
                                                            <!-- Muestra el número de la dirección. -->
                                                            <strong>Número:</strong>
                                                            <?= htmlspecialchars($historico['numero'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                                            <!-- Obtiene el número y escapa los caracteres especiales. -->
                                                        </div>
                                                        <div>
                                                            <!-- Muestra el piso de la dirección. -->
                                                            <strong>Piso:</strong>
                                                            <?= htmlspecialchars($historico['piso'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                                            <!-- Obtiene el piso y escapa los caracteres especiales. -->
                                                        </div>
                                                        <div>
                                                            <!-- Muestra la puerta de la dirección. -->
                                                            <strong>Puerta:</strong>
                                                            <?= htmlspecialchars($historico['puerta'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                                            <!-- Obtiene la puerta y escapa los caracteres especiales. -->
                                                        </div>
                                                        <div>
                                                            <!-- Muestra el código postal. -->
                                                            <strong>CP:</strong>
                                                            <?= htmlspecialchars($historico['codigo_postal'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                                            <!-- Obtiene el código postal y escapa los caracteres especiales. -->
                                                        </div>
                                                        <div>
                                                            <!-- Muestra el país de la dirección. -->
                                                            <strong>País:</strong>
                                                            <?= htmlspecialchars($historico['pais'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                                            <!-- Obtiene el país y escapa los caracteres especiales. -->
                                                        </div>
                                                        <div>
                                                            <!-- Muestra el tipo de dirección: entrega o facturación, según los datos guardados. -->
                                                            <strong>Tipo:</strong>
                                                            <?= htmlspecialchars($historico['tipo'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                                            <!-- Obtiene el tipo y escapa los caracteres especiales. -->
                                                        </div>
                                                        <?php if (!empty($historico['created_at'])): ?>
                                                            <!-- Comprueba que la versión tenga una fecha de creación o registro. -->
                                                            <small class="text-muted">
                                                                <!-- Muestra la fecha con texto pequeño y color atenuado de Bootstrap. -->
                                                                Modificado el:
                                                                <?= htmlspecialchars($historico['created_at'], ENT_QUOTES, 'UTF-8') ?>
                                                                <!-- Muestra la fecha escapando sus caracteres especiales. -->
                                                            </small>
                                                        <?php endif; ?>
                                                        <!-- Finaliza la comprobación de la fecha. -->
                                                    </div>
                                                    <!-- Cierra el contenedor del contenido de la versión. -->
                                                </div>
                                                <!-- Cierra el bloque visual de la versión del historial. -->
                                                <div class="direccion-flecha">↓</div>
                                                <!-- Muestra una flecha hacia abajo para representar la secuencia de versiones. -->
                                            <?php endforeach; ?>
                                            <!-- Finaliza el recorrido de las versiones visibles del historial. -->
                                            <!-- DIRECCIÓN ACTUAL: muestra los datos vigentes después de las versiones anteriores. -->
                                            <div class="direccion-historial">
                                                <!-- Crea el contenedor visual de la dirección actual. -->
                                                <div class="direccion-icono">
                                                    <!-- Muestra un icono de ubicación. -->
                                                    📍
                                                </div>
                                                <div class="direccion-contenido">
                                                    <!-- Agrupa los datos de la dirección actual. -->
                                                    <strong>
                                                        <!-- Destaca el título de este bloque. -->
                                                        Dirección actual
                                                    </strong>
                                                    <div>
                                                        <!-- Muestra el nombre de la vía actual. -->
                                                        <strong>Nombre de la vía:</strong>
                                                        <?= htmlspecialchars(
                                                            $direccion['nombre_via'] ?? '',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                        <!-- Obtiene el nombre de la vía actual y escapa los caracteres especiales. -->
                                                    </div>
                                                    <div>
                                                        <!-- Muestra el número actual. -->
                                                        <strong>Número:</strong>
                                                        <?= htmlspecialchars(
                                                            $direccion['numero'] ?? '',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                        <!-- Obtiene el número y escapa los caracteres especiales. -->
                                                    </div>
                                                    <div>
                                                        <!-- Muestra el piso actual. -->
                                                        <strong>Piso:</strong>
                                                        <?= htmlspecialchars(
                                                            $direccion['piso'] ?? '',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                        <!-- Obtiene el piso y escapa los caracteres especiales. -->
                                                    </div>
                                                    <div>
                                                        <!-- Muestra la puerta actual. -->
                                                        <strong>Puerta:</strong>
                                                        <?= htmlspecialchars(
                                                            $direccion['puerta'] ?? '',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                        <!-- Obtiene la puerta y escapa los caracteres especiales. -->
                                                    </div>
                                                    <div>
                                                        <!-- Muestra el código postal actual. -->
                                                        <strong>CP:</strong>
                                                        <?= htmlspecialchars(
                                                            $direccion['codigo_postal'] ?? '',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                        <!-- Obtiene el código postal y escapa los caracteres especiales. -->
                                                    </div>
                                                    <div>
                                                        <!-- Muestra el país actual. -->
                                                        <strong>País:</strong>
                                                        <?= htmlspecialchars(
                                                            $direccion['pais'] ?? '',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                        <!-- Obtiene el país y escapa los caracteres especiales. -->
                                                    </div>
                                                    <div>
                                                        <!-- Muestra el tipo actual de dirección. -->
                                                        <strong>Tipo:</strong>
                                                        <?= htmlspecialchars(
                                                            $direccion['tipo'] ?? '',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                        <!-- Obtiene el tipo y escapa los caracteres especiales. -->
                                                    </div>
                                                </div>
                                                <!-- Cierra el contenido de la dirección actual. -->
                                            </div>
                                            <!-- Cierra el bloque de la dirección actual. -->
                                        <?php else: ?>
                                            <!-- Si no hay varias versiones, muestra directamente la dirección actual. -->
                                            <!-- ÚNICA DIRECCIÓN SIN MODIFICACIONES: presentación sin historial de versiones. -->
                                            <div class="direccion-historial">
                                                <!-- Crea el contenedor visual de la dirección actual. -->
                                                <div class="direccion-icono">
                                                    <!-- Muestra el icono de ubicación. -->
                                                    📍
                                                </div>
                                                <div class="direccion-contenido">
                                                    <!-- Agrupa los datos de la dirección. -->
                                                    <strong>
                                                        <!-- Destaca el título de la dirección. -->
                                                        Dirección actual
                                                    </strong>
                                                    <div>
                                                        <!-- Muestra el nombre de la vía. -->
                                                        <strong>Nombre de la vía:</strong>
                                                        <?= htmlspecialchars(
                                                            $direccion['nombre_via'] ?? '',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                        <!-- Obtiene el nombre de la vía y escapa los caracteres especiales. -->
                                                    </div>
                                                    <div>
                                                        <!-- Muestra el número. -->
                                                        <strong>Número:</strong>
                                                        <?= htmlspecialchars(
                                                            $direccion['numero'] ?? '',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                        <!-- Obtiene el número y escapa los caracteres especiales. -->
                                                    </div>
                                                    <div>
                                                        <!-- Muestra el piso. -->
                                                        <strong>Piso:</strong>
                                                        <?= htmlspecialchars(
                                                            $direccion['piso'] ?? '',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                        <!-- Obtiene el piso y escapa los caracteres especiales. -->
                                                    </div>
                                                    <div>
                                                        <!-- Muestra la puerta. -->
                                                        <strong>Puerta:</strong>
                                                        <?= htmlspecialchars(
                                                            $direccion['puerta'] ?? '',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                        <!-- Obtiene la puerta y escapa los caracteres especiales. -->
                                                    </div>
                                                    <div>
                                                        <!-- Muestra el código postal. -->
                                                        <strong>CP:</strong>
                                                        <?= htmlspecialchars(
                                                            $direccion['codigo_postal'] ?? '',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                        <!-- Obtiene el código postal y escapa los caracteres especiales. -->
                                                    </div>
                                                    <div>
                                                        <!-- Muestra el país. -->
                                                        <strong>País:</strong>
                                                        <?= htmlspecialchars(
                                                            $direccion['pais'] ?? '',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                        <!-- Obtiene el país y escapa los caracteres especiales. -->
                                                    </div>
                                                    <div>
                                                        <!-- Muestra el tipo de dirección. -->
                                                        <strong>Tipo:</strong>
                                                        <?= htmlspecialchars(
                                                            $direccion['tipo'] ?? '',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                        <!-- Obtiene el tipo y escapa los caracteres especiales. -->
                                                    </div>
                                                </div>
                                                <!-- Cierra el contenido de la dirección actual. -->
                                            </div>
                                            <!-- Cierra el bloque de la dirección actual. -->
                                        <?php endif; ?>
                                        <!-- Finaliza la condición que decide si se muestra el historial o solo la dirección actual. -->
                                        <!-- BOTONES: opciones para modificar o eliminar la dirección. -->
                                        <div class="mt-3">
                                            <!-- Agrupa los botones y añade margen superior. -->
                                            <a
                                                href="direcciones.php?modificar=<?= (int) $direccion['id'] ?>"
                                                class="btn btn-warning">
                                                <!-- Enlaza con direcciones.php pasando el ID de la dirección que se quiere modificar.
                                     Convierte el ID a entero y aplica el estilo amarillo de Bootstrap. -->
                                                Modificar
                                                <!-- Texto visible del enlace para modificar la dirección. -->
                                            </a>
                                            <form
                                                action="controlador/direccionescontroller.php"
                                                method="POST"
                                                style="display: inline;">
                                                <!-- Crea un formulario que envía los datos por POST al controlador de direcciones.
                                     display: inline permite situarlo en la misma línea que el enlace anterior. -->
                                                <input
                                                    type="hidden"
                                                    name="accion"
                                                    value="eliminar">
                                                <!-- Envía al controlador la acción que debe ejecutar: eliminar. -->
                                                <input
                                                    type="hidden"
                                                    name="direccion_id"
                                                    value="<?= (int) $direccion['id'] ?>">
                                                <!-- Envía el identificador de la dirección que se desea eliminar, convertido a entero. -->
                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('¿Seguro que quieres eliminar esta dirección?');">
                                                    <!-- Crea el botón de eliminación.
                                         Antes de enviar el formulario, JavaScript muestra una confirmación.
                                         Si el usuario acepta, confirm() devuelve true y se envía el formulario.
                                         Si cancela, devuelve false y se evita el envío. -->
                                                    Eliminar
                                                    <!-- Texto visible del botón. -->
                                                </button>
                                                <!-- Cierra el botón de eliminación. -->
                                            </form>
                                            <!-- Cierra el formulario de eliminación. -->
                                        </div>
                                        <!-- Cierra el contenedor de los botones. -->
                                    </div>
                                    <!-- Cierra el cuerpo de la tarjeta. -->
                                </div>
                                <!-- Cierra la tarjeta de la dirección. -->
                            <?php endforeach; ?>
                            <!-- Finaliza el recorrido de todas las direcciones registradas. -->
                        <?php endif; ?>
                        <!-- Finaliza la comprobación de si existen direcciones registradas. -->
                    </div>
                    <!-- Cierra el contenedor interno del desplegable. -->
                </details>
                <!-- Cierra el bloque desplegable de direcciones registradas. -->
            </div>
            <!-- Cierra el contenedor exterior que envuelve esta sección. -->
        </div>
    </main>
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row g-4 justify-content-center text-center">
                <div class="col-12 col-md-6 col-lg-3">
                    <h5 class="text-uppercase text-primary mb-3">
                        <strong>
                            Mi Tienda Streetwear
                        </strong>
                    </h5>
                    <p class="text-secondary small">
                        Tu tienda de confianza para encontrar las mejores
                        prendas urbanas, camisetas oversize y zapatillas
                        exclusivas. Elevando tu estilo diario desde 2026.
                    </p>
                </div>
                <div class="col-6 col-md-6 col-lg-3">
                    <h5 class="text-uppercase mb-3">
                        <strong>
                            Enlaces de interés
                        </strong>
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a
                                href="/webcurso/"
                                class="text-secondary text-decoration-none small text-hover-light">
                                Inicio
                            </a>
                        </li>
                        <li class="mb-2">
                            <a
                                href="#"
                                class="text-secondary text-decoration-none small text-hover-light">
                                Productos
                            </a>
                        </li>
                        <li class="mb-2">
                            <a
                                href="#"
                                class="text-secondary text-decoration-none small text-hover-light">
                                Preguntas frecuentes
                            </a>
                        </li>
                        <li class="mb-2">
                            <a
                                href="contacto.php"
                                class="text-secondary text-decoration-none small text-hover-light">
                                Contacto
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-6 col-md-6 col-lg-3">
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
            <hr class="border-secondary my-4">
            <div class="row">
                <div class="col text-center">
                    <p class="text-secondary small mb-0">
                        &copy; 2026 Mi Tienda Streetwear.
                        Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>
    <script src="main.js"></script>
</body>

</html>