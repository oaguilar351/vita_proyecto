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
$x_Cfdi_ID = Null; 
$ox_Cfdi_ID = Null;
$x_Cfdi_Version = Null; 
$ox_Cfdi_Version = Null;
$x_Cfdi_Serie = Null; 
$ox_Cfdi_Serie = Null;
$x_Cfdi_Folio = Null; 
$ox_Cfdi_Folio = Null;
$x_Cfdi_Fecha = Null; 
$ox_Cfdi_Fecha = Null;
$x_Cfdi_Sello = Null; 
$ox_Cfdi_Sello = Null;
$x_c_FormaPago = Null; 
$ox_c_FormaPago = Null;
$x_Cfdi_Certificado = Null; 
$ox_Cfdi_Certificado = Null;
$x_Cfdi_NoCertificado = Null; 
$ox_Cfdi_NoCertificado = Null;
$x_Cfdi_CondicionesDePago = Null; 
$ox_Cfdi_CondicionesDePago = Null;
$x_Cfdi_Subtotal = Null; 
$ox_Cfdi_Subtotal = Null;
$x_Cfdi_Descuento = Null; 
$ox_Cfdi_Descuento = Null;
$x_c_Moneda = Null; 
$ox_c_Moneda = Null;
$x_Cfdi_TipoCambio = Null; 
$ox_Cfdi_TipoCambio = Null;
$x_Cfdi_Total = Null; 
$ox_Cfdi_Total = Null;
$x_c_TipoDeComprobante = Null; 
$ox_c_TipoDeComprobante = Null;
$x_c_Exportacion = Null; 
$ox_c_Exportacion = Null;
$x_c_MetodoPago = Null; 
$ox_c_MetodoPago = Null;
$x_c_CodigoPostal = Null; 
$ox_c_CodigoPostal = Null;
$x_Cfdi_Confirmacion = Null; 
$ox_Cfdi_Confirmacion = Null;
$x_Emi_RFC = Null; 
$ox_Emi_RFC = Null;
$x_Rec_RFC = Null; 
$ox_Rec_RFC = Null;
$x_Cfdi_Error = Null; 
$ox_Cfdi_Error = Null;
$x_Cfdi_UsoCFDI = Null; 
$ox_Cfdi_UsoCFDI = Null;
$x_Cfdi_UUID = Null; 
$ox_Cfdi_UUID = Null;
$x_Cfdi_Retcode = Null; 
$ox_Cfdi_Retcode = Null;
$x_Cfdi_Timestamp = Null; 
$ox_Cfdi_Timestamp = Null;
$x_Cfdi_Acuse = Null; 
$ox_Cfdi_Acuse = Null;
$x_Cfdi_Status = Null; 
$ox_Cfdi_Status = Null;
$x_Mun_ID = Null; 
$ox_Mun_ID = Null;
$x_Cfdi_CreationDate = Null; 
$ox_Cfdi_CreationDate = Null;
$x_Cfdi_Cotejado = Null; 
$ox_Cfdi_Cotejado = Null;
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
	$_SESSION["vit_comprobantes_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["vit_comprobantes_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["vit_comprobantes_searchwhere"];
}

// Build SQL
$sSql = "SELECT * FROM `vit_comprobantes`";

// Load Default Filter
if(@$_SESSION["project1_status_Municipio"] == ""){
$sDefaultFilter = "";
}else{
$sDefaultFilter = "vit_comprobantes.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
}
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
if(@$_SESSION["project1_status_Perfil"] == "1" && @$sWhere==""){
$sSql .= " LIMIT 1000";
}
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
<?php
#echo $sSql; // Uncomment to show SQL for debugging
// Set up Record Set
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
/*$sSqlT = "SELECT COUNT(Cfdi_ID) FROM `vit_comprobantes`";
$rsT = phpmkr_query($sSqlT,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSqlT);*/
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Comprobantes</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Modulos</a></li>
                                        <li class="breadcrumb-item active">Comprobantes</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
					
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card" id="leadsList">
                                <div class="card-header border-0">

                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm-3">
                                            <div class="search-box">
												<form action="comprobantes_listado.php">
												<table border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td><span class="phpmaker">
															<input type="text" name="psearch" class="form-control search">
															<input type="hidden" name="psearchtype" value="" checked>
														</span></td>
													</tr>
												</table>
												</form>
                                                <!--<input type="text" class="form-control search"
                                                    placeholder="Search for...">
                                                <i class="ri-search-line search-icon"></i>-->
                                            </div>
                                        </div>
                                        <div class="col-sm-auto ms-auto">
											
                                            <div class="hstack gap-2"> 
												<?php if(@$_SESSION["vit_comprobantes_OrderBy"]!=""){ ?>
												<a class="btn btn-light" href="comprobantes_listado.php?cmd=resetsort" title="Quitar Orden"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>X</a>
												<?php } ?>											
												<?php if(@$sWhere!="" && @$_SESSION["vit_comprobantes_OrderBy"]==""){ ?>
												<a class="btn btn-light" href="comprobantes_listado.php?cmd=reset" title="Quitar Filtros"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>F</a>
												<?php } ?>
                                                <button type="button" class="btn btn-info" data-bs-toggle="offcanvas"
                                                    href="#offcanvasExample"><i class="ri-filter-3-line align-bottom me-1" title="Elegir Filtros"></i> Filtros</button>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <div class="table-responsive table-card">
