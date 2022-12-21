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
$x_arcdet_archivoDetalleID = Null; 
$ox_arcdet_archivoDetalleID = Null;
$x_arcdet_archivoID = Null; 
$ox_arcdet_archivoID = Null;
$x_arcdet_folio = Null; 
$ox_arcdet_folio = Null;
$x_arcdet_tipo = Null; 
$ox_arcdet_tipo = Null;
$x_arcdet_clave = Null; 
$ox_arcdet_clave = Null;
$x_arcdet_concepto = Null; 
$ox_arcdet_concepto = Null;
$x_arcdet_importe = Null; 
$ox_arcdet_importe = Null;
$x_arcdet_perc_deduc = Null; 
$ox_arcdet_perc_deduc = Null;
$x_arcdet_seriefolio = Null; 
$ox_arcdet_seriefolio = Null;
$x_arcdet_rfc_vita = Null; 
$ox_arcdet_rfc_vita = Null;
$x_arcdet_nombre = Null; 
$ox_arcdet_nombre = Null;
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/phpmkrfn.php") ?>
<?php

// v3.1 Multiple Primary Keys
// Load key from QueryString

$bCopy = true;
#$arcdet_archivoID = @$_GET["arcdet_archivoID"];
$arcdet_archivoID = (@$_GET["arcdet_archivoID"]!="")?@$_GET["arcdet_archivoID"]:@$_POST["arcdet_archivoID"]; // Load Parameter from QueryString
$x_arcdet_archivoDetalleID = @$_GET["arcdet_archivoDetalleID"];
if (empty($x_arcdet_archivoDetalleID)) {
	$bCopy = false;
}
$arcdet_folio = (@$_GET["arcdet_folio"]!="")?@$_GET["arcdet_folio"]:@$_POST["arcdet_folio"]; // Load Parameter from QueryString

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
	$x_arcdet_archivoDetalleID = @$_POST["x_arcdet_archivoDetalleID"];
	$x_arcdet_archivoID = @$_POST["x_arcdet_archivoID"];
	$x_arcdet_folio = @$_POST["x_arcdet_folio"];
	$x_arcdet_tipo = @$_POST["x_arcdet_tipo"];
	$x_arcdet_clave = @$_POST["x_arcdet_clave"];
	$x_arcdet_concepto = @$_POST["x_arcdet_concepto"];
	$x_arcdet_importe = @$_POST["x_arcdet_importe"];
	$x_arcdet_perc_deduc = @$_POST["x_arcdet_perc_deduc"];
	$x_arcdet_seriefolio = @$_POST["x_arcdet_seriefolio"];
	$x_arcdet_rfc_vita = @$_POST["x_arcdet_rfc_vita"];
	$x_arcdet_nombre = @$_POST["x_arcdet_nombre"];
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "C": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: archivos_rosarito_percep_deduclist.php");
			exit();
		}
		break;
	case "A": // Add
		if (AddData($conn)) { // Add New Record
			$_SESSION["ewmsg"] = "Add New Record Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: archivos_rosarito_percep_deduclist.php?showmaster=1&arcdet_archivoID=".$arcdet_archivoID."&arcdet_folio=".$arcdet_folio."");
			exit();
		}
		break;
}
?>

<script type="text/javascript" src="js/ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "-"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
if (EW_this.x_arcdet_importe && !EW_checknumber(EW_this.x_arcdet_importe.value)) {
	if (!EW_onError(EW_this, EW_this.x_arcdet_importe, "TEXT", "Incorrect floating point number - Importe"))
		return false; 
}
return true;
}

