<?php
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    
    require '../../global/connection.php';
    
    // Obtener y gestionar el estado del producto
    $p_estado = isset($_POST['producto_estado']) && $_POST['producto_estado'] == 'on' ? 0 : 1;

    // Recibir los datos del producto desde el formulario
    $p_code = strtoupper(trim($_POST['producto_code']));
    $p_nombre = trim($_POST['producto_nombre']);
    $p_desc = trim($_POST['producto_description']);
    $p_marca = trim($_POST['producto_marca']);
    $p_unitvalue = $_POST['producto_unitvalue'];
    $p_precio = $_POST['producto_precio'];
    $p_deposito = $_POST['producto_deposito'] ?? null; // Depósito opcional
    $p_idprod = $_POST['producto_id'] ?? null;

    // Verificar si el producto ya existe en la base de datos
    if ($p_idprod) {
        // Actualizar producto existente
        $sqlStatement = $pdo->prepare("SELECT * FROM tbl_product WHERE name = :nameprod AND id <> :idprod");
        $sqlStatement->bindParam("nameprod", $p_nombre, PDO::PARAM_STR);
        $sqlStatement->bindParam("idprod", $p_idprod, PDO::PARAM_INT);
    } else {
        // Verificar si el nombre ya existe para un nuevo producto
        $sqlStatement = $pdo->prepare("SELECT * FROM tbl_product WHERE name = :nameprod");
        $sqlStatement->bindParam("nameprod", $p_nombre, PDO::PARAM_STR);
    }
    $sqlStatement->execute();

    // Si no existe un producto con el mismo nombre
    if ($sqlStatement->rowCount() == 0) {
        if ($p_idprod) {
            // Actualizar el producto existente
            $sqlStatement = $pdo->prepare("UPDATE tbl_product SET 
                code = ?, brand = ?, name = ?, description = ?, unit_price = ?, unit_value = ?, active_status = ?, deposit_id = ? WHERE id = ?");
            $sqlStatement->execute([$p_code, $p_marca, $p_nombre, $p_desc, $p_precio, $p_unitvalue, $p_estado, $p_deposito, $p_idprod]);
            echo "OK_UPDATE";
        } else {
            // Insertar nuevo producto
            $sqlStatement = $pdo->prepare("INSERT INTO tbl_product (code, brand, name, description, unit_price, unit_value, active_status, deposit_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $sqlStatement->execute([$p_code, $p_marca, $p_nombre, $p_desc, $p_precio, $p_unitvalue, $p_estado, $p_deposito]);
            echo "OK_INSERT";
        }
    } else {
        // Si el producto ya existe
        echo "EXISTE";
    }

} else {
    echo "ERROR"; // Si la solicitud no es AJAX
}
