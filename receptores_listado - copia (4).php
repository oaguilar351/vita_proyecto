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
$x_Rec_RFC = Null; 
$ox_Rec_RFC = Null;
$x_Rec_Nombre = Null; 
$ox_Rec_Nombre = Null;
$x_Rec_Apellido_Paterno = Null; 
$ox_Rec_Apellido_Paterno = Null;
$x_Rec_Apellido_Materno = Null; 
$ox_Rec_Apellido_Materno = Null;
$x_Rec_DomicilioFiscaleceptor = Null; 
$ox_Rec_DomicilioFiscaleceptor = Null;
$x_Rec_ResidenciaFiscal = Null; 
$ox_Rec_ResidenciaFiscal = Null;
$x_Rec_NumRegIdTrib = Null; 
$ox_Rec_NumRegIdTrib = Null;
$x_Rec_RegimenFiscalReceptor = Null; 
$ox_Rec_RegimenFiscalReceptor = Null;
$x_Rec_Curp = Null; 
$ox_Rec_Curp = Null;
$x_Rec_NumSeguridadSocial = Null; 
$ox_Rec_NumSeguridadSocial = Null;
$x_Rec_FechaInicioRelLaboral = Null; 
$ox_Rec_FechaInicioRelLaboral = Null;
$x_Rec_Antiguedad = Null; 
$ox_Rec_Antiguedad = Null;
$x_Rec_TipoContrato = Null; 
$ox_Rec_TipoContrato = Null;
$x_Rec_Sindicalizado = Null; 
$ox_Rec_Sindicalizado = Null;
$x_Rec_TipoJornada = Null; 
$ox_Rec_TipoJornada = Null;
$x_Rec_TipoRegimen = Null; 
$ox_Rec_TipoRegimen = Null;
$x_Rec_NumEmpleado = Null; 
$ox_Rec_NumEmpleado = Null;
$x_Rec_Departamento = Null; 
$ox_Rec_Departamento = Null;
$x_Rec_Puesto = Null; 
$ox_Rec_Puesto = Null;
$x_Rec_RiesgoPuesto = Null; 
$ox_Rec_RiesgoPuesto = Null;
$x_Rec_PeriodicidadPago = Null; 
$ox_Rec_PeriodicidadPago = Null;
$x_Rec_Banco = Null; 
$ox_Rec_Banco = Null;
$x_Rec_CuentaBancaria = Null; 
$ox_Rec_CuentaBancaria = Null;
$x_Rec_SalarioBaseCotApor = Null; 
$ox_Rec_SalarioBaseCotApor = Null;
$x_Rec_SalarioDiarioIntegrado = Null; 
$ox_Rec_SalarioDiarioIntegrado = Null;
$x_Rec_ClaveEntFed = Null; 
$ox_Rec_ClaveEntFed = Null;
$x_Rec_Status = Null; 
$ox_Rec_Status = Null;
$x_Mun_ID = Null; 
$ox_Mun_ID = Null;
$x_Rec_Creado = Null; 
$ox_Rec_Creado = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=vit_receptor.xls');
}
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/phpmkrfn.php") ?>
<?php
$nStart= 0;
$nStop= 0;
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
	$_SESSION["vit_receptor_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["vit_receptor_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["vit_receptor_searchwhere"];
}

// Build SQL
$sSql = "SELECT * FROM `vit_receptor`";

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
<?php } ?>
<?php

