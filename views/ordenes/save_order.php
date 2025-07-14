<?php
require_once 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // Insertar en tbl_order
        $sqlOrder = "
            INSERT INTO tbl_order (
                type, status, currency, issue_date, delivery_date, provider_id, 
                payment_days, account_number, quotation, requester, approver, 
                observation, total_purchase, total_tax, total_net, exchange_rate_sale, exchange_rate_purchase
            ) VALUES (
                :type, :status, :currency, :issue_date, :delivery_date, :provider_id, 
                :payment_days, :account_number, :quotation, :requester, :approver, 
                :observation, :total_purchase, :total_tax, :total_net, :exchange_rate_sale, :exchange_rate_purchase
            )";

        $stmtOrder = $pdo->prepare($sqlOrder);
        $stmtOrder->execute([
            'type' => $_POST['type'],
            'status' => $_POST['status'],
            'currency' => $_POST['currency'],
            'issue_date' => $_POST['issue_date'],
            'delivery_date' => $_POST['delivery_date'],
            'provider_id' => $_POST['provider_id'],
            'payment_days' => $_POST['payment_days'],
            'account_number' => $_POST['account_number'],
            'quotation' => $_POST['quotation'],
            'requester' => $_POST['requester'],
            'approver' => $_POST['approver'],
            'observation' => $_POST['observation'],
            'total_purchase' => $_POST['total_purchase'],
            'total_tax' => $_POST['total_tax'],
            'total_net' => $_POST['total_net'],
            'exchange_rate_sale' => $_POST['exchange_rate_sale'],
            'exchange_rate_purchase' => $_POST['exchange_rate_purchase']
        ]);

        $orderId = $pdo->lastInsertId();

        // Insertar detalles en tbl_order_detail
        foreach ($_POST['details'] as $detail) {
            $sqlDetail = "
                INSERT INTO tbl_order_detail (
                    order_id, item_code, item_description, item_unit_value, 
                    item_unit_price, item_quantity, item_discount_rate, item_discounted_total, presupuesto_id
                ) VALUES (
                    :order_id, :item_code, :item_description, :item_unit_value, 
                    :item_unit_price, :item_quantity, :item_discount_rate, :item_discounted_total, :presupuesto_id
                )";

            $stmtDetail = $pdo->prepare($sqlDetail);
            $stmtDetail->execute([
                'order_id' => $orderId,
                'item_code' => $detail['item_code'],
                'item_description' => $detail['item_description'],
                'item_unit_value' => $detail['item_unit_value'],
                'item_unit_price' => $detail['item_unit_price'],
                'item_quantity' => $detail['item_quantity'],
                'item_discount_rate' => $detail['item_discount_rate'],
                'item_discounted_total' => $detail['item_discounted_total'],
                'presupuesto_id' => $detail['presupuesto_id']
            ]);
        }

        $pdo->commit();

        echo json_encode(['success' => true, 'message' => 'Orden guardada exitosamente.']);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
}
?>
