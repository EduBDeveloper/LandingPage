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

// Obtener el ID del pedido
$pedido_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consultar detalles del pedido
$stmt_pedido = $pdo->prepare("SELECT * FROM tbl_pedidos WHERE id = :pedido_id");
$stmt_pedido->execute(['pedido_id' => $pedido_id]);
$pedido = $stmt_pedido->fetch(PDO::FETCH_ASSOC);

// Consultar los productos del pedido
$stmt_detalles = $pdo->prepare("SELECT p.description AS producto, d.cantidad, d.precio_unitario 
                                FROM tbl_pedido_detalles d
                                INNER JOIN tbl_product p ON d.producto_id = p.id
                                WHERE d.pedido_id = :pedido_id");
$stmt_detalles->execute(['pedido_id' => $pedido_id]);
$productos = $stmt_detalles->fetchAll(PDO::FETCH_ASSOC);

if (!$pedido) {
    die("Pedido no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir Pedido</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col text-center">
                <h2>Pedido N° <?php echo $pedido['numero_pedido']; ?></h2>
                <p>Estado: <strong><?php echo ucfirst($pedido['estado']); ?></strong></p>
                <p>Fecha: <?php echo date('d/m/Y', strtotime($pedido['fecha'])); ?></p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <h4>Detalles del Pedido</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?php echo $producto['producto']; ?></td>
                                <td><?php echo $producto['cantidad']; ?></td>
                                <td><?php echo number_format($producto['precio_unitario'], 2); ?></td>
                                <td><?php echo number_format($producto['cantidad'] * $producto['precio_unitario'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <h5 class="text-right">
                    Total: 
                    <?php
                    $total = array_reduce($productos, function ($sum, $producto) {
                        return $sum + ($producto['cantidad'] * $producto['precio_unitario']);
                    }, 0);
                    echo number_format($total, 2);
                    ?>
                </h5>
            </div>
        </div>
        <div class="row no-print mt-4">
            <div class="col text-center">
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimir
                </button>
                <a href="list_orders.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</body>
</html>
