$(document).ready(function() {
    $("#m_resumen_factura").addClass("nav-link active");
    $("#m_facturacion").addClass("nav-link active");
    $("#m_facturacion").parent().addClass("nav-item has-treeview menu-open");
    $(document).prop('title', 'Resumen de Facturas - EduBWeb');

    var tbl_facturas = $("#table-facturas").DataTable({
        dom: 'Bfrtip',
        "order": [
            [0, "DESC"]
        ],
        buttons: [{
                extend: 'pdf',
                text: '<i class="fa fa-file-pdf"></i>&nbsp;&nbsp;Descargar PDF'
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
        language: { url: "../../plugins/datatables/Spanish.json" }
    });

    listarDocumentos(true);

    tbl_facturas.columns([0]).visible(false);

    $('input[name="factura_cliente"]').autocomplete({
        source: function(request, response) {
            $.getJSON("../../modules/clientes/obtener-clientes.php", {
                cotiz_nomcliente: $('input[name="factura_cliente"]').val()
            }, response);
        },
        select: function(event, ui) {
            $(this).val(ui.item.label);
        }
    });

    $('input[name="factura_fecini"], input[name="factura_fecfin"]').on("change", function() {
        fact_fini = $('input[name="factura_fecini"]').val();
        fact_ffin = $('input[name="factura_fecfin"]').val();

        if (moment(fact_fini).isValid() && moment(fact_ffin).isValid()) {
            fecinic = $('input[name="factura_fecini"]');
            fecfin = $('input[name="factura_fecfin"]');
            rango_dias = moment.range(moment(fecinic.val()).format('YYYY-MM-DD'), moment(fecfin.val()).format('YYYY-MM-DD'));
            dif_days = rango_dias.diff('days');

            if (dif_days < 0) {
                new_date = moment(fecinic.val()).add(1, 'day');
                fecfin.val(new_date.format('YYYY-MM-DD'));
            } else {
                rango_dias = moment.range(moment(fecfin.val()).format('YYYY-MM-DD'), moment(fecinic.val()).format('YYYY-MM-DD'));
                if (dif_days < 0) {
                    new_date = moment(fecinic.val()).add(1, 'day');
                    fecfin.val(new_date.format('YYYY-MM-DD'));
                }
            }
        }
    });

    $('input[name="factura_fecini"]').on("change", function() {
        fecinic = $(this);
        fecfin = $('input[name="factura_fecfin"]');
        if (moment(fecinic.val()).isValid()) {
            new_date = moment(fecinic.val()).add(1, 'day');
            fecfin.val(new_date.format('YYYY-MM-DD'));
        }
    });

    $("#btn-buscar").click(function() {
        listarDocumentos();
    });

    // Menú contextual para la tabla de facturas
    $("#table-facturas").contextMenu({
        selector: "tbody tr",
        callback: function(key, options) {
            tbl_data = tbl_facturas.rows().data().toArray();

            if (tbl_data.length > 0) {
                var data_row = tbl_facturas.row(this).data();
                var row_id = data_row[0]; // ID de la factura
                var action = key;

                switch (action) {
                    case "download_pdf":
                        window.location.href = `generar-pdf.php?id=${row_id}`;
                        break;

                    case "edit":
                        crear_cookie('COOKIE_ID_FACT', row_id, 1, "/");
                        location.href = "registro-factura";
                        break;

                    case "vigente":
                        Swal.fire({
                            title: "¿Está seguro de marcar como VIGENTE la factura " + data_row[1] + "?",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Marcar como VIGENTE",
                            cancelButtonText: "Cancelar"
                        }).then(result => {
                            if (result.value) {
                                $.post(
                                    "../../modules/facturacion/cambiar-estado-doc.php", { TIPO_DOC: 'INVOICE', ID_DOC: row_id, ESTADO_DOC: 1 },
                                    function(data) {
                                        if (data) {
                                            listarDocumentos();
                                            $.Notification.notify(
                                                "success",
                                                "bottom-right",
                                                "Factura Vigente",
                                                "La factura " + data_row[1] + " fue marcada con éxito"
                                            );
                                        } else {
                                            $.Notification.notify(
                                                "error",
                                                "bottom-right",
                                                "Error",
                                                "La factura " + data_row[1] + " no pudo ser marcada como vigente"
                                            );
                                        }
                                    }
                                );
                            }
                        });
                        break;

                    case "anulado":
                        Swal.fire({
                            title: "¿Está seguro de ANULAR la factura " + data_row[1] + "?",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Anular",
                            cancelButtonText: "Cancelar"
                        }).then(result => {
                            if (result.value) {
                                $.post(
                                    "../../modules/facturacion/cambiar-estado-doc.php", { TIPO_DOC: 'INVOICE', ID_DOC: row_id, ESTADO_DOC: 2 },
                                    function(data) {
                                        if (data) {
                                            listarDocumentos();
                                            $.Notification.notify(
                                                "success",
                                                "bottom-right",
                                                "Factura Anulada",
                                                "La factura " + data_row[1] + " fue anulada con éxito"
                                            );
                                        } else {
                                            $.Notification.notify(
                                                "error",
                                                "bottom-right",
                                                "Error",
                                                "La factura " + data_row[1] + " no pudo ser anulada"
                                            );
                                        }
                                    }
                                );
                            }
                        });
                        break;

                    case "pendiente":
                        Swal.fire({
                            title: "¿Está seguro de marcar como PENDIENTE DE PAGO la factura " + data_row[1] + "?",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Marcar como PENDIENTE DE PAGO",
                            cancelButtonText: "Cancelar"
                        }).then(result => {
                            if (result.value) {
                                $.post(
                                    "../../modules/facturacion/cambiar-estado-doc.php", { TIPO_DOC: 'INVOICE', ID_DOC: row_id, ESTADO_DOC: 3 },
                                    function(data) {
                                        if (data) {
                                            listarDocumentos();
                                            $.Notification.notify(
                                                "success",
                                                "bottom-right",
                                                "Factura Pendiente de Pago",
                                                "La factura " + data_row[1] + " fue marcada con éxito"
                                            );
                                        } else {
                                            $.Notification.notify(
                                                "error",
                                                "bottom-right",
                                                "Error",
                                                "La factura " + data_row[1] + " no pudo ser marcada como pendiente de pago"
                                            );
                                        }
                                    }
                                );
                            }
                        });
                        break;

                    case "cancelado":
                        Swal.fire({
                            title: "¿Está seguro de marcar como CANCELADA la factura " + data_row[1] + "?",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Marcar como CANCELADA",
                            cancelButtonText: "Cancelar"
                        }).then(result => {
                            if (result.value) {
                                $.post(
                                    "../../modules/facturacion/cambiar-estado-doc.php", { TIPO_DOC: 'INVOICE', ID_DOC: row_id, ESTADO_DOC: 4 },
                                    function(data) {
                                        if (data) {
                                            listarDocumentos();
                                            $.Notification.notify(
                                                "success",
                                                "bottom-right",
                                                "Factura Cancelada",
                                                "La factura " + data_row[1] + " fue cancelada con éxito"
                                            );
                                        } else {
                                            $.Notification.notify(
                                                "error",
                                                "bottom-right",
                                                "Error",
                                                "La factura " + data_row[1] + " no pudo ser cancelada"
                                            );
                                        }
                                    }
                                );
                            }
                        });
                        break;
                }
            }
        },
        items: {
            "edit": { "name": "Ver y editar", "icon": "edit" },
            "download_pdf": { "name": "Descargar PDF", "icon": "download" }, // Nueva opción
            "separator1": "",
            "status": {
                "name": "Cambiar estado",
                "items": {
                    "vigente": { "name": "Vigente" },
                    "anulado": { "name": "Anulado" },
                    "pendiente": { "name": "Pendiente de Pago" },
                    "cancelado": { "name": "Cancelado" }
                }
            },
            "separator2": "",
            "close": { "name": "Cerrar", "icon": "quit" }
        }
    });

    $("#btn-reset").click(function(e) {
        e.preventDefault();
        location.reload();
    });

    function listarDocumentos(loadMode = false) {
        const fact_nroo = $('input[name="factura_numero"]').val();
        const fact_client = $('input[name="factura_cliente"]').val();
        const fact_fini = $('input[name="factura_fecini"]').val();
        const fact_ffin = $('input[name="factura_fecfin"]').val();
        const fact_estado = $('select[name="factura_estado"]').val();
        const fact_vendedor = $('select[name="factura_vendedor"]').val();

        Swal.fire({
            html: "<h4>Cargando facturas</h4>",
            allowOutsideClick: false,
            onBeforeOpen: () => Swal.showLoading()
        });

        $.post(
            "../../modules/facturacion/filtrar-doc.php", {
                TIPO_DOC: 'INVOICE',
                defaultLoad: loadMode ? 1 : 0,
                fact_nroo,
                fact_client,
                fact_fini,
                fact_ffin,
                fact_estado,
                fact_vendedor
            },
            function(data) {
                tbl_facturas.clear().draw();
                const data_factura = JSON.parse(data);
                data_factura.forEach(factura => {
                    tbl_facturas.row.add([
                        factura.ID,
                        factura.SERIES_NUMBER,
                        factura.DATE,
                        factura.CUSTOMER,
                        factura.TOTAL_NET,
                        factura.STATUS,
                        factura.SELLER_NAME
                    ]).draw();
                });
            }
        ).always(() => Swal.close());
    }

    function generarFacturaPDF(facturaData, detallesData = []) {
        if (!facturaData || typeof facturaData !== 'object') {
            console.error("Factura Data no válida");
            return;
        }

        const docDefinition = {
            content: [
                { text: 'Factura', style: 'header' },
                { text: `Serie: ${facturaData.series} - Número: ${facturaData.number}`, style: 'subheader' },
                { text: `Fecha de Emisión: ${facturaData.date}` },
                { text: `Cliente: ${facturaData.name}` },
                { text: `Dirección: ${facturaData.address}` },
                { text: `RUC: ${facturaData.ruc}` },
                { text: `Vendedor: ${facturaData.seller_id}` },
                { text: 'Detalle de Productos o Servicios', style: 'subheader' },
                {
                    table: {
                        widths: ['auto', '*', 'auto', 'auto', 'auto'],
                        body: [
                            ['Código', 'Descripción', 'Cantidad', 'Precio Unitario', 'Importe'],
                            ...detallesData.map(item => [
                                item.item_code,
                                item.item_description,
                                item.item_quantity,
                                item.item_unit_price,
                                item.importe
                            ])
                        ]
                    }
                },
                { text: `Subtotal: ${facturaData.total_sub}`, alignment: 'right' },
                { text: `Impuestos: ${facturaData.total_tax}`, alignment: 'right' },
                { text: `Total: ${facturaData.total_net}`, alignment: 'right' },
                { text: 'Método de Pago: Transferencia Bancaria', style: 'subheader' }
            ],
            styles: {
                header: { fontSize: 18, bold: true },
                subheader: { fontSize: 14, bold: true, margin: [0, 10, 0, 5] }
            }
        };

        pdfMake.createPdf(docDefinition).download(`Factura_${facturaData.series}_${facturaData.number}.pdf`);
    }

    function cambiarEstadoFactura(data_row, row_id, estado, estadoTexto, estadoKey) {
        Swal.fire({
            title: `¿Está seguro de cambiar el estado a ${estadoTexto}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cambiar estado'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("../../modules/facturacion/cambiar-estado.php", {
                    id: row_id,
                    estado: estado
                }, function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Estado actualizado',
                            `La factura ahora está ${estadoKey}`,
                            'success'
                        );
                        listarDocumentos();
                    } else {
                        Swal.fire(
                            'Error',
                            'No se pudo actualizar el estado de la factura',
                            'error'
                        );
                    }
                }, "json");
            }
        });
    }
});