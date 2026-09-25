<?php
header('Content-Type: application/json; charset=utf-8');

$host = 'localhost';
$db   = 'registros';
$user = 'root';
$pass = ''; // Configuración por defecto de XAMPP

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error de conexión con el servidor.'
    ]);
    exit;
}

$usuario_ingresado = trim($_POST['usuario'] ?? '');
$password_ingresada = trim($_POST['password'] ?? '');

// 1. Validar campos vacíos
if (empty($usuario_ingresado) || empty($password_ingresada)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Por favor, rellena todos los campos.'
    ]);
    exit;
}

// 2. Validar longitud mínima
if (strlen($password_ingresada) < 8) {
    echo json_encode([
        'status' => 'error',
        'message' => 'La contraseña debe tener al menos 8 caracteres.'
    ]);
    exit;
}

// 3. Comprobar credenciales de forma segura
$stmt = $conn->prepare("SELECT password FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $usuario_ingresado);
$stmt->execute();
$result = $stmt->get_result();

$credencialesValidas = false;

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    if ($password_ingresada === $row['password']) {
        $credencialesValidas = true;
    }
}

// 4. Respuesta única para cualquier fallo de credenciales
if ($credencialesValidas) {
    echo json_encode([
        'status' => 'success',
        'message' => '¡Inicio de sesión exitoso!'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'El usuario o la contraseña introducidos no son correctos.'
    ]);
}

$stmt->close();
$conn->close();
?>


