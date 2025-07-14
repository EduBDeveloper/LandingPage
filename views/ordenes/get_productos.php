<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$dbname = "bd_utic";

$conn = new mysqli($host, $user, $password, $dbname);

// Verifica si la conexión es exitosa
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>


header('Content-Type: application/json'); // Define que el contenido será JSON

// Consulta para obtener los productos
$query = "SELECT id, description FROM tbl_product";

try {
    // Prepara y ejecuta la consulta
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Generar un arreglo con los datos
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Enviar los datos como JSON
    echo json_encode($productos);
} catch (PDOException $e) {
    // Manejo de errores en la consulta
    echo json_encode(['error' => 'Error al ejecutar la consulta: ' . $e->getMessage()]);
}
?>
