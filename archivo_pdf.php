<?php
setlocale(LC_ALL,"es_MX");
//date_default_timezone_set("America/Tijuana");
// include class
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('fpdf/fpdf.php');
require('beans/db.class.php');
require('beans/db_catalogos.class.php');
require('class/pdf.class.php');
require('class/pdf_catalogos.class.php');
include('fpdf/phpqrcode/qrlib.php');		

set_time_limit(999999);
ob_start();
// extend class
//echo 'Versión actual de PHP: ' . phpversion();
function num2letras($num, $fem = false, $dec = true, $cur = 4) { 
   //$num = 3242.52;
   $matuni[2]  = "dos"; 
   $matuni[3]  = "tres"; 
   $matuni[4]  = "cuatro"; 
   $matuni[5]  = "cinco"; 
   $matuni[6]  = "seis"; 
   $matuni[7]  = "siete"; 
   $matuni[8]  = "ocho"; 
   $matuni[9]  = "nueve"; 
   $matuni[10] = "diez"; 
   $matuni[11] = "once"; 
   $matuni[12] = "doce"; 
   $matuni[13] = "trece"; 
   $matuni[14] = "catorce"; 
   $matuni[15] = "quince"; 
   $matuni[16] = "dieciseis"; 
   $matuni[17] = "diecisiete"; 
   $matuni[18] = "dieciocho"; 
   $matuni[19] = "diecinueve"; 
   $matuni[20] = "veinte"; 
   $matunisub[2] = "dos"; 
   $matunisub[3] = "tres"; 
   $matunisub[4] = "cuatro"; 
   $matunisub[5] = "quin"; 
   $matunisub[6] = "seis"; 
   $matunisub[7] = "sete"; 
   $matunisub[8] = "ocho"; 
   $matunisub[9] = "nove"; 

   $matdec[2] = "veint"; 
   $matdec[3] = "treinta"; 
   $matdec[4] = "cuarenta"; 
   $matdec[5] = "cincuenta"; 
   $matdec[6] = "sesenta"; 
   $matdec[7] = "setenta"; 
   $matdec[8] = "ochenta"; 
   $matdec[9] = "noventa"; 
   $matsub[3]  = 'mill'; 
   $matsub[5]  = 'bill'; 
   $matsub[7]  = 'mill'; 
   $matsub[9]  = 'trill'; 
   $matsub[11] = 'mill'; 
   $matsub[13] = 'bill'; 
   $matsub[15] = 'mill'; 
   $matmil[4]  = 'millones'; 
   $matmil[6]  = 'billones'; 
   $matmil[7]  = 'de billones'; 
   $matmil[8]  = 'millones de billones'; 
   $matmil[10] = 'trillones'; 
   $matmil[11] = 'de trillones'; 
   $matmil[12] = 'millones de trillones'; 
   $matmil[13] = 'de trillones'; 
   $matmil[14] = 'billones de trillones'; 
   $matmil[15] = 'de billones de trillones'; 
   $matmil[16] = 'millones de billones de trillones'; 
   
   //Zi hack
   $float=explode('.',$num);
   $num=$float[0];

   $num = trim((string)@$num); 
   if ($num[0] == '-') { 
      $neg = 'menos '; 
      $num = substr($num, 1); 
   }else 
      $neg = ''; 
   while ($num[0] == '0') $num = substr($num, 1); 
   if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
   $zeros = true; 
   $punt = false; 
   $ent = ''; 
   $fra = ''; 
   for ($c = 0; $c < strlen($num); $c++) { 
      $n = $num[$c]; 
      if (! (strpos(".,'''", $n) === false)) { 
         if ($punt) break; 
         else{ 
            $punt = true; 
            continue; 
         } 

      }elseif (! (strpos('0123456789', $n) === false)) { 
         if ($punt) { 
            if ($n != '0') $zeros = false; 
            $fra .= $n; 
         }else 

            $ent .= $n; 
      }else 

         break; 

   } 
   $ent = '     ' . $ent; 
   if ($dec and $fra and ! $zeros) { 
      $fin = ' coma'; 
      for ($n = 0; $n < strlen($fra); $n++) { 
         if (($s = $fra[$n]) == '0') 
            $fin .= ' cero'; 
         elseif ($s == '1') 
            $fin .= $fem ? ' una' : ' un'; 
         else 
            $fin .= ' ' . $matuni[$s]; 
      } 
   }else 
      $fin = ''; 
   if ((int)$ent === 0) return 'Cero ' . $fin; 
   $tex = ''; 
   $sub = 0; 
   $mils = 0; 
   $neutro = false; 
   while ( ($num = substr($ent, -3)) != '   ') { 
     //echo($num);
      $ent = substr($ent, 0, -3); 
      if (++$sub < 3 and $fem) { 
         $matuni[1] = 'una'; 
         $subcent = 'as'; 
      }else{ 
         $matuni[1] = $neutro ? 'un' : 'uno'; 
         $subcent = 'os'; 
      } 
      $t = ''; 
      $n2 = substr($num, 1); 
      if ($n2 == '00') { 
      }elseif ($n2 < 21) 
         $t = ' ' . $matuni[(int)$n2]; 
      elseif ($n2 < 30) { 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      }else{ 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      } 
      $n = $num[0];
      //echo($n); 
      if($n != ' '){
         if ($n == 1) { 
            $t = ' ciento' . $t; 
         }elseif ($n == 5){ 
            $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
         }elseif ($n != 0){ 
            $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
         }          
      }

      if ($sub == 1) { 
      }elseif (! isset($matsub[$sub])) { 
         if ($num == 1) { 
            $t = ' mil'; 
         }elseif ($num > 1){ 
            $t .= ' mil'; 
         } 
      }elseif ($num == 1) { 
         $t .= ' ' . $matsub[$sub] . '?n'; 
      }elseif ($num > 1){ 
         $t .= ' ' . $matsub[$sub] . 'ones'; 
      }   
      if ($num == '000') $mils ++; 
      elseif ($mils != 0) { 
         if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
         $mils = 0; 
      } 
      $neutro = true; 
      $tex = $t . $tex; 
   } 
   $tex = $neg . substr($tex, 1) . $fin; 
   //Zi hack --> return ucfirst($tex);
   $end_num= ucfirst($tex).' '.(($cur != 4) ? utf8_decode('Dólares') : 'Pesos').' '.$float[1].'/100 M.'.(($cur != 4) ? 'E' : 'N').'.';
   return $end_num; 
} 

