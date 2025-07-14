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
    die("Error al conectar con la base de datos: " . $e->getMessage());
}

try {
    // Conexión con PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Capturar datos del formulario
    $orderData = json_decode($_POST['orderData'], true);
    $orderDetails = json_decode($_POST['orderDetails'], true);

    // Iniciar transacción
    $conn->beginTransaction();

    // Insertar datos en tbl_order
    $sqlOrder = "
        INSERT INTO tbl_order (type, number, status, currency, issue_date, delivery_date, provider_id, 
        payment_days, account_number, quotation, requester, approver, observation, total_purchase, total_tax, total_net, 
        exchange_rate_sale, exchange_rate_purchase) 
        VALUES (:type, :number, :status, :currency, :issue_date, :delivery_date, :provider_id, :payment_days, 
        :account_number, :quotation, :requester, :approver, :observation, :total_purchase, :total_tax, :total_net, 
        :exchange_rate_sale, :exchange_rate_purchase)
    ";

    $stmtOrder = $conn->prepare($sqlOrder);
    $stmtOrder->execute([
        ':type' => $orderData['type'],
        ':number' => $orderData['number'],
        ':status' => $orderData['status'],
        ':currency' => $orderData['currency'],
        ':issue_date' => $orderData['issue_date'],
        ':delivery_date' => $orderData['delivery_date'],
        ':provider_id' => $orderData['provider_id'],
        ':payment_days' => $orderData['payment_days'],
        ':account_number' => $orderData['account_number'],
        ':quotation' => $orderData['quotation'],
        ':requester' => $orderData['requester'],
        ':approver' => $orderData['approver'],
        ':observation' => $orderData['observation'],
        ':total_purchase' => $orderData['total_purchase'],
        ':total_tax' => $orderData['total_tax'],
        ':total_net' => $orderData['total_net'],
        ':exchange_rate_sale' => $orderData['exchange_rate_sale'],
        ':exchange_rate_purchase' => $orderData['exchange_rate_purchase'],
    ]);

    // Obtener el ID de la orden insertada
    $orderId = $conn->lastInsertId();

    // Insertar detalles de la orden en tbl_order_detail
    $sqlDetail = "
        INSERT INTO tbl_order_detail (order_id, item_code, item_description, item_gloss, item_unit_value, 
        item_unit_price, item_quantity, item_discount_rate, item_discounted_total, presupuesto_id) 
        VALUES (:order_id, :item_code, :item_description, :item_gloss, :item_unit_value, :item_unit_price, 
        :item_quantity, :item_discount_rate, :item_discounted_total, :presupuesto_id)
    ";

    $stmtDetail = $conn->prepare($sqlDetail);

    foreach ($orderDetails as $detail) {
        $stmtDetail->execute([
            ':order_id' => $orderId,
            ':item_code' => $detail['item_code'],
            ':item_description' => $detail['item_description'],
            ':item_gloss' => $detail['item_gloss'],
            ':item_unit_value' => $detail['item_unit_value'],
            ':item_unit_price' => $detail['item_unit_price'],
            ':item_quantity' => $detail['item_quantity'],
            ':item_discount_rate' => $detail['item_discount_rate'],
            ':item_discounted_total' => $detail['item_discounted_total'],
            ':presupuesto_id' => $detail['presupuesto_id'],
        ]);
    }

    // Confirmar transacción
    $conn->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Orden registrada con éxito",
        "order_id" => $orderId,
    ]);
} catch (PDOException $e) {
    // Revertir transacción en caso de error
    $conn->rollBack();

    echo json_encode([
        "status" => "error",
        "message" => "Error al registrar la orden: " . $e->getMessage(),
    ]);
} finally {
    $conn = null; // Cerrar conexión
}
?>