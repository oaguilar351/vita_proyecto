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
$x_Rmv_ID = Null; 
$ox_Rmv_ID = Null;
$x_Rec_RFC = Null; 
$ox_Rec_RFC = Null;
$x_Rec_FechaInicioRelLaboral = Null; 
$ox_Rec_FechaInicioRelLaboral = Null;
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
$x_Rec_SalarioBaseCotApor = Null; 
$ox_Rec_SalarioBaseCotApor = Null;
$x_Rec_SalarioDiarioIntegrado = Null; 
$ox_Rec_SalarioDiarioIntegrado = Null;
$x_Rec_Status = Null; 
$ox_Rec_Status = Null;
$x_Rmv_Fecha = Null; 
$ox_Rmv_Fecha = Null;
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/db_cat_sat.php") ?>
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
$x_Rec_Antiguedad = Null;
$ox_Rec_Antiguedad = Null;
$x_Rec_TipoContrato = Null;
$ox_Rec_TipoContrato = Null;
$x_Rec_Banco = Null;
$ox_Rec_Banco = Null;
$x_Rec_CuentaBancaria = Null;
$ox_Rec_CuentaBancaria = Null;
$x_Rec_ClaveEntFed = Null;
$ox_Rec_ClaveEntFed = Null;
$x_Mun_ID = Null;
$ox_Mun_ID = Null;
$x_Rec_CreationDate = Null;
$ox_Rec_CreationDate = Null;
$nDisplayRecs = 10;
$nRecRange = 10;

// Set up records per page dynamically
SetUpDisplayRecs();

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$conn_sat = phpmkr_db_connect_sat(HOST, USER, PASS, DB, PORT);

// Handle Reset Command
ResetCmd();

// Set Up Master Detail Parameters
SetUpMasterDetail();

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
	$_SESSION["vit_receptor_movimientos_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["vit_receptor_movimientos_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["vit_receptor_movimientos_searchwhere"];
}

// Build SQL
$sSql = "SELECT * FROM `Vit_Receptor_Movimientos`";

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
	$sSqlMasterBase = "SELECT * FROM `Vit_Receptor`";
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
		$_SESSION["vit_receptor_movimientos_DetailWhere"] = "";
		$_SESSION["ewmsg"] = "No records found";
		phpmkr_free_result($rs);
		phpmkr_db_close($conn);
		header("Location: vit_receptorlist.php");
	}
}
?>
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "-"; // set date separator	

//-->
</script>
<head>
        
        <title>Empleado Historico | VitaInsumos</title>
        <?php include 'layouts/title-meta.php'; ?>
        <link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

        <?php include 'layouts/head-css.php'; ?>

    </head>

    <?php include 'layouts/body.php'; ?>

        <!-- Begin page -->
        <div id="layout-wrapper">

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
                                <h4 class="mb-sm-0">Empleado Historico</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Modulos</a></li>
                                        <li class="breadcrumb-item active">Empleado Historico</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
					<div class="row">
                        <div class="col-lg-12">
                            <div class="card" id="leadsList">
								 <div class="card-header">
                                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0"
                                        role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails"
                                                role="tab">
                                                <i class="fas fa-home"></i>
                                                <b>Movimientos Empleado</b>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
								<div class="card-body p-4">