if(!is_null($_GET['id'])){

   $obj = new PdfMethods();
   $objSat = new SatCatalogos();
   $result = $obj->getGeneralData($_GET['id']);
   if(!is_null($result)){
      $rowVoucher = $result->fetch_assoc();
   }

   $result2 = $obj->getNominaData($_GET['id']);
   if(!is_null($result2)){
      $rowNomina = $result2->fetch_assoc();
   }

   $result3 = $obj->getDeduction($_GET['id']);
   if(!is_null($result3)){
      $rowDeduction = $result3->fetch_assoc();
   }

   $result4 = $obj->getPerception($_GET['id']);
   if(!is_null($result4)){
      $rowPerception = $result4->fetch_assoc();
   }

   $arrayDeduction = array();
   $result5 = $obj->getAllDeductions($_GET['id']);
   if(!is_null($result5)){
      $cx = $result5->num_rows;
      if($cx != 0){
         foreach($result5 as $row){
               $deductions = array();
               $deductions["Ddc_TipoDeduccion"] = $row["Ddc_TipoDeduccion"];
               $deductions["Ddc_Clave"] = $row["Ddc_Clave"];
               $deductions["Ddc_Concepto"] = $row["Ddc_Concepto"];
               $deductions["Ddc_Importe"] = $row["Ddc_Importe"];
               array_push($arrayDeduction, $deductions);
         }
      }
   }

   $arrayPerception = array();
   $result6 = $obj->getAllPerceptions($_GET['id']);
   if(!is_null($result6)){
      $cx = $result6->num_rows;
      if($cx != 0){
         foreach($result6 as $row){
               $perception = array();
               $perception["Prc_TipoPercepcion"] = $row["Prc_TipoPercepcion"];
               $perception["Prc_Clave"] = $row["Prc_Clave"];
               $perception["Prc_Concepto"] = $row["Prc_Concepto"];
               $perception["Prc_ImporteGravado"] = $row["Prc_ImporteGravado"];
               $perception["Prc_ImporteExento"] = $row["Prc_ImporteExento"];
               array_push($arrayPerception, $perception);
         }
      }
   }

   $arrayOtherPyment = array();
   $result7 = $obj->getOtherPayment($_GET['id']);
   if(!is_null($result7)){
      $cx = $result7->num_rows;
      if($cx != 0){
         foreach($result7 as $row){
               $otherpay = array();
               $otherpay["Otr_TipoOtroPago"] = $row["Otr_TipoOtroPago"];
               $otherpay["Otr_Clave"] = $row["Otr_Clave"];
               $otherpay["Otr_Concepto"] = $row["Otr_Concepto"];
               $otherpay["Otr_Importe"] = $row["Otr_Importe"];
               array_push($arrayOtherPyment, $otherpay);
         }
      }
   }


   class KodePDF extends FPDF {
      protected $fontName = 'Arial';
      
      function Header(){
         $obj = new PdfMethods();
         $result = $obj->getMunIDToPicture($_GET['id']);
         
         if(!is_null($result)){
            $rowVoucher = $result->fetch_assoc();
         }
        // echo($rowVoucher["Mun_ID"]);
         if($rowVoucher["Mun_ID"] == 1){
            //Logo 
            $this->Image('fpdf/images/logoTecate.jpeg', 10, 5, 21, 31.5);
            //$this->Image("fpdf/images/logo.png", 10, 8, 30, 40,'png');	
            $this->SetXY(10,55);            
         }else{
            $this->Image('fpdf/images/logoRosarito.jpg', 10, 5, 31.5, 31.5);
            //$this->Image("fpdf/images/logo.png", 10, 8, 30, 40,'png');	
            $this->SetXY(10,55);      
         }

      }
  
      function Footer()				{
          //Posición: a 1,5 cm del final
          $this->SetY(-10);
          //Arial italic 8
          $this->SetFont('Arial','',8);
          //Número de página  html_entity_decode('&#225') (Documento sin validez fiscal)
              $this->Image('fpdf/images/logoVita.jpeg', 12, 283, 35, 9);
          $this->Cell(0, 3, utf8_decode("                                                                            Este documento es una representación impresa de un CFDI.                                                                                  "), 0, 0, 'L');
      }//End Footer
  }

   // create document
   $pdf = new KodePDF();
   $pdf->AddPage();

   // config document
   $pdf->SetTitle('Generar archivos PDF');
   $pdf->SetAuthor('Kodetop');
   $pdf->SetCreator('FPDF Maker');

   $pdf->SetXY(120,8);
   $pdf->SetFont('Arial','B',10);
   $pdf->Cell(40,4, $rowVoucher["Emi_Nombre"],0,2,'C');
   $pdf->SetXY(120,12);
   $pdf->Cell(40, 3, "RFC:".$rowVoucher["Emi_RFC"], 0, 2, 'C');
   $pdf->SetFont('Arial','',9);
   $pdf->SetXY(120,16);
   $pdf->Cell(40, 3, "601-General de Ley Personas Morales", 0, 2,'C');
   if($rowVoucher["Mun_ID"] == 1){
      $pdf->SetXY(120,20);
      $pdf->Cell(40, 3, utf8_decode("Callejón Libertad 1310, Zona Centro CP 21400 Tecate, Baja California, México")  , 0, 2,'C');
      $pdf->SetXY(120,24);
   }else{
      $pdf->SetXY(120,20);
      $pdf->Cell(40, 3, utf8_decode("José Haroz Aguilar 2000, Villa Turistica, Playas de Rosarito, Baja California, México")  , 0, 2,'C');
      $pdf->SetXY(120,24);
   }
   $pdf->setXY(10, 40);
   $pdf->SetFont('Arial','',7);
   $pdf->Cell(75, 3, utf8_decode("Lugar de expedición:"),0,0,'L');
   $pdf->Cell(20, 3,$rowVoucher["c_CodigoPostal"],0,1,'R');
   $pdf->Cell(75, 3, utf8_decode("Fecha de expedición:"),0,0,'L');

   $date = explode(".", $rowVoucher["Cfdi_Fecha"]);
   $pdf->Cell(20, 3, $date[0],0,1,'R');
   $pdf->Cell(75, 3,"No. de Certificado:",0,0,'L');
   $pdf->Cell(20, 3,$rowVoucher["Cfdi_NoCertificado"],0,1,'R');
   $pdf->Cell(75, 3,"Tipo:",0,0,'L');
   $pdf->Cell(20, 3,$rowVoucher["c_TipoDeComprobante"],0,1,'R');
   $pdf->Cell(75, 3,"Version CFDI:",0,0,'L');
   $pdf->Cell(20, 3,$rowVoucher["Cfdi_Version"],0,1,'R');
   $pdf->Cell(75, 3, utf8_decode("Tipo de Nómina"),0,0,'L');
   if($rowVoucher["Nom_TipoNomina"] == "O"){
     $pdf->Cell(20, 3, "Ordinaria",0,1,'R'); 
   }else{
      $pdf->Cell(20, 3, "Extraordinaria",0,1,'R'); 
   }
   
   $pdf->Cell(75, 3, "Origen Recurso",0,0,'L');
   // $pdf->Cell(20, 3, $rowVoucher["Nom_OrigenRecurso"],0,1,'R');
   if($rowVoucher["Nom_OrigenRecurso"] == "IM"){
      $pdf->Cell(20, 3, "Ingreso Mixto",0,1,'R');
      $pdf->Cell(75, 3, "Monto Recurso Propio",0,0,'L');
      $pdf->Cell(20, 3, $rowVoucher["Nom_MontoRecursoPropio"],0,1,'R');
      $pdf->setXY(10, $pdf->GetY()+.5);
      $pdf->SetFont('Arial','B',7);
      $pdf->Cell(60, 3, utf8_decode("RECEPTOR: ".$rowVoucher["Rec_RFC"]." ".$rowVoucher["Rec_Nombre"]." ".$rowVoucher["Rec_Apellido_Paterno"]." ".$rowVoucher["Rec_Apellido_Materno"]),0,0,"L");
   }else{
      $pdf->Cell(20, 3, "Ingreso Propio",0,1,'R');
      $pdf->setXY(10, $pdf->GetY()+.5);
      $pdf->SetFont('Arial','B',7);
      $pdf->Cell(60, 3, utf8_decode("RECEPTOR: ".$rowVoucher["Rec_RFC"]." ".$rowVoucher["Rec_Nombre"]." ".$rowVoucher["Rec_Apellido_Paterno"]." ".$rowVoucher["Rec_Apellido_Materno"]),0,0,"L");
   }
   

   
   $pdf->setXY(110, 40);
   $pdf->SetFont('Arial','',7);
   $pdf->Cell(70, 3,utf8_decode("Método de pago:"),0,0,'L');
   $resultMethod = $objSat->getPayMethod($rowVoucher["c_MetodoPago"]);
   if(!is_null($resultMethod)){
      $rowMethod = $resultMethod->fetch_assoc();
   }
   if(!is_null($rowMethod)){
      $pdf->Cell(20, 3,utf8_decode($rowVoucher["c_MetodoPago"]." - ".$rowMethod["Descripcion"]),0,1,'R');
   }else{
      $pdf->Cell(20, 3,"",0,1,'R');
   }
   
   //$pdf->setXY(120, 60);
   $pdf->setXY(110, 43);
   $pdf->Cell(70, 3,"Forma de Pago:",0,0,'L');
   $resultMode = $objSat->getPayMode($rowVoucher["c_FormaPago"]);
   if(!is_null($resultMode)){
      $rowMode = $resultMode->fetch_assoc();
   }

   if(!is_null($rowMode)){
      $pdf->Cell(20, 3,utf8_decode($rowVoucher["c_FormaPago"]." - ".$rowMode["Descripcion"]) ,0,1,'R');
   }else{
      $pdf->Cell(20, 3,"",0,1,'R');
   }
   
   $pdf->setXY(110, 46);
   $pdf->Cell(70, 3,"Uso CFDI:",0,0,'L');
   $resultCFDI = $objSat->getUsoCFDI($rowVoucher["Cfdi_UsoCFDI"]);
   if(!is_null($resultCFDI)){
      $rowCFDI = $resultCFDI->fetch_assoc();
   }

   if(!is_null($rowCFDI)){
      $pdf->Cell(20, 3,utf8_decode($rowVoucher["Cfdi_UsoCFDI"]." - ".$rowCFDI["Descripcion"]),0,1,'R');
   }else{
      $pdf->Cell(20, 3,"",0,1,'R');
   }
   
   $pdf->setXY(110, 49);
   $pdf->Cell(70, 3,"Moneda:",0,0,'L');
   $resultCurrency = $objSat->getCurrency($rowVoucher["c_Moneda"]);
   if(!is_null($resultCurrency)){
      $rowCurrency = $resultCurrency->fetch_assoc();
   }

   if(!is_null($rowCurrency)){
      $pdf->Cell(20, 3,utf8_decode($rowVoucher["c_Moneda"]." - ".$rowCurrency["Descripcion"]),0,1,'R');
   }else{
      $pdf->Cell(20, 3,"",0,1,'R');
   }
   
   $pdf->setXY(110, 52);
   $pdf->Cell(70, 3,"Serie y Folio:",0,0,'L');
   $pdf->Cell(20, 3,$rowVoucher["Cfdi_Serie"]."-".$rowVoucher["Cfdi_Folio"],0,1,'R');

   if($rowVoucher["Nom_OrigenRecurso"] == "IM"){
      $pdf->setXY(10, $pdf->GetY()+15);
      $current_y = $pdf->GetY();
   }else{
      $pdf->setXY(10, $pdf->GetY()+12);
      $current_y = $pdf->GetY();
   }
   // $pdf->setXY(10, 69);
   // $pdf->SetFont('Arial','B',10);
   // if($rowVoucher["Nom_TipoNomina"] == "O"){
   //    $pdf->Cell(60, 3, utf8_decode("NÓMINA ORDINARIA: ".$rowVoucher["Rec_Nombre"]." ".$rowVoucher["Rec_Apellido_Paterno"]." ".$rowVoucher["Rec_Apellido_Materno"]),0,0,"L");
   // }else{
   //    $pdf->Cell(60, 3, utf8_decode("NÓMINA EXTRAORDINARIA: ".$rowVoucher["Rec_Nombre"]." ".$rowVoucher["Rec_Apellido_Paterno"]." ".$rowVoucher["Rec_Apellido_Materno"]),0,0,"L");
   // }

   $pdf->setXY(10, $pdf->GetY());
   $pdf->SetFont('Arial','',7);
   $pdf->Cell(75, 3,"RFC",0,0,'L');
   $pdf->Cell(20, 3,$rowVoucher["Rec_RFC"],0,1,'R');
   $pdf->setXY(10, $pdf->GetY());
   $pdf->Cell(75, 3,"Regimen Fiscal Receptor",0,0,'L');
   $resultRegimen = $objSat->getTaxRegime($rowVoucher["Rec_RegimenFiscalReceptor"]);
   if(!is_null($resultRegimen)){
      $rowRegimen = $resultRegimen->fetch_assoc();
   }
   
   //echo(strlen($rowRegimen["Descripcion"]>));
   if(!is_null($rowRegimen)){
      if(strlen($rowRegimen["Descripcion"]) > 44 ){
         $pdf->setXY(45, $pdf->GetY());
         $pdf->MultiCell(60, 3,utf8_decode($rowVoucher["Rec_RegimenFiscalReceptor"]." - ".$rowRegimen["Descripcion"]),0,'R',false);
      }else{
         $pdf->Cell(20, 3,utf8_decode($rowVoucher["Rec_RegimenFiscalReceptor"]." - ".$rowRegimen["Descripcion"]),0,1,'R');
      }
   }else{
      $pdf->Cell(20, 3, $rowVoucher["Rec_RegimenFiscalReceptor"] . "|",0,1,'R');
   }
   $current_y = $pdf->GetY();
   
   $pdf->setXY(10, $pdf->GetY());
   $pdf->Cell(75, 3,"CURP",0,0,'L');
   $pdf->Cell(20, 3,$rowVoucher["Rec_Curp"],0,1,'R');
   $pdf->setXY(10, $pdf->GetY());
   $pdf->Cell(75, 3,"Registro patronal",0,0,'L');
   $pdf->Cell(20, 3,$rowVoucher["Emi_RegistroPatronal"],0,1,'R');
   $pdf->setXY(10, $pdf->GetY());
   $pdf->Cell(75, 3,"RFC Patron Origen",0,0,'L');
   $pdf->Cell(20, 3,$rowVoucher["Emi_RFC"],0,1,'R');
   $pdf->setXY(10, $pdf->GetY());
   $pdf->Cell(75, 3,utf8_decode("Número de empleado"),0,0,'L');
   $pdf->Cell(20, 3,$rowVoucher["Rec_NumEmpleado"],0,1,'R');
   $pdf->setXY(10, $pdf->GetY());

   $pdf->Cell(75, 3,utf8_decode("Tipo de régimen"),0,0,'L');
   //$pdf->Cell(20, 3,$rowVoucher["Rec_TipoRegimen"],0,1,'R');
   $resultTypeRegime = $objSat->getTypeRegime($rowVoucher["Rec_TipoRegimen"]);
   if(!is_null($resultTypeRegime)){
      $rowTypeRegime = $resultTypeRegime->fetch_assoc();
   }

   if(!is_null($rowTypeRegime)){
      $pdf->Cell(20, 3,utf8_decode($rowVoucher["Rec_TipoRegimen"]." - ".$rowTypeRegime["Descripcion"]),0,1,'R');
   }else{
      $pdf->Cell(20, 3,"",0,1,'R');
   }

   $pdf->setXY(10, $pdf->GetY());
   $pdf->Cell(75, 3,"Departamento",0,0,'L');
   $pdf->Cell(20, 3,$rowVoucher["Rec_Departamento"],0,1,'R');
   $pdf->setXY(10, $pdf->GetY());
   $pdf->Cell(75, 3,"Puesto:",0,0,'L');
   $pdf->Cell(20, 3,$rowVoucher["Rec_Puesto"],0,1,'R');
   $pdf->setXY(10, $pdf->GetY());
   $pdf->Cell(75, 3,"Riesgo de puesto",0,0,'L');
   $resultJobRisk = $objSat->getJobRisk($rowVoucher["Rec_RiesgoPuesto"]);
   if(!is_null($resultJobRisk)){
      $rowJobRisk = $resultJobRisk->fetch_assoc();
   }

   if(!is_null($rowJobRisk)){
      $pdf->Cell(20, 3,$rowVoucher["Rec_RiesgoPuesto"]." - ".$rowJobRisk["Descripcion"],0,1,'R');
   }else{
      $pdf->Cell(20, 3,"",0,1,'R');
   }
   
   
   $pdf->setXY(10, $pdf->GetY());
   $pdf->Cell(75, 3,"Tipo de contrato",0,0,'L');
   $resultContract = $objSat->getTypeContract($rowVoucher["Rec_TipoContrato"]);
   if(!is_null($resultContract)){
      $rowContract = $resultContract->fetch_assoc();
   }

   if(!is_null($rowContract)){
      if(strlen($rowContract["Descripcion"]) > 44){
         $pdf->setXY(45, $pdf->GetY());
         $pdf->MultiCell(60, 3,utf8_decode($rowVoucher["Rec_TipoContrato"]." - ".$rowContract["Descripcion"]),0,'R',false);
      }else{
         $pdf->Cell(20, 3,utf8_decode($rowVoucher["Rec_TipoContrato"]." - ".$rowContract["Descripcion"]),0,1,'R');
      }      
   }else{
      $pdf->Cell(20, 3,"",0,1,'R');
   }

   $pdf->setXY(10, $pdf->GetY());
   $pdf->Cell(75, 3,"Tipo de jornada",0,0,'L');
   $resultWorking = $objSat->getWorkingDay($rowVoucher["Rec_TipoJornada"]);
   if(!is_null($resultWorking)){
      $rowWorking = $resultWorking->fetch_assoc();
   }

   if(!is_null($rowWorking)){
      $pdf->Cell(20, 3,utf8_decode($rowVoucher["Rec_TipoJornada"]." - ".$rowWorking["Descripcion"]),0,1,'R');
   }else{
      $pdf->Cell(20, 3,"",0,1,'R');
   }
   
   $pdf->setXY(10, $pdf->GetY());
   setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish');
   $pdf->Cell(75, 3,utf8_decode("Antigüedad"),0,0,'L');
   $fechainicial = new DateTime($rowVoucher["Rec_FechaInicioRelLaboral"]);
   $fechafinal = new DateTime($rowNomina["Nom_FechaFinalPago"]);
   $diferencia = $fechainicial->diff($fechafinal);
   $current_y = $current_y - 3;
   $pdf->Cell(20, 3,$diferencia->format('P%yY%mM%dD'),0,1,'R');
   $pdf->setXY(110, $current_y-9);
   $pdf->Cell(70, 3,"Fecha de pago",0,0,'L');
   $date = date('d-m-Y', strtotime($rowNomina["Nom_FechaPago"]));
   $pdf->Cell(20, 3,ucwords(strftime('%e %B, %Y',strtotime($date))),0,1,'R');
   $pdf->setXY(110, $current_y-6);
   $pdf->Cell(70, 3,"Fecha inicial de pago",0,0,'L');
   $date = date('d-m-Y', strtotime($rowNomina["Nom_FechaInicialPago"]));
   $pdf->Cell(20, 3,ucwords(strftime('%e %B, %Y',strtotime($date))),0,1,'R');
   $pdf->setXY(110, $current_y-3);
   $pdf->Cell(70, 3,"Fecha final de pago",0,0,'L');
   $date = date('d-m-Y', strtotime($rowNomina["Nom_FechaFinalPago"]));
   $pdf->Cell(20, 3,ucwords(strftime('%e %B, %Y',strtotime($date))),0,1,'R');
   $pdf->setXY(110, $current_y);
   $pdf->Cell(70, 3,utf8_decode("Inicio de la relación laboral"),0,0,'L');
   $date2 = date('d-m-Y', strtotime($rowVoucher["Rec_FechaInicioRelLaboral"]));
   $pdf->Cell(20, 3,ucwords(strftime('%e %B, %Y',strtotime($date2))),0,1,'R');
   $pdf->setXY(110, $current_y+3);
   $pdf->Cell(70, 3,"Periodicidad de pago",0,0,'L');
   $resultPeriodicity = $objSat->getPeriodicityPay($rowVoucher["Rec_PeriodicidadPago"]);
   if(!is_null($resultPeriodicity)){
      $rowPeriodicity = $resultPeriodicity->fetch_assoc();
   }

   if(!is_null($rowPeriodicity)){
      $pdf->Cell(20, 3,utf8_decode($rowVoucher["Rec_PeriodicidadPago"]." - ".$rowPeriodicity["Descripcion"]),0,1,'R');
   }else{
      $pdf->Cell(20, 3,"",0,1,'R');
   }
   
   $pdf->setXY(110, $current_y+6);
   $pdf->Cell(70, 3,utf8_decode("Número de días pagados"),0,0,'L');
   $pdf->Cell(20, 3,$rowNomina["Nom_NumDiasPagados"],0,1,'R');
   $pdf->setXY(110, $current_y+9);
   $pdf->Cell(70, 3,"Total Percepciones",0,0,'L');
   if($rowNomina["Nom_TotalPercepciones"] == ""){
      $pdf->Cell(20, 3,"",0,1,'R');
   }else{
      $pdf->Cell(20, 3,"$ ".number_format($rowNomina["Nom_TotalPercepciones"],2),0,1,'R');
   }
   
   $pdf->setXY(110, $current_y+12);

   $totalDeducciones = 0.00;
   for($x = 0 ; $x < count($arrayDeduction); $x++){
      $totalDeducciones += $arrayDeduction[$x]["Ddc_Importe"];
   }

   $totalOtros = 0.00;
   for($x = 0; $x < count($arrayOtherPyment); $x++){
      $totalOtros += $arrayOtherPyment[$x]["Otr_Importe"];
   }

   $pdf->Cell(70, 3,"Total Deducciones",0,0,'L');
   if($rowNomina["Nom_TotalDeducciones"] == ""){
      $pdf->Cell(20, 3,"",0,1,'R');
   }else{
      $pdf->Cell(20, 3,"$ ".number_format($rowNomina["Nom_TotalDeducciones"],2),0,1,'R');
   }
   
   $pdf->setXY(110, $current_y+15);
   $pdf->Cell(70, 3,"Total Otros Pagos",0,0,'L');

   if($rowNomina["Nom_TotalOtrosPagos"] == ""){
      $pdf->Cell(20, 3,"",0,1,'R');
   }else{
      $pdf->Cell(20, 3,"$ ".number_format($rowNomina["Nom_TotalOtrosPagos"],2),0,1,'R');
   }
   
   $pdf->setXY(110, $current_y+18);
   $pdf->Cell(70, 3,"SalarioDiarioIntegrado",0,0,'L');
   if($rowVoucher["Rec_SalarioDiarioIntegrado"] == ""){
      $pdf->Cell(20, 3,"",0,1,'R');
   }else{
      $pdf->Cell(20, 3,"$ ".number_format($rowVoucher["Rec_SalarioDiarioIntegrado"],2),0,1,'R');
   }
   
   $pdf->setXY(110, $current_y+21);
   $pdf->Cell(70, 3,"SalarioBaseCotApor",0,0,'L');
   if($rowVoucher["Rec_SalarioBaseCotApor"] == ""){
      $pdf->Cell(20, 3,"",0,1,'R');
   }else{
      $pdf->Cell(20, 3,"$ ".number_format($rowVoucher["Rec_SalarioBaseCotApor"]),0,1,'R');
   }
  
   $pdf->setXY(110, $current_y+24);
   $pdf->Cell(70, 3,"Clave Entidad Federativa",0,0,'L');
   $pdf->Cell(20, 3,$rowVoucher["Rec_ClaveEntFed"],0,1,'R');
   $pdf->setXY(110, $current_y+27);
   $pdf->Cell(70, 3,"NSS",0,0,'L');
   $pdf->Cell(20, 3,$rowVoucher["Rec_NumSeguridadSocial"],0,1,'R');
   $pdf->setXY(110, $current_y+30);
   $pdf->Cell(70, 3,"Banco",0,0,'L');
   
   $resultBank = $objSat->getBank($rowVoucher["Rec_Banco"]);
   if(!is_null($resultBank)){
      $rowBank = $resultBank->fetch_assoc();
   }

   if(!is_null($rowBank)){
      if(strlen($rowBank["Descripcion"]) > 34){
         $pdf->setXY(140, $current_y+30);
         $pdf->MultiCell(60, 3,utf8_decode($rowVoucher["Rec_Banco"]." - ".$rowBank["Descripcion"]),0,'R',false);
      }else{
         $pdf->Cell(20, 3,utf8_decode($rowVoucher["Rec_Banco"]." - ".$rowBank["Descripcion"]),0,1,'R');
      }      
   }else{
      $pdf->Cell(20, 3,"",0,1,'R');
   }


   $pdf->setXY(110, $pdf->GetY());
   $pdf->Cell(70, 3,"Cuenta Bancaria",0,0,'L');
   $pdf->Cell(20, 3,$rowVoucher["Rec_CuentaBancaria"],0,1,'R');

   $pdf->setXY(10, $current_y+40);
   $pdf->SetFont('Arial','B',8);
   $pdf->Cell(60, 3, "Percepciones:",0,0,"L");

   $pdf->setXY(10, $current_y+44);

   $pdf->SetFillColor(255, 255, 255);
   $pdf->SetFont('Arial','', 7);
   $pdf->Cell(10,4,"No.", "LTB",0,'L',true);
   $pdf->Cell(45,4,utf8_decode("Tipo de percepción"), "TB",0,'L',true);
   $pdf->Cell(10,4,"Clave", "TB",0,'L',true);
   $pdf->Cell(80,4, "Concepto" ,"TB",0,'L',true);
   $pdf->Cell(25,4,"Gravado" ,"TB",0,'R',true);
   $pdf->Cell(20,4,"Exento" ,"TBR",0,'R',true);

   $isMultiCellPerception = false;

   $lineStartPerception = $pdf->GetY()+4;
   for($x = 0; $x < count($arrayPerception); $x++){
      $resultPerception = $objSat->getPerception($arrayPerception[$x]["Prc_TipoPercepcion"]);
      if(!is_null($resultPerception)){
         $rowTypePerception = $resultPerception->fetch_assoc();
      }      
      $percepcionDescript = substr($rowTypePerception["Descripcion"], 0, 30); 
      if(strlen($arrayPerception[$x]["Prc_Concepto"]) >= 58){         
         $isMultiCellPerception = true;
         $current_y = $pdf->GetY();
//         if($x == 0){
            $pdf->setXY(10, $pdf->GetY()+5);
//         }else{
//            $pdf->setXY(10, $current_y+1);
//         }

         $pdf->Cell(10, 4,$x+1,0,0,'L');
         $pdf->Cell(45, 4,utf8_decode($arrayPerception[$x]["Prc_TipoPercepcion"]." - ".$percepcionDescript),0,0,'L');
         $pdf->Cell(10, 4,$arrayPerception[$x]["Prc_Clave"],0,0,'L');
         $pdf->Cell(105, 4,"$ ".number_format($arrayPerception[$x]["Prc_ImporteGravado"],2),0,0,'R');
         $pdf->Cell(20, 4,"$ ".number_format($arrayPerception[$x]["Prc_ImporteExento"],2),0,0,'R'); 
         $pdf->setXY(75, $pdf->GetY());
         $pdf->MultiCell(64, 4,utf8_decode($arrayPerception[$x]["Prc_Concepto"]),0,'L', false);
      }else{
         $current_y = $pdf->GetY();
         $pdf->setXY(10, $pdf->GetY()+4);
         $isMultiCellPerception = false;
         $pdf->Cell(10, 4,$x+1,0,0,'L');
         $pdf->Cell(45, 4,utf8_decode($arrayPerception[$x]["Prc_TipoPercepcion"]." - ".$percepcionDescript),0,0,'L');
         $pdf->Cell(10, 4,$arrayPerception[$x]["Prc_Clave"],0,0,'L');
         $pdf->Cell(80, 4,utf8_decode($arrayPerception[$x]["Prc_Concepto"]),0,0,'L');
         $pdf->Cell(25, 4,"$ ".number_format($arrayPerception[$x]["Prc_ImporteGravado"],2),0,0,'R');
         $pdf->Cell(20, 4,"$ ".number_format($arrayPerception[$x]["Prc_ImporteExento"],2),0,0,'R');
      }
   }

   $lineEndPerception = $pdf->GetY()+4;

   $pdf->Line(10, $lineStartPerception, 10, $lineEndPerception);	
   $pdf->Line(200, $lineStartPerception, 200, $lineEndPerception);

   if($isMultiCellPerception == true){
      $pdf->setXY(10, $pdf->GetY()+1); 
   }else{
      $pdf->setXY(10, $pdf->GetY()+4); 
   }

   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(120, 4,"Total Percepciones","LTB",0,'L');
   $pdf->Cell(25, 4,"Total Sueldos: $".number_format($rowPerception["TotalSueldos"]),"TB",0,'R');
   $pdf->Cell(25, 4,"$ ".number_format($rowPerception["TotalGravado"],2),"TB",0,'R');
   $pdf->Cell(20, 4,"$ ".number_format($rowPerception["TotalExento"],2),"RBT",0,'R');

   $pdf->setXY(10, $pdf->GetY()+5);
   $pdf->SetFont('Arial','B',8);
   $pdf->Cell(60, 3, "Deducciones:",0,0,"L");

   
   $pdf->setXY(10, $pdf->GetY()+4);
   $pdf->SetFillColor(255, 255, 255);
   $pdf->SetFont('Arial','', 7);
   $pdf->Cell(10,4,"No.", "LTB",0,'L',true);
   $pdf->Cell(45,4,utf8_decode("Tipo de deducción"), "TB",0,'L',true);
   $pdf->Cell(10,4,"Clave", "TB",0,'L',true);
   $pdf->Cell(50,4,"Concepto", "TB",0,'L',true);
   $pdf->Cell(32,4, "" ,"TB",0,'R',true);
   $pdf->Cell(15,4, "" ,"TB",0,'R',true);
   $pdf->Cell(28,4, "Importe" ,"TBR",0,'R',true);
   $lineStart = $pdf->GetY()+4;


   $isMultiCell = false;
   for($x = 0; $x < count($arrayDeduction); $x++){
      $resultDeduction = $objSat->getDeduction($arrayDeduction[$x]["Ddc_TipoDeduccion"]);
      if(!is_null($resultDeduction)){
         $rowTypeDeduction = $resultDeduction->fetch_assoc();
      }   
      $deductionDescript = substr($rowTypeDeduction["Descripcion"], 0, 30); 
      if(strlen($arrayDeduction[$x]["Ddc_Concepto"]) >= 58){
         $isMultiCell = true;
         $current_y = $pdf->GetY();
//         if($x == 0){
            $pdf->setXY(10, $pdf->GetY()+5);
//         }else{
  //          $pdf->setXY(10, $pdf->GetY()+1);
//         }
         $pdf->Cell(10, 4,$x+1,0,0,'L');
         $pdf->Cell(45,3,utf8_decode($arrayDeduction[$x]["Ddc_TipoDeduccion"]." - ".$deductionDescript),0,0,'L');
         $pdf->Cell(10,3,$arrayDeduction[$x]["Ddc_Clave"], 0,0,'L');
         $pdf->Cell(125,3,"$ ".number_format($arrayDeduction[$x]["Ddc_Importe"],2) ,0,0,'R');           
         $pdf->setXY(75, $pdf->GetY());
         $pdf->MultiCell(65,3,utf8_decode(strlen($arrayDeduction[$x]["Ddc_Concepto"])),0,'L', false);  
      }else{
         $isMultiCell = false;
         $current_y = $pdf->GetY();
         $pdf->setXY(10, $pdf->GetY()+4);
         $pdf->Cell(10, 4,$x+1,0,0,'L');
         $pdf->Cell(45,4,utf8_decode($arrayDeduction[$x]["Ddc_TipoDeduccion"]." - ".$deductionDescript), 0,0,'L');
         $pdf->Cell(10,4,$arrayDeduction[$x]["Ddc_Clave"], 0,0,'L');
         $pdf->Cell(50,4,utf8_decode($arrayDeduction[$x]["Ddc_Concepto"]), 0,0,'L');
         $pdf->Cell(75,4,"$ ".number_format($arrayDeduction[$x]["Ddc_Importe"],2) ,0,0,'R');   
      }
   }

   $lineEnd = $pdf->GetY()+4;
   $pdf->Line(10, $lineStart, 10, $lineEnd);	
   $pdf->Line(200, $lineStart, 200, $lineEnd);	
   if($isMultiCell == true){
      $pdf->setXY(10, $pdf->GetY()+1); 
   }else{
      $pdf->setXY(10, $pdf->GetY()+4); 
   }
   
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(75, 4,"Total Deducciones","LBT",0,'L');
   $pdf->Cell(64, 4,"Otras Deduc.: $".number_format($rowDeduction["Ded_TotalOtrasDeducciones"],2),"BT",0,'R');
   $pdf->Cell(33, 4,"Impuestos Ret.: $".number_format($rowDeduction["Ded_TotalImpuestosRetenidos"],2),"BT",0,'R');   
   $pdf->Cell(18, 4,"$ ".number_format($totalDeducciones, 2),"BTR",0,'R');


   
   $isMultiCellOtherPays = false;   
   if(count($arrayOtherPyment) > 0){
      $lineStartOtherPays = $pdf->GetY()+10;
      $pdf->setXY(10, $pdf->GetY()+5);
      $pdf->SetFont('Arial','B',8);
      $pdf->Cell(60, 4, "Otros Pagos:",0,0,"L");
      $pdf->setXY(10, $pdf->GetY()+4);
      $pdf->SetFillColor(255, 255, 255);
      $pdf->SetFont('Arial','', 7);
      $pdf->Cell(10,4,"No.", "LTB",0,'L',true);
      $pdf->Cell(45,4,"Tipo de otros pagos", "TB",0,'L',true);
      $pdf->Cell(10,4,"Clave", "TB",0,'L',true);
      $pdf->Cell(90,4, "Concepto" ,"TB",0,'L',true);
      $pdf->Cell(35,4,"Importe", "TRB",0,'R',true);
      for($x = 0; $x < count($arrayOtherPyment); $x++){
         $resultOtherPay = $objSat->getOtherPays($arrayOtherPyment[$x]["Otr_TipoOtroPago"]);
         if(!is_null($resultOtherPay)){
            $rowOtherPay = $resultOtherPay->fetch_assoc();
         }
         $otherPayDescript = substr($rowOtherPay["Descripcion"], 0, 30); 
         if(strlen($arrayOtherPyment[$x]["Otr_Concepto"]) >= 58){
            $isMultiCellOtherPays = true;
            $current_y = $pdf->GetY();
            //if($x == 0){
               $pdf->setXY(10, $current_y+5);
            //}else{
              // $pdf->setXY(10, $current_y+1); 
            //}            
            $pdf->Cell(10,3,$x+1, 0,0,'L');
            $pdf->Cell(45,3, $arrayOtherPyment[$x]["Otr_TipoOtroPago"]." - ".$otherPayDescript,0,0,'L');
            $pdf->Cell(30,3,$arrayOtherPyment[$x]["Otr_Clave"], 0,0,'L');
            $pdf->Cell(105,3,"$ ".number_format($arrayOtherPyment[$x]["Otr_Importe"],2), 0,0,'R');
            $pdf->setXY(75, $pdf->GetY());
            $pdf->MultiCell(65,3,utf8_decode($arrayOtherPyment[$x]["Otr_Concepto"]), 0,'L',false); 
         }else{
            $isMultiCellOtherPays = false;
            $current_y = $pdf->GetY();
            $pdf->setXY(10, $pdf->GetY()+4);
            $pdf->Cell(10,4,$x+1, 0,0,'L');
            $pdf->Cell(45,4,$arrayOtherPyment[$x]["Otr_TipoOtroPago"]." - ".$otherPayDescript ,0,0,'L');
            $pdf->Cell(10,4,$arrayOtherPyment[$x]["Otr_Clave"], 0,0,'L');
            $pdf->Cell(90,4, utf8_decode($arrayOtherPyment[$x]["Otr_Concepto"]) ,0,0,'L');
            $pdf->Cell(35,4,"$ ".number_format($arrayOtherPyment[$x]["Otr_Importe"],2), 0,0,'R');
         }
      }   
      if($isMultiCellOtherPays == true){
         $lineEndOtherPays = $pdf->GetY()+1;
      }else{
         $lineEndOtherPays = $pdf->GetY()+4;
      }        
      $pdf->Line(10, $lineStartOtherPays, 10, $lineEndOtherPays);	
      $pdf->Line(200, $lineStartOtherPays, 200, $lineEndOtherPays);
      $pdf->Line(10, $lineEndOtherPays, 200, $lineEndOtherPays);          
      
      $pdf->setXY(10, $lineEndOtherPays);
      $pdf->SetFont('Arial','B',7);
      $pdf->Cell(145, 4,"Total Otros Pagos","LB",0,'L');
      $pdf->Cell(45, 4,"$ ".number_format($totalOtros,2),"RB",0,'R');
   }

   $total = $rowPerception["TotalSueldos"]-$totalDeducciones;
   //echo($total);

   $totalLetter = num2letras(number_format($total,2,'.',''), false, true, 4);
   $pdf->SetFont('Arial','',9); 
   $current_y = $pdf->GetY();
   $pdf->setXY(10, $current_y+5);
   
   $pdf->Cell(60, 4, "IMPORTE CON LETRAS: ".$totalLetter,0,0,"L");

	$selloCFD = "";
    $selloSat = "";
    $selloNoCertificacion = "";

	
	$ruta = 'XML';
	if($rowVoucher["Mun_ID"] == 2){
		$ruta = 'XML_Rosarito';
	}
	
	$xmlFile = $ruta.'/'.$rowVoucher["Cfdi_Serie"].'/'.$rowVoucher["Cfdi_Serie"].'-'.$rowVoucher["Cfdi_Folio"].'-'.$rowVoucher["Cfdi_UUID"].'.xml';	
	if($rowVoucher["Cfdi_Status"]=='C'){		
		$xmlFile = $ruta.'/'.$rowVoucher["Cfdi_Serie"].'/'.$rowVoucher["Cfdi_Serie"].'-'.$rowVoucher["Cfdi_Folio"].'-'.$rowVoucher["Cfdi_UUID_Sustitucion"].'.xml';
	}		
	
	#echo "<br />ANTES IF EXISTE: ".$xmlFile;
	if(file_exists($xmlFile)){
	   #echo "<br />EXISTE: ".$xmlFile;
	  //$xml = new SimpleXMLElement(file_get_contents("fpdf/xmlFiles/".$rowVoucher["Cfdi_Serie"]."-".$rowVoucher["Cfdi_Folio"]."-".$rowVoucher["Cfdi_UUID"].".xml"));
	  $xml = new SimpleXMLElement(file_get_contents($xmlFile));
	  $ns = $xml->getNamespaces(true);
	  $xml->registerXPathNamespace('tfd',$ns['tfd']);
	  $children = $xml->xpath('//tfd:TimbreFiscalDigital');

	  foreach($children AS $child )
	  {
		 $selloCFD = $child['SelloCFD'];
		 $selloSat = $child['SelloSAT'];
		 $selloNoCertificacion = $child['NoCertificadoSAT'];
	  }
	}else{
	  $selloCFD = "Archivo XML no encontrado: ";
	  $selloSat = "";
	  $selloNoCertificacion = "";
	}

   $pdf->setXY(140, $current_y+5);
   $pdf->SetFillColor(255, 255, 255);
   $pdf->SetFont('Arial','', 10);
   $pdf->Cell(30,6,"Subtotal", "LTBR",0,'L');
   $pdf->Cell(30,6,"$ ".number_format($rowPerception["TotalSueldos"],2), "LTBR",0,'R');
   $pdf->setXY(140, $pdf->GetY()+6);
   $pdf->Cell(30,6,"Descuento", "LTBR",0,'L');
   $pdf->Cell(30,6,"$ ".number_format($totalDeducciones, 2), "LTBR",0,'R');
   $pdf->setXY(140, $pdf->GetY()+6);
   $pdf->Cell(30,6,"Total", "LBR",0,'L');
   $pdf->Cell(30,6,"$ ".number_format($total, 2), "LBR",0,'R');

   $img = 'fpdf/images/qrImages/'.$rowVoucher["Cfdi_UUID"].'.png';
   if($rowVoucher["Cfdi_Status"]=='C'){	
		$img = 'fpdf/images/qrImages/'.$rowVoucher["Cfdi_UUID_Sustitucion"].'.png';
	}
   if(file_exists($img)){
      $pdf->Image($img, 10, ( $current_y + 10), 30, 30);
   }else{
      $errorCorrectionLevel = 'L';
      $matrixPointSize = 4;
      $cbb_path = 'fpdf/images/qrImages/'.$rowVoucher["Cfdi_UUID"].'.png';
	  if($rowVoucher["Cfdi_Status"]=='C'){
			$cbb_path = 'fpdf/images/qrImages/'.$rowVoucher["Cfdi_UUID_Sustitucion"].'.png';
	  }
      #$data = '?re='.$rowVoucher["Emi_RFC"].'&rr='.$rowVoucher["Rec_RFC"].'&tt='.number_format($total,2,'.','').'&id='.$rowVoucher["Cfdi_UUID"];
      #QRcode::png($data, $cbb_path, $errorCorrectionLevel, $matrixPointSize, 2);
      #$pdf->Image('fpdf/images/qrImages/'.$rowVoucher["Cfdi_UUID"].'.png', 10, ($current_y+10), 30, 30);
		$total_amount = sprintf("%f", $total);//number_format($total,2,'.','')
		$total_amount = str_pad($total_amount, 17, "0", STR_PAD_LEFT);
		$total_amount = str_pad($total_amount, 6, "0",  STR_PAD_RIGHT);
		if($rowVoucher["Cfdi_Status"]=='C'){
			$data = 'https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?id='.$rowVoucher["Cfdi_UUID_Sustitucion"].'&re='.$rowVoucher["Emi_RFC"].'&rr='.$rowVoucher["Rec_RFC"].'&tt='.$total_amount.'&fe='.substr($selloCFD,-8);
		}else{
			$data = 'https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?id='.$rowVoucher["Cfdi_UUID"].'&re='.$rowVoucher["Emi_RFC"].'&rr='.$rowVoucher["Rec_RFC"].'&tt='.$total_amount.'&fe='.substr($selloCFD,-8);
		}
		#echo "<br />data: ".$data;
		QRcode::png($data, $cbb_path, $errorCorrectionLevel, $matrixPointSize, 2);
		$pdf->Image('fpdf/images/qrImages/'.$rowVoucher["Cfdi_UUID"].'.png', 10, ($current_y+10), 30, 30);	
		if($rowVoucher["Cfdi_Status"]=='C'){
			$pdf->Image('fpdf/images/qrImages/'.$rowVoucher["Cfdi_UUID_Sustitucion"].'.png', 10, ($current_y+10), 30, 30);
		}
      
   }

   $pdf->setXY(40, $current_y+12);
      $pdf->SetFont('Arial','B', 8);
      $pdf->SetTextColor(76, 140, 237);
      $pdf->Cell(30,4,"Folio Fiscal:", 0,0,'L');
   $pdf->setXY(40, $pdf->GetY()+4);
      $pdf->SetFont('Arial','', 8);
      $pdf->SetTextColor(0, 0, 0);
      $pdf->Cell(30,4,$rowVoucher["Cfdi_UUID"], 0,0,'L');
   $pdf->setXY(40, $pdf->GetY()+4);
      $pdf->SetFont('Arial','B', 8);
      $pdf->SetTextColor(76, 140, 237);
      $pdf->Cell(30,4,utf8_decode("Fecha y hora de certificación:"), 0,0,'L');
   $pdf->setXY(40, $pdf->GetY()+4);
      $pdf->SetFont('Arial','', 8);
      $pdf->SetTextColor(0, 0, 0);
      $pdf->Cell(30,4,$rowVoucher["Cfdi_Timestamp"], 0,0,'L');
   $pdf->setXY(40, $pdf->GetY()+4);
      $pdf->SetFont('Arial','B', 8);
      $pdf->SetTextColor(76, 140, 237);
      $pdf->Cell(30,4,utf8_decode("Lugar de expedición:"), 0,0,'L');
   $pdf->setXY(40, $pdf->GetY()+4);
      $pdf->SetFont('Arial','', 8);
      $pdf->SetTextColor(0, 0, 0);
      $pdf->Cell(30,4,$rowVoucher["c_CodigoPostal"], 0,0,'L');

   /*//$xmlFile = "fpdf/xmlFiles/".$rowVoucher["Cfdi_Serie"]."-".$rowVoucher["Cfdi_Folio"]."-".$rowVoucher["Cfdi_UUID"].".xml";
   $xmlFile = "XML/".$rowVoucher["Cfdi_Serie"]."/".$rowVoucher["Cfdi_Serie"]."-".$rowVoucher["Cfdi_Folio"]."-".$rowVoucher["Cfdi_UUID"].".xml";
   if($rowVoucher["Mun_ID"] == 2)
   {
      //$xmlFile = "../XML_Rosarito/".$rowVoucher["Cfdi_UUID"].".xml";
	  $xmlFile = "XML_Rosarito/".$rowVoucher["Cfdi_Serie"]."/".$rowVoucher["Cfdi_Serie"]."-".$rowVoucher["Cfdi_Folio"]."-".$rowVoucher["Cfdi_UUID"].".xml";
   }
   if(file_exists($xmlFile)){
      //$xml = new SimpleXMLElement(file_get_contents("fpdf/xmlFiles/".$rowVoucher["Cfdi_Serie"]."-".$rowVoucher["Cfdi_Folio"]."-".$rowVoucher["Cfdi_UUID"].".xml"));
      $xml = new SimpleXMLElement(file_get_contents($xmlFile));
      $ns = $xml->getNamespaces(true);
      $xml->registerXPathNamespace('tfd',$ns['tfd']);
      $children = $xml->xpath('//tfd:TimbreFiscalDigital');

      foreach($children AS $child )
      {
         $selloCFD = $child['SelloCFD'];
         $selloSat = $child['SelloSAT'];
         $selloNoCertificacion = $child['NoCertificadoSAT'];
      }
   }else{
      $selloCFD = "";
      $selloSat = "";
      $selloNoCertificacion = "";
   }*/

   $pdf->setXY(10, $pdf->GetY()+9);
   $pdf->SetFont('Arial','B', 6);
   $pdf->SetTextColor(76, 140, 237);
   $pdf->Cell(30,4,"Sello Digital del CFDI:", 0,0,'L');
   $pdf->setXY(10, $pdf->GetY()+4);
   $pdf->SetFont('Arial','', 5);
   $pdf->SetTextColor(0, 0, 0);
   $pdf->MultiCell(190,3, $selloCFD, 0,'L',false);

   $pdf->setXY(10, $pdf->GetY());
   $pdf->SetFont('Arial','B', 6);
   $pdf->SetTextColor(76, 140, 237);
   $pdf->Cell(30,4,"Sello del SAT:", 0,0,'L');

   $pdf->setXY(10, $pdf->GetY()+4);
   $pdf->SetFont('Arial','', 5);
   $pdf->SetTextColor(0, 0, 0);
   $pdf->MultiCell(190, 3 ,$selloSat , 0, 'L', false);

   $pdf->setXY(10, $pdf->GetY());
   $pdf->SetFont('Arial','B', 6);
   $pdf->SetTextColor(76, 140, 237);
   $pdf->Cell(30,4,utf8_decode("Cadena Original del complemento de certificación digital del SAT:"), 0,0,'L');
   $pdf->setXY(10, $pdf->GetY()+4);
   $pdf->SetFont('Arial','', 5);
   $pdf->SetTextColor(0, 0, 0);
   $pdf->MultiCell(190,3,$selloCFD, 0, 'L', false);


   $pdf->setXY(10, $pdf->GetY());
   $pdf->SetFont('Arial','B', 6);
   $pdf->SetTextColor(76, 140, 237);
   $pdf->Cell(30,4,"No. de Serie del Certificado del SAT:", 0,0,'L');
   $pdf->setXY(10, $pdf->GetY()+4);
   $pdf->SetFont('Arial','', 5);
   $pdf->SetTextColor(0, 0, 0);
   $pdf->Cell(200, 3, $selloNoCertificacion, 0, 'L', false);


   ob_end_clean();

   $pdf->Output('I', $rowVoucher["Cfdi_Serie"]."-".$rowVoucher["Cfdi_Folio"]."-".$rowVoucher["Cfdi_UUID"].".pdf");
}




