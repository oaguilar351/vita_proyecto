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
$x_Emi_ArchivoPas = Null; 
$ox_Emi_ArchivoPas = Null;
$x_Emi_CertificadoKey = Null; 
$ox_Emi_CertificadoKey = Null;
$fs_x_Emi_CertificadoKey = 0;
$fn_x_Emi_CertificadoKey = "";
$ct_x_Emi_CertificadoKey = "";
$w_x_Emi_CertificadoKey = 0;
$h_x_Emi_CertificadoKey = 0;
$a_x_Emi_CertificadoKey = "";
$x_Emi_CertificadoCer = Null; 
$ox_Emi_CertificadoCer = Null;
$fs_x_Emi_CertificadoCer = 0;
$fn_x_Emi_CertificadoCer = "";
$ct_x_Emi_CertificadoCer = "";
$w_x_Emi_CertificadoCer = 0;
$h_x_Emi_CertificadoCer = 0;
$a_x_Emi_CertificadoCer = "";
$x_Emi_CertificadoPas = Null; 
$ox_Emi_CertificadoPas = Null;
$x_Emi_Constancia = Null; 
$ox_Emi_Constancia = Null;
$fs_x_Emi_Constancia = 0;
$fn_x_Emi_Constancia = "";
$ct_x_Emi_Constancia = "";
$w_x_Emi_Constancia = 0;
$h_x_Emi_Constancia = 0;
$a_x_Emi_Constancia = "";
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=vit_emisor.xls');
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

// Get Search Criteria for Advanced Search
SetUpAdvancedSearch();

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
	$_SESSION["vit_emisor_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["vit_emisor_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["vit_emisor_searchwhere"];
}

// Build SQL
$sSql = "SELECT * FROM `Vit_Emisor`";

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
if (EW_this.x_Emi_RFC && !EW_hasValue(EW_this.x_Emi_RFC, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_Emi_RFC, "TEXT", "Please enter required field - RFC"))
		return false;
}
if (EW_this.x_Mun_ID && !EW_hasValue(EW_this.x_Mun_ID, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_Mun_ID, "SELECT", "Please enter required field - Municipio"))
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
        
        <title>Emisores | VitaInsumos</title>
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
                                <h4 class="mb-sm-0">Emisores</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Modulos</a></li>
                                        <li class="breadcrumb-item active">Emisores</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
