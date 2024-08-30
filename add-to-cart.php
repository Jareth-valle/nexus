<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? null;

if (!$product_id) {
    echo json_encode(['status' => 'error', 'message' => 'Falta product_id']);
    exit;
}

// Aquí debes añadir el producto al carrito en la base de datos
// Ejemplo de código para insertar en la base de datos:
require_once 'db_connection.php'; // Incluye tu conexión a la base de datos

$query = "INSERT INTO carrito (user_id, product_id) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $user_id, $product_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Producto añadido al carrito']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al añadir el producto']);
}

$stmt->close();
$conn->close();
?>



