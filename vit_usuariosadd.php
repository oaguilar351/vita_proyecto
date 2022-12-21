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
$x_Vit_Usuario = Null; 
$ox_Vit_Usuario = Null;
$x_Vit_Contrasena = Null; 
$ox_Vit_Contrasena = Null;
$x_Vit_Nombre = Null; 
$ox_Vit_Nombre = Null;
$x_Mun_ID = Null; 
$ox_Mun_ID = Null;
$x_Perfil_ID = Null; 
$ox_Perfil_ID = Null;
$x_Vit_Status = Null; 
$ox_Vit_Status = Null;
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_Vit_Usuario = @$_GET["Vit_Usuario"];
if (empty($x_Vit_Usuario)) {
	$bCopy = false;
}
$x_Mun_ID = @$_GET["Mun_ID"];
if (empty($x_Mun_ID)) {
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
	$x_Vit_Usuario = @$_POST["n_Vit_Usuario"];
	$x_Vit_Contrasena = @$_POST["n_Vit_Contrasena"];
	$x_Vit_Nombre = @$_POST["n_Vit_Nombre"];
	$x_Mun_ID = @$_POST["n_Mun_ID"];
	$x_Perfil_ID = @$_POST["n_Perfil_ID"];
	$x_Vit_Status = @$_POST["n_Vit_Status"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No hay registros";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: usuarios_listado.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Nuevo registro agregado con exito.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: usuarios_listado.php");
			exit();
		}else{
			ob_end_clean();
			header("Location: usuarios_listado.php");
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
	global $x_Vit_Usuario;
	global $x_Mun_ID;
	$sSql = "SELECT * FROM `vit_usuarios`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Vit_Usuario) : $x_Vit_Usuario;
	$sWhere .= "(`Vit_Usuario` = '" . addslashes($sTmp) . "')";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Mun_ID) : $x_Mun_ID;
	$sWhere .= "(`Mun_ID` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_Vit_Usuario"] = $row["Vit_Usuario"];
		$GLOBALS["x_Vit_Contrasena"] = $row["Vit_Contrasena"];
		$GLOBALS["x_Vit_Nombre"] = $row["Vit_Nombre"];
		$GLOBALS["x_Mun_ID"] = $row["Mun_ID"];
		$GLOBALS["x_Perfil_ID"] = $row["Perfil_ID"];
		$GLOBALS["x_Vit_Status"] = $row["Vit_Status"];
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
	global $x_Vit_Usuario;
	global $x_Mun_ID;
	$sSql = "SELECT * FROM `vit_usuarios`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_Vit_Usuario == "") || (is_null($x_Vit_Usuario))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Vit_Usuario) : $x_Vit_Usuario;			
		$sWhereChk .= "(`Vit_Usuario` = '" . addslashes($sTmp) . "')";
	}
	/*if ((@$x_Mun_ID == "") || (is_null($x_Mun_ID))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Mun_ID) : $x_Mun_ID;			
		$sWhereChk .= "(`Mun_ID` = " . addslashes($sTmp) . ")";
	}*/
	if ($bCheckKey) {
		$sSqlChk = $sSql . " WHERE " . $sWhereChk;
		$rsChk = phpmkr_query($sSqlChk, $conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSqlChk);
		if (phpmkr_num_rows($rsChk) > 0) {
			$_SESSION["ewmsg"] = "Valor duplicado para la llave primaria [Vit_Usuario] = " . $x_Vit_Usuario;
			phpmkr_free_result($rsChk);
			return false;
		}
		phpmkr_free_result($rsChk);
	}
	$sWhereChk = $sWhere;
	if (@$x_Vit_Usuario == "" || (is_null($x_Vit_Usuario))) { // Check field with unique index

		// Ignore
	}else{
		if ($sWhereChk <> "") { $sWhereChk .= " AND ";}
		$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Vit_Usuario) : $x_Vit_Usuario;		
		$sWhereChk .= "(`Vit_Usuario` = '" . addslashes($sTmp) . "')";
		$sSqlChk = $sSql . " WHERE " . $sWhereChk;
		$rsChk = phpmkr_query($sSqlChk, $conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSqlChk);
		if (phpmkr_num_rows($rsChk) > 0) {
			$_SESSION["ewmsg"] = "Duplicate value for index or primary key -- Vit_Usuario, value = " . $x_Vit_Usuario;
			phpmkr_free_result($rsChk);			
			return false;
		}
		phpmkr_free_result($rsChk);
	}

	// Field Vit_Usuario
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Vit_Usuario"]) : $GLOBALS["x_Vit_Usuario"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$sTmp = $theValue;
	$srchFld = $sTmp;
	$strsql = "SELECT * FROM `vit_usuarios` WHERE `Vit_Usuario` = " . $srchFld;
	$rschk = phpmkr_query($strsql,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $strsql);
	if (phpmkr_num_rows($rschk) > 0) {
		echo "Duplicate value for index or primary key -- Vit_Usuario, value = " . $sTmp . "<br>";
		echo "Press [Previous Page] key to continue!";
		die();
	}
	@phpmkr_free_result($rschk);
	$fieldList["`Vit_Usuario`"] = $theValue;

	// Field Vit_Contrasena
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Vit_Contrasena"]) : $GLOBALS["x_Vit_Contrasena"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`Vit_Contrasena`"] = $theValue;

	// Field Vit_Nombre
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Vit_Nombre"]) : $GLOBALS["x_Vit_Nombre"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`Vit_Nombre`"] = $theValue;

	// Field Mun_ID
	$theValue = ($GLOBALS["x_Mun_ID"] != "") ? intval($GLOBALS["x_Mun_ID"]) : "NULL";
	$fieldList["`Mun_ID`"] = $theValue;

	// Field Perfil_ID
	$theValue = ($GLOBALS["x_Perfil_ID"] != "") ? intval($GLOBALS["x_Perfil_ID"]) : "NULL";
	$fieldList["`Perfil_ID`"] = $theValue;

	// Field Vit_Status
	$theValue = ($GLOBALS["x_Vit_Status"] != "") ? intval($GLOBALS["x_Vit_Status"]) : "NULL";
	$fieldList["`Vit_Status`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `vit_usuarios` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
	return true;
}
?>
