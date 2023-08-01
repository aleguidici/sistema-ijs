<?php
    ini_set('default_charset', 'utf-8');
    $page_title = 'Presupuesto';
    require_once('includes/load.php');
    require('Reportes-PDF/fpdf.php');
    page_require_level(2);

    $idReparacion = find_by_id('reparacion_maquina',$_GET['idReparacion']);
    $estaMaquina = find_by_id('maquina',$idReparacion['id_maquina']);
    $esteModelo = find_by_id('maquina_modelo',$estaMaquina['modelo_id']);
    $estaMarca = find_by_id('maquina_marca',$esteModelo['marca_id']);
    $tipoMaquina = find_by_id('maquina_tipo',$esteModelo['tipo_id']);
    $esteCliente = find_by_id('clientemaquina',$estaMaquina['id_cliente']);
    

    $pdf = new FPDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pageWidth = 105;
    $pageHeight = 148;
    $margin = 5;
    //$pdf->SetDrawColor(100, 0, 0, 0);
	//$pdf->SetFillColor(100, 0, 0, 0);
	//$pdf->SetTextColor(100, 0, 0, 0);

    //encabezado
    //$pdf->SetFillColor(175,255,125);
    $pdf->SetFillColor(210,255,200);
    $pdf->SetTextColor(0);   
    $pdf->Rect( $margin, $margin , $pageWidth - ($margin * 1.5), $pageHeight - $margin * 2, 'DF');
    $pdf->SetFillColor(255,230,200); 
    $pdf->Rect( $pageWidth + ($margin / 2 ), $margin, $pageWidth - ($margin * 1.5), $pageHeight - $margin * 2,'DF');
    $pdf->Image('Reportes-PDF/LogoIJS.png',10,11,22);
    $pdf->Image('Reportes-PDF/LogoIJS.png',112,11,22);
    $pdf->SetFont('Arial','B',10);    
    $pdf->SetDrawColor(50);


    $pdf->Cell(102, 1, utf8_decode('Comprobante de reparación de Máquina Eléctrica'), 0, 0, 'L');
    $pdf->Cell(100, 1, utf8_decode('Comprobante de reparación de Máquina Eléctrica'), 0, 1, 'L');
    $pdf->SetFont('Arial','I',7);
    $pdf->Ln(5);
    $pdf->Cell(28);
    $pdf->Cell(102, 3, utf8_decode('Av. Lucas Braulio Areco 2353'), 0, 0, 'L');
    $pdf->Cell(100, 3, utf8_decode('Av. Lucas Braulio Areco 2353'), 0, 1, 'L');
    $pdf->Cell(28);
    $pdf->Cell(102, 3, utf8_decode('Tel: 376 4 689490'), 0, 0, 'L');
    $pdf->Cell(100, 3, utf8_decode('Tel: 376 4 689490'), 0, 1, 'L');
    $pdf->Cell(28);
    $pdf->Cell(102, 3, utf8_decode('Email: administracion@ijsingenieria.com.ar'), 0, 0, 'L');
    $pdf->Cell(100, 3, utf8_decode('Email: administracion@ijsingenieria.com.ar'), 0, 1, 'L');
    $pdf->Cell(28);
    $pdf->Cell(102, 3, utf8_decode('www.ijsingenieria.com.ar'), 0, 0, 'L');
    $pdf->Cell(100, 3, utf8_decode('www.ijsingenieria.com.ar'), 0, 1, 'L');
	$pdf->Line($margin, $margin + 26, $pageWidth - ($margin / 2) , $margin + 26);
    $pdf->Line($pageWidth + ($margin / 2), $margin + 26, ($pageWidth * 2) - $margin , $margin + 26);
    $pdf->SetFont('Arial','B',12); 
    $pdf->Ln(6);
    $pdf->Cell(26);   
    $pdf->Cell(90, 4, utf8_decode('Comprobante IJS Ingeniería'), 0, 0, 'L');
    $pdf->Cell(100, 4, utf8_decode('Comprobante para el Cliente'), 0, 1, 'L');
    $pdf->Ln(4);
    $pdf->setFillColor(0,0,0);
    $pdf->SetFont('Arial','',10);

