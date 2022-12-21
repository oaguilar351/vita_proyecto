<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php
ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
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
$x_Emi_Clave = Null; 
$ox_Emi_Clave = Null;
$x_ser_Status = Null; 
$ox_ser_Status = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=vit_series.xls');
}
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/phpmkrfn.php") ?>
<?php
$arRecKey = array();

// Load Key Parameters
$sKey = "";
$bSingleDelete = true;
$x_ser_SerieID = @$_GET["ser_SerieID"];
if (!empty($x_ser_SerieID)) {
	if ($sKey <> "") { $sKey .= ","; }
	$sKey .= $x_ser_SerieID;
}else{
	$bSingleDelete = false;
}
if (!$bSingleDelete) {
	$sKey = @$_POST["key_d"];
}
if (!is_array($sKey)) {
	if (strlen($sKey) > 0) {	
		$arRecKey = explode(",", $sKey);
	}
}else {
	$sKey = implode(",", $sKey);
	$arRecKey = explode(",", $sKey);
}
$sKey = implode(",", $arRecKey);
$i = 0;
$sDbWhere = "";
while ($i < count($arRecKey)){
	$sDbWhere .= "(";

	// Remove spaces
	$sRecKey = trim($arRecKey[$i+0]);
	$sRecKey = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($sRecKey) : $sRecKey ;

	// Build the SQL
	$sDbWhere .= "`ser_SerieID`=" . $sRecKey . " AND ";
	if (substr($sDbWhere, -5) == " AND ") { $sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5) . ") OR "; }
	$i += 1;
}
if (substr($sDbWhere, -4) == " OR ") { $sDbWhere = substr($sDbWhere, 0 , strlen($sDbWhere)-4); }

// Get action
$sAction = @$_GET["a_delete"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{		
	case "D": // Delete
		if (DeleteData($sDbWhere,$conn)) {			
			$_SESSION["ewmsg"] = "Registro eliminado con exito.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: series_listado.php");
			exit();
		}
		break;
}
?>
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
$nDisplayRecs = 10;
$nRecRange = 10;

// Set up records per page dynamically
SetUpDisplayRecs();

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// Handle Reset Command
ResetCmd();

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
	$_SESSION["vit_series_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["vit_series_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["vit_series_searchwhere"];
}

// Build SQL
$sSql = "SELECT * FROM `vit_series`";

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";

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

//echo $sSql; // Uncomment to show SQL for debugging
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
if (EW_this.x_ser_Prefijo && !EW_hasValue(EW_this.x_ser_Prefijo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_ser_Prefijo, "TEXT", "Please enter required field - SubGrupo"))
		return false;
}
return true;
}

//-->
</script>
<?php } ?>
<?php

// Set up Record Set
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
<head>
        
        <title>Series | VitaInsumos</title>
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
                                <h4 class="mb-sm-0">Series</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Modulos</a></li>
                                        <li class="breadcrumb-item active">Series</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<script>
$(document).ready(function(){
	Swal.fire({
		icon: 'success',
		title: '<?php echo $_SESSION["ewmsg"]; ?>',
		showConfirmButton: false,
		timer: 1500,
		showCloseButton: true
	});
});		
</script>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>			
					<div class="row">
                        <div class="col-lg-12">
                            <div class="card" id="leadsList">
                                <div class="card-header border-0">

                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm-3">
                                            <div class="search-box">
												<form action="series_listado.php">
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
												<?php if(@$_SESSION["vit_series_OrderBy"]!=""){ ?>
												<a class="btn btn-light" href="series_listado.php?cmd=resetsort" title="Quitar Orden"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>X</a>
												<?php } ?>											
												<?php if(@$sWhere!="" || @$sSrchAdvanced!="" && @$_SESSION["vit_series_OrderBy"]==""){ ?>
												<a class="btn btn-light" href="series_listado.php?cmd=reset" title="Quitar Filtros"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>F</a>
												<?php } ?>
                                               <button type="button" class="btn btn-info" data-bs-toggle="offcanvas"
                                                    href="#offcanvasExample"> Nuevo </button>                                  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <div class="table-responsive table-card">
