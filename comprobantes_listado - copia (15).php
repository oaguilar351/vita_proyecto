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
        
        <title>Comprobentes | VitaInsumos</title>
        <?php include 'layouts/title-meta.php'; ?>
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
                                <div class="card-header border-0">

                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm-3">
                                            <div class="search-box">
												<form action="comprobantes_listado.php">
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
												<?php if(@$_SESSION["vit_comprobantes_OrderBy"]!=""){ ?>
												<a class="btn btn-light" href="comprobantes_listado.php?cmd=resetsort" title="Quitar Orden"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>X</a>
												<?php } ?>											
												<?php if(@$sWhere!="" && @$_SESSION["vit_comprobantes_OrderBy"]==""){ ?>
												<a class="btn btn-light" href="comprobantes_listado.php?cmd=reset" title="Quitar Filtros"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>F</a>
												<?php } ?>
                                                <button type="button" class="btn btn-info" data-bs-toggle="offcanvas"
                                                    href="#offcanvasExample"><i class="ri-filter-3-line align-bottom me-1" title="Elegir Filtros"></i> Filtros</button>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="listado_comprobantes">
										<?php include 'crud/listado_comprobantes.php'; ?>                                        
                                    </div>                                   

                                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample"
                                        aria-labelledby="offcanvasExampleLabel">
                                        <div class="offcanvas-header bg-light">
                                            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Filtros - Comprobantes</h5>
                                            <button type="button" class="btn-close text-reset"
                                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <!--end offcanvas-header-->
										<form class="d-flex flex-column justify-content-end h-100" name="vit_comprobantessearch" id="vit_comprobantessearch" action="vit_comprobantessrch.php" method="post" onSubmit="return EW_checkMyForm(this);">
                                        <!--<form action="" class="d-flex flex-column justify-content-end h-100">-->
                                            <div class="offcanvas-body">
												<div class="mb-4">
                                                    <label for="Serie" class="form-label text-muted text-uppercase fw-semibold mb-3">Serie</label>
													<input type="hidden" name="z_Cfdi_Serie[]" value="LIKE,'%,%'">
                                                    <input class="form-control" type="text" name="s_Cfdi_Serie" id="s_Cfdi_Serie" size="30" maxlength="120" value="<?php echo htmlspecialchars(@$_GET["s_Cfdi_Serie"]) ?>">
                                                </div>
												<div class="mb-4">
                                                    <label for="Folio" class="form-label text-muted text-uppercase fw-semibold mb-3">Folio</label>
													<input type="hidden" name="z_Cfdi_Folio[]" value="=,,">
                                                    <input class="form-control" type="text" name="s_Cfdi_Folio" id="s_Cfdi_Folio" size="30" value="<?php echo htmlspecialchars(@$_GET["s_Cfdi_Folio"]); ?>">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="datepicker-range"
                                                        class="form-label text-muted text-uppercase fw-semibold mb-3">Fecha</label>
														<input type="hidden" name="z_Cfdi_Fecha[]" value="LIKE,'%,%'">
														<input type="date" name="s_Cfdi_Fecha" id="s_Cfdi_Fecha" 
                                                                        class="form-control" data-provider="flatpickr" data-date-format="YYYY MM DD"
                                                                        placeholder="Select Date" value="<?php echo FormatDateTime(@$_GET["s_Cfdi_Fecha"],5); ?>" />
                                                </div>
                                                <div class="mb-4">
                                                    <label for="country-select"
                                                        class="form-label text-muted text-uppercase fw-semibold mb-3">Emisor</label>
														<input type="hidden" name="z_Emi_RFC[]" value="LIKE,'%,%'">
														
                                                </div>
												<div class="mb-4">
                                                    <label for="country-select"
                                                        class="form-label text-muted text-uppercase fw-semibold mb-3">Receptor</label>
														<input type="hidden" name="z_Rec_RFC[]" value="LIKE,'%,%'">
														
                                                </div>
                                                <div class="mb-4">
                                                    <label for="UUID" class="form-label text-muted text-uppercase fw-semibold mb-3">UUID</label>
													<input type="hidden" name="z_Cfdi_UUID[]" value="=,','">
                                                    <input class="form-control" type="text" name="s_Cfdi_UUID" id="s_Cfdi_UUID" size="30" value="<?php echo (@$_GET["s_Cfdi_UUID"]); ?>">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="country-select"
                                                        class="form-label text-muted text-uppercase fw-semibold mb-3">Municipio</label>
														<input type="hidden" name="z_Mun_ID[]" value="=,,">
														
                                                </div>
                                            </div>
                                            <!--end offcanvas-body-->
                                            <div class="offcanvas-footer border-top p-3 text-center hstack gap-2">
                                                <!--<button class="btn btn-light w-100">Limpiar Filtro</button>-->
												<?php if(@$_SESSION["vit_comprobantes_OrderBy"]!=""){ ?>												
												<a class="btn btn-light w-100" href="comprobantes_listado.php?cmd=resetsort">Quitar Orden</a>
												<?php } ?>											
												<?php if(@$sWhere!="" && @$_SESSION["vit_comprobantes_OrderBy"]==""){ ?>
												<a class="btn btn-light w-100" href="comprobantes_listado.php?cmd=reset">Quitar Filtros</a>
												<?php } ?>												
                                                <button type="submit" name="Action" class="btn btn-soft-success waves-effect waves-light w-100">Filtrar</button>
												<input type="hidden" name="a_search" value="S">
                                            </div>
                                            <!--end offcanvas-footer-->
                                        </form>
                                    </div>
                                    <!--end offcanvas-->

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
        <script src="js/comprobantes.js"></script>
    </body>

</html>
