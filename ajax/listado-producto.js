$(document).ready(function() {
    $("#m_almacen").attr("class", "nav-link active");
    $("#m_almacen").parent().attr("class", "nav-item has-treeview menu-open");
    $("#m_listado_producto").attr("class", "nav-link active");
    $(document).prop('title', 'Listado de Productos - EduBWeb');
});

var tabla_productos = $('#table-productos');

tabla_productos.DataTable({
    "ajax": {
        "url": "../../modules/productos/consultar-productos.php",
        "type": "POST",
        "data": { "FILTER": "ALL", "ESTADO": "ALL" },
        "dataSrc": function(json) {
            console.log(json); // Muestra los datos devueltos
            return json.data;
        }
    },
    "columns": [
        { "data": "ID" },
        { "data": "CODIGO" },
        { "data": "MARCA" },
        { "data": "NOMBRE" },
        { "data": "DESCPROD" },
        { "data": "VALORMEDIDA" },
        { "data": "CANTIDAD" },
        { "data": "PRECIO" },
        { "data": "ESTADO" },
        { "data": "DEPO_NOMBRE" }
    ],
    "order": [
        [0, "DESC"]
    ],
    "columnDefs": [
        { targets: 9, width: "15%" }
    ],
    dom: 'Bfrtip',
    buttons: [{
            text: '<i class="fa fa-plus-square fa-1x"></i>&nbsp;&nbsp;Nuevo producto',
            action: function(e, dt, node, config) {
                window.location.assign("../../views/productos/registro-producto");
            }
        },
        {
            extend: 'csv',
            text: '<i class="fa fa-file-csv"></i>&nbsp;&nbsp;Descargar CSV'
        },
        {
            extend: 'excel',
            text: '<i class="fa fa-file-excel"></i>&nbsp;&nbsp;Descargar Excel'
        },
        {
            extend: 'print',
            text: '<i class="fa fa-print"></i>&nbsp;&nbsp;Imprimir'
        }
    ],
    "language": {
        "url": "../../plugins/datatables/Spanish.json"
    }
});


tabla_productos.on('click', 'tr', function() {
    var data = tabla_productos.DataTable().row(this).data();
    if (data == null) return;

    console.log("Depósito: ", data["DEPO_NOMBRE"]); // Depuración opcional

    var id_row = data["ID"];
    Swal.fire({
        html: '<h4>Cargando información del producto</h4>',
        allowOutsideClick: false,
        onBeforeOpen: () => {
            Swal.showLoading();
        }
    });

    window.location.assign("../../views/productos/editar-producto?id=" + id_row);
});