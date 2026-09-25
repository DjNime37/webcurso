<?php

header('Content-Type: text/html; charset=UTF-8');
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/../modelo/carrito.php';

try {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('La petición debe realizarse mediante POST.');
    }

    $pdo = new PDO(
        'mysql:host=localhost;dbname=tienda_ropa;charset=utf8mb4',
        'root',
        ''
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $carrito = new Carrito($pdo);

    if (isset($_POST['vaciar_carrito'])) {

        $carrito->vaciarCarrito();

        if (isset($_SESSION['pre_compra_activa'])) {

            $carrito->borrarTablaPreCompra();

            unset($_SESSION['pre_compra_activa']);
        }

        header('Location: ../vista/carrito.php');
        exit;
    }

    if (isset($_POST['eliminar_producto'])) {

        if (!isset($_POST['id'])) {
            throw new Exception('Falta el ID del producto.');
        }

        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);

        if ($id === false || $id < 1) {
            throw new Exception('El ID del producto no es válido.');
        }

        $carrito->eliminarProducto($id);

        if (empty($_SESSION['carrito']) && isset($_SESSION['pre_compra_activa'])) {

            $carrito->borrarTablaPreCompra();

            unset($_SESSION['pre_compra_activa']);
        }

        header('Location: ../vista/carrito.php');
        exit;
    }

    if (isset($_POST['finalizar'])) {

        $resultado = $carrito->guardarPreCompra();

        if (!$resultado) {
            throw new Exception('El carrito está vacío.');
        }

        $_SESSION['pre_compra_activa'] = true;

        $_SESSION['mensaje_carrito'] = 'Carrito guardado correctamente.';

        header('Location: ../vista/carrito.php');
        exit;
    }

    if (!isset($_POST['id'])) {
        throw new Exception('Falta el ID del producto.');
    }

    if (!isset($_POST['cantidad'])) {
        throw new Exception('Falta la cantidad.');
    }

    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $cantidad = filter_var($_POST['cantidad'], FILTER_VALIDATE_INT);

    if ($id === false || $id < 1) {
        throw new Exception('El ID del producto no es válido.');
    }

    if ($cantidad === false || $cantidad < 1) {
        throw new Exception('La cantidad no es válida.');
    }

    $talla = isset($_POST['talla']) ? trim($_POST['talla']) : null;

    $resultado = $carrito->agregarProducto(
        $id,
        $cantidad,
        $talla
    );

    if (!$resultado) {
        throw new Exception('El producto no existe.');
    }

    header('Location: ../vista/carrito.php');
    exit;

} catch (Throwable $e) {

    echo '<h1>Error</h1>';

    echo '<p><strong>Tipo:</strong> ' . htmlspecialchars(get_class($e)) . '</p>';

    echo '<p><strong>Mensaje:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';

    echo '<p><strong>Archivo:</strong> ' . htmlspecialchars($e->getFile()) . '</p>';

    echo '<p><strong>Línea:</strong> ' . $e->getLine() . '</p>';

    echo '<h2>POST recibido</h2>';

    echo '<pre>';
    print_r($_POST);
    echo '</pre>';

    echo '<h2>Sesión</h2>';

    echo '<pre>';
    print_r($_SESSION ?? []);
    echo '</pre>';
}