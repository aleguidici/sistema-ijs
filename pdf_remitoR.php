<?php
    ini_set('default_charset', 'utf-8');
    $page_title = 'Remito R';
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
            $this->SetY(-10);
            //$this->Cell(191,10,utf8_decode('Original Blanco / Duplicado Color'),0,0,'R');
        }
    }

    $id = $_GET['id'];
    $movimiento = find_by_id('movimientos', $id);
    if ($movimiento['concepto'] == 3 || $movimiento['concepto'] == 4)
        redirect('home.php',false);

    $all_detalles = find_detalles4_by_id('movimientos_detalles', $movimiento['id']);
    
    if ($movimiento['concepto'] == 1)
        $destinatario = find_by_id_suc('proveedor', $movimiento['id_proveedor']);
    else
        $destinatario = find_by_id_suc('cliente', $movimiento['id_cliente']);

    $provincia = find_by_id_prov('provincia', $destinatario['provincia']);
    
    $pdf = new PDF();
    $pdf->AliasNbPages();

//ORIGINAL
    b:
    $pdf->AddPage();
    $pdf->AliasNbPages();

    $pageWidth = 210;
    $pageHeight = 297;
    $margin = 10;

    $pdf->setFillColor(220);
    $pdf->SetXY(10, 81);
    $pdf->Cell(190,31,'',0,0,'L',1);

    $pdf->SetXY(10, 10);
    $pdf->Image('Reportes-PDF/LogoIJS.png',42,10,30); //ACHICAR
    $pdf->Rect( $margin, $margin , $pageWidth - $margin * 2, $pageHeight - $margin * 2);

    $pdf->SetFont('Arial','B',30);
    $pdf->Cell(88);
    $pdf->Cell(14, 15, 'R', 1, 0, 'C');
    $pdf->Rect( 105, $margin+15, 0, 56);

    $pdf->Ln(19);
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(24);
    $pdf->MultiCell(60, 6.5, "\nSosa Julio Jorge", 0, "L");
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(25);
    $pdf->MultiCell(47, 3, utf8_decode("ingjsosa@ijsingenieria.com.ar"), 0, 'C', 0);
    $pdf->Cell(25);
    $pdf->MultiCell(47, 3, utf8_decode("www.ijsingenieria.com.ar\n\n"), 0, 'C', 0);

    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(5);
    $pdf->MultiCell(85, 2, "\nAv. Lucas Braulio Areco 2353", 0, "C");
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(5);
    $pdf->MultiCell(85, 6, "CP: 3300 - Posadas, Misiones", 0, "C");
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(5);
    $pdf->MultiCell(80, 3, utf8_decode("\nServicio técnico (WhatsApp): (0376) 435-6110 \nMail: serviciotecnico@ijsingenieria.com.ar \n\nAdministración (Fijo): (0376) 459-2534 \nMail: administracion@ijsingenieria.com.ar"), 0, 'L', 0);

    //$pdf->SetXY(90, 18);
    //$pdf->SetFont('Arial','',17);
    //$pdf->Cell(76, 11, '(Original)', 0, 0, 'C');

    $pdf->SetXY(118, 10);
    $pdf->SetFont('Arial','B',17);
    $cod = '';
    for ($i = 1; $i <= (6-strlen($movimiento['id'])); $i++) {
        $cod .= '0';
    }
    $pdf->MultiCell(80, 4, utf8_decode("\n\n\nREMITO Nº ".$cod.$movimiento['id']), 0, 'R', 0);
    $pdf->SetXY(118, 27);
    $pdf->SetFont('Arial','B',8);
    $pdf->MultiCell(80, 4, utf8_decode("Documento no válido como factura"), 0, 'R', 0);

    $pdf->SetXY(108, 45);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(48, 4, utf8_decode("\nFecha de emisión"), 0, 0, 'L', 0);
    $pdf->Cell(3, 4, ':', 0, 0, 'L', 0);
    $pdf->SetFont('Arial','',9);
    list($año, $mes, $dia) = explode('-', $movimiento['fecha']);
    $pdf->MultiCell(0, 4, utf8_decode($dia.'/'.$mes.'/'.$año), 0, 'L', 0);

    $pdf->SetXY(108, 60);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(48, 4, utf8_decode("\nCUIT"), 0, 0, 'L', 0);
    $pdf->Cell(3, 4, ':', 0, 0, 'L', 0);
    $pdf->SetFont('Arial','',9);
    $pdf->MultiCell(0, 4, utf8_decode("20-18576428-2"), 0, 'L', 0);

    $pdf->SetXY(108, 65);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(48, 4, utf8_decode("\nIngresos brutos"), 0, 0, 'L', 0);
    $pdf->Cell(3, 4, ':', 0, 0, 'L', 0);
    $pdf->SetFont('Arial','',9);
    $pdf->MultiCell(0, 4, utf8_decode("20-18576428-2"), 0, 'L', 0);

    $pdf->SetXY(108, 70);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(48, 4, utf8_decode("\nFecha de inicio de actividades"), 0, 0, 'L', 0);
    $pdf->Cell(3, 4, ':', 0, 0, 'L', 0);
    $pdf->SetFont('Arial','',9);
    $pdf->MultiCell(0, 4, utf8_decode("02/01/2012"), 0, 'L', 0);

    $pdf->SetXY(108, 75);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(48, 4, utf8_decode("\nCondición IVA"), 0, 0, 'L', 0);
    $pdf->Cell(3, 4, ':', 0, 0, 'L', 0);
    $pdf->SetFont('Arial','',9);
    $pdf->MultiCell(0, 4, utf8_decode("Responsable Inscripto"), 0, 'L', 0);

    //------------------------------------------------------------------------------------
    $pdf->setFillColor(0,0,0);
    $pdf->Rect( $margin, $margin+71, $pageWidth - $margin * 2,0);
    $pdf->SetXY(10, 83);
    
    $pdf->Cell(5);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(27, 4, utf8_decode("Razón social: "), 0, 0, "L");
    $pdf->SetFont('Arial','',9);
    if ($destinatario["razon_social"] == "")
        $pdf->MultiCell(66, 4, "-", 0, 'L', 0);
    else
        $pdf->MultiCell(66, 4, utf8_decode($destinatario["razon_social"]), 0, 'L', 0);

    $pdf->Cell(5);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(27, 4, "Domicilio: ", 0, 0, "L");
    $pdf->SetFont('Arial','',9);
    if ($destinatario["direccion"] == "")
        $pdf->MultiCell(66, 4, "-", 0, 'L', 0);
    else
        $pdf->MultiCell(66, 4, utf8_decode($destinatario["direccion"])." (CP: ".$destinatario["cp"]." - ".$destinatario["localidad"].", ".$provincia["nombre"].")", 0, 'L', 0);

    $pdf->SetXY(105, 83);

    $pdf->Cell(4);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(26, 4, utf8_decode("Teléfono: "), 0, 0, "L");
    $pdf->SetFont('Arial','',9);
    if ($movimiento['concepto'] == 1){
        if ($destinatario["telefono1"] == "" && $destinatario["telefono2"] == "")
            $pdf->MultiCell(66, 4, "-", 0, 'L', 0);
        else{
            $tel = "";
            if ($destinatario["telefono2"] != "")
                $tel = " ó ".$destinatario["telefono2"];
            $pdf->MultiCell(66, 4, utf8_decode($destinatario["telefono1"].$tel), 0, 'L', 0);
            
        }
    } else {
        if ($destinatario["tel"] == "")
            $pdf->MultiCell(66, 4, "-", 0, 'L', 0);
        else
            $pdf->MultiCell(66, 4, utf8_decode($destinatario["tel"]), 0, 'L', 0);
    }

    $pdf->Cell(99);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(26, 4, "CUIT: ", 0, 0, "L");
    $pdf->SetFont('Arial','',9);
    if ($destinatario["cuit"] == "")
        $pdf->MultiCell(66, 4, "-", 0, 'L', 0);
    else
        $pdf->MultiCell(66, 4, utf8_decode($destinatario["cuit"]), 0, 'L', 0);

    $pdf->Cell(99);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(26, 4, utf8_decode("Condición IVA: "), 0, 0, "L");
    $pdf->SetFont('Arial','',9);
    if ($destinatario["razon_social"] == "")
        $pdf->MultiCell(66, 4, "-", 0, 'L', 0);
    else
        $pdf->MultiCell(66, 4, utf8_decode($destinatario["iva"]), 0, 'L', 0);

    if ($movimiento['concepto'] == 1){
        $concepto = "Devolución al proveedor por compra de mercadería";
        if ($movimiento['num_factura'] != "") {
            $concepto .= ", según Factura ".$movimiento['letra_factura']." Nº ".$movimiento['num_factura'];
            if ($movimiento['num_factura'] != "")
                $concepto .= " y Remito ".$movimiento['letra_remito']." Nº ".$movimiento['num_remito'];
        } else {
            if ($movimiento['num_remito'] != "")
                $concepto .= ", según Remito ".$movimiento['letra_remito']." Nº ".$movimiento['num_remito'];
        }
        $concepto .= ".";
    } else {
        $concepto = "Envío al cliente por compra de mercadería";
        if ($movimiento['num_factura'] != "") {
            $concepto .= ", según Factura ".$movimiento['letra_factura']." Nº ".$movimiento['num_factura'];
        }
        $concepto .= ".";
    }

    $pdf->Ln(8);
    $pdf->Cell(5);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(27, 4, utf8_decode("Concepto: "), 0, 0, "L");
    $pdf->SetFont('Arial','',9);
    $pdf->MultiCell(156, 4, utf8_decode($concepto), 0, 'L', 0);

    $pdf->Rect( $margin, $margin+102, $pageWidth - $margin * 2,0);
    //------------------------------------------------------------------------------------
    
    $pdf->SetXY(10, 114);
    $pdf->Cell(5);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(94, 4, utf8_decode("Transportista: "), 0, 0, "L");
    $pdf->Cell(27, 4, utf8_decode("CUIT Nº: "), 0, 1, "L");
    $pdf->Ln(2);
    $pdf->Cell(5);
    $pdf->Cell(27, 4, utf8_decode("Domicilio: "), 0, 0, "L");
    $pdf->Rect( $margin+29, $margin+104 , $margin+54 , 4);
    $pdf->Rect( $margin+115, $margin+104 , $margin+59 , 4);
    $pdf->Rect( $margin+29, $margin+110 , $margin+145 , 4);

    //------------------------------------------------------------------------------------
    //Cabecera tabla
    $pdf->SetXY($margin, $margin+116);

    $pdf->setFillColor(180);
    $pdf->SetFont('Arial','',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(18, 6, utf8_decode('Código'), 1, 'C', 1);
    $pdf->SetXY($x + 18, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(145, 6, utf8_decode('Descripción'), 1, 'C', 1);
    $pdf->SetXY($x + 145, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(0, 6, utf8_decode('Cantidad'), 1, 'C', 1);

    //Detalles tabla
    $pdf->setFillColor(0,0,0);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','',8.5);

    foreach ($all_detalles as $detalle):
        $matInsu = find_by_id('inv_materiales_insumos',$detalle['id_matInsu']);

        $pdf->SetXY($x + 18, $y);
        $pdf->MultiCell(145, 4, utf8_decode($matInsu['descripcion'].'  [Marca: '.$matInsu['marca'].' - Tipo: '.$matInsu['tipo'].' - Cod.: '.$matInsu['cod'].']'), 1, 'L');

        $H = $pdf->GetY();
        $height= $H-$y;
        
        $pdf->SetXY($x,$y);
        $pdf->Cell(18,$height,$matInsu['id'],1,1,'C');
        $pdf->SetXY($x + 163, $y);
        $pdf->Cell(0,$height,$detalle['cant'],1,1,'C');

        array_splice($all_detalles, 0, 1);
        $y = $H;

        if ($H > 240)
            goto a;
    endforeach;

    a:
    $pdf->SetXY($margin, 268);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(62,4,utf8_decode('...................................'),0,0,'C');
    $pdf->Cell(62,4,utf8_decode('.............................................'),0,0,'C');
    $pdf->Cell(62,4,utf8_decode('...................................'),0,1,'C');
    $pdf->Cell(62,4,utf8_decode('Recibí Conforme'),0,0,'C');
    $pdf->Cell(62,4,utf8_decode('Aclaración Firma'),0,0,'C');
    $pdf->Cell(62,4,utf8_decode('Nº Documento'),0,0,'C');

    if (count($all_detalles)>0)
        goto b;

    $pdf->Output();
?>
