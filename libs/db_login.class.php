<?php

class LoginMethods{

    function getPassUser($sUserId){
        $result = null;
        try{
            $con = new DatabaseConnection();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query="SELECT * FROM vit_usuarios WHERE Vit_Usuario = '" . $sUserId . "';";
				echo "<br /> query ".$query;
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
	
	function getUser($sUserId){
        $result = null;
        try{
            $con = new DatabaseConnection();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                $query="SELECT * FROM vit_usuarios WHERE Vit_Usuario = '" . $sUserId . "';";
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
	
	function setUser($fieldList, $sWhere){
        $result = false;
        try{
            $con = new DatabaseConnection();
            $lnk = $con->getConnection();
            if(!is_null($lnk)){
                /*$query="SELECT * FROM vit_usuarios WHERE Vit_Usuario = '" . $sUserId . "';";
                $result = $lnk->query($query);*/
				$sSql = "UPDATE `vit_usuarios` SET ";
				foreach ($fieldList as $key=>$temp) {
					$sSql .= "$key = $temp, ";
				}
				if (substr($sSql, -2) == ", ") {
					$sSql = substr($sSql, 0, strlen($sSql)-2);
				}
				$sSql .= " WHERE " . $sWhere;
				#phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
				$lnk->query($sSql);
				$result = true;
            }else{
                throw new Exception("Error Processing Request", 1);
            }
            $lnk ->close();
        }catch(Exception $E){
            echo($E->getMessage());
        }
        return $result;
    }
	
}
?>