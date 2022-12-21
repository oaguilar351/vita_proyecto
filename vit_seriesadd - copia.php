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
$x_ser_SerieID = Null; 
$ox_ser_SerieID = Null;
$x_ser_Prefijo = Null; 
$ox_ser_Prefijo = Null;
$x_ser_Ejercicio = Null; 
$ox_ser_Ejercicio = Null;
$x_ser_Periodo = Null; 
$ox_ser_Periodo = Null;
$x_ser_Numero = Null; 
$ox_ser_Numero = Null;
$x_ser_Serie = Null; 
$ox_ser_Serie = Null;
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_ser_SerieID = @$_GET["ser_SerieID"];
if (empty($x_ser_SerieID)) {
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
	$x_ser_SerieID = @$_POST["n_ser_SerieID"];
	$x_ser_Prefijo = @$_POST["n_ser_Prefijo"];
	$x_ser_Ejercicio = @$_POST["n_ser_Ejercicio"];
	$x_ser_Periodo = @$_POST["n_ser_Periodo"];
	$x_ser_Numero = @$_POST["n_ser_Numero"];
	$x_ser_Serie = @$_POST["n_ser_Serie"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No hay registros";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: series_listado.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Nuevo registro agregado con exito.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: series_listado.php");
			exit();
		}else{
			ob_end_clean();
			header("Location: series_listado.php");
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
	global $x_ser_SerieID;
	$sSql = "SELECT * FROM `vit_series`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_ser_SerieID) : $x_ser_SerieID;
	$sWhere .= "(`ser_SerieID` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_ser_SerieID"] = $row["ser_SerieID"];
		$GLOBALS["x_ser_Prefijo"] = $row["ser_Prefijo"];
		$GLOBALS["x_ser_Ejercicio"] = $row["ser_Ejercicio"];
		$GLOBALS["x_ser_Periodo"] = $row["ser_Periodo"];
		$GLOBALS["x_ser_Numero"] = $row["ser_Numero"];
		$GLOBALS["x_ser_Serie"] = $row["ser_Serie"];
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
	global $x_ser_SerieID;
	$sSql = "SELECT * FROM `vit_series`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_ser_SerieID == "") || (is_null($x_ser_SerieID))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_ser_SerieID) : $x_ser_SerieID;			
		$sWhereChk .= "(`ser_SerieID` = " . addslashes($sTmp) . ")";
	}
	if ($bCheckKey) {
		$sSqlChk = $sSql . " WHERE " . $sWhereChk;
		$rsChk = phpmkr_query($sSqlChk, $conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSqlChk);
		if (phpmkr_num_rows($rsChk) > 0) {
			$_SESSION["ewmsg"] = "Duplicate value for primary key";
			phpmkr_free_result($rsChk);
			return false;
		}
		phpmkr_free_result($rsChk);
	}

	// Field ser_Prefijo
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_ser_Prefijo"]) : $GLOBALS["x_ser_Prefijo"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ser_Prefijo`"] = $theValue;

	// Field ser_Ejercicio
	$theValue = ($GLOBALS["x_ser_Ejercicio"] != "") ? intval($GLOBALS["x_ser_Ejercicio"]) : "NULL";
	$fieldList["`ser_Ejercicio`"] = $theValue;

	// Field ser_Periodo
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_ser_Periodo"]) : $GLOBALS["x_ser_Periodo"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ser_Periodo`"] = $theValue;

	// Field ser_Numero
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_ser_Numero"]) : $GLOBALS["x_ser_Numero"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ser_Numero`"] = $theValue;

	// Field ser_Serie
	$GLOBALS["x_ser_Serie"] = $GLOBALS["x_ser_Prefijo"]."".$GLOBALS["x_ser_Ejercicio"]."".$GLOBALS["x_ser_Periodo"]."".$GLOBALS["x_ser_Numero"];
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_ser_Serie"]) : $GLOBALS["x_ser_Serie"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`ser_Serie`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `vit_series` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
	return true;
}
?>
