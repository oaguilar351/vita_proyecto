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
$x_arch_ArchivoID = Null; 
$ox_arch_ArchivoID = Null;
$x_arch_Ruta = Null; 
$ox_arch_Ruta = Null;
$fs_x_arch_Ruta = 0;
$fn_x_arch_Ruta = "";
$ct_x_arch_Ruta = "";
$w_x_arch_Ruta = 0;
$h_x_arch_Ruta = 0;
$a_x_arch_Ruta = "";
$x_Mun_ID = Null; 
$ox_Mun_ID = Null;
$x_arch_Status = Null; 
$ox_arch_Status = Null;
$x_arch_FecReg = Null; 
$ox_arch_FecReg = Null;
$x_arch_UsuReg = Null; 
$ox_arch_UsuReg = Null;
$x_arch_FecAct = Null; 
$ox_arch_FecAct = Null;
$x_arch_UsuAct = Null; 
$ox_arch_UsuAct = Null;
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
$x_arch_ArchivoID = @$_GET["arch_ArchivoID"];
if (empty($x_arch_ArchivoID)) {
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
	$x_arch_ArchivoID = @$_POST["n_arch_ArchivoID"];
	$x_arch_Ruta = @$_POST["n_arch_Ruta"];
	$x_Mun_ID = @$_POST["n_Mun_ID"];
	$x_arch_Status = @$_POST["n_arch_Status"];
	$x_arch_FecReg = @$_POST["n_arch_FecReg"];
	$x_arch_UsuReg = @$_POST["n_arch_UsuReg"];
	$x_arch_FecAct = @$_POST["n_arch_FecAct"];
	$x_arch_UsuAct = @$_POST["n_arch_UsuAct"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No hay registros";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: archivos_listado.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Nuevo registro agregado con exito.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: archivos_listado.php");
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
	global $x_arch_ArchivoID;
	$sSql = "SELECT * FROM `vit_archivos`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_arch_ArchivoID) : $x_arch_ArchivoID;
	$sWhere .= "(`arch_ArchivoID` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_arch_ArchivoID"] = $row["arch_ArchivoID"];
		$GLOBALS["x_arch_Ruta"] = $row["arch_Ruta"];
		$GLOBALS["x_Mun_ID"] = $row["Mun_ID"];
		$GLOBALS["x_arch_Status"] = $row["arch_Status"];
		$GLOBALS["x_arch_FecReg"] = $row["arch_FecReg"];
		$GLOBALS["x_arch_UsuReg"] = $row["arch_UsuReg"];
		$GLOBALS["x_arch_FecAct"] = $row["arch_FecAct"];
		$GLOBALS["x_arch_UsuAct"] = $row["arch_UsuAct"];
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
	global $x_arch_ArchivoID;
	$sSql = "SELECT * FROM `vit_archivos`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_arch_ArchivoID == "") || (is_null($x_arch_ArchivoID))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_arch_ArchivoID) : $x_arch_ArchivoID;			
		$sWhereChk .= "(`arch_ArchivoID` = " . addslashes($sTmp) . ")";
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

		// check file size
		$EW_MaxFileSize = @$_POST["EW_Max_File_Size"];
	if (!empty($_FILES["x_arch_Ruta"]["size"])) {
		if (!empty($EW_MaxFileSize) && $_FILES["x_arch_Ruta"]["size"] > $EW_MaxFileSize) {
			die("Max. file upload size exceeded");
		}
	}

	// Field arch_Ruta
		if (is_uploaded_file($_FILES["x_arch_Ruta"]["tmp_name"])) {
			$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_arch_Ruta"]["name"]);
					if (!move_uploaded_file($_FILES["x_arch_Ruta"]["tmp_name"], $destfile)) // move file to destination path
					die("You didn't upload a file or the file couldn't be moved to" . $destfile);

			// File Name
			$theName = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes(ewUploadFileName($_FILES["x_arch_Ruta"]["name"])) : ewUploadFileName($_FILES["x_arch_Ruta"]["name"]);
			$fieldList["`arch_Ruta`"] = " '" . $theName . "'";
			@unlink($_FILES["x_arch_Ruta"]["tmp_name"]);
		}

	// Field Mun_ID
	$theValue = ($GLOBALS["x_Mun_ID"] != "") ? intval($GLOBALS["x_Mun_ID"]) : "NULL";
	$fieldList["`Mun_ID`"] = $theValue;

	// Field arch_Status
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_arch_Status"]) : $GLOBALS["x_arch_Status"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`arch_Status`"] = $theValue;

	// Field arch_FecReg
	$GLOBALS["x_arch_FecReg"] = date('Y-m-d H:i:s');
	$theValue = ($GLOBALS["x_arch_FecReg"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_arch_FecReg"]) . "'" : "NULL";
	$fieldList["`arch_FecReg`"] = $theValue;

	// Field arch_UsuReg
	$GLOBALS["x_arch_UsuReg"]= @$_SESSION["project1_status_User"];
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_arch_UsuReg"]) : $GLOBALS["x_arch_UsuReg"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`arch_UsuReg`"] = $theValue;

	// Field arch_FecAct
	$GLOBALS["x_arch_FecAct"] = date('Y-m-d H:i:s');
	$theValue = ($GLOBALS["x_arch_FecAct"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_arch_FecAct"]) . "'" : "NULL";
	$fieldList["`arch_FecAct`"] = $theValue;

	// Field arch_UsuAct
	$GLOBALS["x_arch_UsuAct"]= @$_SESSION["project1_status_User"];
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_arch_UsuAct"]) : $GLOBALS["x_arch_UsuAct"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`arch_UsuAct`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `vit_archivos` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
	return true;
}
?>
