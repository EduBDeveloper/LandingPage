document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("FRM_INSERT_COMPRA").addEventListener("submit", function(e) {
        e.preventDefault();

        let formData = new FormData(this);
        let xhr = new XMLHttpRequest();
        xhr.open("POST", this.action, true);
        xhr.onload = function() {
            if (xhr.status === 200 && xhr.responseText === "OK_INSERT") {
                alert("Compra registrada exitosamente");
                document.getElementById("FRM_INSERT_COMPRA").reset();
            } else {
                alert("Error al registrar la compra");
            }
        };
        xhr.send(formData);
    });

    // Simular carga de proveedores
    let selectProv = document.querySelector('select[name="mov_prov"]');
    let option = document.createElement("option");
    option.value = "1";
    option.text = "Proveedor 1";
    selectProv.add(option);
});