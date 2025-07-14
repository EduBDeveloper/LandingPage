<?php
// Conexión a la base de datos
$host = 'localhost';
$dbname = 'bd_utic';
$username = 'root';
$password = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener los presupuestos disponibles
    $stmt = $pdo->query("SELECT id, numero_presupuesto, total FROM tbl_presupuesto");
    $presupuestos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener proveedores
    $stmtProv = $pdo->query("SELECT id, business_name FROM tbl_provider");
    $proveedores = $stmtProv->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Compras</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3>Crear Orden de Compra</h3>
    <form id="orderForm">
        <div class="form-group">
            <label for="presupuesto">Presupuesto</label>
            <select id="presupuesto" name="presupuesto" class="form-control" required>
                <option value="">Seleccione un presupuesto</option>
                <?php foreach ($presupuestos as $presupuesto) { ?>
                    <option value="<?php echo $presupuesto['id']; ?>">
                        Presupuesto #<?php echo $presupuesto['numero_presupuesto']; ?> - Total: <?php echo $presupuesto['total']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <!-- Más campos relevantes (proveedor, observaciones, etc.) -->

        <button type="submit" class="btn btn-primary">Guardar Orden</button>
    </form>
</div>
<script src="order.js"></script>
</body>
</html>
