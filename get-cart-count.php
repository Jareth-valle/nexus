<?php
// Conectar a la base de datos
$mysqli = new mysqli("localhost", "usuario", "contraseña", "registros sitioweb");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$user_id = $_POST['user_id'];

$stmt = $mysqli->prepare("SELECT SUM(cantidad) AS cart_count FROM carrito WHERE usuario_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$stmt->close();
$mysqli->close();

echo json_encode(['cart_count' => $row['cart_count']]);
?>