<?php
if (@$_SESSION["ewmsg"] <> "") {
	$datosM = explode("|", @$_SESSION["ewmsg"]);
	#echo "<br />datosM: ".print_r($datosM);
	$opcion = trim($datosM[0]);
	$mensaj = @$datosM[1];
	$tiempo = @$datosM[2];
	
	#echo "<br />opcion: ".($opcion);
?>
<?php
	if($opcion=='success'){
?>		
	<script>
	$(document).ready(function(){
	Swal.fire({
		icon: 'success', 
		title: '<?php echo $mensaj; ?>', 
		showConfirmButton: false, 
		timer: 3000, 
		showCloseButton: true 
	});
	});
	</script>
<?php
	}else if($opcion=='danger'){
		#echo "<br />dentro opcion: ".($opcion);	
?>		
	<script>
	$(document).ready(function(){
	Swal.fire({
		icon: 'error', 
		title: '<?php echo $mensaj; ?>', 
		showConfirmButton: false, 
		timer: 5000, 
		showCloseButton: true 
	});
	});
	</script>
<?php	
	}else if($opcion=='warning'){
		#echo "<br />dentro opcion: ".($opcion);	
?>		
	<script>
	$(document).ready(function(){
	Swal.fire({
		icon: 'warning', 
		title: '<?php echo $mensaj; ?>', 
		showConfirmButton: false, 
		timer: 3000, 
		showCloseButton: true 
	});
	});
	</script>
<?php	
	}
?>	
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
												<form action="emisores_listado.php">
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
												<?php if(@$_SESSION["vit_emisor_OrderBy"]!=""){ ?>
												<a class="btn btn-light" href="emisores_listado.php?cmd=resetsort" title="Quitar Orden"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>X</a>
												<?php } ?>											
												<?php if(@$sWhere!="" || @$sSrchAdvanced!="" && @$_SESSION["vit_emisor_OrderBy"]==""){ ?>
												<a class="btn btn-light" href="emisores_listado.php?cmd=reset" title="Quitar Filtros"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>F</a>
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
<form name="vit_emisorlist" id="vit_emisorlist" action="vit_emisorlist.php" method="post" enctype="multipart/form-data">
<table class="table align-middle" id="customerTable">
		<thead class="table-light">
		 <tr>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
RFC
<?php }else{ ?>
	<a href="vit_emisorlist.php?order=<?php echo urlencode("Emi_RFC"); ?>">RFC<?php if (@$_SESSION["vit_emisor_x_Emi_RFC_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_emisor_x_Emi_RFC_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Nombre
<?php }else{ ?>
	<a href="vit_emisorlist.php?order=<?php echo urlencode("Emi_Nombre"); ?>">Nombre<?php if (@$_SESSION["vit_emisor_x_Emi_Nombre_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_emisor_x_Emi_Nombre_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Abreviatura
<?php }else{ ?>
	<a href="javascript:void(0);">Abreviatura<?php if (@$_SESSION["vit_emisor_x_Emi_RfcPatronOrigen_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_emisor_x_Emi_RfcPatronOrigen_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Municipio
<?php }else{ ?>
	<a href="vit_emisorlist.php?order=<?php echo urlencode("Mun_ID"); ?>">Municipio<?php if (@$_SESSION["vit_emisor_x_Mun_ID_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_emisor_x_Mun_ID_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Archivo Key
<?php }else{ ?>
	<a href="vit_emisorlist.php?order=<?php echo urlencode("Emi_ArchivoKey"); ?>">Archivo Key<?php if (@$_SESSION["vit_emisor_x_Emi_ArchivoKey_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_emisor_x_Emi_ArchivoKey_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Archivo Cer
<?php }else{ ?>
	<a href="vit_emisorlist.php?order=<?php echo urlencode("Emi_ArchivoCer"); ?>">Archivo Cer<?php if (@$_SESSION["vit_emisor_x_Emi_ArchivoCer_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_emisor_x_Emi_ArchivoCer_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Certificado Key
<?php }else{ ?>
	<a href="vit_emisorlist.php?order=<?php echo urlencode("Emi_CertificadoKey"); ?>">Certificado Key<?php if (@$_SESSION["vit_emisor_x_Emi_CertificadoKey_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_emisor_x_Emi_CertificadoKey_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Certificado Cer
<?php }else{ ?>
	<a href="vit_emisorlist.php?order=<?php echo urlencode("Emi_CertificadoCer"); ?>">Certificado Cer<?php if (@$_SESSION["vit_emisor_x_Emi_CertificadoCer_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_emisor_x_Emi_CertificadoCer_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Emi Constancia
<?php }else{ ?>
	<a href="vit_emisorlist.php?order=<?php echo urlencode("Emi_Constancia"); ?>">Constancia<?php if (@$_SESSION["vit_emisor_x_Emi_Constancia_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_emisor_x_Emi_Constancia_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
<?php if ($sExport == "") { ?>
<!--<td>&nbsp;</th>
<td>&nbsp;</th>
<td>&nbsp;</th>-->
<td>&nbsp;</th>
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
		$x_Emi_RFC = $row["Emi_RFC"];
		$x_Emi_Nombre = $row["Emi_Nombre"];
		$x_Emi_RegimenFiscal = $row["Emi_RegimenFiscal"];
		$x_Emi_Clave = $row["Emi_Clave"];
		$x_Emi_FacAtrAdquirente = $row["Emi_FacAtrAdquirente"];
		$x_Emi_Curp = $row["Emi_Curp"];
		$x_Emi_RegistroPatronal = $row["Emi_RegistroPatronal"];
		$x_Emi_RfcPatronOrigen = $row["Emi_RfcPatronOrigen"];
		$x_Emi_EntidadSNCF = $row["Emi_EntidadSNCF"];
		$x_Mun_ID = $row["Mun_ID"];
		$x_Emi_NomCorto = $row["Emi_NomCorto"];
		$x_Emi_ArchivoKey = $row["Emi_ArchivoKey"];
		$x_Emi_ArchivoCer = $row["Emi_ArchivoCer"];
		$x_Emi_ArchivoPas = $row["Emi_ArchivoPas"];
		$x_Emi_CertificadoKey = $row["Emi_CertificadoKey"];
		$x_Emi_CertificadoCer = $row["Emi_CertificadoCer"];
		$x_Emi_CertificadoPas = $row["Emi_CertificadoPas"];
		$x_Emi_Constancia = $row["Emi_Constancia"];
	$bEditRow = (($_SESSION["vita_proyecto_Key_Emi_RFC"] == $x_Emi_RFC && $_SESSION["vita_proyecto_Key_Mun_ID"] == $x_Mun_ID) && ($nEditRowCnt == 0));
	if ($bEditRow) {
		$nEditRowCnt++;
		$sItemRowClass = " bgcolor=\"#FFFF99\"";
	}
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- Emi_RFC -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php echo $x_Emi_RFC; ?><input type="hidden" id="x_Emi_RFC" name="x_Emi_RFC" value="<?php echo htmlspecialchars(@$x_Emi_RFC); ?>">
<?php }else{ ?>
<?php echo $x_Emi_RFC; ?>
<?php } ?>
</span></td>
		<!-- Emi_Nombre -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<textarea cols="35" rows="4" id="x_Emi_Nombre" name="x_Emi_Nombre"><?php echo @$x_Emi_Nombre; ?></textarea>
<?php }else{ ?>
<?php echo str_replace(chr(10), "<br>", strtoupper($x_Emi_Nombre)); ?>
<?php } ?>
</span></td>
		<!-- Emi_RfcPatronOrigen -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<input type="text" name="x_Emi_NomCorto" id="x_Emi_NomCorto" size="30" maxlength="39" value="<?php echo htmlspecialchars(@$x_Emi_NomCorto) ?>">
<?php }else{ ?>
<?php echo $x_Emi_NomCorto; ?>
<?php } ?>
</span></td>
		<!-- Mun_ID -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php
if ((!is_null($x_Mun_ID)) && ($x_Mun_ID <> "")) {
	$sSqlWrk = "SELECT DISTINCT `Mun_Descrip` FROM `Vit_Municipios`";
	$sTmp = $x_Mun_ID;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `Mun_ID` = " . $sTmp . "";
	$sSqlWrk .= " ORDER BY `Mun_Descrip` Asc";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = strtoupper($rowwrk["Mun_Descrip"]);
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
<input type="hidden" id="x_Mun_ID" name="x_Mun_ID" value="<?php echo htmlspecialchars(@$x_Mun_ID); ?>">
<?php }else{ ?>
<?php
if ((!is_null($x_Mun_ID)) && ($x_Mun_ID <> "")) {
	$sSqlWrk = "SELECT DISTINCT `Mun_Descrip` FROM `Vit_Municipios`";
	$sTmp = $x_Mun_ID;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `Mun_ID` = " . $sTmp . "";
	$sSqlWrk .= " ORDER BY `Mun_Descrip` Asc";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = strtoupper($rowwrk["Mun_Descrip"]);
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
		<!-- Emi_ArchivoKey -->
		<td align="center"><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php if ((!is_null($x_Emi_ArchivoKey)) && $x_Emi_ArchivoKey <> "") {  ?>
<input type="radio" name="a_x_Emi_ArchivoKey" value="1" checked>Keep&nbsp;
<input type="radio" name="a_x_Emi_ArchivoKey" value="2">Remove&nbsp;
<input type="radio" name="a_x_Emi_ArchivoKey" value="3">Replace<br>
<?php } else {?>
<input type="hidden" name="a_x_Emi_ArchivoKey" value="3">
<?php } ?>
<input type="file" id="x_Emi_ArchivoKey" name="x_Emi_ArchivoKey" size="30" onChange="if (this.form.a_x_Emi_ArchivoKey[2]) this.form.a_x_Emi_ArchivoKey[2].checked=true;">
<?php }else{ ?>
<?php if ((!is_null($x_Emi_ArchivoKey)) &&  $x_Emi_ArchivoKey <> "") { ?>
<a href="<?php echo ewUploadPathVita(0) . $x_Emi_ArchivoKey ?>" target="_blank"><i class="ri-key-line" style="font-size:24px; color:#689f38;"></i> <?php #echo $x_Emi_ArchivoKey; ?></a>
<?php }else{ ?>
<a href="javascript:void(0);"><i class="ri-key-line" style="font-size:24px; color:#CECECE;"></i></a>
<?php } ?>
<?php } ?>
</span></td>
		<!-- Emi_ArchivoCer -->
		<td align="center"><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php if ((!is_null($x_Emi_ArchivoCer)) && $x_Emi_ArchivoCer <> "") {  ?>
<input type="radio" name="a_x_Emi_ArchivoCer" value="1" checked>Keep&nbsp;
<input type="radio" name="a_x_Emi_ArchivoCer" value="2">Remove&nbsp;
<input type="radio" name="a_x_Emi_ArchivoCer" value="3">Replace<br>
<?php } else {?>
<input type="hidden" name="a_x_Emi_ArchivoCer" value="3">
<?php } ?>
<input type="file" id="x_Emi_ArchivoCer" name="x_Emi_ArchivoCer" size="30" onChange="if (this.form.a_x_Emi_ArchivoCer[2]) this.form.a_x_Emi_ArchivoCer[2].checked=true;">
<?php }else{ ?>
<?php if ((!is_null($x_Emi_ArchivoCer)) &&  $x_Emi_ArchivoCer <> "") { ?>
<a href="<?php echo ewUploadPathVita(0) . $x_Emi_ArchivoCer ?>" target="_blank"><i class="ri-shield-keyhole-fill" style="font-size:24px; color:#689f38;"></i> <?php #echo $x_Emi_ArchivoCer; ?></a>
<?php }else{ ?>
<a href="javascript:void(0);"><i class="ri-shield-keyhole-fill" style="font-size:24px; color:#CECECE;"></i></a>
<?php } ?>
<?php } ?>
</span></td>
		<!-- Emi_CertificadoKey -->
		<td align="center"><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php if ((!is_null($x_Emi_CertificadoKey)) && $x_Emi_CertificadoKey <> "") {  ?>
<input type="radio" name="a_x_Emi_CertificadoKey" value="1" checked>Keep&nbsp;
<input type="radio" name="a_x_Emi_CertificadoKey" value="2">Remove&nbsp;
<input type="radio" name="a_x_Emi_CertificadoKey" value="3">Replace<br>
<?php } else {?>
<input type="hidden" name="a_x_Emi_CertificadoKey" value="3">
<?php } ?>
<input type="file" id="x_Emi_CertificadoKey" name="x_Emi_CertificadoKey" size="30" onChange="if (this.form.a_x_Emi_CertificadoKey[2]) this.form.a_x_Emi_CertificadoKey[2].checked=true;">
<?php }else{ ?>
<?php if ((!is_null($x_Emi_CertificadoKey)) &&  $x_Emi_CertificadoKey <> "") { ?>
<a href="<?php echo ewUploadPathVita(0) . $x_Emi_CertificadoKey ?>" target="_blank"><i class="mdi mdi-file-key" style="font-size:24px; color:#689f38;"></i><?php //echo $x_Emi_CertificadoKey; ?></a>
<?php }else{ ?>
<a href="javascript:void(0);"><i class="mdi mdi-file-key" style="font-size:24px; color:#CECECE;"></i></a>
<?php } ?>
<?php } ?>
</span></td>
		<!-- Emi_CertificadoCer -->
		<td align="center"><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php if ((!is_null($x_Emi_CertificadoCer)) && $x_Emi_CertificadoCer <> "") {  ?>
<input type="radio" name="a_x_Emi_CertificadoCer" value="1" checked>Keep&nbsp;
<input type="radio" name="a_x_Emi_CertificadoCer" value="2">Remove&nbsp;
<input type="radio" name="a_x_Emi_CertificadoCer" value="3">Replace<br>
<?php } else {?>
<input type="hidden" name="a_x_Emi_CertificadoCer" value="3">
<?php } ?>
<input type="file" id="x_Emi_CertificadoCer" name="x_Emi_CertificadoCer" size="30" onChange="if (this.form.a_x_Emi_CertificadoCer[2]) this.form.a_x_Emi_CertificadoCer[2].checked=true;">
<?php }else{ ?>
<?php if ((!is_null($x_Emi_CertificadoCer)) &&  $x_Emi_CertificadoCer <> "") { ?>
<a href="<?php echo ewUploadPathVita(0) . $x_Emi_CertificadoCer ?>" target="_blank"><i class="mdi mdi-file-certificate" style="font-size:24px; color:#689f38;"></i></a>
<?php }else{ ?>
<a href="javascript:void(0);"><i class="mdi mdi-file-certificate" style="font-size:24px; color:#CECECE;"></i></a>
<?php } ?>
<?php } ?>
</span></td>
		<!-- Emi_Constancia -->
		<td align="center"><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php if ((!is_null($x_Emi_Constancia)) && $x_Emi_Constancia <> "") {  ?>
<input type="radio" name="a_x_Emi_Constancia" value="1" checked>Keep&nbsp;
<input type="radio" name="a_x_Emi_Constancia" value="2">Remove&nbsp;
<input type="radio" name="a_x_Emi_Constancia" value="3">Replace<br>
<?php } else {?>
<input type="hidden" name="a_x_Emi_Constancia" value="3">
<?php } ?>
<input type="file" id="x_Emi_Constancia" name="x_Emi_Constancia" size="30" onChange="if (this.form.a_x_Emi_Constancia[2]) this.form.a_x_Emi_Constancia[2].checked=true;">
<?php }else{ ?>
<?php if ((!is_null($x_Emi_Constancia)) &&  $x_Emi_Constancia <> "") { ?>
<a href="<?php echo ewUploadPathVita(0) . $x_Emi_Constancia ?>" target="_blank"><i class="ri-attachment-2" style="font-size:24px; color:#689f38;"></i><?php //echo $x_Emi_Constancia; ?></a>
<?php }else{ ?>
<a href="javascript:void(0);"><i class="ri-attachment-2" style="font-size:24px; color:#CECECE;"></i></a>
<?php } ?>
<?php } ?>
</span></td>
<?php if ($sExport == "") { ?>
<!--<td><span class="phpmaker"><a href="<?php if ($x_Emi_RFC <> "" AND $x_Mun_ID <> "") {echo "vit_emisorview.php?Emi_RFC=" . urlencode($x_Emi_RFC) . "&Mun_ID=" . urlencode($x_Mun_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">View</a></span></td>
<td><span class="phpmaker">
<?php if ($_SESSION["vita_proyecto_Key_Emi_RFC"] == $x_Emi_RFC && $_SESSION["vita_proyecto_Key_Mun_ID"] == $x_Mun_ID) { ?>
<a href="" onClick="if (EW_checkMyForm(document.vit_emisorlist)) document.vit_emisorlist.submit();return false;">Update</a>&nbsp;<a href="vit_emisorlist.php?a=cancel">Cancel</a>
<input type="hidden" name="a_list" value="update">
<input type="hidden" name="EW_Max_File_Size" value="200000000">
<?php } else { ?>
<a href="<?php if ($x_Emi_RFC <> "" AND $x_Mun_ID <> "") {echo "vit_emisorlist.php?a=edit&Emi_RFC=" . urlencode($x_Emi_RFC) . "&Mun_ID=" . urlencode($x_Mun_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Inline Edit</a>
<?php } ?>
</span></td>
<td><span class="phpmaker"><a href="<?php if ($x_Emi_RFC <> "" AND $x_Mun_ID <> "") {echo "vit_emisoredit.php?Emi_RFC=" . urlencode($x_Emi_RFC) . "&Mun_ID=" . urlencode($x_Mun_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_Emi_RFC <> "" AND $x_Mun_ID <> "") {echo "vit_emisordelete.php?Emi_RFC=" . urlencode($x_Emi_RFC) . "&Mun_ID=" . urlencode($x_Mun_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Delete</a></span></td>-->
<td><span class="phpmaker">
<div class="dropdown">
		<a href="#" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="false">
			<i class="ri-more-2-fill"></i>
		</a>		
		<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
			<li>
			<a class="dropdown-item" href="<?php if ($x_Emi_RFC <> "" AND $x_Mun_ID <> "") {echo "vit_emisoredit.php?Emi_RFC=" . urlencode($x_Emi_RFC) . "&Mun_ID=" . urlencode($x_Mun_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Editar</a>
			</li>
		</ul>
	</div>
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
</div>
<div class="d-flex justify-content-end mt-3">
	<div class="pagination-wrap hstack gap-2">

<?php if ($sExport == "") { ?>
<form action="emisores_listado.php" name="ewpagerform" id="ewpagerform">
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
	<td><a class="page-item pagination-prev" href="emisores_listado.php?start=1">|<</a></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($PrevStart == $nStartRec) { ?>
	<td><a class="page-item pagination-prev disabled"><</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-prev" href="emisores_listado.php?start=<?php echo $PrevStart; ?>"><</a></td>
	<?php } ?>
<!--current page number-->
	<td><input class="form-control form-control-sm" type="text" name="pageno" value="<?php echo intval(($nStartRec-1)/$nDisplayRecs+1); ?>" size="4"></td>
<!--next page button-->
	<?php if ($NextStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">></a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="emisores_listado.php?start=<?php echo $NextStart; ?>">></a></td>
	<?php  } ?>
<!--last page button-->
	<?php if ($LastStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">>|</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="emisores_listado.php?start=<?php echo $LastStart; ?>">>|</a></td>
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
if (EW_this.n_Emi_RFC && !EW_hasValue(EW_this.n_Emi_RFC, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.n_Emi_RFC, "TEXT", "Favor de ingresar - RFC"))
		return false;
}
if (EW_this.n_Mun_ID && !EW_hasValue(EW_this.n_Mun_ID, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.n_Mun_ID, "SELECT", "Favor de elegir - Municipio"))
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
                                            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Nuevo - Emisor</h5>
                                            <button type="button" class="btn-close text-reset"
                                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <!--end offcanvas-header-->
										<form name="vit_emisoradd" id="vit_emisoradd" action="vit_emisoradd.php" method="post" enctype="multipart/form-data" onSubmit="return EW_checkMyFormN(this);">
                                        <!--<form action="" class="d-flex flex-column justify-content-end h-100">-->
                                            <div class="offcanvas-body">
												<div class="mb-4">
                                                    <label for="RFC" class="form-label text-muted text-uppercase fw-semibold mb-3">RFC</label>                     
													<input class="form-control" type="text" name="n_Emi_RFC" id="n_Emi_RFC" size="30" maxlength="39" placeholder="RFC" value="">
                                                </div>
												<div class="mb-4">
                                                    <label for="Nombre" class="form-label text-muted text-uppercase fw-semibold mb-3">Nombre</label>
                                                    <input class="form-control" type="text" name="n_Emi_Nombre" id="n_Emi_Nombre" size="30" placeholder="Nombre" value="">
                                                </div>
												<div class="mb-4">
                                                    <label for="Paterno" class="form-label text-muted text-uppercase fw-semibold mb-3">Regimen Fiscal</label>
													<?php
													$x_Rec_TipoJornadaList = "<select name=\"n_Emi_RegimenFiscal\" class=\"form-select form-select\">";
													$x_Rec_TipoJornadaList .= "<option value=''>Regimen - Favor de elegir</option>";
													$sSqlWrk = "SELECT
													c_regimenfiscal.c_RegimenFiscal, 
													c_regimenfiscal.Descripcion															
													FROM
													c_regimenfiscal";
													#echo "<br />".$sSqlWrk;
													$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
													if ($rswrk) {
														$rowcntwrk = 0;
														while ($datawrk = phpmkr_fetch_array($rswrk)) {
															$x_Rec_TipoJornadaList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
															$x_Rec_TipoJornadaList .= ">" . $datawrk["c_RegimenFiscal"] . " - " . $datawrk["Descripcion"] . "</option>";
															$rowcntwrk++;
														}
													}
													@phpmkr_free_result($rswrk);
													$x_Rec_TipoJornadaList .= "</select>";
													echo $x_Rec_TipoJornadaList;
													?>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="country-select"
                                                        class="form-label text-muted text-uppercase fw-semibold mb-3">Rfc Patron</label>
														<input class="form-control"  type="text" name="n_Emi_RfcPatronOrigen" id="n_Emi_RfcPatronOrigen" size="30" maxlength="39" placeholder="Rfc Patron" value="">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="country-select"
                                                        class="form-label text-muted text-uppercase fw-semibold mb-3">Municipio</label>
														<?php
														$x_Mun_IDList = "<select class=\"form-control\" name=\"n_Mun_ID\">";
														$x_Mun_IDList .= "<option value=''>Municipio - Favor de elegir</option>";
														$sSqlWrk = "SELECT DISTINCT `Mun_ID`, `Mun_Descrip` FROM `Vit_Municipios`";
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
												<div class="mb-4">
                                                    <label for="Nombre" class="form-label text-muted text-uppercase fw-semibold mb-3">Archivo Key</label>
													<input class="form-control" type="file" id="n_Emi_ArchivoKey" name="n_Emi_ArchivoKey" size="30">													
                                                </div>
												<div class="mb-4">
                                                    <label for="Nombre" class="form-label text-muted text-uppercase fw-semibold mb-3">Archivo Cer</label>
													<input class="form-control" type="file" id="n_Emi_ArchivoCer" name="n_Emi_ArchivoCer" size="30">
                                                </div>
                                            </div>
                                            <!--end offcanvas-body-->
                                            <div class="offcanvas-footer border-top p-3 text-right hstack gap-2">
												<a class="btn btn-primary waves-effect waves-light w-100" href="emisores_listado.php?cmd=reset">Cancelar</a>
                                                <button type="submit" name="Action" class="btn btn-success waves-effect waves-light w-100" value="ADD">Agregar</button>
												<input type="hidden" name="a_add" value="A">
												<input type="hidden" name="EW_Max_File_Size" value="20000000">
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
				$nDisplayRecs = 10;  // Non-numeric, Load Default
			}
		}
		$_SESSION["vit_emisor_RecPerPage"] = $nDisplayRecs; // Save to Session

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_emisor_REC"] = $nStartRec;
	}else{
		if (@$_SESSION["vit_emisor_RecPerPage"] <> "") {
			$nDisplayRecs = $_SESSION["vit_emisor_RecPerPage"]; // Restore from Session
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
	global $x_Emi_RFC;
	global $x_Mun_ID;

	// Get the keys for master table
	if (strlen(@$_GET["a"]) > 0) {
		$sAction = @$_GET["a"];
		if (strtoupper($sAction) == "EDIT") { // Change to Inline Edit Mode
			$bInlineEdit = true;
			if (strlen(@$_GET["Emi_RFC"]) > 0) {
				$x_Emi_RFC = $_GET["Emi_RFC"];
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
					$_SESSION["vita_proyecto_Key_Emi_RFC"] = $x_Emi_RFC; // Set up Inline Edit key
					$_SESSION["vita_proyecto_Key_Mun_ID"] = $x_Mun_ID; // Set up Inline Edit key
				}
			}
		}
		elseif (strtoupper($sAction) == "CANCEL")  // Switch out of Inline Edit Mode
		{
			$_SESSION["vita_proyecto_Key_Emi_RFC"] = ""; // Clear Inline Edit key
			$_SESSION["vita_proyecto_Key_Mun_ID"] = ""; // Clear Inline Edit key
		}
	}
	else
	{
		$sAction = @$_POST["a_list"];
		if (strtoupper($sAction) == "UPDATE") { // Update Record

			// Get fields from form
			global $x_Emi_RFC;
			$x_Emi_RFC = @$_POST["x_Emi_RFC"];
			global $x_Emi_Nombre;
			$x_Emi_Nombre = @$_POST["x_Emi_Nombre"];
			global $x_Emi_RegimenFiscal;
			$x_Emi_RegimenFiscal = @$_POST["x_Emi_RegimenFiscal"];
			global $x_Emi_Clave;
			$x_Emi_Clave = @$_POST["x_Emi_Clave"];
			global $x_Emi_FacAtrAdquirente;
			$x_Emi_FacAtrAdquirente = @$_POST["x_Emi_FacAtrAdquirente"];
			global $x_Emi_Curp;
			$x_Emi_Curp = @$_POST["x_Emi_Curp"];
			global $x_Emi_RegistroPatronal;
			$x_Emi_RegistroPatronal = @$_POST["x_Emi_RegistroPatronal"];
			global $x_Emi_RfcPatronOrigen;
			$x_Emi_RfcPatronOrigen = @$_POST["x_Emi_RfcPatronOrigen"];
			global $x_Emi_EntidadSNCF;
			$x_Emi_EntidadSNCF = @$_POST["x_Emi_EntidadSNCF"];
			global $x_Mun_ID;
			$x_Mun_ID = @$_POST["x_Mun_ID"];
			global $x_Emi_NomCorto;
			$x_Emi_NomCorto = @$_POST["x_Emi_NomCorto"];
			global $x_Emi_ArchivoKey;
			$x_Emi_ArchivoKey = @$_POST["x_Emi_ArchivoKey"];
			global $x_Emi_ArchivoCer;
			$x_Emi_ArchivoCer = @$_POST["x_Emi_ArchivoCer"];
			global $x_Emi_ArchivoPas;
			$x_Emi_ArchivoPas = @$_POST["x_Emi_ArchivoPas"];
			global $x_Emi_CertificadoKey;
			$x_Emi_CertificadoKey = @$_POST["x_Emi_CertificadoKey"];
			global $x_Emi_CertificadoCer;
			$x_Emi_CertificadoCer = @$_POST["x_Emi_CertificadoCer"];
			global $x_Emi_CertificadoPas;
			$x_Emi_CertificadoPas = @$_POST["x_Emi_CertificadoPas"];
			global $x_Emi_Constancia;
			$x_Emi_Constancia = @$_POST["x_Emi_Constancia"];
			if ($_SESSION["vita_proyecto_Key_Emi_RFC"] == $x_Emi_RFC && $_SESSION["vita_proyecto_Key_Mun_ID"] == $x_Mun_ID) {
				if (InlineEditData($conn)) {
					$_SESSION["ewmsg"] = "Update Record Successful";
				}
			}
		}
		$_SESSION["vita_proyecto_Key_Emi_RFC"] = ""; // Clear Inline Edit key
		$_SESSION["vita_proyecto_Key_Mun_ID"] = ""; // Clear Inline Edit key
	}
}

//-------------------------------------------------------------------------------
// Function SetUpAdvancedSearch
// - Set up Advanced Search parameter based on querystring parameters from Advanced Search Page
// - Variables setup: sSrchAdvanced

function SetUpAdvancedSearch()
{
	global $sSrchAdvanced;

	// Field Emi_RFC
	$x_Emi_RFC = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Emi_RFC"]) : @$_GET["x_Emi_RFC"];
	$arrFldOpr = "";
	$z_Emi_RFC = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Emi_RFC"]) : @$_GET["z_Emi_RFC"];
	$arrFldOpr = explode(",",$z_Emi_RFC);
	if ($x_Emi_RFC <> "") {
		$sSrchAdvanced .= "`Emi_RFC` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Emi_RFC) : $x_Emi_RFC; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Emi_Nombre
	$x_Emi_Nombre = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Emi_Nombre"]) : @$_GET["x_Emi_Nombre"];
	$arrFldOpr = "";
	$z_Emi_Nombre = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Emi_Nombre"]) : @$_GET["z_Emi_Nombre"];
	$arrFldOpr = explode(",",$z_Emi_Nombre);
	if ($x_Emi_Nombre <> "") {
		$sSrchAdvanced .= "`Emi_Nombre` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Emi_Nombre) : $x_Emi_Nombre; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Emi_RegimenFiscal
	$x_Emi_RegimenFiscal = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Emi_RegimenFiscal"]) : @$_GET["x_Emi_RegimenFiscal"];
	$arrFldOpr = "";
	$z_Emi_RegimenFiscal = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Emi_RegimenFiscal"]) : @$_GET["z_Emi_RegimenFiscal"];
	$arrFldOpr = explode(",",$z_Emi_RegimenFiscal);
	if ($x_Emi_RegimenFiscal <> "") {
		$sSrchAdvanced .= "`Emi_RegimenFiscal` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Emi_RegimenFiscal) : $x_Emi_RegimenFiscal; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Emi_Clave
	$x_Emi_Clave = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Emi_Clave"]) : @$_GET["x_Emi_Clave"];
	$arrFldOpr = "";
	$z_Emi_Clave = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Emi_Clave"]) : @$_GET["z_Emi_Clave"];
	$arrFldOpr = explode(",",$z_Emi_Clave);
	if ($x_Emi_Clave <> "") {
		$sSrchAdvanced .= "`Emi_Clave` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Emi_Clave) : $x_Emi_Clave; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Emi_FacAtrAdquirente
	$x_Emi_FacAtrAdquirente = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Emi_FacAtrAdquirente"]) : @$_GET["x_Emi_FacAtrAdquirente"];
	$arrFldOpr = "";
	$z_Emi_FacAtrAdquirente = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Emi_FacAtrAdquirente"]) : @$_GET["z_Emi_FacAtrAdquirente"];
	$arrFldOpr = explode(",",$z_Emi_FacAtrAdquirente);
	if ($x_Emi_FacAtrAdquirente <> "") {
		$sSrchAdvanced .= "`Emi_FacAtrAdquirente` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Emi_FacAtrAdquirente) : $x_Emi_FacAtrAdquirente; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Emi_Curp
	$x_Emi_Curp = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Emi_Curp"]) : @$_GET["x_Emi_Curp"];
	$arrFldOpr = "";
	$z_Emi_Curp = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Emi_Curp"]) : @$_GET["z_Emi_Curp"];
	$arrFldOpr = explode(",",$z_Emi_Curp);
	if ($x_Emi_Curp <> "") {
		$sSrchAdvanced .= "`Emi_Curp` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Emi_Curp) : $x_Emi_Curp; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Emi_RegistroPatronal
	$x_Emi_RegistroPatronal = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Emi_RegistroPatronal"]) : @$_GET["x_Emi_RegistroPatronal"];
	$arrFldOpr = "";
	$z_Emi_RegistroPatronal = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Emi_RegistroPatronal"]) : @$_GET["z_Emi_RegistroPatronal"];
	$arrFldOpr = explode(",",$z_Emi_RegistroPatronal);
	if ($x_Emi_RegistroPatronal <> "") {
		$sSrchAdvanced .= "`Emi_RegistroPatronal` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Emi_RegistroPatronal) : $x_Emi_RegistroPatronal; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Emi_RfcPatronOrigen
	$x_Emi_RfcPatronOrigen = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Emi_RfcPatronOrigen"]) : @$_GET["x_Emi_RfcPatronOrigen"];
	$arrFldOpr = "";
	$z_Emi_RfcPatronOrigen = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Emi_RfcPatronOrigen"]) : @$_GET["z_Emi_RfcPatronOrigen"];
	$arrFldOpr = explode(",",$z_Emi_RfcPatronOrigen);
	if ($x_Emi_RfcPatronOrigen <> "") {
		$sSrchAdvanced .= "`Emi_RfcPatronOrigen` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Emi_RfcPatronOrigen) : $x_Emi_RfcPatronOrigen; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Emi_EntidadSNCF
	$x_Emi_EntidadSNCF = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Emi_EntidadSNCF"]) : @$_GET["x_Emi_EntidadSNCF"];
	$arrFldOpr = "";
	$z_Emi_EntidadSNCF = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Emi_EntidadSNCF"]) : @$_GET["z_Emi_EntidadSNCF"];
	$arrFldOpr = explode(",",$z_Emi_EntidadSNCF);
	if ($x_Emi_EntidadSNCF <> "") {
		$sSrchAdvanced .= "`Emi_EntidadSNCF` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Emi_EntidadSNCF) : $x_Emi_EntidadSNCF; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Mun_ID
	$x_Mun_ID = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Mun_ID"]) : @$_GET["x_Mun_ID"];
	$arrFldOpr = "";
	$z_Mun_ID = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Mun_ID"]) : @$_GET["z_Mun_ID"];
	$arrFldOpr = explode(",",$z_Mun_ID);
	if ($x_Mun_ID <> "") {
		$sSrchAdvanced .= "`Mun_ID` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Mun_ID) : $x_Mun_ID; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Emi_NomCorto
	$x_Emi_NomCorto = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Emi_NomCorto"]) : @$_GET["x_Emi_NomCorto"];
	$arrFldOpr = "";
	$z_Emi_NomCorto = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Emi_NomCorto"]) : @$_GET["z_Emi_NomCorto"];
	$arrFldOpr = explode(",",$z_Emi_NomCorto);
	if ($x_Emi_NomCorto <> "") {
		$sSrchAdvanced .= "`Emi_NomCorto` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Emi_NomCorto) : $x_Emi_NomCorto; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Emi_ArchivoKey
	$x_Emi_ArchivoKey = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Emi_ArchivoKey"]) : @$_GET["x_Emi_ArchivoKey"];
	$arrFldOpr = "";
	$z_Emi_ArchivoKey = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Emi_ArchivoKey"]) : @$_GET["z_Emi_ArchivoKey"];
	$arrFldOpr = explode(",",$z_Emi_ArchivoKey);
	if ($x_Emi_ArchivoKey <> "") {
		$sSrchAdvanced .= "`Emi_ArchivoKey` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Emi_ArchivoKey) : $x_Emi_ArchivoKey; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Emi_ArchivoCer
	$x_Emi_ArchivoCer = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Emi_ArchivoCer"]) : @$_GET["x_Emi_ArchivoCer"];
	$arrFldOpr = "";
	$z_Emi_ArchivoCer = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Emi_ArchivoCer"]) : @$_GET["z_Emi_ArchivoCer"];
	$arrFldOpr = explode(",",$z_Emi_ArchivoCer);
	if ($x_Emi_ArchivoCer <> "") {
		$sSrchAdvanced .= "`Emi_ArchivoCer` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Emi_ArchivoCer) : $x_Emi_ArchivoCer; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Emi_ArchivoPas
	$x_Emi_ArchivoPas = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Emi_ArchivoPas"]) : @$_GET["x_Emi_ArchivoPas"];
	$arrFldOpr = "";
	$z_Emi_ArchivoPas = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Emi_ArchivoPas"]) : @$_GET["z_Emi_ArchivoPas"];
	$arrFldOpr = explode(",",$z_Emi_ArchivoPas);
	if ($x_Emi_ArchivoPas <> "") {
		$sSrchAdvanced .= "`Emi_ArchivoPas` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Emi_ArchivoPas) : $x_Emi_ArchivoPas; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Emi_CertificadoKey
	$x_Emi_CertificadoKey = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Emi_CertificadoKey"]) : @$_GET["x_Emi_CertificadoKey"];
	$arrFldOpr = "";
	$z_Emi_CertificadoKey = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Emi_CertificadoKey"]) : @$_GET["z_Emi_CertificadoKey"];
	$arrFldOpr = explode(",",$z_Emi_CertificadoKey);
	if ($x_Emi_CertificadoKey <> "") {
		$sSrchAdvanced .= "`Emi_CertificadoKey` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Emi_CertificadoKey) : $x_Emi_CertificadoKey; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Emi_CertificadoCer
	$x_Emi_CertificadoCer = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Emi_CertificadoCer"]) : @$_GET["x_Emi_CertificadoCer"];
	$arrFldOpr = "";
	$z_Emi_CertificadoCer = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Emi_CertificadoCer"]) : @$_GET["z_Emi_CertificadoCer"];
	$arrFldOpr = explode(",",$z_Emi_CertificadoCer);
	if ($x_Emi_CertificadoCer <> "") {
		$sSrchAdvanced .= "`Emi_CertificadoCer` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Emi_CertificadoCer) : $x_Emi_CertificadoCer; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Emi_CertificadoPas
	$x_Emi_CertificadoPas = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Emi_CertificadoPas"]) : @$_GET["x_Emi_CertificadoPas"];
	$arrFldOpr = "";
	$z_Emi_CertificadoPas = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Emi_CertificadoPas"]) : @$_GET["z_Emi_CertificadoPas"];
	$arrFldOpr = explode(",",$z_Emi_CertificadoPas);
	if ($x_Emi_CertificadoPas <> "") {
		$sSrchAdvanced .= "`Emi_CertificadoPas` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Emi_CertificadoPas) : $x_Emi_CertificadoPas; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Emi_Constancia
	$x_Emi_Constancia = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Emi_Constancia"]) : @$_GET["x_Emi_Constancia"];
	$arrFldOpr = "";
	$z_Emi_Constancia = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Emi_Constancia"]) : @$_GET["z_Emi_Constancia"];
	$arrFldOpr = explode(",",$z_Emi_Constancia);
	if ($x_Emi_Constancia <> "") {
		$sSrchAdvanced .= "`Emi_Constancia` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Emi_Constancia) : $x_Emi_Constancia; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}
	if (strlen($sSrchAdvanced) > 4) {
		$sSrchAdvanced = substr($sSrchAdvanced, 0, strlen($sSrchAdvanced)-4);
	}
}

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`Emi_RFC` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Emi_Nombre` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Emi_RegimenFiscal` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Emi_Clave` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Emi_FacAtrAdquirente` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Emi_Curp` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Emi_RegistroPatronal` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Emi_RfcPatronOrigen` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Emi_EntidadSNCF` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Emi_NomCorto` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Emi_ArchivoKey` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Emi_ArchivoCer` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Emi_ArchivoPas` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Emi_CertificadoKey` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Emi_CertificadoCer` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Emi_CertificadoPas` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Emi_Constancia` LIKE '%" . $sKeyword . "%' OR ";
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

		// Field Emi_RFC
		if ($sOrder == "Emi_RFC") {
			$sSortField = "`Emi_RFC`";
			$sLastSort = @$_SESSION["vit_emisor_x_Emi_RFC_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_emisor_x_Emi_RFC_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_emisor_x_Emi_RFC_Sort"] <> "") { @$_SESSION["vit_emisor_x_Emi_RFC_Sort"] = ""; }
		}

		// Field Emi_Nombre
		if ($sOrder == "Emi_Nombre") {
			$sSortField = "`Emi_Nombre`";
			$sLastSort = @$_SESSION["vit_emisor_x_Emi_Nombre_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_emisor_x_Emi_Nombre_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_emisor_x_Emi_Nombre_Sort"] <> "") { @$_SESSION["vit_emisor_x_Emi_Nombre_Sort"] = ""; }
		}

		// Field Emi_RfcPatronOrigen
		if ($sOrder == "Emi_RfcPatronOrigen") {
			$sSortField = "`Emi_RfcPatronOrigen`";
			$sLastSort = @$_SESSION["vit_emisor_x_Emi_RfcPatronOrigen_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_emisor_x_Emi_RfcPatronOrigen_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_emisor_x_Emi_RfcPatronOrigen_Sort"] <> "") { @$_SESSION["vit_emisor_x_Emi_RfcPatronOrigen_Sort"] = ""; }
		}

		// Field Mun_ID
		if ($sOrder == "Mun_ID") {
			$sSortField = "`Mun_ID`";
			$sLastSort = @$_SESSION["vit_emisor_x_Mun_ID_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_emisor_x_Mun_ID_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_emisor_x_Mun_ID_Sort"] <> "") { @$_SESSION["vit_emisor_x_Mun_ID_Sort"] = ""; }
		}

		// Field Emi_ArchivoKey
		if ($sOrder == "Emi_ArchivoKey") {
			$sSortField = "`Emi_ArchivoKey`";
			$sLastSort = @$_SESSION["vit_emisor_x_Emi_ArchivoKey_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_emisor_x_Emi_ArchivoKey_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_emisor_x_Emi_ArchivoKey_Sort"] <> "") { @$_SESSION["vit_emisor_x_Emi_ArchivoKey_Sort"] = ""; }
		}

		// Field Emi_ArchivoCer
		if ($sOrder == "Emi_ArchivoCer") {
			$sSortField = "`Emi_ArchivoCer`";
			$sLastSort = @$_SESSION["vit_emisor_x_Emi_ArchivoCer_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_emisor_x_Emi_ArchivoCer_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_emisor_x_Emi_ArchivoCer_Sort"] <> "") { @$_SESSION["vit_emisor_x_Emi_ArchivoCer_Sort"] = ""; }
		}

		// Field Emi_CertificadoKey
		if ($sOrder == "Emi_CertificadoKey") {
			$sSortField = "`Emi_CertificadoKey`";
			$sLastSort = @$_SESSION["vit_emisor_x_Emi_CertificadoKey_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_emisor_x_Emi_CertificadoKey_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_emisor_x_Emi_CertificadoKey_Sort"] <> "") { @$_SESSION["vit_emisor_x_Emi_CertificadoKey_Sort"] = ""; }
		}

		// Field Emi_CertificadoCer
		if ($sOrder == "Emi_CertificadoCer") {
			$sSortField = "`Emi_CertificadoCer`";
			$sLastSort = @$_SESSION["vit_emisor_x_Emi_CertificadoCer_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_emisor_x_Emi_CertificadoCer_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_emisor_x_Emi_CertificadoCer_Sort"] <> "") { @$_SESSION["vit_emisor_x_Emi_CertificadoCer_Sort"] = ""; }
		}

		// Field Emi_Constancia
		if ($sOrder == "Emi_Constancia") {
			$sSortField = "`Emi_Constancia`";
			$sLastSort = @$_SESSION["vit_emisor_x_Emi_Constancia_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_emisor_x_Emi_Constancia_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_emisor_x_Emi_Constancia_Sort"] <> "") { @$_SESSION["vit_emisor_x_Emi_Constancia_Sort"] = ""; }
		}
		$_SESSION["vit_emisor_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["vit_emisor_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["vit_emisor_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["vit_emisor_OrderBy"] = $sOrderBy;
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
		$_SESSION["vit_emisor_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["vit_emisor_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["vit_emisor_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["vit_emisor_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["vit_emisor_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["vit_emisor_REC"] = $nStartRec;
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
			$_SESSION["vit_emisor_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["vit_emisor_searchwhere"] = $sSrchWhere;
			$_SESSION["vita_proyecto_InlineEdit_Key"] = ""; // Clear Inline Edit key

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["vit_emisor_OrderBy"] = $sOrderBy;
			if (@$_SESSION["vit_emisor_x_Emi_RFC_Sort"] <> "") { $_SESSION["vit_emisor_x_Emi_RFC_Sort"] = ""; }
			if (@$_SESSION["vit_emisor_x_Emi_Nombre_Sort"] <> "") { $_SESSION["vit_emisor_x_Emi_Nombre_Sort"] = ""; }
			if (@$_SESSION["vit_emisor_x_Emi_RfcPatronOrigen_Sort"] <> "") { $_SESSION["vit_emisor_x_Emi_RfcPatronOrigen_Sort"] = ""; }
			if (@$_SESSION["vit_emisor_x_Mun_ID_Sort"] <> "") { $_SESSION["vit_emisor_x_Mun_ID_Sort"] = ""; }
			if (@$_SESSION["vit_emisor_x_Emi_ArchivoKey_Sort"] <> "") { $_SESSION["vit_emisor_x_Emi_ArchivoKey_Sort"] = ""; }
			if (@$_SESSION["vit_emisor_x_Emi_ArchivoCer_Sort"] <> "") { $_SESSION["vit_emisor_x_Emi_ArchivoCer_Sort"] = ""; }
			if (@$_SESSION["vit_emisor_x_Emi_CertificadoKey_Sort"] <> "") { $_SESSION["vit_emisor_x_Emi_CertificadoKey_Sort"] = ""; }
			if (@$_SESSION["vit_emisor_x_Emi_CertificadoCer_Sort"] <> "") { $_SESSION["vit_emisor_x_Emi_CertificadoCer_Sort"] = ""; }
			if (@$_SESSION["vit_emisor_x_Emi_Constancia_Sort"] <> "") { $_SESSION["vit_emisor_x_Emi_Constancia_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_emisor_REC"] = $nStartRec;
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
		$GLOBALS["x_Emi_ArchivoPas"] = $row["Emi_ArchivoPas"];
		$GLOBALS["x_Emi_CertificadoKey"] = $row["Emi_CertificadoKey"];
		$GLOBALS["x_Emi_CertificadoCer"] = $row["Emi_CertificadoCer"];
		$GLOBALS["x_Emi_CertificadoPas"] = $row["Emi_CertificadoPas"];
		$GLOBALS["x_Emi_Constancia"] = $row["Emi_Constancia"];
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
		$bInlineEditData = false; // Update Failed
	}else{

		// check file size
		$EW_MaxFileSize = @$_POST["EW_Max_File_Size"];
		if (!empty($_FILES["x_Emi_ArchivoKey"]["size"])) {
			if (!empty($EW_MaxFileSize) && $_FILES["x_Emi_ArchivoKey"]["size"] > $EW_MaxFileSize) {
				die("Max. file upload size exceeded");
			}
		}
		$a_x_Emi_ArchivoKey = @$_POST["a_x_Emi_ArchivoKey"];
		if (!empty($_FILES["x_Emi_ArchivoCer"]["size"])) {
			if (!empty($EW_MaxFileSize) && $_FILES["x_Emi_ArchivoCer"]["size"] > $EW_MaxFileSize) {
				die("Max. file upload size exceeded");
			}
		}
		$a_x_Emi_ArchivoCer = @$_POST["a_x_Emi_ArchivoCer"];
		if (!empty($_FILES["x_Emi_CertificadoKey"]["size"])) {
			if (!empty($EW_MaxFileSize) && $_FILES["x_Emi_CertificadoKey"]["size"] > $EW_MaxFileSize) {
				die("Max. file upload size exceeded");
			}
		}
		$a_x_Emi_CertificadoKey = @$_POST["a_x_Emi_CertificadoKey"];
		if (!empty($_FILES["x_Emi_CertificadoCer"]["size"])) {
			if (!empty($EW_MaxFileSize) && $_FILES["x_Emi_CertificadoCer"]["size"] > $EW_MaxFileSize) {
				die("Max. file upload size exceeded");
			}
		}
		$a_x_Emi_CertificadoCer = @$_POST["a_x_Emi_CertificadoCer"];
		if (!empty($_FILES["x_Emi_Constancia"]["size"])) {
			if (!empty($EW_MaxFileSize) && $_FILES["x_Emi_Constancia"]["size"] > $EW_MaxFileSize) {
				die("Max. file upload size exceeded");
			}
		}
		$a_x_Emi_Constancia = @$_POST["a_x_Emi_Constancia"];
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_RFC"]) : $GLOBALS["x_Emi_RFC"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_RFC`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_Nombre"]) : $GLOBALS["x_Emi_Nombre"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_Nombre`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_RfcPatronOrigen"]) : $GLOBALS["x_Emi_RfcPatronOrigen"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_RfcPatronOrigen`"] = $theValue;
		$theValue = ($GLOBALS["x_Mun_ID"] != "") ? intval($GLOBALS["x_Mun_ID"]) : "NULL";
		$fieldList["`Mun_ID`"] = $theValue;
			if (is_uploaded_file($_FILES["x_Emi_ArchivoKey"]["tmp_name"])) {
				$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_Emi_ArchivoKey"]["name"]);
						if (!move_uploaded_file($_FILES["x_Emi_ArchivoKey"]["tmp_name"], $destfile)) // move file to destination path
						die("You didn't upload a file or the file couldn't be moved to" . $destfile);

				// File Name
				$theName = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes(ewUploadFileName($_FILES["x_Emi_ArchivoKey"]["name"])) : ewUploadFileName($_FILES["x_Emi_ArchivoKey"]["name"]);
				$fieldList["`Emi_ArchivoKey`"] = " '" . $theName . "'";
				@unlink($_FILES["x_Emi_ArchivoKey"]["tmp_name"]);
			}
			if (is_uploaded_file($_FILES["x_Emi_ArchivoCer"]["tmp_name"])) {
				$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_Emi_ArchivoCer"]["name"]);
						if (!move_uploaded_file($_FILES["x_Emi_ArchivoCer"]["tmp_name"], $destfile)) // move file to destination path
						die("You didn't upload a file or the file couldn't be moved to" . $destfile);

				// File Name
				$theName = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes(ewUploadFileName($_FILES["x_Emi_ArchivoCer"]["name"])) : ewUploadFileName($_FILES["x_Emi_ArchivoCer"]["name"]);
				$fieldList["`Emi_ArchivoCer`"] = " '" . $theName . "'";
				@unlink($_FILES["x_Emi_ArchivoCer"]["tmp_name"]);
			}
			if (is_uploaded_file($_FILES["x_Emi_CertificadoKey"]["tmp_name"])) {
				$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_Emi_CertificadoKey"]["name"]);
						if (!move_uploaded_file($_FILES["x_Emi_CertificadoKey"]["tmp_name"], $destfile)) // move file to destination path
						die("You didn't upload a file or the file couldn't be moved to" . $destfile);

				// File Name
				$theName = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes(ewUploadFileName($_FILES["x_Emi_CertificadoKey"]["name"])) : ewUploadFileName($_FILES["x_Emi_CertificadoKey"]["name"]);
				$fieldList["`Emi_CertificadoKey`"] = " '" . $theName . "'";
				@unlink($_FILES["x_Emi_CertificadoKey"]["tmp_name"]);
			}
			if (is_uploaded_file($_FILES["x_Emi_CertificadoCer"]["tmp_name"])) {
				$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_Emi_CertificadoCer"]["name"]);
						if (!move_uploaded_file($_FILES["x_Emi_CertificadoCer"]["tmp_name"], $destfile)) // move file to destination path
						die("You didn't upload a file or the file couldn't be moved to" . $destfile);

				// File Name
				$theName = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes(ewUploadFileName($_FILES["x_Emi_CertificadoCer"]["name"])) : ewUploadFileName($_FILES["x_Emi_CertificadoCer"]["name"]);
				$fieldList["`Emi_CertificadoCer`"] = " '" . $theName . "'";
				@unlink($_FILES["x_Emi_CertificadoCer"]["tmp_name"]);
			}
			if (is_uploaded_file($_FILES["x_Emi_Constancia"]["tmp_name"])) {
				$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_Emi_Constancia"]["name"]);
						if (!move_uploaded_file($_FILES["x_Emi_Constancia"]["tmp_name"], $destfile)) // move file to destination path
						die("You didn't upload a file or the file couldn't be moved to" . $destfile);

				// File Name
				$theName = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes(ewUploadFileName($_FILES["x_Emi_Constancia"]["name"])) : ewUploadFileName($_FILES["x_Emi_Constancia"]["name"]);
				$fieldList["`Emi_Constancia`"] = " '" . $theName . "'";
				@unlink($_FILES["x_Emi_Constancia"]["tmp_name"]);
			}

		// update
		$sSql = "UPDATE `vit_emisor` SET ";
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