//-->
</script>

    <head>
        
        <title>Editar Recibo | VitaInsumos</title>
        <?php include 'layouts/title-meta.php'; ?>

        <?php include 'layouts/head-css.php'; ?>

    </head>

    <?php include 'layouts/body.php'; ?>

        <!-- Begin page -->
        <div id="layout-wrapper" style="font-family: Verdana; font-size: 12px;">

            <?php include 'layouts/menu.php'; ?>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
				
					<div class="position-relative mx-n4 mt-n4">
					<br /><br /><br />
                    </div>
                    <div class="row">
                        
                        <div class="col-xxl-12">
                            <div class="card mt-xxl-n5">
                                <div class="card-header">
                                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0"
                                        role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails"
                                                role="tab">
                                                <i class="fas fa-home"></i>
                                                Informacion Recibo 
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body p-4">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
<form name="archivos_rosarito_percep_deducadd" id="archivos_rosarito_percep_deducadd" action="archivos_rosarito_percep_deducadd.php" method="post" onSubmit="return EW_checkMyForm(this);">
<input type="hidden" name="a_add" value="A">
<input type="hidden" name="arcdet_folio" id="arcdet_folio" value="<?php echo urlencode($arcdet_folio); ?>">
<input type="hidden" name="arcdet_archivoID" id="arcdet_archivoID" value="<?php echo urlencode($arcdet_archivoID); ?>">
<table class="table align-middle" id="customerTable">
		<tr>
		<td style="background-color:#f2f4f4;"><span>Archivo</span></td>
		<td style="background-color:#fdedec;"><span class="phpmaker">
