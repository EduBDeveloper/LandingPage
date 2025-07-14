<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-md-12">
                    <div class="m-0 text-dark text-center text-lg">
                        <i class="fas fa-people-carry"></i>&nbsp;&nbsp;Registro de Depósito
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div style="max-width: 1140px; margin: 0 auto;">
                <form id="FRM_INSERT_DEPOSITO" method="post" action="insert-update-deposito.php" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="card-title">Datos del Depósito</div>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Colapsar">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nombre del Depósito</label>
                                        <input type="text" class="form-control" name="nombre" placeholder="Ingrese el nombre del depósito" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ubicación</label>
                                        <input type="text" class="form-control" name="ubicacion" placeholder="Ingrese la ubicación" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Capacidad (en m³)</label>
                                        <input type="number" step="0.01" class="form-control" name="capacidad" placeholder="Ingrese la capacidad del depósito" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fa fa-save"></i>&nbsp;&nbsp;Guardar Depósito
                        </button>
                    </div>
                </form>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-depositos" class="table table-bordered table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Ubicación</th>
                                    <th>Capacidad</th>
                                    <th>Fecha de Registro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Los datos se cargarán dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    function loadDepositos() {
        fetch('get_depositos.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al cargar los depósitos');
                }
                return response.json();
            })
            .then(data => {
                const tableBody = document.querySelector('#table-depositos tbody');
                tableBody.innerHTML = ''; // Limpiar tabla
                data.forEach(deposito => {
                    const row = `
                        <tr>
                            <td>${deposito.id}</td>
                            <td>${deposito.codigo}</td>
                            <td>${deposito.nombre}</td>
                            <td>${deposito.ubicacion}</td>
                            <td>${deposito.capacidad}</td>
                            <td>${deposito.fecha_registro}</td>
                        </tr>`;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            })
            .catch(error => console.error(error));
    }

    // Cargar depósitos al cargar la página
    loadDepositos();
});
</script>
