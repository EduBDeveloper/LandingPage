<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require '../../global/connection.php';
    session_start();

    $prov = $_POST['mov_prov'];
    $fec_venc = $_POST['mov_fec_venc'];
    $obs = trim($_POST['mov_obs']);
    $cant = $_POST['mov_cantidad'];
    $user_id = $_SESSION['loggedInUser']['USERID'];

    $sqlStatement = $pdo->prepare("INSERT INTO tbl_compra(proveedor_id, fecha_compra, observaciones, cantidad, user_id) VALUES(?, ?, ?, ?, ?)");

    if ($sqlStatement) {
        $sqlStatement->execute([$prov, $fec_venc, $obs, $cant, $user_id]);
        echo "OK_INSERT";
    } else {
        echo "ERROR";
    }

} else {
    echo "ERROR";
}

?>
