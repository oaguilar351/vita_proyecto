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
                            <div class="col-xl-3 col-md-6">
                                <div class="card card-height-100">
                                    <div class="card-body">
                                         <div class="d-flex align-items-center">
                                             <div class="avatar-sm flex-shrink-0">
                                                 <span class="avatar-title bg-soft-success text-muted rounded-2 fs-2">
                                                     <i class="bx bx-shopping-bag"></i>
                                                 </span>
                                             </div>
                                             <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium text-muted mb-3">Total Timbrados</p>
<?php		
$timbrados = 0;										
$sSqlWrk = "
SELECT 
Count(Cfdi_ID) AS timbrados 
FROM vit_comprobantes 
WHERE vit_comprobantes.Cfdi_Retcode = '1' 
#AND vit_comprobantes.Cfdi_Status = 'A'
";
if(@$_SESSION["project1_status_Municipio"] != ""){
$sSqlWrk .= "AND vit_comprobantes.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
}
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$timbrados = $rowwrk["timbrados"];
}	
?>											
                                                <h4 class="fs-4 mb-3 text-muted"><span class="counter-value" data-target="<?php echo $timbrados; ?>">0</span></h4>
                                             </div>
                                         </div>
                                    </div><!-- end card body -->
                                </div>
                            </div> <!-- end col-->

                            <div class="col-xl-3 col-md-6">
                                <div class="card card-height-100">
                                    <div class="card-body">
                                         <div class="d-flex align-items-center">
                                             <div class="avatar-sm flex-shrink-0">
                                                 <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                                                     <i class="bx bxs-user-account"></i>
                                                 </span>
                                             </div>
                                             <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium text-muted mb-3">Total Activos</p>
<?php		
$Activos = 0;										
$sSqlWrk = "
SELECT 
Count(Cfdi_ID) AS Activos 
FROM vit_comprobantes 
WHERE vit_comprobantes.Cfdi_Retcode = '1' 
AND vit_comprobantes.Cfdi_Status = 'A'
";
if(@$_SESSION["project1_status_Municipio"] != ""){
$sSqlWrk .= "AND vit_comprobantes.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
}
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$Activos = $rowwrk["Activos"];
}	
?>												
                                                <h4 class="fs-4 mb-3"><span class="counter-value" data-target="<?php echo $Activos; ?>">0</span></h4>
                                             </div>
                                         </div>
                                    </div><!-- end card body -->
                                </div>
                            </div> <!-- end col-->

                            <div class="col-xl-3 col-md-6">
                                <div class="card card-height-100">
                                    <div class="card-body">
                                         <div class="d-flex align-items-center">
                                             <div class="avatar-sm flex-shrink-0">
                                                 <span class="avatar-title bg-soft-danger text-danger rounded-2 fs-2">
                                                        <i class="bx bxs-badge-dollar"></i>
                                                 </span>
                                             </div>
                                             <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium text-muted mb-3">Total Cancelados</p>
												<?php		
$Cancelados = 0;										
$sSqlWrk = "
SELECT 
Count(Cfdi_ID) AS Cancelados 
FROM vit_comprobantes 
WHERE vit_comprobantes.Cfdi_Retcode = '1' 
AND vit_comprobantes.Cfdi_Status = 'C' 
";
if(@$_SESSION["project1_status_Municipio"] != ""){
$sSqlWrk .= "AND vit_comprobantes.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
}
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$Cancelados = $rowwrk["Cancelados"];
}	
?>
                                                <h4 class="fs-4 mb-3"><span class="counter-value" data-target="<?php echo $Cancelados; ?>">0</span></h4>
                                             </div>
                                         </div>
                                    </div><!-- end card body -->
                                </div>
                            </div> <!-- end col-->

                            <div class="col-xl-3 col-md-6">
                                <div class="card card-height-100">
                                    <div class="card-body">
                                         <div class="d-flex align-items-center">
                                             <div class="avatar-sm flex-shrink-0">
                                                 <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                                     <i class="bx bx-store-alt"></i>
                                                 </span>
                                             </div>
                                             <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium text-muted mb-3">Total No Timbrados</p>
												<?php		
$NTimbrados = 0;										
$sSqlWrk = "
SELECT 
Count(Cfdi_ID) AS NTimbrados 
FROM vit_comprobantes 
WHERE vit_comprobantes.Cfdi_Retcode <> '1' 
";
if(@$_SESSION["project1_status_Municipio"] != ""){
$sSqlWrk .= "AND vit_comprobantes.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
}
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$NTimbrados = $rowwrk["NTimbrados"];
}	
?>
                                                <h4 class="fs-4 mb-3"><span class="counter-value" data-target="<?php echo $NTimbrados; ?>">0</span></h4>
                                             </div>
                                         </div>
                                    </div><!-- end card body -->
                                </div>
                            </div> <!-- end col-->
                        </div> <!-- end row-->
					
					<div class="row">
                        <div class="col-lg-12">
                            <div class="card" id="leadsList">
                                <div class="card-header border-0">

                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm-auto ms-auto">											
                                            <div class="hstack gap-2">												
                                                <button type="button" class="btn btn-info" data-bs-toggle="offcanvas" href="#offcanvasExample"><i class="ri-filter-3-line align-bottom me-1" title="Elegir Filtros"></i> Filtros</button>                                                
                                            </div>
                                        </div>
                                    </div>
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
