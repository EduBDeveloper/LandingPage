<?php
require '../../global/connection.php';

if (isset($_GET['purchase_detail_id'])) {
    $purchaseDetailId = $_GET['purchase_detail_id'];

    $query = "SELECT pd.item_code, pd.description, pd.quantity, pd.unit_price, (pd.quantity * pd.unit_price) AS total_price
              FROM tbl_purchase_detail pd
              WHERE pd.id = :purchase_detail_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':purchase_detail_id', $purchaseDetailId, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($details) {
            echo json_encode([
                'status' => 'success',
                'details' => $details
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No se encontraron detalles para el ID proporcionado.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al ejecutar la consulta.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID de detalle de compra no proporcionado.'
    ]);
}
?>
