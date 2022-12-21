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
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=vit_archivos.xls');
}
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/phpmkrfn.php") ?>
<?php
$arRecKey = array();

// Load Key Parameters
$sKey = "";
$bSingleDelete = true;
$x_arch_ArchivoID = @$_GET["arch_ArchivoID"];
if (!empty($x_arch_ArchivoID)) {
	if ($sKey <> "") { $sKey .= ","; }
	$sKey .= $x_arch_ArchivoID;
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
	$sDbWhere .= "`arch_ArchivoID`=" . $sRecKey . " AND ";
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
			header("Location: archivos_listado.php");
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
	$_SESSION["vit_archivos_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["vit_archivos_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["vit_archivos_searchwhere"];
}

// Build SQL
$sSql = "SELECT * FROM `vit_archivos`";

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
        
        <title>Archivos | VitaInsumos</title>
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
                                <h4 class="mb-sm-0">Archivos</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Modulos</a></li>
                                        <li class="breadcrumb-item active">Archivos</li>
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
												<form action="archivos_listado.php">
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
												<?php if(@$_SESSION["vit_archivos_OrderBy"]!=""){ ?>
												<a class="btn btn-light" href="archivos_listado.php?cmd=resetsort" title="Quitar Orden"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>X</a>
												<?php } ?>											
												<?php if(@$sWhere!="" || @$sSrchAdvanced!="" && @$_SESSION["vit_archivos_OrderBy"]==""){ ?>
												<a class="btn btn-light" href="archivos_listado.php?cmd=reset" title="Quitar Filtros"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>F</a>
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
<form name="vit_archivoslist" id="vit_archivoslist" action="archivos_listado.php" method="post" enctype="multipart/form-data">
<table class="table align-middle" id="customerTable">
	<thead class="table-light">
	<tr>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Archivo ID
<?php }else{ ?>
	<a href="archivos_listado.php?order=<?php echo urlencode("arch_ArchivoID"); ?>">Archivo ID<?php if (@$_SESSION["vit_archivos_x_arch_ArchivoID_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_archivos_x_arch_ArchivoID_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Ruta
<?php }else{ ?>
	<a href="archivos_listado.php?order=<?php echo urlencode("arch_Ruta"); ?>">Ruta&nbsp;(*)<?php if (@$_SESSION["vit_archivos_x_arch_Ruta_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_archivos_x_arch_Ruta_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Municipio
<?php }else{ ?>
	<a href="archivos_listado.php?order=<?php echo urlencode("Mun_ID"); ?>">Municipio<?php if (@$_SESSION["vit_archivos_x_Mun_ID_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_archivos_x_Mun_ID_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Status
<?php }else{ ?>
	<a href="archivos_listado.php?order=<?php echo urlencode("arch_Status"); ?>">Status<?php if (@$_SESSION["vit_archivos_x_arch_Status_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_archivos_x_arch_Status_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
<?php if ($sExport == "") { ?>
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
		$x_arch_ArchivoID = $row["arch_ArchivoID"];
		$x_arch_Ruta = $row["arch_Ruta"];
		$x_Mun_ID = $row["Mun_ID"];
		$x_arch_Status = $row["arch_Status"];
		$x_arch_FecReg = $row["arch_FecReg"];
		$x_arch_UsuReg = $row["arch_UsuReg"];
		$x_arch_FecAct = $row["arch_FecAct"];
		$x_arch_UsuAct = $row["arch_UsuAct"];
	$bEditRow = (($_SESSION["vita_proyecto_Key_arch_ArchivoID"] == $x_arch_ArchivoID) && ($nEditRowCnt == 0));
	if ($bEditRow) {
		$nEditRowCnt++;
		$sItemRowClass = " bgcolor=\"#fdedec\"";
	}
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- arch_ArchivoID -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php echo $x_arch_ArchivoID; ?><input type="hidden" id="x_arch_ArchivoID" name="x_arch_ArchivoID" value="<?php echo htmlspecialchars(@$x_arch_ArchivoID); ?>">
<?php }else{ ?>
<?php echo $x_arch_ArchivoID; ?>
<?php } ?>
</span></td>
		<!-- arch_Ruta -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php if ((!is_null($x_arch_Ruta)) && $x_arch_Ruta <> "") {  ?>
<input type="hidden" name="a_x_arch_Ruta" value="3">
<?php } else {?>
<input type="hidden" name="a_x_arch_Ruta" value="3">
<?php } ?>
<input class="form-control" type="file" id="x_arch_Ruta" name="x_arch_Ruta" onChange="if (this.form.a_x_arch_Ruta[2]) this.form.a_x_arch_Ruta[2].checked=true;">
<?php }else{ ?>
<?php if ((!is_null($x_arch_Ruta)) &&  $x_arch_Ruta <> "") { ?>
<a href="<?php echo ewUploadPath(0) . $x_arch_Ruta ?>" target="_blank"><?php echo $x_arch_Ruta; ?></a>
<?php } ?>
<?php } ?>
</span></td>
		<!-- Mun_ID -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php
$x_Mun_IDList = "<select class=\"form-control\" name=\"x_Mun_ID\">";
$x_Mun_IDList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT `Mun_ID`, `Mun_Descrip` FROM `vit_municipios`";
$sSqlWrk .= " ORDER BY `Mun_Descrip` Asc";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_Mun_IDList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["Mun_ID"] == @$x_Mun_ID) {
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
<?php }else{ ?>
<?php
if ((!is_null($x_Mun_ID)) && ($x_Mun_ID <> "")) {
	$sSqlWrk = "SELECT `Mun_Descrip` FROM `vit_municipios`";
	$sTmp = $x_Mun_ID;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `Mun_ID` = " . $sTmp . "";
	$sSqlWrk .= " ORDER BY `Mun_Descrip` Asc";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
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
<?php } ?>
</span></td>
		<!-- arch_Status -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<input type="radio" name="x_arch_Status"<?php if (@$x_arch_Status == "A") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("A"); ?>">
<?php echo "Activo"; ?>
<?php echo EditOptionSeparator(0); ?>
<input type="radio" name="x_arch_Status"<?php if (@$x_arch_Status == "I") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("I"); ?>">
<?php echo "Inactivo"; ?>
<?php echo EditOptionSeparator(1); ?>
<?php }else{ ?>
<?php
switch ($x_arch_Status) {
	case "A":
		$sTmp = '<span class="badge badge-soft-success">Activo</span>';
		break;
	case "I":
		$sTmp = '<span class="badge badge-soft-danger">Inactivo</span>';
		break;
	default:
		$sTmp = "";
}
$ox_arch_Status = $x_arch_Status; // Backup Original Value
$x_arch_Status = $sTmp;
?>
<?php echo $x_arch_Status; ?>
<?php $x_arch_Status = $ox_arch_Status; // Restore Original Value ?>
<?php } ?>
</span></td>
<?php if ($sExport == "") { ?>
<td><span class="phpmaker">
<?php if ($_SESSION["vita_proyecto_Key_arch_ArchivoID"] == $x_arch_ArchivoID) { ?>
<a href="" onClick="if (EW_checkMyForm(document.vit_archivoslist)) document.vit_archivoslist.submit();return false;">Actualizar</a>&nbsp;<a href="archivos_listado.php?a=cancel">Cancel</a>
<input type="hidden" name="a_list" value="update">
<input type="hidden" name="EW_Max_File_Size" value="200000000">
<?php } else { ?>
	<div class="dropdown">
		<a href="javascript:void(0);" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="false">
			<i class="ri-more-2-fill"></i>
		</a>		
		<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
			<li>
			<a class="dropdown-item" href="<?php if ($x_arch_ArchivoID <> "") {echo "archivos_listado.php?a=edit&arch_ArchivoID=" . urlencode($x_arch_ArchivoID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Editar</a>			
			</li>
			<li>
			<a class="dropdown-item" onclick = "if(!confirm('Confirmar que desea eliminar el registro?')) return false;" href="<?php if ($x_arch_ArchivoID <> "") {echo "archivos_listado.php?arch_ArchivoID=" . urlencode($x_arch_ArchivoID)."&a_delete=D"; } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Eliminar</a>	
			</li>
		</ul>
	</div>
<?php } ?>
</span></td>
<?php } ?>
	</tr>
<?php
	}
}
?>
</tbody>
</table>
</form>
<?php } ?>
<div class="d-flex justify-content-end mt-3">
	<div class="pagination-wrap hstack gap-2">
<?php if ($sExport == "") { ?>
<form action="archivos_listado.php" name="ewpagerform" id="ewpagerform">
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
	<td><a class="page-item pagination-prev" href="archivos_listado.php?start=1">|<</a></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($PrevStart == $nStartRec) { ?>
	<td><a class="page-item pagination-prev disabled"><</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-prev" href="archivos_listado.php?start=<?php echo $PrevStart; ?>"><</a></td>
	<?php } ?>
<!--current page number-->
	<td><input class="form-control form-control-sm" type="text" name="pageno" value="<?php echo intval(($nStartRec-1)/$nDisplayRecs+1); ?>" size="4"></td>
<!--next page button-->
	<?php if ($NextStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">></a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="archivos_listado.php?start=<?php echo $NextStart; ?>">></a></td>
	<?php  } ?>
<!--last page button-->
	<?php if ($LastStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">>|</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="archivos_listado.php?start=<?php echo $LastStart; ?>">>|</a></td>
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
if (EW_this.n_arch_Ruta && !EW_hasValue(EW_this.n_arch_Ruta, "FILE" )) {
	if (!EW_onError(EW_this, EW_this.n_arch_Ruta, "FILE", "Favor de elegir el archivo a cargar."))
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
                                            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Nuevo - Archivo</h5>
                                            <button type="button" class="btn-close text-reset"
                                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <!--end offcanvas-header-->
										<form name="vit_archivosadd" id="vit_archivosadd" action="vit_archivosadd.php" method="post" enctype="multipart/form-data" onSubmit="return EW_checkMyFormN(this);">
										<input type="hidden" name="EW_Max_File_Size" value="200000000">
                                        <!--<form action="" class="d-flex flex-column justify-content-end h-100">-->
                                            <div class="offcanvas-body">
												<div class="mb-4">
                                                    <label for="RFC" class="form-label text-muted text-uppercase fw-semibold mb-3">Ruta</label>                     
													<input class="form-control" type="file" id="n_arch_Ruta" name="n_arch_Ruta">
                                                </div>
												<div class="mb-4">
                                                    <label for="RFC" class="form-label text-muted text-uppercase fw-semibold mb-3">Municipio</label>                     
													<?php
													$x_Mun_IDList = "<select class=\"form-select form-select\" name=\"n_Mun_ID\">";
													$x_Mun_IDList .= "<option value=''>Favor de elegir</option>";
													$sSqlWrk = "SELECT `Mun_ID`, `Mun_Descrip` FROM `vit_municipios`";
													$sSqlWrk .= " ORDER BY `Mun_Descrip` Asc";
													$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
													if ($rswrk) {
														$rowcntwrk = 0;
														while ($datawrk = phpmkr_fetch_array($rswrk)) {
															$x_Mun_IDList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";															
															$x_Mun_IDList .= ">" . $datawrk["Mun_Descrip"] . "</option>";
															$rowcntwrk++;
														}
													}
													@phpmkr_free_result($rswrk);
													$x_Mun_IDList .= "</select>";
													echo $x_Mun_IDList;
													?>
                                                </div>
												<div class="mb-4">
                                                    <label for="Nombre" class="form-label text-muted text-uppercase fw-semibold mb-3">Status</label><br />
                                                   <input type="radio" name="n_arch_Status"<?php if (@$x_arch_Status == "A") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("A"); ?>">
													<?php echo "Activo"; ?>
													<?php echo EditOptionSeparator(0); ?>
													<input type="radio" name="n_arch_Status"<?php if (@$x_arch_Status == "I") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("I"); ?>">
													<?php echo "Inactivo"; ?>
													<?php echo EditOptionSeparator(1); ?>
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
		$_SESSION["vit_archivos_RecPerPage"] = $nDisplayRecs; // Save to Session

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_archivos_REC"] = $nStartRec;
	}else{
		if (@$_SESSION["vit_archivos_RecPerPage"] <> "") {
			$nDisplayRecs = $_SESSION["vit_archivos_RecPerPage"]; // Restore from Session
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
	global $x_arch_ArchivoID;

	// Get the keys for master table
	if (strlen(@$_GET["a"]) > 0) {
		$sAction = @$_GET["a"];
		if (strtoupper($sAction) == "EDIT") { // Change to Inline Edit Mode
			$bInlineEdit = true;
			if (strlen(@$_GET["arch_ArchivoID"]) > 0) {
				$x_arch_ArchivoID = $_GET["arch_ArchivoID"];
			}else{
				$bInlineEdit = false;
			}
			if ($bInlineEdit) {
				if (LoadData($conn)) {
					$_SESSION["vita_proyecto_Key_arch_ArchivoID"] = $x_arch_ArchivoID; // Set up Inline Edit key
				}
			}
		}
		elseif (strtoupper($sAction) == "CANCEL")  // Switch out of Inline Edit Mode
		{
			$_SESSION["vita_proyecto_Key_arch_ArchivoID"] = ""; // Clear Inline Edit key
		}
	}
	else
	{
		$sAction = @$_POST["a_list"];
		if (strtoupper($sAction) == "UPDATE") { // Update Record

			// Get fields from form
			global $x_arch_ArchivoID;
			$x_arch_ArchivoID = @$_POST["x_arch_ArchivoID"];
			global $x_arch_Ruta;
			$x_arch_Ruta = @$_POST["x_arch_Ruta"];
			global $x_Mun_ID;
			$x_Mun_ID = @$_POST["x_Mun_ID"];
			global $x_arch_Status;
			$x_arch_Status = @$_POST["x_arch_Status"];
			global $x_arch_FecReg;
			$x_arch_FecReg = @$_POST["x_arch_FecReg"];
			global $x_arch_UsuReg;
			$x_arch_UsuReg = @$_POST["x_arch_UsuReg"];
			global $x_arch_FecAct;
			$x_arch_FecAct = @$_POST["x_arch_FecAct"];
			global $x_arch_UsuAct;
			$x_arch_UsuAct = @$_POST["x_arch_UsuAct"];
			if ($_SESSION["vita_proyecto_Key_arch_ArchivoID"] == $x_arch_ArchivoID) {
				if (InlineEditData($conn)) {
					$_SESSION["ewmsg"] = "Registro actualizado con exito.";
				}
			}
		}
		$_SESSION["vita_proyecto_Key_arch_ArchivoID"] = ""; // Clear Inline Edit key
	}
}

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`arch_Ruta` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`arch_Status` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`arch_UsuReg` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`arch_UsuAct` LIKE '%" . $sKeyword . "%' OR ";
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

		// Field arch_ArchivoID
		if ($sOrder == "arch_ArchivoID") {
			$sSortField = "`arch_ArchivoID`";
			$sLastSort = @$_SESSION["vit_archivos_x_arch_ArchivoID_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_archivos_x_arch_ArchivoID_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_archivos_x_arch_ArchivoID_Sort"] <> "") { @$_SESSION["vit_archivos_x_arch_ArchivoID_Sort"] = ""; }
		}

		// Field arch_Ruta
		if ($sOrder == "arch_Ruta") {
			$sSortField = "`arch_Ruta`";
			$sLastSort = @$_SESSION["vit_archivos_x_arch_Ruta_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_archivos_x_arch_Ruta_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_archivos_x_arch_Ruta_Sort"] <> "") { @$_SESSION["vit_archivos_x_arch_Ruta_Sort"] = ""; }
		}

		// Field Mun_ID
		if ($sOrder == "Mun_ID") {
			$sSortField = "`Mun_ID`";
			$sLastSort = @$_SESSION["vit_archivos_x_Mun_ID_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_archivos_x_Mun_ID_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_archivos_x_Mun_ID_Sort"] <> "") { @$_SESSION["vit_archivos_x_Mun_ID_Sort"] = ""; }
		}

		// Field arch_Status
		if ($sOrder == "arch_Status") {
			$sSortField = "`arch_Status`";
			$sLastSort = @$_SESSION["vit_archivos_x_arch_Status_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_archivos_x_arch_Status_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_archivos_x_arch_Status_Sort"] <> "") { @$_SESSION["vit_archivos_x_arch_Status_Sort"] = ""; }
		}
		$_SESSION["vit_archivos_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["vit_archivos_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["vit_archivos_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["vit_archivos_OrderBy"] = $sOrderBy;
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
		$_SESSION["vit_archivos_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["vit_archivos_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["vit_archivos_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["vit_archivos_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["vit_archivos_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["vit_archivos_REC"] = $nStartRec;
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
			$_SESSION["vit_archivos_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["vit_archivos_searchwhere"] = $sSrchWhere;
			$_SESSION["vita_proyecto_InlineEdit_Key"] = ""; // Clear Inline Edit key

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["vit_archivos_OrderBy"] = $sOrderBy;
			if (@$_SESSION["vit_archivos_x_arch_ArchivoID_Sort"] <> "") { $_SESSION["vit_archivos_x_arch_ArchivoID_Sort"] = ""; }
			if (@$_SESSION["vit_archivos_x_arch_Ruta_Sort"] <> "") { $_SESSION["vit_archivos_x_arch_Ruta_Sort"] = ""; }
			if (@$_SESSION["vit_archivos_x_Mun_ID_Sort"] <> "") { $_SESSION["vit_archivos_x_Mun_ID_Sort"] = ""; }
			if (@$_SESSION["vit_archivos_x_arch_Status_Sort"] <> "") { $_SESSION["vit_archivos_x_arch_Status_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_archivos_REC"] = $nStartRec;
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
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables

function InlineEditData($conn)
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
		$bInlineEditData = false; // Update Failed
	}else{

		// check file size
		$EW_MaxFileSize = @$_POST["EW_Max_File_Size"];
		if (!empty($_FILES["x_arch_Ruta"]["size"])) {
			if (!empty($EW_MaxFileSize) && $_FILES["x_arch_Ruta"]["size"] > $EW_MaxFileSize) {
				die("Max. file upload size exceeded");
			}
		}
		$a_x_arch_Ruta = @$_POST["a_x_arch_Ruta"];
			if (is_uploaded_file($_FILES["x_arch_Ruta"]["tmp_name"])) {
				$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_arch_Ruta"]["name"]);
						if (!move_uploaded_file($_FILES["x_arch_Ruta"]["tmp_name"], $destfile)) // move file to destination path
						die("You didn't upload a file or the file couldn't be moved to" . $destfile);

				// File Name
				$theName = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes(ewUploadFileName($_FILES["x_arch_Ruta"]["name"])) : ewUploadFileName($_FILES["x_arch_Ruta"]["name"]);
				$fieldList["`arch_Ruta`"] = " '" . $theName . "'";
				@unlink($_FILES["x_arch_Ruta"]["tmp_name"]);
			}
		$theValue = ($GLOBALS["x_Mun_ID"] != "") ? intval($GLOBALS["x_Mun_ID"]) : "NULL";
		$fieldList["`Mun_ID`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_arch_Status"]) : $GLOBALS["x_arch_Status"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`arch_Status`"] = $theValue;
		
		// Field arch_FecAct
		$GLOBALS["x_arch_FecAct"] = date('Y-m-d H:i:s');
		$theValue = ($GLOBALS["x_arch_FecAct"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_arch_FecAct"]) . "'" : "NULL";
		$fieldList["`arch_FecAct`"] = $theValue;

		// Field arch_UsuAct
		$GLOBALS["x_arch_UsuAct"]= @$_SESSION["project1_status_User"];
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_arch_UsuAct"]) : $GLOBALS["x_arch_UsuAct"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`arch_UsuAct`"] = $theValue;

		// update
		$sSql = "UPDATE `vit_archivos` SET ";
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
<?php

//-------------------------------------------------------------------------------
// Function DeleteData
// - Delete Records based on input sql criteria sqlKey

function DeleteData($sqlKey,$conn)
{
	global $x_arch_ArchivoID;
	$sSql = "Delete FROM `vit_archivos`";
	$sSql .= " WHERE " . $sqlKey;
	phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
	return true;
}
?>