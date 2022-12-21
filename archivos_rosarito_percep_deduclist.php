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
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=archivos_rosarito_percep_deduc.xls');
}
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/phpmkrfn.php") ?>
<?php
$nStartRec = 0;
$nStopRec = 0;
$nTotalRecs = 0;
$nRecCount = 0;
$nRecActual = 0;
$sKeyMaster = "";
$sDbWhereMaster = "";
$sSrchAdvanced = "";
$sDbWhereDetail = "";
$sSrchBasic = "";
$sSrchWhere = "";
$sDbWhere = "";
$sDefaultOrderBy = "";
$sDefaultFilter = "";
$sWhere = "";
$sGroupBy = "";
$sHaving = "";
$sOrderBy = "";
$sSqlMasterBase = "";
$sSqlMaster = "";
$bMasterRecordExist = false;
$x_arc_archivoID = Null;
$ox_arc_archivoID = Null;
$x_arc_archivoRuta = Null;
$ox_arc_archivoRuta = Null;
$fs_x_arc_archivoRuta = Null;
$fn_x_arc_archivoRuta = Null;
$ct_x_arc_archivoRuta = Null;
$w_x_arc_archivoRuta = Null;
$h_x_arc_archivoRuta = Null;
$a_x_arc_archivoRuta = Null;
$nDisplayRecs = 20;
$nRecRange = 10;

// Set up records per page dynamically
SetUpDisplayRecs();

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// Handle Reset Command
ResetCmd();

// Set Up Master Detail Parameters
SetUpMasterDetail();

// Set Up Inline Edit Parameters
$sAction = "";
SetUpInlineEdit($conn);

// Get Search Criteria for Basic Search
SetUpBasicSearch();

// Build Search Criteria
if ($sSrchAdvanced != "") {
	$sSrchWhere = $sSrchAdvanced; // Advanced Search
}
elseif ($sSrchBasic != "") {
	$sSrchWhere = $sSrchBasic; // Basic Search
}

// Save Search Criteria
if ($sSrchWhere != "") {
	$_SESSION["archivos_rosarito_percep_deduc_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["archivos_rosarito_percep_deduc_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["archivos_rosarito_percep_deduc_searchwhere"];
}

// Build SQL
$sSql = "SELECT * FROM `archivos_rosarito_percep_deduc`";

// Load Default Filter
$sDefaultFilter = "";
$arcdet_archivoID = (@$_GET["arcdet_archivoID"]!="")?@$_GET["arcdet_archivoID"]:@$_POST["arcdet_archivoID"]; // Load Parameter from QueryString
$arcdet_folio = (@$_GET["arcdet_folio"]!="")?@$_GET["arcdet_folio"]:@$_POST["arcdet_folio"]; // Load Parameter from QueryString

$sTmp = (!(function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())) ? addslashes($arcdet_folio) : $arcdet_folio;
if ($sDefaultFilter <> "") { $sDefaultFilter .= " AND "; }
$sDefaultFilter .= "`arcdet_folio` =  " . $sTmp . "";

$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "arcdet_clave ASC";

// Build WHERE condition
$sDbWhere = "";
if ($sDbWhereDetail <> "") {
	$sDbWhere .= "(" . $sDbWhereDetail . ") AND ";
}
if ($sSrchWhere <> "") {
	$sDbWhere .= "(" . $sSrchWhere . ") AND ";
}
if (strlen($sDbWhere) > 5) {
	$sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5); // Trim rightmost AND
}
$sWhere = "";
if ($sDefaultFilter <> "") {
	$sWhere .= "(" . $sDefaultFilter . ") AND ";
}
if ($sDbWhere <> "") {
	$sWhere .= "(" . $sDbWhere . ") AND ";
}
if (substr($sWhere, -5) == " AND ") {
	$sWhere = substr($sWhere, 0, strlen($sWhere)-5);
}
if ($sWhere != "") {
	$sSql .= " WHERE " . $sWhere;
}
if ($sGroupBy != "") {
	$sSql .= " GROUP BY " . $sGroupBy;
}
if ($sHaving != "") {
	$sSql .= " HAVING " . $sHaving;
}

// Set Up Sorting Order
$sOrderBy = "";
SetUpSortOrder();
if ($sOrderBy != "") {
	$sSql .= " ORDER BY " . $sOrderBy;
}

#echo $sSql; // Uncomment to show SQL for debugging
// Build Master Record SQL

if ($sDbWhereMaster <> "") {
	$sSqlMasterBase = "SELECT * FROM `archivos_rosarito`";
	$sWhereMaster = "";
	$sGroupByMaster = "";
	$sHavingMaster = "";
	$sOrderByMaster = "";
	$sSqlMaster = $sSqlMasterBase;
	if ($sWhereMaster <> "") { $sWhereMaster .= " AND "; }
	$sWhereMaster .= $sDbWhereMaster;
	if ($sWhereMaster <> "") {
		$sSqlMaster .= " WHERE " . $sWhereMaster;
	}
	if ($sGroupByMaster <> "") {
		$sSqlMaster .= " GROUP BY " . $sGroupByMaster;
	}
	if ($sHavingMaster <> "") {
		$sSqlMaster .= " HAVING " . $sHavingMaster;
	}
	if ($sOrderByMaster <> "") {
		$sSqlMaster .= " ORDER BY " . $sOrderByMaster;
	}
	$rs = phpmkr_query($sSqlMaster, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlMaster);
	$bMasterRecordExist = (phpmkr_num_rows($rs) > 0);
	if (!$bMasterRecordExist) {
		$_SESSION["_MasterWhere"] = "";
		$_SESSION["archivos_rosarito_percep_deduc_DetailWhere"] = "";
		$_SESSION["ewmsg"] = "No records found";
		phpmkr_free_result($rs);
		phpmkr_db_close($conn);
		header("Location: archivos_rosaritolist.php");
	}
}
?>

