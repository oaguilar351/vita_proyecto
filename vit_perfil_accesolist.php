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
$x_Acc_Acceso_ID = Null; 
$ox_Acc_Acceso_ID = Null;
$x_Per_Perfil_ID = Null; 
$ox_Per_Perfil_ID = Null;
$x_Mod_Modulo_ID = Null; 
$ox_Mod_Modulo_ID = Null;
$x_Acc_Agregar = Null; 
$ox_Acc_Agregar = Null;
$x_Acc_Editar = Null; 
$ox_Acc_Editar = Null;
$x_Acc_Eliminar = Null; 
$ox_Acc_Eliminar = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=vit_perfil_acceso.xls');
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
$x_Per_Descripcion = Null;
$ox_Per_Descripcion = Null;
$nDisplayRecs = 10;
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
	$_SESSION["vit_perfil_acceso_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["vit_perfil_acceso_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["vit_perfil_acceso_searchwhere"];
}

// Build SQL
$sSql = "SELECT * FROM `vit_perfil_acceso`";

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
// Build Master Record SQL

if ($sDbWhereMaster <> "") {
	$sSqlMasterBase = "SELECT * FROM `vit_perfil`";
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
	$rs = phpmkr_query($sSqlMaster, $conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSqlMaster);
	$bMasterRecordExist = (phpmkr_num_rows($rs) > 0);
	if (!$bMasterRecordExist) {
		$_SESSION["_MasterWhere"] = "";
		$_SESSION["vit_perfil_acceso_DetailWhere"] = "";
		$_SESSION["ewmsg"] = "No records found";
		phpmkr_free_result($rs);
		phpmkr_db_close($conn);
		header("Location: vit_perfillist.php");
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
return true;
}

//-->
</script>
<?php } ?>
<?php if ($sExport == "") { ?>
<script type="text/javascript">
<!--
function EW_selectKey(elem) {
	var f = elem.form;	
	if (!f.elements["key_d[]"]) return;
	if (f.elements["key_d[]"][0]) {
		for (var i=0; i<f.elements["key_d[]"].length; i++)
			f.elements["key_d[]"][i].checked = elem.checked;	
	} else {
		f.elements["key_d[]"].checked = elem.checked;	
	}
}

//-->
</script>
<?php } ?>
<head>
        
        <title>Perfil Accesos | VitaInsumos</title>
        <?php include 'layouts/title-meta.php'; ?>
        <!-- Sweet Alert css-->
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
                                <h4 class="mb-sm-0">Perfil Accesos</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Modulos</a></li>
                                        <li class="breadcrumb-item active">Perfil Accesos</li>
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
                    <!-- end page title -->
					<div class="row">
                        <div class="col-lg-12">
                            <div class="card" id="leadsList">
                                <div class="card-header border-0">

                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm-3">
                                            <div class="search-box">
												<form action="vit_perfil_accesolist.php">
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
												<?php if(@$_SESSION["vit_perfil_acceso_OrderBy"]!=""){ ?>
												<a class="btn btn-light" href="vit_perfil_accesolist.php?cmd=resetsort" title="Quitar Orden"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>X</a>
												<?php } ?>											
												<?php if(@$sSrchAdvanced!="" && @$_SESSION["vit_perfil_acceso_OrderBy"]==""){ ?>
												<a class="btn btn-light" href="vit_perfil_accesolist.php?cmd=reset" title="Quitar Filtros"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>F</a>
												<?php } ?>
                                                <button type="button" class="btn btn-info" data-bs-toggle="offcanvas"
                                                    href="#offcanvasExample"><i class="ri-filter-3-line align-bottom me-1" title="Elegir Filtros"></i> Nuevo </button> 
												&nbsp;
                                            </div>
											
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <div class="table-responsive table-card">
<?php
if ($sDbWhereMaster <> "") {
	if ($bMasterRecordExist) { ?>
<table class="table align-middle" id="customerTable">
	<thead class="table-light">
		<tr>
		<th valign="top"><span>ID</span></th>
		<th valign="top"><span>Descripcion</span></th>
	</tr>
	</thead>
    <tbody class="list form-check-all">
	<tr bgcolor="#FFFFFF">
<?php
		$row = phpmkr_fetch_array($rs);
		$x_Per_Perfil_ID = $row["Per_Perfil_ID"];
		$x_Per_Descripcion = $row["Per_Descripcion"];
?>
		<td><span class="phpmaker">
<?php echo $x_Per_Perfil_ID; ?>
</span></td>
		<td><span class="phpmaker">
<?php echo $x_Per_Descripcion; ?>
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
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
<?php if ($nTotalRecs > 0)  { ?>
<form name="vit_perfil_accesolist" id="vit_perfil_accesolist" action="vit_perfil_accesolist.php" method="post">
<table class="table align-middle" id="customerTable">
	<thead class="table-light">
		<tr>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Perfil
<?php }else{ ?>
	<a href="vit_perfil_accesolist.php?order=<?php echo urlencode("Per_Perfil_ID"); ?>">Perfil<?php if (@$_SESSION["vit_perfil_acceso_x_Per_Perfil_ID_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_perfil_acceso_x_Per_Perfil_ID_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Modulo
<?php }else{ ?>
	<a href="vit_perfil_accesolist.php?order=<?php echo urlencode("Mod_Modulo_ID"); ?>">Modulo<?php if (@$_SESSION["vit_perfil_acceso_x_Mod_Modulo_ID_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_perfil_acceso_x_Mod_Modulo_ID_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Agregar
<?php }else{ ?>
	<a href="vit_perfil_accesolist.php?order=<?php echo urlencode("Acc_Agregar"); ?>">Agregar<?php if (@$_SESSION["vit_perfil_acceso_x_Acc_Agregar_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_perfil_acceso_x_Acc_Agregar_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Editar
<?php }else{ ?>
	<a href="vit_perfil_accesolist.php?order=<?php echo urlencode("Acc_Editar"); ?>">Editar<?php if (@$_SESSION["vit_perfil_acceso_x_Acc_Editar_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_perfil_acceso_x_Acc_Editar_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Eliminar
<?php }else{ ?>
	<a href="vit_perfil_accesolist.php?order=<?php echo urlencode("Acc_Eliminar"); ?>">Eliminar<?php if (@$_SESSION["vit_perfil_acceso_x_Acc_Eliminar_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_perfil_acceso_x_Acc_Eliminar_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
<?php if ($sExport == "") { ?>
<!--<th>&nbsp;</th>-->
<th>&nbsp;</th>
<!--<th>&nbsp;</th>-->
<th><input type="checkbox" class="phpmaker" onClick="EW_selectKey(this);">&nbsp;Marcar</th>
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
		$x_Acc_Acceso_ID = $row["Acc_Acceso_ID"];
		$x_Per_Perfil_ID = $row["Per_Perfil_ID"];
		$x_Mod_Modulo_ID = $row["Mod_Modulo_ID"];
		$x_Acc_Agregar = $row["Acc_Agregar"];
		$x_Acc_Editar = $row["Acc_Editar"];
		$x_Acc_Eliminar = $row["Acc_Eliminar"];
	$bEditRow = (($_SESSION["vita_accesos_Key_Acc_Acceso_ID"] == $x_Acc_Acceso_ID) && ($nEditRowCnt == 0));
	if ($bEditRow) {
		$nEditRowCnt++;
		$sItemRowClass = " bgcolor=\"#fdedec\"";
	}
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
<?php if ($bEditRow) { ?>
<input type="hidden" id="x_Acc_Acceso_ID" name="x_Acc_Acceso_ID" value="<?php echo htmlspecialchars(@$x_Acc_Acceso_ID); ?>">
<?php } ?>
		<!-- Per_Perfil_ID -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php if (@$_SESSION["vit_perfil_acceso_MasterKey_Per_Perfil_ID"] <> "") { ?>
<?php $x_Per_Perfil_ID = @$_SESSION["vit_perfil_acceso_MasterKey_Per_Perfil_ID"]; ?>
<?php
if ((!is_null($x_Per_Perfil_ID)) && ($x_Per_Perfil_ID <> "")) {
	$sSqlWrk = "SELECT DISTINCT `Per_Descripcion` FROM `vit_perfil`";
	$sTmp = $x_Per_Perfil_ID;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `Per_Perfil_ID` = " . $sTmp . "";
	$sSqlWrk .= " ORDER BY `Per_Descripcion` Asc";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["Per_Descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_Per_Perfil_ID = $x_Per_Perfil_ID; // Backup Original Value
$x_Per_Perfil_ID = $sTmp;
?>
<?php echo $x_Per_Perfil_ID; ?>
<?php $x_Per_Perfil_ID = $ox_Per_Perfil_ID; // Restore Original Value ?>
<input type="hidden" id="x_Per_Perfil_ID" name="x_Per_Perfil_ID" value="<?php echo $x_Per_Perfil_ID; ?>">
<?php } else { ?>
<?php
$x_Per_Perfil_IDList = "<select name=\"x_Per_Perfil_ID\" class=\"form-select form-select\">";
$x_Per_Perfil_IDList .= "<option value=''>Favor de elegir</option>";
$sSqlWrk = "SELECT DISTINCT `Per_Perfil_ID`, `Per_Descripcion` FROM `vit_perfil`";
$sSqlWrk .= " ORDER BY `Per_Descripcion` Asc";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_Per_Perfil_IDList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["Per_Perfil_ID"] == @$x_Per_Perfil_ID) {
			$x_Per_Perfil_IDList .= "' selected";
		}
		$x_Per_Perfil_IDList .= ">" . $datawrk["Per_Descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_Per_Perfil_IDList .= "</select>";
echo $x_Per_Perfil_IDList;
?>
<?php } ?>
<?php }else{ ?>
<?php
if ((!is_null($x_Per_Perfil_ID)) && ($x_Per_Perfil_ID <> "")) {
	$sSqlWrk = "SELECT DISTINCT `Per_Descripcion` FROM `vit_perfil`";
	$sTmp = $x_Per_Perfil_ID;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `Per_Perfil_ID` = " . $sTmp . "";
	$sSqlWrk .= " ORDER BY `Per_Descripcion` Asc";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["Per_Descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_Per_Perfil_ID = $x_Per_Perfil_ID; // Backup Original Value
$x_Per_Perfil_ID = $sTmp;
?>
<?php echo $x_Per_Perfil_ID; ?>
<?php $x_Per_Perfil_ID = $ox_Per_Perfil_ID; // Restore Original Value ?>
<?php } ?>
</span></td>
		<!-- Mod_Modulo_ID -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php
$x_Mod_Modulo_IDList = "<select name=\"x_Mod_Modulo_ID\" class=\"form-select form-select\">";
$x_Mod_Modulo_IDList .= "<option value=''>Favor de elegir</option>";
$sSqlWrk = "SELECT `Mod_Modulo_ID`, `Mod_Descripcion` FROM `vit_modulos`";
$sSqlWrk .= " ORDER BY `Mod_Descripcion` Asc";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_Mod_Modulo_IDList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["Mod_Modulo_ID"] == @$x_Mod_Modulo_ID) {
			$x_Mod_Modulo_IDList .= "' selected";
		}
		$x_Mod_Modulo_IDList .= ">" . $datawrk["Mod_Descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_Mod_Modulo_IDList .= "</select>";
echo $x_Mod_Modulo_IDList;
?>
<?php }else{ ?>
<?php
if ((!is_null($x_Mod_Modulo_ID)) && ($x_Mod_Modulo_ID <> "")) {
	$sSqlWrk = "SELECT `Mod_Descripcion` FROM `vit_modulos`";
	$sTmp = $x_Mod_Modulo_ID;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `Mod_Modulo_ID` = " . $sTmp . "";
	$sSqlWrk .= " ORDER BY `Mod_Descripcion` Asc";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["Mod_Descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_Mod_Modulo_ID = $x_Mod_Modulo_ID; // Backup Original Value
$x_Mod_Modulo_ID = $sTmp;
?>
<?php echo $x_Mod_Modulo_ID; ?>
<?php $x_Mod_Modulo_ID = $ox_Mod_Modulo_ID; // Restore Original Value ?>
<?php } ?>
</span></td>
		<!-- Acc_Agregar -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<input type="radio" name="x_Acc_Agregar"<?php if (@$x_Acc_Agregar == "S") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("S"); ?>">
<?php echo "Si"; ?>
<?php echo EditOptionSeparator(0); ?>
<input type="radio" name="x_Acc_Agregar"<?php if (@$x_Acc_Agregar == "N") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("N"); ?>">
<?php echo "No"; ?>
<?php echo EditOptionSeparator(1); ?>
<?php }else{ ?>
<?php
switch ($x_Acc_Agregar) {
	case "S":
		$sTmp = "Si";
		break;
	case "N":
		$sTmp = "No";
		break;
	default:
		$sTmp = "";
}
$ox_Acc_Agregar = $x_Acc_Agregar; // Backup Original Value
$x_Acc_Agregar = $sTmp;
?>
<?php echo $x_Acc_Agregar; ?>
<?php $x_Acc_Agregar = $ox_Acc_Agregar; // Restore Original Value ?>
<?php } ?>
</span></td>
		<!-- Acc_Editar -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<input type="radio" name="x_Acc_Editar"<?php if (@$x_Acc_Editar == "S") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("S"); ?>">
<?php echo "Si"; ?>
<?php echo EditOptionSeparator(0); ?>
<input type="radio" name="x_Acc_Editar"<?php if (@$x_Acc_Editar == "N") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("N"); ?>">
<?php echo "No"; ?>
<?php echo EditOptionSeparator(1); ?>
<?php }else{ ?>
<?php
switch ($x_Acc_Editar) {
	case "S":
		$sTmp = "Si";
		break;
	case "N":
		$sTmp = "No";
		break;
	default:
		$sTmp = "";
}
$ox_Acc_Editar = $x_Acc_Editar; // Backup Original Value
$x_Acc_Editar = $sTmp;
?>
<?php echo $x_Acc_Editar; ?>
<?php $x_Acc_Editar = $ox_Acc_Editar; // Restore Original Value ?>
<?php } ?>
</span></td>
		<!-- Acc_Eliminar -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<input type="radio" name="x_Acc_Eliminar"<?php if (@$x_Acc_Eliminar == "S") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("S"); ?>">
<?php echo "Si"; ?>
<?php echo EditOptionSeparator(0); ?>
<input type="radio" name="x_Acc_Eliminar"<?php if (@$x_Acc_Eliminar == "N") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("N"); ?>">
<?php echo "No"; ?>
<?php echo EditOptionSeparator(1); ?>
<?php }else{ ?>
<?php
switch ($x_Acc_Eliminar) {
	case "S":
		$sTmp = "Si";
		break;
	case "N":
		$sTmp = "No";
		break;
	default:
		$sTmp = "";
}
$ox_Acc_Eliminar = $x_Acc_Eliminar; // Backup Original Value
$x_Acc_Eliminar = $sTmp;
?>
<?php echo $x_Acc_Eliminar; ?>
<?php $x_Acc_Eliminar = $ox_Acc_Eliminar; // Restore Original Value ?>
<?php } ?>
</span></td>
<?php if ($sExport == "") { ?>
<!--<td><span class="phpmaker"><a href="<?php if ($x_Acc_Acceso_ID <> "") {echo "vit_perfil_accesoview.php?Acc_Acceso_ID=" . urlencode($x_Acc_Acceso_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">View</a></span></td>-->
<td><span class="phpmaker">
<?php if ($_SESSION["vita_accesos_Key_Acc_Acceso_ID"] == $x_Acc_Acceso_ID) { ?>
<a href="" onClick="if (EW_checkMyForm(document.vit_perfil_accesolist)) document.vit_perfil_accesolist.submit();return false;">Actualizar</a>&nbsp;<a href="vit_perfil_accesolist.php?a=cancel">Cancelar</a>
<input type="hidden" name="a_list" value="update">
<?php } else { ?>
<a href="<?php if ($x_Acc_Acceso_ID <> "") {echo "vit_perfil_accesolist.php?a=edit&Acc_Acceso_ID=" . urlencode($x_Acc_Acceso_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Editar</a>
<?php } ?>
</span></td>
<!--<td><span class="phpmaker"><a href="<?php if ($x_Acc_Acceso_ID <> "") {echo "vit_perfil_accesoedit.php?Acc_Acceso_ID=" . urlencode($x_Acc_Acceso_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a></span></td>-->
<td><span class="phpmaker"><input type="checkbox" name="key_d[]" value="<?php echo $x_Acc_Acceso_ID; ?>" class="phpmaker">&nbsp;Eliminar</span></td>
<?php } ?>
	</tr>
<?php
	}
}
?>
	<tr>
		<td colspan="7" align="right">
		<?php if ($sExport == "") { ?>
<?php if ($nRecActual > 0) { ?>
<input type="button" name="btndelete" class="btn btn-soft-danger waves-effect waves-light" value="Eliminar" onClick="this.form.action='vit_perfil_accesodelete.php';this.form.encoding='application/x-www-form-urlencoded';this.form.submit();">
<?php } ?>
<?php } ?>
		</td>
	</tr>
 </tbody>
</table>

</form>
<?php if (strtoupper($sAction) == "EDIT") { ?>
<?php } ?>
<?php } ?>
<?php

// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
</div>
<div class="d-flex justify-content-end mt-3">
	<div class="pagination-wrap hstack gap-2">
<?php if ($sExport == "") { ?>
<form action="vit_perfil_accesolist.php" name="ewpagerform" id="ewpagerform">
<table bgcolor="" border="0" cellspacing="1" cellpadding="4" bgcolor="#CCCCCC">
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
	<td><a class="page-item pagination-prev" href="vit_perfil_accesolist.php?start=1">|<</a></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($PrevStart == $nStartRec) { ?>
	<td><a class="page-item pagination-prev disabled"><</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-prev" href="vit_perfil_accesolist.php?start=<?php echo $PrevStart; ?>"><</a></td>
	<?php } ?>
<!--current page number-->
	<td><input class="form-control form-control-sm" type="text" name="pageno" value="<?php echo intval(($nStartRec-1)/$nDisplayRecs+1); ?>" size="4"></td>
<!--next page button-->
	<?php if ($NextStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">></a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="vit_perfil_accesolist.php?start=<?php echo $NextStart; ?>">></a></td>
	<?php  } ?>
<!--last page button-->
	<?php if ($LastStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">>|</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="vit_perfil_accesolist.php?start=<?php echo $LastStart; ?>">>|</a></td>
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
<option value="ALL"<?php if (@$_SESSION["vit_receptor_RecPerPage"] == -1) { echo " selected";  }?>>Todos</option>
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
function EW_checkMyForm(EW_this) {
if (EW_this.x_Rec_CreationDate && !EW_checkdate(EW_this.x_Rec_CreationDate.value)) {
	if (!EW_onError(EW_this, EW_this.x_Rec_CreationDate, "TEXT", "Incorrect date, format = yyyy-mm-dd - Rec Creation Date"))
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
                                            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Filtros - Receptores</h5>
                                            <button type="button" class="btn-close text-reset"
                                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <!--end offcanvas-header-->
										<form class="d-flex flex-column justify-content-end h-100" name="vit_receptorsrch" id="vit_receptorsrch" action="vit_receptorsrch.php" method="post" onSubmit="return EW_checkMyForm(this);">
                                        <!--<form action="" class="d-flex flex-column justify-content-end h-100">-->
                                            <div class="offcanvas-body">
												<div class="mb-4">
                                                    <label for="RFC" class="form-label text-muted text-uppercase fw-semibold mb-3">RFC</label>
													<input type="hidden" name="z_Rec_RFC[]" value="LIKE,'%,%'">
                                                    <input class="form-control" type="text" name="s_Rec_RFC" id="s_Rec_RFC" size="30" maxlength="120" value="<?php echo htmlspecialchars(@$_GET["s_Rec_RFC"]) ?>">
                                                </div>
												<div class="mb-4">
                                                    <label for="Nombre" class="form-label text-muted text-uppercase fw-semibold mb-3">Nombre</label>
													<input type="hidden" name="z_Rec_Nombre[]" value="LIKE,'%,%'">
                                                    <input class="form-control" type="text" name="s_Rec_Nombre" id="s_Rec_Nombre" size="30" value="<?php echo htmlspecialchars(@$_GET["s_Rec_Nombre"]); ?>">
                                                </div>
												<div class="mb-4">
                                                    <label for="Paterno" class="form-label text-muted text-uppercase fw-semibold mb-3">Apellido Paterno</label>
													<input type="hidden" name="z_Rec_Apellido_Paterno[]" value="LIKE,'%,%'">
                                                    <input class="form-control" type="text" name="s_Rec_Apellido_Paterno" id="s_Rec_Apellido_Paterno" size="30" value="<?php echo htmlspecialchars(@$_GET["s_Rec_Apellido_Paterno"]); ?>">
                                                </div>
												<div class="mb-4">
                                                    <label for="Nombre" class="form-label text-muted text-uppercase fw-semibold mb-3">Apellido Materno</label>
													<input type="hidden" name="x_Rec_Apellido_Materno[]" value="LIKE,'%,%'">
                                                    <input class="form-control" type="text" name="s_Rec_Apellido_Materno" id="s_Rec_Apellido_Materno" size="30" value="<?php echo htmlspecialchars(@$_GET["s_Rec_Apellido_Materno"]); ?>">
                                                </div>
                                               <div class="mb-4">
                                                    <label for="Curp" class="form-label text-muted text-uppercase fw-semibold mb-3">Curp</label>
													<input type="hidden" name="z_Rec_Curp[]" value="LIKE,'%,%'">
                                                    <input class="form-control" type="text" name="s_Rec_Curp" id="s_Rec_Curp" size="30" value="<?php echo htmlspecialchars(@$_GET["s_Rec_Curp"]); ?>">
                                                </div>
												 <div class="mb-4">
                                                    <label for="NumEmpleado" class="form-label text-muted text-uppercase fw-semibold mb-3">Num Empleado</label>
													<input type="hidden" name="z_Rec_NumEmpleado[]" value="LIKE,'%,%'">
                                                    <input class="form-control" type="text" name="s_Rec_NumEmpleado" id="s_Rec_NumEmpleado" size="30" value="<?php echo htmlspecialchars(@$_GET["s_Rec_NumEmpleado"]); ?>">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="country-select"
                                                        class="form-label text-muted text-uppercase fw-semibold mb-3">Departamento</label>
														<input type="hidden" name="z_Rec_Departamento[]" value="LIKE,'%,%'">
														<?php
														$x_Emi_RFCList = "<select class=\"form-control\" name=\"s_Rec_Departamento\">";
														$x_Emi_RFCList .= "<option value=''>TODOS</option>";
														$sSqlWrk = "SELECT vit_receptor.Rec_Departamento FROM vit_receptor WHERE vit_receptor.Rec_Departamento <> ''";
														if(@$_SESSION["project1_status_Municipio"] != ""){
															$sSqlWrk .= "AND vit_receptor.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
														}
														$sSqlWrk .= "GROUP BY vit_receptor.Rec_Departamento";
														#echo "<br />".$sSqlWrk;
														$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
														if ($rswrk) {
															$rowcntwrk = 0;
															while ($datawrk = phpmkr_fetch_array($rswrk)) {
																$x_Emi_RFCList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
																if ($datawrk["Rec_Departamento"] == @$_GET["s_Rec_Departamento"]) {
																	$x_Emi_RFCList .= "' selected";
																}
																$x_Emi_RFCList .= ">" . $datawrk["Rec_Departamento"] . " " . $datawrk["Rec_Departamento"] . "</option>";
																$rowcntwrk++;
															}
														}
														@phpmkr_free_result($rswrk);
														$x_Emi_RFCList .= "</select>";
														echo $x_Emi_RFCList;
														?>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="country-select"
                                                        class="form-label text-muted text-uppercase fw-semibold mb-3">Municipio</label>
														<input type="hidden" name="z_Mun_ID[]" value="=,,">
														<?php
														$x_Mun_IDList = "<select class=\"form-control\" name=\"s_Mun_ID\">";
														$x_Mun_IDList .= "<option value=''>TODOS</option>";
														$sSqlWrk = "SELECT DISTINCT `Mun_ID`, `Mun_Descrip` FROM `vit_municipios`";
														$sSqlWrk .= "WHERE Mun_ID <> '' ";
														if(@$_SESSION["project1_status_Municipio"] != ""){
															$sSqlWrk .= "AND Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
														}	
														$sSqlWrk .= " ORDER BY `Mun_Descrip` Asc";
														#echo "<br />".$sSqlWrk;
														$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
														if ($rswrk) {
															$rowcntwrk = 0;
															while ($datawrk = phpmkr_fetch_array($rswrk)) {
																$x_Mun_IDList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
																if ($datawrk["Mun_ID"] == @$_GET["s_Mun_ID"]) {
																	$x_Mun_IDList .= "' selected";
																}
																$x_Mun_IDList .= ">" . $datawrk["Mun_Descrip"] . "</option>";
																$rowcntwrk++;
															}
														}
														@phpmkr_free_result($rswrk);
														$x_Mun_IDList .= "</select>";
														echo $x_Mun_IDList;
														?>
                                                </div>
                                            </div>
                                            <!--end offcanvas-body-->
                                            <div class="offcanvas-footer border-top p-3 text-center hstack gap-2">
                                                <!--<button class="btn btn-light w-100">Limpiar Filtro</button>-->
												<?php if(@$_SESSION["vit_receptor_OrderBy"]!=""){ ?>												
												<a class="btn btn-light w-100" href="vit_perfil_accesolist.php?cmd=resetsort">Quitar Orden</a>
												<?php } ?>											
												<?php if(@$sWhere!="" && @$_SESSION["vit_receptor_OrderBy"]==""){ ?>
												<a class="btn btn-light w-100" href="vit_perfil_accesolist.php?cmd=reset">Quitar Filtros</a>
												<?php } ?>												
                                                <button type="submit" name="Action" class="btn btn-soft-success waves-effect waves-light w-100">Filtrar</button>
												<input type="hidden" name="a_search" value="S">
                                            </div>
                                            <!--end offcanvas-footer-->
                                        </form>
                                    </div>
                                    <!--end offcanvas-->
									<!------FIN FILTROS------------->
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
		$_SESSION["vit_perfil_acceso_RecPerPage"] = $nDisplayRecs; // Save to Session

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_perfil_acceso_REC"] = $nStartRec;
	}else{
		if (@$_SESSION["vit_perfil_acceso_RecPerPage"] <> "") {
			$nDisplayRecs = $_SESSION["vit_perfil_acceso_RecPerPage"]; // Restore from Session
		}else{
			$nDisplayRecs = 10; // Load Default
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
	global $x_Per_Perfil_ID;

	// Get the keys for master table
	if (strlen(@$_GET["showmaster"]) > 0) {

		// Reset start record counter (new master key)
		$nStartRec = 1;
		$_SESSION["vit_perfil_acceso_REC"] = $nStartRec;
		$sDbWhereMaster = "";
		$sDbWhereDetail = "";	
		$x_Per_Perfil_ID = @$_GET["Per_Perfil_ID"]; // Load Parameter from QueryString
		$sTmp = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Per_Perfil_ID) : $x_Per_Perfil_ID;
		if ($sDbWhereMaster <> "") { $sDbWhereMaster .= " AND "; }
		$sDbWhereMaster .= "`Per_Perfil_ID` =  " . $sTmp . "";
		if ($sDbWhereDetail <> "") { $sDbWhereDetail .= " AND "; }
		$sDbWhereDetail .= "`Per_Perfil_ID` =  " . $sTmp  . "";
		$_SESSION["vit_perfil_acceso_MasterKey_Per_Perfil_ID"] = $sTmp; // Save Master Key Value
		$_SESSION["vit_perfil_acceso_MasterWhere"] = $sDbWhereMaster;
		$_SESSION["vit_perfil_acceso_DetailWhere"] = $sDbWhereDetail;
	}else{
		$sDbWhereMaster = @$_SESSION["vit_perfil_acceso_MasterWhere"];
		$sDbWhereDetail = @$_SESSION["vit_perfil_acceso_DetailWhere"];
	}
}

//-------------------------------------------------------------------------------
// Function SetUpInlineEdit
// - Set up Inline Edit parameters based on querystring parameters a & key
// - Variables setup: sAction, sKey, Session("Proj_InlineEdit_Key")

function SetUpInlineEdit($conn)
{
	global $x_Acc_Acceso_ID;

	// Get the keys for master table
	if (strlen(@$_GET["a"]) > 0) {
		$sAction = @$_GET["a"];
		if (strtoupper($sAction) == "EDIT") { // Change to Inline Edit Mode
			$bInlineEdit = true;
			if (strlen(@$_GET["Acc_Acceso_ID"]) > 0) {
				$x_Acc_Acceso_ID = $_GET["Acc_Acceso_ID"];
			}else{
				$bInlineEdit = false;
			}
			if ($bInlineEdit) {
				if (LoadData($conn)) {
					$_SESSION["vita_accesos_Key_Acc_Acceso_ID"] = $x_Acc_Acceso_ID; // Set up Inline Edit key
				}
			}
		}
		elseif (strtoupper($sAction) == "CANCEL")  // Switch out of Inline Edit Mode
		{
			$_SESSION["vita_accesos_Key_Acc_Acceso_ID"] = ""; // Clear Inline Edit key
		}
	}
	else
	{
		$sAction = @$_POST["a_list"];
		if (strtoupper($sAction) == "UPDATE") { // Update Record

			// Get fields from form
			global $x_Acc_Acceso_ID;
			$x_Acc_Acceso_ID = @$_POST["x_Acc_Acceso_ID"];
			global $x_Per_Perfil_ID;
			$x_Per_Perfil_ID = @$_POST["x_Per_Perfil_ID"];
			global $x_Mod_Modulo_ID;
			$x_Mod_Modulo_ID = @$_POST["x_Mod_Modulo_ID"];
			global $x_Acc_Agregar;
			$x_Acc_Agregar = @$_POST["x_Acc_Agregar"];
			global $x_Acc_Editar;
			$x_Acc_Editar = @$_POST["x_Acc_Editar"];
			global $x_Acc_Eliminar;
			$x_Acc_Eliminar = @$_POST["x_Acc_Eliminar"];
			if ($_SESSION["vita_accesos_Key_Acc_Acceso_ID"] == $x_Acc_Acceso_ID) {
				if (InlineEditData($conn)) {
					$_SESSION["ewmsg"] = "Update Record Successful";
				}
			}
		}
		$_SESSION["vita_accesos_Key_Acc_Acceso_ID"] = ""; // Clear Inline Edit key
	}
}

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`Acc_Agregar` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Acc_Editar` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Acc_Eliminar` LIKE '%" . $sKeyword . "%' OR ";
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

		// Field Per_Perfil_ID
		if ($sOrder == "Per_Perfil_ID") {
			$sSortField = "`Per_Perfil_ID`";
			$sLastSort = @$_SESSION["vit_perfil_acceso_x_Per_Perfil_ID_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_perfil_acceso_x_Per_Perfil_ID_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_perfil_acceso_x_Per_Perfil_ID_Sort"] <> "") { @$_SESSION["vit_perfil_acceso_x_Per_Perfil_ID_Sort"] = ""; }
		}

		// Field Mod_Modulo_ID
		if ($sOrder == "Mod_Modulo_ID") {
			$sSortField = "`Mod_Modulo_ID`";
			$sLastSort = @$_SESSION["vit_perfil_acceso_x_Mod_Modulo_ID_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_perfil_acceso_x_Mod_Modulo_ID_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_perfil_acceso_x_Mod_Modulo_ID_Sort"] <> "") { @$_SESSION["vit_perfil_acceso_x_Mod_Modulo_ID_Sort"] = ""; }
		}

		// Field Acc_Agregar
		if ($sOrder == "Acc_Agregar") {
			$sSortField = "`Acc_Agregar`";
			$sLastSort = @$_SESSION["vit_perfil_acceso_x_Acc_Agregar_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_perfil_acceso_x_Acc_Agregar_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_perfil_acceso_x_Acc_Agregar_Sort"] <> "") { @$_SESSION["vit_perfil_acceso_x_Acc_Agregar_Sort"] = ""; }
		}

		// Field Acc_Editar
		if ($sOrder == "Acc_Editar") {
			$sSortField = "`Acc_Editar`";
			$sLastSort = @$_SESSION["vit_perfil_acceso_x_Acc_Editar_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_perfil_acceso_x_Acc_Editar_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_perfil_acceso_x_Acc_Editar_Sort"] <> "") { @$_SESSION["vit_perfil_acceso_x_Acc_Editar_Sort"] = ""; }
		}

		// Field Acc_Eliminar
		if ($sOrder == "Acc_Eliminar") {
			$sSortField = "`Acc_Eliminar`";
			$sLastSort = @$_SESSION["vit_perfil_acceso_x_Acc_Eliminar_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_perfil_acceso_x_Acc_Eliminar_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_perfil_acceso_x_Acc_Eliminar_Sort"] <> "") { @$_SESSION["vit_perfil_acceso_x_Acc_Eliminar_Sort"] = ""; }
		}
		$_SESSION["vit_perfil_acceso_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["vit_perfil_acceso_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["vit_perfil_acceso_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["vit_perfil_acceso_OrderBy"] = $sOrderBy;
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
		$_SESSION["vit_perfil_acceso_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["vit_perfil_acceso_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["vit_perfil_acceso_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["vit_perfil_acceso_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["vit_perfil_acceso_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["vit_perfil_acceso_REC"] = $nStartRec;
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
			$_SESSION["vit_perfil_acceso_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["vit_perfil_acceso_searchwhere"] = $sSrchWhere;
			$_SESSION["vit_perfil_acceso_MasterWhere"] = ""; // Clear master criteria
			$sDbWhereMaster = "";
			$_SESSION["vit_perfil_acceso_DetailWhere"] = ""; // Clear detail criteria
			$sDbWhereDetail = "";
		$_SESSION["vit_perfil_acceso_MasterKey_Per_Perfil_ID"] = ""; // Clear Master Key Value
			$_SESSION["vita_accesos_InlineEdit_Key"] = ""; // Clear Inline Edit key

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["vit_perfil_acceso_OrderBy"] = $sOrderBy;
			if (@$_SESSION["vit_perfil_acceso_x_Per_Perfil_ID_Sort"] <> "") { $_SESSION["vit_perfil_acceso_x_Per_Perfil_ID_Sort"] = ""; }
			if (@$_SESSION["vit_perfil_acceso_x_Mod_Modulo_ID_Sort"] <> "") { $_SESSION["vit_perfil_acceso_x_Mod_Modulo_ID_Sort"] = ""; }
			if (@$_SESSION["vit_perfil_acceso_x_Acc_Agregar_Sort"] <> "") { $_SESSION["vit_perfil_acceso_x_Acc_Agregar_Sort"] = ""; }
			if (@$_SESSION["vit_perfil_acceso_x_Acc_Editar_Sort"] <> "") { $_SESSION["vit_perfil_acceso_x_Acc_Editar_Sort"] = ""; }
			if (@$_SESSION["vit_perfil_acceso_x_Acc_Eliminar_Sort"] <> "") { $_SESSION["vit_perfil_acceso_x_Acc_Eliminar_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_perfil_acceso_REC"] = $nStartRec;
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
	global $x_Acc_Acceso_ID;
	$sSql = "SELECT * FROM `vit_perfil_acceso`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Acc_Acceso_ID) : $x_Acc_Acceso_ID;
	$sWhere .= "(`Acc_Acceso_ID` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_Acc_Acceso_ID"] = $row["Acc_Acceso_ID"];
		$GLOBALS["x_Per_Perfil_ID"] = $row["Per_Perfil_ID"];
		$GLOBALS["x_Mod_Modulo_ID"] = $row["Mod_Modulo_ID"];
		$GLOBALS["x_Acc_Agregar"] = $row["Acc_Agregar"];
		$GLOBALS["x_Acc_Editar"] = $row["Acc_Editar"];
		$GLOBALS["x_Acc_Eliminar"] = $row["Acc_Eliminar"];
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
	global $x_Acc_Acceso_ID;
	$sSql = "SELECT * FROM `vit_perfil_acceso`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Acc_Acceso_ID) : $x_Acc_Acceso_ID;	
	$sWhere .= "(`Acc_Acceso_ID` = " . addslashes($sTmp) . ")";
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
		$theValue = ($GLOBALS["x_Per_Perfil_ID"] != "") ? intval($GLOBALS["x_Per_Perfil_ID"]) : "NULL";
		$fieldList["`Per_Perfil_ID`"] = $theValue;
		$theValue = ($GLOBALS["x_Mod_Modulo_ID"] != "") ? intval($GLOBALS["x_Mod_Modulo_ID"]) : "NULL";
		$fieldList["`Mod_Modulo_ID`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Acc_Agregar"]) : $GLOBALS["x_Acc_Agregar"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Acc_Agregar`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Acc_Editar"]) : $GLOBALS["x_Acc_Editar"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Acc_Editar`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Acc_Eliminar"]) : $GLOBALS["x_Acc_Eliminar"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Acc_Eliminar`"] = $theValue;

		// update
		$sSql = "UPDATE `vit_perfil_acceso` SET ";
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
