<?php
session_start(); // Inicia la sesión para acceder a las variables de sesión

// Configura la zona horaria
date_default_timezone_set('America/Tegucigalpa');

// Obtén la fecha y hora actuales
$currentDateTime = date('Y-m-d H:i:s');

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "comentarios";

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar conexión
if (!$conn) {
    echo json_encode(['error' => 'Error de conexión: ' . mysqli_connect_error()]);
    exit;
}

// Manejar la solicitud POST para registrar un comentario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comentario = mysqli_real_escape_string($conn, $_POST['comment']); // Sanitiza el comentario

    // Obtener el nombre de usuario desde la sesión
    $usuario = isset($_SESSION['username']) ? $_SESSION['username'] : 'Usuario Anónimo';

    // Insertar el comentario en la base de datos
    $query = "INSERT INTO personas (comentario, created_at, usuario) VALUES ('$comentario', '$currentDateTime', '$usuario')";
    if (!mysqli_query($conn, $query)) {
        echo json_encode(['error' => 'Error en la inserción: ' . mysqli_error($conn)]);
        exit;
    }

    // Devolver el comentario recién insertado
    echo json_encode([
        'usuario' => $usuario,
        'comentario' => $comentario,
        'created_at' => $currentDateTime
    ]);
    exit;
}

// Manejar la solicitud GET para cargar los comentarios
$query = "SELECT * FROM personas ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['error' => 'Error en la consulta: ' . mysqli_error($conn)]);
    exit;
}

$comments = [];
while ($row = mysqli_fetch_assoc($result)) {
    $comments[] = $row;
}

echo json_encode($comments);

mysqli_close($conn);
?>