<?php if ($nTotalRecs > 0)  { ?>										
                                            <table class="table align-middle" id="customerTable">
                                                <thead class="table-light">
                                                    <tr>
													<?php if(@$sWhere!=""){ ?>
                                                        <th>
															<a href="comprobantes_listado.php?order=<?php echo urlencode("Cfdi_Version"); ?>">
															Version&nbsp;(*)
															<?php if (@$_SESSION["vit_comprobantes_x_Cfdi_Version_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_comprobantes_x_Cfdi_Version_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
															</a>
																</th>
																<th>
															<a href="comprobantes_listado.php?order=<?php echo urlencode("Cfdi_Serie"); ?>">
															Serie&nbsp;(*)
															<?php if (@$_SESSION["vit_comprobantes_x_Cfdi_Serie_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_comprobantes_x_Cfdi_Serie_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
															</a>
																</th>
																<th>
															<a href="comprobantes_listado.php?order=<?php echo urlencode("Cfdi_Folio"); ?>">
															Folio
															<?php if (@$_SESSION["vit_comprobantes_x_Cfdi_Folio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_comprobantes_x_Cfdi_Folio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
															</a>
																</th>
																<th>
															<a href="comprobantes_listado.php?order=<?php echo urlencode("Cfdi_Fecha"); ?>">
															Fecha
															<?php if (@$_SESSION["vit_comprobantes_x_Cfdi_Fecha_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_comprobantes_x_Cfdi_Fecha_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
															</a>
																</th>
																<th>
															<a href="comprobantes_listado.php?order=<?php echo urlencode("Cfdi_Subtotal"); ?>">
															Subtotal
															<?php if (@$_SESSION["vit_comprobantes_x_Cfdi_Subtotal_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_comprobantes_x_Cfdi_Subtotal_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
															</a>
																</th>
																<th>
															<a href="comprobantes_listado.php?order=<?php echo urlencode("Cfdi_Descuento"); ?>">
															Descuento
															<?php if (@$_SESSION["vit_comprobantes_x_Cfdi_Descuento_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_comprobantes_x_Cfdi_Descuento_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
															</a>
																</th>
																<th>
															<a href="comprobantes_listado.php?order=<?php echo urlencode("c_Moneda"); ?>">
															Moneda&nbsp;(*)
															<?php if (@$_SESSION["vit_comprobantes_x_c_Moneda_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_comprobantes_x_c_Moneda_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
															</a>
																</th>
																<th>
															<a href="comprobantes_listado.php?order=<?php echo urlencode("Cfdi_Total"); ?>">
															Total
															<?php if (@$_SESSION["vit_comprobantes_x_Cfdi_Total_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_comprobantes_x_Cfdi_Total_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
															</a>
																</th>
																<th>
															<a href="comprobantes_listado.php?order=<?php echo urlencode("Emi_RFC"); ?>">
															Emisor
															<?php if (@$_SESSION["vit_comprobantes_x_Emi_RFC_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_comprobantes_x_Emi_RFC_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
															</a>
																</th>
																<th>
															<a href="comprobantes_listado.php?order=<?php echo urlencode("Rec_RFC"); ?>">
															Receptor
															<?php if (@$_SESSION["vit_comprobantes_x_Rec_RFC_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_comprobantes_x_Rec_RFC_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
															</a>
																</th>
																<th>
															<a href="comprobantes_listado.php?order=<?php echo urlencode("Cfdi_UUID"); ?>">
															UUID&nbsp;(*)
															<?php if (@$_SESSION["vit_comprobantes_x_Cfdi_UUID_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_comprobantes_x_Cfdi_UUID_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
															</a>
																</th>
																<th>
															<a href="comprobantes_listado.php?order=<?php echo urlencode("Cfdi_Status"); ?>">
															Status
															<?php if (@$_SESSION["vit_comprobantes_x_Cfdi_Status_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_comprobantes_x_Cfdi_Status_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
															</a>
																</th>
																<th>
															<a href="#">
															Archivos
															</a>
																</th>
																<th>
															<a href="comprobantes_listado.php?order=<?php echo urlencode("Mun_ID"); ?>">
															Municipio
															<?php if (@$_SESSION["vit_comprobantes_x_Mun_ID_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_comprobantes_x_Mun_ID_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?>
															</a>
																</th>
														<!--<td>&nbsp;</td>
														<td>&nbsp;</td>
														<td>&nbsp;</td>-->
													<?php }else{ ?>
														<th>															
															Version&nbsp;(*)
																</th>
																<th>
															Serie&nbsp;(*)
																</th>
																<th>
															Folio
																</th>
																<th>
															Fecha
																</th>
																<th>
															Subtotal
																</th>
																<th>
															Descuento
																</th>
																<th>
															Moneda&nbsp;(*)
																</th>
																<th>
															Total
																</th>
																<th>
															Emisor
																</th>
																<th>
															Receptor
																</th>
																<th>
															UUID&nbsp;(*)
																</th>
																<th>
															Status
																</th>
																<th>
															Archivos
																</th>
																<th>
															Municipio
																</th>
														<!--<td>&nbsp;</td>
														<td>&nbsp;</td>
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
		$x_Cfdi_ID = $row["Cfdi_ID"];
		$x_Cfdi_Version = $row["Cfdi_Version"];
		$x_Cfdi_Serie = $row["Cfdi_Serie"];
		$x_Cfdi_Folio = $row["Cfdi_Folio"];
		$x_Cfdi_Fecha = $row["Cfdi_Fecha"];
		$x_Cfdi_Sello = $row["Cfdi_Sello"];
		$x_c_FormaPago = $row["c_FormaPago"];
		$x_Cfdi_Certificado = $row["Cfdi_Certificado"];
		$x_Cfdi_NoCertificado = $row["Cfdi_NoCertificado"];
		$x_Cfdi_CondicionesDePago = $row["Cfdi_CondicionesDePago"];
		$x_Cfdi_Subtotal = $row["Cfdi_Subtotal"];
		$x_Cfdi_Descuento = $row["Cfdi_Descuento"];
		$x_c_Moneda = $row["c_Moneda"];
		$x_Cfdi_TipoCambio = $row["Cfdi_TipoCambio"];
		$x_Cfdi_Total = $row["Cfdi_Total"];
		$x_c_TipoDeComprobante = $row["c_TipoDeComprobante"];
		$x_c_Exportacion = $row["c_Exportacion"];
		$x_c_MetodoPago = $row["c_MetodoPago"];
		$x_c_CodigoPostal = $row["c_CodigoPostal"];
		$x_Cfdi_Confirmacion = $row["Cfdi_Confirmacion"];
		$x_Emi_RFC = $row["Emi_RFC"];
		$x_Rec_RFC = $row["Rec_RFC"];
		$x_Cfdi_Error = $row["Cfdi_Error"];
		$x_Cfdi_UsoCFDI = $row["Cfdi_UsoCFDI"];
		$x_Cfdi_UUID = $row["Cfdi_UUID"];
		$x_Cfdi_Retcode = $row["Cfdi_Retcode"];
		$x_Cfdi_Timestamp = $row["Cfdi_Timestamp"];
		$x_Cfdi_Acuse = $row["Cfdi_Acuse"];
		$x_Cfdi_Status = $row["Cfdi_Status"];
		$x_Mun_ID = $row["Mun_ID"];
		$x_Cfdi_CreationDate = $row["Cfdi_CreationDate"];
		$x_Cfdi_Cotejado = $row["Cfdi_Cotejado"];
?>
<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- Cfdi_Version -->
		<td>
<?php echo $x_Cfdi_Version; ?>
</td>
		<!-- Cfdi_Serie -->
		<td>
<?php echo $x_Cfdi_Serie; ?>
</td>
		<!-- Cfdi_Folio -->
		<td>
<?php echo $x_Cfdi_Folio; ?>
</td>
		<!-- Cfdi_Fecha -->
		<td>
<?php echo FormatDateTime($x_Cfdi_Fecha,5); ?>
</td>
		<!-- Cfdi_Subtotal -->
		<td>
<div align="right"><?php echo (is_numeric($x_Cfdi_Subtotal)) ? FormatCurrency($x_Cfdi_Subtotal,2,-2,-2,-2) : $x_Cfdi_Subtotal; ?></div>
</td>
		<!-- Cfdi_Descuento -->
		<td>
<div align="right"><?php echo (is_numeric($x_Cfdi_Descuento)) ? FormatCurrency($x_Cfdi_Descuento,2,-2,-2,-2) : $x_Cfdi_Descuento; ?></div>
</td>
		<!-- c_Moneda -->
		<td>
<?php echo $x_c_Moneda; ?>
</td>
		<!-- Cfdi_Total -->
		<td>
<div align="right"><?php echo (is_numeric($x_Cfdi_Total)) ? FormatCurrency($x_Cfdi_Total,2,-2,-2,-2) : $x_Cfdi_Total; ?></div>
</td>
		<!-- Emi_RFC -->
		<td>
<?php
if ((!is_null($x_Emi_RFC)) && ($x_Emi_RFC <> "")) {
	$sSqlWrk = "SELECT DISTINCT `Emi_Nombre` FROM `vit_emisor`";
	$sTmp = $x_Emi_RFC;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `Emi_RFC` = '" . $sTmp . "'";
	$sSqlWrk .= " ORDER BY `Emi_RFC` Asc";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
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
</td>
		<!-- Rec_RFC -->
		<td>
<?php
if ((!is_null($x_Rec_RFC)) && ($x_Rec_RFC <> "")) {
	$sSqlWrk = "SELECT DISTINCT `Rec_Nombre`, `Rec_Apellido_Paterno`, `Rec_Apellido_Materno` FROM `vit_receptor`";
	$sTmp = $x_Rec_RFC;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `Rec_RFC` = '" . $sTmp . "'";
	$sSqlWrk .= " ORDER BY `Rec_RFC` Asc";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["Rec_Nombre"]." ".$rowwrk["Rec_Apellido_Paterno"]." ".$rowwrk["Rec_Apellido_Materno"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_Rec_RFC = $x_Rec_RFC; // Backup Original Value
$x_Rec_RFC = $sTmp;
?>
<?php echo $x_Rec_RFC; ?>
<?php $x_Rec_RFC = $ox_Rec_RFC; // Restore Original Value ?>
</td>
		<!-- Cfdi_UUID -->
		<td>
<?php echo $x_Cfdi_UUID; ?>
</td>
		<!-- Cfdi_Status -->
		<td>
<?php
switch ($x_Cfdi_Status) {
	case "A":
		$sTmp = '<span class="badge badge-soft-success">Activo</span>';
		break;
	case "C":
		$sTmp = '<span class="badge badge-soft-danger">Cancelado</span>';
		break;
	case "P":
		$sTmp = '<span class="badge badge-soft-info">Pendiente</span>';
		break;
	default:
		$sTmp = "";
}
$ox_Cfdi_Status = $x_Cfdi_Status; // Backup Original Value
$x_Cfdi_Status = $sTmp;
?>
<?php echo $x_Cfdi_Status; ?>
<?php $x_Cfdi_Status = $ox_Cfdi_Status; // Restore Original Value ?>
</td>
		<!-- archivos Cfdi_UUID -->
		<td>
		<?php 
		$archivo_o = $row["Cfdi_Serie"].'/'.$row["Cfdi_Serie"].'-'.$row["Cfdi_Folio"].'-'.$row["Cfdi_UUID"].'.xml';
		#echo 
		$url = 'https://admin.vitainsumos.mx/XML/'.$archivo_o;
		$file_headers = @get_headers($url);
		if($file_headers[0] != 'HTTP/1.1 404 Not Found') {
		?>
		<a href="<?php echo $url; ?>" target="_blank"><i class="las la-file-code" style="color:#2874a6; font-size:24px;"></i></a>
		<?php 
		}
		?>
		</td>
		<!-- Mun_ID -->
		<td>
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
</td>
<!--<td><a href="<?php if ($x_Cfdi_ID <> "") {echo "vit_comprobantesview.php?Cfdi_ID=" . urlencode($x_Cfdi_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">View</a></td>
<td><a href="<?php if ($x_Cfdi_ID <> "") {echo "vit_comprobantesedit.php?Cfdi_ID=" . urlencode($x_Cfdi_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a></td>
<td><a href="<?php if ($x_Cfdi_ID <> "") {echo "vit_comprobantesdelete.php?Cfdi_ID=" . urlencode($x_Cfdi_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Delete</a></td>-->
	</tr>
<?php
	}
}
?>

                                                </tbody>
                                            </table>
<?php } ?>											
                                        </div>
                                        <div class="d-flex justify-content-end mt-3">
                                            <div class="pagination-wrap hstack gap-2">												
												<form action="comprobantes_listado.php" name="ewpagerform" id="ewpagerform">
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
	<td><a class="page-item pagination-prev" href="comprobantes_listado.php?start=1">|<</a></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($PrevStart == $nStartRec) { ?>
	<td><a class="page-item pagination-prev disabled"><</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-prev" href="comprobantes_listado.php?start=<?php echo $PrevStart; ?>"><</a></td>
	<?php } ?>
<!--current page number-->
	<td><input class="form-control form-control-sm" type="text" name="pageno" value="<?php echo intval(($nStartRec-1)/$nDisplayRecs+1); ?>" size="4"></td>
<!--next page button-->
	<?php if ($NextStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">></a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="comprobantes_listado.php?start=<?php echo $NextStart; ?>">></a></td>
	<?php  } ?>
<!--last page button-->
	<?php if ($LastStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">>|</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="comprobantes_listado.php?start=<?php echo $LastStart; ?>">>|</a></td>
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
function EW_checkMyForm(EW_this) {
if (EW_this.x_Cfdi_Folio && !EW_checkinteger(EW_this.x_Cfdi_Folio.value)) {
	if (!EW_onError(EW_this, EW_this.x_Cfdi_Folio, "TEXT", "Incorrect integer - Folio"))
		return false; 
}
return true;
}

//-->
</script>
                                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample"
                                        aria-labelledby="offcanvasExampleLabel">
                                        <div class="offcanvas-header bg-light">
                                            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Filtros - Comprobantes</h5>
                                            <button type="button" class="btn-close text-reset"
                                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <!--end offcanvas-header-->
										<form class="d-flex flex-column justify-content-end h-100" name="vit_comprobantessearch" id="vit_comprobantessearch" action="vit_comprobantessrch.php" method="post" onSubmit="return EW_checkMyForm(this);">
                                        <!--<form action="" class="d-flex flex-column justify-content-end h-100">-->
                                            <div class="offcanvas-body">
												<div class="mb-4">
                                                    <label for="Serie" class="form-label text-muted text-uppercase fw-semibold mb-3">Serie</label>
													<input type="hidden" name="z_Cfdi_Serie[]" value="LIKE,'%,%'">
                                                    <input class="form-control" type="text" name="s_Cfdi_Serie" id="s_Cfdi_Serie" size="30" maxlength="120" value="<?php echo htmlspecialchars(@$_GET["s_Cfdi_Serie"]) ?>">
                                                </div>
												<div class="mb-4">
                                                    <label for="Folio" class="form-label text-muted text-uppercase fw-semibold mb-3">Folio</label>
													<input type="hidden" name="z_Cfdi_Folio[]" value="=,,">
                                                    <input class="form-control" type="text" name="s_Cfdi_Folio" id="s_Cfdi_Folio" size="30" value="<?php echo htmlspecialchars(@$_GET["s_Cfdi_Folio"]); ?>">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="datepicker-range"
                                                        class="form-label text-muted text-uppercase fw-semibold mb-3">Fecha</label>
														<input type="hidden" name="z_Cfdi_Fecha[]" value="LIKE,'%,%'">
														<input type="date" name="s_Cfdi_Fecha" id="s_Cfdi_Fecha" 
                                                                        class="form-control" data-provider="flatpickr" data-date-format="YYYY MM DD"
                                                                        placeholder="Select Date" value="<?php echo FormatDateTime(@$_GET["s_Cfdi_Fecha"],5); ?>" />
                                                </div>
                                                <div class="mb-4">
                                                    <label for="country-select"
                                                        class="form-label text-muted text-uppercase fw-semibold mb-3">Emisor</label>
														<input type="hidden" name="z_Emi_RFC[]" value="LIKE,'%,%'">
														<?php
														$x_Emi_RFCList = "<select class=\"form-control\" name=\"s_Emi_RFC\">";
														$x_Emi_RFCList .= "<option value=''>TODOS</option>";
														$sSqlWrk = "SELECT vit_emisor.Emi_RFC,
														vit_emisor.Emi_Nombre
														FROM vit_comprobantes INNER JOIN vit_emisor ON vit_comprobantes.Emi_RFC = vit_emisor.Emi_RFC
														WHERE vit_emisor.Emi_RFC <> ''
														GROUP BY vit_emisor.Emi_RFC
														ORDER BY vit_emisor.Emi_RFC Asc";
														#echo "<br />".$sSqlWrk;
														$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
														if ($rswrk) {
															$rowcntwrk = 0;
															while ($datawrk = phpmkr_fetch_array($rswrk)) {
																$x_Emi_RFCList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
																if ($datawrk["Emi_RFC"] == @$_GET["s_Emi_RFC"]) {
																	$x_Emi_RFCList .= "' selected";
																}
																$x_Emi_RFCList .= ">" . $datawrk["Emi_RFC"] . " " . $datawrk["Emi_Nombre"] . "</option>";
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
                                                        class="form-label text-muted text-uppercase fw-semibold mb-3">Receptor</label>
														<input type="hidden" name="z_Rec_RFC[]" value="LIKE,'%,%'">
														<?php
														$x_Rec_RFCList = "<select class=\"form-control\" name=\"s_Rec_RFC\">";
														$x_Rec_RFCList .= "<option value=''>TODOS</option>";
														$sSqlWrk = "SELECT ";
														$sSqlWrk .= "vit_comprobantes.Rec_RFC, ";
														$sSqlWrk .= "vit_receptor.Rec_Nombre, ";
														$sSqlWrk .= "vit_receptor.Rec_Apellido_Paterno, ";
														$sSqlWrk .= "vit_receptor.Rec_Apellido_Materno ";
														$sSqlWrk .= "FROM vit_comprobantes ";
														$sSqlWrk .= "INNER JOIN vit_receptor ON vit_comprobantes.Rec_RFC = vit_receptor.Rec_RFC "; 														
														$sSqlWrk .= "WHERE Rec_Nombre <> '' ";
														if(@$_GET["s_Emi_RFC"]!=""){
														$sSqlWrk .= "AND vit_comprobantes.Emi_RFC = '".@$_GET["s_Emi_RFC"]."' ";															
														}
														$sSqlWrk .= "GROUP BY Rec_RFC ";
														$sSqlWrk .= "ORDER BY Rec_Nombre, Rec_Apellido_Paterno, Rec_Apellido_Materno Asc";
														#echo "<br />".$sSqlWrk;
														$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
														if ($rswrk) {
															$rowcntwrk = 0;
															while ($datawrk = phpmkr_fetch_array($rswrk)) {
																$x_Rec_RFCList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
																if ($datawrk["Rec_RFC"] == @$_GET["s_Rec_RFC"]) {
																	$x_Rec_RFCList .= "' selected";
																}
																$x_Rec_RFCList .= ">" . $datawrk["Rec_Nombre"] . " " . $datawrk["Rec_Apellido_Paterno"] . " " . $datawrk["Rec_Apellido_Materno"] . "</option>";
																$rowcntwrk++;
															}
														}
														@phpmkr_free_result($rswrk);
														$x_Rec_RFCList .= "</select>";
														echo $x_Rec_RFCList;
														?>
                                                </div>
                                                <div class="mb-4">
                                                    <label for="UUID" class="form-label text-muted text-uppercase fw-semibold mb-3">UUID</label>
													<input type="hidden" name="z_Cfdi_UUID[]" value="=,','">
                                                    <input class="form-control" type="text" name="s_Cfdi_UUID" id="s_Cfdi_UUID" size="30" value="<?php echo (@$_GET["s_Cfdi_UUID"]); ?>">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="country-select"
                                                        class="form-label text-muted text-uppercase fw-semibold mb-3">Municipio</label>
														<input type="hidden" name="z_Mun_ID[]" value="=,,">
														<?php
														$x_Mun_IDList = "<select class=\"form-control\" name=\"s_Mun_ID\">";
														$x_Mun_IDList .= "<option value=''>TODOS</option>";
														$sSqlWrk = "SELECT DISTINCT `Mun_ID`, `Mun_Descrip` FROM `vit_municipios`";
														$sSqlWrk .= " ORDER BY `Mun_Descrip` Asc";
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
												<?php if(@$_SESSION["vit_comprobantes_OrderBy"]!=""){ ?>												
												<a class="btn btn-light w-100" href="comprobantes_listado.php?cmd=resetsort">Quitar Orden</a>
												<?php } ?>											
												<?php if(@$sWhere!="" && @$_SESSION["vit_comprobantes_OrderBy"]==""){ ?>
												<a class="btn btn-light w-100" href="comprobantes_listado.php?cmd=reset">Quitar Filtros</a>
												<?php } ?>												
                                                <button type="submit" name="Action" class="btn btn-soft-success waves-effect waves-light w-100">Filtrar</button>
												<input type="hidden" name="a_search" value="S">
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
		$_SESSION["vit_comprobantes_RecPerPage"] = $nDisplayRecs; // Save to Session

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_comprobantes_REC"] = $nStartRec;
	}else{
		if (@$_SESSION["vit_comprobantes_RecPerPage"] <> "") {
			$nDisplayRecs = $_SESSION["vit_comprobantes_RecPerPage"]; // Restore from Session
		}else{
			$nDisplayRecs = 10; // Load Default
		}
	}
}

//-------------------------------------------------------------------------------
// Function SetUpAdvancedSearch
// - Set up Advanced Search parameter based on querystring parameters from Advanced Search Page
// - Variables setup: sSrchAdvanced

function SetUpAdvancedSearch()
{
	global $sSrchAdvanced;

	// Field Cfdi_ID
	$x_Cfdi_ID = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Cfdi_ID"]) : @$_GET["x_Cfdi_ID"];
	$arrFldOpr = "";
	$z_Cfdi_ID = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_ID"]) : @$_GET["z_Cfdi_ID"];
	$arrFldOpr = explode(",",$z_Cfdi_ID);
	if ($x_Cfdi_ID <> "") {
		$sSrchAdvanced .= "`Cfdi_ID` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Cfdi_ID) : $x_Cfdi_ID; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_Version
	$x_Cfdi_Version = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Cfdi_Version"]) : @$_GET["x_Cfdi_Version"];
	$arrFldOpr = "";
	$z_Cfdi_Version = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_Version"]) : @$_GET["z_Cfdi_Version"];
	$arrFldOpr = explode(",",$z_Cfdi_Version);
	if ($x_Cfdi_Version <> "") {
		$sSrchAdvanced .= "`Cfdi_Version` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Cfdi_Version) : $x_Cfdi_Version; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_Serie
	$s_Cfdi_Serie = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["s_Cfdi_Serie"]) : @$_GET["s_Cfdi_Serie"];
	$arrFldOpr = "";
	$z_Cfdi_Serie = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_Serie"]) : @$_GET["z_Cfdi_Serie"];
	$arrFldOpr = explode(",",$z_Cfdi_Serie);
	if ($s_Cfdi_Serie <> "") {
		$sSrchAdvanced .= "`Cfdi_Serie` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($s_Cfdi_Serie) : $s_Cfdi_Serie; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_Folio
	$s_Cfdi_Folio = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["s_Cfdi_Folio"]) : @$_GET["s_Cfdi_Folio"];
	$arrFldOpr = "";
	$z_Cfdi_Folio = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_Folio"]) : @$_GET["z_Cfdi_Folio"];
	$arrFldOpr = explode(",",$z_Cfdi_Folio);
	if ($s_Cfdi_Folio <> "") {
		$sSrchAdvanced .= "`Cfdi_Folio` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($s_Cfdi_Folio) : $s_Cfdi_Folio; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_Fecha
	$s_Cfdi_Fecha = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["s_Cfdi_Fecha"]) : @$_GET["s_Cfdi_Fecha"];
	$arrFldOpr = "";
	$z_Cfdi_Fecha = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_Fecha"]) : @$_GET["z_Cfdi_Fecha"];
	$arrFldOpr = explode(",",$z_Cfdi_Fecha);
	if ($s_Cfdi_Fecha <> "") {
		$sSrchAdvanced .= "`Cfdi_Fecha` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($s_Cfdi_Fecha) : $s_Cfdi_Fecha; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_Sello
	$x_Cfdi_Sello = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Cfdi_Sello"]) : @$_GET["x_Cfdi_Sello"];
	$arrFldOpr = "";
	$z_Cfdi_Sello = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_Sello"]) : @$_GET["z_Cfdi_Sello"];
	$arrFldOpr = explode(",",$z_Cfdi_Sello);
	if ($x_Cfdi_Sello <> "") {
		$sSrchAdvanced .= "`Cfdi_Sello` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Cfdi_Sello) : $x_Cfdi_Sello; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field c_FormaPago
	$x_c_FormaPago = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_c_FormaPago"]) : @$_GET["x_c_FormaPago"];
	$arrFldOpr = "";
	$z_c_FormaPago = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_c_FormaPago"]) : @$_GET["z_c_FormaPago"];
	$arrFldOpr = explode(",",$z_c_FormaPago);
	if ($x_c_FormaPago <> "") {
		$sSrchAdvanced .= "`c_FormaPago` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_c_FormaPago) : $x_c_FormaPago; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_Certificado
	$x_Cfdi_Certificado = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Cfdi_Certificado"]) : @$_GET["x_Cfdi_Certificado"];
	$arrFldOpr = "";
	$z_Cfdi_Certificado = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_Certificado"]) : @$_GET["z_Cfdi_Certificado"];
	$arrFldOpr = explode(",",$z_Cfdi_Certificado);
	if ($x_Cfdi_Certificado <> "") {
		$sSrchAdvanced .= "`Cfdi_Certificado` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Cfdi_Certificado) : $x_Cfdi_Certificado; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_NoCertificado
	$x_Cfdi_NoCertificado = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Cfdi_NoCertificado"]) : @$_GET["x_Cfdi_NoCertificado"];
	$arrFldOpr = "";
	$z_Cfdi_NoCertificado = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_NoCertificado"]) : @$_GET["z_Cfdi_NoCertificado"];
	$arrFldOpr = explode(",",$z_Cfdi_NoCertificado);
	if ($x_Cfdi_NoCertificado <> "") {
		$sSrchAdvanced .= "`Cfdi_NoCertificado` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Cfdi_NoCertificado) : $x_Cfdi_NoCertificado; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_CondicionesDePago
	$x_Cfdi_CondicionesDePago = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Cfdi_CondicionesDePago"]) : @$_GET["x_Cfdi_CondicionesDePago"];
	$arrFldOpr = "";
	$z_Cfdi_CondicionesDePago = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_CondicionesDePago"]) : @$_GET["z_Cfdi_CondicionesDePago"];
	$arrFldOpr = explode(",",$z_Cfdi_CondicionesDePago);
	if ($x_Cfdi_CondicionesDePago <> "") {
		$sSrchAdvanced .= "`Cfdi_CondicionesDePago` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Cfdi_CondicionesDePago) : $x_Cfdi_CondicionesDePago; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_Subtotal
	$x_Cfdi_Subtotal = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Cfdi_Subtotal"]) : @$_GET["x_Cfdi_Subtotal"];
	$arrFldOpr = "";
	$z_Cfdi_Subtotal = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_Subtotal"]) : @$_GET["z_Cfdi_Subtotal"];
	$arrFldOpr = explode(",",$z_Cfdi_Subtotal);
	if ($x_Cfdi_Subtotal <> "") {
		$sSrchAdvanced .= "`Cfdi_Subtotal` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Cfdi_Subtotal) : $x_Cfdi_Subtotal; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_Descuento
	$x_Cfdi_Descuento = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Cfdi_Descuento"]) : @$_GET["x_Cfdi_Descuento"];
	$arrFldOpr = "";
	$z_Cfdi_Descuento = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_Descuento"]) : @$_GET["z_Cfdi_Descuento"];
	$arrFldOpr = explode(",",$z_Cfdi_Descuento);
	if ($x_Cfdi_Descuento <> "") {
		$sSrchAdvanced .= "`Cfdi_Descuento` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Cfdi_Descuento) : $x_Cfdi_Descuento; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field c_Moneda
	$x_c_Moneda = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_c_Moneda"]) : @$_GET["x_c_Moneda"];
	$arrFldOpr = "";
	$z_c_Moneda = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_c_Moneda"]) : @$_GET["z_c_Moneda"];
	$arrFldOpr = explode(",",$z_c_Moneda);
	if ($x_c_Moneda <> "") {
		$sSrchAdvanced .= "`c_Moneda` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_c_Moneda) : $x_c_Moneda; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_TipoCambio
	$x_Cfdi_TipoCambio = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Cfdi_TipoCambio"]) : @$_GET["x_Cfdi_TipoCambio"];
	$arrFldOpr = "";
	$z_Cfdi_TipoCambio = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_TipoCambio"]) : @$_GET["z_Cfdi_TipoCambio"];
	$arrFldOpr = explode(",",$z_Cfdi_TipoCambio);
	if ($x_Cfdi_TipoCambio <> "") {
		$sSrchAdvanced .= "`Cfdi_TipoCambio` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Cfdi_TipoCambio) : $x_Cfdi_TipoCambio; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_Total
	$x_Cfdi_Total = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Cfdi_Total"]) : @$_GET["x_Cfdi_Total"];
	$arrFldOpr = "";
	$z_Cfdi_Total = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_Total"]) : @$_GET["z_Cfdi_Total"];
	$arrFldOpr = explode(",",$z_Cfdi_Total);
	if ($x_Cfdi_Total <> "") {
		$sSrchAdvanced .= "`Cfdi_Total` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Cfdi_Total) : $x_Cfdi_Total; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field c_TipoDeComprobante
	$x_c_TipoDeComprobante = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_c_TipoDeComprobante"]) : @$_GET["x_c_TipoDeComprobante"];
	$arrFldOpr = "";
	$z_c_TipoDeComprobante = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_c_TipoDeComprobante"]) : @$_GET["z_c_TipoDeComprobante"];
	$arrFldOpr = explode(",",$z_c_TipoDeComprobante);
	if ($x_c_TipoDeComprobante <> "") {
		$sSrchAdvanced .= "`c_TipoDeComprobante` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_c_TipoDeComprobante) : $x_c_TipoDeComprobante; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field c_Exportacion
	$x_c_Exportacion = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_c_Exportacion"]) : @$_GET["x_c_Exportacion"];
	$arrFldOpr = "";
	$z_c_Exportacion = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_c_Exportacion"]) : @$_GET["z_c_Exportacion"];
	$arrFldOpr = explode(",",$z_c_Exportacion);
	if ($x_c_Exportacion <> "") {
		$sSrchAdvanced .= "`c_Exportacion` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_c_Exportacion) : $x_c_Exportacion; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field c_MetodoPago
	$x_c_MetodoPago = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_c_MetodoPago"]) : @$_GET["x_c_MetodoPago"];
	$arrFldOpr = "";
	$z_c_MetodoPago = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_c_MetodoPago"]) : @$_GET["z_c_MetodoPago"];
	$arrFldOpr = explode(",",$z_c_MetodoPago);
	if ($x_c_MetodoPago <> "") {
		$sSrchAdvanced .= "`c_MetodoPago` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_c_MetodoPago) : $x_c_MetodoPago; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field c_CodigoPostal
	$x_c_CodigoPostal = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_c_CodigoPostal"]) : @$_GET["x_c_CodigoPostal"];
	$arrFldOpr = "";
	$z_c_CodigoPostal = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_c_CodigoPostal"]) : @$_GET["z_c_CodigoPostal"];
	$arrFldOpr = explode(",",$z_c_CodigoPostal);
	if ($x_c_CodigoPostal <> "") {
		$sSrchAdvanced .= "`c_CodigoPostal` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_c_CodigoPostal) : $x_c_CodigoPostal; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_Confirmacion
	$x_Cfdi_Confirmacion = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Cfdi_Confirmacion"]) : @$_GET["x_Cfdi_Confirmacion"];
	$arrFldOpr = "";
	$z_Cfdi_Confirmacion = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_Confirmacion"]) : @$_GET["z_Cfdi_Confirmacion"];
	$arrFldOpr = explode(",",$z_Cfdi_Confirmacion);
	if ($x_Cfdi_Confirmacion <> "") {
		$sSrchAdvanced .= "`Cfdi_Confirmacion` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Cfdi_Confirmacion) : $x_Cfdi_Confirmacion; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Emi_RFC
	$s_Emi_RFC = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["s_Emi_RFC"]) : @$_GET["s_Emi_RFC"];
	$arrFldOpr = "";
	$z_Emi_RFC = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Emi_RFC"]) : @$_GET["z_Emi_RFC"];
	$arrFldOpr = explode(",",$z_Emi_RFC);
	if ($s_Emi_RFC <> "") {
		$sSrchAdvanced .= "`Emi_RFC` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($s_Emi_RFC) : $s_Emi_RFC; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_RFC
	$s_Rec_RFC = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["s_Rec_RFC"]) : @$_GET["s_Rec_RFC"];
	$arrFldOpr = "";
	$z_Rec_RFC = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_RFC"]) : @$_GET["z_Rec_RFC"];
	$arrFldOpr = explode(",",$z_Rec_RFC);
	if ($s_Rec_RFC <> "") {
		$sSrchAdvanced .= "`Rec_RFC` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($s_Rec_RFC) : $s_Rec_RFC; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_Error
	$x_Cfdi_Error = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Cfdi_Error"]) : @$_GET["x_Cfdi_Error"];
	$arrFldOpr = "";
	$z_Cfdi_Error = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_Error"]) : @$_GET["z_Cfdi_Error"];
	$arrFldOpr = explode(",",$z_Cfdi_Error);
	if ($x_Cfdi_Error <> "") {
		$sSrchAdvanced .= "`Cfdi_Error` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Cfdi_Error) : $x_Cfdi_Error; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_UsoCFDI
	$x_Cfdi_UsoCFDI = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Cfdi_UsoCFDI"]) : @$_GET["x_Cfdi_UsoCFDI"];
	$arrFldOpr = "";
	$z_Cfdi_UsoCFDI = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_UsoCFDI"]) : @$_GET["z_Cfdi_UsoCFDI"];
	$arrFldOpr = explode(",",$z_Cfdi_UsoCFDI);
	if ($x_Cfdi_UsoCFDI <> "") {
		$sSrchAdvanced .= "`Cfdi_UsoCFDI` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Cfdi_UsoCFDI) : $x_Cfdi_UsoCFDI; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_UUID
	$s_Cfdi_UUID = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? trim(@$_GET["s_Cfdi_UUID"]) : trim(@$_GET["s_Cfdi_UUID"]);
	$arrFldOpr = "";
	$z_Cfdi_UUID = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_UUID"]) : @$_GET["z_Cfdi_UUID"];
	$arrFldOpr = explode(",",$z_Cfdi_UUID);
	if ($s_Cfdi_UUID <> "") {
		$sSrchAdvanced .= "`Cfdi_UUID` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($s_Cfdi_UUID) : $s_Cfdi_UUID; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_Retcode
	$x_Cfdi_Retcode = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Cfdi_Retcode"]) : @$_GET["x_Cfdi_Retcode"];
	$arrFldOpr = "";
	$z_Cfdi_Retcode = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_Retcode"]) : @$_GET["z_Cfdi_Retcode"];
	$arrFldOpr = explode(",",$z_Cfdi_Retcode);
	if ($x_Cfdi_Retcode <> "") {
		$sSrchAdvanced .= "`Cfdi_Retcode` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Cfdi_Retcode) : $x_Cfdi_Retcode; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_Timestamp
	$x_Cfdi_Timestamp = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Cfdi_Timestamp"]) : @$_GET["x_Cfdi_Timestamp"];
	$arrFldOpr = "";
	$z_Cfdi_Timestamp = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_Timestamp"]) : @$_GET["z_Cfdi_Timestamp"];
	$arrFldOpr = explode(",",$z_Cfdi_Timestamp);
	if ($x_Cfdi_Timestamp <> "") {
		$sSrchAdvanced .= "`Cfdi_Timestamp` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Cfdi_Timestamp) : $x_Cfdi_Timestamp; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_Acuse
	$x_Cfdi_Acuse = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Cfdi_Acuse"]) : @$_GET["x_Cfdi_Acuse"];
	$arrFldOpr = "";
	$z_Cfdi_Acuse = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_Acuse"]) : @$_GET["z_Cfdi_Acuse"];
	$arrFldOpr = explode(",",$z_Cfdi_Acuse);
	if ($x_Cfdi_Acuse <> "") {
		$sSrchAdvanced .= "`Cfdi_Acuse` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Cfdi_Acuse) : $x_Cfdi_Acuse; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_Status
	$x_Cfdi_Status = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Cfdi_Status"]) : @$_GET["x_Cfdi_Status"];
	$arrFldOpr = "";
	$z_Cfdi_Status = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_Status"]) : @$_GET["z_Cfdi_Status"];
	$arrFldOpr = explode(",",$z_Cfdi_Status);
	if ($x_Cfdi_Status <> "") {
		$sSrchAdvanced .= "`Cfdi_Status` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Cfdi_Status) : $x_Cfdi_Status; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Mun_ID
	$s_Mun_ID = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["s_Mun_ID"]) : @$_GET["s_Mun_ID"];
	$arrFldOpr = "";
	$z_Mun_ID = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Mun_ID"]) : @$_GET["z_Mun_ID"];
	$arrFldOpr = explode(",",$z_Mun_ID);
	if ($s_Mun_ID <> "") {
		$sSrchAdvanced .= "`Mun_ID` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($s_Mun_ID) : $s_Mun_ID; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_CreationDate
	$x_Cfdi_CreationDate = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Cfdi_CreationDate"]) : @$_GET["x_Cfdi_CreationDate"];
	$arrFldOpr = "";
	$z_Cfdi_CreationDate = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_CreationDate"]) : @$_GET["z_Cfdi_CreationDate"];
	$arrFldOpr = explode(",",$z_Cfdi_CreationDate);
	if ($x_Cfdi_CreationDate <> "") {
		$sSrchAdvanced .= "`Cfdi_CreationDate` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Cfdi_CreationDate) : $x_Cfdi_CreationDate; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Cfdi_Cotejado
	$x_Cfdi_Cotejado = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Cfdi_Cotejado"]) : @$_GET["x_Cfdi_Cotejado"];
	$arrFldOpr = "";
	$z_Cfdi_Cotejado = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Cfdi_Cotejado"]) : @$_GET["z_Cfdi_Cotejado"];
	$arrFldOpr = explode(",",$z_Cfdi_Cotejado);
	if ($x_Cfdi_Cotejado <> "") {
		$sSrchAdvanced .= "`Cfdi_Cotejado` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Cfdi_Cotejado) : $x_Cfdi_Cotejado; // Add input parameter
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
	$sKeyword = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? trim($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`Cfdi_UUID` LIKE '%" . $sKeyword . "%' OR ";
	/*$BasicSearchSQL.= "`Cfdi_Serie` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Cfdi_Sello` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`c_FormaPago` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Cfdi_Certificado` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Cfdi_NoCertificado` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Cfdi_CondicionesDePago` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`c_Moneda` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`c_TipoDeComprobante` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`c_Exportacion` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`c_MetodoPago` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`c_CodigoPostal` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Cfdi_Confirmacion` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Emi_RFC` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_RFC` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Cfdi_Error` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Cfdi_UsoCFDI` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Cfdi_UUID` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Cfdi_Acuse` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Cfdi_Status` LIKE '%" . $sKeyword . "%' OR ";*/
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
	$sSearch = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? trim(@$_GET["psearch"]) : trim(@$_GET["psearch"]);
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

		// Field Cfdi_Version
		if ($sOrder == "Cfdi_Version") {
			$sSortField = "`Cfdi_Version`";
			$sLastSort = @$_SESSION["vit_comprobantes_x_Cfdi_Version_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_comprobantes_x_Cfdi_Version_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_comprobantes_x_Cfdi_Version_Sort"] <> "") { @$_SESSION["vit_comprobantes_x_Cfdi_Version_Sort"] = ""; }
		}

		// Field Cfdi_Serie
		if ($sOrder == "Cfdi_Serie") {
			$sSortField = "`Cfdi_Serie`";
			$sLastSort = @$_SESSION["vit_comprobantes_x_Cfdi_Serie_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_comprobantes_x_Cfdi_Serie_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_comprobantes_x_Cfdi_Serie_Sort"] <> "") { @$_SESSION["vit_comprobantes_x_Cfdi_Serie_Sort"] = ""; }
		}

		// Field Cfdi_Folio
		if ($sOrder == "Cfdi_Folio") {
			$sSortField = "`Cfdi_Folio`";
			$sLastSort = @$_SESSION["vit_comprobantes_x_Cfdi_Folio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_comprobantes_x_Cfdi_Folio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_comprobantes_x_Cfdi_Folio_Sort"] <> "") { @$_SESSION["vit_comprobantes_x_Cfdi_Folio_Sort"] = ""; }
		}

		// Field Cfdi_Fecha
		if ($sOrder == "Cfdi_Fecha") {
			$sSortField = "`Cfdi_Fecha`";
			$sLastSort = @$_SESSION["vit_comprobantes_x_Cfdi_Fecha_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_comprobantes_x_Cfdi_Fecha_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_comprobantes_x_Cfdi_Fecha_Sort"] <> "") { @$_SESSION["vit_comprobantes_x_Cfdi_Fecha_Sort"] = ""; }
		}

		// Field Cfdi_Subtotal
		if ($sOrder == "Cfdi_Subtotal") {
			$sSortField = "`Cfdi_Subtotal`";
			$sLastSort = @$_SESSION["vit_comprobantes_x_Cfdi_Subtotal_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_comprobantes_x_Cfdi_Subtotal_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_comprobantes_x_Cfdi_Subtotal_Sort"] <> "") { @$_SESSION["vit_comprobantes_x_Cfdi_Subtotal_Sort"] = ""; }
		}

		// Field Cfdi_Descuento
		if ($sOrder == "Cfdi_Descuento") {
			$sSortField = "`Cfdi_Descuento`";
			$sLastSort = @$_SESSION["vit_comprobantes_x_Cfdi_Descuento_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_comprobantes_x_Cfdi_Descuento_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_comprobantes_x_Cfdi_Descuento_Sort"] <> "") { @$_SESSION["vit_comprobantes_x_Cfdi_Descuento_Sort"] = ""; }
		}

		// Field c_Moneda
		if ($sOrder == "c_Moneda") {
			$sSortField = "`c_Moneda`";
			$sLastSort = @$_SESSION["vit_comprobantes_x_c_Moneda_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_comprobantes_x_c_Moneda_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_comprobantes_x_c_Moneda_Sort"] <> "") { @$_SESSION["vit_comprobantes_x_c_Moneda_Sort"] = ""; }
		}

		// Field Cfdi_Total
		if ($sOrder == "Cfdi_Total") {
			$sSortField = "`Cfdi_Total`";
			$sLastSort = @$_SESSION["vit_comprobantes_x_Cfdi_Total_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_comprobantes_x_Cfdi_Total_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_comprobantes_x_Cfdi_Total_Sort"] <> "") { @$_SESSION["vit_comprobantes_x_Cfdi_Total_Sort"] = ""; }
		}

		// Field Emi_RFC
		if ($sOrder == "Emi_RFC") {
			$sSortField = "`Emi_RFC`";
			$sLastSort = @$_SESSION["vit_comprobantes_x_Emi_RFC_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_comprobantes_x_Emi_RFC_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_comprobantes_x_Emi_RFC_Sort"] <> "") { @$_SESSION["vit_comprobantes_x_Emi_RFC_Sort"] = ""; }
		}

		// Field Rec_RFC
		if ($sOrder == "Rec_RFC") {
			$sSortField = "`Rec_RFC`";
			$sLastSort = @$_SESSION["vit_comprobantes_x_Rec_RFC_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_comprobantes_x_Rec_RFC_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_comprobantes_x_Rec_RFC_Sort"] <> "") { @$_SESSION["vit_comprobantes_x_Rec_RFC_Sort"] = ""; }
		}

		// Field Cfdi_UUID
		if ($sOrder == "Cfdi_UUID") {
			$sSortField = "`Cfdi_UUID`";
			$sLastSort = @$_SESSION["vit_comprobantes_x_Cfdi_UUID_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_comprobantes_x_Cfdi_UUID_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_comprobantes_x_Cfdi_UUID_Sort"] <> "") { @$_SESSION["vit_comprobantes_x_Cfdi_UUID_Sort"] = ""; }
		}

		// Field Cfdi_Status
		if ($sOrder == "Cfdi_Status") {
			$sSortField = "`Cfdi_Status`";
			$sLastSort = @$_SESSION["vit_comprobantes_x_Cfdi_Status_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_comprobantes_x_Cfdi_Status_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_comprobantes_x_Cfdi_Status_Sort"] <> "") { @$_SESSION["vit_comprobantes_x_Cfdi_Status_Sort"] = ""; }
		}

		// Field Mun_ID
		if ($sOrder == "Mun_ID") {
			$sSortField = "`Mun_ID`";
			$sLastSort = @$_SESSION["vit_comprobantes_x_Mun_ID_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_comprobantes_x_Mun_ID_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_comprobantes_x_Mun_ID_Sort"] <> "") { @$_SESSION["vit_comprobantes_x_Mun_ID_Sort"] = ""; }
		}
		$_SESSION["vit_comprobantes_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["vit_comprobantes_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["vit_comprobantes_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["vit_comprobantes_OrderBy"] = $sOrderBy;
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
		$_SESSION["vit_comprobantes_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["vit_comprobantes_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["vit_comprobantes_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["vit_comprobantes_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["vit_comprobantes_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["vit_comprobantes_REC"] = $nStartRec;
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
			$_SESSION["vit_comprobantes_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["vit_comprobantes_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["vit_comprobantes_OrderBy"] = $sOrderBy;
			if (@$_SESSION["vit_comprobantes_x_Cfdi_Version_Sort"] <> "") { $_SESSION["vit_comprobantes_x_Cfdi_Version_Sort"] = ""; }
			if (@$_SESSION["vit_comprobantes_x_Cfdi_Serie_Sort"] <> "") { $_SESSION["vit_comprobantes_x_Cfdi_Serie_Sort"] = ""; }
			if (@$_SESSION["vit_comprobantes_x_Cfdi_Folio_Sort"] <> "") { $_SESSION["vit_comprobantes_x_Cfdi_Folio_Sort"] = ""; }
			if (@$_SESSION["vit_comprobantes_x_Cfdi_Fecha_Sort"] <> "") { $_SESSION["vit_comprobantes_x_Cfdi_Fecha_Sort"] = ""; }
			if (@$_SESSION["vit_comprobantes_x_Cfdi_Subtotal_Sort"] <> "") { $_SESSION["vit_comprobantes_x_Cfdi_Subtotal_Sort"] = ""; }
			if (@$_SESSION["vit_comprobantes_x_Cfdi_Descuento_Sort"] <> "") { $_SESSION["vit_comprobantes_x_Cfdi_Descuento_Sort"] = ""; }
			if (@$_SESSION["vit_comprobantes_x_c_Moneda_Sort"] <> "") { $_SESSION["vit_comprobantes_x_c_Moneda_Sort"] = ""; }
			if (@$_SESSION["vit_comprobantes_x_Cfdi_Total_Sort"] <> "") { $_SESSION["vit_comprobantes_x_Cfdi_Total_Sort"] = ""; }
			if (@$_SESSION["vit_comprobantes_x_Emi_RFC_Sort"] <> "") { $_SESSION["vit_comprobantes_x_Emi_RFC_Sort"] = ""; }
			if (@$_SESSION["vit_comprobantes_x_Rec_RFC_Sort"] <> "") { $_SESSION["vit_comprobantes_x_Rec_RFC_Sort"] = ""; }
			if (@$_SESSION["vit_comprobantes_x_Cfdi_UUID_Sort"] <> "") { $_SESSION["vit_comprobantes_x_Cfdi_UUID_Sort"] = ""; }
			if (@$_SESSION["vit_comprobantes_x_Cfdi_Status_Sort"] <> "") { $_SESSION["vit_comprobantes_x_Cfdi_Status_Sort"] = ""; }
			if (@$_SESSION["vit_comprobantes_x_Mun_ID_Sort"] <> "") { $_SESSION["vit_comprobantes_x_Mun_ID_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_comprobantes_REC"] = $nStartRec;
	}
}
?>