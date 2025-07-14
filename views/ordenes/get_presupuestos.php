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


// Consulta para obtener los presupuestos disponibles
$sql = "SELECT id, numero_presupuesto, total FROM tbl_presupuesto";
$result = $conn->query($sql);

$presupuestos = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $presupuestos[] = $row;
    }
}

echo json_encode($presupuestos);

$conn->close();
?>
