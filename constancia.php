<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php
/*if (@$_SESSION["project1_status"] <> "login") {
	header("Location:  login.php");
	exit();
}*/
?>
<?php

// Initialize common variables
$x_const_ContanciaID = Null; 
$ox_const_ContanciaID = Null;
$x_const_RFC = Null; 
$ox_const_RFC = Null;
$x_const_CURP = Null; 
$ox_const_CURP = Null;
$x_const_Nombres = Null; 
$ox_const_Nombres = Null;
$x_const_Apellido1 = Null; 
$ox_const_Apellido1 = Null;
$x_const_Apellido2 = Null; 
$ox_const_Apellido2 = Null;
$x_const_InicioOperaciones = Null; 
$ox_const_InicioOperaciones = Null;
$x_const_EstatusPadron = Null; 
$ox_const_EstatusPadron = Null;
$x_const_UltimoCambio = Null; 
$ox_const_UltimoCambio = Null;
$x_const_NombreComercial = Null; 
$ox_const_NombreComercial = Null;
$x_const_CodigoPostal = Null; 
$ox_const_CodigoPostal = Null;
$x_const_TipoVialidad = Null; 
$ox_const_TipoVialidad = Null;
$x_const_NombreVialidad = Null; 
$ox_const_NombreVialidad = Null;
$x_const_NumExterior = Null; 
$ox_const_NumExterior = Null;
$x_const_NumInterior = Null; 
$ox_const_NumInterior = Null;
$x_const_Colonia = Null; 
$ox_const_Colonia = Null;
$x_const_Localidad = Null; 
$ox_const_Localidad = Null;
$x_const_Municipio = Null; 
$ox_const_Municipio = Null;
$x_const_Entidad = Null; 
$ox_const_Entidad = Null;
$x_const_EntreCalle = Null; 
$ox_const_EntreCalle = Null;
$x_const_YCalle = Null; 
$ox_const_YCalle = Null;
$x_const_Email = Null; 
$ox_const_Email = Null;
$x_const_TelefonoLada = Null; 
$ox_const_TelefonoLada = Null;
$x_const_TelefonoNum = Null; 
$ox_const_TelefonoNum = Null;
$x_const_EstadoDomicilio = Null; 
$ox_const_EstadoDomicilio = Null;
$x_const_EstadoContribuyente = Null; 
$ox_const_EstadoContribuyente = Null;
$x_const_Archivo = Null; 
$ox_const_Archivo = Null;
$x_const_Ruta = Null; 
$ox_const_Ruta = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=constancias.xls');
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
$x_Rec_CreationDate = Null;
$ox_Rec_CreationDate = Null;
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
	$_SESSION["constancias_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["constancias_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["constancias_searchwhere"];
}

