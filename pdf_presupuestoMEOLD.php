<?php
    ini_set('default_charset', 'utf-8');
    $page_title = 'Presupuesto';
    require_once('includes/load.php');
    require('Reportes-PDF/fpdf.php');
    page_require_level(2);

    $estaReparacion = find_by_id('reparacion_maquina',$_GET['idReparacion']);
    $estaMaquina = find_by_id('maquina',$estaReparacion['id_maquina']);
    $esteModelo = find_by_id('maquina_modelo',$estaMaquina['modelo_id']);
    $estaMarca = find_by_id('maquina_marca',$esteModelo['marca_id']);
    $tipoMaquina = find_by_id('maquina_tipo',$esteModelo['tipo_id']);
    $esteCliente = find_by_id('clientemaquina',$estaMaquina['id_cliente']);
    $fechaActual = date('d-m-Y H:i:s');
    $allRepuestos = find_all_reparacion_id_reparacion_repuesto('reparacion_repuesto',$_GET['idReparacion']);
    $mano_obra = $_GET['man'];    
    $senia = $_GET['sen'];
    $recargo = $_GET['rec'];
    $grasa = $_GET['gra'];
    $flete = $_GET['fle'];
    $parcial = $_GET['par'];
    $parcial2 = $parcial + $recargo + $grasa;
    $totales2 = $parcial2 + $mano_obra;
    $totales = $_GET['tot'];
    

    $pdf = new FPDF('P','mm','A5');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pageWidth = 148;
    $pageHeight = 210;
    $margin = 5;
    //encabezado
    //$pdf->SetFillColor(175,255,125);
    //$pdf->SetFillColor(210,255,200);
    $pdf->SetTextColor(0);   
    //$pdf->Rect( $margin, $margin, $pageWidth - ($margin * 2), $pageHeight - ($margin * 2), 'DF');
    $pdf->Image('Reportes-PDF/LogoIJS.png',5,-5,45);
    $pdf->SetFont('Arial','B',18);    
    $pdf->SetDrawColor(50);
    $pdf->Ln(-4.5);
    $pdf->Cell(45);
    $cod = '';
        for ($i = 1; $i <= (7-strlen($estaReparacion['id'])); $i++) {
            $cod .= '0';
            if ($i == '3')
            $cod .= '-';
        }
    $pdf->MultiCell(93, 7, htmlspecialchars_decode(utf8_decode("PRESUPUESTO N°: ".$cod.$estaReparacion['id'])), 0, 'L', 0);
    //$pdf->Cell(0, 7, htmlspecialchars_decode(utf8_decode("PRESUPUESTO N°: ".$estaReparacion['id'])), 0, 2, 'L');
    $pdf->SetFont('Arial','I',10);
    $pdf->Cell(0,3.5,"",0,2);
    $pdf->Cell(45);
    $pdf->Cell(102, 3.5, utf8_decode('Av. Lucas Braulio Areco 2353'), 0, 2, 'L');
    $pdf->Cell(102, 3.5, utf8_decode('Cel: 376 4 689490'), 0, 2, 'L');
    $pdf->Cell(102, 3.5, utf8_decode('Email: administracion@ijsingenieria.com.ar'), 0, 2, 'L');
    $pdf->Cell(102, 3.5, utf8_decode('www.ijsingenieria.com.ar'), 0, 2, 'L');
    $pdf->Ln(8);
	//$pdf->Line($margin, $margin + 26, $pageWidth - $margin  , $margin + 26);

    //fecha y cliente
    $pdf->Rect( $margin, $margin + 30 , $pageWidth - ($margin * 2), 22);
    $pdf->SetFont('Arial','',12);
    //$pdf->Cell(0, 8, htmlspecialchars_decode(utf8_decode("PRESUPUESTO N°: ".$idReparacion['id'])), 0, 2, 'L');



    $pdf->Cell(-5);
    $x = $pdf->GetX();
    $y = $pdf->GetY();    
    $pdf->MultiCell(18, 6, utf8_decode('FECHA:'), 0, 'L', 0);
    $pdf->SetXY($x + 18, $y);

    $pdf->SetFont('Arial','I',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();    
    $pdf->MultiCell(0, 6, htmlspecialchars_decode(utf8_decode($fechaActual)), 0, 'L', 0);
    $pdf->SetXY($x - 18, $y + 10);

    $pdf->SetFont('Arial','',12);
    $x = $pdf->GetX();
    $y = $pdf->GetY();    
    $pdf->MultiCell(22, 6, utf8_decode('CLIENTE:'), 0, 'L', 0);
    $pdf->SetXY($x + 22, $y);

    $pdf->SetFont('Arial','I',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();    
    $pdf->MultiCell(0, 6, htmlspecialchars_decode(utf8_decode($esteCliente['razon_social'])), 0, 'L', 0);
    $pdf->SetXY($x - 22, $y + 14);

    //datos maquina
    $pdf->Rect( $margin, $margin + 54 , $pageWidth - ($margin * 2), 83);

    $pdf->SetFont('Arial','',12);
    $x = $pdf->GetX();
    $y = $pdf->GetY();    
    $pdf->MultiCell(23, 6, utf8_decode('MAQUINA:'), 0, 'L', 0);
    $pdf->SetXY($x + 23, $y);

    $pdf->SetFont('Arial','I',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();    
    $pdf->MultiCell(55, 6, htmlspecialchars_decode(utf8_decode($tipoMaquina['descripcion'])), 0, 'L', 0);
    $pdf->SetXY($x + 55, $y);

    $pdf->SetFont('Arial','',12);
    $x = $pdf->GetX();
    $y = $pdf->GetY();    
    $pdf->MultiCell(19, 6, htmlspecialchars_decode(utf8_decode('MARCA:')), 0, 'L', 0);
    $pdf->SetXY($x + 19, $y);

    $pdf->SetFont('Arial','I',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();    
    $pdf->MultiCell(0, 6, htmlspecialchars_decode(utf8_decode($estaMarca['descripcion'])), 0, 'L', 0);
    $pdf->SetXY($x, $y + 10);

    $pdf->Ln(1);
    $pdf->Cell(-5);
    $pdf->SetFont('Arial','',12);
    $x = $pdf->GetX();
    $y = $pdf->GetY();    
    $pdf->MultiCell(23, 6, htmlspecialchars_decode(utf8_decode('MODELO:')), 0, 'L', 0);
    $pdf->SetXY($x + 23, $y);

    $pdf->SetFont('Arial','I',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();    
    $pdf->MultiCell(55, 6, htmlspecialchars_decode(utf8_decode($esteModelo['codigo'])), 0, 'L', 0);
    $pdf->SetXY($x + 55, $y);
    
    $pdf->SetFont('Arial','',12);
    $x = $pdf->GetX();
    $y = $pdf->GetY();    
    $pdf->MultiCell(19, 6, htmlspecialchars_decode(utf8_decode('SERIE:')), 0, 'L', 0);
    $pdf->SetXY($x + 19, $y);

    $pdf->SetFont('Arial','I',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();    
    $pdf->MultiCell(0, 6, htmlspecialchars_decode(utf8_decode($estaMaquina['num_serie'])), 0, 'L', 0);
    $pdf->SetXY($x, $y + 10);

    $pdf->Ln(1);
    $pdf->Cell(-5);
    $pdf->SetFont('Arial','',12);
    $x = $pdf->GetX();
    $y = $pdf->GetY();    
    $pdf->MultiCell(40, 6, htmlspecialchars_decode(utf8_decode('OBSERVACIONES:')), 0, 'L', 0);
    $pdf->SetXY($x + 1, $y + 6);

    $pdf->SetFont('Arial','I',10);
    $pdf->Rect($x + 1, $y + 6, $x + 131, 18);
    $x = $pdf->GetX();
    $y = $pdf->GetY();    
    $pdf->MultiCell(0, 6, htmlspecialchars_decode(utf8_decode($estaReparacion['descripcion'])), 0, 'L', 0);
    $pdf->SetXY($x - 1, $y + 21);

    $pdf->SetFont('Arial','',12);
    $x = $pdf->GetX();
    $y = $pdf->GetY();    
    $pdf->MultiCell(40, 6, htmlspecialchars_decode(utf8_decode('REPUESTOS:')), 0, 'L', 0);
    $pdf->SetXY($x + 1, $y + 6);

    $pdf->SetFont('Arial','I',10);
    $pdf->Rect($x + 1, $y + 6, $x + 131, 24);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $c = 0 ;
 /*   foreach ($allRepuestos as $unRepuesto):
        $c = $c + 1;
        $elRepuesto = find_by_id('maquina_repuesto',$unRepuesto['repuesto_id']);
        $medir = strlen($elRepuesto['descripcion']);
        if ($medir > 20) {
            $pdf->Cell(($medir * 2) + 10, 6, htmlspecialchars_decode(utf8_decode($elRepuesto['descripcion']."; ")), 1, 0, 'L');
        } else {
            $pdf->Cell(($medir + 16.5), 6, htmlspecialchars_decode(utf8_decode($elRepuesto['descripcion']."; ")), 1, 0, 'L');
        }
        if ($c == 3) {
            $pdf->Ln(1);
            $pdf->SetXY($x, $y + 6);
        }
        if ($c == 6){
            $pdf->Ln(1);
            $pdf->SetXY($x, $y + 12);
        }
    //$pdf->MultiCell(0, 6, htmlspecialchars_decode(utf8_decode($unRepuesto['repuesto_id'])), 1, 'L', 0);
    endforeach;
    */
    $concatena = "";
    foreach ($allRepuestos as $unRepuesto):
        $elRepuesto = find_by_id('maquina_repuesto',$unRepuesto['repuesto_id']);
        if($unRepuesto['elegido'] == 1){
            $concatena = $concatena.$elRepuesto['descripcion']."; ";
        }
    endforeach;
    $pdf->MultiCell(0, 5, htmlspecialchars_decode(utf8_decode($concatena)), 0, 'L', 0);
    //$pdf->Cell(0, 6, htmlspecialchars_decode(utf8_decode($concatena)), 1, 0, 'L');
    $pdf->SetXY($x - 1, $y + 30);





//datos del presupuesto
  
    $pdf->Rect( $margin, $margin + 139 , $pageWidth - ($margin * 2), 44);
    $pdf->SetFont('Arial','',11);
    $x = $pdf->GetX();
    $y = $pdf->GetY();  
    $pdf->MultiCell(94, 6, htmlspecialchars_decode(utf8_decode('- COSTO DE MANO DE OBRA POR REPARACIÓN:')), 0, 'L', 0);
    $pdf->SetXY($x + 94, $y);

    $pdf->SetFont('Arial','I',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();  
    $pdf->MultiCell(0, 6, htmlspecialchars_decode(utf8_decode("$ ".$mano_obra)), 0, 'R', 0);
    $pdf->SetXY($x - 94, $y + 8);

    $pdf->SetFont('Arial','',11);
    $x = $pdf->GetX();
    $y = $pdf->GetY();  
    $pdf->MultiCell(101, 6, htmlspecialchars_decode(utf8_decode('- COSTO DE MATERIALES Y PIEZAS A REEMPLAZAR:')), 0, 'L', 0);
    $pdf->SetXY($x + 101, $y);

    $pdf->SetFont('Arial','I',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();  
    $pdf->MultiCell(0, 6, htmlspecialchars_decode(utf8_decode("$ ".round($parcial2,0))), 0, 'R', 0);
    $pdf->SetXY($x - 24, $y + 8);

    $pdf->SetFont('Arial','',11);
    $x = $pdf->GetX();
    $y = $pdf->GetY();  
    $pdf->MultiCell(24, 6, htmlspecialchars_decode(utf8_decode('SUBTOTAL:')), 0, 'R', 0);
    $pdf->SetXY($x + 24, $y);

    $pdf->SetFont('Arial','I',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();  
    $pdf->MultiCell(0, 6, htmlspecialchars_decode(utf8_decode("$ ".round($totales2,0))), 0, 'R', 0);
    $pdf->SetXY($x - 101, $y + 8);

    $pdf->SetFont('Arial','',11);
    $x = $pdf->GetX();
    $y = $pdf->GetY();  
    $pdf->MultiCell(51, 6, htmlspecialchars_decode(utf8_decode('- ENTREGA DE ANTICIPO:')), 0, 'L', 0);
    $pdf->SetXY($x + 101, $y);

    $pdf->SetFont('Arial','I',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();  
    $pdf->MultiCell(0, 6, htmlspecialchars_decode(utf8_decode("$ ".$senia)), 0, 'R', 0);
    $pdf->SetXY($x - 16, $y + 8);

    $pdf->Line($x - 0.5,$y + 7,$x + 33,$y + 7);

    $pdf->SetFont('Arial','',11);
    $x = $pdf->GetX();
    $y = $pdf->GetY();  
    $pdf->MultiCell(16, 6, htmlspecialchars_decode(utf8_decode('TOTAL:')), 0, 'R', 0);
    $pdf->SetXY($x + 16, $y);

    $pdf->SetFont('Arial','I',10);
    $x = $pdf->GetX();
    $y = $pdf->GetY();  
    $pdf->MultiCell(0, 6, htmlspecialchars_decode(utf8_decode("$ ".round($totales,0))), 0, 'R', 0);
    $pdf->SetXY($x - 101, $y + 7);





//----------------
   

    //muestra el pdf
	//$pdf->Output();
    //ESTO ES SOLO EN EL ONLINE, EN EL LOCAL NO FUNCIONA
    //$pdf->Output(I, "Presupuesto_".$cod.$estaReparacion['id']."_".$esteCliente['razon_social'].".pdf");
    $pdf->Output();
    ?>