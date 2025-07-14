<?php
$host = 'localhost';
$dbname = 'bd_utic';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT id, business_name AS nombre FROM tbl_provider");
    $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($proveedores);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al obtener proveedores: ' . $e->getMessage()]);
}
