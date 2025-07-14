<?php
$host = 'localhost';
$dbname = 'bd_utic';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT id, nombre FROM deposito");
    $depositos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($depositos);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Error al obtener depósitos: ' . $e->getMessage()]);
}
