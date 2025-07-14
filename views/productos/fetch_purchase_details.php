<?php
$dsn = 'mysql:host=localhost;dbname=bd_utic;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $purchaseId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    $query = $pdo->prepare("SELECT * FROM tbl_purchase_detail WHERE id = ?");
    $query->bindParam(1, $purchaseId, PDO::PARAM_INT);
    $query->execute();

    $purchase = $query->fetch(PDO::FETCH_ASSOC);

    echo json_encode($purchase);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$pdo = null;
?>