<?php if ($sExport == "") { ?>
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
<?php } ?>
<head>
        
        <title>Recibos - Percepciones Deducciones | VitaInsumos</title>
        <?php include 'layouts/title-meta.php'; ?>
        <link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

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
				
					<!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Recibos - Percepciones Deducciones</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Modulos</a></li>
                                        <li class="breadcrumb-item active">Recibos - Percepciones Deducciones</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-lg-12">
                            <div class="card" id="leadsList">
                                <div class="card-header border-0">							

<?php
if ($sDbWhereMaster <> "") {
	if ($bMasterRecordExist) { ?>
<?php
		$row = phpmkr_fetch_array($rs);
		$x_arc_archivoID = $row["arc_archivoID"];
		$x_arc_archivoRuta = $row["arc_archivoRuta"];
		$x_Mun_ID = $row["Mun_ID"];
		$x_Emi_RFC = $row["Emi_RFC"];
		$x_arc_fechaCarga = $row["arc_fechaCarga"];
		$x_arc_fechaAct = $row["arc_fechaAct"];
		$x_arc_usuarioReg = $row["arc_usuarioReg"];
?>	
<div class="row g-4 align-items-center">
		<div class="col-sm-3">
			<div class="search-box">
				<form action="archivos_rosarito_percep_deduclist.php">
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td><span class="phpmaker">
							<input type="text" name="psearch" class="form-control search">
							<input type="hidden" name="psearchtype" value="" checked>
						</span></td>
					</tr>
				</table>
				</form>
			</div>
		</div>
		<div class="col-sm-auto ms-auto">											
			<div class="hstack gap-2">
			   <a class="btn btn-primary waves-effect waves-light" href="archivos_listado.php">Regresar</a>
			   <a class="btn btn-primary waves-effect waves-light" href="archivos_rosarito_percep_deduclist.php?cmd=reset&cmd=resetall&arcdet_archivoID=<?php echo urlencode($x_arc_archivoID); ?>">Todos</a>
			</div>
		</div>
	</div>
</div>
<div class="card-body">
	<div>
		<div class="table-responsive table-card">
<table class="table align-middle" id="customerTable">
	<thead class="table-light">
		<tr>
		<th valign="top"><span><a href="javascript:void(0);">ID</a></span></th>
		<th valign="top"><span><a href="javascript:void(0);">Archivo</a></span></th>
		<th valign="top"><span><a href="javascript:void(0);">Municipio</a></span></th>
		<th valign="top"><span><a href="javascript:void(0);">Emisor</a></span></th>
		<th valign="top"><span><a href="javascript:void(0);">Fecha Carga</a></span></th>
		<th valign="top"><span><a href="javascript:void(0);">Fecha Act</a></span></th>
		<th valign="top"><span><a href="javascript:void(0);">Usuario Reg</a></span></th>
		</tr>
	</thead>
	<tbody class="list form-check-all">
	<tr bgcolor="#FFFFFF">
		<td><span class="phpmaker">
<?php echo $x_arc_archivoID; ?>
</span></td>
		<td><span class="phpmaker">
<?php if ((!is_null($x_arc_archivoRuta)) &&  $x_arc_archivoRuta <> "") { ?>
<a href="<?php echo ewUploadPath(0) . $x_arc_archivoRuta ?>" target="_blank"><?php echo $x_arc_archivoRuta; ?></a>
<?php } ?>
</span></td>
		<td><span class="phpmaker">
<?php
if ((!is_null($x_Mun_ID)) && ($x_Mun_ID <> "")) {
	$sSqlWrk = "SELECT `Mun_Descrip` FROM `Vit_Municipios`";
	$sTmp = $x_Mun_ID;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `Mun_ID` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["Mun_Descrip"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_Mun_ID = $x_Mun_ID; // Backup Original Value
$x_Mun_ID = $sTmp;
?>
<?php echo $x_Mun_ID; ?>
<?php $x_Mun_ID = $ox_Mun_ID; // Restore Original Value ?>
</span></td>
		<td><span class="phpmaker">
<?php
if ((!is_null($x_Emi_RFC)) && ($x_Emi_RFC <> "")) {
	$sSqlWrk = "SELECT `Emi_Nombre` FROM `Vit_Emisor`";
	$sTmp = $x_Emi_RFC;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `Emi_RFC` = '" . $sTmp . "'";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["Emi_Nombre"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_Emi_RFC = $x_Emi_RFC; // Backup Original Value
$x_Emi_RFC = $sTmp;
?>
<?php echo $x_Emi_RFC; ?>
<?php $x_Emi_RFC = $ox_Emi_RFC; // Restore Original Value ?>
</span></td>
		<td><span class="phpmaker">
<?php echo FormatDateTime($x_arc_fechaCarga,5); ?>
</span></td>
		<td><span class="phpmaker">
<?php echo FormatDateTime($x_arc_fechaAct,5); ?>
</span></td>
		<td><span class="phpmaker">
<?php
if ((!is_null($x_arc_usuarioReg)) && ($x_arc_usuarioReg <> "")) {
	$sSqlWrk = "SELECT `Vit_Nombre` FROM `vit_usuarios`";
	$sTmp = $x_arc_usuarioReg;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `Vit_Usuario` = '" . $sTmp . "'";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["Vit_Nombre"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_arc_usuarioReg = $x_arc_usuarioReg; // Backup Original Value
$x_arc_usuarioReg = $sTmp;
?>
<?php echo $x_arc_usuarioReg; ?>
<?php $x_arc_usuarioReg = $ox_arc_usuarioReg; // Restore Original Value ?>
</span></td>
	</tr>
	</tbody>
</table>
<?php
	}
	phpmkr_free_result($rs);
}
?>
<?php

// Set up Record Set
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
<?php if ($nTotalRecs > 0)  { ?>
<form name="archivos_rosarito_percep_deduclist" id="archivos_rosarito_percep_deduclist" action="archivos_rosarito_percep_deduclist.php" method="post">
<table class="table align-middle" id="customerTable">
	<thead class="table-light">
		<tr>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Serie Folio
<?php }else{ ?>
	<a href="archivos_rosarito_percep_deduclist.php?order=<?php echo urlencode("arcdet_seriefolio"); ?>">Serie Folio&nbsp;<?php if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_seriefolio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_seriefolio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Rfc Vita
<?php }else{ ?>
	<a href="archivos_rosarito_percep_deduclist.php?order=<?php echo urlencode("arcdet_rfc_vita"); ?>">Rfc Vita&nbsp;<?php if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_rfc_vita_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_rfc_vita_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Empleado
<?php }else{ ?>
	<a href="archivos_rosarito_percep_deduclist.php?order=<?php echo urlencode("arcdet_nombre"); ?>">Empleado&nbsp;<?php if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_nombre_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_nombre_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Num. Empleado
<?php }else{ ?>
	<a href="archivos_rosarito_percep_deduclist.php?order=<?php echo urlencode("arcdet_folio"); ?>">Num. Empleado&nbsp;<?php if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_folio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_folio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Percepcion/Deduccion
<?php }else{ ?>
	<a href="archivos_rosarito_percep_deduclist.php?order=<?php echo urlencode("arcdet_perc_deduc"); ?>">Percepcion/Deduccion&nbsp;<?php if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_perc_deduc_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_perc_deduc_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Tipo
<?php }else{ ?>
	<a href="archivos_rosarito_percep_deduclist.php?order=<?php echo urlencode("arcdet_tipo"); ?>">Tipo&nbsp;<?php if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_tipo_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_tipo_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Clave
<?php }else{ ?>
	<a href="archivos_rosarito_percep_deduclist.php?order=<?php echo urlencode("arcdet_clave"); ?>">Clave&nbsp;<?php if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_clave_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_clave_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Concepto
<?php }else{ ?>
	<a href="archivos_rosarito_percep_deduclist.php?order=<?php echo urlencode("arcdet_concepto"); ?>">Concepto<?php if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_concepto_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_concepto_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Importe
<?php }else{ ?>
	<a href="archivos_rosarito_percep_deduclist.php?order=<?php echo urlencode("arcdet_importe"); ?>">Importe<?php if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_importe_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_importe_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>		
<?php if ($sExport == "") { ?>
<!--<td>&nbsp;</td>-->
<th>&nbsp;</th>
<th>&nbsp;</th>
<?php } ?>
	</tr>
	</thead>
	<tbody class="list form-check-all">
<?php

// Avoid starting record > total records
if ($nStartRec > $nTotalRecs) {
	$nStartRec = $nTotalRecs;
}

// Set the last record to display
$nStopRec = $nStartRec + $nDisplayRecs - 1;

// Move to first record directly for performance reason
$nRecCount = $nStartRec - 1;
if (phpmkr_num_rows($rs) > 0) {
	phpmkr_data_seek($rs, $nStartRec -1);
}
$nEditRowCnt = 0;
$nRecActual = 0;
$tot_per = 0;
$tot_ded = 0;
while (($row = @phpmkr_fetch_array($rs)) && ($nRecCount < $nStopRec)) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;

		// Set row color
		$sItemRowClass = " bgcolor=\"#FFFFFF\"";

		// Display alternate color for rows
		if ($nRecCount % 2 <> 0) {
			$sItemRowClass = " bgcolor=\"#F5F5F5\"";
		}
		$x_arcdet_archivoDetalleID = $row["arcdet_archivoDetalleID"];
		$x_arcdet_archivoID = $row["arcdet_archivoID"];
		$x_arcdet_folio = $row["arcdet_folio"];
		$x_arcdet_tipo = $row["arcdet_tipo"];
		$x_arcdet_clave = $row["arcdet_clave"];
		$x_arcdet_concepto = $row["arcdet_concepto"];
		$x_arcdet_importe = $row["arcdet_importe"];
		$x_arcdet_perc_deduc = $row["arcdet_perc_deduc"];
		$x_arcdet_seriefolio = $row["arcdet_seriefolio"];
		$x_arcdet_rfc_vita = $row["arcdet_rfc_vita"];
		$x_arcdet_nombre = $row["arcdet_nombre"];
		
		if($x_arcdet_perc_deduc=='P'){
		$tot_per += $x_arcdet_importe;
		}else if($x_arcdet_perc_deduc=='D'){
		$tot_ded += $x_arcdet_importe;
		}
	$bEditRow = (($_SESSION["project_archivos_rosarito_Key_arcdet_archivoDetalleID"] == $x_arcdet_archivoDetalleID) && ($nEditRowCnt == 0));
	if ($bEditRow) {
		$nEditRowCnt++;
		$sItemRowClass = " bgcolor=\"#FFFF99\"";
	}
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
<?php if ($bEditRow) { ?>

<?php } ?>
		<!-- arcdet_seriefolio -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php echo $x_arcdet_seriefolio; ?><input type="hidden" id="x_arcdet_seriefolio" name="x_arcdet_seriefolio" value="<?php echo htmlspecialchars(@$x_arcdet_seriefolio); ?>">
<?php }else{ ?>
<?php echo $x_arcdet_seriefolio; ?>
<?php } ?>
</span></td>
		<!-- arcdet_rfc_vita -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php echo $x_arcdet_rfc_vita; ?><input type="hidden" id="x_arcdet_rfc_vita" name="x_arcdet_rfc_vita" value="<?php echo htmlspecialchars(@$x_arcdet_rfc_vita); ?>">
<?php }else{ ?>
<?php echo $x_arcdet_rfc_vita; ?>
<?php } ?>
</span></td>
		<!-- arcdet_nombre -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php echo $x_arcdet_nombre; ?><input type="hidden" id="x_arcdet_nombre" name="x_arcdet_nombre" value="<?php echo htmlspecialchars(@$x_arcdet_nombre); ?>">
<?php }else{ ?>
<?php echo $x_arcdet_nombre; ?>
<?php } ?>
</span></td>
		
		<!-- arcdet_folio -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php echo $x_arcdet_folio; ?><input type="hidden" id="x_arcdet_folio" name="x_arcdet_folio" value="<?php echo htmlspecialchars(@$x_arcdet_folio); ?>">
<?php }else{ ?>
<?php echo $x_arcdet_folio; ?>
<?php } ?>
</span></td>
		<!-- arcdet_perc_deduc -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php 
if($x_arcdet_perc_deduc=='P'){
	echo 'Percepcion';
}else if($x_arcdet_perc_deduc=='D'){
	echo 'Deduccion';
}else{
	echo '-';
}	
?>
<input type="hidden" id="x_arcdet_perc_deduc" name="x_arcdet_perc_deduc" value="<?php echo htmlspecialchars(@$x_arcdet_perc_deduc); ?>">
<?php }else{ ?>
<?php 
if($x_arcdet_perc_deduc=='P'){
	echo 'Percepcion';
}else if($x_arcdet_perc_deduc=='D'){
	echo 'Deduccion';
}else{
	echo '-';
}	
?>
<?php } ?>
</span></td>
		<!-- arcdet_tipo -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php echo $x_arcdet_tipo; ?><input type="hidden" id="x_arcdet_tipo" name="x_arcdet_tipo" value="<?php echo htmlspecialchars(@$x_arcdet_tipo); ?>">
