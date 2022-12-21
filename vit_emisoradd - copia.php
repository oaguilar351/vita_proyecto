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
$x_Emi_RFC = Null; 
$ox_Emi_RFC = Null;
$x_Emi_Nombre = Null; 
$ox_Emi_Nombre = Null;
$x_Emi_RegimenFiscal = Null; 
$ox_Emi_RegimenFiscal = Null;
$x_Emi_Clave = Null; 
$ox_Emi_Clave = Null;
$x_Emi_FacAtrAdquirente = Null; 
$ox_Emi_FacAtrAdquirente = Null;
$x_Emi_Curp = Null; 
$ox_Emi_Curp = Null;
$x_Emi_RegistroPatronal = Null; 
$ox_Emi_RegistroPatronal = Null;
$x_Emi_RfcPatronOrigen = Null; 
$ox_Emi_RfcPatronOrigen = Null;
$x_Emi_EntidadSNCF = Null; 
$ox_Emi_EntidadSNCF = Null;
$x_Mun_ID = Null; 
$ox_Mun_ID = Null;
$x_Emi_NomCorto = Null; 
$ox_Emi_NomCorto = Null;
$x_Emi_ArchivoKey = Null; 
$ox_Emi_ArchivoKey = Null;
$fs_x_Emi_ArchivoKey = 0;
$fn_x_Emi_ArchivoKey = "";
$ct_x_Emi_ArchivoKey = "";
$w_x_Emi_ArchivoKey = 0;
$h_x_Emi_ArchivoKey = 0;
$a_x_Emi_ArchivoKey = "";
$x_Emi_ArchivoCer = Null; 
$ox_Emi_ArchivoCer = Null;
$fs_x_Emi_ArchivoCer = 0;
$fn_x_Emi_ArchivoCer = "";
$ct_x_Emi_ArchivoCer = "";
$w_x_Emi_ArchivoCer = 0;
$h_x_Emi_ArchivoCer = 0;
$a_x_Emi_ArchivoCer = "";
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_Emi_RFC = @$_GET["Emi_RFC"];
if (empty($x_Emi_RFC)) {
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
	$x_Emi_RFC = @$_POST["n_Emi_RFC"];
	$x_Emi_Nombre = @$_POST["n_Emi_Nombre"];
	$x_Emi_RegimenFiscal = @$_POST["n_Emi_RegimenFiscal"];
	$x_Emi_Clave = @$_POST["n_Emi_Clave"];
	$x_Emi_FacAtrAdquirente = @$_POST["n_Emi_FacAtrAdquirente"];
	$x_Emi_Curp = @$_POST["n_Emi_Curp"];
	$x_Emi_RegistroPatronal = @$_POST["n_Emi_RegistroPatronal"];
	$x_Emi_RfcPatronOrigen = @$_POST["n_Emi_RfcPatronOrigen"];
	$x_Emi_EntidadSNCF = @$_POST["n_Emi_EntidadSNCF"];
	$x_Mun_ID = @$_POST["n_Mun_ID"];
	$x_Emi_NomCorto = @$_POST["n_Emi_NomCorto"];
	$x_Emi_ArchivoKey = @$_POST["n_Emi_ArchivoKey"];
	$x_Emi_ArchivoCer = @$_POST["n_Emi_ArchivoCer"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key			
			$msg = "warning|No hay registro.|3000";
			$_SESSION["ewmsg"] = $msg;
			#$_SESSION["ewmsg"] = "No hay registro";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: emisores_listado.php");
			exit();
		}
		break;
	case "A": // Add
		#echo "<br \>CASO AGREGAR";
		if (AddData($conn)) { // Add New Record			
			#$msg = "Nuevo registro agregado con exito.";
			$msg = "success|Nuevo registro agregado con exito.|3000";
			$_SESSION["ewmsg"] = $msg;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: emisores_listado.php");
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
	global $x_Emi_RFC;
	global $x_Mun_ID;
	$sSql = "SELECT * FROM `vit_emisor`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Emi_RFC) : $x_Emi_RFC;
	$sWhere .= "(`Emi_RFC` = '" . addslashes($sTmp) . "')";
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
		$GLOBALS["x_Emi_RFC"] = $row["Emi_RFC"];
		$GLOBALS["x_Emi_Nombre"] = $row["Emi_Nombre"];
		$GLOBALS["x_Emi_RegimenFiscal"] = $row["Emi_RegimenFiscal"];
		$GLOBALS["x_Emi_Clave"] = $row["Emi_Clave"];
		$GLOBALS["x_Emi_FacAtrAdquirente"] = $row["Emi_FacAtrAdquirente"];
		$GLOBALS["x_Emi_Curp"] = $row["Emi_Curp"];
		$GLOBALS["x_Emi_RegistroPatronal"] = $row["Emi_RegistroPatronal"];
		$GLOBALS["x_Emi_RfcPatronOrigen"] = $row["Emi_RfcPatronOrigen"];
		$GLOBALS["x_Emi_EntidadSNCF"] = $row["Emi_EntidadSNCF"];
		$GLOBALS["x_Mun_ID"] = $row["Mun_ID"];
		$GLOBALS["x_Emi_NomCorto"] = $row["Emi_NomCorto"];
		$GLOBALS["x_Emi_ArchivoKey"] = $row["Emi_ArchivoKey"];
		$GLOBALS["x_Emi_ArchivoCer"] = $row["Emi_ArchivoCer"];
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
	global $x_Emi_RFC;
	global $x_Mun_ID;
	$sSql = "SELECT * FROM `vit_emisor`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_Emi_RFC == "") || (is_null($x_Emi_RFC))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Emi_RFC) : $x_Emi_RFC;			
		$sWhereChk .= "(`Emi_RFC` = '" . addslashes($sTmp) . "')";
	}
	if ((@$x_Mun_ID == "") || (is_null($x_Mun_ID))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " OR "; }
		$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Mun_ID) : $x_Mun_ID;			
		$sWhereChk .= "(`Mun_ID` = " . addslashes($sTmp) . ")";
	}
	if ($bCheckKey) {
		$sSqlChk = $sSql . " WHERE " . $sWhereChk;
		$rsChk = phpmkr_query($sSqlChk, $conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSqlChk);
		if (phpmkr_num_rows($rsChk) > 0) {
			$msg = "danger|RFC [".$x_Emi_RFC."] se encuentra duplicado|5000";
			#$msg = "RFC [".$x_Emi_RFC."] se encuentra duplicado.";
			$_SESSION["ewmsg"] = $msg;
			phpmkr_free_result($rsChk);
			//return false;
			header("Location: emisores_listado.php");
			exit();
		}
		phpmkr_free_result($rsChk);
	}

		// check file size
		$EW_MaxFileSize = @$_POST["EW_Max_File_Size"];
	if (!empty($_FILES["x_Emi_ArchivoKey"]["size"])) {
		if (!empty($EW_MaxFileSize) && $_FILES["x_Emi_ArchivoKey"]["size"] > $EW_MaxFileSize) {
			die("Max. file upload size exceeded");
		}
	}
	if (!empty($_FILES["x_Emi_ArchivoCer"]["size"])) {
		if (!empty($EW_MaxFileSize) && $_FILES["x_Emi_ArchivoCer"]["size"] > $EW_MaxFileSize) {
			die("Max. file upload size exceeded");
		}
	}

	// Field Emi_RFC
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_RFC"]) : $GLOBALS["x_Emi_RFC"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`Emi_RFC`"] = $theValue;

	// Field Emi_Nombre
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_Nombre"]) : $GLOBALS["x_Emi_Nombre"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`Emi_Nombre`"] = $theValue;

	// Field Emi_RegimenFiscal
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_RegimenFiscal"]) : $GLOBALS["x_Emi_RegimenFiscal"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`Emi_RegimenFiscal`"] = $theValue;

	// Field Emi_Clave
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_Clave"]) : $GLOBALS["x_Emi_Clave"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`Emi_Clave`"] = $theValue;

	// Field Emi_FacAtrAdquirente
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_FacAtrAdquirente"]) : $GLOBALS["x_Emi_FacAtrAdquirente"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`Emi_FacAtrAdquirente`"] = $theValue;

	// Field Emi_Curp
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_Curp"]) : $GLOBALS["x_Emi_Curp"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`Emi_Curp`"] = $theValue;

	// Field Emi_RegistroPatronal
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_RegistroPatronal"]) : $GLOBALS["x_Emi_RegistroPatronal"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`Emi_RegistroPatronal`"] = $theValue;

	// Field Emi_RfcPatronOrigen
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_RfcPatronOrigen"]) : $GLOBALS["x_Emi_RfcPatronOrigen"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`Emi_RfcPatronOrigen`"] = $theValue;

	// Field Emi_EntidadSNCF
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_EntidadSNCF"]) : $GLOBALS["x_Emi_EntidadSNCF"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`Emi_EntidadSNCF`"] = $theValue;

	// Field Mun_ID
	$theValue = ($GLOBALS["x_Mun_ID"] != "") ? intval($GLOBALS["x_Mun_ID"]) : "NULL";
	$fieldList["`Mun_ID`"] = $theValue;

	// Field Emi_NomCorto
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_NomCorto"]) : $GLOBALS["x_Emi_NomCorto"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`Emi_NomCorto`"] = $theValue;

	// Field Emi_ArchivoKey
		if (is_uploaded_file($_FILES["x_Emi_ArchivoKey"]["tmp_name"])) {
			$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_Emi_ArchivoKey"]["name"]);
					if (!move_uploaded_file($_FILES["x_Emi_ArchivoKey"]["tmp_name"], $destfile)) // move file to destination path
					die("You didn't upload a file or the file couldn't be moved to" . $destfile);

			// File Name
			$theName = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes(ewUploadFileName($_FILES["x_Emi_ArchivoKey"]["name"])) : ewUploadFileName($_FILES["x_Emi_ArchivoKey"]["name"]);
			$fieldList["`Emi_ArchivoKey`"] = " '" . $theName . "'";
			@unlink($_FILES["x_Emi_ArchivoKey"]["tmp_name"]);
		}

	// Field Emi_ArchivoCer
		if (is_uploaded_file($_FILES["x_Emi_ArchivoCer"]["tmp_name"])) {
			$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_Emi_ArchivoCer"]["name"]);
					if (!move_uploaded_file($_FILES["x_Emi_ArchivoCer"]["tmp_name"], $destfile)) // move file to destination path
					die("You didn't upload a file or the file couldn't be moved to" . $destfile);

			// File Name
			$theName = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes(ewUploadFileName($_FILES["x_Emi_ArchivoCer"]["name"])) : ewUploadFileName($_FILES["x_Emi_ArchivoCer"]["name"]);
			$fieldList["`Emi_ArchivoCer`"] = " '" . $theName . "'";
			@unlink($_FILES["x_Emi_ArchivoCer"]["tmp_name"]);
		}

	// insert into database
	$sSql = "INSERT INTO `vit_emisor` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
	return true;
}
?>
