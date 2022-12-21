<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php
if (@$_SESSION["project1_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/phpmkrfn.php") ?>
<?php
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
?>
    <head>
        
        <title>Empleados | VitaInsumos</title>
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
                                <h4 class="mb-sm-0">Empleados</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Modulos</a></li>
                                        <li class="breadcrumb-item active">Empleados</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
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
                                <div class="card-body border border-dashed border-end-0 border-start-0">
									<form class="d-flex flex-column justify-content-end h-100" name="vit_comprobantessearch" id="vit_comprobantessearch" action="vit_comprobantessrch.php" method="post">
										<div class="row g-3">
										
											<div class="col-xxl-2 col-sm-1">
											 <div class="input-light" id="select_municipios">
												<?php
												$x_Mun_IDList = "<select class=\"form-control\" data-choices data-choices-search-false id=\"s_Mun_ID\" name=\"s_Mun_ID\">";
												$x_Mun_IDList .= "<option value=''>Municipios</option>";
												$sSqlWrk = "SELECT DISTINCT `Mun_ID`, `Mun_Descrip` FROM `Vit_Municipios` WHERE Mun_ID <> '' ";
												if(@$_SESSION["project1_status_Municipio"] != ""){
												$sSqlWrk .= "AND Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
												}
												$sSqlWrk .= "ORDER BY `Mun_Descrip` ASC ";
												$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
												if ($rswrk) {
													$rowcntwrk = 0;
													while ($datawrk = phpmkr_fetch_array($rswrk)) {
														$x_Mun_IDList .= "<option value=\"" . htmlspecialchars($datawrk["Mun_ID"]) . "\"";
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
											<!--end col-->
										
											<div class="col-xxl-2 col-sm-2">
												<div class="input-light">
												<input type="text" class="form-control border-light bg-light" placeholder="RFC Receptor" name="s_RecRFC" id="s_RecRFC">
												</div>
											</div>
											
											<div class="col-xxl-2 col-sm-2">
												<div class="input-light">
												<input type="text" class="form-control border-light bg-light" placeholder="Nombre Receptor" name="s_RecNom" id="s_RecNom">
												</div>
											</div>											
											<!--end col-->
											
											<div class="col-xxl-2 col-sm-2">
											<div class="input-light">
												<input type="date" class="form-control" data-provider="flatpickr" name="s_FechaInicio" id="s_FechaInicio" value="" placeholder="Fecha Inicio Relacion">
												 </div>
											</div>
											<!--end col-->
											
											<div class="col-xxl-2 col-sm-2">
											 <div class="input-light">
												<?php
												$x_statusList = "<select class=\"form-control\" data-choices data-choices-search-false id=\"s_Status\" name=\"s_Status\">";
												$x_statusList .= "<option value=''>Status</option>";
												$x_statusList .= "<option value=\"1\"";
												$x_statusList .= ">" . "Activo" . "</option>";
												$x_statusList .= "<option value=\"0\"";
												$x_statusList .= ">" . "Inactivo" . "</option>";
												$x_statusList .= "</select>";
												echo $x_statusList;
												?>
												  </div>
											</div>
											<!--end col-->
											
											<div class="col-xxl-1 col-sm-1">
												<button type="button" id="btn_quitar" class="btn btn-info w-100" title="Quitar Filtros">Cancelar</button>
											</div>
											<!--end col-->
											
											<div class="col-xxl-1 col-sm-1">
												<button type="button" id="btn_filtrar" class="btn btn-primary w-100"><i class="ri-equalizer-fill me-1 align-bottom" title="Aplicar Filtros"></i> Filtrar </button>
											</div>
											<!--end col-->
										</div>
										<!--end row-->
									</form>
								</div>
                                <div class="card-body">
								 <div>
                                    <div id="listado_empleados">
										<?php include 'crud/listado_empleados.php'; ?>                                        
                                    </div>
                                    <!--end offcanvas-->
								 </div>
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
        <script src="assets/js/app.js"></script>
        <!-- App js -->
        <script src="js/empleados.js"></script>
    </body>

</html>
