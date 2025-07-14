<?php
require '../../global/connection.php';
$FILTER_PROD = $_POST["FILTER"];
$ESTADO_PROD = $_POST["ESTADO"];

if ($FILTER_PROD == "ALL") {
    $sqlquery_adic = "";
    if ($ESTADO_PROD != "ALL") {
        $sqlquery_adic = " WHERE tp.active_status = $ESTADO_PROD";
    }

    $sqlStatement = $pdo->prepare("
        SELECT tp.id AS IDPROD, tp.code AS CODE, tp.description AS DESCPROD, tp.name AS NOMPROD, tp.brand AS MARCA, 
        tp.stock_quantity AS CANTIDAD, ROUND(tp.unit_price, 2) AS PRECIO, tp.unit_value AS VALORMEDIDA, 
        tp.registration_date AS FECREG, tp.active_status AS ESTADO, deposito.nombre AS DEPO_NOMBRE
        FROM tbl_product tp 
        LEFT JOIN deposito ON tp.deposit_id = deposito.id 
        $sqlquery_adic 
        ORDER BY tp.id DESC
    ");
    $sqlStatement->execute();
    $rowsNumber = $sqlStatement->rowCount();
    $json_data = array();

    if ($rowsNumber > 0) {
        foreach ($sqlStatement as $ROW) {
            $ROWDATA = array(
                'ID' => $ROW["IDPROD"],
                'CODIGO' => $ROW["CODE"],
                'NOMBRE' => $ROW["NOMPROD"],
                'DESCPROD' => $ROW["DESCPROD"],
                'MARCA' => $ROW["MARCA"],
                'CANTIDAD' => $ROW["CANTIDAD"],
                'PRECIO' => $ROW["PRECIO"],
                'VALORMEDIDA' => $ROW["VALORMEDIDA"] ?: "-",
                'DEPO_NOMBRE' => $ROW["DEPO_NOMBRE"] ?: "Sin Depósito",
                'ESTADO' => $ROW["ESTADO"] == 1 ? "Activo" : "Inactivo",
                'FECREG' => date("d/m/Y H:i", strtotime($ROW["FECREG"]))
            );
            array_push($json_data, $ROWDATA);
        }
    }
    echo json_encode(array("data" => $json_data));
} else {
    $ID_REAL = str_replace("PROD-", "", $FILTER_PROD);

    $sqlquery_adic = "";
    if ($ESTADO_PROD != "ALL") {
        $sqlquery_adic = " AND active_status = $ESTADO_PROD";
    }

    $sqlStatement = $pdo->prepare("SELECT * FROM tbl_product WHERE id = :PRODID $sqlquery_adic");
    $sqlStatement->bindParam("PRODID", $ID_REAL, PDO::PARAM_INT);
    $sqlStatement->execute();
    $rowsNumber = $sqlStatement->rowCount();
    $json_data = array();

    if ($rowsNumber > 0) {
        foreach ($sqlStatement as $ROW) {
            $ROWDATA = array(
                'ID' => $ROW["id"],
                'CODIGO' => $ROW["code"],
                'DESCRIPTION' => $ROW["description"],
                'NOMBRE' => $ROW["name"],
                'MARCA' => $ROW["brand"],
                'CANTIDAD' => $ROW["stock_quantity"],
                'UNITVALUE' => $ROW["unit_value"],
                'PRECIO' => $ROW["unit_price"],
                'ESTADO' => $ROW["active_status"] == 1 ? "Activo" : "Inactivo"
            );
            array_push($json_data, $ROWDATA);
        }
    }
    echo json_encode($json_data);
}
?>
