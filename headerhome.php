<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$totalArticulos = 0;

if (!empty($_SESSION['pedido_finalizado'])) {
    $carritoContador = $_SESSION['carrito_pedido'] ?? [];
} else {
    $carritoContador = $_SESSION['carrito'] ?? [];
}

if (is_array($carritoContador)) {
    foreach ($carritoContador as $producto) {
        $totalArticulos += (int) ($producto['cantidad'] ?? 0);
    }
}
?>

<link rel="stylesheet" href="/webcurso/estilos/estilos.css">
<link rel="stylesheet" href="/webcurso/estilos/headers.css">

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">

            <a class="navbar-brand" href="/webcurso/index.php">
                <strong style="background: linear-gradient(90deg, red, orange, yellow, green, cyan, blue, violet); background-clip: text; -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    TIENDA DE ROPA
                </strong>
            </a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Abrir menú de navegación">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/webcurso/index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/webcurso/chandals.php">Chándals</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/webcurso/deportivas.php">Deportivas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/webcurso/reproductores.php">Reproductores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/webcurso/termicas.php">Térmicas</a>
                    </li>
                </ul>

                <div class="usuario-acceso ms-auto">

                    <a
                        href="/webcurso/iniciosesión.php"
                        class="login-link"
                        title="Mi cuenta"
                        rel="noopener noreferrer">
                        <span class="icon-usuario">👤</span>
                        <span class="text-usuario">Mi cuenta</span>
                    </a>

                    <a
                        href="/webcurso/crearcuenta.php"
                        class="btn-registro"
                        title="Registrarse"
                        target="_blank"
                        rel="noopener noreferrer">
                        Registrarse
                    </a>

                    <a
                        href="/webcurso/vista/carrito.php"
                        class="btn-sin-registro"
                        title="Carrito">
                        🛒
                        <?php if ($totalArticulos > 0): ?>
                            <span class="badge bg-danger">
                                <?= $totalArticulos ?>
                            </span>
                        <?php endif; ?>
                    </a>

                </div>
            </div>
        </div>
    </nav>
</header>