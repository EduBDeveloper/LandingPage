<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Compra</title>
    <link rel="stylesheet" href="path/to/bootstrap.min.css">
    <link rel="stylesheet" href="path/to/fontawesome.css">
    <link rel="stylesheet" href="path/to/select2.min.css">
    <link rel="stylesheet" href="path/to/custom-styles.css">
    
</head>

<body>
    <!-- Header -->
    <header class="main-header">
        <!-- Aquí va el contenido del header -->
    </header>

    <!-- Sidebar -->
    <aside class="main-sidebar">
        <!-- Aquí va el contenido del sidebar -->
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-0">
                    <div class="col-md-12">
                        <h1 class="m-0 text-dark text-center"><i class="fas fa-receipt"></i>&nbsp;&nbsp;Orden de Compra</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="container-fluid">
                <div style="max-width: 1140px; margin: 0 auto;">
                    <!-- Formulario Orden de Compra -->
                    <form id="FRM_INSERT_DETA_ORDCOMPRA" method="post" action="modules/ordenes/insert-update-orden.php" enctype="multipart/form-data">
                        <input type="hidden" name="orden_id">

                        <!-- Datos de Orden -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Datos de Orden</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Fecha de Emisión</label>
                                        <input type="date" name="orden_fecemision" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Fecha de Entrega</label>
                                        <input type="date" name="orden_fecentrega" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Tipo de Moneda</label>
                                        <select name="orden_tipomoneda" class="form-control" required>
                                            <option value="">Seleccione</option>
                                            <option value="MN">Moneda Nacional</option>
                                            <option value="ME">Moneda Extranjera</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>N° Cotización</label>
                                        <input type="text" name="orden_cotizacion" class="form-control" placeholder="Número Cotización">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label>Forma de Pago</label>
                                        <select name="orden_tipopagotext" class="form-control" required>
                                            <option value="">Seleccione</option>
                                            <option value="0">Contado</option>
                                            <option value="15">15 días</option>
                                            <option value="30">30 días</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Solicitado por</label>
                                        <input type="text" name="orden_solicitante" class="form-control" placeholder="Nombre del solicitante" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Autorizado por</label>
                                        <input type="text" name="orden_autorizador" class="form-control" placeholder="Nombre del autorizador" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Estado</label>
                                        <select name="orden_estado" class="form-control">
                                            <option value="Pendiente">Pendiente</option>
                                            <option value="Aprobado">Aprobado</option>
                                            <option value="Anulado">Anulado</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detalles del Presupuesto -->
                        <div class="card card-danger">
    <div class="card-header">
        <h3 class="card-title">Detalles del Presupuesto</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <label>Presupuesto</label>
                <s<form action="procesar_presupuesto.php" method="POST">
    <label for="presupuesto">Selecciona un presupuesto:</label>
    <select name="presupuesto_id" id="presupuesto">
        <option value="">Seleccione un presupuesto</option>

        <?php
        // Verifica si se obtuvieron resultados de la consulta
        if ($result->num_rows > 0) {
            // Muestra los resultados en el select
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['id'] . '">' . $row['numero_presupuesto'] . '</option>';
            }
        } else {
            echo '<option value="">No hay presupuestos disponibles</option>';
        }
        ?>

    </select>
    <input type="submit" value="Ver detalles">
</form>

<?php
$conn->close();
?>


            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-4">
                <label>Código</label>
                <input type="text" id="codigo_articulo" class="form-control" readonly>
            </div>
            <div class="col-md-8">
                <label>Descripción</label>
                <input type="text" id="descripcion_articulo" class="form-control" readonly>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-2">
                <label>Precio Unitario</label>
                <input type="text" id="precio_unitario" class="form-control" readonly>
            </div>
            <div class="col-md-2">
                <label>Cantidad</label>
                <input type="number" id="cantidad" class="form-control">
            </div>
            <div class="col-md-2">
                <label>% Descuento</label>
                <input type="number" id="descuento" class="form-control" value="0">
            </div>
            <div class="col-md-3">
                <label>Valor Total</label>
                <input type="text" id="valor_total" class="form-control" readonly>
            </div>
            <div class="col-md-3">
                <label>IVA</label>
                <input type="text" id="valor_igv" class="form-control" readonly>
            </div>
        </div>
    </div>
</div>


                        <!-- Botón Registrar -->
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success btn-block">Registrar Orden</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <!-- Aquí va el contenido del footer -->
    </footer>
    <script>
    function cargarDatosPresupuesto() {
        let presupuestoId = $('#select_presupuesto').val();

        if (presupuestoId) {
            $.ajax({
                url: 'get-detalle-presupuesto.php', // Ruta del archivo PHP
                method: 'GET',
                data: { id: presupuestoId },
                dataType: 'json',
                success: function(response) {
                    if (response.length > 0) {
                        // Ejemplo: Mostrar los datos del primer producto en el formulario
                        $('#codigo_articulo').val(response[0].id_producto);
                        $('#descripcion_articulo').val(response[0].descripcion);
                        $('#precio_unitario').val(response[0].precio_unitario);
                        $('#cantidad').val(response[0].cantidad);
                        // Ajusta para múltiples productos si es necesario
                    } else {
                        alert('No se encontraron datos para el presupuesto seleccionado.');
                    }
                },
                error: function(error) {
                    console.error('Error al cargar los datos del presupuesto:', error);
                }
            });
        }
    }
</script>


</body>

</html>
    