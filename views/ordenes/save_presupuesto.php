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

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Iniciar una transacción
        $pdo->beginTransaction();

        // Calcular el total del presupuesto
        $total = 0;
        foreach ($_POST['cantidades'] as $index => $cantidad) {
            $precio = $_POST['precios'][$index];
            $total += $cantidad * $precio;
        }

        // Insertar los datos principales del presupuesto
        $stmt = $pdo->prepare("
            INSERT INTO tbl_presupuesto
            (numero_presupuesto, fecha_hora, proveedor_id, pedido_id, total) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_POST['numero_presupuesto'], // Número del presupuesto
            $_POST['fecha_hora'],         // Fecha y hora del presupuesto
            $_POST['proveedor_id'],       // ID del proveedor
            $_POST['pedido_id'],          // ID del pedido relacionado
            $total                        // Total calculado
        ]);

        $presupuestoId = $pdo->lastInsertId(); // Obtener el ID del presupuesto recién creado

        // Insertar los detalles del presupuesto
        $stmtDetalle = $pdo->prepare("
            INSERT INTO  tbl_detalle_presupuesto
            (id_presupuesto, id_producto, cantidad, precio_unitario) 
            VALUES (?, ?, ?, ?)
        ");
        foreach ($_POST['producto_ids'] as $index => $productoId) {
            $cantidad = $_POST['cantidades'][$index];
            $precio = $_POST['precios'][$index];
            $stmtDetalle->execute([$presupuestoId, $productoId, $cantidad, $precio]);
        }

        // Confirmar la transacción
        $pdo->commit();
        echo "Presupuesto guardado correctamente.";
    } catch (PDOException $e) {
        // Revertir la transacción en caso de error
        $pdo->rollBack();
        die("Error al guardar presupuesto: " . $e->getMessage());
    }
}
?>
