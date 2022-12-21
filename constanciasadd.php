<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php
if (@$_SESSION["project1_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
?>
<?php

// Initialize common variables
$x_const_ContanciaID = Null; 
$ox_const_ContanciaID = Null;
$x_const_RFC = Null; 
$ox_const_RFC = Null;
$x_const_CURP = Null; 
$ox_const_CURP = Null;
$x_const_Nombres = Null; 
$ox_const_Nombres = Null;
$x_const_Apellido1 = Null; 
$ox_const_Apellido1 = Null;
$x_const_Apellido2 = Null; 
$ox_const_Apellido2 = Null;
$x_const_InicioOperaciones = Null; 
$ox_const_InicioOperaciones = Null;
$x_const_EstatusPadron = Null; 
$ox_const_EstatusPadron = Null;
$x_const_UltimoCambio = Null; 
$ox_const_UltimoCambio = Null;
$x_const_NombreComercial = Null; 
$ox_const_NombreComercial = Null;
$x_const_CodigoPostal = Null; 
$ox_const_CodigoPostal = Null;
$x_const_TipoVialidad = Null; 
$ox_const_TipoVialidad = Null;
$x_const_NombreVialidad = Null; 
$ox_const_NombreVialidad = Null;
$x_const_NumExterior = Null; 
$ox_const_NumExterior = Null;
$x_const_NumInterior = Null; 
$ox_const_NumInterior = Null;
$x_const_Colonia = Null; 
$ox_const_Colonia = Null;
$x_const_Localidad = Null; 
$ox_const_Localidad = Null;
$x_const_Municipio = Null; 
$ox_const_Municipio = Null;
$x_const_Entidad = Null; 
$ox_const_Entidad = Null;
$x_const_EntreCalle = Null; 
$ox_const_EntreCalle = Null;
$x_const_YCalle = Null; 
$ox_const_YCalle = Null;
$x_const_Email = Null; 
$ox_const_Email = Null;
$x_const_TelefonoLada = Null; 
$ox_const_TelefonoLada = Null;
$x_const_TelefonoNum = Null; 
$ox_const_TelefonoNum = Null;
$x_const_EstadoDomicilio = Null; 
$ox_const_EstadoDomicilio = Null;
$x_const_EstadoContribuyente = Null; 
$ox_const_EstadoContribuyente = Null;
$x_const_Archivo = Null; 
$ox_const_Archivo = Null;
$fs_x_const_Archivo = 0;
$fn_x_const_Archivo = "";
$ct_x_const_Archivo = "";
$w_x_const_Archivo = 0;
$h_x_const_Archivo = 0;
$a_x_const_Archivo = "";
$x_const_Ruta = Null; 
$ox_const_Ruta = Null;
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_const_ContanciaID = @$_GET["const_ContanciaID"];
if (empty($x_const_ContanciaID)) {
	$bCopy = false;
}

// Get action
$sAction = @$_POST["a_add"];
if (($sAction == "") || ((is_null($sAction)))) {
	if ($bCopy) {
		$sAction = "C"; // Copy record
	}else{
		$sAction = "I"; // Display blank record
	}
}else{

	// Get fields from form
	$x_const_ContanciaID = @$_POST["x_const_ContanciaID"];
	$x_const_RFC = @$_POST["x_const_RFC"];
	$x_const_CURP = @$_POST["x_const_CURP"];
	$x_const_Nombres = @$_POST["x_const_Nombres"];
	$x_const_Apellido1 = @$_POST["x_const_Apellido1"];
	$x_const_Apellido2 = @$_POST["x_const_Apellido2"];
	$x_const_InicioOperaciones = @$_POST["x_const_InicioOperaciones"];
	$x_const_EstatusPadron = @$_POST["x_const_EstatusPadron"];
	$x_const_UltimoCambio = @$_POST["x_const_UltimoCambio"];
	$x_const_NombreComercial = @$_POST["x_const_NombreComercial"];
	$x_const_CodigoPostal = @$_POST["x_const_CodigoPostal"];
	$x_const_TipoVialidad = @$_POST["x_const_TipoVialidad"];
	$x_const_NombreVialidad = @$_POST["x_const_NombreVialidad"];
	$x_const_NumExterior = @$_POST["x_const_NumExterior"];
	$x_const_NumInterior = @$_POST["x_const_NumInterior"];
	$x_const_Colonia = @$_POST["x_const_Colonia"];
	$x_const_Localidad = @$_POST["x_const_Localidad"];
	$x_const_Municipio = @$_POST["x_const_Municipio"];
	$x_const_Entidad = @$_POST["x_const_Entidad"];
	$x_const_EntreCalle = @$_POST["x_const_EntreCalle"];
	$x_const_YCalle = @$_POST["x_const_YCalle"];
	$x_const_Email = @$_POST["x_const_Email"];
	$x_const_TelefonoLada = @$_POST["x_const_TelefonoLada"];
	$x_const_TelefonoNum = @$_POST["x_const_TelefonoNum"];
	$x_const_EstadoDomicilio = @$_POST["x_const_EstadoDomicilio"];
	$x_const_EstadoContribuyente = @$_POST["x_const_EstadoContribuyente"];
	$x_const_Archivo = @$_POST["x_const_Archivo"];
	$x_const_Ruta = @$_POST["x_const_Ruta"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No hay registros";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: constancias_listado.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Nuevo Registro Agregado Exitosamente";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: constancias_listado.php");
			exit();
		}
		break;
}
?>
<?php
phpmkr_db_close($conn);
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($conn)
{
	global $x_const_ContanciaID;
	$sSql = "SELECT * FROM `constancias`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_const_ContanciaID) : $x_const_ContanciaID;
	$sWhere .= "(`const_ContanciaID` = " . addslashes($sTmp) . ")";
	$sSql .= " WHERE " . $sWhere;
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	if ($sOrderBy <> "") {
		$sSql .= " ORDER BY " . $sOrderBy;
	}
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$bLoadData = false;
	}else{
		$bLoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		$GLOBALS["x_const_ContanciaID"] = $row["const_ContanciaID"];
		$GLOBALS["x_const_RFC"] = $row["const_RFC"];
		$GLOBALS["x_const_CURP"] = $row["const_CURP"];
		$GLOBALS["x_const_Nombres"] = $row["const_Nombres"];
		$GLOBALS["x_const_Apellido1"] = $row["const_Apellido1"];
		$GLOBALS["x_const_Apellido2"] = $row["const_Apellido2"];
		$GLOBALS["x_const_InicioOperaciones"] = $row["const_InicioOperaciones"];
		$GLOBALS["x_const_EstatusPadron"] = $row["const_EstatusPadron"];
		$GLOBALS["x_const_UltimoCambio"] = $row["const_UltimoCambio"];
		$GLOBALS["x_const_NombreComercial"] = $row["const_NombreComercial"];
		$GLOBALS["x_const_CodigoPostal"] = $row["const_CodigoPostal"];
		$GLOBALS["x_const_TipoVialidad"] = $row["const_TipoVialidad"];
		$GLOBALS["x_const_NombreVialidad"] = $row["const_NombreVialidad"];
		$GLOBALS["x_const_NumExterior"] = $row["const_NumExterior"];
		$GLOBALS["x_const_NumInterior"] = $row["const_NumInterior"];
		$GLOBALS["x_const_Colonia"] = $row["const_Colonia"];
		$GLOBALS["x_const_Localidad"] = $row["const_Localidad"];
		$GLOBALS["x_const_Municipio"] = $row["const_Municipio"];
		$GLOBALS["x_const_Entidad"] = $row["const_Entidad"];
		$GLOBALS["x_const_EntreCalle"] = $row["const_EntreCalle"];
		$GLOBALS["x_const_YCalle"] = $row["const_YCalle"];
		$GLOBALS["x_const_Email"] = $row["const_Email"];
		$GLOBALS["x_const_TelefonoLada"] = $row["const_TelefonoLada"];
		$GLOBALS["x_const_TelefonoNum"] = $row["const_TelefonoNum"];
		$GLOBALS["x_const_EstadoDomicilio"] = $row["const_EstadoDomicilio"];
		$GLOBALS["x_const_EstadoContribuyente"] = $row["const_EstadoContribuyente"];
		$GLOBALS["x_const_Archivo"] = $row["const_Archivo"];
		$GLOBALS["x_const_Ruta"] = $row["const_Ruta"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function AddData
// - Add Data
// - Variables used: field variables

function AddData($conn)
{
	global $x_const_ContanciaID;
	$sSql = "SELECT * FROM `constancias`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	$dato_ruta = explode("_", $GLOBALS["x_const_Ruta"]);
	$x_const_RFC = $dato_ruta[1];
	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_const_RFC == "") || (is_null($x_const_RFC))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_const_RFC) : $x_const_RFC;			
		$sWhereChk .= "(`const_RFC` = '" . addslashes($sTmp) . "')";
	}
	if ($bCheckKey) {
		$sSqlChk = $sSql . " WHERE " . $sWhereChk;
		echo "<br />sSqlChk: ".$sSqlChk;
		$rsChk = phpmkr_query($sSqlChk, $conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSqlChk);
		if (phpmkr_num_rows($rsChk) > 0) {
			$_SESSION["ewmsg"] = "RFC de la contancia ya se encuantra registrado.";
			phpmkr_free_result($rsChk);
			//return false;
			header("Location: constancias_listado.php");
			exit();
		}
		phpmkr_free_result($rsChk);
	}
	if (!empty($_FILES["x_const_Archivo"]["size"])) {
		if (!empty($EW_MaxFileSize) && $_FILES["x_const_Archivo"]["size"] > $EW_MaxFileSize) {
			die("Max. file upload size exceeded");
		}
	}

	// Field const_Ruta
		
	
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_Ruta"]) : $GLOBALS["x_const_Ruta"];	
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`const_Ruta`"] = $theValue;
	
	
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_RFC"]) : $GLOBALS["x_const_RFC"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`const_RFC`"] = $theValue;
	
	$ruta = $GLOBALS["x_const_Ruta"];
	$texto = file_get_contents($ruta);
	$texto = preg_replace('<\<td role=\"gridcell\" style=\"text-align:left;\"\>>', "*", $texto);
	$texto = preg_replace('<\<span style=\"font-weight: bold;\"\>>', "|", $texto);
	//$texto = remover_javascript($texto);
	$javascript = "/<script[^>]*?>.*?<\/script>/si";  //Expresión regular buscará todos los códigos Javascripts 
	$texto = preg_replace($javascript, "", $texto);
	$javascript = "/<script[^>]*?javascript{1}[^>]*?>.*?<\/script>/si";
	$texto = preg_replace($javascript, "", $texto); //Expresión regular buscará todos los códigos Javascripts 
	//
	$texto = strip_tags($texto);
	$parts = explode('|', $texto);
	for($i=1;$i<COUNT($parts);$i++){
	   #echo "<br/ >".$i.": ".$parts[$i];
	   $parts2 = explode(':*', $parts[$i]);
	   #echo "<br/ >".$i.": ".$parts2[1];
	   if($i==1){
			$GLOBALS["x_const_CURP"] = $parts2[1];
			$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_CURP"]) : $GLOBALS["x_const_CURP"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`const_CURP`"] = $theValue;
		}
		if($i==2){
			$GLOBALS["x_const_Nombres"] = $parts2[1];
			$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_Nombres"]) : $GLOBALS["x_const_Nombres"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`const_Nombres`"] = $theValue;
		}		
		if($i==3){
			$GLOBALS["x_const_Apellido1"] = $parts2[1];
			$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_Apellido1"]) : $GLOBALS["x_const_Apellido1"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`const_Apellido1`"] = $theValue;
		}
		if($i==4){
			$GLOBALS["x_const_Apellido2"] = $parts2[1];
			$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_Apellido2"]) : $GLOBALS["x_const_Apellido2"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`const_Apellido2`"] = $theValue;
		}
		if($i==6){
			$GLOBALS["x_const_InicioOperaciones"] = $parts2[1];
			$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_InicioOperaciones"]) : $GLOBALS["x_const_InicioOperaciones"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`const_InicioOperaciones`"] = $theValue;
		}
		if($i==7){
			$GLOBALS["x_const_EstatusPadron"] = $parts2[1];
			$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_EstatusPadron"]) : $GLOBALS["x_const_EstatusPadron"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`const_EstatusPadron`"] = $theValue;
		}
		if($i==8){
			$GLOBALS["x_const_UltimoCambio"] = $parts2[1];
			$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_UltimoCambio"]) : $GLOBALS["x_const_UltimoCambio"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`const_UltimoCambio`"] = $theValue;
		}
		if($i==9){
			$GLOBALS["x_const_Entidad"] = $parts2[1];
			$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_Entidad"]) : $GLOBALS["x_const_Entidad"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`const_Entidad`"] = $theValue;
		}
		if($i==10){
			$GLOBALS["x_const_Municipio"] = $parts2[1];
			$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_Municipio"]) : $GLOBALS["x_const_Municipio"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`const_Municipio`"] = $theValue;
		}
		if($i==11){
			$GLOBALS["x_const_Colonia"] = $parts2[1];
			$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_Colonia"]) : $GLOBALS["x_const_Colonia"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`const_Colonia`"] = $theValue;
		}
		if($i==12){
			$GLOBALS["x_const_TipoVialidad"] = $parts2[1];
			$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_TipoVialidad"]) : $GLOBALS["x_const_TipoVialidad"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`const_TipoVialidad`"] = $theValue;
		}
		if($i==13){
			$GLOBALS["x_const_NombreVialidad"] = $parts2[1];
			$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_NombreVialidad"]) : $GLOBALS["x_const_NombreVialidad"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`const_NombreVialidad`"] = $theValue;
		}
		if($i==14){
			$GLOBALS["x_const_NumExterior"] = $parts2[1];
			$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_NumExterior"]) : $GLOBALS["x_const_NumExterior"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`const_NumExterior`"] = $theValue;
		}
		if($i==15){
			$GLOBALS["x_const_NumInterior"] =$parts2[1];
			$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_NumInterior"]) : $GLOBALS["x_const_NumInterior"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`const_NumInterior`"] = $theValue;
		}
		if($i==16){
			$GLOBALS["x_const_CodigoPostal"] = $parts2[1];
			$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_CodigoPostal"]) : $GLOBALS["x_const_CodigoPostal"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`const_CodigoPostal`"] = $theValue;
		}
		if($i==17){
			$GLOBALS["x_const_Email"] = $parts2[1];
			$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_Email"]) : $GLOBALS["x_const_Email"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`const_Email`"] = $theValue;
		}
		if($i==19){
			$GLOBALS["x_const_EstadoContribuyente"] = $parts2[1];
			$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_EstadoContribuyente"]) : $GLOBALS["x_const_EstadoContribuyente"]; 
			$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
			$fieldList["`const_EstadoContribuyente`"] = $theValue;
		}
	}
	
	$GLOBALS["x_const_NombreComercial"] = $GLOBALS["x_const_Nombres"]." ".$GLOBALS["x_const_Apellido1"]." ".$GLOBALS["x_const_Apellido2"];
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_const_NombreComercial"]) : $GLOBALS["x_const_NombreComercial"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`const_NombreComercial`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `constancias` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	echo "<br />sSql: ".$sSql;
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
	return true;
}
?>
