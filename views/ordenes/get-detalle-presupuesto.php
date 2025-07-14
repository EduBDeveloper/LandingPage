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


// Obtener el id del presupuesto desde la URL
$presupuesto_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta para obtener los detalles del presupuesto
$sql = "SELECT dp.id_producto, p.name AS descripcion, dp.cantidad, dp.precio_unitario 
        FROM tbl_detalle_presupuesto dp
        JOIN tbl_product p ON dp.id_producto = p.id
        WHERE dp.id_presupuesto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $presupuesto_id);
$stmt->execute();
$result = $stmt->get_result();

$detalles = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $detalles[] = $row;
    }
}

echo json_encode($detalles);

$stmt->close();
$conn->close();

?>
