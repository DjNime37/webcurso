<?php

header('Content-Type: text/html; charset=UTF-8');

class Carrito
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        if (!isset($_SESSION['usuario'])) {
            $_SESSION['usuario'] = [
                'tipo' => 'invitado'
            ];
        }
    }

    public function obtenerProducto(int $id): array|false
    {
        $sql = "SELECT id, nombre, imagen, precio
                FROM productos
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function agregarProducto(
        int $id,
        int $cantidad,
        ?string $talla = null
    ): bool {

        $producto = $this->obtenerProducto($id);

        if (!$producto || $cantidad < 1) {
            return false;
        }

        $talla = $talla !== null && trim($talla) !== ''
            ? trim($talla)
            : null;

        foreach ($_SESSION['carrito'] as $indice => $articulo) {
            if (
                $articulo['id'] === (int) $producto['id'] &&
                ($articulo['talla'] ?? null) === $talla
            ) {
                $_SESSION['carrito'][$indice]['cantidad'] += $cantidad;
                return true;
            }
        }

        $articulo = [
            'id' => (int) $producto['id'],
            'nombre' => $producto['nombre'],
            'imagen' => $producto['imagen'],
            'precio' => (float) $producto['precio'],
            'cantidad' => $cantidad
        ];

        if ($talla !== null) {
            $articulo['talla'] = $talla;
        }

        $_SESSION['carrito'][] = $articulo;

        return true;
    }

    public function eliminarProducto(int $id): void
    {
        foreach ($_SESSION['carrito'] as $indice => $articulo) {

            if ($articulo['id'] === $id) {
                unset($_SESSION['carrito'][$indice]);
            }
        }

        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }

    public function obtenerCarrito(): array
    {
        return $_SESSION['carrito'];
    }

    public function vaciarCarrito(): void
    {
        $_SESSION['carrito'] = [];
    }

    public function crearTablaPreCompra(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS pre_compra (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_pedido INT NOT NULL,
            producto_id INT NOT NULL,
            nombre VARCHAR(255) NOT NULL,
            imagen VARCHAR(500) NOT NULL,
            talla VARCHAR(50) NOT NULL,
            precio DECIMAL(10,2) NOT NULL,
            cantidad INT NOT NULL,
            fecha_hora DATETIME NOT NULL,
            total DECIMAL(10,2) NOT NULL
        )";

        $this->conn->exec($sql);
    }

    public function guardarPreCompra(): bool
    {
        if (empty($_SESSION['carrito'])) {
            return false;
        }

        $this->crearTablaPreCompra();

        $sql = "SELECT COALESCE(MAX(id_pedido), 0) + 1
                FROM pre_compra";

        $stmt = $this->conn->query($sql);
        $idPedido = (int) $stmt->fetchColumn();

        $fechaHora = date('Y-m-d H:i:s');

        $total = 0;

        foreach ($_SESSION['carrito'] as $articulo) {
            $precio = (float) $articulo['precio'];
            $cantidad = (int) $articulo['cantidad'];

            $total += $precio * $cantidad;
        }

        $sql = "INSERT INTO pre_compra
                (
                    id_pedido,
                    producto_id,
                    nombre,
                    imagen,
                    talla,
                    precio,
                    cantidad,
                    fecha_hora,
                    total
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        foreach ($_SESSION['carrito'] as $articulo) {

            $talla = isset($articulo['talla']) && trim($articulo['talla']) !== ''
                ? trim($articulo['talla'])
                : 'No aplica';

            $stmt->execute([
                $idPedido,
                (int) $articulo['id'],
                $articulo['nombre'],
                $articulo['imagen'],
                $talla,
                (float) $articulo['precio'],
                (int) $articulo['cantidad'],
                $fechaHora,
                $total
            ]);
        }

        return true;
    }

    public function borrarTablaPreCompra(): void
    {
        $sql = "DROP TABLE IF EXISTS pre_compra";

        $this->conn->exec($sql);
    }
}