<?php if ($nTotalRecs > 0)  { ?>
<form name="vit_serieslist" id="vit_serieslist" action="series_listado.php" method="post">
<table class="table align-middle" id="customerTable">
		<thead class="table-light">
		 <tr>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Emisor
<?php }else{ ?>
	<a href="series_listado.php?order=<?php echo urlencode("Emi_Clave"); ?>">Emisor<?php if (@$_SESSION["vit_series_x_Emi_Clave_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_series_x_Emi_Clave_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
SubGrupo
<?php }else{ ?>
	<a href="series_listado.php?order=<?php echo urlencode("ser_Prefijo"); ?>">SubGrupo&nbsp;(*)<?php if (@$_SESSION["vit_series_x_ser_Prefijo_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_series_x_ser_Prefijo_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Ejercicio
<?php }else{ ?>
	<a href="series_listado.php?order=<?php echo urlencode("ser_Ejercicio"); ?>">Ejercicio<?php if (@$_SESSION["vit_series_x_ser_Ejercicio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_series_x_ser_Ejercicio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Periodo
<?php }else{ ?>
	<a href="series_listado.php?order=<?php echo urlencode("ser_Periodo"); ?>">Periodo<?php if (@$_SESSION["vit_series_x_ser_Periodo_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_series_x_ser_Periodo_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Numero
<?php }else{ ?>
	<a href="series_listado.php?order=<?php echo urlencode("ser_Numero"); ?>">Numero&nbsp;(*)<?php if (@$_SESSION["vit_series_x_ser_Numero_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_series_x_ser_Numero_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Serie
<?php }else{ ?>
	<a href="series_listado.php?order=<?php echo urlencode("ser_Serie"); ?>">Serie&nbsp;(*)<?php if (@$_SESSION["vit_series_x_ser_Serie_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_series_x_ser_Serie_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>		
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Status
<?php }else{ ?>
	<a href="series_listado.php?order=<?php echo urlencode("ser_Status"); ?>">Status<?php if (@$_SESSION["vit_series_x_ser_Status_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_series_x_ser_Status_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
<?php if ($sExport == "") { ?>
<!--<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>-->
<td>&nbsp;</td>
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
		$x_ser_SerieID = $row["ser_SerieID"];
		$x_ser_Prefijo = $row["ser_Prefijo"];
		$x_ser_Ejercicio = $row["ser_Ejercicio"];
		$x_ser_Periodo = $row["ser_Periodo"];
		$x_ser_Numero = $row["ser_Numero"];
		$x_ser_Serie = $row["ser_Serie"];
		$x_Emi_Clave = $row["Emi_Clave"];
		$x_ser_Status = $row["ser_Status"];
	$bEditRow = (($_SESSION["vita_proyecto_Key_ser_SerieID"] == $x_ser_SerieID) && ($nEditRowCnt == 0));
	if ($bEditRow) {
		$nEditRowCnt++;
		$sItemRowClass = " bgcolor=\"#fdedec\"";
	}
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
<?php if ($bEditRow) { ?>
<input type="hidden" id="x_ser_SerieID" name="x_ser_SerieID" value="<?php echo htmlspecialchars(@$x_ser_SerieID); ?>">
<?php } ?>
		<!-- Emi_Clave -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php
$x_Emi_ClaveList = "<select class=\"form-control\" name=\"x_Emi_Clave\">";
$x_Emi_ClaveList .= "<option value=''>Favor de elegir</option>";
$sSqlWrk = "SELECT `Emi_Clave`, `Emi_NomCorto` FROM `Vit_Emisor`";
$sSqlWrk .= " ORDER BY `Emi_NomCorto` Asc";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_Emi_ClaveList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["Emi_Clave"] == @$x_Emi_Clave) {
			$x_Emi_ClaveList .= "' selected";
		}
		$x_Emi_ClaveList .= ">" . $datawrk["Emi_NomCorto"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_Emi_ClaveList .= "</select>";
echo $x_Emi_ClaveList;
?>
<?php }else{ ?>
<?php
if ((!is_null($x_Emi_Clave)) && ($x_Emi_Clave <> "")) {
	$sSqlWrk = "SELECT `Emi_NomCorto` FROM `Vit_Emisor`";
	$sTmp = $x_Emi_Clave;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `Emi_Clave` = '" . $sTmp . "'";
	$sSqlWrk .= " ORDER BY `Emi_NomCorto` Asc";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["Emi_NomCorto"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_Emi_Clave = $x_Emi_Clave; // Backup Original Value
$x_Emi_Clave = $sTmp;
?>
<?php echo $x_Emi_Clave; ?>
<?php $x_Emi_Clave = $ox_Emi_Clave; // Restore Original Value ?>
<?php } ?>
</span></td>
		<!-- ser_Prefijo -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<input class="form-control" type="text" name="x_ser_Prefijo" id="x_ser_Prefijo" size="25" maxlength="60" value="<?php echo htmlspecialchars(@$x_ser_Prefijo) ?>">
<?php }else{ ?>
<?php echo $x_ser_Prefijo; ?>
<?php } ?>
</span></td>
		<!-- ser_Ejercicio -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php
$x_ser_EjercicioList = "<select class=\"form-control\" name=\"x_ser_Ejercicio\">";
$x_ser_EjercicioList .= "<option value=''>Favor de elegir</option>";
	$x_ser_EjercicioList .= "<option value=\"2015\"";
	if (@$x_ser_Ejercicio == "2015") {
		$x_ser_EjercicioList .= " selected";
	}
	$x_ser_EjercicioList .= ">" . "2015" . "</option>";
	$x_ser_EjercicioList .= "<option value=\"2016\"";
	if (@$x_ser_Ejercicio == "2016") {
		$x_ser_EjercicioList .= " selected";
	}
	$x_ser_EjercicioList .= ">" . "2016" . "</option>";
	$x_ser_EjercicioList .= "<option value=\"2017\"";
	if (@$x_ser_Ejercicio == "2017") {
		$x_ser_EjercicioList .= " selected";
	}
	$x_ser_EjercicioList .= ">" . "2017" . "</option>";
	$x_ser_EjercicioList .= "<option value=\"2018\"";
	if (@$x_ser_Ejercicio == "2018") {
		$x_ser_EjercicioList .= " selected";
	}
	$x_ser_EjercicioList .= ">" . "2018" . "</option>";
	$x_ser_EjercicioList .= "<option value=\"2019\"";
	if (@$x_ser_Ejercicio == "2019") {
		$x_ser_EjercicioList .= " selected";
	}
	$x_ser_EjercicioList .= ">" . "2019" . "</option>";
	$x_ser_EjercicioList .= "<option value=\"2020\"";
	if (@$x_ser_Ejercicio == "2020") {
		$x_ser_EjercicioList .= " selected";
	}
	$x_ser_EjercicioList .= ">" . "2020" . "</option>";
	$x_ser_EjercicioList .= "<option value=\"2021\"";
	if (@$x_ser_Ejercicio == "2021") {
		$x_ser_EjercicioList .= " selected";
	}
	$x_ser_EjercicioList .= ">" . "2021" . "</option>";
	$x_ser_EjercicioList .= "<option value=\"2022\"";
	if (@$x_ser_Ejercicio == "2022") {
		$x_ser_EjercicioList .= " selected";
	}
	$x_ser_EjercicioList .= ">" . "2022" . "</option>";
	$x_ser_EjercicioList .= "<option value=\"2023\"";
	if (@$x_ser_Ejercicio == "2023") {
		$x_ser_EjercicioList .= " selected";
	}
	$x_ser_EjercicioList .= ">" . "2023" . "</option>";
	$x_ser_EjercicioList .= "<option value=\"2024\"";
	if (@$x_ser_Ejercicio == "2024") {
		$x_ser_EjercicioList .= " selected";
	}
	$x_ser_EjercicioList .= ">" . "2024" . "</option>";
$x_ser_EjercicioList .= "</select>";
echo $x_ser_EjercicioList;
?>
<?php }else{ ?>
<?php
switch ($x_ser_Ejercicio) {
	case "2015":
		$sTmp = "2015";
		break;
	case "2016":
		$sTmp = "2016";
		break;
	case "2017":
		$sTmp = "2017";
		break;
	case "2018":
		$sTmp = "2018";
		break;
	case "2019":
		$sTmp = "2019";
		break;
	case "2020":
		$sTmp = "2020";
		break;
	case "2021":
		$sTmp = "2021";
		break;
	case "2022":
		$sTmp = "2022";
		break;
	case "2023":
		$sTmp = "2023";
		break;
	case "2024":
		$sTmp = "2024";
		break;
	default:
		$sTmp = "";
}
$ox_ser_Ejercicio = $x_ser_Ejercicio; // Backup Original Value
$x_ser_Ejercicio = $sTmp;
?>
<?php echo $x_ser_Ejercicio; ?>
<?php $x_ser_Ejercicio = $ox_ser_Ejercicio; // Restore Original Value ?>
<?php } ?>
</span></td>
		<!-- ser_Periodo -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php
$x_ser_PeriodoList = "<select class=\"form-control\" name=\"x_ser_Periodo\">";
$x_ser_PeriodoList .= "<option value=''>Favor de elegir</option>";
	$x_ser_PeriodoList .= "<option value=\"CAT\"";
	if (@$x_ser_Periodo == "CAT") {
		$x_ser_PeriodoList .= " selected";
	}
	$x_ser_PeriodoList .= ">" . "CATORCENA" . "</option>";
	$x_ser_PeriodoList .= "<option value=\"QUI\"";
	if (@$x_ser_Periodo == "QUI") {
		$x_ser_PeriodoList .= " selected";
	}
	$x_ser_PeriodoList .= ">" . "QUINCENAL" . "</option>";
	$x_ser_PeriodoList .= "<option value=\"MES\"";
	if (@$x_ser_Periodo == "MES") {
		$x_ser_PeriodoList .= " selected";
	}
	$x_ser_PeriodoList .= ">" . "MENSUAL" . "</option>";
	$x_ser_PeriodoList .= "<option value=\"EXT\"";
	if (@$x_ser_Periodo == "EXT") {
		$x_ser_PeriodoList .= " selected";
	}
	$x_ser_PeriodoList .= ">" . "EXTRAORDINARIA" . "</option>";
$x_ser_PeriodoList .= "</select>";
echo $x_ser_PeriodoList;
?>
<?php }else{ ?>
<?php
switch ($x_ser_Periodo) {
	case "CAT":
		$sTmp = "CATORCENA";
		break;
	case "QUI":
		$sTmp = "QUINCENAL";
		break;
	case "MES":
		$sTmp = "MENSUAL";
		break;
	case "EXT":
		$sTmp = "EXTRAORDINARIA";
		break;
	default:
		$sTmp = "";
}
$ox_ser_Periodo = $x_ser_Periodo; // Backup Original Value
$x_ser_Periodo = $sTmp;
?>
<?php echo $x_ser_Periodo; ?>
<?php $x_ser_Periodo = $ox_ser_Periodo; // Restore Original Value ?>
<?php } ?>
</span></td>
		<!-- ser_Numero -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<input class="form-control" type="text" name="x_ser_Numero" id="x_ser_Numero" size="10" maxlength="9" value="<?php echo htmlspecialchars(@$x_ser_Numero) ?>">
<?php }else{ ?>
<?php echo $x_ser_Numero; ?>
<?php } ?>
</span></td>
		<!-- ser_Serie -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<input class="form-control" type="text" name="x_ser_Serie" id="x_ser_Serie" size="25" maxlength="150" value="<?php echo htmlspecialchars(@$x_ser_Serie) ?>" readonly>
<?php }else{ ?>
<?php echo $x_ser_Serie; ?>
<?php } ?>
</span></td>		
		<!-- ser_Status -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<input type="radio" name="x_ser_Status"<?php if (@$x_ser_Status == "A") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("A"); ?>">
<?php echo "Activo"; ?>
<?php echo EditOptionSeparator(0); ?><br />
<input type="radio" name="x_ser_Status"<?php if (@$x_ser_Status == "I") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("I"); ?>">
<?php echo "Inactivo"; ?>
<?php echo EditOptionSeparator(1); ?>
<?php }else{ ?>
<?php
switch ($x_ser_Status) {
	case "A":
		$sTmp = '<span class="badge badge-soft-success">Activo</span>';
		break;
	case "I":
		$sTmp = '<span class="badge badge-soft-danger">Inactivo</span>';
		break;
	default:
		$sTmp = "";
}
$ox_ser_Status = $x_ser_Status; // Backup Original Value
$x_ser_Status = $sTmp;
?>
<?php echo $x_ser_Status; ?>
<?php $x_ser_Status = $ox_ser_Status; // Restore Original Value ?>
<?php } ?>
</span></td>
<td><span class="phpmaker">
<?php if ($_SESSION["vita_proyecto_Key_ser_SerieID"] == $x_ser_SerieID) { ?>
<a href="" onClick="if (EW_checkMyForm(document.vit_serieslist)) document.vit_serieslist.submit();return false;">Actualizar</a>&nbsp;<a href="series_listado.php?a=cancel">Cancelar</a>
<input type="hidden" name="a_list" value="update">
<?php } else { ?>
<div class="dropdown">
		<a href="#" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="false">
			<i class="ri-more-2-fill"></i>
		</a>
		
		<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
			<li>
			<a class="dropdown-item" href="<?php if ($x_ser_SerieID <> "") {echo "series_listado.php?a=edit&ser_SerieID=" . urlencode($x_ser_SerieID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Editar</a>
			</li>
			<li>
			<a class="dropdown-item" onclick = "if(!confirm('Confirmar que desea eliminar el registro?')) return false;" href="<?php if ($x_ser_SerieID <> "") {echo "series_listado.php?ser_SerieID=" . urlencode($x_ser_SerieID)."&a_delete=D"; } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Eliminar</a>	
			</li>
		</ul>
	</div>
<?php } ?>
</span></td>
	</tr>
<?php
	}
}
?>
</tbody>
</table>
</form>
</div>
<?php } ?>
<div class="d-flex justify-content-end mt-3">
	<div class="pagination-wrap hstack gap-2">
<?php if ($sExport == "") { ?>
<form action="series_listado.php" name="ewpagerform" id="ewpagerform">
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
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpmaker">Pagina &nbsp;</span></td>
<!--first page button-->
	<?php if ($nStartRec == 1) { ?>
	<td><a class="page-item pagination-prev disabled" href="#">|<</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-prev" href="series_listado.php?start=1">|<</a></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($PrevStart == $nStartRec) { ?>
	<td><a class="page-item pagination-prev disabled"><</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-prev" href="series_listado.php?start=<?php echo $PrevStart; ?>"><</a></td>
	<?php } ?>
<!--current page number-->
	<td><input class="form-control form-control-sm" type="text" name="pageno" value="<?php echo intval(($nStartRec-1)/$nDisplayRecs+1); ?>" size="4"></td>
<!--next page button-->
	<?php if ($NextStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">></a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="series_listado.php?start=<?php echo $NextStart; ?>">></a></td>
	<?php  } ?>
<!--last page button-->
	<?php if ($LastStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">>|</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="series_listado.php?start=<?php echo $LastStart; ?>">>|</a></td>
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
<option value="ALL"<?php if (@$_SESSION["vit_comprobantes_RecPerPage"] == -1) { echo " selected";  }?>>Todos</option>
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
<script type="text/javascript">
<!--
function EW_checkMyFormN(EW_this) {
if (EW_this.n_ser_Prefijo && !EW_hasValue(EW_this.n_ser_Prefijo, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.n_ser_Prefijo, "TEXT", "Favor de ingresar - SubGrupo"))
		return false;
}
return true;
}

//-->
</script>
<!------INICIO FILTROS--------->
									<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample"
                                        aria-labelledby="offcanvasExampleLabel">
                                        <div class="offcanvas-header bg-light">
                                            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Nueva - Serie</h5>
                                            <button type="button" class="btn-close text-reset"
                                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <!--end offcanvas-header-->
										<form name="vit_seriesadd" id="vit_seriesadd" action="vit_seriesadd.php" method="post" onSubmit="return EW_checkMyFormN(this);">
                                        <!--<form action="" class="d-flex flex-column justify-content-end h-100">-->
                                            <div class="offcanvas-body">
												<div class="mb-4">
                                                    <label for="RFC" class="form-label text-muted text-uppercase fw-semibold mb-3">Emisor</label>                     
													<?php
													$x_Emi_ClaveList = "<select class=\"form-select form-select\" name=\"n_Emi_Clave\">";
													$x_Emi_ClaveList .= "<option value=''>Favor de elegir</option>";
													$sSqlWrk = "SELECT `Emi_Clave`, `Emi_NomCorto` FROM `Vit_Emisor`";
													$sSqlWrk .= " ORDER BY `Emi_NomCorto` Asc";
													$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
													if ($rswrk) {
														$rowcntwrk = 0;
														while ($datawrk = phpmkr_fetch_array($rswrk)) {
															$x_Emi_ClaveList .= "<option value=\"" . htmlspecialchars($datawrk["Emi_Clave"]) . "\"";
															if ($datawrk["Emi_Clave"] == @$x_Emi_Clave) {
																$x_Emi_ClaveList .= "' selected";
															}
															$x_Emi_ClaveList .= ">" . $datawrk["Emi_NomCorto"] . "</option>";
															$rowcntwrk++;
														}
													}
													@phpmkr_free_result($rswrk);
													$x_Emi_ClaveList .= "</select>";
													echo $x_Emi_ClaveList;
													?>
                                                </div>
												<div class="mb-4">
                                                    <label for="RFC" class="form-label text-muted text-uppercase fw-semibold mb-3">SubGrupo</label>                     
													<input class="form-control" type="text" name="n_ser_Prefijo" id="n_ser_Prefijo" size="30" maxlength="39" value="">
                                                </div>
												<div class="mb-4">
                                                    <label for="Nombre" class="form-label text-muted text-uppercase fw-semibold mb-3">Ejercicio</label>
                                                   <?php
													$x_ser_EjercicioList = "<select class=\"form-select form-select\" name=\"n_ser_Ejercicio\">";
													$x_ser_EjercicioList .= "<option value=''>Favor de elegir</option>";
														$x_ser_EjercicioList .= "<option value=\"2015\"";
														$x_ser_EjercicioList .= ">" . "2015" . "</option>";
														$x_ser_EjercicioList .= "<option value=\"2016\"";
														$x_ser_EjercicioList .= ">" . "2016" . "</option>";
														$x_ser_EjercicioList .= "<option value=\"2017\"";
														$x_ser_EjercicioList .= ">" . "2017" . "</option>";
														$x_ser_EjercicioList .= "<option value=\"2018\"";
														$x_ser_EjercicioList .= ">" . "2018" . "</option>";
														$x_ser_EjercicioList .= "<option value=\"2019\"";
														$x_ser_EjercicioList .= ">" . "2019" . "</option>";
														$x_ser_EjercicioList .= "<option value=\"2020\"";
														$x_ser_EjercicioList .= ">" . "2020" . "</option>";
														$x_ser_EjercicioList .= "<option value=\"2021\"";
														$x_ser_EjercicioList .= ">" . "2021" . "</option>";
														$x_ser_EjercicioList .= "<option value=\"2022\"";
														$x_ser_EjercicioList .= ">" . "2022" . "</option>";
														$x_ser_EjercicioList .= "<option value=\"2023\"";
														$x_ser_EjercicioList .= ">" . "2023" . "</option>";
														$x_ser_EjercicioList .= "<option value=\"2024\"";
														$x_ser_EjercicioList .= ">" . "2024" . "</option>";
													$x_ser_EjercicioList .= "</select>";
													echo $x_ser_EjercicioList;
													?>
                                                </div>
												<div class="mb-4">
                                                    <label for="Paterno" class="form-label text-muted text-uppercase fw-semibold mb-3">Periodo</label>
													<?php
													$x_ser_PeriodoList = "<select class=\"form-select form-select\" name=\"n_ser_Periodo\">";
													$x_ser_PeriodoList .= "<option value=''>Favor de elegir</option>";
														$x_ser_PeriodoList .= "<option value=\"CAT\"";
														$x_ser_PeriodoList .= ">" . "CATORCENA" . "</option>";
														$x_ser_PeriodoList .= "<option value=\"QUI\"";
														$x_ser_PeriodoList .= ">" . "QUINCENAL" . "</option>";
														$x_ser_PeriodoList .= "<option value=\"MES\"";
														$x_ser_PeriodoList .= ">" . "MENSUAL" . "</option>";
														$x_ser_PeriodoList .= "<option value=\"EXT\"";
														$x_ser_PeriodoList .= ">" . "EXTRAORDINARIA" . "</option>";
													$x_ser_PeriodoList .= "</select>";
													echo $x_ser_PeriodoList;
													?>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="country-select"
                                                        class="form-label text-muted text-uppercase fw-semibold mb-3">Numero</label>
														<input class="form-control" type="text" name="n_ser_Numero" id="n_ser_Numero" size="30" maxlength="39" value="">														
                                                </div>
												<div class="mb-4">
                                                    <label for="country-select"
                                                        class="form-label text-muted text-uppercase fw-semibold mb-3">Status</label>
														<br />
														<input type="radio" name="n_ser_Status"<?php if (@$x_ser_Status == "A") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("A"); ?>">
														<?php echo "Activo"; ?>
														<?php echo EditOptionSeparator(0); ?>
														<input type="radio" name="n_ser_Status"<?php if (@$x_ser_Status == "I") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("I"); ?>">
														<?php echo "Inactivo"; ?>
														<?php echo EditOptionSeparator(1); ?>
														<input type="hidden" name="n_ser_Serie" id="n_ser_Serie">
                                                </div>
                                            </div>
                                            <!--end offcanvas-body-->
                                            <div class="offcanvas-footer border-top p-3 text-right hstack gap-2">
                                                <button type="submit" name="Action" class="btn btn-soft-success waves-effect waves-light w-100" value="ADD">Agregar</button>
												<input type="hidden" name="a_add" value="A">
                                            </div>
                                            <!--end offcanvas-footer-->
                                        </form>
                                    </div>
                                    <!--end offcanvas-->
									<!------FIN FILTROS------------->
<?php
// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>									
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
				$nDisplayRecs = 10;  // Non-numeric, Load Default
			}
		}
		$_SESSION["vit_series_RecPerPage"] = $nDisplayRecs; // Save to Session

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_series_REC"] = $nStartRec;
	}else{
		if (@$_SESSION["vit_series_RecPerPage"] <> "") {
			$nDisplayRecs = $_SESSION["vit_series_RecPerPage"]; // Restore from Session
		}else{
			$nDisplayRecs = 10; // Load Default
		}
	}
}

//-------------------------------------------------------------------------------
// Function SetUpInlineEdit
// - Set up Inline Edit parameters based on querystring parameters a & key
// - Variables setup: sAction, sKey, Session("Proj_InlineEdit_Key")

function SetUpInlineEdit($conn)
{
	global $x_ser_SerieID;

	// Get the keys for master table
	if (strlen(@$_GET["a"]) > 0) {
		$sAction = @$_GET["a"];
		if (strtoupper($sAction) == "EDIT") { // Change to Inline Edit Mode
			$bInlineEdit = true;
			if (strlen(@$_GET["ser_SerieID"]) > 0) {
				$x_ser_SerieID = $_GET["ser_SerieID"];
			}else{
				$bInlineEdit = false;
			}
			if ($bInlineEdit) {
				if (LoadData($conn)) {
					$_SESSION["vita_proyecto_Key_ser_SerieID"] = $x_ser_SerieID; // Set up Inline Edit key
				}
			}
		}
		elseif (strtoupper($sAction) == "CANCEL")  // Switch out of Inline Edit Mode
		{
			$_SESSION["vita_proyecto_Key_ser_SerieID"] = ""; // Clear Inline Edit key
		}
	}
	else
	{
		$sAction = @$_POST["a_list"];
		if (strtoupper($sAction) == "UPDATE") { // Update Record

			// Get fields from form
			global $x_ser_SerieID;
			$x_ser_SerieID = @$_POST["x_ser_SerieID"];
			global $x_ser_Prefijo;
			$x_ser_Prefijo = @$_POST["x_ser_Prefijo"];
			global $x_ser_Ejercicio;
			$x_ser_Ejercicio = @$_POST["x_ser_Ejercicio"];
			global $x_ser_Periodo;
			$x_ser_Periodo = @$_POST["x_ser_Periodo"];
			global $x_ser_Numero;
			$x_ser_Numero = @$_POST["x_ser_Numero"];
			global $x_ser_Serie;
			$x_ser_Serie = @$_POST["x_ser_Serie"];
			global $x_Emi_Clave;
			$x_Emi_Clave = @$_POST["x_Emi_Clave"];
			global $x_ser_Status;
			$x_ser_Status = @$_POST["x_ser_Status"];
			if ($_SESSION["vita_proyecto_Key_ser_SerieID"] == $x_ser_SerieID) {
				if (InlineEditData($conn)) {
					$_SESSION["ewmsg"] = "Registro actualizado con exito.";
				}
			}
		}
		$_SESSION["vita_proyecto_Key_ser_SerieID"] = ""; // Clear Inline Edit key
	}
}

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`ser_Prefijo` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`ser_Periodo` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`ser_Numero` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`ser_Serie` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Emi_Clave` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`ser_Status` LIKE '%" . $sKeyword . "%' OR ";
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
	$sSearch = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes(@$_GET["psearch"]) : @$_GET["psearch"];
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

		// Field ser_Prefijo
		if ($sOrder == "ser_Prefijo") {
			$sSortField = "`ser_Prefijo`";
			$sLastSort = @$_SESSION["vit_series_x_ser_Prefijo_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_series_x_ser_Prefijo_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_series_x_ser_Prefijo_Sort"] <> "") { @$_SESSION["vit_series_x_ser_Prefijo_Sort"] = ""; }
		}

		// Field ser_Ejercicio
		if ($sOrder == "ser_Ejercicio") {
			$sSortField = "`ser_Ejercicio`";
			$sLastSort = @$_SESSION["vit_series_x_ser_Ejercicio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_series_x_ser_Ejercicio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_series_x_ser_Ejercicio_Sort"] <> "") { @$_SESSION["vit_series_x_ser_Ejercicio_Sort"] = ""; }
		}

		// Field ser_Periodo
		if ($sOrder == "ser_Periodo") {
			$sSortField = "`ser_Periodo`";
			$sLastSort = @$_SESSION["vit_series_x_ser_Periodo_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_series_x_ser_Periodo_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_series_x_ser_Periodo_Sort"] <> "") { @$_SESSION["vit_series_x_ser_Periodo_Sort"] = ""; }
		}

		// Field ser_Numero
		if ($sOrder == "ser_Numero") {
			$sSortField = "`ser_Numero`";
			$sLastSort = @$_SESSION["vit_series_x_ser_Numero_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_series_x_ser_Numero_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_series_x_ser_Numero_Sort"] <> "") { @$_SESSION["vit_series_x_ser_Numero_Sort"] = ""; }
		}

		// Field ser_Serie
		if ($sOrder == "ser_Serie") {
			$sSortField = "`ser_Serie`";
			$sLastSort = @$_SESSION["vit_series_x_ser_Serie_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_series_x_ser_Serie_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_series_x_ser_Serie_Sort"] <> "") { @$_SESSION["vit_series_x_ser_Serie_Sort"] = ""; }
		}

		// Field Emi_Clave
		if ($sOrder == "Emi_Clave") {
			$sSortField = "`Emi_Clave`";
			$sLastSort = @$_SESSION["vit_series_x_Emi_Clave_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_series_x_Emi_Clave_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_series_x_Emi_Clave_Sort"] <> "") { @$_SESSION["vit_series_x_Emi_Clave_Sort"] = ""; }
		}

		// Field ser_Status
		if ($sOrder == "ser_Status") {
			$sSortField = "`ser_Status`";
			$sLastSort = @$_SESSION["vit_series_x_ser_Status_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_series_x_ser_Status_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_series_x_ser_Status_Sort"] <> "") { @$_SESSION["vit_series_x_ser_Status_Sort"] = ""; }
		}
		$_SESSION["vit_series_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["vit_series_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["vit_series_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["vit_series_OrderBy"] = $sOrderBy;
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
		$_SESSION["vit_series_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["vit_series_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["vit_series_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["vit_series_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["vit_series_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["vit_series_REC"] = $nStartRec;
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
			$_SESSION["vit_series_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["vit_series_searchwhere"] = $sSrchWhere;
			$_SESSION["vita_proyecto_InlineEdit_Key"] = ""; // Clear Inline Edit key

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["vit_series_OrderBy"] = $sOrderBy;
			if (@$_SESSION["vit_series_x_ser_Prefijo_Sort"] <> "") { $_SESSION["vit_series_x_ser_Prefijo_Sort"] = ""; }
			if (@$_SESSION["vit_series_x_ser_Ejercicio_Sort"] <> "") { $_SESSION["vit_series_x_ser_Ejercicio_Sort"] = ""; }
			if (@$_SESSION["vit_series_x_ser_Periodo_Sort"] <> "") { $_SESSION["vit_series_x_ser_Periodo_Sort"] = ""; }
			if (@$_SESSION["vit_series_x_ser_Numero_Sort"] <> "") { $_SESSION["vit_series_x_ser_Numero_Sort"] = ""; }
			if (@$_SESSION["vit_series_x_ser_Serie_Sort"] <> "") { $_SESSION["vit_series_x_ser_Serie_Sort"] = ""; }
			if (@$_SESSION["vit_series_x_Emi_Clave_Sort"] <> "") { $_SESSION["vit_series_x_Emi_Clave_Sort"] = ""; }
			if (@$_SESSION["vit_series_x_ser_Status_Sort"] <> "") { $_SESSION["vit_series_x_ser_Status_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_series_REC"] = $nStartRec;
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
		$GLOBALS["x_Emi_Clave"] = $row["Emi_Clave"];
		$GLOBALS["x_ser_Status"] = $row["ser_Status"];
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
		$bInlineEditData = false; // Update Failed
	}else{
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_ser_Prefijo"]) : $GLOBALS["x_ser_Prefijo"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ser_Prefijo`"] = $theValue;
		$theValue = ($GLOBALS["x_ser_Ejercicio"] != "") ? intval($GLOBALS["x_ser_Ejercicio"]) : "NULL";
		$fieldList["`ser_Ejercicio`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_ser_Periodo"]) : $GLOBALS["x_ser_Periodo"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ser_Periodo`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_ser_Numero"]) : $GLOBALS["x_ser_Numero"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ser_Numero`"] = $theValue;
		$GLOBALS["x_ser_Serie"] = $GLOBALS["x_Emi_Clave"]."".$GLOBALS["x_ser_Prefijo"]."".$GLOBALS["x_ser_Ejercicio"]."".$GLOBALS["x_ser_Periodo"]."".$GLOBALS["x_ser_Numero"];
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_ser_Serie"]) : $GLOBALS["x_ser_Serie"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ser_Serie`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_Clave"]) : $GLOBALS["x_Emi_Clave"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_Clave`"] = $theValue;
		
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_ser_Status"]) : $GLOBALS["x_ser_Status"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`ser_Status`"] = $theValue;

		// update
		$sSql = "UPDATE `vit_series` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
		$bInlineEditData = true; // Update Successful
	}
	return $bInlineEditData;
}
?>