// Build SQL
$sSql = "SELECT * FROM `constancias`";

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
$sSql .= " LIMIT 1";

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
	$rs = phpmkr_query($sSqlMaster, $conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSqlMaster);
	$bMasterRecordExist = (phpmkr_num_rows($rs) > 0);
	if (!$bMasterRecordExist) {
		$_SESSION["_MasterWhere"] = "";
		$_SESSION["constancias_DetailWhere"] = "";
		$_SESSION["ewmsg"] = "No records found";
		phpmkr_free_result($rs);
		phpmkr_db_close($conn);
		header("Location: empleados_listado.php");
	}
}
?>
<?php //include ("header.php") ?>
<?php if ($sExport == "") { ?>
<script type="text/javascript" src="js/ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "-"; // set date separator	

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
        
        <title>Consulta Constancia | VitaInsumos</title>
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
                                                <b>Informacion Receptor</b>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                
                            
<?php
if ($sDbWhereMaster <> "") {
	if ($bMasterRecordExist) { 
		$row = phpmkr_fetch_array($rs);
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
		$x_Rec_CreationDate = $row["Rec_CreationDate"];
?>
								<div class="card-body p-4">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
										<form name="vit_receptoredit" id="vit_receptoredit" action="vit_receptoredit.php" method="post" onSubmit="return EW_checkMyForm(this);">										
                                                <div class="row">
                                                    <div class="col-lg-2" style="background-color:#eaf2f8;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">RFC</label>
                                                            <input type="text" class="form-control" id="x_Rec_RFC" name="x_Rec_RFC" value="<?php echo htmlspecialchars(@$x_Rec_RFC); ?>" readonly>
															</div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-3" style="background-color:#eaf2f8;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Curp</label>
                                                            <input type="text" class="form-control" id="x_Rec_Curp" name="x_Rec_Curp" placeholder="Ingresar CURP" value="<?php echo htmlspecialchars(@$x_Rec_Curp); ?>">
															</div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-3" style="background-color:#eaf2f8;">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Nombre</label>
                                                            <input type="text" class="form-control" id="x_Rec_Nombre" name="x_Rec_Nombre" placeholder="Ingresar el nombre" value="<?php echo @$x_Rec_Nombre; ?>">
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-lg-2" style="background-color:#eaf2f8;">
                                                        <div class="mb-3">
                                                            <label for="lastnameInput" class="form-label">Apellido Paterno</label>
                                                            <input type="text" class="form-control" id="x_Rec_Apellido_Paterno" name="x_Rec_Apellido_Paterno" placeholder="Ingresar apellido paterno" value="<?php echo @$x_Rec_Apellido_Paterno; ?>">
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-lg-2" style="background-color:#eaf2f8;">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label">Apellido Materno</label>
                                                            <input type="text" class="form-control" id="x_Rec_Apellido_Materno" name="x_Rec_Apellido_Materno" placeholder="Ingresar apellido materno" value="<?php echo @$x_Rec_Apellido_Materno; ?>">
                                                        </div>
                                                    </div>                                                    
													<div class="col-lg-2" style="background-color:#eaf2f8;">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Domicilio Fiscal</label>
                                                            <input type="text" class="form-control" id="x_Rec_DomicilioFiscaleceptor" name="x_Rec_DomicilioFiscaleceptor" placeholder="Ingresar el domicilio" value="<?php echo @$x_Rec_DomicilioFiscaleceptor; ?>">
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-6" style="background-color:#eaf2f8;">
                                                        <div class="mb-3">
                                                            <label for="lastnameInput" class="form-label">Residencia Fiscal</label>
                                                            <input type="text" class="form-control" id="x_Rec_ResidenciaFiscal" name="x_Rec_ResidenciaFiscal" placeholder="Ingresar Residencia Fiscal" value="<?php echo @$x_Rec_ResidenciaFiscal; ?>">
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#eaf2f8;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Clave Entidad Federativa</label>
                                                            <input type="text" class="form-control" id="x_Rec_ClaveEntFed" name="x_Rec_ClaveEntFed" value="<?php echo htmlspecialchars(@$x_Rec_ClaveEntFed); ?>">
															</div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#eaf2f8;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Municipio</label>
															<?php
															$x_Rec_TipoJornadaList = "<select name=\"x_Mun_ID\" class=\"form-select form-select\">";
															$x_Rec_TipoJornadaList .= "<option value=''>Favor de elegir</option>";
															$sSqlWrk = "SELECT DISTINCT `Mun_ID`, `Mun_Descrip` FROM `Vit_Municipios`";
															$sSqlWrk .= " ORDER BY `Mun_Descrip` Asc";
															#echo "<br />".$sSqlWrk;
															$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
															if ($rswrk) {
																$rowcntwrk = 0;
																while ($datawrk = phpmkr_fetch_array($rswrk)) {
																	$x_Rec_TipoJornadaList .= "<option value=\"" . htmlspecialchars($datawrk["Mun_ID"]) . "\"";
																	if ($datawrk["Mun_ID"] == @$x_Mun_ID) {
																		$x_Rec_TipoJornadaList .= "' selected";
																	}
																	$x_Rec_TipoJornadaList .= ">" . $datawrk["Mun_ID"] . " " . $datawrk["Mun_Descrip"] . "</option>";
																	$rowcntwrk++;
																}
															}
															@phpmkr_free_result($rswrk);
															$x_Rec_TipoJornadaList .= "</select>";
															echo $x_Rec_TipoJornadaList;
															?>
															</div>
                                                    </div>
                                                    <!--end col-->
                                                </div>
                                                <!--end row-->
                                            </form>
                                        </div>
                                        <!--end tab-pane-->
                                    </div>
                                </div>
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
<div class="card-header">
		<ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0"
			role="tablist">
			<li class="nav-item">
				<a class="nav-link active" data-bs-toggle="tab" href="#personalDetails"
					role="tab">
					<i class="fas fa-home"></i>
					<b>Informacion Constancia</b>
				</a>
			</li>
		</ul>
	</div>
	<div class="card-body p-4">
<?php if ($nTotalRecs > 0)  { ?>

	
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
		$x_const_ContanciaID = $row["const_ContanciaID"];
		$x_const_RFC = $row["const_RFC"];
		$x_const_CURP = $row["const_CURP"];
		$x_const_Nombres = $row["const_Nombres"];
		$x_const_Apellido1 = $row["const_Apellido1"];
		$x_const_Apellido2 = $row["const_Apellido2"];
		$x_const_InicioOperaciones = $row["const_InicioOperaciones"];
		$x_const_EstatusPadron = $row["const_EstatusPadron"];
		$x_const_UltimoCambio = $row["const_UltimoCambio"];
		$x_const_NombreComercial = $row["const_NombreComercial"];
		$x_const_CodigoPostal = $row["const_CodigoPostal"];
		$x_const_TipoVialidad = $row["const_TipoVialidad"];
		$x_const_NombreVialidad = $row["const_NombreVialidad"];
		$x_const_NumExterior = $row["const_NumExterior"];
		$x_const_NumInterior = $row["const_NumInterior"];
		$x_const_Colonia = $row["const_Colonia"];
		$x_const_Localidad = $row["const_Localidad"];
		$x_const_Municipio = $row["const_Municipio"];
		$x_const_Entidad = $row["const_Entidad"];
		$x_const_EntreCalle = $row["const_EntreCalle"];
		$x_const_YCalle = $row["const_YCalle"];
		$x_const_Email = $row["const_Email"];
		$x_const_TelefonoLada = $row["const_TelefonoLada"];
		$x_const_TelefonoNum = $row["const_TelefonoNum"];
		$x_const_EstadoDomicilio = $row["const_EstadoDomicilio"];
		$x_const_EstadoContribuyente = $row["const_EstadoContribuyente"];
		$x_const_Archivo = $row["const_Archivo"];
		$x_const_Ruta = $row["const_Ruta"];
?>	
	
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
											<div class="row">
                                                    <div class="col-lg-2" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">RFC</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_RFC; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-3" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">CURP</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_CURP; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-3" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Nombres</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_Nombres; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Apellido Paterno</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_Apellido1; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Apellido Materno</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_Apellido2; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Codigo Postal</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_CodigoPostal; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Tipo Vialidad</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_TipoVialidad; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Nombre Vialidad</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_NombreVialidad; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-1" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Num Exterior</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_NumExterior; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-1" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Num Interior</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_NumInterior; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Entidad</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_Entidad; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Municipio</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_Municipio; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->													
													
                                                </div>
                                                <!--end row-->
												
                                        <!--end tab-pane-->
                                    </div>
                                
<?php
	}
}
?>
							

<?php } ?>
									<div class="col-lg-12">
													<br />
                                                        <div class="hstack gap-2 justify-content-end">
															<a class="btn btn-primary waves-effect waves-light" href="empleados_listado.php">Cancelar</a>
															<input type="hidden" name="a_edit" value="U">
                                                        </div>
                                                    </div>
                                                    <!--end col-->
								</div>
								
								</div>								
<?php

// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
								
							</div>
							<!--end col-->

						</div>
										<!--end row-->
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
		$_SESSION["constancias_RecPerPage"] = $nDisplayRecs; // Save to Session

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["constancias_REC"] = $nStartRec;
	}else{
		if (@$_SESSION["constancias_RecPerPage"] <> "") {
			$nDisplayRecs = $_SESSION["constancias_RecPerPage"]; // Restore from Session
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
	global $x_const_RFC;

	// Get the keys for master table
	if (strlen(@$_GET["showmaster"]) > 0) {

		// Reset start record counter (new master key)
		$nStartRec = 1;
		$_SESSION["constancias_REC"] = $nStartRec;
		$sDbWhereMaster = "";
		$sDbWhereDetail = "";	
		$x_const_RFC = @$_GET["const_RFC"]; // Load Parameter from QueryString
		$sTmp = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_const_RFC) : $x_const_RFC;
		if ($sDbWhereMaster <> "") { $sDbWhereMaster .= " AND "; }
		$sDbWhereMaster .= "`Rec_RFC` =  '" . $sTmp . "'";
		if ($sDbWhereDetail <> "") { $sDbWhereDetail .= " AND "; }
		$sDbWhereDetail .= "`const_RFC` =  '" . $sTmp  . "'";
		$_SESSION["constancias_MasterKey_const_RFC"] = $sTmp; // Save Master Key Value
		$_SESSION["constancias_MasterWhere"] = $sDbWhereMaster;
		$_SESSION["constancias_DetailWhere"] = $sDbWhereDetail;
	}else{
		$sDbWhereMaster = @$_SESSION["constancias_MasterWhere"];
		$sDbWhereDetail = @$_SESSION["constancias_DetailWhere"];
	}
}

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`const_RFC` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_CURP` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Nombres` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Apellido1` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Apellido2` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_InicioOperaciones` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_EstatusPadron` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_UltimoCambio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_NombreComercial` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_CodigoPostal` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_TipoVialidad` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_NombreVialidad` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_NumExterior` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_NumInterior` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Colonia` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Localidad` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Municipio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Entidad` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_EntreCalle` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_YCalle` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Email` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_TelefonoLada` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_TelefonoNum` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_EstadoDomicilio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_EstadoContribuyente` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Archivo` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Ruta` LIKE '%" . $sKeyword . "%' OR ";
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

		// Field const_ContanciaID
		if ($sOrder == "const_ContanciaID") {
			$sSortField = "`const_ContanciaID`";
			$sLastSort = @$_SESSION["constancias_x_const_ContanciaID_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_ContanciaID_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_ContanciaID_Sort"] <> "") { @$_SESSION["constancias_x_const_ContanciaID_Sort"] = ""; }
		}

		// Field const_RFC
		if ($sOrder == "const_RFC") {
			$sSortField = "`const_RFC`";
			$sLastSort = @$_SESSION["constancias_x_const_RFC_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_RFC_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_RFC_Sort"] <> "") { @$_SESSION["constancias_x_const_RFC_Sort"] = ""; }
		}

		// Field const_CURP
		if ($sOrder == "const_CURP") {
			$sSortField = "`const_CURP`";
			$sLastSort = @$_SESSION["constancias_x_const_CURP_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_CURP_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_CURP_Sort"] <> "") { @$_SESSION["constancias_x_const_CURP_Sort"] = ""; }
		}

		// Field const_Nombres
		if ($sOrder == "const_Nombres") {
			$sSortField = "`const_Nombres`";
			$sLastSort = @$_SESSION["constancias_x_const_Nombres_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_Nombres_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_Nombres_Sort"] <> "") { @$_SESSION["constancias_x_const_Nombres_Sort"] = ""; }
		}

		// Field const_Apellido1
		if ($sOrder == "const_Apellido1") {
			$sSortField = "`const_Apellido1`";
			$sLastSort = @$_SESSION["constancias_x_const_Apellido1_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_Apellido1_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_Apellido1_Sort"] <> "") { @$_SESSION["constancias_x_const_Apellido1_Sort"] = ""; }
		}

		// Field const_Apellido2
		if ($sOrder == "const_Apellido2") {
			$sSortField = "`const_Apellido2`";
			$sLastSort = @$_SESSION["constancias_x_const_Apellido2_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_Apellido2_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_Apellido2_Sort"] <> "") { @$_SESSION["constancias_x_const_Apellido2_Sort"] = ""; }
		}

		// Field const_InicioOperaciones
		if ($sOrder == "const_InicioOperaciones") {
			$sSortField = "`const_InicioOperaciones`";
			$sLastSort = @$_SESSION["constancias_x_const_InicioOperaciones_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_InicioOperaciones_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_InicioOperaciones_Sort"] <> "") { @$_SESSION["constancias_x_const_InicioOperaciones_Sort"] = ""; }
		}

		// Field const_EstatusPadron
		if ($sOrder == "const_EstatusPadron") {
			$sSortField = "`const_EstatusPadron`";
			$sLastSort = @$_SESSION["constancias_x_const_EstatusPadron_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_EstatusPadron_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_EstatusPadron_Sort"] <> "") { @$_SESSION["constancias_x_const_EstatusPadron_Sort"] = ""; }
		}

		// Field const_UltimoCambio
		if ($sOrder == "const_UltimoCambio") {
			$sSortField = "`const_UltimoCambio`";
			$sLastSort = @$_SESSION["constancias_x_const_UltimoCambio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_UltimoCambio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_UltimoCambio_Sort"] <> "") { @$_SESSION["constancias_x_const_UltimoCambio_Sort"] = ""; }
		}

		// Field const_NombreComercial
		if ($sOrder == "const_NombreComercial") {
			$sSortField = "`const_NombreComercial`";
			$sLastSort = @$_SESSION["constancias_x_const_NombreComercial_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_NombreComercial_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_NombreComercial_Sort"] <> "") { @$_SESSION["constancias_x_const_NombreComercial_Sort"] = ""; }
		}

		// Field const_CodigoPostal
		if ($sOrder == "const_CodigoPostal") {
			$sSortField = "`const_CodigoPostal`";
			$sLastSort = @$_SESSION["constancias_x_const_CodigoPostal_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_CodigoPostal_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_CodigoPostal_Sort"] <> "") { @$_SESSION["constancias_x_const_CodigoPostal_Sort"] = ""; }
		}

		// Field const_TipoVialidad
		if ($sOrder == "const_TipoVialidad") {
			$sSortField = "`const_TipoVialidad`";
			$sLastSort = @$_SESSION["constancias_x_const_TipoVialidad_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_TipoVialidad_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_TipoVialidad_Sort"] <> "") { @$_SESSION["constancias_x_const_TipoVialidad_Sort"] = ""; }
		}

		// Field const_NombreVialidad
		if ($sOrder == "const_NombreVialidad") {
			$sSortField = "`const_NombreVialidad`";
			$sLastSort = @$_SESSION["constancias_x_const_NombreVialidad_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_NombreVialidad_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_NombreVialidad_Sort"] <> "") { @$_SESSION["constancias_x_const_NombreVialidad_Sort"] = ""; }
		}

		// Field const_NumExterior
		if ($sOrder == "const_NumExterior") {
			$sSortField = "`const_NumExterior`";
			$sLastSort = @$_SESSION["constancias_x_const_NumExterior_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_NumExterior_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_NumExterior_Sort"] <> "") { @$_SESSION["constancias_x_const_NumExterior_Sort"] = ""; }
		}

		// Field const_NumInterior
		if ($sOrder == "const_NumInterior") {
			$sSortField = "`const_NumInterior`";
			$sLastSort = @$_SESSION["constancias_x_const_NumInterior_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_NumInterior_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_NumInterior_Sort"] <> "") { @$_SESSION["constancias_x_const_NumInterior_Sort"] = ""; }
		}

		// Field const_Colonia
		if ($sOrder == "const_Colonia") {
			$sSortField = "`const_Colonia`";
			$sLastSort = @$_SESSION["constancias_x_const_Colonia_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_Colonia_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_Colonia_Sort"] <> "") { @$_SESSION["constancias_x_const_Colonia_Sort"] = ""; }
		}

		// Field const_Localidad
		if ($sOrder == "const_Localidad") {
			$sSortField = "`const_Localidad`";
			$sLastSort = @$_SESSION["constancias_x_const_Localidad_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_Localidad_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_Localidad_Sort"] <> "") { @$_SESSION["constancias_x_const_Localidad_Sort"] = ""; }
		}

		// Field const_Municipio
		if ($sOrder == "const_Municipio") {
			$sSortField = "`const_Municipio`";
			$sLastSort = @$_SESSION["constancias_x_const_Municipio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_Municipio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_Municipio_Sort"] <> "") { @$_SESSION["constancias_x_const_Municipio_Sort"] = ""; }
		}

		// Field const_Entidad
		if ($sOrder == "const_Entidad") {
			$sSortField = "`const_Entidad`";
			$sLastSort = @$_SESSION["constancias_x_const_Entidad_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_Entidad_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_Entidad_Sort"] <> "") { @$_SESSION["constancias_x_const_Entidad_Sort"] = ""; }
		}

		// Field const_EntreCalle
		if ($sOrder == "const_EntreCalle") {
			$sSortField = "`const_EntreCalle`";
			$sLastSort = @$_SESSION["constancias_x_const_EntreCalle_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_EntreCalle_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_EntreCalle_Sort"] <> "") { @$_SESSION["constancias_x_const_EntreCalle_Sort"] = ""; }
		}

		// Field const_YCalle
		if ($sOrder == "const_YCalle") {
			$sSortField = "`const_YCalle`";
			$sLastSort = @$_SESSION["constancias_x_const_YCalle_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_YCalle_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_YCalle_Sort"] <> "") { @$_SESSION["constancias_x_const_YCalle_Sort"] = ""; }
		}

		// Field const_Email
		if ($sOrder == "const_Email") {
			$sSortField = "`const_Email`";
			$sLastSort = @$_SESSION["constancias_x_const_Email_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_Email_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_Email_Sort"] <> "") { @$_SESSION["constancias_x_const_Email_Sort"] = ""; }
		}

		// Field const_TelefonoLada
		if ($sOrder == "const_TelefonoLada") {
			$sSortField = "`const_TelefonoLada`";
			$sLastSort = @$_SESSION["constancias_x_const_TelefonoLada_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_TelefonoLada_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_TelefonoLada_Sort"] <> "") { @$_SESSION["constancias_x_const_TelefonoLada_Sort"] = ""; }
		}

		// Field const_TelefonoNum
		if ($sOrder == "const_TelefonoNum") {
			$sSortField = "`const_TelefonoNum`";
			$sLastSort = @$_SESSION["constancias_x_const_TelefonoNum_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_TelefonoNum_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_TelefonoNum_Sort"] <> "") { @$_SESSION["constancias_x_const_TelefonoNum_Sort"] = ""; }
		}

		// Field const_EstadoDomicilio
		if ($sOrder == "const_EstadoDomicilio") {
			$sSortField = "`const_EstadoDomicilio`";
			$sLastSort = @$_SESSION["constancias_x_const_EstadoDomicilio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_EstadoDomicilio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_EstadoDomicilio_Sort"] <> "") { @$_SESSION["constancias_x_const_EstadoDomicilio_Sort"] = ""; }
		}

		// Field const_EstadoContribuyente
		if ($sOrder == "const_EstadoContribuyente") {
			$sSortField = "`const_EstadoContribuyente`";
			$sLastSort = @$_SESSION["constancias_x_const_EstadoContribuyente_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_EstadoContribuyente_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_EstadoContribuyente_Sort"] <> "") { @$_SESSION["constancias_x_const_EstadoContribuyente_Sort"] = ""; }
		}

		// Field const_Archivo
		if ($sOrder == "const_Archivo") {
			$sSortField = "`const_Archivo`";
			$sLastSort = @$_SESSION["constancias_x_const_Archivo_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_Archivo_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_Archivo_Sort"] <> "") { @$_SESSION["constancias_x_const_Archivo_Sort"] = ""; }
		}

		// Field const_Ruta
		if ($sOrder == "const_Ruta") {
			$sSortField = "`const_Ruta`";
			$sLastSort = @$_SESSION["constancias_x_const_Ruta_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_Ruta_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_Ruta_Sort"] <> "") { @$_SESSION["constancias_x_const_Ruta_Sort"] = ""; }
		}
		$_SESSION["constancias_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["constancias_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["constancias_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["constancias_OrderBy"] = $sOrderBy;
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
		$_SESSION["constancias_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["constancias_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["constancias_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["constancias_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["constancias_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["constancias_REC"] = $nStartRec;
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
			$_SESSION["constancias_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["constancias_searchwhere"] = $sSrchWhere;
			$_SESSION["constancias_MasterWhere"] = ""; // Clear master criteria
			$sDbWhereMaster = "";
			$_SESSION["constancias_DetailWhere"] = ""; // Clear detail criteria
			$sDbWhereDetail = "";
		$_SESSION["constancias_MasterKey_const_RFC"] = ""; // Clear Master Key Value

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["constancias_OrderBy"] = $sOrderBy;
			if (@$_SESSION["constancias_x_const_ContanciaID_Sort"] <> "") { $_SESSION["constancias_x_const_ContanciaID_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_RFC_Sort"] <> "") { $_SESSION["constancias_x_const_RFC_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_CURP_Sort"] <> "") { $_SESSION["constancias_x_const_CURP_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_Nombres_Sort"] <> "") { $_SESSION["constancias_x_const_Nombres_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_Apellido1_Sort"] <> "") { $_SESSION["constancias_x_const_Apellido1_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_Apellido2_Sort"] <> "") { $_SESSION["constancias_x_const_Apellido2_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_InicioOperaciones_Sort"] <> "") { $_SESSION["constancias_x_const_InicioOperaciones_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_EstatusPadron_Sort"] <> "") { $_SESSION["constancias_x_const_EstatusPadron_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_UltimoCambio_Sort"] <> "") { $_SESSION["constancias_x_const_UltimoCambio_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_NombreComercial_Sort"] <> "") { $_SESSION["constancias_x_const_NombreComercial_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_CodigoPostal_Sort"] <> "") { $_SESSION["constancias_x_const_CodigoPostal_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_TipoVialidad_Sort"] <> "") { $_SESSION["constancias_x_const_TipoVialidad_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_NombreVialidad_Sort"] <> "") { $_SESSION["constancias_x_const_NombreVialidad_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_NumExterior_Sort"] <> "") { $_SESSION["constancias_x_const_NumExterior_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_NumInterior_Sort"] <> "") { $_SESSION["constancias_x_const_NumInterior_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_Colonia_Sort"] <> "") { $_SESSION["constancias_x_const_Colonia_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_Localidad_Sort"] <> "") { $_SESSION["constancias_x_const_Localidad_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_Municipio_Sort"] <> "") { $_SESSION["constancias_x_const_Municipio_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_Entidad_Sort"] <> "") { $_SESSION["constancias_x_const_Entidad_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_EntreCalle_Sort"] <> "") { $_SESSION["constancias_x_const_EntreCalle_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_YCalle_Sort"] <> "") { $_SESSION["constancias_x_const_YCalle_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_Email_Sort"] <> "") { $_SESSION["constancias_x_const_Email_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_TelefonoLada_Sort"] <> "") { $_SESSION["constancias_x_const_TelefonoLada_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_TelefonoNum_Sort"] <> "") { $_SESSION["constancias_x_const_TelefonoNum_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_EstadoDomicilio_Sort"] <> "") { $_SESSION["constancias_x_const_EstadoDomicilio_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_EstadoContribuyente_Sort"] <> "") { $_SESSION["constancias_x_const_EstadoContribuyente_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_Archivo_Sort"] <> "") { $_SESSION["constancias_x_const_Archivo_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_Ruta_Sort"] <> "") { $_SESSION["constancias_x_const_Ruta_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["constancias_REC"] = $nStartRec;
	}
}
?>
