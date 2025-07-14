<?php
require '../../global/connection.php';

$FAC_ID = $_POST["FAC_ID"];

$sqlStatement = $pdo->prepare("SELECT * FROM tbl_invoice_detail WHERE invoice_id = :FACID");
$sqlStatement->bindParam("FACID", $FAC_ID, PDO::PARAM_INT);
$sqlStatement->execute();

$rowsNumber = $sqlStatement->rowCount();
$json_data = [];

if ($rowsNumber > 0) {
    foreach ($sqlStatement as $ROW) {
        $ROWDATA = [
            'IDPROD' => $ROW["item_id"],
            'CODPROD' => $ROW["item_code"],
            'STOCKPROD' => $ROW["item_quantity"],
            'NOMBRE' => $ROW["item_name"],
            'DESCRIP' => $ROW["item_description"],
            'PRECIOUNIT' => $ROW["item_unit_price"],
            'CANTIDAD' => $ROW["item_quantity"],
            'IMPORTE' => $ROW["item_unit_price"] * $ROW["item_quantity"]
        ];
        $json_data[] = $ROWDATA;
    }
} else {
    // Si no hay detalles, devolver datos por defecto
    $json_data[] = [
        'IDPROD' => 'N/A',
        'CODPROD' => 'N/A',
        'STOCKPROD' => 0,
        'NOMBRE' => 'N/A',
        'DESCRIP' => 'N/A',
        'PRECIOUNIT' => 0,
        'CANTIDAD' => 0,
        'IMPORTE' => 0
    ];
}

echo json_encode($json_data);
?>
