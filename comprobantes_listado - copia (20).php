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
        
        <title>Comprobantes | VitaInsumos</title>
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
                                <div class="card-body border border-dashed border-end-0 border-start-0">
									<form class="d-flex flex-column justify-content-end h-100" name="vit_comprobantessearch" id="vit_comprobantessearch" action="vit_comprobantessrch.php" method="post">
										<div class="row g-3">
										
											<div class="col-xxl-3 col-sm-3">
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
											
											<div class="col-xxl-3 col-sm-3">
												<div class="input-light" id="select_emisores">
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
											
											<div class="col-xxl-3 col-sm-3">
											<div class="input-light" id="select_series">
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
											
											<div class="col-xxl-3 col-sm-3">
											 <div class="input-light" id="select_receptores">
												<?php
												$x_Rec_RFCList = "<select class=\"form-control\" data-choices data-choices-search-false id=\"s_Rec_RFC\" name=\"s_Rec_RFC\">";
												$x_Rec_RFCList .= "<option value=''>Receptor</option>";
												$sSqlWrk = "SELECT ";
												$sSqlWrk .= "Vit_Receptor.Rec_RFC, ";
												$sSqlWrk .= "Vit_Receptor.Rec_Nombre, ";
												$sSqlWrk .= "Vit_Receptor.Rec_Apellido_Paterno, ";
												$sSqlWrk .= "Vit_Receptor.Rec_Apellido_Materno ";
												$sSqlWrk .= "FROM Vit_Receptor ";												
												$sSqlWrk .= "WHERE Rec_Nombre <> '' ";
												if(@$_GET["s_Emi_RFC"]!=""){
												$sSqlWrk .= "AND Vit_Receptor.Emi_RFC = '".@$_GET["s_Emi_RFC"]."' ";															
												}
												if(@$_SESSION["project1_status_Municipio"] != ""){
												$sSqlWrk .= "AND Vit_Receptor.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
												}
												$sSqlWrk .= "GROUP BY Rec_RFC ";
												$sSqlWrk .= "ORDER BY Rec_Nombre, Rec_Apellido_Paterno, Rec_Apellido_Materno Asc";
												#echo "<br />".$sSqlWrk;
												$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
												if ($rswrk) {
													$rowcntwrk = 0;
													while ($datawrk = phpmkr_fetch_array($rswrk)) {
														$x_Rec_RFCList .= "<option value=\"" . htmlspecialchars($datawrk["Rec_RFC"]) . "\"";
														$x_Rec_RFCList .= ">" . $datawrk["Rec_Nombre"] . " " . $datawrk["Rec_Apellido_Paterno"] . " " . $datawrk["Rec_Apellido_Materno"] . "</option>";
														$rowcntwrk++;
													}
												}
												@phpmkr_free_result($rswrk);
												$x_Rec_RFCList .= "</select>";
												echo $x_Rec_RFCList;
												?>
												  </div>
											</div>
											<!--end col-->										

											<div class="col-xxl-3 col-sm-2">
											 <div class="input-light">
												<?php
												$x_statusList = "<select class=\"form-control\" data-choices data-choices-search-false id=\"s_Status\" name=\"s_Status\">";
												$x_statusList .= "<option value=''>Status</option>";
												$x_statusList .= "<option value=\"A\"";
												$x_statusList .= ">" . "Activo" . "</option>";
												$x_statusList .= "<option value=\"C\"";
												$x_statusList .= ">" . "Cancelado" . "</option>";
												#$x_statusList .= "<option value=\"P\"";
												#$x_statusList .= ">" . "Pendiente de cancelar" . "</option>";
												$x_statusList .= "</select>";
												echo $x_statusList;
												?>
												  </div>
											</div>
											<!--end col-->
											
											<div class="col-xxl-3 col-sm-2">
												<div class="input-light">
												<!--<input type="text" class="form-control" data-choices data-choices-search-false name="s_Cfdi_UUID" id="s_Cfdi_UUID" placeholder="UUID">-->
												<input type="text" class="form-control border-light bg-light" placeholder="RFC Receptor" name="s_RecRFC" id="s_RecRFC">
												</div>
											</div>
											
											<div class="col-xxl-3 col-sm-2">
												<div class="input-light">
												<!--<input type="text" class="form-control" data-choices data-choices-search-false name="s_Cfdi_UUID" id="s_Cfdi_UUID" placeholder="UUID">-->
												<input type="text" class="form-control border-light bg-light" placeholder="UUID" name="s_Cfdi_UUID" id="s_Cfdi_UUID">
												</div>
											</div>											
											<!--end col-->
											
											<div class="col-xxl-1 col-sm-1">
												<button type="button" id="btn_filtrar" class="btn btn-primary w-100"><i class="ri-equalizer-fill me-1 align-bottom" title="Aplicar Filtros"></i> Filtrar </button>
											</div>
											<!--end col-->
											
											<div class="col-xxl-1 col-sm-1">
												<button type="button" id="btn_quitar" class="btn btn-info w-100" title="Quitar Filtros">Cancelar</button>
											</div>
											<!--end col-->
										</div>
										<!--end row-->
									</form>
								</div>
                                <div class="card-body">
								 <div>
                                    <div id="listado_comprobantes">
										<?php include 'crud/listado_comprobantes.php'; ?>                                        
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
        <script src="js/comprobantes.js"></script>
    </body>

</html>
