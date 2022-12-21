<?php 

class SatCatalogos{
   // DatabaseConnectionCatalogos

    function getTaxRegime($code){
        $result = null;
        try{
            $con = new DatabaseConnectionCatalogos();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT Descripcion from sat_catalogos.c_RegimenFiscal where c_RegimenFiscal = '".$code."';";
                //echo($query);
                //echo($query);
                $result = $lnk->query($query);
            }else{
                throw new Exception("Error Processing Request", 1);
            }
            $lnk->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getPayMethod($code){
        $result = null;
        try{
            $con = new DatabaseConnectionCatalogos();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT Descripcion from sat_catalogos.c_MetodoPago where c_MetodoPago = '".$code."';";
                //echo($query);
                $result = $lnk->query($query);
            }else{
                throw new Exception("Error Processing Request", 1);
            }
            $lnk->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getPayMode($code){
        $result = null;
        try{
            $con = new DatabaseConnectionCatalogos();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT Descripcion from sat_catalogos.c_FormaPago where c_FormaPago = '".$code."';";
                $result = $lnk->query($query);
            }else{
                throw new Exception("Error Processing Request", 1);
            }
            $lnk->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getUsoCFDI($code){
        $result = null;
        try{
            $con = new DatabaseConnectionCatalogos();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT Descripcion from sat_catalogos.c_UsoCFDI where c_UsoCFDI = '".$code."';";
                $result = $lnk->query($query);
            }else{
                throw new Exception("Error Processing Request", 1);
            }
            $lnk->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getCurrency($code){
        $result = null;
        try{
            $con = new DatabaseConnectionCatalogos();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT Descripcion from sat_catalogos.c_Moneda where c_Moneda = '".$code."';";
                $result = $lnk->query($query);
            }else{
                throw new Exception("Error Processing Request", 1);
            }
            $lnk->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getTypeRegime($code){
        $result = null;
        try{
            $con = new DatabaseConnectionCatalogos();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT Descripcion from sat_catalogos.c_TipoRegimen where c_TipoRegimen = '".$code."';";
                $result = $lnk->query($query);
            }else{
                throw new Exception("Error Processing Request", 1);
            }
            $lnk->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getJobRisk($code){
        $result = null;
        try{
            $con = new DatabaseConnectionCatalogos();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT Descripcion from sat_catalogos.c_RiesgoPuesto where c_RiesgoPuesto = '".$code."';";
                $result = $lnk->query($query);
            }else{
                throw new Exception("Error Processing Request", 1);
            }
            $lnk->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getTypeContract($code){
        $result = null;
        try{
            $con = new DatabaseConnectionCatalogos();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT Descripcion from sat_catalogos.c_TipoContrato where c_TipoContrato = '".$code."';";
                $result = $lnk->query($query);
            }else{
                throw new Exception("Error Processing Request", 1);
            }
            $lnk->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getWorkingDay($code){
        $result = null;
        try{
            $con = new DatabaseConnectionCatalogos();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT Descripcion from sat_catalogos.c_TipoJornada where c_TipoJornada = '".$code."';";
                $result = $lnk->query($query);
            }else{
                throw new Exception("Error Processing Request", 1);
            }
            $lnk->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getPeriodicityPay($code){
        $result = null;
        try{
            $con = new DatabaseConnectionCatalogos();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT Descripcion from sat_catalogos.c_PeriodicidadPago where c_PeriodicidadPago = '".$code."';";
                $result = $lnk->query($query);
            }else{
                throw new Exception("Error Processing Request", 1);
            }
            $lnk->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getBank($code){
        $result = null;
        try{
            $con = new DatabaseConnectionCatalogos();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT Descripcion from sat_catalogos.c_Banco where c_Banco = '".$code."';";
                $result = $lnk->query($query);
            }else{
                throw new Exception("Error Processing Request", 1);
            }
            $lnk->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getPerception($code){
        $result = null;
        try{
            $con = new DatabaseConnectionCatalogos();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT Descripcion from sat_catalogos.c_TipoPercepcion where c_TipoPercepcion = '".$code."';";
                $result = $lnk->query($query);
            }else{
                throw new Exception("Error Processing Request", 1);
            }
            $lnk->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getDeduction($code){
        $result = null;
        try{
            $con = new DatabaseConnectionCatalogos();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT Descripcion from sat_catalogos.c_TipoDeduccion where c_TipoDeduccion = '".$code."';";
                $result = $lnk->query($query);
            }else{
                throw new Exception("Error Processing Request", 1);
            }
            $lnk->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }

    function getOtherPays($code){
        $result = null;
        try{
            $con = new DatabaseConnectionCatalogos();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query = "SELECT Descripcion from sat_catalogos.c_TipoOtroPago where c_TipoOtroPago = '".$code."';";
                $result = $lnk->query($query);
            }else{
                throw new Exception("Error Processing Request", 1);
            }
            $lnk->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }




}
?>