<?php
// Conexión a la base de datos
$host = 'localhost';
$dbname = 'bd_utic';
$username = 'root';
$password = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => "Error al conectar con la base de datos: " . $e->getMessage()]);
    exit;
}

// Verificar si se recibió el parámetro 'id'
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['error' => "ID de pedido no proporcionado."]);
    exit;
}

$pedido_id = intval($_GET['id']);

try {
    // Consulta para obtener los detalles del pedido
    $sql = "SELECT 
                dp.producto_id,
                p.name AS producto,
                dp.cantidad,
                dp.precio_unitario AS precio
            FROM tbl_pedido_detalles dp
            INNER JOIN tbl_product p ON dp.producto_id = p.id
            WHERE dp.pedido_id = :pedido_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':pedido_id', $pedido_id, PDO::PARAM_INT);
    $stmt->execute();

    $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($detalles)) {
        echo json_encode(['error' => "No se encontraron detalles para este pedido."]);
    } else {
        echo json_encode($detalles);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => "Error al obtener los detalles: " . $e->getMessage()]);
}
?>
