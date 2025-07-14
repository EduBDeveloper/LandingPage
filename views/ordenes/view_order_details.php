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

$pedido_id = $_GET['id'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Pedido</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <div class="text-dark text-lg">
                            <i class="fas fa-eye"></i>&nbsp;&nbsp;Detalles del Pedido
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div style="max-width: 1140px; margin: 0 auto;">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Consultar detalles del pedido
                            $stmt = $pdo->prepare("SELECT d.cantidad, d.precio_unitario, 
                                                   p.description AS producto
                                                   FROM tbl_pedido_detalles d
                                                   JOIN tbl_product p ON d.producto_id = p.id
                                                   WHERE d.pedido_id = :pedido_id");
                            $stmt->execute(['pedido_id' => $pedido_id]);
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $subtotal = $row['cantidad'] * $row['precio_unitario'];
                                echo "<tr>";
                                echo "<td>{$row['producto']}</td>";
                                echo "<td>{$row['cantidad']}</td>";
                                echo "<td>{$row['precio_unitario']}</td>";
                                echo "<td>{$subtotal}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
