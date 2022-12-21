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
$fs_x_const_Archivo = 0;
$fn_x_const_Archivo = "";
$ct_x_const_Archivo = "";
$w_x_const_Archivo = 0;
$h_x_const_Archivo = 0;
$a_x_const_Archivo = "";
$x_const_Ruta = Null; 
$ox_const_Ruta = Null;
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/phpmkrfn.php") ?>
<?php

// Get key
$x_const_ContanciaID = @$_GET["const_ContanciaID"];
if (($x_const_ContanciaID == "") || ((is_null($x_const_ContanciaID)))) {
	ob_end_clean(); 
	header("Location: constancias_listado.php"); 
	exit();
}

//$x_const_ContanciaID = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_const_ContanciaID) : $x_const_ContanciaID;
// Get action

$sAction = @$_POST["a_view"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No hay registros";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: constancias_listado.php");
			exit();
		}
}
?>
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
                                                Consulta Constancia
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body p-4">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
											<div class="row">
                                                    <div class="col-lg-2" style="background-color:#f2f4f4;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">RFC</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_RFC; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-3" style="background-color:#f2f4f4;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">CURP</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_CURP; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-3" style="background-color:#f2f4f4;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Nombres</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_Nombres; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#f2f4f4;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Apellido Paterno</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_Apellido1; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#f2f4f4;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Apellido Materno</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_Apellido2; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#f2f4f4;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Inicio Operaciones</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_InicioOperaciones; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#f2f4f4;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Estatus Padron</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_EstatusPadron; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#f2f4f4;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Ultimo Cambio</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_UltimoCambio; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-4" style="background-color:#f2f4f4;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Nombre Comercial</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_NombreComercial; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#f2f4f4;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Codigo Postal</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_CodigoPostal; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#eaf2f8;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Tipo Vialidad</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_TipoVialidad; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#eaf2f8;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Nombre Vialidad</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_NombreVialidad; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#eaf2f8;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Num Exterior</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_NumExterior; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#eaf2f8;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Num Interior</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_NumInterior; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#eaf2f8;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Colonia</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_Colonia; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#eaf2f8;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Localidad</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_Localidad; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#eaf2f8;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Municipio</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_Municipio; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#eaf2f8;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Entidad</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_Entidad; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#eaf2f8;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Entre Calle</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_EntreCalle; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#eaf2f8;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">YCalle</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_YCalle; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#eaf2f8;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Email</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_Email; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#eaf2f8;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Telefono Lada</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_TelefonoLada; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Telefono Num</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_TelefonoNum; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-2" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Estado Domicilio</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_EstadoDomicilio; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-4" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Estado Contribuyente</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_EstadoContribuyente; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-4" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Archivo</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_Archivo; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-12" style="background-color:#fdedec;">
														<br />
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label">Ruta</label>
                                                            <input type="text" class="form-control" value="<?php echo $x_const_Ruta; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--end col-->
													<div class="col-lg-12">
													<br />
                                                        <div class="hstack gap-2 justify-content-end">
															<a class="btn btn-primary waves-effect waves-light" href="constancias_listado.php">Cancelar</a>
															<input type="hidden" name="a_edit" value="U">
                                                        </div>
                                                    </div>
                                                    <!--end col-->
                                                </div>
                                                <!--end row-->
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
	global $x_const_ContanciaID;
	$sSql = "SELECT * FROM `constancias`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_const_ContanciaID) : $x_const_ContanciaID;
	$sWhere .= "(`const_ContanciaID` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_const_ContanciaID"] = $row["const_ContanciaID"];
		$GLOBALS["x_const_RFC"] = $row["const_RFC"];
		$GLOBALS["x_const_CURP"] = $row["const_CURP"];
		$GLOBALS["x_const_Nombres"] = $row["const_Nombres"];
		$GLOBALS["x_const_Apellido1"] = $row["const_Apellido1"];
		$GLOBALS["x_const_Apellido2"] = $row["const_Apellido2"];
		$GLOBALS["x_const_InicioOperaciones"] = $row["const_InicioOperaciones"];
		$GLOBALS["x_const_EstatusPadron"] = $row["const_EstatusPadron"];
		$GLOBALS["x_const_UltimoCambio"] = $row["const_UltimoCambio"];
		$GLOBALS["x_const_NombreComercial"] = $row["const_NombreComercial"];
		$GLOBALS["x_const_CodigoPostal"] = $row["const_CodigoPostal"];
		$GLOBALS["x_const_TipoVialidad"] = $row["const_TipoVialidad"];
		$GLOBALS["x_const_NombreVialidad"] = $row["const_NombreVialidad"];
		$GLOBALS["x_const_NumExterior"] = $row["const_NumExterior"];
		$GLOBALS["x_const_NumInterior"] = $row["const_NumInterior"];
		$GLOBALS["x_const_Colonia"] = $row["const_Colonia"];
		$GLOBALS["x_const_Localidad"] = $row["const_Localidad"];
		$GLOBALS["x_const_Municipio"] = $row["const_Municipio"];
		$GLOBALS["x_const_Entidad"] = $row["const_Entidad"];
		$GLOBALS["x_const_EntreCalle"] = $row["const_EntreCalle"];
		$GLOBALS["x_const_YCalle"] = $row["const_YCalle"];
		$GLOBALS["x_const_Email"] = $row["const_Email"];
		$GLOBALS["x_const_TelefonoLada"] = $row["const_TelefonoLada"];
		$GLOBALS["x_const_TelefonoNum"] = $row["const_TelefonoNum"];
		$GLOBALS["x_const_EstadoDomicilio"] = $row["const_EstadoDomicilio"];
		$GLOBALS["x_const_EstadoContribuyente"] = $row["const_EstadoContribuyente"];
		$GLOBALS["x_const_Archivo"] = $row["const_Archivo"];
		$GLOBALS["x_const_Ruta"] = $row["const_Ruta"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
