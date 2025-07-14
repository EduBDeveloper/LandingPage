<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productos = $_POST['productos'];
    $cantidades = $_POST['cantidades'];
    $precios = $_POST['precios'];

    for ($i = 0; $i < count($productos); $i++) {
        $productoId = $productos[$i];
        $cantidad = $cantidades[$i];
        $precio = $precios[$i];

        $query = "SELECT * FROM tbl_product WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $productoId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $nuevaCantidad = $row['stock_quantity'] + $cantidad;

            $queryUpdate = "UPDATE tbl_product SET stock_quantity = ?, unit_price = ? WHERE id = ?";
            $stmtUpdate = $conn->prepare($queryUpdate);
            $stmtUpdate->bind_param("idi", $nuevaCantidad, $precio, $productoId);

            if ($stmtUpdate->execute()) {
                echo "Producto ID $productoId actualizado correctamente.";
            } else {
                echo "Error al actualizar el producto ID $productoId: " . $stmtUpdate->error;
            }
        } else {
            $queryInsert = "INSERT INTO tbl_product (id, stock_quantity, unit_price) VALUES (?, ?, ?)";
            $stmtInsert = $conn->prepare($queryInsert);
            $stmtInsert->bind_param("iid", $productoId, $cantidad, $precio);

            if ($stmtInsert->execute()) {
                echo "Producto ID $productoId insertado correctamente.";
            } else {
                echo "Error al insertar el producto ID $productoId: " . $stmtInsert->error;
            }
        }
    }
} else {
    echo "Método no permitido.";
}
?>