<?php
if ($sDbWhereMaster <> "") {
	if ($bMasterRecordExist) { ?>

<table class="table align-middle" id="customerTable">
	<thead class="table-light">
		<tr>
			<th><a href="javascript:void(0);">RFC</a></th>
			<th><a href="javascript:void(0);">Nombre</a></th>
			<th><a href="javascript:void(0);">Puesto</a></th>
			<th><a href="javascript:void(0);">Departamento</a></th>
			<th><a href="javascript:void(0);">Numero Empleado</a></th>
			<th><a href="javascript:void(0);">Inicio Relacion</a></th>
			<th><a href="javascript:void(0);">Domicilio</a></th>
			<th><a href="javascript:void(0);">Municipio</a></th>
			<th><a href="javascript:void(0);">Status</a></th>
			<th><a href="javascript:void(0);">&nbsp;</a></th>
		</tr>
	</thead>
	<tbody class="list form-check-all">
	<tr bgcolor="#FFFFFF">
<?php
		$row = phpmkr_fetch_array($rs);		
		$Rec_RFC = $row["Rec_RFC"];
		$Rec_Nombre = $row["Rec_Nombre"]." ".$row["Rec_Apellido_Paterno"]." ".$row["Rec_Apellido_Materno"];
		$Rec_Puesto = $row["Rec_Puesto"];
		$Rec_Departamento = $row["Rec_Departamento"];
		$Rec_Fecha = $row["Rec_FechaInicioRelLaboral"];
		$Rec_NumEmpleado = $row["Rec_NumEmpleado"];
		$Rec_Domicilio = $row["Rec_DomicilioFiscaleceptor"];
		$Mun_Descrip = "";
		$Rec_Status = ($row["Rec_Status"]=="1")?'Activo':'Inactivo';
		
		if ((!is_null($row["Mun_ID"])) && ($row["Mun_ID"] <> "")) {
		$sSqlWrk = "SELECT Mun_Descrip FROM Vit_Municipios ";
		$sSqlWrk .= " WHERE Mun_ID = '" . $row["Mun_ID"] . "'";
		#echo $sSqlWrk;
		$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
		if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
			$Mun_Descrip = strtoupper($rowwrk["Mun_Descrip"]);
		}
		}
?>
		<td><?php echo $x_Rec_RFC; ?></td>
		<td><?php echo strtoupper($Rec_Nombre); ?></td>
		<td><?php echo strtoupper($Rec_Puesto); ?></td>
		<td><?php echo strtoupper($Rec_Departamento); ?></td>
		<td><?php echo strtoupper($Rec_NumEmpleado); ?></td>
		<td><?php echo FormatDateTime($Rec_Fecha,5); ?></td>
		<td><?php echo strtoupper($Rec_Domicilio); ?></td>
		<td><?php echo strtoupper($Mun_Descrip); ?></td>
		<td><?php echo strtoupper($Rec_Status); ?></td>
		<td><a class="btn btn-primary waves-effect waves-light" href="empleados_listado.php" title="Regresar Listado Empleados">Regresar</a></td>		
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
<form method="post">
<table class="table align-middle" id="customerTable">
<thead class="table-light">
	<tr>		
		<th valign="top"><span>
	<a href="vit_receptor_movimientoslist.php?order=<?php echo urlencode("Rec_NumEmpleado"); ?>">Num Empleado&nbsp;<?php if (@$_SESSION["vit_receptor_movimientos_x_Rec_NumEmpleado_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_movimientos_x_Rec_NumEmpleado_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="vit_receptor_movimientoslist.php?order=<?php echo urlencode("Rec_Departamento"); ?>">Departamento&nbsp;<?php if (@$_SESSION["vit_receptor_movimientos_x_Rec_Departamento_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_movimientos_x_Rec_Departamento_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="vit_receptor_movimientoslist.php?order=<?php echo urlencode("Rec_Puesto"); ?>">Puesto&nbsp;<?php if (@$_SESSION["vit_receptor_movimientos_x_Rec_Puesto_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_movimientos_x_Rec_Puesto_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="vit_receptor_movimientoslist.php?order=<?php echo urlencode("Rec_Sindicalizado"); ?>">Sindicalizado<?php if (@$_SESSION["vit_receptor_movimientos_x_Rec_Sindicalizado_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_movimientos_x_Rec_Sindicalizado_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="vit_receptor_movimientoslist.php?order=<?php echo urlencode("Rec_TipoJornada"); ?>">Tipo Jornada<?php if (@$_SESSION["vit_receptor_movimientos_x_Rec_TipoJornada_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_movimientos_x_Rec_TipoJornada_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="vit_receptor_movimientoslist.php?order=<?php echo urlencode("Rec_TipoRegimen"); ?>">Tipo Regimen<?php if (@$_SESSION["vit_receptor_movimientos_x_Rec_TipoRegimen_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_movimientos_x_Rec_TipoRegimen_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="vit_receptor_movimientoslist.php?order=<?php echo urlencode("Rec_RiesgoPuesto"); ?>">Riesgo Puesto<?php if (@$_SESSION["vit_receptor_movimientos_x_Rec_RiesgoPuesto_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_movimientos_x_Rec_RiesgoPuesto_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="vit_receptor_movimientoslist.php?order=<?php echo urlencode("Rec_PeriodicidadPago"); ?>">Periodicidad Pago<?php if (@$_SESSION["vit_receptor_movimientos_x_Rec_PeriodicidadPago_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_movimientos_x_Rec_PeriodicidadPago_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="vit_receptor_movimientoslist.php?order=<?php echo urlencode("Rec_SalarioBaseCotApor"); ?>">Salario Base&nbsp;<?php if (@$_SESSION["vit_receptor_movimientos_x_Rec_SalarioBaseCotApor_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_movimientos_x_Rec_SalarioBaseCotApor_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="vit_receptor_movimientoslist.php?order=<?php echo urlencode("Rec_SalarioDiarioIntegrado"); ?>">Salario Diario Integrado&nbsp;<?php if (@$_SESSION["vit_receptor_movimientos_x_Rec_SalarioDiarioIntegrado_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_movimientos_x_Rec_SalarioDiarioIntegrado_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="vit_receptor_movimientoslist.php?order=<?php echo urlencode("Rec_Status"); ?>">Status<?php if (@$_SESSION["vit_receptor_movimientos_x_Rec_Status_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_movimientos_x_Rec_Status_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="vit_receptor_movimientoslist.php?order=<?php echo urlencode("Rmv_Fecha"); ?>">Fecha Movimiento<?php if (@$_SESSION["vit_receptor_movimientos_x_Rmv_Fecha_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_movimientos_x_Rmv_Fecha_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
<!--<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>-->
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
		$x_Rmv_ID = $row["Rmv_ID"];
		$x_Rec_RFC = $row["Rec_RFC"];
		$x_Rec_FechaInicioRelLaboral = $row["Rec_FechaInicioRelLaboral"];
		$x_Rec_Sindicalizado = $row["Rec_Sindicalizado"];
		$x_Rec_TipoJornada = $row["Rec_TipoJornada"];
		$x_Rec_TipoRegimen = $row["Rec_TipoRegimen"];
		$x_Rec_NumEmpleado = $row["Rec_NumEmpleado"];
		$x_Rec_Departamento = $row["Rec_Departamento"];
		$x_Rec_Puesto = $row["Rec_Puesto"];
		$x_Rec_RiesgoPuesto = $row["Rec_RiesgoPuesto"];
		$x_Rec_PeriodicidadPago = $row["Rec_PeriodicidadPago"];
		$x_Rec_SalarioBaseCotApor = ($row["Rec_SalarioBaseCotApor"]!="")?$row["Rec_SalarioBaseCotApor"]:0;
		$x_Rec_SalarioDiarioIntegrado = ($row["Rec_SalarioDiarioIntegrado"]!="")?$row["Rec_SalarioDiarioIntegrado"]:0;
		$x_Rec_Status = $row["Rec_Status"];
		$x_Rmv_Fecha = $row["Rmv_Fecha"];
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- Rec_NumEmpleado -->
		<td><span class="phpmaker">
<?php echo $x_Rec_NumEmpleado; ?>
</span></td>
		<!-- Rec_Departamento -->
		<td><span class="phpmaker">
<?php echo str_replace(chr(10), "<br>", $x_Rec_Departamento); ?>
</span></td>
		<!-- Rec_Puesto -->
		<td><span class="phpmaker">
<?php echo str_replace(chr(10), "<br>", $x_Rec_Puesto); ?>
</span></td>
<!-- Rec_Sindicalizado -->
		<td><span class="phpmaker">
<?php
switch ($x_Rec_Sindicalizado) {
	case "Sí":
		$sTmp = "Sí";
		break;
	case "No":
		$sTmp = "No";
		break;
	default:
		$sTmp = "";
}
$ox_Rec_Sindicalizado = $x_Rec_Sindicalizado; // Backup Original Value
$x_Rec_Sindicalizado = $sTmp;
?>
<?php echo $x_Rec_Sindicalizado; ?>
<?php $x_Rec_Sindicalizado = $ox_Rec_Sindicalizado; // Restore Original Value ?>
</span></td>
		<!-- Rec_TipoJornada -->
		<td><span class="phpmaker">
<?php
if ((!is_null($x_Rec_TipoJornada)) && ($x_Rec_TipoJornada <> "")) {
	$sSqlWrk = "SELECT DISTINCT `Descripcion` FROM `c_TipoJornada`";
	$sTmp = $x_Rec_TipoJornada;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `c_TipoJornada` = " . $sTmp . "";
	$sSqlWrk .= " ORDER BY `Descripcion` Asc";
	$rswrk = phpmkr_query($sSqlWrk,$conn_sat) or die("Failed to execute query" . phpmkr_error($conn_sat) . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["Descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_Rec_TipoJornada = $x_Rec_TipoJornada; // Backup Original Value
$x_Rec_TipoJornada = $sTmp;
?>
<?php echo $x_Rec_TipoJornada; ?>
<?php $x_Rec_TipoJornada = $ox_Rec_TipoJornada; // Restore Original Value ?>
</span></td>
		<!-- Rec_TipoRegimen -->
		<td><span class="phpmaker">
<?php
if ((!is_null($x_Rec_TipoRegimen)) && ($x_Rec_TipoRegimen <> "")) {
	$sSqlWrk = "SELECT DISTINCT `Descripcion` FROM `c_TipoRegimen`";
	$sTmp = $x_Rec_TipoRegimen;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `c_TipoRegimen` = " . $sTmp . "";
	$sSqlWrk .= " ORDER BY `Descripcion` Asc";
	$rswrk = phpmkr_query($sSqlWrk,$conn_sat) or die("Failed to execute query" . phpmkr_error($conn_sat) . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["Descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_Rec_TipoRegimen = $x_Rec_TipoRegimen; // Backup Original Value
$x_Rec_TipoRegimen = $sTmp;
?>
<?php echo $x_Rec_TipoRegimen; ?>
<?php $x_Rec_TipoRegimen = $ox_Rec_TipoRegimen; // Restore Original Value ?>
</span></td>
		<!-- Rec_RiesgoPuesto -->
		<td><span class="phpmaker">
<?php
if ((!is_null($x_Rec_RiesgoPuesto)) && ($x_Rec_RiesgoPuesto <> "")) {
	$sSqlWrk = "SELECT DISTINCT `Descripcion` FROM `c_RiesgoPuesto`";
	$sTmp = $x_Rec_RiesgoPuesto;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `c_RiesgoPuesto` = " . $sTmp . "";
	$sSqlWrk .= " ORDER BY `Descripcion` Asc";
	$rswrk = phpmkr_query($sSqlWrk,$conn_sat) or die("Failed to execute query" . phpmkr_error($conn_sat) . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["Descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_Rec_RiesgoPuesto = $x_Rec_RiesgoPuesto; // Backup Original Value
$x_Rec_RiesgoPuesto = $sTmp;
?>
<?php echo $x_Rec_RiesgoPuesto; ?>
<?php $x_Rec_RiesgoPuesto = $ox_Rec_RiesgoPuesto; // Restore Original Value ?>
</span></td>
		<!-- Rec_PeriodicidadPago -->
		<td><span class="phpmaker">
<?php
if ((!is_null($x_Rec_PeriodicidadPago)) && ($x_Rec_PeriodicidadPago <> "")) {
	$sSqlWrk = "SELECT DISTINCT `Descripcion` FROM `c_PeriodicidadPago`";
	$sTmp = $x_Rec_PeriodicidadPago;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `c_PeriodicidadPago` = " . $sTmp . "";
	$sSqlWrk .= " ORDER BY `Descripcion` Asc";
	$rswrk = phpmkr_query($sSqlWrk,$conn_sat) or die("Failed to execute query" . phpmkr_error($conn_sat) . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["Descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_Rec_PeriodicidadPago = $x_Rec_PeriodicidadPago; // Backup Original Value
$x_Rec_PeriodicidadPago = $sTmp;
?>
<?php echo $x_Rec_PeriodicidadPago; ?>
<?php $x_Rec_PeriodicidadPago = $ox_Rec_PeriodicidadPago; // Restore Original Value ?>
</span></td>
		<!-- Rec_SalarioBaseCotApor -->
		<td align="right"><span class="phpmaker">
<?php echo (is_numeric($x_Rec_SalarioBaseCotApor)) ? FormatCurrency($x_Rec_SalarioBaseCotApor,2,-2,-2,-2) : $x_Rec_SalarioBaseCotApor; ?>
</span></td>
		<!-- Rec_SalarioDiarioIntegrado -->
		<td align="right"><span class="phpmaker">
<?php echo (is_numeric($x_Rec_SalarioDiarioIntegrado)) ? FormatCurrency($x_Rec_SalarioDiarioIntegrado,2,-2,-2,-2) : $x_Rec_SalarioDiarioIntegrado; ?>
</span></td>
		<!-- Rec_Status -->
		<td><span class="phpmaker">
<?php
switch ($x_Rec_Status) {
	case "0":
		$sTmp = "Inactivo";
		break;
	case "1":
		$sTmp = "Activo";
		break;
	default:
		$sTmp = "";
}
$ox_Rec_Status = $x_Rec_Status; // Backup Original Value
$x_Rec_Status = $sTmp;
?>
<?php echo $x_Rec_Status; ?>
<?php $x_Rec_Status = $ox_Rec_Status; // Restore Original Value ?>
</span></td>
		<!-- Rmv_Fecha -->
		<td><span class="phpmaker">
<?php echo FormatDateTime($x_Rmv_Fecha,5); ?>
</span></td>
<!--<td><span class="phpmaker"><a href="<?php if ($x_Rmv_ID <> "") {echo "vit_receptor_movimientosview.php?Rmv_ID=" . urlencode($x_Rmv_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">View</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_Rmv_ID <> "") {echo "vit_receptor_movimientosedit.php?Rmv_ID=" . urlencode($x_Rmv_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_Rmv_ID <> "") {echo "vit_receptor_movimientosadd.php?Rmv_ID=" . urlencode($x_Rmv_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Copy</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_Rmv_ID <> "") {echo "vit_receptor_movimientosdelete.php?Rmv_ID=" . urlencode($x_Rmv_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Delete</a></span></td>-->
	</tr>
<?php
	}
}
?>
	</tbody>	
</table>
</form>
<?php } ?>
<?php

// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
<div class="d-flex justify-content-end mt-3">
	<div class="pagination-wrap hstack gap-2">
<form action="vit_receptor_movimientoslist.php" name="ewpagerform" id="ewpagerform">
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
	<td><a class="page-item pagination-prev" href="vit_receptor_movimientoslist.php?start=1">|<</a></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($PrevStart == $nStartRec) { ?>
	<td><a class="page-item pagination-prev disabled"><</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-prev" href="vit_receptor_movimientoslist.php?start=<?php echo $PrevStart; ?>"><</a></td>
	<?php } ?>
<!--current page number-->
	<td><input class="form-control form-control-sm" type="text" name="pageno" value="<?php echo intval(($nStartRec-1)/$nDisplayRecs+1); ?>" size="4"></td>
<!--next page button-->
	<?php if ($NextStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">></a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="vit_receptor_movimientoslist.php?start=<?php echo $NextStart; ?>">></a></td>
	<?php  } ?>
<!--last page button-->
	<?php if ($LastStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">>|</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="vit_receptor_movimientoslist.php?start=<?php echo $LastStart; ?>">>|</a></td>
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
<option value="ALL"<?php if (@$_SESSION["vit_receptor_movimientos_RecPerPage"] == -1) { echo " selected";  }?>>Todos</option>
</select>
		</span></td>
<?php } ?>
	</tr>
</table>
</form>
</div>
	</div>
	</div>
	
</div>
			</div>

                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->	
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
        <script src="assets/js/app.js"></script>
        <!-- App js -->
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
		$_SESSION["vit_receptor_movimientos_RecPerPage"] = $nDisplayRecs; // Save to Session

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_receptor_movimientos_REC"] = $nStartRec;
	}else{
		if (@$_SESSION["vit_receptor_movimientos_RecPerPage"] <> "") {
			$nDisplayRecs = $_SESSION["vit_receptor_movimientos_RecPerPage"]; // Restore from Session
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
	global $x_Rec_RFC;

	// Get the keys for master table
	if (strlen(@$_GET["showmaster"]) > 0) {

		// Reset start record counter (new master key)
		$nStartRec = 1;
		$_SESSION["vit_receptor_movimientos_REC"] = $nStartRec;
		$sDbWhereMaster = "";
		$sDbWhereDetail = "";	
		$x_Rec_RFC = @$_GET["Rec_RFC"]; // Load Parameter from QueryString
		$sTmp = (!(function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())) ? addslashes($x_Rec_RFC) : $x_Rec_RFC;
		if ($sDbWhereMaster <> "") { $sDbWhereMaster .= " AND "; }
		$sDbWhereMaster .= "`Rec_RFC` =  '" . $sTmp . "'";
		if ($sDbWhereDetail <> "") { $sDbWhereDetail .= " AND "; }
		$sDbWhereDetail .= "`Rec_RFC` =  '" . $sTmp  . "'";
		$_SESSION["vit_receptor_movimientos_MasterKey_Rec_RFC"] = $sTmp; // Save Master Key Value
		$_SESSION["vit_receptor_movimientos_MasterWhere"] = $sDbWhereMaster;
		$_SESSION["vit_receptor_movimientos_DetailWhere"] = $sDbWhereDetail;
	}else{
		$sDbWhereMaster = @$_SESSION["vit_receptor_movimientos_MasterWhere"];
		$sDbWhereDetail = @$_SESSION["vit_receptor_movimientos_DetailWhere"];
	}
}

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!(function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`Rec_RFC` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_FechaInicioRelLaboral` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Sindicalizado` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_TipoJornada` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_TipoRegimen` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_NumEmpleado` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Departamento` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Puesto` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_RiesgoPuesto` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_PeriodicidadPago` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_SalarioBaseCotApor` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_SalarioDiarioIntegrado` LIKE '%" . $sKeyword . "%' OR ";
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

		// Field Rec_RFC
		if ($sOrder == "Rec_RFC") {
			$sSortField = "`Rec_RFC`";
			$sLastSort = @$_SESSION["vit_receptor_movimientos_x_Rec_RFC_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_movimientos_x_Rec_RFC_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_RFC_Sort"] <> "") { @$_SESSION["vit_receptor_movimientos_x_Rec_RFC_Sort"] = ""; }
		}

		// Field Rec_FechaInicioRelLaboral
		if ($sOrder == "Rec_FechaInicioRelLaboral") {
			$sSortField = "`Rec_FechaInicioRelLaboral`";
			$sLastSort = @$_SESSION["vit_receptor_movimientos_x_Rec_FechaInicioRelLaboral_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_movimientos_x_Rec_FechaInicioRelLaboral_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_FechaInicioRelLaboral_Sort"] <> "") { @$_SESSION["vit_receptor_movimientos_x_Rec_FechaInicioRelLaboral_Sort"] = ""; }
		}

		// Field Rec_Sindicalizado
		if ($sOrder == "Rec_Sindicalizado") {
			$sSortField = "`Rec_Sindicalizado`";
			$sLastSort = @$_SESSION["vit_receptor_movimientos_x_Rec_Sindicalizado_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_movimientos_x_Rec_Sindicalizado_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_Sindicalizado_Sort"] <> "") { @$_SESSION["vit_receptor_movimientos_x_Rec_Sindicalizado_Sort"] = ""; }
		}

		// Field Rec_TipoJornada
		if ($sOrder == "Rec_TipoJornada") {
			$sSortField = "`Rec_TipoJornada`";
			$sLastSort = @$_SESSION["vit_receptor_movimientos_x_Rec_TipoJornada_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_movimientos_x_Rec_TipoJornada_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_TipoJornada_Sort"] <> "") { @$_SESSION["vit_receptor_movimientos_x_Rec_TipoJornada_Sort"] = ""; }
		}

		// Field Rec_TipoRegimen
		if ($sOrder == "Rec_TipoRegimen") {
			$sSortField = "`Rec_TipoRegimen`";
			$sLastSort = @$_SESSION["vit_receptor_movimientos_x_Rec_TipoRegimen_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_movimientos_x_Rec_TipoRegimen_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_TipoRegimen_Sort"] <> "") { @$_SESSION["vit_receptor_movimientos_x_Rec_TipoRegimen_Sort"] = ""; }
		}

		// Field Rec_NumEmpleado
		if ($sOrder == "Rec_NumEmpleado") {
			$sSortField = "`Rec_NumEmpleado`";
			$sLastSort = @$_SESSION["vit_receptor_movimientos_x_Rec_NumEmpleado_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_movimientos_x_Rec_NumEmpleado_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_NumEmpleado_Sort"] <> "") { @$_SESSION["vit_receptor_movimientos_x_Rec_NumEmpleado_Sort"] = ""; }
		}

		// Field Rec_Departamento
		if ($sOrder == "Rec_Departamento") {
			$sSortField = "`Rec_Departamento`";
			$sLastSort = @$_SESSION["vit_receptor_movimientos_x_Rec_Departamento_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_movimientos_x_Rec_Departamento_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_Departamento_Sort"] <> "") { @$_SESSION["vit_receptor_movimientos_x_Rec_Departamento_Sort"] = ""; }
		}

		// Field Rec_Puesto
		if ($sOrder == "Rec_Puesto") {
			$sSortField = "`Rec_Puesto`";
			$sLastSort = @$_SESSION["vit_receptor_movimientos_x_Rec_Puesto_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_movimientos_x_Rec_Puesto_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_Puesto_Sort"] <> "") { @$_SESSION["vit_receptor_movimientos_x_Rec_Puesto_Sort"] = ""; }
		}

		// Field Rec_RiesgoPuesto
		if ($sOrder == "Rec_RiesgoPuesto") {
			$sSortField = "`Rec_RiesgoPuesto`";
			$sLastSort = @$_SESSION["vit_receptor_movimientos_x_Rec_RiesgoPuesto_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_movimientos_x_Rec_RiesgoPuesto_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_RiesgoPuesto_Sort"] <> "") { @$_SESSION["vit_receptor_movimientos_x_Rec_RiesgoPuesto_Sort"] = ""; }
		}

		// Field Rec_PeriodicidadPago
		if ($sOrder == "Rec_PeriodicidadPago") {
			$sSortField = "`Rec_PeriodicidadPago`";
			$sLastSort = @$_SESSION["vit_receptor_movimientos_x_Rec_PeriodicidadPago_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_movimientos_x_Rec_PeriodicidadPago_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_PeriodicidadPago_Sort"] <> "") { @$_SESSION["vit_receptor_movimientos_x_Rec_PeriodicidadPago_Sort"] = ""; }
		}

		// Field Rec_SalarioBaseCotApor
		if ($sOrder == "Rec_SalarioBaseCotApor") {
			$sSortField = "`Rec_SalarioBaseCotApor`";
			$sLastSort = @$_SESSION["vit_receptor_movimientos_x_Rec_SalarioBaseCotApor_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_movimientos_x_Rec_SalarioBaseCotApor_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_SalarioBaseCotApor_Sort"] <> "") { @$_SESSION["vit_receptor_movimientos_x_Rec_SalarioBaseCotApor_Sort"] = ""; }
		}

		// Field Rec_SalarioDiarioIntegrado
		if ($sOrder == "Rec_SalarioDiarioIntegrado") {
			$sSortField = "`Rec_SalarioDiarioIntegrado`";
			$sLastSort = @$_SESSION["vit_receptor_movimientos_x_Rec_SalarioDiarioIntegrado_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_movimientos_x_Rec_SalarioDiarioIntegrado_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_SalarioDiarioIntegrado_Sort"] <> "") { @$_SESSION["vit_receptor_movimientos_x_Rec_SalarioDiarioIntegrado_Sort"] = ""; }
		}

		// Field Rec_Status
		if ($sOrder == "Rec_Status") {
			$sSortField = "`Rec_Status`";
			$sLastSort = @$_SESSION["vit_receptor_movimientos_x_Rec_Status_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_movimientos_x_Rec_Status_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_Status_Sort"] <> "") { @$_SESSION["vit_receptor_movimientos_x_Rec_Status_Sort"] = ""; }
		}

		// Field Rmv_Fecha
		if ($sOrder == "Rmv_Fecha") {
			$sSortField = "`Rmv_Fecha`";
			$sLastSort = @$_SESSION["vit_receptor_movimientos_x_Rmv_Fecha_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_movimientos_x_Rmv_Fecha_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_movimientos_x_Rmv_Fecha_Sort"] <> "") { @$_SESSION["vit_receptor_movimientos_x_Rmv_Fecha_Sort"] = ""; }
		}
		$_SESSION["vit_receptor_movimientos_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["vit_receptor_movimientos_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["vit_receptor_movimientos_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["vit_receptor_movimientos_OrderBy"] = $sOrderBy;
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
		$_SESSION["vit_receptor_movimientos_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["vit_receptor_movimientos_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["vit_receptor_movimientos_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["vit_receptor_movimientos_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["vit_receptor_movimientos_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["vit_receptor_movimientos_REC"] = $nStartRec;
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
			$_SESSION["vit_receptor_movimientos_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["vit_receptor_movimientos_searchwhere"] = $sSrchWhere;
			$_SESSION["vit_receptor_movimientos_MasterWhere"] = ""; // Clear master criteria
			$sDbWhereMaster = "";
			$_SESSION["vit_receptor_movimientos_DetailWhere"] = ""; // Clear detail criteria
			$sDbWhereDetail = "";
		$_SESSION["vit_receptor_movimientos_MasterKey_Rec_RFC"] = ""; // Clear Master Key Value

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["vit_receptor_movimientos_OrderBy"] = $sOrderBy;
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_RFC_Sort"] <> "") { $_SESSION["vit_receptor_movimientos_x_Rec_RFC_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_FechaInicioRelLaboral_Sort"] <> "") { $_SESSION["vit_receptor_movimientos_x_Rec_FechaInicioRelLaboral_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_Sindicalizado_Sort"] <> "") { $_SESSION["vit_receptor_movimientos_x_Rec_Sindicalizado_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_TipoJornada_Sort"] <> "") { $_SESSION["vit_receptor_movimientos_x_Rec_TipoJornada_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_TipoRegimen_Sort"] <> "") { $_SESSION["vit_receptor_movimientos_x_Rec_TipoRegimen_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_NumEmpleado_Sort"] <> "") { $_SESSION["vit_receptor_movimientos_x_Rec_NumEmpleado_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_Departamento_Sort"] <> "") { $_SESSION["vit_receptor_movimientos_x_Rec_Departamento_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_Puesto_Sort"] <> "") { $_SESSION["vit_receptor_movimientos_x_Rec_Puesto_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_RiesgoPuesto_Sort"] <> "") { $_SESSION["vit_receptor_movimientos_x_Rec_RiesgoPuesto_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_PeriodicidadPago_Sort"] <> "") { $_SESSION["vit_receptor_movimientos_x_Rec_PeriodicidadPago_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_SalarioBaseCotApor_Sort"] <> "") { $_SESSION["vit_receptor_movimientos_x_Rec_SalarioBaseCotApor_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_SalarioDiarioIntegrado_Sort"] <> "") { $_SESSION["vit_receptor_movimientos_x_Rec_SalarioDiarioIntegrado_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_movimientos_x_Rec_Status_Sort"] <> "") { $_SESSION["vit_receptor_movimientos_x_Rec_Status_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_movimientos_x_Rmv_Fecha_Sort"] <> "") { $_SESSION["vit_receptor_movimientos_x_Rmv_Fecha_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_receptor_movimientos_REC"] = $nStartRec;
	}
}
?>
