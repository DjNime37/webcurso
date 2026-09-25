<?php

// Activa la visualización de errores de PHP. El "1" significa que los errores se mostrarán en pantalla. Es útil durante el desarrollo, pero normalmente se desactiva en producción.
ini_set('display_errors', 1);

// Indica a PHP que informe de TODOS los tipos de errores, avisos y advertencias posibles.
error_reporting(E_ALL);

// Crea una nueva conexión con la base de datos mediante PDO.
$pdo = new PDO(

    // Cadena de conexión que indica que utilizamos MySQL/MariaDB, que el servidor es localhost, que la base de datos es "usuarios" y que utilizamos UTF-8 para admitir tildes, ñ, etc.
    "mysql:host=localhost;dbname=usuarios;charset=utf8mb4",

    // Usuario de la base de datos.
    "root",

    // Contraseña del usuario. Está vacía en este caso.
    "",

    [

        // Indica que PDO debe lanzar una excepción cuando se produzca un error en la base de datos.
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

        // Hace que los resultados de las consultas SQL se devuelvan como arrays asociativos.
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);

// Comprueba qué método HTTP se ha utilizado para acceder a esta página. Si el formulario se envía mediante POST, $_SERVER['REQUEST_METHOD'] tendrá el valor "POST".
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    // Si la petición NO es POST, se detiene inmediatamente la ejecución del programa y muestra este mensaje.
    exit('Acceso no válido.');
}

$nombre = trim($_POST['nombre'] ?? '');
// Obtiene el valor enviado mediante POST con el nombre "nombre", elimina los espacios al principio y al final mediante trim() y, si no existe, utiliza una cadena vacía.

$primer_apellido = trim($_POST['primer_apellido'] ?? '');
// Obtiene el valor enviado mediante POST con el nombre "primer_apellido", elimina los espacios al principio y al final mediante trim() y, si no existe, utiliza una cadena vacía.

$segundo_apellido = trim($_POST['segundo_apellido'] ?? '');
// Obtiene el valor enviado mediante POST con el nombre "segundo_apellido", elimina los espacios al principio y al final mediante trim() y, si no existe, utiliza una cadena vacía.

$usuario = trim($_POST['usuario'] ?? '');
// Obtiene el valor enviado mediante POST con el nombre "usuario", elimina los espacios al principio y al final mediante trim() y, si no existe, utiliza una cadena vacía.

$email = trim($_POST['email'] ?? '');
// Obtiene el valor enviado mediante POST con el nombre "email", elimina los espacios al principio y al final mediante trim() y, si no existe, utiliza una cadena vacía.

$password = $_POST['password'] ?? '';
// Obtiene la contraseña enviada mediante POST con el nombre "password" y, si no existe, utiliza una cadena vacía.

$password_confirm = $_POST['password_confirm'] ?? '';
// Obtiene la confirmación de la contraseña enviada mediante POST con el nombre "password_confirm" y, si no existe, utiliza una cadena vacía.

$fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
// Obtiene la fecha de nacimiento enviada mediante POST con el nombre "fecha_nacimiento" y, si no existe, utiliza una cadena vacía.

$prefijo = trim($_POST['prefijo'] ?? '');
// Obtiene el prefijo telefónico enviado mediante POST con el nombre "prefijo", elimina los espacios al principio y al final mediante trim() y, si no existe, utiliza una cadena vacía.

$telefono = trim($_POST['telefono'] ?? '');
// Obtiene el número de teléfono enviado mediante POST con el nombre "telefono", elimina los espacios al principio y al final mediante trim() y, si no existe, utiliza una cadena vacía.

$codigo_postal = trim($_POST['codigo_postal'] ?? '');
// Obtiene el código postal enviado mediante POST con el nombre "codigo_postal", elimina los espacios al principio y al final mediante trim() y, si no existe, utiliza una cadena vacía.

$nombre_via = trim($_POST['nombre_via'] ?? '');
// Obtiene el nombre de la vía enviado mediante POST con el nombre "nombre_via", elimina los espacios al principio y al final mediante trim() y, si no existe, utiliza una cadena vacía.

$numero = trim($_POST['numero'] ?? '');
// Obtiene el número de la dirección enviado mediante POST con el nombre "numero", elimina los espacios al principio y al final mediante trim() y, si no existe, utiliza una cadena vacía.

$piso = trim($_POST['piso'] ?? '');
// Obtiene el piso enviado mediante POST con el nombre "piso", elimina los espacios al principio y al final mediante trim() y, si no existe, utiliza una cadena vacía.

$puerta = trim($_POST['puerta'] ?? '');
// Obtiene la puerta enviada mediante POST con el nombre "puerta", elimina los espacios al principio y al final mediante trim() y, si no existe, utiliza una cadena vacía.

$pais = trim($_POST['pais'] ?? '');
// Obtiene el país enviado mediante POST con el nombre "pais", elimina los espacios al principio y al final mediante trim() y, si no existe, utiliza una cadena vacía.



if (
    $nombre === '' ||             // Si el usuario rellena todo excepto $telefono por ejemplo, $telefono === '' será verdadero, por lo que se ejecutará exit('Faltan datos obligatorios.');
    $primer_apellido === '' ||
    $segundo_apellido === '' ||
    $usuario === '' ||
    $email === '' ||
    $password === '' ||                   // Basta con que uno solo de los campos esté vacío para que la condición sea verdadera.
    $fecha_nacimiento === '' ||
    $prefijo === '' ||
    $telefono === '' ||
    $codigo_postal === '' ||
    $nombre_via === '' ||
    $numero === '' ||
    $pais === ''
) {
    exit('Faltan datos obligatorios.');
}
// Comprueba que la contraseña introducida y la contraseña de confirmación sean exactamente iguales.
if ($password !== $password_confirm) {  // !== significa "no es idéntico"
    exit('Las contraseñas no coinciden.');
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
    INSERT INTO usuarios (
        nombre,
        primer_apellido,
        segundo_apellido,
        usuario,
        email,
        password,
        fecha_nacimiento,
        prefijo,
        telefono,
        codigo_postal,
        nombre_via,
        numero,
        piso,
        puerta,
        pais
    ) VALUES (
        :nombre,
        :primer_apellido,
        :segundo_apellido,
        :usuario,
        :email,
        :password,
        :fecha_nacimiento,
        :prefijo,
        :telefono,
        :codigo_postal,
        :nombre_via,
        :numero,
        :piso,
        :puerta,
        :pais
    )
");
// "$stmt = $pdo->prepare" prepara una consulta SQL para ejecutarla posteriormente utilizando la conexión $pdo. prepare() ,que permite utilizar parámetros en la consulta de forma segura y ayuda a evitar inyecciones SQL.

$stmt->execute([
    ':nombre' => $nombre,    // Asocia el parámetro ":nombre" de la consulta SQL con el valor que contiene la variable $nombre. Hace que el marcador ":nombre" reciba el valor "Mario".
    ':primer_apellido' => $primer_apellido,
    ':segundo_apellido' => $segundo_apellido,
    ':usuario' => $usuario,
    ':email' => $email,
    ':password' => $password_hash,
    ':fecha_nacimiento' => $fecha_nacimiento,
    ':prefijo' => $prefijo,
    ':telefono' => $telefono,
    ':codigo_postal' => $codigo_postal,
    ':nombre_via' => $nombre_via,
    ':numero' => $numero,
    ':piso' => $piso === '' ? null : $piso,
    ':puerta' => $puerta === '' ? null : $puerta,
    ':pais' => $pais
]);

echo 'Cuenta creada correctamente.';
?>


