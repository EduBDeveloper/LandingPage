<?php
// Conexión a la base de datos
include 'C:\xampp\htdocs\talleroperacion\global\connection.php'; // Asegúrate de que la ruta sea correcta

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $id = isset($_POST['id']) ? intval($_POST['id']) : null; // ID solo se envía en caso de actualización
    $nombre = trim($_POST['nombre'] ?? ''); // Campo del nombre del depósito
    $ubicacion = trim($_POST['ubicacion'] ?? ''); // Campo de ubicación
    $capacidad = isset($_POST['capacidad']) ? floatval($_POST['capacidad']) : 0; // Campo de capacidad

    // Validar campos requeridos
    if (empty($nombre) || empty($ubicacion) || $capacidad <= 0) {
        die('Error: Todos los campos son obligatorios.');
    }

    try {
        if ($id) {
            // Si se recibe un ID, actualiza el depósito existente
            $sql = "UPDATE deposito SET 
                        nombre = :nombre, 
                        ubicacion = :ubicacion, 
                        capacidad = :capacidad 
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':ubicacion', $ubicacion);
            $stmt->bindParam(':capacidad', $capacidad);
        } else {
            // Generar un nuevo código único para el depósito
            $stmt = $pdo->query("SELECT MAX(id) AS max_id FROM deposito");
            $maxId = $stmt->fetch(PDO::FETCH_ASSOC)['max_id'] ?? 0;
            $nuevoCodigo = 'DEP' . str_pad($maxId + 1, 5, '0', STR_PAD_LEFT);

            // Insertar un nuevo depósito
            $sql = "INSERT INTO deposito (codigo, nombre, ubicacion, capacidad) 
                    VALUES (:codigo, :nombre, :ubicacion, :capacidad)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':codigo', $nuevoCodigo);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':ubicacion', $ubicacion);
            $stmt->bindParam(':capacidad', $capacidad);
        }

        // Ejecutar consulta
        if ($stmt->execute()) {
            echo $id ? "Depósito actualizado con éxito." : "Depósito registrado con éxito.";
        } else {
            throw new Exception("Error al guardar los datos.");
        }
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die('Error: Método de solicitud no permitido.');
}
?>
