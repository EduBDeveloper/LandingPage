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

// Manejar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productos = $_POST['productos'] ?? []; // Array de productos con cantidad
    if (empty($productos)) {
        $mensaje = "Error: Debes agregar al menos un producto.";
    } else {
        try {
            // Obtener el número de pedido
            $stmt = $pdo->query("SELECT COALESCE(MAX(numero_pedido), 0) + 1 AS nuevo_pedido FROM tbl_pedidos");
            $nuevo_pedido = $stmt->fetchColumn();

            // Insertar el pedido en tbl_pedidos
            $sql_pedido = "INSERT INTO tbl_pedidos (numero_pedido, estado) VALUES (:numero_pedido, 'pendiente')";
            $stmt_pedido = $pdo->prepare($sql_pedido);
            $stmt_pedido->bindParam(':numero_pedido', $nuevo_pedido);
            $stmt_pedido->execute();
            $pedido_id = $pdo->lastInsertId();

            // Insertar productos en tbl_pedido_detalles
            $sql_detalles = "INSERT INTO tbl_pedido_detalles (pedido_id, producto_id, cantidad, precio_unitario) 
                             VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario)";
            $stmt_detalles = $pdo->prepare($sql_detalles);

            foreach ($productos as $producto) {
                $producto_id = intval($producto['producto_id']);
                $cantidad = intval($producto['cantidad']);
                $stmt_precio = $pdo->prepare("SELECT precio FROM tbl_product WHERE id = :producto_id");
                $stmt_precio->execute(['producto_id' => $producto_id]);
                $precio_unitario = $stmt_precio->fetchColumn();

                $stmt_detalles->execute([
                    'pedido_id' => $pedido_id,
                    'producto_id' => $producto_id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio_unitario
                ]);
            }

            $mensaje = "Pedido registrado con éxito. Número de pedido: $nuevo_pedido.";
        } catch (Exception $e) {
            $mensaje = "Error al registrar el pedido: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Pedidos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="content-wrapper">
        <!-- Header Section -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <div class="text-dark text-lg">
                            <i class="fas fa-shopping-cart"></i>&nbsp;&nbsp;Registro de Pedidos
                        </div>
                    </div>
                </div>
            </div>
        </div>

       <!-- Form Section -->
<div class="content">
    <div class="container-fluid">
        <div style="max-width: 1140px; margin: 0 auto;">
            <form method="POST">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="card-title">Datos del Pedido</div>
                    </div>
                    <div class="card-body">
                        <!-- Campos de Fecha y Hora -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha</label>
                                    <input type="text" class="form-control" value="<?php echo date('d/m/Y'); ?>" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hora</label>
                                    <input type="text" class="form-control" value="<?php echo date('H:i:s'); ?>" disabled>
                                </div>
                            </div>
                        </div>

                        <!-- Contenedor de productos -->
                        <div id="productos-container">
                            <div class="row producto-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Producto</label>
                                        <select name="productos[0][producto_id]" class="form-control" required>
                                            <option value="">Seleccione un producto</option>
                                            <?php
                                            $stmt = $pdo->query("SELECT id, description FROM tbl_product");
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='{$row['id']}'>{$row['description']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Cantidad</label>
                                        <input type="number" name="productos[0][cantidad]" class="form-control" min="1" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (isset($mensaje)): ?>
                    <div class="alert alert-info">
                        <?php echo $mensaje; ?>
                    </div>
                <?php endif; ?>

                <!-- Botones -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <button type="button" id="add-product" class="btn btn-secondary btn-block">
                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar otro producto
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;Registrar Pedido
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

    <script>
        let productoIndex = 1;
        document.getElementById('add-product').addEventListener('click', () => {
            const container = document.getElementById('productos-container');
            const newRow = document.createElement('div');
            newRow.classList.add('row', 'producto-row');
            newRow.innerHTML = `
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Producto</label>
                        <select name="productos[${productoIndex}][producto_id]" class="form-control" required>
                            <option value="">Seleccione un producto</option>
                            <?php
                            $stmt = $pdo->query("SELECT id, description FROM tbl_product");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$row['id']}'>{$row['description']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Cantidad</label>
                        <input type="number" name="productos[${productoIndex}][cantidad]" class="form-control" min="1" required>
                    </div>
                </div>
            `;
            container.appendChild(newRow);
            productoIndex++;
        });
    </script>
</body>
</html>
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

// Variables de paginación
$limite = 5;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $limite;

// Contar el total de pedidos
$stmt_total = $pdo->query("SELECT COUNT(*) AS total FROM tbl_pedidos");
$total_pedidos = $stmt_total->fetchColumn();
$total_paginas = ceil($total_pedidos / $limite);

// Obtener los pedidos para la página actual
$stmt = $pdo->prepare("SELECT p.id, p.numero_pedido, p.fecha, p.estado, 
                        SUM(d.cantidad * d.precio_unitario) AS total
                        FROM tbl_pedidos p
                        LEFT JOIN tbl_pedido_detalles d ON p.id = d.pedido_id
                        GROUP BY p.id
                        LIMIT :limite OFFSET :offset");
$stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<br><br><br>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pedidos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        .custom-context-menu {
            display: none;
            position: absolute;
            z-index: 1000;
            width: 200px;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }
        .custom-context-menu a {
            display: block;
            padding: 8px 16px;
            color: #333;
            text-decoration: none;
        }
        .custom-context-menu a:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <h3 class="text-dark text-lg">
                    <i class="fas fa-list"></i>&nbsp;&nbsp;Lista de Pedidos
                </h3>
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
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedidos as $row): ?>
                                <?php
                                $status_class = '';
                                switch ($row['estado']) {
                                    case 'pendiente':
                                        $status_class = 'badge-warning';
                                        break;
                                    case 'aprobado':
                                        $status_class = 'badge-success';
                                        break;
                                    case 'cancelado':
                                        $status_class = 'badge-danger';
                                        break;
                                    case 'anulado':
                                        $status_class = 'badge-secondary';
                                        break;
                                }
                                ?>
                                <tr>
                                    <td><?php echo $row['numero_pedido']; ?></td>
                                    <td><?php echo $row['fecha']; ?></td>
                                    <td><?php echo number_format($row['total'], 2); ?></td>
                                    <td><span class="badge <?php echo $status_class; ?>"><?php echo $row['estado']; ?></span></td>
                                    <td>
    <a href="view_order_details.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">
        <i class="fas fa-eye"></i> Ver Detalles
    </a>
    <a href="print_order.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary btn-sm" target="_blank">
        <i class="fas fa-print"></i> Imprimir
    </a>
    <a href="generate_pdf.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">
        <i class="fas fa-file-pdf"></i> Descargar PDF
    </a>
</td>

                                    
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Paginación -->
                    <nav>
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                <li class="page-item <?php echo ($i === $pagina_actual) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?pagina=<?php echo $i; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
