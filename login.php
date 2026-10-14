<?php
// Indica que la respuesta del servidor se enviará en formato JSON y con codificación UTF-8.
header('Content-Type: application/json; charset=utf-8');
// Define el servidor donde se encuentra la base de datos.
$host = 'localhost';
// Define el nombre de la base de datos que contiene los usuarios.
$db = 'registros';
// Define el usuario de MySQL.
$user = 'root';
// Define la contraseña de MySQL; en la configuración predeterminada de XAMPP suele estar vacía.
$pass = '';
// Crea la conexión con la base de datos utilizando la extensión MySQLi.
$conn = new mysqli($host, $user, $pass, $db);
// Comprueba si se ha producido un error al establecer la conexión.
if ($conn->connect_error) {
    // Devuelve una respuesta JSON indicando que se ha producido un error de conexión.
    echo json_encode([
        'status' => 'error',
        'message' => 'Error de conexión con el servidor.'
    ]);
    // Detiene la ejecución del script para evitar que continúe sin conexión a la base de datos.
    exit;
}
// Obtiene el nombre de usuario enviado mediante POST y elimina los espacios al principio y al final.
$usuario_ingresado = trim($_POST['usuario'] ?? '');
// Obtiene la contraseña enviada mediante POST y elimina los espacios al principio y al final.
$password_ingresada = trim($_POST['password'] ?? '');
// 1. VALIDAR CAMPOS VACÍOS
// Comprueba si el nombre de usuario o la contraseña están vacíos.
if (empty($usuario_ingresado) || empty($password_ingresada)) {
    // Devuelve un mensaje de error indicando que deben rellenarse todos los campos.
    echo json_encode([
        'status' => 'error',
        'message' => 'Por favor, rellena todos los campos.'
    ]);
    // Detiene la ejecución para no continuar con las comprobaciones.
    exit;
}
// 2. VALIDAR LA LONGITUD MÍNIMA DE LA CONTRASEÑA
// Comprueba si la contraseña introducida tiene menos de ocho caracteres.
if (strlen($password_ingresada) < 8) {
    // Devuelve un mensaje de error si la contraseña no alcanza la longitud mínima.
    echo json_encode([
        'status' => 'error',
        'message' => 'La contraseña debe tener al menos 8 caracteres.'
    ]);
    // Detiene la ejecución para no consultar las credenciales en la base de datos.
    exit;
}
// 3. COMPROBAR LAS CREDENCIALES DE FORMA SEGURA MEDIANTE UNA CONSULTA PREPARADA
// Prepara una consulta SQL que busca la contraseña del usuario indicado.
// El signo de interrogación representa el parámetro que se enlazará posteriormente.
$stmt = $conn->prepare("SELECT password FROM usuarios WHERE usuario = ?");
// Asocia el nombre de usuario al parámetro de la consulta.
// La letra "s" indica que el parámetro es una cadena de texto.
$stmt->bind_param("s", $usuario_ingresado);
// Ejecuta la consulta SQL con el nombre de usuario proporcionado.
$stmt->execute();
// Obtiene el conjunto de resultados de la consulta.
$result = $stmt->get_result();
// Inicializa la variable que indica si las credenciales son válidas.
$credencialesValidas = false;
// Comprueba si la consulta ha encontrado exactamente un usuario.
if ($result->num_rows === 1) {
    // Recupera la fila encontrada como un array asociativo.
    $row = $result->fetch_assoc();
    // Compara la contraseña introducida con la contraseña almacenada en la base de datos.
    if ($password_ingresada === $row['password']) {
        // Marca las credenciales como válidas si ambas contraseñas coinciden.
        $credencialesValidas = true;
    }
}
// 4. DEVOLVER UNA RESPUESTA SEGÚN EL RESULTADO DE LA AUTENTICACIÓN
// Comprueba si el usuario ha introducido unas credenciales válidas.
if ($credencialesValidas) {
    // Inicia la sesión para poder guardar los datos del usuario que ha iniciado sesión.
    session_start();
    // Guarda el tipo de usuario para identificar que se trata de un usuario registrado.
    $_SESSION['tipo'] = 'registrado';
    // Guarda el nombre de usuario introducido en el formulario de inicio de sesión.
    $_SESSION['usuario'] = $usuario_ingresado;
    // Devuelve una respuesta JSON indicando que las credenciales son correctas
    // y proporciona la dirección de la página a la que debe redirigirse.
    echo json_encode([
        'status' => 'success',
        'message' => '¡Inicio de sesión exitoso!',
        'redirect' => 'usuarioregistrado.php'
    ]);
} else {
    // Devuelve el mismo mensaje cuando el usuario no existe o la contraseña no coincide.
    echo json_encode([
        'status' => 'error',
        'message' => 'El usuario o la contraseña introducidos no son correctos.'
    ]);
}
// Cierra la consulta preparada y libera los recursos asociados.
$stmt->close();
// Cierra la conexión con la base de datos.
$conn->close();