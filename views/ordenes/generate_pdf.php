<?php
require('C:\xampp\htdocs\talleroperacion\plugins\fpdf\fpdf.php');

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

// Verificar si se recibe el ID del pedido
if (!isset($_GET['id'])) {
    die("ID de pedido no especificado.");
}

$pedido_id = intval($_GET['id']);

// Consultar información del pedido
$stmt = $pdo->prepare("
    SELECT p.numero_pedido, p.fecha, p.estado, 
           d.cantidad, d.precio_unitario, prod.description
    FROM tbl_pedidos p
    JOIN tbl_pedido_detalles d ON p.id = d.pedido_id
    JOIN tbl_product prod ON d.producto_id = prod.id
    WHERE p.id = :pedido_id
");
$stmt->execute(['pedido_id' => $pedido_id]);
$pedido = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$pedido) {
    die("Pedido no encontrado.");
}

// Crear el PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

// Encabezado
$pdf->Cell(0, 10, utf8_decode("Detalle del Pedido N° " . $pedido[0]['numero_pedido']), 0, 1, 'C');
$pdf->Ln(10);

// Información del pedido
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 10, "Fecha: " . $pedido[0]['fecha'], 0, 1);
$pdf->Cell(40, 10, "Estado: " . ucfirst($pedido[0]['estado']), 0, 1);
$pdf->Ln(10);

// Tabla de productos
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(80, 10, utf8_decode('Producto'), 1);
$pdf->Cell(30, 10, 'Cantidad', 1);
$pdf->Cell(40, 10, 'Precio Unitario', 1);
$pdf->Cell(40, 10, 'Subtotal', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$total = 0;
foreach ($pedido as $item) {
    $subtotal = $item['cantidad'] * $item['precio_unitario'];
    $total += $subtotal;

    $pdf->Cell(80, 10, utf8_decode($item['description']), 1);
    $pdf->Cell(30, 10, $item['cantidad'], 1);
    $pdf->Cell(40, 10, number_format($item['precio_unitario'], 2), 1);
    $pdf->Cell(40, 10, number_format($subtotal, 2), 1);
    $pdf->Ln();
}

// Total
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(150, 10, 'Total:', 1);
$pdf->Cell(40, 10, number_format($total, 2), 1);

// Generar el PDF
$pdf->Output('D', "Pedido_" . $pedido[0]['numero_pedido'] . ".pdf");
exit;
?>