<?php }else{ ?>
<?php echo $x_arcdet_tipo; ?>
<?php } ?>
</span></td>
		<!-- arcdet_clave -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php echo $x_arcdet_clave; ?><input type="hidden" id="x_arcdet_clave" name="x_arcdet_clave" value="<?php echo htmlspecialchars(@$x_arcdet_clave); ?>">
<?php }else{ ?>
<?php echo $x_arcdet_clave; ?>
<?php } ?>
</span></td>
		<!-- arcdet_concepto -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php
$x_arcdet_conceptoList = "<select name=\"x_arcdet_concepto\">";
$x_arcdet_conceptoList .= "<option value=''>Favor de elegir</option>";
if($x_arcdet_perc_deduc=='P'){
$sSqlWrk = "SELECT `prc_TipoPercepcion`, `prc_Clave`, `prc_Concepto` FROM `percepciones_rosarito`";
}else{
$sSqlWrk = "SELECT `ddc_TipoDeduccion`, `ddc_Clave`, `ddc_Concepto` FROM `deducciones_rosarito`";	
}
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		
		if($x_arcdet_perc_deduc=='P'){
			$x_arcdet_conceptoList .= "<option value=\"" . $datawrk["prc_TipoPercepcion"] . "|" . $datawrk["prc_Clave"] . "|" . $datawrk["prc_Concepto"] . "\"";
		if ($datawrk["prc_Concepto"] == @$x_arcdet_concepto) {
			$x_arcdet_conceptoList .= "' selected";
		}
		}else{
			$x_arcdet_conceptoList .= "<option value=\"" . $datawrk["ddc_TipoDeduccion"] . "|" . $datawrk["ddc_Clave"] . "|" . $datawrk["ddc_Concepto"] . "\"";
		if ($datawrk["ddc_Concepto"] == @$x_arcdet_concepto) {
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
<?php }else{ ?>
<?php
if ((!is_null($x_arcdet_concepto)) && ($x_arcdet_concepto <> "")) {
	$sSqlWrk = "SELECT `prc_Concepto` FROM `percepciones_rosarito`";
	$sTmp = $x_arcdet_concepto;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `prc_TipoPercepcion` = '" . $sTmp . "'";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["prc_Concepto"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_arcdet_concepto = $x_arcdet_concepto; // Backup Original Value
$x_arcdet_concepto = $sTmp;
?>
<?php echo $x_arcdet_concepto; ?>
<?php $x_arcdet_concepto = $ox_arcdet_concepto; // Restore Original Value ?>
<?php } ?>
</span></td>
		<!-- arcdet_importe -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<input type="text" name="x_arcdet_importe" id="x_arcdet_importe" size="30" value="<?php echo htmlspecialchars(@$x_arcdet_importe) ?>">
<?php }else{ ?>
<div align="right"><?php echo (is_numeric($x_arcdet_importe)) ? FormatCurrency($x_arcdet_importe,2,-2,-2,-2) : $x_arcdet_importe; ?></div>
<?php } ?>
</span></td>		
<?php if ($sExport == "") { ?>
<!--<td><span class="phpmaker"><a href="<?php if ($x_arcdet_archivoDetalleID <> "") {echo "archivos_rosarito_percep_deducview.php?arcdet_archivoDetalleID=" . urlencode($x_arcdet_archivoDetalleID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">View</a></span></td>-->
<td><span class="phpmaker">
<?php if ($_SESSION["project_archivos_rosarito_Key_arcdet_archivoDetalleID"] == $x_arcdet_archivoDetalleID) { ?>
<a href="" onClick="if (EW_checkMyForm(document.archivos_rosarito_percep_deduclist))document.archivos_rosarito_percep_deduclist.submit();return false;">Actualizar</a>&nbsp;<a href="archivos_rosarito_percep_deduclist.php?a=cancel&arcdet_archivoID=<?php echo urlencode(@$arcdet_archivoID); ?>&arcdet_folio=<?php echo urlencode($arcdet_folio); ?>">Cancel</a>
<input type="hidden" name="a_list" value="update">
<input type="hidden" name="arcdet_folio" id="arcdet_folio" value="<?php echo urlencode($arcdet_folio); ?>">
<input type="hidden" id="x_arcdet_archivoDetalleID" name="x_arcdet_archivoDetalleID" value="<?php echo htmlspecialchars(@$x_arcdet_archivoDetalleID); ?>">
<input type="hidden" id="arcdet_archivoID" name="arcdet_archivoID" value="<?php echo htmlspecialchars(@$x_arcdet_archivoID); ?>">
<?php } else { ?>
<a href="<?php if ($x_arcdet_archivoDetalleID <> "") {echo "archivos_rosarito_percep_deduclist.php?a=edit&arcdet_archivoDetalleID=" . urlencode($x_arcdet_archivoDetalleID)."&arcdet_folio=".urlencode($arcdet_folio); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Editar</a>
<?php } ?>
</span></td>
<td><span class="phpmaker"><a href="<?php if ($x_arcdet_archivoDetalleID <> "") {echo "archivos_rosarito_percep_deducadd.php?arcdet_archivoID=" . urlencode(@$arcdet_archivoID)."&arcdet_archivoDetalleID=" . urlencode($x_arcdet_archivoDetalleID)."&arcdet_folio=".urlencode($arcdet_folio); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Copiar</a></span></td>
<?php } ?>
	</tr>
<?php
	}
}
?>
<tr bgcolor="#EEEEEE">
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;<b>Total Percepcion</b></span></td>
<td align="right"><span class="phpmaker">&nbsp;<b><?php echo (is_numeric($tot_per)) ? FormatCurrency($tot_per,2,-2,-2,-2) : $tot_per; ?></b></span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
</tr>
<tr bgcolor="#EEEEEE">
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;<b>Total Deduccion</b></span></td>
<td align="right"><span class="phpmaker">&nbsp;<b><?php echo (is_numeric($tot_ded)) ? FormatCurrency($tot_ded,2,-2,-2,-2) : $tot_ded; ?></b></span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
</tr>
<tr bgcolor="#EEEEEE">
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;<b>Total</b></span></td>
<td align="right"><span class="phpmaker">&nbsp;<b><?php echo (is_numeric($tot_per-$tot_ded)) ? FormatCurrency($tot_per-$tot_ded,2,-2,-2,-2) : $tot_per-$tot_ded; ?></b></span></td>
<td><span class="phpmaker">&nbsp;</span></td>
<td><span class="phpmaker">&nbsp;</span></td>
</tr>
</tbody>
</table>
</form>
<?php if (strtoupper($sAction) == "EDIT") { ?>
<?php } ?>
<?php } ?>
</div>
	<div class="d-flex justify-content-end mt-3">
	<div class="pagination-wrap hstack gap-2">
