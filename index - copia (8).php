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
<?php $conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT); ?>
    <head>
        
        <title>Inicio | VitaInsumos</title>
        <?php include 'layouts/title-meta.php'; ?>

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
                                    <h4 class="mb-sm-0">Inicio</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">&nbsp;</a></li>
                                            <li class="breadcrumb-item active">Inicio</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->                        

                        <div class="row">
                            <div class="col-xxl-12">
								<!---INICIO TABLA---->        
								<div class="card" id="marketList">
									<div class="card-header border-bottom-dashed">
										<div class="row align-items-center">
											<div class="col-3">
												<h5 class="card-title mb-0">Informe General</h5>
											</div><!--end col-->
											<div class="col-auto ms-auto">
												<div class="d-flex gap-2">
													<!--<button class="btn btn-success"><i class="ri-equalizer-line align-bottom me-1"></i> Filters</button>-->
												</div>
											</div><!--end col-->
										</div><!--end row-->
									</div><!--end card-header-->
									<div class="card-body border border-dashed border-end-0 border-start-0">
									<form class="d-flex flex-column justify-content-end h-100" name="vit_comprobantessearch" id="vit_comprobantessearch" action="vit_comprobantessrch.php" method="post">
										<div class="row g-3">
										
											<div class="col-xxl-2 col-sm-1">
											 <div class="input-light" id="select_municipios">
												<?php
												$x_Mun_IDList = "<select class=\"form-control\" data-choices data-choices-search-false id=\"s_Mun_ID\" name=\"s_Mun_ID\">";
												$x_Mun_IDList .= "<option value=''>Municipio</option>";
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
										
											<div class="col-xxl-4 col-sm-4">
												<div class="input-light" id="select_municipios">
												<?php
												$x_Mun_IDList = "<select class=\"form-control\" data-choices data-choices-search-false id=\"s_Emi_RFC\" name=\"s_Emi_RFC\">";
												$x_Mun_IDList .= "<option value=''>Emisor</option>";
												$sSqlWrk = "SELECT DISTINCT `Emi_RFC`, `Emi_NomCorto` FROM `Vit_Emisor` WHERE Mun_ID <> '' ";
												if(@$_SESSION["project1_status_Municipio"] != ""){
												$sSqlWrk .= "AND Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
												}
												$sSqlWrk .= "ORDER BY `Emi_NomCorto` ASC ";
												$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
												if ($rswrk) {
													$rowcntwrk = 0;
													while ($datawrk = phpmkr_fetch_array($rswrk)) {
														$x_Mun_IDList .= "<option value=\"" . htmlspecialchars($datawrk["Emi_RFC"]) . "\"";
														$x_Mun_IDList .= ">" . $datawrk["Emi_NomCorto"] . "</option>";
														$rowcntwrk++;
													}
												}
												@phpmkr_free_result($rswrk);
												$x_Mun_IDList .= "</select>";
												echo $x_Mun_IDList;
												?>
												  </div>
											</div>
											
											<div class="col-xxl-2 col-sm-2">
												<div class="input-light" id="select_municipios">
												<?php
												$s_Ejercicio = date('Y');
												$x_Mun_IDList = "<select class=\"form-control\" data-choices data-choices-search-false id=\"s_Ejercicio\" name=\"s_Ejercicio\">";
												$x_Mun_IDList .= "<option value=''>Ejercicio</option>";
												$sSqlWrk = "SELECT inf_ejercicio FROM `vit_informe` WHERE Mun_ID <> '' ";
												if(@$_SESSION["project1_status_Municipio"] != ""){
												$sSqlWrk .= "AND Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
												}
												$sSqlWrk .= "GROUP BY `inf_ejercicio`";
												$sSqlWrk .= "ORDER BY `inf_ejercicio` ASC ";
												$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
												if ($rswrk) {
													$rowcntwrk = 0;
													while ($datawrk = phpmkr_fetch_array($rswrk)) {
														$selected = ($s_Ejercicio == $datawrk["inf_ejercicio"])?' selected':'';
														$x_Mun_IDList .= "<option value=\"" . htmlspecialchars($datawrk["inf_ejercicio"]) . "\"";
														$x_Mun_IDList .= "".$selected.">" . $datawrk["inf_ejercicio"] . "</option>";
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
											<div class="input-light" id="select_municipios">
												<?php
												$x_Mun_IDList = "<select class=\"form-control\" data-choices data-choices-search-false id=\"s_Periodo\" name=\"s_Periodo\">";
												$x_Mun_IDList .= "<option value=''>Periodo</option>";
												$sSqlWrk = "SELECT inf_periodo FROM `vit_informe` WHERE Mun_ID <> '' ";
												if(@$_SESSION["project1_status_Municipio"] != ""){
												$sSqlWrk .= "AND Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
												}
												$sSqlWrk .= "GROUP BY `inf_periodo`";
												$sSqlWrk .= "ORDER BY `inf_periodo` ASC ";
												$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
												if ($rswrk) {
													$rowcntwrk = 0;
													while ($datawrk = phpmkr_fetch_array($rswrk)) {
														$x_Mun_IDList .= "<option value=\"" . htmlspecialchars($datawrk["inf_periodo"]) . "\"";
														$x_Mun_IDList .= ">" . $datawrk["inf_periodo"] . "</option>";
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

										<div class="table-responsive table-card" id="listado_informe">
											<?php include 'crud/listado_informe.php'; ?>	
										</div>
										
									</div><!--end card-body-->
								</div><!--end card-->
							</div><!--end col-->                            
                        </div><!--end row-->                        

                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <?php include 'layouts/footer.php'; ?>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        

        <?php #include 'layouts/customizer.php'; ?>

        <?php include 'layouts/vendor-scripts.php'; ?>

        <!-- list.js min js -->
        <script src="assets/libs/list.js/list.min.js"></script>
        <script src="assets/libs/list.pagination.js/list.pagination.min.js"></script>

        <!-- apexcharts -->
        <script src="assets/libs/apexcharts/apexcharts.min.js"></script>

        <!--crypto-buy-sell init-->
		
        <!-- App js -->
        <script src="assets/js/app.js"></script>
		
		<script src="js/index.js"></script>
    </body>

</html>