//----------------
    $x = $pdf->GetX();
    $y = $pdf->GetY(); 
    $pdf->SetXY($x - 3, $y - 9);
    $pdf->SetFont('Arial','I',8);     
    $pdf->MultiCell(24, 6, htmlspecialchars_decode(utf8_decode('IJS-ME: '.$estaMaquina['id'])), 1, 'L', 0); 
    $pdf->SetXY($x, $y);
    $pdf->Cell(-3);
    $x = $pdf->GetX();
    $y = $pdf->GetY();    
    $pdf->MultiCell(7, 6, utf8_decode('ID:'), 1, 'L', 0);
    $pdf->SetXY($x + 8, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    $pdf->MultiCell(16, 6, htmlspecialchars_decode(utf8_decode($idReparacion['id'])), 1, 'L', 0);
    $pdf->SetXY($x + 17, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','',10);
    $pdf->MultiCell(25, 6, 'Fecha y hora:', 1, 'L', 0);
    $pdf->SetXY($x + 26, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    list($año, $mes, $dia) = explode('-', $idReparacion['fecha_ingreso']);
    $pdf->MultiCell(42, 6, utf8_decode($dia.'/'.$mes.'/'.$año.' - '.$idReparacion['hora_ingreso']), 1, 'L', 0);
    $pdf->SetXY($x + 51, $y);

    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(7, 6, utf8_decode('ID:'), 1, 'L', 0);
    $pdf->SetXY($x + 8, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    $pdf->MultiCell(16, 6, htmlspecialchars_decode(utf8_decode($idReparacion['id'])), 1, 'L', 0);
    $pdf->SetXY($x + 17, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','',10);
    $pdf->MultiCell(25, 6, 'Fecha y hora:', 1, 'L', 0);
    $pdf->SetXY($x + 26, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    list($año, $mes, $dia) = explode('-', $idReparacion['fecha_ingreso']);
    $pdf->MultiCell(43, 6, utf8_decode($dia.'/'.$mes.'/'.$año.' - '.$idReparacion['hora_ingreso']), 1, 'L', 0);
    $pdf->SetXY($x + 26, $y + 8);
    $pdf->Ln(2);

//-------------------

    $pdf->Cell(-3);
    $pdf->SetFont('Arial','',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(15, 6, utf8_decode('Cliente:'), 1, 'L', 0);
    $pdf->SetXY($x + 16, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    $pdf->MultiCell(77, 6, htmlspecialchars_decode(utf8_decode($esteCliente['razon_social'])), 1, 'L', 0);
    $pdf->SetXY($x + 86, $y);

    
    $pdf->SetFont('Arial','',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(15, 6, utf8_decode('Cliente:'), 1, 'L', 0);
    $pdf->SetXY($x + 16, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    $pdf->MultiCell(78, 6, htmlspecialchars_decode(utf8_decode($esteCliente['razon_social'])), 1, 'L', 0);
    $pdf->SetXY($x + 16, $y + 8);
    $pdf->Ln(2);

//--------------------

    $pdf->Cell(-3);
    $pdf->SetFont('Arial','',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(19, 6, utf8_decode('Localidad:'), 1, 'L', 0);
    $pdf->SetXY($x + 20, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    $pdf->MultiCell(23, 6, htmlspecialchars_decode(utf8_decode($esteCliente['localidad'])), 1, 'L', 0);
    $pdf->SetXY($x + 24, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','',10);
    $pdf->MultiCell(15, 6, utf8_decode('Cel/Tel:'), 1, 'L', 0);
    $pdf->SetXY($x + 16, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    if ($esteCliente['cel'] != ""){
   	$pdf->MultiCell(33, 6, htmlspecialchars_decode(utf8_decode($esteCliente['cel'])), 1, 'L', 0);
    } elseif ($esteCliente['tel'] != "") {
    $pdf->MultiCell(33, 6, htmlspecialchars_decode(utf8_decode($esteCliente['tel'])), 1, 'L', 0);
    } else {
    $pdf->MultiCell(33, 6, utf8_decode('-----'), 1, 'C', 0);
    }
	$pdf->SetXY($x + 42, $y);

    $pdf->SetFont('Arial','',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(19, 6, utf8_decode('Localidad:'), 1, 'L', 0);
    $pdf->SetXY($x + 20, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    $pdf->MultiCell(24, 6, htmlspecialchars_decode(utf8_decode($esteCliente['localidad'])), 1, 'L', 0);
    $pdf->SetXY($x + 25, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','',10);
    $pdf->MultiCell(15, 6, utf8_decode('Cel/Tel:'), 1, 'L', 0);
    $pdf->SetXY($x + 16, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    if ($esteCliente['cel'] != ""){
   	$pdf->MultiCell(33, 6, htmlspecialchars_decode(utf8_decode($esteCliente['cel'])), 1, 'L', 0);
    } elseif ($esteCliente['tel'] != "") {
    $pdf->MultiCell(33, 6, htmlspecialchars_decode(utf8_decode($esteCliente['tel'])), 1, 'L', 0);
    } else {
    $pdf->MultiCell(33, 6, utf8_decode('-----'), 1, 'C', 0);
    }
    $pdf->SetXY($x + 16, $y + 8);
    $pdf->Ln(2);

//-----------------------------

    $pdf->Cell(-3);
    $pdf->SetFont('Arial','',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(17, 6, utf8_decode('Maquina:'), 1, 'L', 0);
    $pdf->SetXY($x + 18, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    $pdf->MultiCell(38, 6, htmlspecialchars_decode(utf8_decode($tipoMaquina['descripcion'])), 1, 'L', 0);
    $pdf->SetXY($x + 39, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','',10);
    $pdf->MultiCell(13, 6, utf8_decode('Marca:'), 1, 'L', 0);
    $pdf->SetXY($x + 14, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    $pdf->MultiCell(22, 6, htmlspecialchars_decode(utf8_decode($estaMarca['descripcion'])), 1, 'L', 0);
    $pdf->SetXY($x + 31, $y);

    $pdf->SetFont('Arial','',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(17, 6, utf8_decode('Maquina:'), 1, 'L', 0);
    $pdf->SetXY($x + 18, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    $pdf->MultiCell(38, 6, htmlspecialchars_decode(utf8_decode($tipoMaquina['descripcion'])), 1, 'L', 0);
    $pdf->SetXY($x + 39, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','',10);
    $pdf->MultiCell(13, 6, utf8_decode('Marca:'), 1, 'L', 0);
    $pdf->SetXY($x + 14, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    $pdf->MultiCell(23, 6, htmlspecialchars_decode(utf8_decode($estaMarca['descripcion'])), 1, 'L', 0);
    $pdf->SetXY($x + 12, $y + 8);
    $pdf->Ln(2);

//---------------------------------------------------

    $pdf->Cell(-3);
    $pdf->SetFont('Arial','',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(15, 6, utf8_decode('Modelo:'), 1, 'L', 0);
    $pdf->SetXY($x + 16, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    $pdf->MultiCell(30, 6, htmlspecialchars_decode(utf8_decode($esteModelo['codigo'])), 1, 'L', 0);
    $pdf->SetXY($x + 31, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','',10);
    $pdf->MultiCell(17, 6, utf8_decode('N° Serie:'), 1, 'L', 0);
    $pdf->SetXY($x + 18, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    $pdf->MultiCell(28, 6, htmlspecialchars_decode(utf8_decode($estaMaquina['num_serie'])), 1, 'L', 0);
    $pdf->SetXY($x + 37, $y);

    $pdf->SetFont('Arial','',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(15, 6, utf8_decode('Modelo:'), 1, 'L', 0);
    $pdf->SetXY($x + 16, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    $pdf->MultiCell(30, 6, htmlspecialchars_decode(utf8_decode($esteModelo['codigo'])), 1, 'L', 0);
    $pdf->SetXY($x + 31, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','',10);
    $pdf->MultiCell(17, 6, utf8_decode('N° Serie:'), 1, 'L', 0);
    $pdf->SetXY($x + 18, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    $pdf->MultiCell(29, 6, htmlspecialchars_decode(utf8_decode($estaMaquina['num_serie'])), 1, 'L', 0);
    $pdf->SetXY($x + 35, $y + 8);
    $pdf->Ln(2);

    //----------------------

    $pdf->Cell(-3);
    $pdf->SetFont('Arial','',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();    
    $pdf->MultiCell(27, 6, utf8_decode('Observaciones:'), 1, 'L', 0);
    $pdf->SetXY($x + 102, $y);
    $pdf->Rect($x, $y + 7, $x + 86, 32);

    $pdf->SetFont('Arial','',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(27, 6, utf8_decode('Observaciones:'), 1, 'L', 0);
    $pdf->SetXY($x + 16, $y + 5);
    $pdf->Rect($x, $y + 7, ($x + 87) - 102 , 32);
    $pdf->Ln(2);

    //-------------------

	$pdf->Cell(-3);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    $pdf->MultiCell(93, 6, htmlspecialchars_decode(utf8_decode($idReparacion['descripcion'])), 0, 'L', 0);
    $pdf->SetXY($x + 102, $y);

    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    $pdf->MultiCell(94, 6, htmlspecialchars_decode(utf8_decode($idReparacion['descripcion'])), 0, 'L', 0);
    $pdf->SetXY($x, $y + 31);
    $pdf->Ln(2);

    //--------------------

    $pdf->Cell(-3);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','',9.5);
    $pdf->MultiCell(70, 6, utf8_decode('Entrega de dinero para la revisión del equipo:'), 1, 'L', 0);
    $pdf->SetXY($x + 71, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    $pdf->MultiCell(22, 6,  htmlspecialchars_decode(utf8_decode("$ ".$idReparacion['senia'])), 1, 'L', 0);
    $pdf->SetXY($x + 31, $y);

    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','',9.5);
    $pdf->MultiCell(70, 6, utf8_decode('Entrega de dinero para la revisión del equipo:'), 1, 'L', 0);
    $pdf->SetXY($x + 71, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->SetFont('Arial','I',8);
    $pdf->MultiCell(23, 6,  htmlspecialchars_decode(utf8_decode("$ ".$idReparacion['senia'])), 1, 'L', 0);
    $pdf->SetXY($x + 20, $y);






    //muestra el pdf
	$pdf->Output();
    //ESTO ES SOLO EN EL ONLINE, EN EL LOCAL NO FUNCIONA
    //$pdf->Output(I, $dia.'-'.$mes.'-'.$año."_Comprobante de reparacion_".$esteCliente['razon_social']."_".$tipoMaquina['descripcion']."_".$estaMarca['descripcion']."_".$esteModelo['codigo'].".pdf");
    ?>