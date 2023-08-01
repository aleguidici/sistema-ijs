<?php
    
    $page_title = 'Datos de la medición';
    require_once('includes/load.php');
    require('Reportes-PDF/fpdf.php');
    page_require_level(3);
    header("Content-Type: text/html; charset=utf-8");

    $datos_medicion = find_medicion_by_id('datos_medicion',$_GET['datos_medic']);
    $cliente = find_by_id_suc('cliente',$datos_medicion['num_suc']);
    $logo_temp = $cliente['logo'];

    class PDF extends FPDF {
        // Cabecera de página


        function Header(){
            // Logo

            global $logo_temp;

            if ($logo_temp == '0'){
                $this->Image('Reportes-PDF/LogoIJS.png',80,10,45,45);
            } else {
                $nombre_logo = find_by_id('imagen',$logo_temp);
                
                $this->Image('uploads/imagenes/'.$nombre_logo['file_name'],35,10,45,45);
                $this->Image('Reportes-PDF/LogoIJS.png',125,10,45,45);
            }

            $this->Ln(50);
            
        }

        // Pie de página
        function Footer(){
            // Posición: a 45 mm del final
            $this->SetY(-43);
            $this->SetFont('Arial','',10);
            
        //Si debe agregarse un tecnico
            $datos_medicion = find_medicion_by_id('datos_medicion',$_GET['datos_medic']);
            $tecnico = find_by_id_personal('personal', $datos_medicion['id_tecnico']);
            if ($tecnico['id'] == '1'){
                $this->Image('uploads/imagenes/FirmaJulio.png',10,225,45,45);
            }
            if ($tecnico['id'] == '4'){
                $this->Image('uploads/imagenes/FirmaSeba.png',10,225,45,45);
            }
            if ($tecnico['id'] == '18'){
                $this->Image('uploads/imagenes/FirmaFelipe.png',10,225,45,45);
            }
            if ($tecnico['id'] == '16'){
                $this->Image('uploads/imagenes/firmaMendietaDiego.png',10,234,45,29);
            }            
            
            $respons = find_by_id_personal('personal', $datos_medicion['id_profesional']);
            if ($respons['id'] == '1'){
                $this->Image('uploads/imagenes/FirmaJulio.png',155,225,45,45);
            }
            if ($respons['id'] == '21'){
                $this->Image('uploads/imagenes/FirmaIsmaelCarballo.png',158,225,42,42);
            }
            if ($respons['id'] == '26'){
                $this->Image('uploads/imagenes/firmaClaudioMigliorisi.png',160,230,39,39);
            }
            if ($respons['id'] == '10'){
                $this->Image('uploads/imagenes/firmaQuinterosJulioCatamarca.png',160,229,42,33);
            }
            $cliente = find_by_id_suc('cliente',$datos_medicion['num_suc']);
            $mat_tecnico = "";
            $mat_respons = "";
            $all_matriculados = find_all_matriculados('personal_matriculas');
            
            foreach ($all_matriculados as $unaMat):
                if ($unaMat['id_provincia'] == $cliente['provincia']) {
                    if ($unaMat['id_personal'] == $tecnico['id']){
                        $mat_tecnico = $unaMat;
                    }
                    if ($unaMat['id_personal'] == $respons['id']){
                        $mat_respons = $unaMat;
                    }
                }
            endforeach;

            if($tecnico && !($tecnico['id']==$respons['id'])){ 
                $apellido = $this->GetStringWidth($tecnico['apellido']);
                $nombre = $this->GetStringWidth($tecnico['nombre']);
                $cargo = $this->GetStringWidth($tecnico['cargo']);
                if($apellido+$nombre > $cargo){
                    $ancho = $apellido + $nombre + 3;
                } else {
                    $ancho = $cargo + 3;
                }

                $x = $this->GetX();
                $y = $this->GetY();
                $this->Cell($ancho, 4, (utf8_decode($tecnico['apellido'])).', '.utf8_decode($tecnico['nombre']), 0, 0, 'L');
                $this->SetXY($x + 100, $y);
                $this->Cell(0, 4, (utf8_decode($respons['apellido'])).', '.utf8_decode($respons['nombre']), 0, 1, 'R');

                $x = $this->GetX();
                $y = $this->GetY();
                $this->MultiCell($ancho, 4, (utf8_decode($tecnico['cargo'])), 0, 'L');
                $this->SetXY($x + 100, $y);
                $this->Cell(0,4, (utf8_decode($respons['cargo'])), 0, 1, 'R');

                $x = $this->GetX();
                $y = $this->GetY();
                
                if(!empty($mat_tecnico) && $mat_tecnico['num_matricula']){
                    $this->MultiCell($ancho, 4, (utf8_decode($mat_tecnico['num_matricula'])), 0, 'L');
                    $this->SetXY($x + 100, $y); 
                }
                if(!empty($mat_respons))
                    $this->Cell(0, 4, (utf8_decode($mat_respons['num_matricula'])), 0, 1, 'R'); 
                
            } else {
                $this->Cell(0, 4, (utf8_decode($respons['apellido'])).', '.(utf8_decode($respons['nombre'])), 0, 1, 'R');
                $this->Cell(0, 4, (utf8_decode($respons['cargo'])), 0, 1, 'R');
                $this->Cell(0, 4, (utf8_decode($mat_respons['num_matricula'])), 0, 1, 'R'); 
            }
            
            $this->Ln(3);
            $this->setFillColor(0,0,0);
            $this->Cell(0, 0.3, '', 1, 1, '', 1);
            $this->Ln(3);
            
            $this->Cell(80, 4, utf8_decode('IJS Ingeniería'), 0, 0, 'L');
            $this->Cell(0, 4, utf8_decode('Tel / Cel.: (0376) 4592534 / 154285848'), 0, 1, 'R');
            $this->Cell(80, 4, utf8_decode('Av. Lucas Braulio Areco 2353'), 0, 0, 'L');
            $this->Cell(0, 4, utf8_decode('E-mail: ingjsosa@ijsingenieria.com.ar'), 0, 1, 'R');
            $this->Cell(80, 4, utf8_decode('Posadas, Misiones - Argentina'), 0, 0, 'L');
            $this->Cell(0, 4, utf8_decode('www.ijsingenieria.com.ar'), 0, 1, 'R');
        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    
//1º pag
    $pdf->AddPage();
    // Título: Arial bold 15
    $pdf->SetFont('Arial','B',26);
    $pdf->Cell(20);
    $pdf->MultiCell(150,15,utf8_decode('Protocolo de Medición de Resistencia de Puesta a Tierra y Continuidad de Masas '),0,'C');
    
    //Datos cliente
    $pdf->Ln(20);
    $cliente = find_by_id_suc('cliente',$datos_medicion['num_suc']);
    $provincia = find_by_id_prov('provincia', $cliente['provincia']);
    
    $pdf->Cell(15);
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(42, 10, utf8_decode('Razón social: '), 0, 0, 'L');
    $pdf->SetFont('Arial','',16);
    $pdf->MultiCell(0, 10, utf8_decode(($cliente['razon_social'])), 0, 'L');
    
    
    $pdf->Cell(15);
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(42, 10, utf8_decode('Sucursal: '), 0, 0, 'L');
    $pdf->SetFont('Arial','',16);
    $cadena = "N/D";
    if ($cliente['num_suc'] != NULL && $cliente['nombre_suc'] != NULL)
        $cadena = 'Suc. '.$cliente['num_suc'].' - "'.$cliente['nombre_suc'].'"';
    else
        if ($cliente['num_suc'] != NULL && $cliente['nombre_suc'] == NULL)
            $cadena = 'Suc. '.$cliente['num_suc'];
        if ($cliente['num_suc'] == NULL && $cliente['nombre_suc'] != NULL)
            $cadena = 'Suc. "'.$cliente['nombre_suc'].'"';
        
    
    $pdf->MultiCell(0, 10, utf8_decode(($cadena)), 0, 'L');

    $pdf->Cell(15);
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(42, 10, utf8_decode('Dirección: '), 0, 0, 'L');
    $pdf->SetFont('Arial','',16);
    $pdf->MultiCell(0, 10, utf8_decode($cliente['direccion']), 0, 'L');

    $pdf->Cell(15);
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(42, 10, utf8_decode('Localidad: '), 0, 0, 'L');
    $pdf->SetFont('Arial','',16);
    $pdf->MultiCell(0, 10, utf8_decode(($cliente['localidad'])), 0, 'L');

    $pdf->Cell(15);
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(42, 10, utf8_decode('Provincia: '), 0, 0, 'L');
    $pdf->SetFont('Arial','',16);
    $pdf->MultiCell(0, 10, utf8_decode($provincia['nombre']), 0, 'L');

    $pdf->Cell(15);
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(42, 10, utf8_decode('C.P.: '), 0, 0, 'L');
    $pdf->SetFont('Arial','',16);
    $pdf->MultiCell(0, 10, utf8_decode($cliente['cp']), 0, 'L');

    $pdf->Cell(15);
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(42, 10, utf8_decode('CUIT: '), 0, 0, 'L');
    $pdf->SetFont('Arial','',16);
    $pdf->MultiCell(0, 10, utf8_decode($cliente['cuit']), 0, 'L');

//2º pag
    //Encabezado de la tabla
    $pdf->AddPage();

    $pdf->SetFont('Times','B',16);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(15);
    $pdf->MultiCell(160, 8, utf8_decode('PROTOCOLO DE MEDICIÓN DE LA PUESTA A TIERRA Y CONTINUIDAD DE LAS MASAS'), 1, 'C', 1);
    $pdf->Ln(5);

    $pdf->SetFont('Times','',14);
    $pdf->SetTextColor(0,0,0);

    $pdf->Cell(15);
    $pdf->MultiCell(160, 7, utf8_decode('Razón social: '.$cliente['razon_social']), 1, 'L');
    
    $pdf->Cell(15);
    $pdf->MultiCell(160, 7, utf8_decode('Sucursal: '.$cadena), 1, 'L');

    $pdf->Cell(15);
    $pdf->MultiCell(160, 7, utf8_decode('Dirección: '.$cliente['direccion']), 1, 'L');

    $pdf->Cell(15);
    $pdf->Cell(160, 7, utf8_decode('Localidad: '.$cliente['localidad']), 1, 1, 'L');

    $pdf->Cell(15);
    $pdf->Cell(160, 7, utf8_decode('Provincia: '.$provincia['nombre']), 1, 1, 'L');

    $pdf->Cell(15);
    $pdf->Cell(80, 7, utf8_decode('C.P.: '.$cliente['cp']), 1, 0, 'L');
    $pdf->Cell(80, 7, utf8_decode('CUIT: '.$cliente['cuit']), 1, 1, 'L');

    $pdf->Ln(5);
    $instrumento = find_by_id_instrumento('inv_instrumentos',$datos_medicion['nro_instrumento']);

    $pdf->SetFont('Times','B',14);
    $pdf->setFillColor(180,180,180);
    $pdf->Cell(15);
    $pdf->Cell(160, 7, utf8_decode('DATOS PARA MEDICIÓN'), 1, 1, 'C', 1);
    
    $pdf->SetFont('Times','',13);
    $pdf->Cell(15);
    $pdf->MultiCell(160, 7, utf8_decode("Instrumento utilizado:   Analizador de instalaciones\n" .'      Marca "'.$instrumento['marca'].'", Modelo "'.$instrumento['modelo'].'", Nº de serie: "'.$instrumento['num_serie']).'"', 1, 'L');
    $pdf->Cell(15);
    $pdf->Cell(160, 1, '', 1, 1, '', 0);

    $pdf->Cell(15);
    $fecha = $instrumento['fecha_calibracion'];
    list($año, $mes, $dia) = explode('-', $fecha);

    $pdf->Cell(160, 7, utf8_decode('Fecha de calibración del instrumento utilizado: '.$dia.'/'.$mes.'/'.$año), 1, 1, 'C');
    $pdf->Cell(15);
    $pdf->Cell(160, 1, '', 1, 1, '', 0);

    $pdf->Cell(15);
    $fecha_m = $datos_medicion['fecha_medicion'];
    list($año_m, $mes_m, $dia_m) = explode('-', $fecha_m);

    $pdf->Cell(70, 7, utf8_decode('Fecha de medición: '.$dia_m.'/'.$mes_m.'/'.$año_m), 1, 0, 'C');
    $pdf->Cell(48, 7, utf8_decode('Hora de inicio: '.substr($datos_medicion['hora_inicio'],0,5)), 1, 0, 'C');
    $pdf->Cell(42, 7, utf8_decode('Hora de fin: '.substr($datos_medicion['hora_finalizado'],0,5)), 1, 1, 'C');
    $pdf->Cell(15);

    $pdf->setFillColor(0,0,0);
    $pdf->Cell(160, 1, '', 1, 1, '', 1);

    $pdf->SetFont('Times','B',14);
    $pdf->setFillColor(220,220,220);
    $pdf->Cell(15);
    $pdf->Cell(160, 7, utf8_decode('Metodología utilizada'), 1, 1, 'C', 1);

    $pdf->SetFont('Times','',11.5);
    $pdf->Cell(15);
    $pdf->MultiCell(160, 5, utf8_decode('      Mide la resistencia de la tierra sin la necesidad de clavar jabalinas o de medir con cables externos. La medición de puesta a tierra usa el camino real de la corriente de tierra y la corriente generada por la red eléctrica, sin la necesidad de ninguna desconexión. La resistencia de tierra mostrada en pantalla es exactamente la que la corriente de tierra necesitaría para ir a través de la misma si llegara a ocurrir una falla en la tierra (no hace falta).'), 1, 'L', 0);

    $pdf->SetFont('Times','B',14);
    $pdf->setFillColor(220,220,220);
    $pdf->Cell(15);
    $pdf->Cell(160, 7, utf8_decode('Observaciones'), 1, 1, 'C', 1);

    $pdf->SetFont('Times','',11.5);
    $pdf->Cell(15);
    $pdf->MultiCell(160, 5, utf8_decode("      ".'El valor de la medición obtenida por el instrumento corresponde a la impedancia de puesta a tierra. Pero dado el régimen de frecuencia y configuración de la puesta a tierra, este valor puede tomarse como el de resistencia de puesta a tierra sin mayores errores.'), 1, 'L', 0);

    $pdf->SetFont('Times','B',14);
    $pdf->setFillColor(220,220,220);
    $pdf->Cell(15);
    $pdf->Cell(160, 7, utf8_decode('Documentación que se Adjuntara a la Medición'), 1, 1, 'C', 1);

    $pdf->SetFont('Times','',11.5);
    $pdf->Cell(15);
    $pdf->MultiCell(160, 5, utf8_decode(" - Certificado de Calibración;\n - Plano o croquis;"), 1, 'L', 0);

//3º pag
    //Encabezado tabla
    $pdf->AddPage();
    $pdf->SetFont('Times','B',12);
    $pdf->setFillColor(0,0,0);
    $pdf->SetTextColor(255,255,255);
    $pdf->MultiCell(0, 6, utf8_decode('PROTOCOLO DE MEDICIÓN DE LA PUESTA A TIERRA Y CONTINUIDAD DE LAS MASAS'), 1, 'C', 1);
    $pdf->SetFont('Times','',12);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(120, 5, utf8_decode('Razón social: '.$cliente['razon_social']), 1, 0, 'L');
    $pdf->Cell(0, 5, utf8_decode('CUIT: '.$cliente['cuit']), 1, 1, 'L');
    $pdf->MultiCell(0, 5, utf8_decode('Sucursal: '.$cadena), 1, 'L');
    $pdf->MultiCell(0, 5, utf8_decode('Dirección: '.$cliente['direccion']), 1, 'L');
    $pdf->Cell(100, 5, utf8_decode('Localidad: '.$cliente['localidad']), 1, 0, 'L');
    $pdf->Cell(60, 5, utf8_decode('Provincia: '.$provincia['nombre']), 1, 0, 'L');
    $pdf->MultiCell(0, 5, utf8_decode('C.P.: '.$cliente['cp']), 1, 'L');
    
    $pdf->Ln(5);

    $pdf->SetFont('Times','B',12);
    $pdf->setFillColor(180,180,180);
    $pdf->Cell(0, 6, utf8_decode('DATOS DE LA MEDICIÓN'), 1, 1, 'C', 1);

    $pdf->SetFont('Times','',9);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(9, 4, "\n\n\n\n".utf8_decode('Nro. de toma de tierra')."\n\n\n\n\n", 1, 'C');
    $pdf->SetXY($x + 9, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(27, 4, "\n\n\n\n\n\n".utf8_decode('Sector')."\n\n\n\n\n\n\n", 1, 'C');
    $pdf->SetXY($x + 27, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(24.5, 4, utf8_decode("\nDescripción de la condición del terreno al momento de la medición\n". '(Lecho seco / Arcilloso / Pantanoso / Lluvias recientes / Arenoso seco o húmedo / Otro)'."\n\n"), 1, 'C');
    $pdf->SetXY($x + 24.5, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(34, 4, utf8_decode("\n".'Uso de la puesta a tierra'."\n". '(Toma de Tierra del neutro de Transformador / Toma de Tierra de Seguridad de las Masas / De Protección de equipos Electrónicos / De Informática / De Iluminación / De Pararrayos / Otros)'."\n\n\n"), 1, 'C');
    $pdf->SetXY($x + 34, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(14, 4, "\n\n".utf8_decode('Esquema de conexión a tierra utilizado'."\n". '(TT / TN-S / TN-C / TNC-S / IT)'."\n\n"), 1, 'C');
    $pdf->SetXY($x + 14, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(25, 4, utf8_decode('Medición de la puesa a tierra'), 1, 'C');
    $pdf->SetXY($x, $y+8);
    $pdf->MultiCell(15, 4, "\n\n".utf8_decode('Valor obtenido en la medición expresado en ohm')."\n\n\n\n", 1, 'C');
    $pdf->SetXY($x+15, $y+8);
    $pdf->MultiCell(10, 4, "\n\n\n".utf8_decode('Cum_ ple (SI / NO)')."\n\n\n\n\n", 1, 'C');
    $pdf->SetXY($x + 25, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(37, 4, "Continuidad de\nlas masas", 1, 'C');
    $pdf->SetXY($x, $y+8);
    $pdf->MultiCell(15, 4, "\n".utf8_decode('El circuito de puesta a tierra es continuo y perma_ nente')."\n(SI / NO)\n\n\n", 1, 'C');
    $pdf->SetXY($x+15, $y+8);
    $pdf->MultiCell(22, 4, utf8_decode('El circuito de puesta a tierra tiene la capacidad de carga para conducir la corriente de falla y una resistencia apropiada (SI / NO)'), 1, 'C');
    $pdf->SetXY($x + 37, $y);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(0, 4, utf8_decode('Para la protección contra contactos indirectos se utiliza:'."\n".'(Dispositivo Diferencial (DD) / Interruptor Automático (IA) / Fusible (Fus).'), 1, 'C');

    //Cuerpo tabla
    $all_detalles = find_detalles_by_id('detalles_medicion', $datos_medicion['id_medicion']);
    
    foreach ($all_detalles as $detalle):
        $size = $pdf->GetStringWidth($detalle['uso_puesta_tierra']);
        $size2 = $pdf->GetStringWidth($detalle['sector']);
        
        $max1 = $size/30;
        $max2 = $size2/26;
        $renglones1 = ceil($max1); //2 siempre
        $renglones2 = ceil($max2);
        
        $altura = 4*(max($renglones1, $renglones2));
        
        $pdf->Cell(9, $altura, utf8_decode($detalle['numero_toma']), 1, 0, 'C');
        
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        
        if ($renglones2 == 1)
            $pdf->MultiCell(27, $altura, utf8_decode($detalle['sector']), 1, 'C');
        if ($renglones2 == 2)
            $pdf->MultiCell(27, $altura-4, utf8_decode($detalle['sector']), 1, 'C');
        if ($renglones2 == 3)
            $pdf->MultiCell(27, $altura-8, utf8_decode($detalle['sector']), 1, 'C');
            
        $pdf->SetXY($x + 27, $y);
        
        
        
        $pdf->Cell(24.5, $altura, utf8_decode($detalle['descrip_terreno']), 1, 0, 'C');
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        
        $pdf->MultiCell(34, $altura-(($altura/4)-1)*4, utf8_decode($detalle['uso_puesta_tierra']), 1, 'C');
            
        $pdf->SetXY($x + 34, $y);
        $pdf->Cell(14, $altura, utf8_decode($detalle['esquema_conexion']), 1, 0, 'C');
        $pdf->Cell(15, $altura, utf8_decode($detalle['valor_medicion']), 1, 0, 'C');
        $pdf->Cell(10, $altura, utf8_decode($detalle['cumple']), 1, 0, 'C');
        $pdf->Cell(15, $altura, utf8_decode($detalle['circuito_continuo']), 1, 0, 'C');
        $pdf->Cell(22, $altura, utf8_decode($detalle['conducir_corriente']), 1, 0, 'C');
        $pdf->Cell(0, $altura, utf8_decode($detalle['proteccion_contactos']), 1, 1, 'C');
    endforeach;

//4º pag
    //Encabezado tabla
    $pdf->AddPage();
    $pdf->SetFont('Times','B',12);
    $pdf->setFillColor(0,0,0);
    $pdf->SetTextColor(255,255,255);
    $pdf->MultiCell(0, 6, utf8_decode('PROTOCOLO DE MEDICIÓN DE LA PUESTA A TIERRA Y CONTINUIDAD DE LAS MASAS'), 1, 'C', 1);
    $pdf->SetFont('Times','',12);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(120, 5, utf8_decode('Razón social: '.$cliente['razon_social']), 1, 0, 'L');
    $pdf->Cell(0, 5, utf8_decode('CUIT: '.$cliente['cuit']), 1, 1, 'L');
    $pdf->MultiCell(0, 5, utf8_decode('Sucursal: '.$cadena), 1, 'L');
    $pdf->MultiCell(0, 5, utf8_decode('Dirección: '.$cliente['direccion']), 1, 'L');
    $pdf->Cell(100, 5, utf8_decode('Localidad: '.$cliente['localidad']), 1, 0, 'L');
    $pdf->Cell(60, 5, utf8_decode('Provincia: '.$provincia['nombre']), 1, 0, 'L');
    $pdf->MultiCell(0, 5, utf8_decode('C.P.: '.$cliente['cp']), 1, 'L');
    
    $pdf->Ln(5);

    $pdf->SetFont('Times','B',14);
    $pdf->setFillColor(180,180,180);
    $pdf->Cell(0, 6, utf8_decode('Análisis de los Datos y Mejoras a Realizar'), 1, 1, 'C', 1);

    $pdf->SetFont('Times','B',14);
    $pdf->setFillColor(220,220,220);
    $pdf->Cell(0, 7, utf8_decode('Conclusiones'), 1, 1, 'C', 1);

    $pdf->SetFont('Times','',11.5);
    $pdf->MultiCell(0, 5, utf8_decode('      '.$datos_medicion['conclusiones']), 1, 'L', 0);

    $pdf->Ln(5);

    $pdf->SetFont('Times','B',14);
    $pdf->setFillColor(220,220,220);
    $pdf->Cell(0, 7, utf8_decode('Recomendaciones'), 1, 1, 'C', 1);

    $pdf->SetFont('Times','',11.5);
    $pdf->MultiCell(0, 5, utf8_decode('      '.$datos_medicion['recomendaciones']), 1, 'L', 0);

//5º pag
    //Encabezado tabla
    $pdf->AddPage();
    $pdf->SetFont('Times','B',12);
    $pdf->setFillColor(0,0,0);
    $pdf->SetTextColor(255,255,255);
    $pdf->MultiCell(0, 6, utf8_decode('PROTOCOLO DE MEDICIÓN DE LA PUESTA A TIERRA Y CONTINUIDAD DE LAS MASAS'), 1, 'C', 1);
    $pdf->SetFont('Times','',12);
    $pdf->SetTextColor(0,0,0);
    $pdf->Cell(120, 5, utf8_decode('Razón social: '.$cliente['razon_social']), 1, 0, 'L');
    $pdf->Cell(0, 5, utf8_decode('CUIT: '.$cliente['cuit']), 1, 1, 'L');
    $pdf->MultiCell(0, 5, utf8_decode('Sucursal: '.$cadena), 1, 'L');
    $pdf->MultiCell(0, 5, utf8_decode('Dirección: '.$cliente['direccion']), 1, 'L');
    $pdf->Cell(100, 5, utf8_decode('Localidad: '.$cliente['localidad']), 1, 0, 'L');
    $pdf->Cell(60, 5, utf8_decode('Provincia: '.$provincia['nombre']), 1, 0, 'L');
    $pdf->MultiCell(0, 5, utf8_decode('C.P.: '.$cliente['cp']), 1, 'L');

    $pdf->Ln(5);

    $pdf->Cell(15);
    
    $croquis = find_imagen($cliente['croquis']);

    if ($croquis['id'] !== '0'){
        $pdf->Cell(0, 7, 'Croquis:', 0, 1, 'L', 0);
        $x_act = $pdf->GetX();
        $y_act = $pdf->GetY();
        
        $x = 170;
        $y = 140;
        $mov = 10;

        list($x1, $y1) = getimagesize('uploads/imagenes/'.$croquis['file_name']);

        if ($x1/17 > $y1/14) {
            $y = $y1 * ($x / $x1);
            $x = 0; }
        else {
            if ($x1/17 < $y1/14) {
                $x = $x1 * ($y / $y1);
                $y = 0;
                $mov = 95-($x/2);
            } else {
                $x = 0;
                $y = 0;
            }
        }
                
        $pdf->Image('uploads/imagenes/'.$croquis['file_name'],$x_act+$mov, $y_act, $x, $y);
    } else {
        $pdf->Cell(0, 7, 'Croquis: ', 0, 1, 'L', 0);
        $pdf->Ln(25);
        $pdf->SetFont('Times','I',12);
        $pdf->Cell(0, 7, '- No disponible -', 0, 1, 'C', 0);
    }

    $pdf->Output(I, utf8_decode($cadena." ".$cliente['razon_social']." - ".$provincia['nombre'].", ".$cliente['localidad']." (".$cliente['direccion'].").pdf"));
?>