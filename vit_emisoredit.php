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
<?php include ("libs/db.php") ?>
<?php include ("libs/db_cat_sat.php") ?>
<?php include ("libs/phpmkrfn.php") ?>
<?php

// Load key from QueryString
$x_Emi_RFC = @$_GET["Emi_RFC"];

//if (!empty($x_Emi_RFC )) $x_Emi_RFC  = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Emi_RFC ) : $x_Emi_RFC ;
$x_Mun_ID = @$_GET["Mun_ID"];

//if (!empty($x_Mun_ID )) $x_Mun_ID  = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Mun_ID ) : $x_Mun_ID ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_Emi_RFC = @$_POST["x_Emi_RFC"];
	$x_Emi_Nombre = @$_POST["x_Emi_Nombre"];
	$x_Emi_RegimenFiscal = @$_POST["x_Emi_RegimenFiscal"];
	$x_Emi_Clave = @$_POST["x_Emi_Clave"];
	$x_Emi_FacAtrAdquirente = @$_POST["x_Emi_FacAtrAdquirente"];
	$x_Emi_Curp = @$_POST["x_Emi_Curp"];
	$x_Emi_RegistroPatronal = @$_POST["x_Emi_RegistroPatronal"];
	$x_Emi_RfcPatronOrigen = @$_POST["x_Emi_RfcPatronOrigen"];
	$x_Emi_EntidadSNCF = @$_POST["x_Emi_EntidadSNCF"];
	$x_Mun_ID = @$_POST["x_Mun_ID"];
	$x_Emi_NomCorto = @$_POST["x_Emi_NomCorto"];
	$x_Emi_ArchivoKey = @$_POST["x_Emi_ArchivoKey"];
	$x_Emi_ArchivoCer = @$_POST["x_Emi_ArchivoCer"];
	$x_Emi_ArchivoPas = @$_POST["x_Emi_ArchivoPas"];
	$x_Emi_CertificadoKey = @$_POST["x_Emi_CertificadoKey"];
	$x_Emi_CertificadoCer = @$_POST["x_Emi_CertificadoCer"];
	$x_Emi_CertificadoPas = @$_POST["x_Emi_CertificadoPas"];
	$x_Emi_Constancia = @$_POST["x_Emi_Constancia"];
}

