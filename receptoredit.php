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
$x_Rec_CreationDate = Null; 
$ox_Rec_CreationDate = Null;
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/db_cat_sat.php") ?>
<?php include ("libs/phpmkrfn.php") ?>
<?php

// Load key from QueryString
$x_Rec_RFC = @$_GET["Rec_RFC"];

//if (!empty($x_Rec_RFC )) $x_Rec_RFC  = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Rec_RFC ) : $x_Rec_RFC ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_Rec_RFC = @$_POST["x_Rec_RFC"];
	$x_Rec_Nombre = @$_POST["x_Rec_Nombre"];
	$x_Rec_Apellido_Paterno = @$_POST["x_Rec_Apellido_Paterno"];
	$x_Rec_Apellido_Materno = @$_POST["x_Rec_Apellido_Materno"];
	$x_Rec_DomicilioFiscaleceptor = @$_POST["x_Rec_DomicilioFiscaleceptor"];
	$x_Rec_ResidenciaFiscal = @$_POST["x_Rec_ResidenciaFiscal"];
	$x_Rec_NumRegIdTrib = @$_POST["x_Rec_NumRegIdTrib"];
	$x_Rec_RegimenFiscalReceptor = @$_POST["x_Rec_RegimenFiscalReceptor"];
	$x_Rec_Curp = @$_POST["x_Rec_Curp"];
	$x_Rec_NumSeguridadSocial = @$_POST["x_Rec_NumSeguridadSocial"];
	$x_Rec_FechaInicioRelLaboral = @$_POST["x_Rec_FechaInicioRelLaboral"];
	$x_Rec_Antiguedad = @$_POST["x_Rec_Antiguedad"];
	$x_Rec_TipoContrato = @$_POST["x_Rec_TipoContrato"];
	$x_Rec_Sindicalizado = @$_POST["x_Rec_Sindicalizado"];
	$x_Rec_TipoJornada = @$_POST["x_Rec_TipoJornada"];
	$x_Rec_TipoRegimen = @$_POST["x_Rec_TipoRegimen"];
	$x_Rec_NumEmpleado = @$_POST["x_Rec_NumEmpleado"];
	$x_Rec_Departamento = @$_POST["x_Rec_Departamento"];
	$x_Rec_Puesto = @$_POST["x_Rec_Puesto"];
	$x_Rec_RiesgoPuesto = @$_POST["x_Rec_RiesgoPuesto"];
	$x_Rec_PeriodicidadPago = @$_POST["x_Rec_PeriodicidadPago"];
	$x_Rec_Banco = @$_POST["x_Rec_Banco"];
	$x_Rec_CuentaBancaria = @$_POST["x_Rec_CuentaBancaria"];
	$x_Rec_SalarioBaseCotApor = @$_POST["x_Rec_SalarioBaseCotApor"];
	$x_Rec_SalarioDiarioIntegrado = @$_POST["x_Rec_SalarioDiarioIntegrado"];
	$x_Rec_ClaveEntFed = @$_POST["x_Rec_ClaveEntFed"];
	$x_Rec_Status = @$_POST["x_Rec_Status"];
	$x_Mun_ID = @$_POST["x_Mun_ID"];
	$x_Rec_CreationDate = @$_POST["x_Rec_CreationDate"];
}

// Check if valid key
if (($x_Rec_RFC == "") || (is_null($x_Rec_RFC))) {
	ob_end_clean();
	header("Location: empleados_listado.php");
	exit();
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$conn_sat = phpmkr_db_connect_sat(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No hay registro";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: empleados_listado.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Registro actualizado con exito.";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: empleados_listado.php");
			exit();
		}
		break;
}
?>


<script type="text/javascript" src="js/ew.js"></script>
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
	//alert("editando...");
if (EW_this.x_Rec_RFC && !EW_hasValue(EW_this.x_Rec_RFC, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_Rec_RFC, "TEXT", "Es necesario el RFC"))
		return false;
}
return true;
}