<?php if ($sExport == "") { ?>
<form action="archivos_rosarito_percep_deduclist.php" name="ewpagerform" id="ewpagerform">
<table border="0" cellspacing="1" cellpadding="4">
	<tr>
		<td nowrap>
<?php
if ($nTotalRecs > 0) {
	$rsEof = ($nTotalRecs < ($nStartRec + $nDisplayRecs));
	$PrevStart = $nStartRec - $nDisplayRecs;
	if ($PrevStart < 1) { $PrevStart = 1; }
	$NextStart = $nStartRec + $nDisplayRecs;
	if ($NextStart > $nTotalRecs) { $NextStart = $nStartRec ; }
	$LastStart = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
	?>
	<table border="0" cellspacing="0" cellpadding="2"><tr><td><span class="phpmaker">Pagina &nbsp;</span></td>
<!--first page button-->
	<?php if ($nStartRec == 1) { ?>
	<td><a class="page-item pagination-prev disabled" href="#">|<</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-prev" href="archivos_rosarito_percep_deduclist.php?start=1&cmd=resetall&arcdet_archivoID=<?php echo urlencode($x_arc_archivoID); ?>">|<</a></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($PrevStart == $nStartRec) { ?>
	<td><a class="page-item pagination-prev disabled"><</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-prev" href="archivos_rosarito_percep_deduclist.php?start=<?php echo $PrevStart; ?>&cmd=resetall&arcdet_archivoID=<?php echo urlencode($x_arc_archivoID); ?>"><</a></td>
	<?php } ?>
<!--current page number-->
	<td><input class="form-control form-control-sm" type="text" name="pageno" value="<?php echo intval(($nStartRec-1)/$nDisplayRecs+1); ?>" size="4"></td>
<!--next page button-->
	<?php if ($NextStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">></a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="archivos_rosarito_percep_deduclist.php?start=<?php echo $NextStart; ?>&cmd=resetall&arcdet_archivoID=<?php echo urlencode($x_arc_archivoID); ?>">></a></td>
	<?php  } ?>
<!--last page button-->
	<?php if ($LastStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">>|</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="archivos_rosarito_percep_deduclist.php?start=<?php echo $LastStart; ?>&cmd=resetall&arcdet_archivoID=<?php echo urlencode($x_arc_archivoID); ?>">>|</a></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;de <?php echo intval(($nTotalRecs-1)/$nDisplayRecs+1);?></span></td>
	</tr></table>
	<?php if ($nStartRec > $nTotalRecs) { $nStartRec = $nTotalRecs; }
	$nStopRec = $nStartRec + $nDisplayRecs - 1;
	$nRecCount = $nTotalRecs - 1;
	if ($rsEof) { $nRecCount = $nTotalRecs; }
	if ($nStopRec > $nRecCount) { $nStopRec = $nRecCount; } ?>
	<span class="phpmaker">Registros del <?php echo $nStartRec; ?> al <?php echo $nStopRec; ?> de un total <?php echo $nTotalRecs; ?></span>
<?php } else { ?>
	<span class="phpmaker">
	La consulta no encontro registros con los filtros indicados. <br />
	Favor de intenterlo nuevamente.
	</span>
<?php } ?>
		</td>
<?php if ($nTotalRecs > 0) { ?>
		<td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="right" valign="top" nowrap><span class="phpmaker">Registros por pagina&nbsp;
<select class="form-select form-select-sm" name="RecPerPage" onChange="this.form.pageno.value = 1;this.form.submit();" class="phpmaker">
<option value="10"<?php if ($nDisplayRecs == 10) { echo " selected";  }?>>10</option>
<option value="20"<?php if ($nDisplayRecs == 20) { echo " selected";  }?>>20</option>
<option value="50"<?php if ($nDisplayRecs == 50) { echo " selected";  }?>>50</option>
<option value="100"<?php if ($nDisplayRecs == 100) { echo " selected";  }?>>100</option>
<option value="ALL"<?php if (@$_SESSION["archivos_rosarito_RecPerPage"] == -1) { echo " selected";  }?>>Todos</option>
</select>
		</span></td>
<?php } ?>
	</tr>
</table>
</form>
<?php } ?>
</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
					

					</div>
                <!-- container-fluid -->
            </div>
                <!-- End Page-content -->

                <?php #include 'layouts/footer.php'; ?>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        

        <?php #include 'layouts/customizer.php'; ?>

        <?php include 'layouts/vendor-scripts.php'; ?>
        <!-- Sweet Alerts js -->
        <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
        
        <!-- App js -->
        <!--<script src="assets/js/app.js"></script>-->
    </body>

</html>
<?php

// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
<?php

//-------------------------------------------------------------------------------
// Function SetUpDisplayRecs
// - Set up Number of Records displayed per page based on Form element RecPerPage
// - Variables setup: nDisplayRecs

function SetUpDisplayRecs()
{
	global $nStartRec;
	global $nDisplayRecs;
	global $nTotalRecs;
	$sWrk = @$_GET["RecPerPage"];
	if ($sWrk <> "") {
		if (is_numeric($sWrk)) {
			$nDisplayRecs = $sWrk;
		}else{
			if (strtoupper($sWrk) == "ALL") { // Display All Records
				$nDisplayRecs = -1;
			}else{
				$nDisplayRecs = 20;  // Non-numeric, Load Default
			}
		}
		$_SESSION["archivos_rosarito_percep_deduc_RecPerPage"] = $nDisplayRecs; // Save to Session

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["archivos_rosarito_percep_deduc_REC"] = $nStartRec;
	}else{
		if (@$_SESSION["archivos_rosarito_percep_deduc_RecPerPage"] <> "") {
			$nDisplayRecs = $_SESSION["archivos_rosarito_percep_deduc_RecPerPage"]; // Restore from Session
		}else{
			$nDisplayRecs = 20; // Load Default
		}
	}
}

//-------------------------------------------------------------------------------
// Function SetUpMasterDetail
// - Set up Master Detail criteria based on querystring parameter key_m
// - Variables setup: sKeyMaster, sDbWhereMaster, Session("TblVar_masterkey")

function SetUpMasterDetail()
{
	global $sDbWhereMaster;
	global $sDbWhereDetail;
	global $sKeyMaster;
	global $nStartRec;
	global $x_arcdet_archivoID;

	// Get the keys for master table
	if (strlen(@$_GET["showmaster"]) > 0) {

		// Reset start record counter (new master key)
		$nStartRec = 1;
		$_SESSION["archivos_rosarito_percep_deduc_REC"] = $nStartRec;
		$sDbWhereMaster = "";
		$sDbWhereDetail = "";	
		$x_arcdet_archivoID = @$_GET["arcdet_archivoID"]; // Load Parameter from QueryString
		$sTmp = (!(function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())) ? addslashes($x_arcdet_archivoID) : $x_arcdet_archivoID;
		if ($sDbWhereMaster <> "") { $sDbWhereMaster .= " AND "; }
		$sDbWhereMaster .= "`arc_archivoID` =  " . $sTmp . "";
		if ($sDbWhereDetail <> "") { $sDbWhereDetail .= " AND "; }
		$sDbWhereDetail .= "`arcdet_archivoID` =  " . $sTmp  . "";
		$_SESSION["archivos_rosarito_percep_deduc_MasterKey_arcdet_archivoID"] = $sTmp; // Save Master Key Value
		$_SESSION["archivos_rosarito_percep_deduc_MasterWhere"] = $sDbWhereMaster;
		$_SESSION["archivos_rosarito_percep_deduc_DetailWhere"] = $sDbWhereDetail;
	}else{
		$sDbWhereMaster = @$_SESSION["archivos_rosarito_percep_deduc_MasterWhere"];
		$sDbWhereDetail = @$_SESSION["archivos_rosarito_percep_deduc_DetailWhere"];
	}
}

//-------------------------------------------------------------------------------
// Function SetUpInlineEdit
// - Set up Inline Edit parameters based on querystring parameters a & key
// - Variables setup: sAction, sKey, Session("Proj_InlineEdit_Key")

function SetUpInlineEdit($conn)
{
	global $x_arcdet_archivoDetalleID;
	#echo "<br />DENTRO FUNCION SetUpInlineEdit";
	// Get the keys for master table
	if (strlen(@$_GET["a"]) > 0) {
		#echo "<br />DENTRO GET[a]";
		$sAction = @$_GET["a"];
		if (strtoupper($sAction) == "EDIT") { // Change to Inline Edit Mode
			$bInlineEdit = true;
			if (strlen(@$_GET["arcdet_archivoDetalleID"]) > 0) {
				$x_arcdet_archivoDetalleID = $_GET["arcdet_archivoDetalleID"];
			}else{
				$bInlineEdit = false;
			}
			if ($bInlineEdit) {
				if (LoadData($conn)) {
					$_SESSION["project_archivos_rosarito_Key_arcdet_archivoDetalleID"] = $x_arcdet_archivoDetalleID; // Set up Inline Edit key
				}
			}
		}
		elseif (strtoupper($sAction) == "CANCEL")  // Switch out of Inline Edit Mode
		{
			$_SESSION["project_archivos_rosarito_Key_arcdet_archivoDetalleID"] = ""; // Clear Inline Edit key
		}
	}
	else
	{
		#echo "<br />DENTRO ELSE GET[a]";
		$sAction = @$_POST["a_list"];
		if (strtoupper($sAction) == "UPDATE") { // Update Record
			#echo "<br />DENTRO UPDATE";
			// Get fields from form
			global $x_arcdet_archivoDetalleID;
			$x_arcdet_archivoDetalleID = @$_POST["x_arcdet_archivoDetalleID"];
			global $x_arcdet_archivoID;
			$x_arcdet_archivoID = @$_POST["x_arcdet_archivoID"];
			global $x_arcdet_folio;
			$x_arcdet_folio = @$_POST["x_arcdet_folio"];
			global $x_arcdet_tipo;
			$x_arcdet_tipo = @$_POST["x_arcdet_tipo"];
			global $x_arcdet_clave;
			$x_arcdet_clave = @$_POST["x_arcdet_clave"];
			global $x_arcdet_concepto;
			$x_arcdet_concepto = @$_POST["x_arcdet_concepto"];
			global $x_arcdet_importe;
			$x_arcdet_importe = @$_POST["x_arcdet_importe"];
			global $x_arcdet_perc_deduc;
			$x_arcdet_perc_deduc = @$_POST["x_arcdet_perc_deduc"];
			global $x_arcdet_seriefolio;
			$x_arcdet_seriefolio = @$_POST["x_arcdet_seriefolio"];
			global $x_arcdet_rfc_vita;
			$x_arcdet_rfc_vita = @$_POST["x_arcdet_rfc_vita"];
			global $x_arcdet_nombre;
			$x_arcdet_nombre = @$_POST["x_arcdet_nombre"];
			#echo "<br />".$_SESSION["project_archivos_rosarito_Key_arcdet_archivoDetalleID"]." == ".$x_arcdet_archivoDetalleID;
			if ($_SESSION["project_archivos_rosarito_Key_arcdet_archivoDetalleID"] == $x_arcdet_archivoDetalleID) {
				#echo "<br />DENTRO InlineEditData";
				if (InlineEditData($conn)) {
					$_SESSION["ewmsg"] = "Registro Actualizado.";
				}
			}
		}
		$_SESSION["project_archivos_rosarito_Key_arcdet_archivoDetalleID"] = ""; // Clear Inline Edit key
	}
}

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!(function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`arcdet_folio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`arcdet_tipo` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`arcdet_clave` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`arcdet_concepto` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`arcdet_perc_deduc` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`arcdet_seriefolio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`arcdet_rfc_vita` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`arcdet_nombre` LIKE '%" . $sKeyword . "%' OR ";
	if (substr($BasicSearchSQL, -4) == " OR ") { $BasicSearchSQL = substr($BasicSearchSQL, 0, strlen($BasicSearchSQL)-4); }
	return $BasicSearchSQL;
}

//-------------------------------------------------------------------------------
// Function SetUpBasicSearch
// - Set up Basic Search parameter based on form elements pSearch & pSearchType
// - Variables setup: sSrchBasic

function SetUpBasicSearch()
{
	global $sSrchBasic;
	$sSearch = (!(function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())) ? addslashes(@$_GET["psearch"]) : @$_GET["psearch"];
	$sSearchType = @$_GET["psearchtype"];
	if ($sSearch <> "") {
		if ($sSearchType <> "") {
			while (strpos($sSearch, "  ") != false) {
				$sSearch = str_replace("  ", " ",$sSearch);
			}
			$arKeyword = split(" ", trim($sSearch));
			foreach ($arKeyword as $sKeyword)
			{
				$sSrchBasic .= "(" . BasicSearchSQL($sKeyword) . ") " . $sSearchType . " ";
			}
		}
		else
		{
			$sSrchBasic = BasicSearchSQL($sSearch);
		}
	}
	if (substr($sSrchBasic, -4) == " OR ") { $sSrchBasic = substr($sSrchBasic, 0, strlen($sSrchBasic)-4); }
	if (substr($sSrchBasic, -5) == " AND ") { $sSrchBasic = substr($sSrchBasic, 0, strlen($sSrchBasic)-5); }
}

//-------------------------------------------------------------------------------
// Function SetUpSortOrder
// - Set up Sort parameters based on Sort Links clicked
// - Variables setup: sOrderBy, Session("Table_OrderBy"), Session("Table_Field_Sort")

function SetUpSortOrder()
{
	global $sOrderBy;
	global $sDefaultOrderBy;

	// Check for an Order parameter
	if (strlen(@$_GET["order"]) > 0) {
		$sOrder = @$_GET["order"];

		// Field arcdet_archivoID
		if ($sOrder == "arcdet_archivoID") {
			$sSortField = "`arcdet_archivoID`";
			$sLastSort = @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_archivoID_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_archivoID_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_archivoID_Sort"] <> "") { @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_archivoID_Sort"] = ""; }
		}

		// Field arcdet_folio
		if ($sOrder == "arcdet_folio") {
			$sSortField = "`arcdet_folio`";
			$sLastSort = @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_folio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_folio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_folio_Sort"] <> "") { @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_folio_Sort"] = ""; }
		}

		// Field arcdet_tipo
		if ($sOrder == "arcdet_tipo") {
			$sSortField = "`arcdet_tipo`";
			$sLastSort = @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_tipo_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_tipo_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_tipo_Sort"] <> "") { @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_tipo_Sort"] = ""; }
		}

		// Field arcdet_clave
		if ($sOrder == "arcdet_clave") {
			$sSortField = "`arcdet_clave`";
			$sLastSort = @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_clave_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_clave_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_clave_Sort"] <> "") { @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_clave_Sort"] = ""; }
		}

		// Field arcdet_concepto
		if ($sOrder == "arcdet_concepto") {
			$sSortField = "`arcdet_concepto`";
			$sLastSort = @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_concepto_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_concepto_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_concepto_Sort"] <> "") { @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_concepto_Sort"] = ""; }
		}

		// Field arcdet_importe
		if ($sOrder == "arcdet_importe") {
			$sSortField = "`arcdet_importe`";
			$sLastSort = @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_importe_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_importe_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_importe_Sort"] <> "") { @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_importe_Sort"] = ""; }
		}

		// Field arcdet_perc_deduc
		if ($sOrder == "arcdet_perc_deduc") {
			$sSortField = "`arcdet_perc_deduc`";
			$sLastSort = @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_perc_deduc_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_perc_deduc_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_perc_deduc_Sort"] <> "") { @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_perc_deduc_Sort"] = ""; }
		}

		// Field arcdet_seriefolio
		if ($sOrder == "arcdet_seriefolio") {
			$sSortField = "`arcdet_seriefolio`";
			$sLastSort = @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_seriefolio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_seriefolio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_seriefolio_Sort"] <> "") { @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_seriefolio_Sort"] = ""; }
		}

		// Field arcdet_rfc_vita
		if ($sOrder == "arcdet_rfc_vita") {
			$sSortField = "`arcdet_rfc_vita`";
			$sLastSort = @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_rfc_vita_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_rfc_vita_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_rfc_vita_Sort"] <> "") { @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_rfc_vita_Sort"] = ""; }
		}

		// Field arcdet_nombre
		if ($sOrder == "arcdet_nombre") {
			$sSortField = "`arcdet_nombre`";
			$sLastSort = @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_nombre_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_nombre_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_nombre_Sort"] <> "") { @$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_nombre_Sort"] = ""; }
		}
		$_SESSION["archivos_rosarito_percep_deduc_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["archivos_rosarito_percep_deduc_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["archivos_rosarito_percep_deduc_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["archivos_rosarito_percep_deduc_OrderBy"] = $sOrderBy;
	}
}

