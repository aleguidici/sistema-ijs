<?php
    $page_title = 'Orden de compra';
    require_once('includes/load.php');
    require('Reportes-PDF/fpdf.php');
    page_require_level(2);

    $orden_compra = find_by_id('orden_compra',$_GET['orden_comp']);
    $proveedor = find_by_id('proveedor', $orden_compra['id_proveedor']);
    $provincia = find_by_id_prov('provincia', $proveedor['provincia']);
    $all_detalles = find_detalles2_by_id('orden_detalles', $orden_compra['id']);
    $fin = false;
    $cont = 0;
    $subtotal_suma = 0;
    $iva_suma = 0;

    $pdf = new FPDF();
    $pdf->AliasNbPages();
    
//1º pag
    $hojaOK = false;
    do {
        $pdf->AddPage();
        // PARA ANULADO $pdf->Image('Reportes-PDF/LogoIJS.png',10,10,190,277);
    
        //---Encabezado---
        $pageWidth = 210;
        $pageHeight = 297;
        $margin = 10;
    
        $pdf->Image('Reportes-PDF/LogoIJS.png',32,9,29);
        $pdf->Rect( $margin, $margin , $pageWidth - $margin * 2, $pageHeight - $margin * 2);
    
        $pdf->SetFont('Arial','B',30);
        $pdf->Cell(75);
        $pdf->Cell(30, 20, 'OC', 1, 0, 'C');
    
        $pdf->SetFont('Arial','',14);
        
        $cod = '';
        for ($i = 1; $i <= (7-strlen($orden_compra['id'])); $i++) {
            $cod .= '0';
            if ($i == '3')
            $cod .= '-';
        }
    
        $pdf->MultiCell(0, 20, utf8_decode('Orden de compra '.$cod.$orden_compra['id']), 0, 'C');
        $pdf->Ln(1);
        $pdf->SetFont('Arial','',14);
        $pdf->Cell(75, 10, 'Sosa Julio Jorge', 0, 0, 'C');
    
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(40);
        list($año, $mes, $dia) = explode('-', $orden_compra['fecha_emision']);
        $pdf->Cell(0, 10, utf8_decode('Fecha de emisión: '.$dia.'/'.$mes.'/'.$año), 0, 1, 'L');
        
        list($año2, $mes2, $dia2) = explode('-', $orden_compra['fecha_validez']);
        $pdf->Cell(115);
        $pdf->Cell(0, 0, utf8_decode('Vto. de Orden de compra: '.$dia2.'/'.$mes2.'/'.$año2), 0, 1, 'L');
    
        $pdf->Line($margin, $margin + 34, $pageWidth - $margin, $margin + 34);
        
        $pdf->SetFont('Arial','',9);
        $pdf->Ln(4);
        // pdf->Cell(0, 5, utf8_decode('Calle 75 C Nº 8475 - Mza. 312 - Barrio 170 / Viv. Ducon'), 0, 1, 'L');
        $pdf->Cell(0, 4, utf8_decode('Av. Lucas Braulio Areco N°2353 P.B.'), 0, 1, 'L');
        $pdf->Cell(115, 4, utf8_decode('(3300) Posadas - Misiones'), 0, 0, 'L');
        $pdf->Cell(0, 4, utf8_decode('CUIT: 20-18576428-2'), 0, 1, 'L');
        $pdf->Cell(115, 4, utf8_decode('Tel: 376-4285848'), 0, 0, 'L');
        $pdf->Cell(0, 4, utf8_decode('Ingresos Brutos: 20185764282'), 0, 1, 'L');
        $pdf->Cell(115, 4, utf8_decode('ingjsosa@ijsingenieria.com.ar'), 0, 0, 'L');
        $pdf->Cell(0, 4, utf8_decode('Inicio de actividades: 02/01/2012'), 0, 1, 'L');
        $pdf->Cell(115, 4, utf8_decode('www.ijsingenieria.com.ar'), 0, 0, 'L');
        $pdf->Cell(0, 4, utf8_decode('Condición IVA: Responsable Inscripto'), 0, 1, 'L');
    
        $pdf->Line($margin, $margin + 56, $pageWidth - $margin, $margin + 56);
        $pdf->Ln(2);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0, 4, utf8_decode('Proveedor: '.$proveedor['razon_social']), 0, 1, 'L');
        $pdf->Cell(0, 4, utf8_decode('Dirección: '.$proveedor['direccion'].' - '.$proveedor['localidad']. ', '.$provincia['nombre']), 0, 1, 'L');
        //---Encabezado---
    
        $pdf->Ln(1);
    
        //---Encabezado Tabla---
        $pdf->setFillColor(180,180,180);
        $pdf->SetFont('Arial','',8);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->MultiCell(8, 8, utf8_decode('Item'), 1, 'C', 1);
        $pdf->SetXY($x + 8, $y);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->MultiCell(95, 8, utf8_decode('Descripción'), 1, 'C', 1);
        $pdf->SetXY($x + 95, $y);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->MultiCell(9, 8, utf8_decode('Cant.'), 1, 'C', 1);
        $pdf->SetXY($x + 9, $y);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->MultiCell(19, 4, utf8_decode('Precio unitario'), 1, 'C', 1);
        $pdf->SetXY($x + 19, $y);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->MultiCell(10, 4, utf8_decode('% Bonif.'), 1, 'C', 1);
        $pdf->SetXY($x + 10, $y);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->MultiCell(18, 8, utf8_decode('Subtotal'), 1, 'C', 1);
        $pdf->SetXY($x + 18, $y);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->MultiCell(13, 4, utf8_decode('Alicuota IVA'), 1, 'C', 1);
        $pdf->SetXY($x + 13, $y);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->MultiCell(0, 4, utf8_decode('Subtotal c/ IVA'), 1, 'C', 1);
        //---Encabezado Tabla---
        
        //---Cuerpo tabla---
        $pdf->setFillColor(0,0,0);
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetFont('Arial','',7);
    
        if($orden_compra['moneda'] == '0'){
            $moneda = '$ ';
        }
        if($orden_compra['moneda'] == '1'){
            $moneda = 'U$S ';
        }
        if($orden_compra['moneda'] == '2'){
            $moneda = 'R$ ';
        }
        if($orden_compra['moneda'] == '3'){
            $moneda = 'Gs. ';
        }
        $ver =  $pdf->GetY();
    
        foreach ($all_detalles as $detalle):
            $pdf->SetXY($x + 8, $y);
            $pdf->MultiCell(95, 3.5, htmlspecialchars_decode(utf8_decode($detalle['descripcion'])), 1, 'L');
    
            $H = $pdf->GetY();
            $height= $H-$y;
            
            $pdf->SetXY($x,$y);
            $pdf->Cell(8,$height,$detalle['item'],1,1,'C');
            $pdf->SetXY($x + 103, $y);
            $pdf->Cell(9,$height,$detalle['cantidad'],1,1,'C');
            $pdf->SetXY($x + 112, $y);
            $precio = str_replace('.', '[', number_format($detalle['precio_unit'],2));
            $precio = str_replace(',', '.', $precio);
            $precio = str_replace('[', ',', $precio);
            $pdf->Cell(19,$height,$moneda.$precio,1,1,'C');
            $pdf->SetXY($x + 131, $y);
            $pdf->Cell(10,$height,str_replace('.', ',', $detalle['bonificacion']).' %',1,1,'C');
            $pdf->SetXY($x + 141, $y);
            $subtotal = round($detalle['cantidad']*$detalle['precio_unit']*(1-$detalle['bonificacion']/100),2);
            $subtotal_ok = str_replace('.', '[', number_format($subtotal,2));
            $subtotal_ok = str_replace(',', '.', $subtotal_ok);
            $subtotal_ok = str_replace('[', ',', $subtotal_ok);
            $pdf->Cell(18,$height,$moneda.$subtotal_ok,1,1,'C');
            $pdf->SetXY($x + 159, $y);
            $pdf->Cell(13,$height,str_replace('.', ',', $detalle['iva']).' %',1,1,'C');
            $pdf->SetXY($x + 172, $y);
            $subtotal_iva = round($subtotal*(1+$detalle['iva']/100),2);
            
            $subtotal_iva_ok = str_replace('.', '[', number_format($subtotal_iva,2));
            $subtotal_iva_ok = str_replace(',', '.', $subtotal_iva_ok);
            $subtotal_iva_ok = str_replace('[', ',', $subtotal_iva_ok);
            $pdf->Cell(0,$height,$moneda.$subtotal_iva_ok,1,1,'C');
    
            $subtotal_suma += $subtotal;
            $iva_suma += round($subtotal*($detalle['iva']/100),2);
    
            unset($all_detalles[$cont]);
            $cont++;
            $y = $H;
            $ver =  $pdf->GetY();
            if ($ver > 233) {
                goto eti;
            }
        endforeach;
        
        
        $pdf->setFillColor(180,180,180);
        $pdf->SetFont('Arial','',9);
        $y = $pdf->GetY();
        $pdf->SetXY($x + 103, $y);
        $pdf->Cell(45,5,'Importe Neto Gravado',0,0,'L',1);
        $pdf->Cell(17,5,$moneda,0,0,'C',1);
        $pdf->Cell(0,5,str_replace('.', ',', number_format($subtotal_suma,2)),0,1,'R',1);
        $pdf->SetXY($x + 103, $y+4);
        $pdf->Cell(45,5,'IVA',0,0,'L',1);
        $pdf->Cell(17,5,$moneda,0,0,'C',1);
        $pdf->Cell(0,5,str_replace('.', ',', number_format($iva_suma,2)),0,1,'R',1);
        $pdf->SetXY($x + 103, $y+8);
        $pdf->Cell(45,5,'Total Presupuesto',0,0,'L',1);
        $pdf->Cell(17,5,$moneda,0,0,'C',1);
        $pdf->Cell(0,5,str_replace('.', ',', number_format($subtotal_suma+$iva_suma,2)),0,1,'R',1);
        $pdf->Rect( $margin+103, $margin+$y-10 , $pageWidth - 103 - $margin * 2, $pageHeight - 264 - $margin * 2);
        
        $hojaOK = true;
        eti:
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x, 240);
        $pdf->SetFont('Arial','BU',8);
        $pdf->Cell(27,3,'Observaciones:',0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->MultiCell(155,3, htmlspecialchars_decode(utf8_decode($orden_compra['observaciones'])), 0, 'J');
        
        $pdf->Ln(2);
        $pdf->SetFont('Arial','BU',8);
        $pdf->Cell(27,3,'Forma de pago:',0,0,'L');
        $pdf->SetFont('Arial','',8);
        $x1 = $pdf->GetX();
        $y1 = $pdf->GetY();
        $pdf->MultiCell(63,3, htmlspecialchars_decode(utf8_decode($orden_compra['forma_pago'])), 0, 'J');
        
        $H1 = $pdf->GetY();
        $height1= $H1-$y1;
        $pdf->SetXY($x1+67, $y1);
        
        $pdf->SetFont('Arial','BU',8);
        $pdf->Cell(27,3,utf8_decode('Forma de envío:'),0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(0,3,htmlspecialchars_decode(utf8_decode($orden_compra['forma_envio'])),0,1,'J');
    
        $pdf->Ln($height1);
        $pdf->SetFont('Arial','BU',8);
        $pdf->Cell(27,3,utf8_decode('Dirección:'),0,0,'L');
        $pdf->SetFont('Arial','',8);
        $y = $pdf->GetY();
        $pdf->MultiCell(67,3, htmlspecialchars_decode(utf8_decode('Av. Lucas Braulio Areco N°2353 P.B. - Posadas, Misiones')), 0, 'L');
    
        $pdf->SetXY(104, $y);
        $pdf->SetFont('Arial','BU',8);
        $pdf->Cell(47,3,'Validez de la orden de compra:',0,0,'L');
        $pdf->SetFont('Arial','',8);
        $date1 = $orden_compra['fecha_emision'];
        $date2 = $orden_compra['fecha_validez'];
        $fecha1 = strtotime($orden_compra['fecha_emision']); 
        $fecha2 = strtotime($orden_compra['fecha_validez']); 
        $cant = 0;
        for($fecha1;$fecha1<=$fecha2;$fecha1=strtotime('+1 day ' . date('Y-m-d',$fecha1))){ 
            if((strcmp(date('D',$fecha1),"Sun")!=0) && (strcmp(date('D',$fecha1),"Sat")!=0)){
                $cant += 1;
            }
        }
        // $diff = abs(strtotime($date2) - strtotime($date1));
        // $years = floor($diff / (365*60*60*24));
        // $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        // $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24))+1;
        $pdf->Cell(0,3,utf8_decode($cant.' días hábiles'),0,1,'L');
        
        if ($orden_compra['anulado'] == 1) {
            $pdf->Image('Reportes-PDF/ANULADO.png',20,65,160);        
        }
            $fin = true;
    } while ($hojaOK==false);
    
    $pdf->Output();
?>