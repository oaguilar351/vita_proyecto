<?php

class PdfMethods{

    function getMunIDToPicture($id){
        $result = null;
        try{
            $con = new DatabaseConnection();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query="SELECT Mun_ID from Vit_Comprobantes where Cfdi_ID = ".$id.";";
                $result = $lnk->query($query);
            }else{
                throw new Exception("Error Processing Request", 1);
            }
            $lnk ->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getGeneralData($id){
        $result = null;
        try{
            $con = new DatabaseConnection();
            $lnk = $con->getConnection();

            if(!is_null($lnk)){
                $query="SELECT 
                            a.Cfdi_Fecha,
                            a.Cfdi_NoCertificado,
                            a.c_TipoDeComprobante,
                            a.Cfdi_Version,
                            a.c_MetodoPago,
                            a.c_FormaPago,
                            a.Cfdi_UsoCFDI,
                            a.c_Moneda,
                            a.Cfdi_Serie,
                            a.Cfdi_Folio,
                            a.Cfdi_Sello,
                            a.Cfdi_Certificado,
                            a.c_CodigoPostal,
                            a.Cfdi_UUID,
                            a.Cfdi_Timestamp,
                            a.Mun_ID,
                            b.Rec_Nombre,
                            b.Rec_Apellido_Paterno,
                            b.Rec_Apellido_Materno,
                            b.Rec_RFC,
                            b.Rec_Curp,
                            b.Rec_NumEmpleado,
                            b.Rec_TipoRegimen,
                            b.Rec_Departamento,
                            b.Rec_Puesto,
                            b.Rec_RiesgoPuesto,
                            b.Rec_TipoContrato,
                            b.Rec_TipoJornada,
                            b.Rec_Antiguedad,
                            b.Rec_FechaInicioRelLaboral,
                            b.Rec_PeriodicidadPago,
                            b.Rec_SalarioDiarioIntegrado,
                            b.Rec_SalarioBaseCotApor,
                            b.Rec_ClaveEntFed,
                            b.Rec_NumSeguridadSocial,
                            b.Rec_Banco,
                            b.Rec_CuentaBancaria,
                            c.Emi_Nombre,
                            c.Emi_RFC,
                            c.Emi_RegistroPatronal,
                            c.Emi_RfcPatronOrigen
                            from Vit_Comprobantes a, Vit_Receptor b, Vit_Emisor c
                            where Cfdi_ID = ".$id." and a.Rec_RFC = b.Rec_RFC and a.Emi_RFC = c.Emi_RFC;";
                $result = $lnk->query($query);
            }else{
                throw new Exception("Error Processing Request", 1);
            }
            $lnk ->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getNominaData($id){
        $result = null;
        try{
            $con = new DatabaseConnection();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT 
                Nom_FechaPago,
                Nom_FechaInicialPago,
                Nom_FechaFinalPago,
                Nom_NumDiasPagados,
                Nom_TotalPercepciones,
                Nom_TotalDeducciones,
                Nom_TotalOtrosPagos
            FROM
                Vit_Nominas
            WHERE
                Cfdi_ID = ".$id.";";
                $result = $lnk->query($query);
            }
            $lnk ->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getAllDeductions($id){
        $result = null;
        try{
            $con = new DatabaseConnection();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT Ddc_TipoDeduccion, Ddc_Clave, Ddc_Concepto, Ddc_Importe FROM Vit_Deduccion where Cfdi_ID = ".$id.";";
                $result = $lnk->query($query);
            }
            $lnk ->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getDeduction($id){
        $result = null;
        try{
            $con = new DatabaseConnection();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT Ded_TotalOtrasDeducciones, Ded_TotalImpuestosRetenidos FROM Vit_Deducciones where Cfdi_ID = ".$id.";";
                $result = $lnk->query($query);
            }
            $lnk ->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getAllPerceptions($id){
        $result = null;
        try{
            $con = new DatabaseConnection();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT Prc_TipoPercepcion, Prc_Clave, Prc_Concepto, Prc_ImporteGravado, Prc_ImporteExento FROM Vit_Percepcion where Cfdi_ID = ".$id.";";
                $result = $lnk->query($query);
            }
            $lnk ->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getPerception($id){
        $result = null;
        try{
            $con = new DatabaseConnection();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT TotalSueldos, TotalGravado, TotalExento FROM Vit_Percepciones where Cfdi_ID = ".$id.";";
                $result = $lnk->query($query);
            }
            $lnk ->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getOtherPayment($id){
        $result = null;
        try{
            $con = new DatabaseConnection();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT Otr_TipoOtroPago, Otr_Clave, Otr_Concepto, Otr_Importe FROM vit_otropago where Cfdi_ID = ".$id.";";
                $result = $lnk->query($query);
            }
            $lnk ->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }
}
?>