//-->
</script>

    <head>
        
        <title>Editar Empleado | VitaInsumos</title>
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
                                                <b>Informacion Empleado</b>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body p-4">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
										<form name="vit_receptoredit" id="vit_receptoredit" action="receptoredit.php" method="post" onSubmit="return EW_checkMyForm(this);">										
                                                <div class="row">
                                                    <div class="col-lg-4" style="background-color:#f2f4f4;">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Nombre</label>
                                                            <input type="text" class="form-control" id="x_Rec_Nombre" name="x_Rec_Nombre" placeholder="Ingresar el nombre" value="<?php echo @$x_Rec_Nombre; ?>">
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-lg-4" style="background-color:#f2f4f4;">
                                                        <div class="mb-3">
                                                            <label for="lastnameInput" class="form-label">Apellido Paterno</label>
                                                            <input type="text" class="form-control" id="x_Rec_Apellido_Paterno" name="x_Rec_Apellido_Paterno" placeholder="Ingresar apellido paterno" value="<?php echo @$x_Rec_Apellido_Paterno; ?>">
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-lg-4" style="background-color:#f2f4f4;">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label">Apellido Materno</label>
                                                            <input type="text" class="form-control" id="x_Rec_Apellido_Materno" name="x_Rec_Apellido_Materno" placeholder="Ingresar apellido materno" value="<?php echo @$x_Rec_Apellido_Materno; ?>">
                                                        </div>
                                                    </div>
                                                    <!--end col-->													
													<div class="col-lg-4" style="background-color:#f2f4f4;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Num Empleado</label>
                                                            <input type="text" class="form-control" id="x_Rec_NumEmpleado" name="x_Rec_NumEmpleado" value="<?php echo htmlspecialchars(@$x_Rec_NumEmpleado); ?>">
															</div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-4" style="background-color:#f2f4f4;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Departamento</label>
															<?php
															$x_Rec_TipoJornadaList = "<select name=\"x_Rec_Departamento\" class=\"form-select form-select\">";
															$x_Rec_TipoJornadaList .= "<option value=''>Favor de elegir</option>";
															$sSqlWrk = "SELECT
															Vit_Receptor.Rec_Departamento
															FROM
															Vit_Receptor
															WHERE Vit_Receptor.Rec_Departamento <> ''
															GROUP BY
															Vit_Receptor.Rec_Departamento
															ORDER BY Vit_Receptor.Rec_Departamento ASC";
															#echo "<br />".$sSqlWrk;
															$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
															if ($rswrk) {
																$rowcntwrk = 0;
																while ($datawrk = phpmkr_fetch_array($rswrk)) {
																	$x_Rec_TipoJornadaList .= "<option value=\"" . htmlspecialchars($datawrk["Rec_Departamento"]) . "\"";
																	if ($datawrk["Rec_Departamento"] == @$x_Rec_Departamento) {
																		$x_Rec_TipoJornadaList .= "' selected";
																	}
																	$x_Rec_TipoJornadaList .= ">" . $datawrk["Rec_Departamento"] . "</option>";
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
													<div class="col-lg-4" style="background-color:#f2f4f4;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Puesto</label>
															<?php
															$x_Rec_TipoJornadaList = "<select name=\"x_Rec_Puesto\" class=\"form-select form-select\">";
															$x_Rec_TipoJornadaList .= "<option value=''>Favor de elegir</option>";
															$sSqlWrk = "SELECT
															Vit_Receptor.Rec_Puesto
															FROM
															Vit_Receptor
															WHERE Vit_Receptor.Rec_Puesto <> ''
															GROUP BY
															Vit_Receptor.Rec_Puesto
															ORDER BY Vit_Receptor.Rec_Puesto ASC";
															#echo "<br />".$sSqlWrk;
															$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
															if ($rswrk) {
																$rowcntwrk = 0;
																while ($datawrk = phpmkr_fetch_array($rswrk)) {
																	$x_Rec_TipoJornadaList .= "<option value=\"" . htmlspecialchars($datawrk["Rec_Puesto"]) . "\"";
																	if ($datawrk["Rec_Puesto"] == @$x_Rec_Puesto) {
																		$x_Rec_TipoJornadaList .= "' selected";
																	}
																	$x_Rec_TipoJornadaList .= ">" . $datawrk["Rec_Puesto"] . "</option>";
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
													
													 <div class="col-lg-2" style="background-color:#eaf2f8;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">RFC</label>
                                                            <input type="text" class="form-control" id="x_Rec_RFC" name="x_Rec_RFC" value="<?php echo htmlspecialchars(@$x_Rec_RFC); ?>" readonly>
															</div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#eaf2f8;">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Domicilio Fiscal</label>
                                                            <input type="text" class="form-control" id="x_Rec_DomicilioFiscaleceptor" name="x_Rec_DomicilioFiscaleceptor" placeholder="Ingresar el domicilio" value="<?php echo @$x_Rec_DomicilioFiscaleceptor; ?>">
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-lg-4" style="background-color:#eaf2f8;">
                                                        <div class="mb-3">
                                                            <label for="lastnameInput" class="form-label">Residencia Fiscal</label>
                                                            <input type="text" class="form-control" id="x_Rec_ResidenciaFiscal" name="x_Rec_ResidenciaFiscal" placeholder="Ingresar Residencia Fiscal" value="<?php echo @$x_Rec_ResidenciaFiscal; ?>">
                                                        </div>
                                                    </div>
                                                    <!--end col-->                                                    
													<div class="col-lg-4" style="background-color:#eaf2f8;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Regimen Fiscal</label>
                                                            <!--<input type="text" class="form-control" id="x_Rec_TipoContrato" name="x_Rec_TipoContrato" value="<?php echo htmlspecialchars(@$x_Rec_RegimenFiscalReceptor); ?>">-->
															<?php
															$x_Rec_TipoJornadaList = "<select name=\"x_Rec_RegimenFiscalReceptor\" class=\"form-select form-select\">";
															$x_Rec_TipoJornadaList .= "<option value=''>Favor de elegir</option>";
															$sSqlWrk = "SELECT
															sat_catalogos.c_RegimenFiscal.c_RegimenFiscal,
															sat_catalogos.c_RegimenFiscal.Descripcion
															FROM
															sat_catalogos.c_RegimenFiscal";
															#echo "<br />".$sSqlWrk;
															$rswrk = phpmkr_query($sSqlWrk,$conn_sat) or die("Failed to execute query" . phpmkr_error($conn_sat) . ' SQL:' . $sSqlWrk);
															if ($rswrk) {
																$rowcntwrk = 0;
																while ($datawrk = phpmkr_fetch_array($rswrk)) {
																	$x_Rec_TipoJornadaList .= "<option value=\"" . htmlspecialchars($datawrk["c_RegimenFiscal"]) . "\"";
																	if ($datawrk["c_RegimenFiscal"] == @$x_Rec_RegimenFiscalReceptor) {
																		$x_Rec_TipoJornadaList .= "' selected";
																	}
																	$x_Rec_TipoJornadaList .= ">" . $datawrk["c_RegimenFiscal"] . " " . $datawrk["Descripcion"] . "</option>";
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
                                                   
													<div class="col-lg-2" style="background-color:#eaf2f8;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Curp</label>
                                                            <input type="text" class="form-control" id="x_Rec_Curp" name="x_Rec_Curp" placeholder="Ingresar CURP" value="<?php echo htmlspecialchars(@$x_Rec_Curp); ?>">
															</div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#eaf2f8;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Num Seguridad Social</label>
                                                            <input type="text" class="form-control" id="x_Rec_NumSeguridadSocial" name="x_Rec_NumSeguridadSocial" placeholder="Ingresar Seguridad Social" value="<?php echo htmlspecialchars(@$x_Rec_NumSeguridadSocial); ?>">
															</div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#eaf2f8;">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label">Num Reg</label>
                                                            <input type="text" class="form-control" id="x_Rec_NumRegIdTrib" name="x_Rec_NumRegIdTrib" placeholder="Ingresar Num Reg" value="<?php echo @$x_Rec_NumRegIdTrib; ?>">
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
													<div class="col-lg-2" style="background-color:#eaf2f8;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Sindicalizado</label>
															<br />
															<input type="radio" name="x_Rec_Sindicalizado"<?php if (@$x_Rec_Sindicalizado == "Sí") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("Sí"); ?>">
															<?php echo "Sí"; ?>
															<?php echo EditOptionSeparator(0); ?>
															<input type="radio" name="x_Rec_Sindicalizado"<?php if (@$x_Rec_Sindicalizado == "No") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("No"); ?>">
															<?php echo "No"; ?>
															<?php echo EditOptionSeparator(1); ?>
															</div>
                                                    </div>
                                                    <!--end col-->
													
													<div class="col-lg-2" style="background-color:#fdedec;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Fecha Inicio Rel Laboral</label>
															<input type="date" class="form-control" data-provider="flatpickr" name="x_Rec_FechaInicioRelLaboral" id="x_Rec_FechaInicioRelLaboral" value="<?php echo FormatDateTime(@$x_Rec_FechaInicioRelLaboral,5); ?>">
															</div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#fdedec;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Antiguedad</label>
                                                            <input type="text" class="form-control" id="x_Rec_Antiguedad" name="x_Rec_Antiguedad" value="<?php echo htmlspecialchars(@$x_Rec_Antiguedad); ?>">
															</div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#fdedec;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Tipo Contrato</label>
                                                            <!--<input type="text" class="form-control" id="x_Rec_TipoContrato" name="x_Rec_TipoContrato" value="<?php echo htmlspecialchars(@$x_Rec_TipoContrato); ?>">-->
															<?php
															$x_Rec_TipoJornadaList = "<select name=\"x_Rec_TipoContrato\" class=\"form-select form-select\">";
															$x_Rec_TipoJornadaList .= "<option value=''>Favor de elegir</option>";
															$sSqlWrk = "SELECT
															sat_catalogos.c_TipoContrato.c_TipoContrato,
															sat_catalogos.c_TipoContrato.Descripcion
															FROM
															sat_catalogos.c_TipoContrato";
															#echo "<br />".$sSqlWrk;
															$rswrk = phpmkr_query($sSqlWrk,$conn_sat) or die("Failed to execute query" . phpmkr_error($conn_sat) . ' SQL:' . $sSqlWrk);
															if ($rswrk) {
																$rowcntwrk = 0;
																while ($datawrk = phpmkr_fetch_array($rswrk)) {
																	$x_Rec_TipoJornadaList .= "<option value=\"" . htmlspecialchars($datawrk["c_TipoContrato"]) . "\"";
																	if ($datawrk["c_TipoContrato"] == @$x_Rec_TipoContrato) {
																		$x_Rec_TipoJornadaList .= "' selected";
																	}
																	$x_Rec_TipoJornadaList .= ">" . $datawrk["c_TipoContrato"] . " " . $datawrk["Descripcion"] . "</option>";
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
													
													<div class="col-lg-2" style="background-color:#fdedec;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Tipo Jornada</label>
															<?php
															$x_Rec_TipoJornadaList = "<select name=\"x_Rec_TipoJornada\" class=\"form-select form-select\">";
															$x_Rec_TipoJornadaList .= "<option value=''>Favor de elegir</option>";
															$sSqlWrk = "SELECT
															sat_catalogos.c_TipoJornada.c_TipoJornada,
															sat_catalogos.c_TipoJornada.Descripcion
															FROM
															sat_catalogos.c_TipoJornada";
															#echo "<br />".$sSqlWrk;
															$rswrk = phpmkr_query($sSqlWrk,$conn_sat) or die("Failed to execute query" . phpmkr_error($conn_sat) . ' SQL:' . $sSqlWrk);
															if ($rswrk) {
																$rowcntwrk = 0;
																while ($datawrk = phpmkr_fetch_array($rswrk)) {
																	$x_Rec_TipoJornadaList .= "<option value=\"" . htmlspecialchars($datawrk["c_TipoJornada"]) . "\"";
																	if ($datawrk["c_TipoJornada"] == @$x_Rec_TipoJornada) {
																		$x_Rec_TipoJornadaList .= "' selected";
																	}
																	$x_Rec_TipoJornadaList .= ">" . $datawrk["c_TipoJornada"] . " " . $datawrk["Descripcion"] . "</option>";
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
													<div class="col-lg-2" style="background-color:#fdedec;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Tipo Regimen</label>
															<?php
															$x_Rec_TipoJornadaList = "<select name=\"x_Rec_TipoRegimen\" class=\"form-select form-select\">";
															$x_Rec_TipoJornadaList .= "<option value=''>Favor de elegir</option>";
															$sSqlWrk = "SELECT
															sat_catalogos.c_TipoRegimen.c_TipoRegimen,
															sat_catalogos.c_TipoRegimen.Descripcion
															FROM
															sat_catalogos.c_TipoRegimen";
															#echo "<br />".$sSqlWrk;
															$rswrk = phpmkr_query($sSqlWrk,$conn_sat) or die("Failed to execute query" . phpmkr_error($conn_sat) . ' SQL:' . $sSqlWrk);
															if ($rswrk) {
																$rowcntwrk = 0;
																while ($datawrk = phpmkr_fetch_array($rswrk)) {
																	$x_Rec_TipoJornadaList .= "<option value=\"" . htmlspecialchars($datawrk["c_TipoRegimen"]) . "\"";
																	if ($datawrk["c_TipoRegimen"] == @$x_Rec_TipoRegimen) {
																		$x_Rec_TipoJornadaList .= "' selected";
																	}
																	$x_Rec_TipoJornadaList .= ">" . $datawrk["c_TipoRegimen"] . " " . $datawrk["Descripcion"] . "</option>";
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
													<div class="col-lg-2" style="background-color:#fdedec;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Riesgo Puesto</label>
															<?php
															$x_Rec_TipoJornadaList = "<select name=\"x_Rec_RiesgoPuesto\" class=\"form-select form-select\">";
															$x_Rec_TipoJornadaList .= "<option value=''>Favor de elegir</option>";
															$sSqlWrk = "SELECT
															sat_catalogos.c_RiesgoPuesto.c_RiesgoPuesto,
															sat_catalogos.c_RiesgoPuesto.Descripcion
															FROM
															sat_catalogos.c_RiesgoPuesto";
															#echo "<br />".$sSqlWrk;
															$rswrk = phpmkr_query($sSqlWrk,$conn_sat) or die("Failed to execute query" . phpmkr_error($conn_sat) . ' SQL:' . $sSqlWrk);
															if ($rswrk) {
																$rowcntwrk = 0;
																while ($datawrk = phpmkr_fetch_array($rswrk)) {
																	$x_Rec_TipoJornadaList .= "<option value=\"" . htmlspecialchars($datawrk["c_RiesgoPuesto"]) . "\"";
																	if ($datawrk["c_RiesgoPuesto"] == @$x_Rec_RiesgoPuesto) {
																		$x_Rec_TipoJornadaList .= "' selected";
																	}
																	$x_Rec_TipoJornadaList .= ">" . $datawrk["c_RiesgoPuesto"] . " " . $datawrk["Descripcion"] . "</option>";
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
													<div class="col-lg-2" style="background-color:#fdedec;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Periodicidad Pago</label>
															<?php
															$x_Rec_TipoJornadaList = "<select name=\"x_Rec_PeriodicidadPago\" class=\"form-select form-select\">";
															$x_Rec_TipoJornadaList .= "<option value=''>Favor de elegir</option>";
															$sSqlWrk = "SELECT
															sat_catalogos.c_PeriodicidadPago.c_PeriodicidadPago,
															sat_catalogos.c_PeriodicidadPago.Descripcion
															FROM
															sat_catalogos.c_PeriodicidadPago";
															#echo "<br />".$sSqlWrk;
															$rswrk = phpmkr_query($sSqlWrk,$conn_sat) or die("Failed to execute query" . phpmkr_error($conn_sat) . ' SQL:' . $sSqlWrk);
															if ($rswrk) {
																$rowcntwrk = 0;
																while ($datawrk = phpmkr_fetch_array($rswrk)) {
																	$x_Rec_TipoJornadaList .= "<option value=\"" . htmlspecialchars($datawrk["c_PeriodicidadPago"]) . "\"";
																	if ($datawrk["c_PeriodicidadPago"] == @$x_Rec_PeriodicidadPago) {
																		$x_Rec_TipoJornadaList .= "' selected";
																	}
																	$x_Rec_TipoJornadaList .= ">" . $datawrk["c_PeriodicidadPago"] . " " . $datawrk["Descripcion"] . "</option>";
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
													<div class="col-lg-2" style="background-color:#fdedec;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Banco</label>
															<?php
															$x_Rec_TipoJornadaList = "<select name=\"x_Rec_Banco\" class=\"form-select form-select\">";
															$x_Rec_TipoJornadaList .= "<option value=''>Favor de elegir</option>";
															$sSqlWrk = "SELECT
															sat_catalogos.c_Banco.c_Banco,
															sat_catalogos.c_Banco.Descripcion
															FROM
															sat_catalogos.c_Banco";
															#echo "<br />".$sSqlWrk;
															$rswrk = phpmkr_query($sSqlWrk,$conn_sat) or die("Failed to execute query" . phpmkr_error($conn_sat) . ' SQL:' . $sSqlWrk);
															if ($rswrk) {
																$rowcntwrk = 0;
																while ($datawrk = phpmkr_fetch_array($rswrk)) {
																	$x_Rec_TipoJornadaList .= "<option value=\"" . htmlspecialchars($datawrk["c_Banco"]) . "\"";
																	if ($datawrk["c_Banco"] == @$x_Rec_Banco) {
																		$x_Rec_TipoJornadaList .= "' selected";
																	}
																	$x_Rec_TipoJornadaList .= ">" . $datawrk["c_Banco"] . " " . $datawrk["Descripcion"] . "</option>";
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
													<div class="col-lg-2" style="background-color:#fdedec;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Cuenta Bancaria</label>
                                                            <input type="text" class="form-control" id="x_Rec_CuentaBancaria" name="x_Rec_CuentaBancaria" value="<?php echo htmlspecialchars(@$x_Rec_CuentaBancaria); ?>">
															</div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#fdedec;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Salario Base</label>
                                                            <input type="text" class="form-control" id="x_Rec_SalarioBaseCotApor" name="x_Rec_SalarioBaseCotApor" value="<?php echo htmlspecialchars(@$x_Rec_SalarioBaseCotApor); ?>">
															</div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#fdedec;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Salario Diario</label>
                                                            <input type="text" class="form-control" id="x_Rec_SalarioDiarioIntegrado" name="x_Rec_SalarioDiarioIntegrado" value="<?php echo htmlspecialchars(@$x_Rec_SalarioDiarioIntegrado); ?>">
															</div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#fdedec;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Status</label>
															<br />
															<input type="radio" name="x_Rec_Status"<?php if (@$x_Rec_Status == "0") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("0"); ?>">
															<?php echo "Inactivo"; ?>
															<?php echo EditOptionSeparator(0); ?>
															<input type="radio" name="x_Rec_Status"<?php if (@$x_Rec_Status == "1") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("1"); ?>">
															<?php echo "Activo"; ?>
															<?php echo EditOptionSeparator(1); ?>
															</div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-lg-12">
													<br />
                                                        <div class="hstack gap-2 justify-content-end">															
															<a class="btn btn-primary waves-effect waves-light" href="empleados_listado.php">Cancelar</a>
															<button type="submit" name="Action" class="btn btn-success waves-effect waves-light" value="EDIT">Actualizar</button>
															<input type="hidden" name="a_edit" value="U">
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
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->

				</div>
                <!-- container-fluid -->
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
phpmkr_db_close($conn);
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($conn)
{
	global $x_Rec_RFC;
	$sSql = "SELECT * FROM `Vit_Receptor`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Rec_RFC) : $x_Rec_RFC;
	$sWhere .= "(`Rec_RFC` = '" . addslashes($sTmp) . "')";
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
		$GLOBALS["x_Rec_RFC"] = $row["Rec_RFC"];
		$GLOBALS["x_Rec_Nombre"] = $row["Rec_Nombre"];
		$GLOBALS["x_Rec_Apellido_Paterno"] = $row["Rec_Apellido_Paterno"];
		$GLOBALS["x_Rec_Apellido_Materno"] = $row["Rec_Apellido_Materno"];
		$GLOBALS["x_Rec_DomicilioFiscaleceptor"] = $row["Rec_DomicilioFiscaleceptor"];
		$GLOBALS["x_Rec_ResidenciaFiscal"] = $row["Rec_ResidenciaFiscal"];
		$GLOBALS["x_Rec_NumRegIdTrib"] = $row["Rec_NumRegIdTrib"];
		$GLOBALS["x_Rec_RegimenFiscalReceptor"] = $row["Rec_RegimenFiscalReceptor"];
		$GLOBALS["x_Rec_Curp"] = $row["Rec_Curp"];
		$GLOBALS["x_Rec_NumSeguridadSocial"] = $row["Rec_NumSeguridadSocial"];
		$GLOBALS["x_Rec_FechaInicioRelLaboral"] = $row["Rec_FechaInicioRelLaboral"];
		$GLOBALS["x_Rec_Antiguedad"] = $row["Rec_Antiguedad"];
		$GLOBALS["x_Rec_TipoContrato"] = $row["Rec_TipoContrato"];
		$GLOBALS["x_Rec_Sindicalizado"] = $row["Rec_Sindicalizado"];
		$GLOBALS["x_Rec_TipoJornada"] = $row["Rec_TipoJornada"];
		$GLOBALS["x_Rec_TipoRegimen"] = $row["Rec_TipoRegimen"];
		$GLOBALS["x_Rec_NumEmpleado"] = $row["Rec_NumEmpleado"];
		$GLOBALS["x_Rec_Departamento"] = $row["Rec_Departamento"];
		$GLOBALS["x_Rec_Puesto"] = $row["Rec_Puesto"];
		$GLOBALS["x_Rec_RiesgoPuesto"] = $row["Rec_RiesgoPuesto"];
		$GLOBALS["x_Rec_PeriodicidadPago"] = $row["Rec_PeriodicidadPago"];
		$GLOBALS["x_Rec_Banco"] = $row["Rec_Banco"];
		$GLOBALS["x_Rec_CuentaBancaria"] = $row["Rec_CuentaBancaria"];
		$GLOBALS["x_Rec_SalarioBaseCotApor"] = $row["Rec_SalarioBaseCotApor"];
		$GLOBALS["x_Rec_SalarioDiarioIntegrado"] = $row["Rec_SalarioDiarioIntegrado"];
		$GLOBALS["x_Rec_ClaveEntFed"] = $row["Rec_ClaveEntFed"];
		$GLOBALS["x_Rec_Status"] = $row["Rec_Status"];
		$GLOBALS["x_Mun_ID"] = $row["Mun_ID"];
		$GLOBALS["x_Rec_CreationDate"] = $row["Rec_CreationDate"];
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

function EditData($conn)
{
	global $x_Rec_RFC;
	$sSql = "SELECT * FROM `Vit_Receptor`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Rec_RFC) : $x_Rec_RFC;	
	$sWhere .= "(`Rec_RFC` = '" . addslashes($sTmp) . "')";
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
	#echo "<br />EDIT..sSql: ".$sSql;
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$bEditData = false; // Update Failed
	}else{
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_RFC"]) : $GLOBALS["x_Rec_RFC"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_RFC`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_Nombre"]) : $GLOBALS["x_Rec_Nombre"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_Nombre`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_Apellido_Paterno"]) : $GLOBALS["x_Rec_Apellido_Paterno"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_Apellido_Paterno`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_Apellido_Materno"]) : $GLOBALS["x_Rec_Apellido_Materno"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_Apellido_Materno`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_DomicilioFiscaleceptor"]) : $GLOBALS["x_Rec_DomicilioFiscaleceptor"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_DomicilioFiscaleceptor`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_ResidenciaFiscal"]) : $GLOBALS["x_Rec_ResidenciaFiscal"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_ResidenciaFiscal`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_NumRegIdTrib"]) : $GLOBALS["x_Rec_NumRegIdTrib"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_NumRegIdTrib`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_RegimenFiscalReceptor"]) : $GLOBALS["x_Rec_RegimenFiscalReceptor"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_RegimenFiscalReceptor`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_Curp"]) : $GLOBALS["x_Rec_Curp"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_Curp`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_NumSeguridadSocial"]) : $GLOBALS["x_Rec_NumSeguridadSocial"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_NumSeguridadSocial`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_FechaInicioRelLaboral"]) : $GLOBALS["x_Rec_FechaInicioRelLaboral"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_FechaInicioRelLaboral`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_Antiguedad"]) : $GLOBALS["x_Rec_Antiguedad"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_Antiguedad`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_TipoContrato"]) : $GLOBALS["x_Rec_TipoContrato"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_TipoContrato`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_Sindicalizado"]) : $GLOBALS["x_Rec_Sindicalizado"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_Sindicalizado`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_TipoJornada"]) : $GLOBALS["x_Rec_TipoJornada"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_TipoJornada`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_TipoRegimen"]) : $GLOBALS["x_Rec_TipoRegimen"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_TipoRegimen`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_NumEmpleado"]) : $GLOBALS["x_Rec_NumEmpleado"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_NumEmpleado`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_Departamento"]) : $GLOBALS["x_Rec_Departamento"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_Departamento`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_Puesto"]) : $GLOBALS["x_Rec_Puesto"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_Puesto`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_RiesgoPuesto"]) : $GLOBALS["x_Rec_RiesgoPuesto"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_RiesgoPuesto`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_PeriodicidadPago"]) : $GLOBALS["x_Rec_PeriodicidadPago"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_PeriodicidadPago`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_Banco"]) : $GLOBALS["x_Rec_Banco"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_Banco`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_CuentaBancaria"]) : $GLOBALS["x_Rec_CuentaBancaria"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_CuentaBancaria`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_SalarioBaseCotApor"]) : $GLOBALS["x_Rec_SalarioBaseCotApor"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_SalarioBaseCotApor`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_SalarioDiarioIntegrado"]) : $GLOBALS["x_Rec_SalarioDiarioIntegrado"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_SalarioDiarioIntegrado`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_ClaveEntFed"]) : $GLOBALS["x_Rec_ClaveEntFed"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_ClaveEntFed`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Rec_Status"]) : $GLOBALS["x_Rec_Status"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Rec_Status`"] = $theValue;
		$theValue = ($GLOBALS["x_Mun_ID"] != "") ? intval($GLOBALS["x_Mun_ID"]) : "NULL";
		$fieldList["`Mun_ID`"] = $theValue;
		$theValue = ($GLOBALS["x_Rec_CreationDate"] != "") ? " '" . ConvertDateToMysqlFormat($GLOBALS["x_Rec_CreationDate"]) . "'" : "NULL";
		$fieldList["`Rec_CreationDate`"] = $theValue;

		// update
		$sSql = "UPDATE `Vit_Receptor` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
		$bEditData = true; // Update Successful
	}
	return $bEditData;
}
?>