// Check if valid key
if (($x_Emi_RFC == "") || (is_null($x_Emi_RFC))) {
	ob_end_clean();
	header("Location: emisores_listado.php");
	exit();
}
if (($x_Mun_ID == "") || (is_null($x_Mun_ID))) {
	ob_end_clean();
	header("Location: emisores_listado.php");
	exit();
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
$conn_sat = phpmkr_db_connect_sat(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$msg = "warning|No hay registro.|3000";
			$_SESSION["ewmsg"] = $msg;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: emisores_listado.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$msg = "success|Registro actualizado con exito.|3000";
			$_SESSION["ewmsg"] = $msg;
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: emisores_listado.php");
			exit();
		}
		break;
}
?>
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
<head>
        
        <title>Editar Receptor | VitaInsumos</title>
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
                                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                                <i class="fas fa-home"></i>
                                                <b>Informacion Emisor</b>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body p-4">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
										<form name="vit_emisoredit" id="vit_emisoredit" action="vit_emisoredit.php" method="post" enctype="multipart/form-data" onSubmit="return EW_checkMyForm(this);">
                                                <div class="row">
                                                    <div class="col-lg-3" style="background-color:#f2f4f4;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">RFC</label>
                                                            <input type="text" class="form-control" id="x_Emi_RFC" name="x_Emi_RFC" value="<?php echo htmlspecialchars(@$x_Emi_RFC); ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-lg-6" style="background-color:#f2f4f4;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="lastnameInput" class="form-label">Nombre</label>
                                                            <input class="form-control" type="text" id="x_Emi_Nombre" name="x_Emi_Nombre" size="100" maxlength="100" value="<?php echo @$x_Emi_Nombre; ?>">
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													 <div class="col-lg-3" style="background-color:#f2f4f4;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Nombre Corto</label>
                                                            <input type="text" class="form-control" id="x_Emi_NomCorto" name="x_Emi_NomCorto" value="<?php echo htmlspecialchars(@$x_Emi_NomCorto); ?>">
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-lg-4" style="background-color:#f2f4f4;">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label">Regimen Fiscal</label>
															<?php
															$x_Rec_TipoJornadaList = "<select name=\"x_Emi_RegimenFiscal\" class=\"form-select form-select\">";
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
																	if ($datawrk["c_RegimenFiscal"] == @$x_Emi_RegimenFiscal) {
																		$x_Rec_TipoJornadaList .= "' selected";
																	}
																	$x_Rec_TipoJornadaList .= ">" . $datawrk["c_RegimenFiscal"] . " - " . $datawrk["Descripcion"] . "</option>";
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
                                                            <label for="emailInput" class="form-label">Clave</label>
                                                            <input class="form-control" type="text" name="x_Emi_Clave" id="x_Emi_Clave" size="30" maxlength="30" value="<?php echo htmlspecialchars(@$x_Emi_Clave) ?>">
															</div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-4" style="background-color:#f2f4f4;">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Fac Atr Adquirente</label>
															<input class="form-control" type="text" name="x_Emi_FacAtrAdquirente" id="x_Emi_FacAtrAdquirente" size="30" maxlength="30" value="<?php echo htmlspecialchars(@$x_Emi_FacAtrAdquirente) ?>">
															</div>
                                                    </div>
                                                    <!--end col-->													
													<div class="col-lg-3" style="background-color:#eaf2f8;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Curp</label>
                                                            <input class="form-control" type="text" name="x_Emi_Curp" id="x_Emi_Curp" size="30" maxlength="54" value="<?php echo htmlspecialchars(@$x_Emi_Curp) ?>">
															</div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-3" style="background-color:#eaf2f8;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Registro Patronal</label>
                                                            <input class="form-control" type="text" name="x_Emi_RegistroPatronal" id="x_Emi_RegistroPatronal" size="30" maxlength="60" value="<?php echo htmlspecialchars(@$x_Emi_RegistroPatronal) ?>">
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-lg-3" style="background-color:#eaf2f8;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="lastnameInput" class="form-label">Rfc Patron Origen</label>
                                                            <input class="form-control"  type="text" name="x_Emi_RfcPatronOrigen" id="x_Emi_RfcPatronOrigen" size="30" maxlength="39" value="<?php echo htmlspecialchars(@$x_Emi_RfcPatronOrigen) ?>">
                                                        </div>
                                                    </div>
                                                    <!--end col-->                                                    
													<div class="col-lg-3" style="background-color:#eaf2f8;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">Entidad SNCF</label>
															<br />
															<input type="radio" name="x_Emi_EntidadSNCF"<?php if (@$x_Emi_EntidadSNCF == "1") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("1"); ?>">
															<?php echo "Sí"; ?>
															<?php echo EditOptionSeparator(0); ?>
															<input type="radio" name="x_Emi_EntidadSNCF"<?php if (@$x_Emi_EntidadSNCF == "0") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("0"); ?>">
															<?php echo "No"; ?>
															<?php echo EditOptionSeparator(1); ?>
															</div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#fdedec;">
														<br />
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
													<div class="col-lg-4" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">
															FIEL SAT - Archivo Key 
															<?php if ((!is_null($x_Emi_ArchivoKey)) && $x_Emi_ArchivoKey <> "") {  ?>
															[ <b><?php echo (@$x_Emi_ArchivoKey) ?></b> ]
															<?php } ?>
															</label>
															<br />
															<?php if ((!is_null($x_Emi_ArchivoKey)) && $x_Emi_ArchivoKey <> "") {  ?>
															<input type="hidden" name="a_x_Emi_ArchivoKey" value="3">
															<?php } else {?>
															<input type="hidden" name="a_x_Emi_ArchivoKey" value="3">
															<?php } ?>
															<input class="form-control" type="file" id="x_Emi_ArchivoKey" name="x_Emi_ArchivoKey" size="30" onChange="if (this.form.a_x_Emi_ArchivoKey[2]) this.form.a_x_Emi_ArchivoKey[2].checked=true;">
															</div>
                                                    </div>
													<!--end col-->
													<div class="col-lg-4" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">
															FIEL SAT - Archivo Cer
															<?php if ((!is_null($x_Emi_ArchivoCer)) && $x_Emi_ArchivoCer <> "") {  ?>
															[ <b><?php echo (@$x_Emi_ArchivoCer) ?></b> ]
															<?php } ?>
															</label>
															<br />
															<?php if ((!is_null($x_Emi_ArchivoCer)) && $x_Emi_ArchivoCer <> "") {  ?>
															<input type="hidden" name="a_x_Emi_ArchivoCer" value="3">
															<?php } else {?>
															<input type="hidden" name="a_x_Emi_ArchivoCer" value="3">
															<?php } ?>
															<input class="form-control" type="file" id="x_Emi_ArchivoCer" name="x_Emi_ArchivoCer" size="30" onChange="if (this.form.a_x_Emi_ArchivoCer[2]) this.form.a_x_Emi_ArchivoCer[2].checked=true;">
															</div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">FIEL SAT - Password</label>
                                                            <input class="form-control" type="password" name="x_Emi_ArchivoPas" id="x_Emi_ArchivoPas" size="30" maxlength="20" value="<?php echo htmlspecialchars(@$x_Emi_ArchivoPas) ?>">
															</div>
                                                    </div>
                                                    <!--end col-->
													<!--end col-->
													<div class="col-lg-2" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">
															Constancia
															<?php if ((!is_null($x_Emi_Constancia)) && $x_Emi_Constancia <> "") {  ?>
															[ <b><?php echo (@$x_Emi_Constancia) ?></b> ]
															<?php } ?>
															</label>
															<br />
															<?php if ((!is_null($x_Emi_Constancia)) && $x_Emi_Constancia <> "") {  ?>
															<input type="hidden" name="a_x_Emi_Constancia" value="3">
															<?php } else {?>
															<input type="hidden" name="a_x_Emi_Constancia" value="3">
															<?php } ?>
															<input class="form-control" type="file" id="x_Emi_Constancia" name="x_Emi_Constancia" size="30" onChange="if (this.form.a_x_Emi_Constancia[2]) this.form.a_x_Emi_Constancia[2].checked=true;">                                                            
															</div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-4" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">
															CSD SAT - Archivo Key 
															<?php if ((!is_null($x_Emi_CertificadoKey)) && $x_Emi_CertificadoKey <> "") {  ?>
															[ <b><?php echo (@$x_Emi_CertificadoKey) ?></b> ]
															<?php } ?>
															</label>
															<br />
															<?php if ((!is_null($x_Emi_CertificadoKey)) && $x_Emi_CertificadoKey <> "") {  ?>
															<input type="hidden" name="a_x_Emi_CertificadoKey" value="3">
															<?php } else {?>
															<input type="hidden" name="a_x_Emi_CertificadoKey" value="3">
															<?php } ?>
															<input class="form-control" type="file" id="x_Emi_CertificadoKey" name="x_Emi_CertificadoKey" size="30" onChange="if (this.form.a_x_Emi_CertificadoKey[2]) this.form.a_x_Emi_CertificadoKey[2].checked=true;">
															</div>
                                                    </div>
													<!--end col-->
													<div class="col-lg-4" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">
															CSD SAT - Archivo Cer
															<?php if ((!is_null($x_Emi_CertificadoCer)) && $x_Emi_CertificadoCer <> "") {  ?>
															[ <b><?php echo (@$x_Emi_CertificadoCer) ?></b> ]
															<?php } ?>
															</label>
															<br />
															<?php if ((!is_null($x_Emi_CertificadoCer)) && $x_Emi_CertificadoCer <> "") {  ?>
															<input type="hidden" name="a_x_Emi_CertificadoCer" value="3">
															<?php } else {?>
															<input type="hidden" name="a_x_Emi_CertificadoCer" value="3">
															<?php } ?>
															<input class="form-control" type="file" id="x_Emi_CertificadoCer" name="x_Emi_CertificadoCer" size="30" onChange="if (this.form.a_x_Emi_CertificadoCer[2]) this.form.a_x_Emi_CertificadoCer[2].checked=true;">
															</div>
                                                    </div>
                                                    
													<div class="col-lg-2" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label">CSD SAT - Password</label>
                                                            <input class="form-control" type="password" name="x_Emi_CertificadoPas" id="x_Emi_CertificadoPas" size="30" maxlength="20" value="<?php echo htmlspecialchars(@$x_Emi_CertificadoPas) ?>">
															</div>
                                                    </div>
                                                    <!--end col-->
                                                    <div class="col-lg-12">
													<br />
                                                        <div class="hstack gap-2 justify-content-end">
															<a class="btn btn-primary waves-effect waves-light" href="emisores_listado.php">Cancelar</a>
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
	global $x_Emi_RFC;
	global $x_Mun_ID;
	$sSql = "SELECT * FROM `Vit_Emisor`";
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

