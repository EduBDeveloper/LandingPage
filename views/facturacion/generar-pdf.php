<?php
require '../../global/connection.php';
require 'C:/xampp/htdocs/talleroperacion/plugins/fpdf/fpdf.php';

// Cambiar de $_POST a $_GET para capturar el parámetro de la URL
if (isset($_GET['id'])) {
    $idFactura = $_GET['id'];
} else {
    echo "El parámetro 'id' no está definido.";
    exit;
}

// Consulta de la factura
$sql = $pdo->prepare("SELECT * FROM tbl_invoice WHERE id = :id");
$sql->bindParam(':id', $idFactura, PDO::PARAM_INT);
$sql->execute();

if ($sql->rowCount() > 0) {
    $factura = $sql->fetch(PDO::FETCH_ASSOC);

    // Configuración de FPDF
    $pdf = new FPDF();
    $pdf->SetCreator('FPDF');
    $pdf->SetAuthor('EduBWeb');
    $pdf->SetTitle('Factura');
    $pdf->SetMargins(20, 10, 20);
    $pdf->AddPage();

    // Logo de la empresa
    $pdf->Image('C:\xampp\htdocs\talleroperacion\img\chemistry.png', 10, 10, 30); 
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 15, utf8_decode("Factura: {$factura['series']}-{$factura['number']}"), 0, 1, 'C');
    $pdf->Ln(22);

    // Información del emisor (empresa)
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 6, utf8_decode("Emisor:"), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, utf8_decode("Empresa: EduB Web S.A."), 0, 1, 'L');
    $pdf->Cell(0, 6, utf8_decode("Dirección: UTIC, San Lorenzo, Paraguay"), 0, 1, 'L');
    $pdf->Cell(0, 6, utf8_decode("Teléfono: +595 985 478 215"), 0, 1, 'L');
    $pdf->Cell(0, 6, utf8_decode("Correo: administracion@eduwebx.com"), 0, 1, 'L');
    $pdf->Ln(10);

    // Detalles del cliente
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 6, utf8_decode("Proveedor:"), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, utf8_decode("Proveedor: {$factura['name']}"), 0, 1, 'L');
    $pdf->Cell(0, 6, utf8_decode("Dirección: {$factura['address']}"), 0, 1, 'L');
    $pdf->Ln(10);

    // Detalles de la factura
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 6, utf8_decode("Detalles de la Factura:"), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, utf8_decode("Fecha: {$factura['date']}"), 0, 1, 'L');
    $pdf->Cell(0, 6, utf8_decode("Total Neto: $" . number_format($factura['total_net'], 2)), 0, 1, 'L');
    $pdf->Ln(10);

    // Encabezado de la tabla de productos
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 10, utf8_decode('Código'), 1, 0, 'C');
    $pdf->Cell(70, 10, utf8_decode('Descripción'), 1, 0, 'C');
    $pdf->Cell(20, 10, utf8_decode('Cantidad'), 1, 0, 'C');
    $pdf->Cell(30, 10, utf8_decode('Precio Unitario'), 1, 0, 'C');
    $pdf->Cell(30, 10, utf8_decode('Total'), 1, 1, 'C');
    $pdf->SetFont('Arial', '', 10);

    // Detalles de los productos
    $sqlDetails = $pdo->prepare("SELECT * FROM tbl_invoice_detail WHERE invoice_id = :id");
    $sqlDetails->bindParam(':id', $idFactura, PDO::PARAM_INT);
    $sqlDetails->execute();

    $subtotal = 0; // Inicializar subtotal
    if ($sqlDetails->rowCount() > 0) {
        while ($detail = $sqlDetails->fetch(PDO::FETCH_ASSOC)) {
            // Calcular el total por producto
            $itemTotal = $detail['item_quantity'] * $detail['item_unit_price'];
            $subtotal += $itemTotal; // Sumar al subtotal

            // Agregar los detalles del producto a la tabla
            $pdf->Cell(40, 10, utf8_decode($detail['item_code']), 1, 0, 'C');
            $pdf->Cell(70, 10, utf8_decode($detail['item_description']), 1, 0, 'L');
            $pdf->Cell(20, 10, utf8_decode($detail['item_quantity']), 1, 0, 'C');
            $pdf->Cell(30, 10, number_format($detail['item_unit_price'], 2), 1, 0, 'C');
            $pdf->Cell(30, 10, number_format($itemTotal, 2), 1, 1, 'C');
        }
    }

    // Calcular IVA (suponiendo 10%)
    $iva = $subtotal * 0.10;  // 10% de IVA
    $totalConIva = $subtotal + $iva;

    // Subtotal, IVA y Total
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(140, 10, utf8_decode("Subtotal: "), 0, 0, 'R');
    $pdf->Cell(30, 10, "$" . number_format($subtotal, 2), 0, 1, 'C');
    $pdf->Cell(140, 10, utf8_decode("IVA (10%): "), 0, 0, 'R');
    $pdf->Cell(30, 10, "$" . number_format($iva, 2), 0, 1, 'C');
    $pdf->Cell(140, 10, utf8_decode("Total: "), 0, 0, 'R');
    $pdf->Cell(30, 10, "$" . number_format($totalConIva, 2), 0, 1, 'C');

    // Salida del archivo PDF
    $pdf->Output('Factura.pdf', 'D');
} else {
    echo "Factura no encontrada.";
}
?>
