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

// Consulta a la base de datos para obtener los depósitos
$sql = "SELECT id, nombre FROM deposito";
$stmt = $pdo->query($sql);

// Obtener los resultados como un array asociativo
$depositos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Devolver los datos como JSON
echo json_encode($depositos);
?>