<?php if (@$_SESSION["archivos_rosarito_percep_deduc_MasterKey_arcdet_archivoID"] <> "") { ?>
<?php $x_arcdet_archivoID = @$_SESSION["archivos_rosarito_percep_deduc_MasterKey_arcdet_archivoID"]; ?>
<?php
if ((!is_null($x_arcdet_archivoID)) && ($x_arcdet_archivoID <> "")) {
	$sSqlWrk = "SELECT `arc_archivoRuta` FROM `archivos_rosarito`";
	$sTmp = $x_arcdet_archivoID;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `arc_archivoID` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["arc_archivoRuta"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_arcdet_archivoID = $x_arcdet_archivoID; // Backup Original Value
$x_arcdet_archivoID = $sTmp;
?>
<?php echo $x_arcdet_archivoID; ?>
<?php $x_arcdet_archivoID = $ox_arcdet_archivoID; // Restore Original Value ?>
<input type="hidden" id="x_arcdet_archivoID" name="x_arcdet_archivoID" value="<?php echo $x_arcdet_archivoID; ?>">
<?php } else { ?>
<?php
$x_arcdet_archivoIDList = "<select name=\"x_arcdet_archivoID\">";
$x_arcdet_archivoIDList .= "<option value=''>Favor de elegir</option>";
$sSqlWrk = "SELECT `arc_archivoID`, `arc_archivoRuta` FROM `archivos_rosarito`";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_arcdet_archivoIDList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["arc_archivoID"] == @$x_arcdet_archivoID) {
			$x_arcdet_archivoIDList .= "' selected";
		}
		$x_arcdet_archivoIDList .= ">" . $datawrk["arc_archivoRuta"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_arcdet_archivoIDList .= "</select>";
echo $x_arcdet_archivoIDList;
?>
<?php } ?>
</span></td>
	</tr>
	<tr>
		<td style="background-color:#f2f4f4;"><span>Num. Empleado</span></td>
		<td style="background-color:#fdedec;"><span class="phpmaker">
<input type="text" class="form-control" name="x_arcdet_folio" id="x_arcdet_folio" size="30" maxlength="10" value="<?php echo htmlspecialchars(@$x_arcdet_folio) ?>" readonly>
</span></td>
	</tr>
	<tr>
		<td style="background-color:#f2f4f4;"><span>Tipo</span></td>
		<td style="background-color:#fdedec;"><span class="phpmaker">
<input type="text" class="form-control" name="x_arcdet_tipo" id="x_arcdet_tipo" size="30" maxlength="5" value="<?php echo htmlspecialchars(@$x_arcdet_tipo) ?>" readonly>
</span></td>
	</tr>
	<tr>
		<td style="background-color:#f2f4f4;"><span>Clave</span></td>
		<td style="background-color:#fdedec;"><span class="phpmaker">
<input type="text" class="form-control" name="x_arcdet_clave" id="x_arcdet_clave" size="30" maxlength="7" value="<?php echo htmlspecialchars(@$x_arcdet_clave) ?>" readonly>
</span></td>
	</tr>
	<tr>
		<td style="background-color:#f2f4f4;"><span>Concepto</span></td>
		<td style="background-color:#fdedec;"><span class="phpmaker">
<?php
$x_arcdet_conceptoList = "<select name=\"x_arcdet_concepto\" class=\"form-select form-select\">";
$x_arcdet_conceptoList .= "<option value=''>Favor de elegir</option>";
if($x_arcdet_perc_deduc=='P'){
$sSqlWrk = "SELECT `prc_TipoPercepcion`, `prc_Clave`, `prc_Concepto` FROM `percepciones_rosarito`";
}else{
$sSqlWrk = "SELECT `ddc_TipoDeduccion`, `ddc_Clave`, `ddc_Concepto` FROM `deducciones_rosarito`";	
}
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		
		if($x_arcdet_perc_deduc=='P'){
			$x_arcdet_conceptoList .= "<option value=\"" . $datawrk["prc_TipoPercepcion"] . "|" . $datawrk["prc_Clave"] . "|" . $datawrk["prc_Concepto"] . "\"";
		if ($datawrk["prc_TipoPercepcion"] == @$x_arcdet_tipo && $datawrk["prc_Clave"] == @$x_arcdet_clave && $datawrk["prc_Concepto"] == @$x_arcdet_concepto) {
			$x_arcdet_conceptoList .= "' selected";
		}
		}else{
			$x_arcdet_conceptoList .= "<option value=\"" . $datawrk["ddc_TipoDeduccion"] . "|" . $datawrk["ddc_Clave"] . "|" . $datawrk["ddc_Concepto"] . "\"";
		#if ($datawrk["ddc_Concepto"] == @$x_arcdet_concepto) {
			if ($datawrk["ddc_TipoDeduccion"] == @$x_arcdet_tipo && $datawrk["ddc_Clave"] == @$x_arcdet_clave && $datawrk["ddc_Concepto"] == @$x_arcdet_concepto) {
			$x_arcdet_conceptoList .= "' selected";
		}	
		}
		if($x_arcdet_perc_deduc=='P'){
		$x_arcdet_conceptoList .= ">" . $datawrk["prc_TipoPercepcion"] . " " . $datawrk["prc_Clave"] . " " . $datawrk["prc_Concepto"] . "</option>";
		}else{
		$x_arcdet_conceptoList .= ">" . $datawrk["ddc_TipoDeduccion"] . " " . $datawrk["ddc_Clave"] . " " . $datawrk["ddc_Concepto"] . "</option>";
		}		
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_arcdet_conceptoList .= "</select>";
echo $x_arcdet_conceptoList;
?>
</span></td>
	</tr>
	<tr>
		<td style="background-color:#f2f4f4;"><span>Importe</span></td>
		<td style="background-color:#fdedec;"><span class="phpmaker">
<input type="text" class="form-control" name="x_arcdet_importe" id="x_arcdet_importe" size="30" value="<?php echo htmlspecialchars(@$x_arcdet_importe) ?>">
</span></td>
	</tr>
	<tr>
		<td style="background-color:#f2f4f4;"><span>Percepcion/Deduccion</span></td>
		<td style="background-color:#fdedec;"><span class="phpmaker">
<input type="text" class="form-control" name="x_arcdet_perc_deduc" id="x_arcdet_perc_deduc" size="30" maxlength="20" value="<?php echo htmlspecialchars(@$x_arcdet_perc_deduc) ?>" readonly>
</span></td>
	</tr>
	<tr>
		<td style="background-color:#f2f4f4;"><span>Serie Folio</span></td>
		<td style="background-color:#fdedec;"><span class="phpmaker">
<input type="text" class="form-control" name="x_arcdet_seriefolio" id="x_arcdet_seriefolio" size="30" maxlength="50" value="<?php echo htmlspecialchars(@$x_arcdet_seriefolio) ?>" readonly>
</span></td>
	</tr>
	<tr>
		<td style="background-color:#f2f4f4;"><span>Rfc Vita</span></td>
		<td style="background-color:#fdedec;"><span class="phpmaker">
<input type="text" class="form-control" name="x_arcdet_rfc_vita" id="x_arcdet_rfc_vita" size="30" maxlength="25" value="<?php echo htmlspecialchars(@$x_arcdet_rfc_vita) ?>" readonly>
</span></td>
	</tr>
	<tr>
		<td style="background-color:#f2f4f4;"><span>Empleado</span></td>
		<td style="background-color:#fdedec;"><span class="phpmaker">
<input type="text" class="form-control" name="x_arcdet_nombre" id="x_arcdet_nombre" size="30" maxlength="200" value="<?php echo htmlspecialchars(@$x_arcdet_nombre) ?>" readonly>
</span></td>
	</tr>
</table>
<!--<p>
<input type="submit" name="Action" value="ADD">-->
<div class="col-lg-12">
<br />
	<div class="hstack gap-2 justify-content-end">
		<button type="submit" name="Action" class="btn btn-soft-success waves-effect waves-light" value="EDIT">Actualizar</button>
		<!--<a href="archivos_rosarito_percep_deduclist.php?showmaster=1&arcdet_archivoID=<?php echo $arcdet_archivoID; ?>&arcdet_folio=<?php echo $arcdet_folio; ?>">Back to List</a>-->
		<a class="btn btn-soft-primary waves-effect waves-light" href="archivos_rosarito_percep_deduclist.php?showmaster=1&arcdet_archivoID=<?php echo $arcdet_archivoID; ?>&arcdet_folio=<?php echo $arcdet_folio; ?>">Cancelar</a>
		<input type="hidden" name="a_edit" value="U">
	</div>
</div>
</form>
</div>
                                        <!--end tab-pane-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->

				</div>
                <!-- container-fluid -->
            </div><!-- End Page-content -->

                <?php #include 'layouts/footer.php'; ?>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        

        <?php #include 'layouts/customizer.php'; ?>

        <?php include 'layouts/vendor-scripts.php'; ?>

        <!-- profile-setting init js -->
        <script src="assets/js/pages/profile-setting.init.js"></script>

        <!-- App js -->
        <script src="assets/js/app.js"></script>
    </body>

</html>
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
	global $x_arcdet_archivoDetalleID;
	$sSql = "SELECT * FROM `archivos_rosarito_percep_deduc`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_arcdet_archivoDetalleID) : $x_arcdet_archivoDetalleID;
	$sWhere .= "(`arcdet_archivoDetalleID` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_arcdet_archivoDetalleID"] = $row["arcdet_archivoDetalleID"];
		$GLOBALS["x_arcdet_archivoID"] = $row["arcdet_archivoID"];
		$GLOBALS["x_arcdet_folio"] = $row["arcdet_folio"];
		$GLOBALS["x_arcdet_tipo"] = $row["arcdet_tipo"];
		$GLOBALS["x_arcdet_clave"] = $row["arcdet_clave"];
		$GLOBALS["x_arcdet_concepto"] = $row["arcdet_concepto"];
		$GLOBALS["x_arcdet_importe"] = $row["arcdet_importe"];
		$GLOBALS["x_arcdet_perc_deduc"] = $row["arcdet_perc_deduc"];
		$GLOBALS["x_arcdet_seriefolio"] = $row["arcdet_seriefolio"];
		$GLOBALS["x_arcdet_rfc_vita"] = $row["arcdet_rfc_vita"];
		$GLOBALS["x_arcdet_nombre"] = $row["arcdet_nombre"];
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
	global $x_arcdet_archivoDetalleID;
	$sSql = "SELECT * FROM `archivos_rosarito_percep_deduc`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";

	// Check for duplicate key
	$bCheckKey = true;
	$sWhereChk = $sWhere;
	if ((@$x_arcdet_archivoDetalleID == "") || (is_null($x_arcdet_archivoDetalleID))) {
		$bCheckKey = false;
	} else {
		if ($sWhereChk <> "") { $sWhereChk .= " AND "; }
		$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_arcdet_archivoDetalleID) : $x_arcdet_archivoDetalleID;			
		$sWhereChk .= "(`arcdet_archivoDetalleID` = " . addslashes($sTmp) . ")";
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

	// Field arcdet_archivoID
	$theValue = ($GLOBALS["x_arcdet_archivoID"] != "") ? intval($GLOBALS["x_arcdet_archivoID"]) : "NULL";
	$fieldList["`arcdet_archivoID`"] = $theValue;

	// Field arcdet_folio
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_arcdet_folio"]) : $GLOBALS["x_arcdet_folio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`arcdet_folio`"] = $theValue;

	if(isset($GLOBALS["x_arcdet_concepto"]) && $GLOBALS["x_arcdet_concepto"]!=""){
		$datos = explode("|", $GLOBALS["x_arcdet_concepto"]);
		$arcdet_tipo = $datos[0];
		$arcdet_clave = $datos[1];
		$det_concepto = $datos[2];
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($arcdet_tipo) : $arcdet_tipo; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`arcdet_tipo`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($arcdet_clave) : $arcdet_clave; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`arcdet_clave`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($det_concepto) : $det_concepto; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`arcdet_concepto`"] = $theValue;
	}
		
	/*// Field arcdet_tipo
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_arcdet_tipo"]) : $GLOBALS["x_arcdet_tipo"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`arcdet_tipo`"] = $theValue;

	// Field arcdet_clave
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_arcdet_clave"]) : $GLOBALS["x_arcdet_clave"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`arcdet_clave`"] = $theValue;

	// Field arcdet_concepto
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_arcdet_concepto"]) : $GLOBALS["x_arcdet_concepto"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`arcdet_concepto`"] = $theValue;*/

	// Field arcdet_importe
	$theValue = ($GLOBALS["x_arcdet_importe"] != "") ? " '" . doubleval($GLOBALS["x_arcdet_importe"]) . "'" : "NULL";
	$fieldList["`arcdet_importe`"] = $theValue;

	// Field arcdet_perc_deduc
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_arcdet_perc_deduc"]) : $GLOBALS["x_arcdet_perc_deduc"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`arcdet_perc_deduc`"] = $theValue;

	// Field arcdet_seriefolio
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_arcdet_seriefolio"]) : $GLOBALS["x_arcdet_seriefolio"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`arcdet_seriefolio`"] = $theValue;

	// Field arcdet_rfc_vita
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_arcdet_rfc_vita"]) : $GLOBALS["x_arcdet_rfc_vita"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`arcdet_rfc_vita`"] = $theValue;

	// Field arcdet_nombre
	$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_arcdet_nombre"]) : $GLOBALS["x_arcdet_nombre"]; 
	$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
	$fieldList["`arcdet_nombre`"] = $theValue;

	// insert into database
	$sSql = "INSERT INTO `archivos_rosarito_percep_deduc` (";
	$sSql .= implode(",", array_keys($fieldList));
	$sSql .= ") VALUES (";
	$sSql .= implode(",", array_values($fieldList));
	$sSql .= ")";
	phpmkr_query($sSql, $conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
	return true;
}
?>
