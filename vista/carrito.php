<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();
try {
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
    $carrito = $_SESSION['carrito'];
} catch (Throwable $e) {
    echo '<h1>Error</h1>';
    echo '<p><strong>Tipo:</strong> ' . htmlspecialchars(get_class($e)) . '</p>';
    echo '<p><strong>Mensaje:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><strong>Archivo:</strong> ' . htmlspecialchars($e->getFile()) . '</p>';
    echo '<p><strong>Línea:</strong> ' . $e->getLine() . '</p>';
    exit;
}
$totalProductos = 0;
$totalPagar = 0;
foreach ($carrito as $producto) {
    $cantidad = (int) $producto['cantidad'];
    $precio = (float) $producto['precio'];
    $totalProductos += $cantidad;
    $totalPagar += $precio * $cantidad;
}
$mensajeCarrito = $_SESSION['mensaje_carrito'] ?? null;
unset($_SESSION['mensaje_carrito']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Carrito de compra</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
<h1 class="mb-4 text-center">
    Productos del carrito
</h1>
<?php if ($mensajeCarrito): ?>
    <div id="mensaje-carrito" class="alert alert-success text-center">
        <?php echo htmlspecialchars($mensajeCarrito); ?>
    </div>
<?php endif; ?>
<?php if (empty($carrito)): ?>
    <div class="alert alert-info">
        El carrito está vacío.
    </div>
    <div class="text-center">
        <a href="../index.html" class="btn btn-primary">
            Volver a comprar
        </a>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-lg-8">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Artículo</th>
                            <th>Talla</th>
                            <th>Precio ud</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($carrito as $producto): ?>
                            <?php
                            $cantidad = (int) $producto['cantidad'];
                            $precio = (float) $producto['precio'];
                            ?>
                            <tr>
                                <td>
                                    <?php if (!empty($producto['imagen'])): ?>
                                        <img
                                            src="../<?php echo htmlspecialchars($producto['imagen']); ?>"
                                            alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                            style="width: 60px; height: 60px; object-fit: cover;"
                                        >
                                    <?php else: ?>
                                        Imagen no disponible
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($producto['nombre']); ?>
                                </td>
                                <td>
                                    <?php if (isset($producto['talla'])): ?>
                                        <?php echo htmlspecialchars($producto['talla']); ?>
                                    <?php else: ?>
                                        No aplica
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo number_format($precio, 2, ',', '.'); ?> €
                                </td>
                                <td>
                                    <?php echo $cantidad; ?>
                                </td>
                                <td>
                                    <?php echo number_format($totalPagar, 2, ',', '.'); ?> €
                                </td>
                                <td>
                                    <form
                                        action="../controlador/carritocontroller.php"
                                        method="POST"
                                    >
                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php echo (int) $producto['id']; ?>"
                                        >
                                        <button
                                            type="submit"
                                            name="eliminar_producto"
                                            value="1"
                                            class="btn btn-danger btn-sm"
                                        >
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">
                        Resumen del pedido
                    </h5>
                    <p>
                        Total de productos:
                        <?php echo $totalProductos; ?>
                    </p>
                    <p>
                        Total a pagar:
                        <?php echo number_format($totalPagar, 2, ',', '.'); ?> €
                    </p>
                    <div class="d-flex gap-2 flex-wrap">
                        <form
                            action="../controlador/carritocontroller.php"
                            method="POST"
                        >
                            <button
                                type="submit"
                                name="finalizar"
                                value="1"
                                class="btn btn-primary"
                            >
                                Finalizar
                            </button>
                        </form>
                        <a
                            href="../index.html"
                            class="btn btn-primary"
                        >
                            Volver a comprar
                        </a>
                        <form
                            action="../controlador/carritocontroller.php"
                            method="POST"
                        >
                            <button
                                type="submit"
                                name="vaciar_carrito"
                                value="1"
                                class="btn btn-danger"
                            >
                                Vaciar carrito
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
</div>
<script>
const mensaje = document.getElementById('mensaje-carrito');
if (mensaje) {
    setTimeout(() => {
        mensaje.remove();
    }, 3000);
}
</script>
</body>
</html>