function EditData($conn)
{
	global $x_Emi_RFC;
	global $x_Mun_ID;
	$sSql = "SELECT * FROM `Vit_Emisor`";
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
		$bEditData = false; // Update Failed
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
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_RegimenFiscal"]) : $GLOBALS["x_Emi_RegimenFiscal"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_RegimenFiscal`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_Clave"]) : $GLOBALS["x_Emi_Clave"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_Clave`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_FacAtrAdquirente"]) : $GLOBALS["x_Emi_FacAtrAdquirente"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_FacAtrAdquirente`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_Curp"]) : $GLOBALS["x_Emi_Curp"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_Curp`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_RegistroPatronal"]) : $GLOBALS["x_Emi_RegistroPatronal"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_RegistroPatronal`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_RfcPatronOrigen"]) : $GLOBALS["x_Emi_RfcPatronOrigen"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_RfcPatronOrigen`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_EntidadSNCF"]) : $GLOBALS["x_Emi_EntidadSNCF"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_EntidadSNCF`"] = $theValue;
		$theValue = ($GLOBALS["x_Mun_ID"] != "") ? intval($GLOBALS["x_Mun_ID"]) : "NULL";
		$fieldList["`Mun_ID`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_NomCorto"]) : $GLOBALS["x_Emi_NomCorto"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_NomCorto`"] = $theValue;
		if ($a_x_Emi_ArchivoKey == "2") { // Remove
			$fieldList["`Emi_ArchivoKey`"] = "NULL";
		} else if ($a_x_Emi_ArchivoKey == "3") { // Update
			if (is_uploaded_file($_FILES["x_Emi_ArchivoKey"]["tmp_name"])) {
				$destfile = ewUploadPathVita(0) . ewUploadFileName($_FILES["x_Emi_ArchivoKey"]["name"]);
						if (!move_uploaded_file($_FILES["x_Emi_ArchivoKey"]["tmp_name"], $destfile)) // move file to destination path
						die("You didn't upload a file or the file couldn't be moved to" . $destfile);

				// File Name
				$theName = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes(ewUploadFileName($_FILES["x_Emi_ArchivoKey"]["name"])) : ewUploadFileName($_FILES["x_Emi_ArchivoKey"]["name"]);
				$fieldList["`Emi_ArchivoKey`"] = " '" . $theName . "'";
				@unlink($_FILES["x_Emi_ArchivoKey"]["tmp_name"]);
			}
		}
		if ($a_x_Emi_ArchivoCer == "2") { // Remove
			$fieldList["`Emi_ArchivoCer`"] = "NULL";
		} else if ($a_x_Emi_ArchivoCer == "3") { // Update
			if (is_uploaded_file($_FILES["x_Emi_ArchivoCer"]["tmp_name"])) {
				$destfile = ewUploadPathVita(0) . ewUploadFileName($_FILES["x_Emi_ArchivoCer"]["name"]);
						if (!move_uploaded_file($_FILES["x_Emi_ArchivoCer"]["tmp_name"], $destfile)) // move file to destination path
						die("You didn't upload a file or the file couldn't be moved to" . $destfile);

				// File Name
				$theName = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes(ewUploadFileName($_FILES["x_Emi_ArchivoCer"]["name"])) : ewUploadFileName($_FILES["x_Emi_ArchivoCer"]["name"]);
				$fieldList["`Emi_ArchivoCer`"] = " '" . $theName . "'";
				@unlink($_FILES["x_Emi_ArchivoCer"]["tmp_name"]);
			}
		}
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_ArchivoPas"]) : $GLOBALS["x_Emi_ArchivoPas"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_ArchivoPas`"] = $theValue;
		
		if ($a_x_Emi_CertificadoKey == "2") { // Remove
			$fieldList["`Emi_CertificadoKey`"] = "NULL";
		} else if ($a_x_Emi_CertificadoKey == "3") { // Update
			if (is_uploaded_file($_FILES["x_Emi_CertificadoKey"]["tmp_name"])) {
				$destfile = ewUploadPathVita(0) . ewUploadFileName($_FILES["x_Emi_CertificadoKey"]["name"]);
						if (!move_uploaded_file($_FILES["x_Emi_CertificadoKey"]["tmp_name"], $destfile)) // move file to destination path
						die("You didn't upload a file or the file couldn't be moved to" . $destfile);

				// File Name
				$theName = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes(ewUploadFileName($_FILES["x_Emi_CertificadoKey"]["name"])) : ewUploadFileName($_FILES["x_Emi_CertificadoKey"]["name"]);
				$fieldList["`Emi_CertificadoKey`"] = " '" . $theName . "'";
				@unlink($_FILES["x_Emi_CertificadoKey"]["tmp_name"]);
			}
		}
		if ($a_x_Emi_CertificadoCer == "2") { // Remove
			$fieldList["`Emi_CertificadoCer`"] = "NULL";
		} else if ($a_x_Emi_CertificadoCer == "3") { // Update
			if (is_uploaded_file($_FILES["x_Emi_CertificadoCer"]["tmp_name"])) {
				$destfile = ewUploadPathVita(0) . ewUploadFileName($_FILES["x_Emi_CertificadoCer"]["name"]);
						if (!move_uploaded_file($_FILES["x_Emi_CertificadoCer"]["tmp_name"], $destfile)) // move file to destination path
						die("You didn't upload a file or the file couldn't be moved to" . $destfile);

				// File Name
				$theName = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes(ewUploadFileName($_FILES["x_Emi_CertificadoCer"]["name"])) : ewUploadFileName($_FILES["x_Emi_CertificadoCer"]["name"]);
				$fieldList["`Emi_CertificadoCer`"] = " '" . $theName . "'";
				@unlink($_FILES["x_Emi_CertificadoCer"]["tmp_name"]);
			}
		}
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_CertificadoPas"]) : $GLOBALS["x_Emi_CertificadoPas"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_CertificadoPas`"] = $theValue;
		if ($a_x_Emi_Constancia == "2") { // Remove
			$fieldList["`Emi_Constancia`"] = "NULL";
		} else if ($a_x_Emi_Constancia == "3") { // Update
			if (is_uploaded_file($_FILES["x_Emi_Constancia"]["tmp_name"])) {
				$destfile = ewUploadPathVita(0) . ewUploadFileName($_FILES["x_Emi_Constancia"]["name"]);
						if (!move_uploaded_file($_FILES["x_Emi_Constancia"]["tmp_name"], $destfile)) // move file to destination path
						die("You didn't upload a file or the file couldn't be moved to" . $destfile);

				// File Name
				$theName = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes(ewUploadFileName($_FILES["x_Emi_Constancia"]["name"])) : ewUploadFileName($_FILES["x_Emi_Constancia"]["name"]);
				$fieldList["`Emi_Constancia`"] = " '" . $theName . "'";
				@unlink($_FILES["x_Emi_Constancia"]["tmp_name"]);
			}
		}

		// update
		$sSql = "UPDATE `Vit_Emisor` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		#echo "<br />sSql: ".$sSql;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
		$bEditData = true; // Update Successful
	}
	return $bEditData;
}
?>
