<?php
    ini_set('default_charset', 'utf-8');
    $page_title = 'Presupuesto';
    require_once('includes/load.php');
    require('Reportes-PDF/fpdf.php');
    page_require_level(2);
    
    class PDF extends FPDF
    {
        function Footer()
        {
            // Go to 0.5 cm from bottom
            $this->SetY(-10);
            // Select Arial italic 8
            $this->SetFont('Arial','I',8);
            // Print current and total page numbers
            $this->Cell(0,10,utf8_decode('Página '.$this->PageNo().'/{nb}'),0,0,'C');
        }
    }

    $id = $_GET['id'];
    $presupuesto = find_by_id('presupuesto', $id);
    $all_detalles = find_detalles3_by_id('presupuesto_detalles', $presupuesto['id']);
    $cliente = find_by_id_suc('cliente', $presupuesto['id_cliente']);
    $subtotal_suma = 0;
    $iva_suma = 0;

    $pdf = new PDF();
    $pdf->AliasNbPages();

//1º pag
    b:
    $pdf->AddPage();
    $pdf->AliasNbPages();

    $pageWidth = 210;
    $pageHeight = 297;
    $margin = 10;

    $pdf->Image('Reportes-PDF/LogoIJS.png',35,5,45);
    $pdf->Rect( $margin, $margin , $pageWidth - $margin * 2, $pageHeight - $margin * 2);


    $pdf->SetFont('Arial','B',30);
    $pdf->Cell(88);
    $pdf->Cell(14, 15, 'X', 1, 0, 'C');
    $pdf->Rect( 105, $margin+15, 0, 68);    

    $pdf->Ln(26);
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(25);
    $pdf->MultiCell(80, 6.5, "\nSosa Julio Jorge", 0, "L");
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(25);
    $pdf->MultiCell(47, 3, utf8_decode("ingjsosa@ijsingenieria.com.ar"), 0, 'C', 0);
    $pdf->Cell(25);
    $pdf->MultiCell(47, 3, utf8_decode("www.ijsingenieria.com.ar\n\n"), 0, 'C', 0);

    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(5);
    $pdf->MultiCell(85, 4, "\nAv. Lucas Braulio Areco 2353", 0, "C");
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(5);
    $pdf->MultiCell(85, 6, "CP: 3300 - Posadas, Misiones", 0, "C");
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(5);
    $pdf->MultiCell(80, 3, utf8_decode("\nServicio técnico (WhatsApp): (0376) 435-6110 \nMail: serviciotecnico@ijsingenieria.com.ar \n\nAdministración (Fijo): (0376) 459-2534 \nMail: administracion@ijsingenieria.com.ar"), 0, 'L', 0);

    $pdf->SetXY(118, 10);
    $pdf->SetFont('Arial','B',17);
    $cod = '';
    for ($i = 1; $i <= (6-strlen($presupuesto['id'])); $i++) {
        $cod .= '0';
    }
    $pdf->MultiCell(80, 4, utf8_decode("\n\n\nPRESUPUESTO Nº ".$cod.$presupuesto['id']), 0, 'R', 0);
    $pdf->SetXY(118, 27);
    $pdf->SetFont('Arial','B',8);
    $pdf->MultiCell(80, 4, utf8_decode("Documento no válido como factura"), 0, 'R', 0);

    $pdf->SetXY(108, 58);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(48, 4, utf8_decode("\nFecha de emisión"), 0, 0, 'L', 0);
    $pdf->Cell(3, 4, ':', 0, 0, 'L', 0);
    $pdf->SetFont('Arial','',9);
    list($año, $mes, $dia) = explode('-', $presupuesto['fecha_emision']);
    $pdf->MultiCell(0, 4, utf8_decode($dia.'/'.$mes.'/'.$año), 0, 'L', 0);

    $pdf->SetXY(108, 63);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(48, 4, utf8_decode("\nValidez"), 0, 0, 'L', 0);
    $pdf->Cell(3, 4, ':', 0, 0, 'L', 0);
    $pdf->SetFont('Arial','',9);
    $pdf->MultiCell(0, 4, utf8_decode("7 días hábiles"), 0, 'L', 0);

    $pdf->SetXY(108, 72);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(48, 4, utf8_decode("\nCUIT"), 0, 0, 'L', 0);
    $pdf->Cell(3, 4, ':', 0, 0, 'L', 0);
    $pdf->SetFont('Arial','',9);
    $pdf->MultiCell(0, 4, utf8_decode("20-18576428-2"), 0, 'L', 0);

    $pdf->SetXY(108, 77);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(48, 4, utf8_decode("\nIngresos brutos"), 0, 0, 'L', 0);
    $pdf->Cell(3, 4, ':', 0, 0, 'L', 0);
    $pdf->SetFont('Arial','',9);
    $pdf->MultiCell(0, 4, utf8_decode("20-18576428-2"), 0, 'L', 0);

    $pdf->SetXY(108, 82);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(48, 4, utf8_decode("\nFecha de inicio de actividades"), 0, 0, 'L', 0);
    $pdf->Cell(3, 4, ':', 0, 0, 'L', 0);
    $pdf->SetFont('Arial','',9);
    $pdf->MultiCell(0, 4, utf8_decode("02/01/2012"), 0, 'L', 0);

    $pdf->SetXY(108, 87);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(48, 4, utf8_decode("\nCondición IVA"), 0, 0, 'L', 0);
    $pdf->Cell(3, 4, ':', 0, 0, 'L', 0);
    $pdf->SetFont('Arial','',9);
    $pdf->MultiCell(0, 4, utf8_decode("Responsable Inscripto"), 0, 'L', 0);

    //------------------------------------------------------------------------------------
    $pdf->Rect( $margin, $margin+83, $pageWidth - $margin * 2,0);
    $pdf->SetXY(10, 95);
    
    $pdf->Cell(5);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(27, 4, utf8_decode("Razón social: "), 0, 0, "L");
    $pdf->SetFont('Arial','',9);
    if ($cliente["razon_social"] == "")
        $pdf->MultiCell(66, 4, "-", 0, 'L', 0);
    else
        $pdf->MultiCell(66, 4, utf8_decode($cliente["razon_social"]), 0, 'L', 0);

    $pdf->Cell(5);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(27, 4, "Domicilio: ", 0, 0, "L");
    $pdf->SetFont('Arial','',9);
    if ($cliente["direccion"] == "")
        $pdf->MultiCell(66, 4, "-", 0, 'L', 0);
    else
        $pdf->MultiCell(66, 4, utf8_decode($cliente["direccion"]), 0, 'L', 0);

    $pdf->SetXY(105, 95);

    $pdf->Cell(4);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(26, 4, utf8_decode("Teléfono: "), 0, 0, "L");
    $pdf->SetFont('Arial','',9);
    if ($cliente["tel"] == "")
        $pdf->MultiCell(66, 4, "-", 0, 'L', 0);
    else
        $pdf->MultiCell(66, 4, utf8_decode($cliente["tel"]), 0, 'L', 0);

    $pdf->Cell(99);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(26, 4, "CUIT: ", 0, 0, "L");
    $pdf->SetFont('Arial','',9);
    if ($cliente["cuit"] == "")
        $pdf->MultiCell(66, 4, "-", 0, 'L', 0);
    else
        $pdf->MultiCell(66, 4, utf8_decode($cliente["cuit"]), 0, 'L', 0);

    $pdf->Cell(99);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(26, 4, utf8_decode("Condición IVA: "), 0, 0, "L");
    $pdf->SetFont('Arial','',9);
    if ($cliente["razon_social"] == "")
        $pdf->MultiCell(66, 4, "-", 0, 'L', 0);
    else
        $pdf->MultiCell(66, 4, utf8_decode($cliente["iva"]), 0, 'L', 0);

    //------------------------------------------------------------------------------------
    //Cabecera tabla
    $pdf->SetXY($margin, $margin+105);

    $pdf->setFillColor(180,180,180);
    $pdf->SetFont('Arial','',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(18, 12, utf8_decode('Código'), 1, 'C', 1);
    $pdf->SetXY($x + 18, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(60, 12, utf8_decode('Descripción'), 1, 'C', 1);
    $pdf->SetXY($x + 60, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(23, 6, utf8_decode('Precio unitario'), 1, 'C', 1);
    $pdf->SetXY($x + 23, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(11, 12, utf8_decode('Cant.'), 1, 'C', 1);
    $pdf->SetXY($x + 11, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(25, 12, utf8_decode('Subtotal'), 1, 'C', 1);
    $pdf->SetXY($x + 25, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(15, 6, utf8_decode('Alicuota IVA'), 1, 'C', 1);
    $pdf->SetXY($x + 15, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(0, 12, utf8_decode('Subtotal c/ IVA'), 1, 'C', 1);

    //Detalles tabla
    $pdf->setFillColor(0,0,0);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','',8.5);

    foreach ($all_detalles as $detalle):
        $producto = find_prod($detalle['id_producto']);
        $pdf->SetXY($x + 18, $y);
        $pdf->MultiCell(60, 4, utf8_decode($producto['descripcion']), 1, 'L');

        $H = $pdf->GetY();
        $height= $H-$y;
        
        $pdf->SetXY($x,$y);
        $pdf->Cell(18,$height,$producto['codigo'],1,1,'C');
        $pdf->SetXY($x + 78, $y);
        $p_unit = str_replace('.', ',', $detalle['p_unit']);
        $pdf->Cell(23,$height,'U$S '.$p_unit,1,1,'C');
        $pdf->SetXY($x + 101, $y);
        $pdf->Cell(11,$height,$detalle['cantidad'],1,1,'C');
        $pdf->SetXY($x + 112, $y);
        $subtotal = $detalle['p_unit']*$detalle['cantidad'];
        $subtotal_cad = str_replace('.', ',', $subtotal);
        $pdf->Cell(25,$height,'U$S '.$subtotal_cad,1,1,'C');
        $pdf->SetXY($x + 137, $y);
        $pdf->Cell(15,$height,(str_replace('.', ',',$presupuesto['alicuota_iva'])).' %',1,1,'C');
        $pdf->SetXY($x + 152, $y);
        $iva = round($detalle['p_unit']*$detalle['cantidad']*(1+$presupuesto['alicuota_iva']/100),2);
        $iva = str_replace('.', ',', $iva);
        $pdf->Cell(0,$height,'U$S '.$iva,1,1,'C');

        $subtotal_suma += $subtotal;
        $iva_suma += round($detalle['p_unit']*$detalle['cantidad']*($presupuesto['alicuota_iva']/100),2);

        array_splice($all_detalles, 0, 1);
        $y = $H;

        if ($H > 225)
            goto a;

    endforeach;

    $pdf->setFillColor(180,180,180);
    $pdf->SetFont('Arial','',12);
    $pdf->SetXY($x+95, 235);
    $pdf->Cell(45,8,'Importe Neto Gravado',0,0,'L',1);
    $pdf->Cell(0,8,'U$S '.str_replace('.', ',', str_replace(',', '', number_format($subtotal_suma,2))),0,1,'R',1);
    $pdf->SetXY($x+95, 241);
    $pdf->Cell(45,8,'IVA',0,0,'L',1);
    $pdf->Cell(0,8,'U$S '.str_replace('.', ',', str_replace(',', '', number_format($iva_suma,2))),0,1,'R',1);
    $pdf->SetXY($x+95, 247);
    $pdf->Cell(45,8,'Total Presupuesto',0,0,'L',1);
    $pdf->Cell(0,8,'U$S '.str_replace('.', ',', str_replace(',', '', number_format($subtotal_suma+$iva_suma,2))),0,1,'R',1);

    $pdf->Rect( $margin+95, $margin+225 , $pageWidth - 95 - $margin * 2, $pageHeight - 257 - $margin * 2);

    a:
    $pdf->SetXY($margin, 258);
    $pdf->SetFont('Arial','BU',10);
    $pdf->Cell(32,4,utf8_decode('Forma de Pago:'),0,0,'L');
    $pdf->SetFont('Arial','',10);
    if ($presupuesto['forma_pago'] == "" || $presupuesto['forma_pago'] == NULL)
        $pdf->MultiCell(0,4,' - ',0,'J');
    else
        $pdf->MultiCell(0,4,utf8_decode($presupuesto['forma_pago']),0,'J');

    $pdf->SetXY($margin, 264);

    $pdf->SetFont('Arial','BU',10);
    $pdf->Cell(32,4,'Observaciones:',0,0,'L');
    $pdf->SetFont('Arial','',10);
    if ($presupuesto['observaciones'] == "" || $presupuesto['observaciones'] == NULL)
        $pdf->MultiCell(0,4,' - ',0,'J');
    else
        $pdf->MultiCell(0,4,utf8_decode($presupuesto['observaciones']),0,'J');

    if (count($all_detalles)>0)
        goto b;

    $pdf->Output();
?>
