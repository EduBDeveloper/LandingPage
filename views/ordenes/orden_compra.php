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

// Función para obtener los detalles de un presupuesto
function obtenerDetallesPresupuesto($presupuesto_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT pd.id_producto, p.description AS producto, pd.cantidad, pd.precio_unitario
                           FROM tbl_detalle_presupuesto pd
                           JOIN tbl_product p ON pd.id_producto = p.id
                           WHERE pd.id_presupuesto = ?");
    $stmt->execute([$presupuesto_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Iniciar transacción
        $pdo->beginTransaction();

        // Insertar la orden de compra
        $stmtOrden = $pdo->prepare("INSERT INTO tbl_order
            (type, number, status, currency, issue_date, delivery_date, provider_id, payment_days, account_number, quotation, requester, approver, observation, total_purchase, total_tax, total_net, exchange_rate_sale, exchange_rate_purchase)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtOrden->execute([
            $_POST['type'],
            $_POST['number'],
            $_POST['status'],
            $_POST['currency'],
            $_POST['issue_date'],
            $_POST['delivery_date'],
            $_POST['provider_id'],
            $_POST['payment_days'],
            $_POST['account_number'],
            $_POST['quotation'],
            $_POST['requester'],
            $_POST['approver'],
            $_POST['observation'],
            $_POST['total_purchase'],
            $_POST['total_tax'],
            $_POST['total_net'],
            $_POST['exchange_rate_sale'],
            $_POST['exchange_rate_purchase']
        ]);

        $order_id = $pdo->lastInsertId();  // Obtener el ID de la orden de compra recién insertada

        // Insertar detalles de la orden de compra
        foreach ($_POST['producto_ids'] as $index => $producto_id) {
            $cantidad = $_POST['cantidades'][$index];
            $precio = $_POST['precios'][$index];
            $item_total = $cantidad * $precio;
            $stmtDetalle = $pdo->prepare("INSERT INTO tbl_order_detail (order_id, presupuesto_id, item_code, item_description, item_gloss, item_unit_value, item_unit_price, item_quantity, item_discount_rate, item_discounted_total)
                                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtDetalle->execute([
                $order_id,
                $_POST['presupuesto_id'],
                '',  // Código del producto, puedes agregar lógica si es necesario
                '',  // Descripción del producto, puedes agregar lógica si es necesario
                '',  // Gloss (detalles adicionales)
                '',  // Valor unitario
                $precio,
                $cantidad,
                0,   // Descuento (puedes agregar lógica si es necesario)
                $item_total
            ]);
        }

        // Confirmar la transacción
        $pdo->commit();
        echo "Orden de compra guardada correctamente.";
    } catch (PDOException $e) {
        // Revertir la transacción en caso de error
        $pdo->rollBack();
        die("Error al guardar la orden de compra: " . $e->getMessage());
    }
} else {
    $presupuesto_id = isset($_GET['presupuesto_id']) ? $_GET['presupuesto_id'] : null;
    if ($presupuesto_id) {
        $detallesPresupuesto = obtenerDetallesPresupuesto($presupuesto_id);
    } else {
        die('Presupuesto no válido.');
    }
}
?>
