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

// Manejar la actualización del estado del pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'], $_POST['nuevo_estado'])) {
    $pedido_id = $_POST['pedido_id'];
    $nuevo_estado = $_POST['nuevo_estado'];

    $stmt = $pdo->prepare("UPDATE tbl_pedidos SET estado = :nuevo_estado WHERE id = :pedido_id");
    $stmt->execute(['nuevo_estado' => $nuevo_estado, 'pedido_id' => $pedido_id]);
}
?>

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
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <div class="text-dark text-lg">
                            <i class="fas fa-list"></i>&nbsp;&nbsp;Lista de Pedidos
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
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("SELECT p.id, p.numero_pedido, p.fecha, p.estado, 
                                                 SUM(d.cantidad * d.precio_unitario) AS total
                                                 FROM tbl_pedidos p
                                                 LEFT JOIN tbl_pedido_detalles d ON p.id = d.pedido_id
                                                 GROUP BY p.id");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
                                echo "<tr class='pedido-row' data-id='{$row['id']}'>";
                                echo "<td>{$row['numero_pedido']}</td>";
                                echo "<td>{$row['fecha']}</td>";
                                echo "<td>{$row['total']}</td>";
                                echo "<td><span class='badge {$status_class}'>{$row['estado']}</span></td>";
                                echo "<td>
                                        <a href='view_order_details.php?id={$row['id']}' class='btn btn-info btn-sm'>
                                            <i class='fas fa-eye'></i> Ver Detalles
                                        </a>
                                    </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="context-menu" class="custom-context-menu">
        <a href="#" data-estado="pendiente">Cambiar a Pendiente</a>
        <a href="#" data-estado="aprobado">Cambiar a Aprobado</a>
        <a href="#" data-estado="cancelado">Cambiar a Cancelado</a>
        <a href="#" data-estado="anulado">Cambiar a Anulado</a>
    </div>

    <form id="update-status-form" method="POST" style="display: none;">
        <input type="hidden" name="pedido_id" id="pedido_id">
        <input type="hidden" name="nuevo_estado" id="nuevo_estado">
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let $contextMenu = $('#context-menu');

            $('.pedido-row').on('contextmenu', function(e) {
                e.preventDefault();
                let pedidoId = $(this).data('id');
                $('#pedido_id').val(pedidoId);

                $contextMenu.css({
                    top: e.pageY + 'px',
                    left: e.pageX + 'px'
                }).fadeIn(200);
            });

            $(document).on('click', function() {
                $contextMenu.fadeOut(200);
            });

            $contextMenu.on('click', 'a', function(e) {
                e.preventDefault();
                let nuevoEstado = $(this).data('estado');
                $('#nuevo_estado').val(nuevoEstado);
                $('#update-status-form').submit();
            });
        });
    </script>
</body>
</html>
