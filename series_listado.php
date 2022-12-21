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
        
        <title>Series | VitaInsumos</title>
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
                                <h4 class="mb-sm-0">Series</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Modulos</a></li>
                                        <li class="breadcrumb-item active">Series</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
					
					<div class="row">
                        <div class="col-lg-12">
                            <div class="card" id="leadsList">
                                <div class="card-body border border-dashed border-end-0 border-start-0">
									<form class="d-flex flex-column justify-content-end h-100" name="vit_comprobantessearch" id="vit_comprobantessearch" action="vit_comprobantessrch.php" method="post">
										<div class="row g-3">											 
											
											<div class="col-xxl-3 col-sm-2">
												<div class="input-light">
													<?php
													$x_Emi_RFCList = "<select class=\"form-control\" data-choices data-choices-search-false id=\"s_Emi_RFC\" name=\"s_Emi_RFC\">";
													$x_Emi_RFCList .= "<option value=''>Emisor</option>";
													$sSqlWrk = "
													SELECT DISTINCT
													Vit_Emisor.Emi_RFC, 
													Vit_Emisor.Emi_Nombre, 
													Vit_Emisor.Emi_NomCorto
													FROM Vit_Emisor
													WHERE Vit_Emisor.Emi_RFC <> '' ";
													if(@$_SESSION["project1_status_Municipio"] != ""){
													$sSqlWrk .= "AND Vit_Emisor.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
													}
													$sSqlWrk .= "
													ORDER BY Vit_Emisor.Emi_Nombre ASC";
													#echo "<br />".$sSqlWrk;
													$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
													if ($rswrk) {
														$rowcntwrk = 0;
														while ($datawrk = phpmkr_fetch_array($rswrk)) {
															$x_Emi_RFCList .= "<option value=\"" . htmlspecialchars($datawrk["Emi_RFC"]) . "\"";
															if ($datawrk["Emi_RFC"] == @$_GET["s_Emi_RFC"]) {
																$x_Emi_RFCList .= "' selected";
															}
															$x_Emi_RFCList .= ">" . $datawrk["Emi_Nombre"] . "</option>";
															$rowcntwrk++;
														}
													}
													@phpmkr_free_result($rswrk);
													$x_Emi_RFCList .= "</select>";
													echo $x_Emi_RFCList;
													?>
												</div>
											</div>
											<!--end col-->
											
											<div class="col-xxl-3 col-sm-2">
											<div class="input-light">
												<?php
												$x_Cfdi_SerieList = "<select class=\"form-control\" data-choices data-choices-search-false id=\"s_Cfdi_Serie\" name=\"s_Cfdi_Serie\">";
												$x_Cfdi_SerieList .= "<option value=''>Series</option>";
												$sSqlWrk = "
												SELECT DISTINCT 
												vit_comprobantes.Cfdi_Serie, 
												vit_comprobantes.Cfdi_Status, 
												vit_comprobantes.Cfdi_Error 
												FROM 
												vit_comprobantes 
												WHERE vit_comprobantes.Cfdi_Retcode = '1' 
												AND vit_comprobantes.Cfdi_Status <> 'P'  
												";
												if(@$_SESSION["project1_status_Municipio"] != ""){
												$sSqlWrk .= "AND vit_comprobantes.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
												}
												$sSqlWrk .= "ORDER BY vit_comprobantes.Cfdi_Serie ASC";
												#echo "<br />".$sSqlWrk;
												$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
												if ($rswrk) {
													$rowcntwrk = 0;
													while ($datawrk = phpmkr_fetch_array($rswrk)) {
														$x_Cfdi_SerieList .= "<option value=\"" . htmlspecialchars($datawrk["Cfdi_Serie"]) . "\"";
														if ($datawrk["Cfdi_Serie"] == @$_GET["Cfdi_Serie"]) {
															$x_Cfdi_SerieList .= "' selected";
														}
														$x_Cfdi_SerieList .= ">" . $datawrk["Cfdi_Serie"] . "</option>";
														$rowcntwrk++;
													}
												}
												@phpmkr_free_result($rswrk);
												$x_Cfdi_SerieList .= "</select>";
												echo $x_Cfdi_SerieList;
												?>
												 </div>
											</div>
											<!--end col-->			

											<div class="col-xxl-2 col-sm-2">
											<div class="input-light">
												<input type="date" class="form-control" data-provider="flatpickr" name="s_Nom_FechaPago" id="s_Nom_FechaPago" value="" placeholder="Fecha Pago">
												 </div>
											</div>
											<!--end col-->														
											
											<div class="col-xxl-2 col-sm-2">
											<div class="input-light">
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
											<!--end col-->
											<div class="col-xxl-1 col-sm-2">
												<button type="button" id="btn_filtrar" class="btn btn-primary w-100"><i class="ri-equalizer-fill me-1 align-bottom" title="Aplicar Filtros"></i> Filtrar </button>
												<!--<button type="button" id="btn_filtrar" class="btn btn-success waves-effect waves-light w-100" title="Aplicar Filtros" data-bs-dismiss="offcanvas">Filtrar</button>-->
											</div>
											<!--end col-->
										</div>
										<!--end row-->
									</form>
								</div>
                                <div class="card-body">
								 <div>
                                    <div id="listado_series">
										<?php include 'crud/listado_series.php'; ?>                                        
                                    </div>                                   

                                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                                        <?php //include 'crud/filtros_comprobantes.php'; ?>
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
        <script src="js/series.js"></script>
    </body>

</html>
