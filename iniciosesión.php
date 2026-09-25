<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $errors = [];
    if (
        strlen($usuario) < 4 ||
        strlen($usuario) > 20 ||
        !preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÜüÑñ0-9_]+$/', $usuario)
    ) {
        $errors['usuario'] = 'El nombre de usuario debe tener entre 4 y 20 caracteres y solo puede contener letras, números, guiones bajos y caracteres especiales (áéíóúüñÁÉÍÓÚÜÑ).';
    }
    if (
        strlen($password) < 8 ||
        !preg_match('/^(?=.*[A-Za-z])(?=.*\d).+$/', $password)
    ) {
        $errors['password'] = 'La contraseña debe tener al menos 8 caracteres, incluyendo al menos una letra y un número.';
    }
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: iniciosesión.php');
        exit;
    }
    try {
        $pdo = new PDO(
    'mysql:host=localhost;dbname=usuarios;charset=utf8mb4',
    'root',
    ''
);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT id, usuario, password
                FROM usuarios
                WHERE usuario = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$usuario]);
        $usuarioBD = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuarioBD && password_verify($password, $usuarioBD['password'])) {
            $_SESSION['authenticated'] = true;
            $_SESSION['username'] = $usuarioBD['usuario'];
            $_SESSION['usuario_id'] = $usuarioBD['id'];
            header('Location: dashboard.php');
            exit;
        } else {
            $_SESSION['errors']['auth'] = 'Nombre de usuario o contraseña incorrectos.';
            header('Location: iniciosesión.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['errors']['auth'] = 'Error al conectar con la base de datos.';
        header('Location: iniciosesión.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de usuario</title>
    <link rel="stylesheet" href="estilos/botóniniciosesión.css">
</head>
<body>
    <h1>Iniciar sesión</h1>
    <?php if (isset($_SESSION['errors'])): ?>
        <div class="errores">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>
    <form id="iniciosesiónForm" action="iniciosesión.php" method="POST" autocomplete="off">
        <label for="usuario">Nombre de usuario:</label>
        <input
            type="text"
            id="usuario"
            name="usuario"
            placeholder="Ejemplo: juan123"
            pattern="[A-Za-zÁÉÍÓÚáéíóúÜüÑñ0-9_]{4,20}"
            title="Entre 4 y 20 caracteres."
            required
        >
        <label for="password">Contraseña:</label>
        <input
            type="password"
            id="password"
            name="password"
            placeholder="Mínimo 8 caracteres"
            pattern="(?=.*[A-Za-z])(?=.*\d).{8,}"
            title="Mínimo 8 caracteres, al menos una letra y un número"
            required
        >
        <button type="submit">Iniciar sesión</button>
    </form>
</body>
</html>