<?php
require_once 'C:\xampp\htdocs\talleroperacion\global\connection.php'; // Ajusta la ruta según tu estructura
require_once 'fpdf/fpdf.php'; // Incluye la librería FPDF para generar PDFs

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $FAC_ID = $_POST['FAC_ID'] ?? null;

    if (!$FAC_ID) {
        echo json_encode(['status' => 'error', 'message' => 'ID de factura no proporcionado']);
        exit;
    }

    try {
        // Obtener los datos de la factura
        $query = "
            SELECT 
                i.id, 
                i.series, 
                i.number, 
                i.date, 
                i.name AS customer_name, 
                i.ruc, 
                i.address, 
                i.total_net, 
                i.total_sub, 
                i.total_tax, 
                i.status, 
                i.seller_name
            FROM tbl_invoice i
            WHERE i.id = :FAC_ID";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['FAC_ID' => $FAC_ID]);
        $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si la factura existe
        if (!$invoice) {
            echo json_encode(['status' => 'error', 'message' => 'Factura no encontrada']);
            exit;
        }

        // Obtener los detalles de la factura
        $details_query = "
            SELECT 
                d.item_code, 
                d.item_description, 
                d.item_quantity, 
                d.item_unit_price
            FROM tbl_invoice_detail d
            WHERE d.invoice_id = :FAC_ID";
        $details_stmt = $pdo->prepare($details_query);
        $details_stmt->execute(['FAC_ID' => $FAC_ID]);
        $details = $details_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Verifica si los detalles están vacíos
        if (empty($details)) {
            $details = [['item_code' => 'N/A', 'item_description' => 'N/A', 'item_quantity' => 0, 'item_unit_price' => 0]];
        }

        // Generar el PDF de la factura
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);
        
        // Imprimir datos de la factura
        $pdf->Cell(200, 10, "Factura: ".$invoice['series']." ".$invoice['number'], 0, 1, 'C');
        $pdf->Cell(200, 10, "Cliente: ".$invoice['customer_name'], 0, 1, 'C');
        $pdf->Cell(200, 10, "RUC: ".$invoice['ruc'], 0, 1, 'C');
        $pdf->Cell(200, 10, "Direccion: ".$invoice['address'], 0, 1, 'C');
        $pdf->Cell(200, 10, "Total Neto: ".$invoice['total_net'], 0, 1, 'C');
        $pdf->Cell(200, 10, "Total Subtotal: ".$invoice['total_sub'], 0, 1, 'C');
        $pdf->Cell(200, 10, "Total Impuesto: ".$invoice['total_tax'], 0, 1, 'C');

        // Imprimir detalles de los productos
        $pdf->Ln(10); // Salto de línea
        $pdf->Cell(40, 10, "Codigo", 1);
        $pdf->Cell(60, 10, "Descripcion", 1);
        $pdf->Cell(40, 10, "Cantidad", 1);
        $pdf->Cell(40, 10, "Precio Unit.", 1);
        $pdf->Ln();

        foreach ($details as $item) {
            $pdf->Cell(40, 10, $item['item_code'], 1);
            $pdf->Cell(60, 10, $item['item_description'], 1);
            $pdf->Cell(40, 10, $item['item_quantity'], 1);
            $pdf->Cell(40, 10, $item['item_unit_price'], 1);
            $pdf->Ln();
        }

        // Salida del PDF
        $pdf->Output();
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error en la consulta: ' . $e->getMessage()]);
    }
}
?>
