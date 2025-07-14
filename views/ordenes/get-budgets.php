<?php
require_once 'db.php'; // Conexión a la base de datos

$sql = "SELECT id, numero_presupuesto, total FROM tbl_presupuesto";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    $budgets = [];
    while ($row = $result->fetch_assoc()) {
        $budgets[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $budgets]);
} else {
    echo json_encode(['success' => false, 'data' => []]);
}

$db->close();
?>
