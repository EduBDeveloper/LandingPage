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

// Obtener datos para los desplegables
try {
    $pedidosStmt = $pdo->query("SELECT id, numero_pedido FROM tbl_pedidos");
    $pedidos = $pedidosStmt->fetchAll(PDO::FETCH_ASSOC);

    $proveedoresStmt = $pdo->query("SELECT id, business_name FROM tbl_provider");
    $proveedores = $proveedoresStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener datos: " . $e->getMessage());
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-md-12">
                    <div class="m-0 text-dark text-center text-lg">
                        <i class="fas fa-file-alt"></i>&nbsp;&nbsp;Generar Presupuesto
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div style="max-width: 1140px; margin: 0 auto;">
                <form id="FRM_PRESUPUESTO" method="post" action="save_presupuesto.php">
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="card-title">Datos del Presupuesto</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Número de Presupuesto</label>
                                        <input type="text" class="form-control" name="numero_presupuesto" readonly value="<?php echo time(); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Fecha y Hora</label>
                                        <input type="text" class="form-control" name="fecha_hora" readonly value="<?php echo date('Y-m-d H:i:s'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Proveedor</label>
                                        <select class="form-control" name="proveedor_id" required>
                                            <option value="">Seleccione un proveedor</option>
                                            <?php foreach ($proveedores as $prov) { ?>
                                                <option value="<?php echo $prov['id']; ?>">
                                                    <?php echo htmlspecialchars($prov['business_name']); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Pedido</label>
                                        <select class="form-control" name="pedido_id" id="pedido_id" required>
                                            <option value="">Seleccione un pedido</option>
                                            <?php foreach ($pedidos as $pedido) { ?>
                                                <option value="<?php echo $pedido['id']; ?>">
                                                    Pedido #<?php echo htmlspecialchars($pedido['numero_pedido']); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="detalle_pedido" style="display: none;">
                                <h5>Detalle del Pedido</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio Unitario</th>
                                            <th>Subtotal</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pedido_detalles">
                                        <!-- Detalles dinámicos cargados con JavaScript -->
                                    </tbody>
                                </table>
                                <div class="text-right">
                                    <button type="button" class="btn btn-primary" id="btnAgregarProducto">
                                        <i class="fa fa-plus"></i> Añadir Producto
                                    </button>
                                    <h5>Total: <span id="total_pedido">0.00</span></h5>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Guardar
                            </button>
                            <button type="button" class="btn btn-danger" onclick="window.history.back();">
                                <i class="fa fa-times"></i> Cancelar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('pedido_id').addEventListener('change', function () {
    const pedidoId = this.value;

    if (pedidoId) {
        fetch(`get_pedido_details.php?id=${pedidoId}`)
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('pedido_detalles');
                tbody.innerHTML = '';

                if (data.error) {
                    alert(data.error);
                    return;
                }

                let total = 0;

                data.forEach(item => {
                    const subtotal = item.cantidad * item.precio;
                    total += subtotal;

                    tbody.innerHTML += generarFilaProducto(item.producto_id, item.producto, item.cantidad, item.precio, subtotal);
                });

                document.getElementById('total_pedido').textContent = total.toFixed(2);
                document.getElementById('detalle_pedido').style.display = 'block';

                agregarEventosActualizarTotales();
            })
            .catch(error => {
                alert('Error al cargar los detalles del pedido.');
                console.error(error);
            });
    } else {
        document.getElementById('detalle_pedido').style.display = 'none';
    }
});
function generarFilaProducto(id = '', producto = '', cantidad = 1, precio = 0, subtotal = 0) {
    return `
        <tr>
            <td>
                <select class="form-control select-producto" name="producto_ids[]">
                    <option value="${id}" selected>${producto ? producto : 'Seleccionar producto'}</option>
                </select>
            </td>
            <td>
                <input type="number" name="cantidades[]" value="${cantidad}" class="form-control" min="1">
            </td>
            <td>
                <input type="number" name="precios[]" value="${precio}" class="form-control" step="0.01" min="0">
            </td>
            <td class="subtotal">${subtotal.toFixed(2)}</td>
            <td>
                <button type="button" class="btn btn-danger btnEliminarProducto">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
    `;
}
function cargarProductosEnSelect(select) {
    fetch('get_productos.php') // Archivo PHP para obtener los productos
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            select.innerHTML = '<option value="">Seleccionar producto</option>';
            data.forEach(producto => {
                const option = document.createElement('option');
                option.value = producto.id;
                option.textContent = producto.description;
                select.appendChild(option);
            });
        })
        .catch(error => {
            alert('Error al cargar los productos.');
            console.error(error);
        });
}


document.getElementById('btnAgregarProducto').addEventListener('click', function () {
    const tbody = document.getElementById('pedido_detalles');
    tbody.innerHTML += generarFilaProducto();

    // Obtener el último select creado
    const select = tbody.querySelector('tr:last-child .select-producto');
    cargarProductosEnSelect(select);

    agregarEventosActualizarTotales();
    agregarEventosEliminar();
});


function agregarEventosEliminar() {
    document.querySelectorAll('.btnEliminarProducto').forEach(btn => {
        btn.addEventListener('click', function () {
            this.closest('tr').remove();
            actualizarTotal();
        });
    });
}
async function cargarProductos(select) {
    try {
        const response = await fetch('get_productos.php'); // Reemplaza con tu ruta
        const productos = await response.json();
        productos.forEach(producto => {
            const option = document.createElement('option');
            option.value = producto.id;
            option.textContent = producto.description;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Error al cargar productos:', error);
    }
}

function agregarEventosActualizarTotales() {
    const inputsCantidad = document.querySelectorAll('input[name="cantidades[]"]');
    const inputsPrecio = document.querySelectorAll('input[name="precios[]"]');
    const subtotales = document.querySelectorAll('.subtotal');

    function actualizarTotal() {
        let total = 0;

        inputsCantidad.forEach((input, index) => {
            const cantidad = parseFloat(inputsCantidad[index].value) || 0;
            const precio = parseFloat(inputsPrecio[index].value) || 0;
            const subtotal = cantidad * precio;

            subtotales[index].textContent = subtotal.toFixed(2);
            total += subtotal;
        });

        document.getElementById('total_pedido').textContent = total.toFixed(2);
    }

    inputsCantidad.forEach(input => input.addEventListener('input', actualizarTotal));
    inputsPrecio.forEach(input => input.addEventListener('input', actualizarTotal));
}

// Llama inicialmente a la función para configurar eventos en filas dinámicas
agregarEventosEliminar();
</script>
