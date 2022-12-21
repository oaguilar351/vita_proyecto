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
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=vit_usuarios.xls');
}
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/phpmkrfn.php") ?>
<?php
$arRecKey = array();

// Load Key Parameters
$sKey = "";
$bSingleDelete = true;
$x_Vit_Usuario = @$_GET["Vit_Usuario"];
if (!empty($x_Vit_Usuario)) {
	if ($sKey <> "") { $sKey .= ","; }
	$sKey .= $x_Vit_Usuario;
}else{
	$bSingleDelete = false;
}
$x_Mun_ID = @$_GET["Mun_ID"];
if (!empty($x_Mun_ID)) {
	if ($sKey <> "") { $sKey .= ","; }
	$sKey .= $x_Mun_ID;
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
	$sDbWhere .= "`Vit_Usuario`='" . $sRecKey . "' AND ";

	// Remove spaces
	$sRecKey = trim($arRecKey[$i+1]);
	$sRecKey = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($sRecKey) : $sRecKey ;

	// Build the SQL
	$sDbWhere .= "`Mun_ID`=" . $sRecKey . " AND ";
	if (substr($sDbWhere, -5) == " AND ") { $sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5) . ") OR "; }
	$i += 2;
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
			header("Location: usuarios_listado.php");
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
	$_SESSION["vit_usuarios_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["vit_usuarios_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["vit_usuarios_searchwhere"];
}

// Build SQL
$sSql = "SELECT * FROM `vit_usuarios`";

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
if (EW_this.x_Vit_Usuario && !EW_hasValue(EW_this.x_Vit_Usuario, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_Vit_Usuario, "TEXT", "Favor de ingresar - Usuario"))
		return false;
}
if (EW_this.x_Mun_ID && !EW_hasValue(EW_this.x_Mun_ID, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_Mun_ID, "SELECT", "Favor de elegir - Municipio"))
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
        
        <title>Comprobentes | VitaInsumos</title>
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
                                <h4 class="mb-sm-0">Usuarios</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Modulos</a></li>
                                        <li class="breadcrumb-item active">Usuarios</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<!-- Primary Alert -->
