<?php
// get_pedido.php
require 'config.php'; // Asegúrate de incluir el archivo de conexión a la base de datos

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM pedidos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $pedido = $result->fetch_assoc();
        echo json_encode($pedido);
    } else {
        echo json_encode([]);
    }
}
?>
