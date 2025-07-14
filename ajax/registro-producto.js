$(document).ready(function() {
    // Activar el menú y establecer el título de la página
    $("#m_almacen").addClass("nav-link active");
    $("#m_almacen").parent().addClass("nav-item has-treeview menu-open");
    $("#m_registro_producto").addClass("nav-link active");
    $(document).prop('title', 'Registro de Productos - EduBWeb');

    // Cargar proveedores en el select
    cargarProveedores();

    // Cargar depósitos en el select
    cargarDepositos();

    // Manejo del formulario de producto
    $("#FRM_INSERT_PRODUCTO").submit(function(e) {
        e.preventDefault();
        guardarProducto($(this));
    });

    // Botón para limpiar campos
    $("#btn-new").click(function(e) {
        e.preventDefault();
        limpiarCampos();
    });

    // Botón para ir al listado de productos
    $("#btn-product-list").click(function(e) {
        e.preventDefault();
        window.location.assign("../../views/productos/listado-producto");
    });
});

// Función para cargar proveedores
function cargarProveedores() {
    console.log("Cargando depósitos...");

    $.post("../../modules/proveedores/listar-proveedores.php", function(data) {
        $('select[name="producto_proveedor"]').empty().select2({
            data: JSON.parse(data)
        });
    });
}

function cargarDepositos() {
    $.post("../../modules/deposito/listar-depositos.php", function(data) {
        console.log("Datos recibidos:", data); // Verifica si se reciben los datos
        let depositos = JSON.parse(data);
        let formattedData = depositos.map(function(deposito) {
            return {
                id: deposito.id, // ID del depósito
                text: deposito.nombre // Nombre del depósito
            };
        });
        $('select[name="producto_deposito"]').empty().select2({
            data: formattedData
        });
    }).fail(function(xhr, status, error) {
        console.error("Error cargando depósitos:", error);
    });
}





// Función para guardar producto
function guardarProducto(form) {
    const url = form.attr('action');
    const formData = new FormData(document.getElementById(form.attr("id")));
    const id_product = $('input[name="producto_id"]').val();

    $.ajax({
        type: "POST",
        url: url,
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
            Swal.fire({
                html: '<h4>Guardando información</h4>',
                allowOutsideClick: false,
                onBeforeOpen: () => Swal.showLoading()
            });
        },
        success: function(data) {
            procesarRespuestaGuardado(data, form, id_product);
        },
        error: function() {
            Swal.fire("Error", "Ocurrió un error al guardar el producto", "error");
        }
    });
}

// Función para procesar la respuesta del guardado
function procesarRespuestaGuardado(data, form, id_product) {
    Swal.close();

    if (data === "ERROR") {
        Swal.fire("Error", "No se pudo guardar los datos del producto", "error");
    } else if (data === "EXISTE") {
        Swal.fire("Error", "El producto ya existe en la base de datos", "error");
    } else if (data === "OK_INSERT") {
        Swal.fire("Éxito", "Producto guardado correctamente", "success");
        form.trigger("reset");
        $('select[name="producto_proveedor"], select[name="producto_deposito"]').val(null).trigger('change');
        $('#table-productos').DataTable().ajax.reload();
    } else if (data === "OK_UPDATE") {
        Swal.fire("Éxito", "Producto actualizado correctamente", "success");
        if (id_product) {
            $('input[name="producto_id"]').val("");
            $("#btn-save-product font").text("Guardar producto");
        }
        Faq
        form.trigger("reset");
        $('select[name="producto_proveedor"], select[name="producto_deposito"]').val(null).trigger('change');
    }
}

// Función para limpiar campos del formulario
function limpiarCampos() {
    $("#FRM_INSERT_PRODUCTO").trigger("reset");
    $('select[name="producto_proveedor"], select[name="producto_deposito"]').val(null).trigger('change');
    $('input[name="producto_id"]').val("");
    $("#btn-save-product font").text("Guardar producto");
}