<!--<div class="alert alert-primary alert-dismissible fade show" role="alert">
    <strong><?php echo $_SESSION["ewmsg"]; ?></strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>-->
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
												<form action="usuarios_listado.php">
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
											<form name="vit_usuarioslist" id="vit_usuarioslist" action="usuarios_listado.php" method="post">
											<table class="table align-middle" id="customerTable">
                                                <thead class="table-light">
												 <tr>
													<th>
											<?php if ($sExport <> "") { ?>
											Usuario
											<?php }else{ ?>
												<a href="usuarios_listado.php?order=<?php echo urlencode("Vit_Usuario"); ?>">Usuario&nbsp;(*)<?php if (@$_SESSION["vit_usuarios_x_Vit_Usuario_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_usuarios_x_Vit_Usuario_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
											<?php } ?>
													</span></td>
													<th>
											<?php if ($sExport <> "") { ?>
											Contrasena
											<?php }else{ ?>
												<a href="usuarios_listado.php?order=<?php echo urlencode("Vit_Contrasena"); ?>">Contrasena&nbsp;(*)<?php if (@$_SESSION["vit_usuarios_x_Vit_Contrasena_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_usuarios_x_Vit_Contrasena_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
											<?php } ?>
													</th>
													<th>
											<?php if ($sExport <> "") { ?>
											Nombre
											<?php }else{ ?>
												<a href="usuarios_listado.php?order=<?php echo urlencode("Vit_Nombre"); ?>">Nombre&nbsp;(*)<?php if (@$_SESSION["vit_usuarios_x_Vit_Nombre_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_usuarios_x_Vit_Nombre_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
											<?php } ?>
													</th>
													<th>
											<?php if ($sExport <> "") { ?>
											Municipio
											<?php }else{ ?>
												<a href="usuarios_listado.php?order=<?php echo urlencode("Mun_ID"); ?>">Municipio<?php if (@$_SESSION["vit_usuarios_x_Mun_ID_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_usuarios_x_Mun_ID_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
											<?php } ?>
													</th>
													<th>
											<?php if ($sExport <> "") { ?>
											Perfil
											<?php }else{ ?>
												<a href="usuarios_listado.php?order=<?php echo urlencode("Perfil_ID"); ?>">Perfil<?php if (@$_SESSION["vit_usuarios_x_Perfil_ID_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_usuarios_x_Perfil_ID_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
											<?php } ?>
													</th>
													<th>
											<?php if ($sExport <> "") { ?>
											Status
											<?php }else{ ?>
												<a href="usuarios_listado.php?order=<?php echo urlencode("Vit_Status"); ?>">Status<?php if (@$_SESSION["vit_usuarios_x_Vit_Status_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_usuarios_x_Vit_Status_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
											<?php } ?>
													</th>
											<?php if ($sExport == "") { ?>
											<!--<td>&nbsp;</td>-->
											<td>&nbsp;</td>
											<!--<td>&nbsp;</td>
											<td>&nbsp;</td>-->
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
		$x_Vit_Usuario = $row["Vit_Usuario"];
		$x_Vit_Contrasena = $row["Vit_Contrasena"];
		$x_Vit_Nombre = $row["Vit_Nombre"];
		$x_Mun_ID = $row["Mun_ID"];
		$x_Perfil_ID = $row["Perfil_ID"];
		$x_Vit_Status = $row["Vit_Status"];
	$bEditRow = (($_SESSION["vita_proyecto_Key_Vit_Usuario"] == $x_Vit_Usuario && $_SESSION["vita_proyecto_Key_Mun_ID"] == $x_Mun_ID) && ($nEditRowCnt == 0));
	if ($bEditRow) {
		$nEditRowCnt++;
		$sItemRowClass = " bgcolor=\"#fbe9e7\"";
	}
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- Vit_Usuario -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php echo $x_Vit_Usuario; ?><input type="hidden" id="x_Vit_Usuario" name="x_Vit_Usuario" value="<?php echo htmlspecialchars(@$x_Vit_Usuario); ?>">
<?php }else{ ?>
<?php echo $x_Vit_Usuario; ?>
<?php } ?>
</span></td>
		<!-- Vit_Contrasena -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<div class="col-lg-12">
<input class="form-control form-control-sm" type="text" name="x_Vit_Contrasena" id="x_Vit_Contrasena" size="30" maxlength="30" value="<?php echo htmlspecialchars(@$x_Vit_Contrasena) ?>">
 </div>
<?php }else{ ?>
<?php echo $x_Vit_Contrasena; ?>
<?php } ?>
</span></td>
		<!-- Vit_Nombre -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<div class="col-lg-10">
<input class="form-control form-control-sm" type="text" name="x_Vit_Nombre" id="x_Vit_Nombre" size="30" maxlength="30" value="<?php echo htmlspecialchars(@$x_Vit_Nombre) ?>">
</div>
<?php }else{ ?>
<?php echo $x_Vit_Nombre; ?>
<?php } ?>
</span></td>
		<!-- Mun_ID -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php
$x_Mun_IDList = "<select class=\"form-control form-control-sm\" name=\"x_Mun_ID\">";
$x_Mun_IDList .= "<option value=''>Favor de elegir</option>";
$sSqlWrk = "SELECT Mun_ID, Mun_Descrip FROM `vit_municipios`";
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
	$sSqlWrk = "SELECT DISTINCT `Mun_Descrip` FROM `vit_municipios`";
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
		<!-- Perfil_ID -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php
$x_Perfil_IDList = "<select class=\"form-control form-control-sm\" name=\"x_Perfil_ID\">";
$x_Perfil_IDList .= "<option value=''>Favor de elegir</option>";
$sSqlWrk = "SELECT DISTINCT `Per_Perfil_ID`, `Per_Descripcion` FROM `vit_perfil`";
$sSqlWrk .= " ORDER BY `Per_Descripcion` Asc";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_Perfil_IDList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["Per_Perfil_ID"] == @$x_Perfil_ID) {
			$x_Perfil_IDList .= "' selected";
		}
		$x_Perfil_IDList .= ">" . $datawrk["Per_Descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_Perfil_IDList .= "</select>";
echo $x_Perfil_IDList;
?>
<?php }else{ ?>
<?php
if ((!is_null($x_Perfil_ID)) && ($x_Perfil_ID <> "")) {
	$sSqlWrk = "SELECT DISTINCT `Per_Descripcion` FROM `vit_perfil`";
	$sTmp = $x_Perfil_ID;
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
$ox_Perfil_ID = $x_Perfil_ID; // Backup Original Value
$x_Perfil_ID = $sTmp;
?>
<?php echo $x_Perfil_ID; ?>
<?php $x_Perfil_ID = $ox_Perfil_ID; // Restore Original Value ?>
<?php } ?>
</span></td>
		<!-- Vit_Status -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<input type="radio" name="x_Vit_Status"<?php if (@$x_Vit_Status == "1") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("1"); ?>">
<?php echo "Activo"; ?>
<?php echo EditOptionSeparator(0); ?>
<input type="radio" name="x_Vit_Status"<?php if (@$x_Vit_Status == "0") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("0"); ?>">
<?php echo "Inactivo"; ?>
<?php echo EditOptionSeparator(1); ?>
<?php }else{ ?>
<?php
switch ($x_Vit_Status) {
	case "0":
		$sTmp = "Inactivo";
		$sTmp = '<span class="badge badge-soft-danger">Inactivo</span>';
		break;
	case "1":
		$sTmp = "Activo";
		$sTmp = '<span class="badge badge-soft-success">Activo</span>';
		break;
	default:
		$sTmp = "";
}
$ox_Vit_Status = $x_Vit_Status; // Backup Original Value
$x_Vit_Status = $sTmp;
?>
<?php echo $x_Vit_Status; ?>
<?php $x_Vit_Status = $ox_Vit_Status; // Restore Original Value ?>
<?php } ?>
</span></td>
<?php if ($sExport == "") { ?>
	<td>
	<?php if ($_SESSION["vita_proyecto_Key_Vit_Usuario"] == $x_Vit_Usuario && $_SESSION["vita_proyecto_Key_Mun_ID"] == $x_Mun_ID) { ?>
	<a href="" onClick="if (EW_checkMyForm(document.vit_usuarioslist)) document.vit_usuarioslist.submit();return false;">Actualizar</a>&nbsp;<a href="usuarios_listado.php?a=cancel">Cancelar</a>
	<input type="hidden" name="a_list" value="update">
	<?php } else { ?>
	<div class="dropdown">
		<a href="#" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="false">
			<i class="ri-more-2-fill"></i>
		</a>
		
		<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
			<li>			
			<a class="dropdown-item" href="<?php if ($x_Vit_Usuario <> "" AND $x_Mun_ID <> "") {echo "usuarios_listado.php?a=edit&Vit_Usuario=" . urlencode($x_Vit_Usuario) . "&Mun_ID=" . urlencode($x_Mun_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Editar</a>			
			</li>
			<li>
			<a class="dropdown-item" href="<?php if ($x_Vit_Usuario <> "" AND $x_Mun_ID <> "") {echo "usuarios_listado.php?Vit_Usuario=" . urlencode($x_Vit_Usuario) . "&Mun_ID=" . urlencode($x_Mun_ID) ."&a_delete=D"; } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Eliminar</a>
			</li>
		</ul>
	</div>
	<?php } ?>
</td>
<!--<td><span class="phpmaker"><a href="<?php if ($x_Vit_Usuario <> "" AND $x_Mun_ID <> "") {echo "vit_usuariosview.php?Vit_Usuario=" . urlencode($x_Vit_Usuario) . "&Mun_ID=" . urlencode($x_Mun_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">View</a></span></td>-->
<!--<td><span class="phpmaker"><a href="<?php if ($x_Vit_Usuario <> "" AND $x_Mun_ID <> "") {echo "vit_usuariosedit.php?Vit_Usuario=" . urlencode($x_Vit_Usuario) . "&Mun_ID=" . urlencode($x_Mun_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_Vit_Usuario <> "" AND $x_Mun_ID <> "") {echo "vit_usuariosdelete.php?Vit_Usuario=" . urlencode($x_Vit_Usuario) . "&Mun_ID=" . urlencode($x_Mun_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Delete</a></span></td>-->
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

											</div>
                                        <div class="d-flex justify-content-end mt-3">
                                            <div class="pagination-wrap hstack gap-2">												
												<form action="usuarios_listado.php" name="ewpagerform" id="ewpagerform">
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
	<td><a class="page-item pagination-prev" href="usuarios_listado.php?start=1">|<</a></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($PrevStart == $nStartRec) { ?>
	<td><a class="page-item pagination-prev disabled"><</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-prev" href="usuarios_listado.php?start=<?php echo $PrevStart; ?>"><</a></td>
	<?php } ?>
<!--current page number-->
	<td><input class="form-control form-control-sm" type="text" name="pageno" value="<?php echo intval(($nStartRec-1)/$nDisplayRecs+1); ?>" size="4"></td>
<!--next page button-->
	<?php if ($NextStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">></a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="usuarios_listado.php?start=<?php echo $NextStart; ?>">></a></td>
	<?php  } ?>
<!--last page button-->
	<?php if ($LastStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">>|</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="usuarios_listado.php?start=<?php echo $LastStart; ?>">>|</a></td>
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
                                            </div>
                                        </div>
                                    </div>
<script type="text/javascript">
<!--
function EW_checkMyFormN(EW_this) {
if (EW_this.n_Vit_Usuario && !EW_hasValue(EW_this.n_Vit_Usuario, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.n_Vit_Usuario, "TEXT", "Please enter required field - Usuario"))
		return false;
}
if (EW_this.n_Mun_ID && !EW_hasValue(EW_this.n_Mun_ID, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.n_Mun_ID, "SELECT", "Please enter required field - Municipio"))
		return false;
}
return true;
}

//-->
</script>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample"
                                        aria-labelledby="offcanvasExampleLabel">
                                        <div class="offcanvas-header bg-light">
                                            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Nuevo - Usuarios</h5>
                                            <button type="button" class="btn-close text-reset"
                                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <!--end offcanvas-header-->
										<form class="d-flex flex-column justify-content-end h-100" name="vit_usuariosadd" id="vit_usuariosadd" action="vit_usuariosadd.php" method="post" onSubmit="return EW_checkMyFormN(this);">
                                        <!--<form action="" class="d-flex flex-column justify-content-end h-100">-->
                                            <div class="offcanvas-body">
												<div class="mb-4">
                                                    <label for="Serie" class="form-label text-muted text-uppercase fw-semibold mb-3">Usuario</label>
                                                    <input class="form-control" type="text" name="n_Vit_Usuario" id="n_Vit_Usuario" size="30" maxlength="120" value="">
                                                </div>
												<div class="mb-4">
                                                    <label for="Folio" class="form-label text-muted text-uppercase fw-semibold mb-3">Contrasena</label>
                                                    <input class="form-control" type="text" name="n_Vit_Contrasena" id="n_Vit_Contrasena" size="30" value="">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="datepicker-range"
                                                        class="form-label text-muted text-uppercase fw-semibold mb-3">Nombre</label>
														 <input class="form-control" type="text" name="n_Vit_Nombre" id="n_Vit_Nombre" size="30" value="">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="country-select"
                                                        class="form-label text-muted text-uppercase fw-semibold mb-3">Municipio</label>
														<?php if (!(!is_null($x_Mun_ID)) || ($x_Mun_ID == "")) { $x_Mun_ID = 0;} // Set default value ?>
														<?php
														$x_Mun_IDList = "<select class=\"form-control\" name=\"n_Mun_ID\">";
														$x_Mun_IDList .= "<option value=''>Favor de elegir</option>";
														$sSqlWrk = "SELECT DISTINCT `Mun_ID`, `Mun_Descrip` FROM `vit_municipios`";
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
                                                </div>
												<div class="mb-4">
                                                    <label for="country-select"
                                                        class="form-label text-muted text-uppercase fw-semibold mb-3">Perfil</label>
														<?php
														$x_Perfil_IDList = "<select class=\"form-control\" name=\"n_Perfil_ID\">";
														$x_Perfil_IDList .= "<option value=''>Favor de elegir</option>";
														$sSqlWrk = "SELECT DISTINCT `Per_Perfil_ID`, `Per_Descripcion` FROM `vit_perfil`";
														$sSqlWrk .= " ORDER BY `Per_Descripcion` Asc";
														$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
														if ($rswrk) {
															$rowcntwrk = 0;
															while ($datawrk = phpmkr_fetch_array($rswrk)) {
																$x_Perfil_IDList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
																if ($datawrk["Per_Perfil_ID"] == @$x_Perfil_ID) {
																	$x_Perfil_IDList .= "' selected";
																}
																$x_Perfil_IDList .= ">" . $datawrk["Per_Descripcion"] . "</option>";
																$rowcntwrk++;
															}
														}
														@phpmkr_free_result($rswrk);
														$x_Perfil_IDList .= "</select>";
														echo $x_Perfil_IDList;
														?>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="UUID" class="form-label text-muted text-uppercase fw-semibold mb-3">Status</label>
													<br />
													<?php if (!(!is_null($x_Vit_Status)) || ($x_Vit_Status == "")) { $x_Vit_Status = 1;} // Set default value ?>
													<input type="radio" name="n_Vit_Status"<?php if (@$x_Vit_Status == "1") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("1"); ?>">
													<?php echo "Activo"; ?>
													<?php echo EditOptionSeparator(0); ?>
													<input type="radio" name="n_Vit_Status"<?php if (@$x_Vit_Status == "0") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("0"); ?>">
													<?php echo "Inactivo"; ?>
													<?php echo EditOptionSeparator(1); ?>
                                                </div>
                                            </div>
                                            <!--end offcanvas-body-->
                                            <div class="offcanvas-footer border-top p-3 text-center hstack gap-2">
                                                <!--<button class="btn btn-light w-100">Limpiar Filtro</button>-->									
												<a class="btn btn-light w-100" href="usuarios_listado.php?cmd=reset">Cancelar</a>
                                                <button type="submit" name="Action" value="ADD" class="btn btn-soft-success waves-effect waves-light w-100">Agreger</button>
												<input type="hidden" name="a_add" value="A">
                                            </div>
                                            <!--end offcanvas-footer-->
                                        </form>
                                    </div>
                                    <!--end offcanvas-->
                                       
                                     </div>
                            </div>

                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->

<?php
// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>

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
		$_SESSION["vit_usuarios_RecPerPage"] = $nDisplayRecs; // Save to Session

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_usuarios_REC"] = $nStartRec;
	}else{
		if (@$_SESSION["vit_usuarios_RecPerPage"] <> "") {
			$nDisplayRecs = $_SESSION["vit_usuarios_RecPerPage"]; // Restore from Session
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
	global $x_Vit_Usuario;
	global $x_Mun_ID;

	// Get the keys for master table
	if (strlen(@$_GET["a"]) > 0) {
		$sAction = @$_GET["a"];
		if (strtoupper($sAction) == "EDIT") { // Change to Inline Edit Mode
			$bInlineEdit = true;
			if (strlen(@$_GET["Vit_Usuario"]) > 0) {
				$x_Vit_Usuario = $_GET["Vit_Usuario"];
			}else{
				$bInlineEdit = false;
			}
			if (strlen(@$_GET["Mun_ID"]) > 0) {
				$x_Mun_ID = $_GET["Mun_ID"];
			}else{
				$bInlineEdit = false;
			}
			if ($bInlineEdit) {
				if (LoadData($conn)) {
					$_SESSION["vita_proyecto_Key_Vit_Usuario"] = $x_Vit_Usuario; // Set up Inline Edit key
					$_SESSION["vita_proyecto_Key_Mun_ID"] = $x_Mun_ID; // Set up Inline Edit key
				}
			}
		}
		elseif (strtoupper($sAction) == "CANCEL")  // Switch out of Inline Edit Mode
		{
			$_SESSION["vita_proyecto_Key_Vit_Usuario"] = ""; // Clear Inline Edit key
			$_SESSION["vita_proyecto_Key_Mun_ID"] = ""; // Clear Inline Edit key
		}
	}
	else
	{
		$sAction = @$_POST["a_list"];
		if (strtoupper($sAction) == "UPDATE") { // Update Record

			// Get fields from form
			global $x_Vit_Usuario;
			$x_Vit_Usuario = @$_POST["x_Vit_Usuario"];
			global $x_Vit_Contrasena;
			$x_Vit_Contrasena = @$_POST["x_Vit_Contrasena"];
			global $x_Vit_Nombre;
			$x_Vit_Nombre = @$_POST["x_Vit_Nombre"];
			global $x_Mun_ID;
			$x_Mun_ID = @$_POST["x_Mun_ID"];
			global $x_Perfil_ID;
			$x_Perfil_ID = @$_POST["x_Perfil_ID"];
			global $x_Vit_Status;
			$x_Vit_Status = @$_POST["x_Vit_Status"];
			#if ($_SESSION["vita_proyecto_Key_Vit_Usuario"] == $x_Vit_Usuario && $_SESSION["vita_proyecto_Key_Mun_ID"] == $x_Mun_ID) {
			if ($_SESSION["vita_proyecto_Key_Vit_Usuario"] == $x_Vit_Usuario) {	
				if (InlineEditData($conn)) {
					$_SESSION["ewmsg"] = "Registro actualizado con exito.";
				}
			}
		}
		$_SESSION["vita_proyecto_Key_Vit_Usuario"] = ""; // Clear Inline Edit key
		$_SESSION["vita_proyecto_Key_Mun_ID"] = ""; // Clear Inline Edit key
	}
}

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`Vit_Usuario` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Vit_Contrasena` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Vit_Nombre` LIKE '%" . $sKeyword . "%' OR ";
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

		// Field Vit_Usuario
		if ($sOrder == "Vit_Usuario") {
			$sSortField = "`Vit_Usuario`";
			$sLastSort = @$_SESSION["vit_usuarios_x_Vit_Usuario_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_usuarios_x_Vit_Usuario_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_usuarios_x_Vit_Usuario_Sort"] <> "") { @$_SESSION["vit_usuarios_x_Vit_Usuario_Sort"] = ""; }
		}

		// Field Vit_Contrasena
		if ($sOrder == "Vit_Contrasena") {
			$sSortField = "`Vit_Contrasena`";
			$sLastSort = @$_SESSION["vit_usuarios_x_Vit_Contrasena_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_usuarios_x_Vit_Contrasena_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_usuarios_x_Vit_Contrasena_Sort"] <> "") { @$_SESSION["vit_usuarios_x_Vit_Contrasena_Sort"] = ""; }
		}

		// Field Vit_Nombre
		if ($sOrder == "Vit_Nombre") {
			$sSortField = "`Vit_Nombre`";
			$sLastSort = @$_SESSION["vit_usuarios_x_Vit_Nombre_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_usuarios_x_Vit_Nombre_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_usuarios_x_Vit_Nombre_Sort"] <> "") { @$_SESSION["vit_usuarios_x_Vit_Nombre_Sort"] = ""; }
		}

		// Field Mun_ID
		if ($sOrder == "Mun_ID") {
			$sSortField = "`Mun_ID`";
			$sLastSort = @$_SESSION["vit_usuarios_x_Mun_ID_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_usuarios_x_Mun_ID_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_usuarios_x_Mun_ID_Sort"] <> "") { @$_SESSION["vit_usuarios_x_Mun_ID_Sort"] = ""; }
		}

		// Field Perfil_ID
		if ($sOrder == "Perfil_ID") {
			$sSortField = "`Perfil_ID`";
			$sLastSort = @$_SESSION["vit_usuarios_x_Perfil_ID_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_usuarios_x_Perfil_ID_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_usuarios_x_Perfil_ID_Sort"] <> "") { @$_SESSION["vit_usuarios_x_Perfil_ID_Sort"] = ""; }
		}

		// Field Vit_Status
		if ($sOrder == "Vit_Status") {
			$sSortField = "`Vit_Status`";
			$sLastSort = @$_SESSION["vit_usuarios_x_Vit_Status_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_usuarios_x_Vit_Status_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_usuarios_x_Vit_Status_Sort"] <> "") { @$_SESSION["vit_usuarios_x_Vit_Status_Sort"] = ""; }
		}
		$_SESSION["vit_usuarios_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["vit_usuarios_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["vit_usuarios_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["vit_usuarios_OrderBy"] = $sOrderBy;
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
		$_SESSION["vit_usuarios_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["vit_usuarios_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["vit_usuarios_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["vit_usuarios_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["vit_usuarios_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["vit_usuarios_REC"] = $nStartRec;
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
			$_SESSION["vit_usuarios_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["vit_usuarios_searchwhere"] = $sSrchWhere;
			$_SESSION["vita_proyecto_InlineEdit_Key"] = ""; // Clear Inline Edit key

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["vit_usuarios_OrderBy"] = $sOrderBy;
			if (@$_SESSION["vit_usuarios_x_Vit_Usuario_Sort"] <> "") { $_SESSION["vit_usuarios_x_Vit_Usuario_Sort"] = ""; }
			if (@$_SESSION["vit_usuarios_x_Vit_Contrasena_Sort"] <> "") { $_SESSION["vit_usuarios_x_Vit_Contrasena_Sort"] = ""; }
			if (@$_SESSION["vit_usuarios_x_Vit_Nombre_Sort"] <> "") { $_SESSION["vit_usuarios_x_Vit_Nombre_Sort"] = ""; }
			if (@$_SESSION["vit_usuarios_x_Mun_ID_Sort"] <> "") { $_SESSION["vit_usuarios_x_Mun_ID_Sort"] = ""; }
			if (@$_SESSION["vit_usuarios_x_Perfil_ID_Sort"] <> "") { $_SESSION["vit_usuarios_x_Perfil_ID_Sort"] = ""; }
			if (@$_SESSION["vit_usuarios_x_Vit_Status_Sort"] <> "") { $_SESSION["vit_usuarios_x_Vit_Status_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_usuarios_REC"] = $nStartRec;
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
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables

function InlineEditData($conn)
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
	/*if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Mun_ID) : $x_Mun_ID;	
	$sWhere .= "(`Mun_ID` = " . addslashes($sTmp) . ")";*/
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
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Vit_Usuario"]) : $GLOBALS["x_Vit_Usuario"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Vit_Usuario`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Vit_Contrasena"]) : $GLOBALS["x_Vit_Contrasena"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Vit_Contrasena`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Vit_Nombre"]) : $GLOBALS["x_Vit_Nombre"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Vit_Nombre`"] = $theValue;
		$theValue = ($GLOBALS["x_Mun_ID"] != "") ? intval($GLOBALS["x_Mun_ID"]) : "NULL";
		$fieldList["`Mun_ID`"] = $theValue;
		$theValue = ($GLOBALS["x_Perfil_ID"] != "") ? intval($GLOBALS["x_Perfil_ID"]) : "NULL";
		$fieldList["`Perfil_ID`"] = $theValue;
		$theValue = ($GLOBALS["x_Vit_Status"] != "") ? intval($GLOBALS["x_Vit_Status"]) : "NULL";
		$fieldList["`Vit_Status`"] = $theValue;

		// update
		$sSql = "UPDATE `vit_usuarios` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		#echo "<br />sSql: ".$sSql; die;
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
	global $x_Vit_Usuario;
	global $x_Mun_ID;
	$sSql = "Delete FROM `vit_usuarios`";
	$sSql .= " WHERE " . $sqlKey;
	phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
	return true;
}
?>