//-------------------------------------------------------------------------------
// Function SetUpStartRec
//- Set up Starting Record parameters based on Pager Navigation
// - Variables setup: nStartRec

function SetUpStartRec()
{

	// Check for a START parameter
	global $nStartRec;
	global $nDisplayRecs;
	global $nTotalRecs;
	if (strlen(@$_GET["start"]) > 0) {
		$nStartRec = @$_GET["start"];
		$_SESSION["archivos_rosarito_percep_deduc_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["archivos_rosarito_percep_deduc_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["archivos_rosarito_percep_deduc_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["archivos_rosarito_percep_deduc_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["archivos_rosarito_percep_deduc_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["archivos_rosarito_percep_deduc_REC"] = $nStartRec;
		}
	}
}

//-------------------------------------------------------------------------------
// Function ResetCmd
// - Clear list page parameters
// - RESET: reset search parameters
// - RESETALL: reset search & master/detail parameters
// - RESETSORT: reset sort parameters

function ResetCmd()
{

	// Get Reset Cmd
	if (strlen(@$_GET["cmd"]) > 0) {
		$sCmd = @$_GET["cmd"];

		// Reset Search Criteria
		if (strtoupper($sCmd) == "RESET") {
			$sSrchWhere = "";
			$_SESSION["archivos_rosarito_percep_deduc_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["archivos_rosarito_percep_deduc_searchwhere"] = $sSrchWhere;
			$_SESSION["archivos_rosarito_percep_deduc_MasterWhere"] = ""; // Clear master criteria
			$sDbWhereMaster = "";
			$_SESSION["archivos_rosarito_percep_deduc_DetailWhere"] = ""; // Clear detail criteria
			$sDbWhereDetail = "";
		$_SESSION["archivos_rosarito_percep_deduc_MasterKey_arcdet_archivoID"] = ""; // Clear Master Key Value
			$_SESSION["project_archivos_rosarito_InlineEdit_Key"] = ""; // Clear Inline Edit key

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["archivos_rosarito_percep_deduc_OrderBy"] = $sOrderBy;
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_archivoID_Sort"] <> "") { $_SESSION["archivos_rosarito_percep_deduc_x_arcdet_archivoID_Sort"] = ""; }
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_folio_Sort"] <> "") { $_SESSION["archivos_rosarito_percep_deduc_x_arcdet_folio_Sort"] = ""; }
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_tipo_Sort"] <> "") { $_SESSION["archivos_rosarito_percep_deduc_x_arcdet_tipo_Sort"] = ""; }
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_clave_Sort"] <> "") { $_SESSION["archivos_rosarito_percep_deduc_x_arcdet_clave_Sort"] = ""; }
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_concepto_Sort"] <> "") { $_SESSION["archivos_rosarito_percep_deduc_x_arcdet_concepto_Sort"] = ""; }
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_importe_Sort"] <> "") { $_SESSION["archivos_rosarito_percep_deduc_x_arcdet_importe_Sort"] = ""; }
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_perc_deduc_Sort"] <> "") { $_SESSION["archivos_rosarito_percep_deduc_x_arcdet_perc_deduc_Sort"] = ""; }
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_seriefolio_Sort"] <> "") { $_SESSION["archivos_rosarito_percep_deduc_x_arcdet_seriefolio_Sort"] = ""; }
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_rfc_vita_Sort"] <> "") { $_SESSION["archivos_rosarito_percep_deduc_x_arcdet_rfc_vita_Sort"] = ""; }
			if (@$_SESSION["archivos_rosarito_percep_deduc_x_arcdet_nombre_Sort"] <> "") { $_SESSION["archivos_rosarito_percep_deduc_x_arcdet_nombre_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["archivos_rosarito_percep_deduc_REC"] = $nStartRec;
	}
}
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
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())) ? stripslashes($x_arcdet_archivoDetalleID) : $x_arcdet_archivoDetalleID;
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
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
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
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables

function InlineEditData($conn)
{
	global $x_arcdet_archivoDetalleID;
	$sSql = "SELECT * FROM `archivos_rosarito_percep_deduc`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())) ? stripslashes($x_arcdet_archivoDetalleID) : $x_arcdet_archivoDetalleID;	
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
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$bInlineEditData = false; // Update Failed
	}else{
		
		if(isset($GLOBALS["x_arcdet_concepto"]) && $GLOBALS["x_arcdet_concepto"]!=""){
		$datos = explode("|", $GLOBALS["x_arcdet_concepto"]);
		$arcdet_tipo = $datos[0];
		$arcdet_clave = $datos[1];
		$det_concepto = $datos[2];
		$theValue = (!(function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())) ? addslashes($arcdet_tipo) : $arcdet_tipo; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`arcdet_tipo`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())) ? addslashes($arcdet_clave) : $arcdet_clave; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`arcdet_clave`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())) ? addslashes($det_concepto) : $det_concepto; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`arcdet_concepto`"] = $theValue;
		}
		$theValue = ($GLOBALS["x_arcdet_importe"] != "") ? " '" . doubleval($GLOBALS["x_arcdet_importe"]) . "'" : "NULL";
		$fieldList["`arcdet_importe`"] = $theValue;

		// update
		$sSql = "UPDATE `archivos_rosarito_percep_deduc` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		#echo "<>sSql: ".$sSql;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
		$bInlineEditData = true; // Update Successful
	}
	return $bInlineEditData;
}
?>