// Set up Record Set
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStart= 1;
SetUpStartRec(); // Set Up Start Record Position
?>
    <head>
        
        <title>Receptores | VitaInsumos</title>
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
                                <h4 class="mb-sm-0">Receptores</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Modulos</a></li>
                                        <li class="breadcrumb-item active">Receptores</li>
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
												<form action="receptores_listado.php">
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
												<?php if(@$_SESSION["vit_receptor_OrderBy"]!=""){ ?>
												<a class="btn btn-light" href="receptores_listado.php?cmd=resetsort" title="Quitar Orden"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>X</a>
												<?php } ?>											
												<?php if(@$sSrchAdvanced!="" && @$_SESSION["vit_receptor_OrderBy"]==""){ ?>
												<a class="btn btn-light" href="receptores_listado.php?cmd=reset" title="Quitar Filtros"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>F</a>
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
<form method="post">
<table class="table align-middle" id="customerTable">
	<thead class="table-light">
	<tr>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
RFC
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Rec_RFC"); ?>">RFC<?php if (@$_SESSION["vit_receptor_x_Rec_RFC_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Rec_RFC_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Nombre
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Rec_Nombre"); ?>">Nombre<?php if (@$_SESSION["vit_receptor_x_Rec_Nombre_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Rec_Nombre_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Apellido Paterno
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Rec_Apellido_Paterno"); ?>">Apellido Paterno<?php if (@$_SESSION["vit_receptor_x_Rec_Apellido_Paterno_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Rec_Apellido_Paterno_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Apellido Materno
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Rec_Apellido_Materno"); ?>">Apellido Materno<?php if (@$_SESSION["vit_receptor_x_Rec_Apellido_Materno_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Rec_Apellido_Materno_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Domicilio Fiscaleceptor
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Rec_DomicilioFiscaleceptor"); ?>">Domicilio Fiscaleceptor<?php if (@$_SESSION["vit_receptor_x_Rec_DomicilioFiscaleceptor_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Rec_DomicilioFiscaleceptor_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Curp
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Rec_Curp"); ?>">Curp<?php if (@$_SESSION["vit_receptor_x_Rec_Curp_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Rec_Curp_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Fecha Inicio Rel Laboral
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Rec_FechaInicioRelLaboral"); ?>">Fecha Inicio Rel Laboral<?php if (@$_SESSION["vit_receptor_x_Rec_FechaInicioRelLaboral_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Rec_FechaInicioRelLaboral_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Num Empleado
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Rec_NumEmpleado"); ?>">Num Empleado<?php if (@$_SESSION["vit_receptor_x_Rec_NumEmpleado_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Rec_NumEmpleado_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Departamento
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Rec_Departamento"); ?>">Departamento<?php if (@$_SESSION["vit_receptor_x_Rec_Departamento_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Rec_Departamento_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span>
<?php if ($sExport <> "") { ?>
Mun ID
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Mun_ID"); ?>">Mun ID<?php if (@$_SESSION["vit_receptor_x_Mun_ID_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Mun_ID_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
<?php if ($sExport == "") { ?>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<?php } ?>
	</tr>
	</thead>
    <tbody class="list form-check-all">
<?php

// Avoid starting record > total records
if ($nStart> $nTotalRecs) {
	$nStart= $nTotalRecs;
}

// Set the last record to display
$nStopRec = $nStart+ $nDisplayRecs - 1;

// Move to first record directly for performance reason
$nRecCount = $nStart- 1;
if (phpmkr_num_rows($rs) > 0) {
	phpmkr_data_seek($rs, $nStart-1);
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
		$x_Rec_RFC = $row["Rec_RFC"];
		$x_Rec_Nombre = $row["Rec_Nombre"];
		$x_Rec_Apellido_Paterno = $row["Rec_Apellido_Paterno"];
		$x_Rec_Apellido_Materno = $row["Rec_Apellido_Materno"];
		$x_Rec_DomicilioFiscaleceptor = $row["Rec_DomicilioFiscaleceptor"];
		$x_Rec_ResidenciaFiscal = $row["Rec_ResidenciaFiscal"];
		$x_Rec_NumRegIdTrib = $row["Rec_NumRegIdTrib"];
		$x_Rec_RegimenFiscalReceptor = $row["Rec_RegimenFiscalReceptor"];
		$x_Rec_Curp = $row["Rec_Curp"];
		$x_Rec_NumSeguridadSocial = $row["Rec_NumSeguridadSocial"];
		$x_Rec_FechaInicioRelLaboral = $row["Rec_FechaInicioRelLaboral"];
		$x_Rec_Antiguedad = $row["Rec_Antiguedad"];
		$x_Rec_TipoContrato = $row["Rec_TipoContrato"];
		$x_Rec_Sindicalizado = $row["Rec_Sindicalizado"];
		$x_Rec_TipoJornada = $row["Rec_TipoJornada"];
		$x_Rec_TipoRegimen = $row["Rec_TipoRegimen"];
		$x_Rec_NumEmpleado = $row["Rec_NumEmpleado"];
		$x_Rec_Departamento = $row["Rec_Departamento"];
		$x_Rec_Puesto = $row["Rec_Puesto"];
		$x_Rec_RiesgoPuesto = $row["Rec_RiesgoPuesto"];
		$x_Rec_PeriodicidadPago = $row["Rec_PeriodicidadPago"];
		$x_Rec_Banco = $row["Rec_Banco"];
		$x_Rec_CuentaBancaria = $row["Rec_CuentaBancaria"];
		$x_Rec_SalarioBaseCotApor = $row["Rec_SalarioBaseCotApor"];
		$x_Rec_SalarioDiarioIntegrado = $row["Rec_SalarioDiarioIntegrado"];
		$x_Rec_ClaveEntFed = $row["Rec_ClaveEntFed"];
		$x_Rec_Status = $row["Rec_Status"];
		$x_Mun_ID = $row["Mun_ID"];
		$x_Rec_Creado = $row["Rec_Creado"];
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- Rec_RFC -->
		<td><span class="phpmaker">
<?php echo $x_Rec_RFC; ?>
</span></td>
		<!-- Rec_Nombre -->
		<td><span class="phpmaker">
<?php echo str_replace(chr(10), "<br>", $x_Rec_Nombre); ?>
</span></td>
		<!-- Rec_Apellido_Paterno -->
		<td><span class="phpmaker">
<?php echo str_replace(chr(10), "<br>", $x_Rec_Apellido_Paterno); ?>
</span></td>
		<!-- Rec_Apellido_Materno -->
		<td><span class="phpmaker">
<?php echo str_replace(chr(10), "<br>", $x_Rec_Apellido_Materno); ?>
</span></td>
		<!-- Rec_DomicilioFiscaleceptor -->
		<td><span class="phpmaker">
<?php echo $x_Rec_DomicilioFiscaleceptor; ?>
</span></td>
		<!-- Rec_Curp -->
		<td><span class="phpmaker">
<?php echo $x_Rec_Curp; ?>
</span></td>
		<!-- Rec_FechaInicioRelLaboral -->
		<td><span class="phpmaker">
<?php echo $x_Rec_FechaInicioRelLaboral; ?>
</span></td>
		<!-- Rec_NumEmpleado -->
		<td><span class="phpmaker">
<?php echo $x_Rec_NumEmpleado; ?>
</span></td>
		<!-- Rec_Departamento -->
		<td><span class="phpmaker">
<?php echo str_replace(chr(10), "<br>", $x_Rec_Departamento); ?>
</span></td>
		<!-- Mun_ID -->
		<td><span class="phpmaker">
<?php echo $x_Mun_ID; ?>
</span></td>
<?php if ($sExport == "") { ?>
<td><span class="phpmaker"><a href="<?php if ($x_Rec_RFC <> "") {echo "vit_receptorview.php?Rec_RFC=" . urlencode($x_Rec_RFC); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">View</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_Rec_RFC <> "") {echo "vit_receptoredit.php?Rec_RFC=" . urlencode($x_Rec_RFC); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_Rec_RFC <> "") {echo "vit_receptordelete.php?Rec_RFC=" . urlencode($x_Rec_RFC); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Delete</a></span></td>
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
<form action="vit_receptorlist.php" name="ewpagerform" id="ewpagerform">
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
	<td><a class="page-item pagination-prev" href="receptores_listado.php?start=1">|<</a></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($PrevStart == $nStartRec) { ?>
	<td><a class="page-item pagination-prev disabled"><</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-prev" href="receptores_listado.php?start=<?php echo $PrevStart; ?>"><</a></td>
	<?php } ?>
<!--current page number-->
	<td><input class="form-control form-control-sm" type="text" name="pageno" value="<?php echo intval(($nStartRec-1)/$nDisplayRecs+1); ?>" size="4"></td>
<!--next page button-->
	<?php if ($NextStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">></a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="receptores_listado.php?start=<?php echo $NextStart; ?>">></a></td>
	<?php  } ?>
<!--last page button-->
	<?php if ($LastStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">>|</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="receptores_listado.php?start=<?php echo $LastStart; ?>">>|</a></td>
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
												<a class="btn btn-light w-100" href="receptores_listado.php?cmd=resetsort">Quitar Orden</a>
												<?php } ?>											
												<?php if(@$sWhere!="" && @$_SESSION["vit_receptor_OrderBy"]==""){ ?>
												<a class="btn btn-light w-100" href="receptores_listado.php?cmd=reset">Quitar Filtros</a>
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
		$_SESSION["vit_receptor_RecPerPage"] = $nDisplayRecs; // Save to Session

		// Reset Start Position (Reset Command)
		$nStart= 1;
		$_SESSION["vit_receptor_REC"] = $nStartRec;
	}else{
		if (@$_SESSION["vit_receptor_RecPerPage"] <> "") {
			$nDisplayRecs = $_SESSION["vit_receptor_RecPerPage"]; // Restore from Session
		}else{
			$nDisplayRecs = 10; // Load Default
		}
	}
}

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`Rec_RFC` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Nombre` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Apellido_Paterno` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Apellido_Materno` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_DomicilioFiscaleceptor` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_ResidenciaFiscal` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_NumRegIdTrib` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_RegimenFiscalReceptor` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Curp` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_NumSeguridadSocial` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_FechaInicioRelLaboral` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Antiguedad` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_TipoContrato` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Sindicalizado` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_TipoJornada` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_TipoRegimen` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_NumEmpleado` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Departamento` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Puesto` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_RiesgoPuesto` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_PeriodicidadPago` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Banco` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_CuentaBancaria` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_SalarioBaseCotApor` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_SalarioDiarioIntegrado` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_ClaveEntFed` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Status` LIKE '%" . $sKeyword . "%' OR ";
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

		// Field Rec_RFC
		if ($sOrder == "Rec_RFC") {
			$sSortField = "`Rec_RFC`";
			$sLastSort = @$_SESSION["vit_receptor_x_Rec_RFC_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Rec_RFC_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Rec_RFC_Sort"] <> "") { @$_SESSION["vit_receptor_x_Rec_RFC_Sort"] = ""; }
		}

		// Field Rec_Nombre
		if ($sOrder == "Rec_Nombre") {
			$sSortField = "`Rec_Nombre`";
			$sLastSort = @$_SESSION["vit_receptor_x_Rec_Nombre_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Rec_Nombre_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Rec_Nombre_Sort"] <> "") { @$_SESSION["vit_receptor_x_Rec_Nombre_Sort"] = ""; }
		}

		// Field Rec_Apellido_Paterno
		if ($sOrder == "Rec_Apellido_Paterno") {
			$sSortField = "`Rec_Apellido_Paterno`";
			$sLastSort = @$_SESSION["vit_receptor_x_Rec_Apellido_Paterno_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Rec_Apellido_Paterno_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Rec_Apellido_Paterno_Sort"] <> "") { @$_SESSION["vit_receptor_x_Rec_Apellido_Paterno_Sort"] = ""; }
		}

		// Field Rec_Apellido_Materno
		if ($sOrder == "Rec_Apellido_Materno") {
			$sSortField = "`Rec_Apellido_Materno`";
			$sLastSort = @$_SESSION["vit_receptor_x_Rec_Apellido_Materno_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Rec_Apellido_Materno_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Rec_Apellido_Materno_Sort"] <> "") { @$_SESSION["vit_receptor_x_Rec_Apellido_Materno_Sort"] = ""; }
		}

		// Field Rec_DomicilioFiscaleceptor
		if ($sOrder == "Rec_DomicilioFiscaleceptor") {
			$sSortField = "`Rec_DomicilioFiscaleceptor`";
			$sLastSort = @$_SESSION["vit_receptor_x_Rec_DomicilioFiscaleceptor_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Rec_DomicilioFiscaleceptor_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Rec_DomicilioFiscaleceptor_Sort"] <> "") { @$_SESSION["vit_receptor_x_Rec_DomicilioFiscaleceptor_Sort"] = ""; }
		}

		// Field Rec_Curp
		if ($sOrder == "Rec_Curp") {
			$sSortField = "`Rec_Curp`";
			$sLastSort = @$_SESSION["vit_receptor_x_Rec_Curp_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Rec_Curp_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Rec_Curp_Sort"] <> "") { @$_SESSION["vit_receptor_x_Rec_Curp_Sort"] = ""; }
		}

		// Field Rec_FechaInicioRelLaboral
		if ($sOrder == "Rec_FechaInicioRelLaboral") {
			$sSortField = "`Rec_FechaInicioRelLaboral`";
			$sLastSort = @$_SESSION["vit_receptor_x_Rec_FechaInicioRelLaboral_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Rec_FechaInicioRelLaboral_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Rec_FechaInicioRelLaboral_Sort"] <> "") { @$_SESSION["vit_receptor_x_Rec_FechaInicioRelLaboral_Sort"] = ""; }
		}

		// Field Rec_NumEmpleado
		if ($sOrder == "Rec_NumEmpleado") {
			$sSortField = "`Rec_NumEmpleado`";
			$sLastSort = @$_SESSION["vit_receptor_x_Rec_NumEmpleado_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Rec_NumEmpleado_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Rec_NumEmpleado_Sort"] <> "") { @$_SESSION["vit_receptor_x_Rec_NumEmpleado_Sort"] = ""; }
		}

		// Field Rec_Departamento
		if ($sOrder == "Rec_Departamento") {
			$sSortField = "`Rec_Departamento`";
			$sLastSort = @$_SESSION["vit_receptor_x_Rec_Departamento_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Rec_Departamento_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Rec_Departamento_Sort"] <> "") { @$_SESSION["vit_receptor_x_Rec_Departamento_Sort"] = ""; }
		}

		// Field Mun_ID
		if ($sOrder == "Mun_ID") {
			$sSortField = "`Mun_ID`";
			$sLastSort = @$_SESSION["vit_receptor_x_Mun_ID_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Mun_ID_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Mun_ID_Sort"] <> "") { @$_SESSION["vit_receptor_x_Mun_ID_Sort"] = ""; }
		}
		$_SESSION["vit_receptor_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["vit_receptor_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["vit_receptor_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["vit_receptor_OrderBy"] = $sOrderBy;
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
		$nStart= @$_GET["start"];
		$_SESSION["vit_receptor_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStart= ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStart<= 0) {
				$nStart= 1;
			}elseif ($nStart>= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStart= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["vit_receptor_REC"] = $nStartRec;
		}else{
			$nStart= @$_SESSION["vit_receptor_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStart== "")) {
				$nStart= 1; // Reset start record counter
				$_SESSION["vit_receptor_REC"] = $nStartRec;
			}
		}
	}else{
		$nStart= @$_SESSION["vit_receptor_REC"];
		if (!(is_numeric($nStartRec)) || ($nStart== "")) {
			$nStart= 1; //Reset start record counter
			$_SESSION["vit_receptor_REC"] = $nStartRec;
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
			$_SESSION["vit_receptor_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["vit_receptor_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["vit_receptor_OrderBy"] = $sOrderBy;
			if (@$_SESSION["vit_receptor_x_Rec_RFC_Sort"] <> "") { $_SESSION["vit_receptor_x_Rec_RFC_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_x_Rec_Nombre_Sort"] <> "") { $_SESSION["vit_receptor_x_Rec_Nombre_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_x_Rec_Apellido_Paterno_Sort"] <> "") { $_SESSION["vit_receptor_x_Rec_Apellido_Paterno_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_x_Rec_Apellido_Materno_Sort"] <> "") { $_SESSION["vit_receptor_x_Rec_Apellido_Materno_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_x_Rec_DomicilioFiscaleceptor_Sort"] <> "") { $_SESSION["vit_receptor_x_Rec_DomicilioFiscaleceptor_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_x_Rec_Curp_Sort"] <> "") { $_SESSION["vit_receptor_x_Rec_Curp_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_x_Rec_FechaInicioRelLaboral_Sort"] <> "") { $_SESSION["vit_receptor_x_Rec_FechaInicioRelLaboral_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_x_Rec_NumEmpleado_Sort"] <> "") { $_SESSION["vit_receptor_x_Rec_NumEmpleado_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_x_Rec_Departamento_Sort"] <> "") { $_SESSION["vit_receptor_x_Rec_Departamento_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_x_Mun_ID_Sort"] <> "") { $_SESSION["vit_receptor_x_Mun_ID_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStart= 1;
		$_SESSION["vit_receptor_REC"] = $nStartRec;
	}
}
?>
