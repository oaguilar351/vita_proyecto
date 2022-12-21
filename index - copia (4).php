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
                            <div class="col-xl-3 col-sm-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <h6 class="text-muted mb-3">Total Comprobantes Timbrados</h6>
												<?php		
$timbrados = 0;										
$sSqlWrk = "
SELECT 
Count(vit_comprobantes.Cfdi_ID) AS timbrados 
FROM vit_comprobantes 
INNER JOIN Vit_Nominas ON vit_comprobantes.Cfdi_ID = Vit_Nominas.Cfdi_ID
WHERE vit_comprobantes.Cfdi_Retcode = '1'
AND YEAR(Vit_Nominas.Nom_FechaPago) = '2022'
AND MONTH(Vit_Nominas.Nom_FechaPago) = '10'
";
if(@$_SESSION["project1_status_Municipio"] != ""){
$sSqlWrk .= "AND vit_comprobantes.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
}
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$timbrados = $rowwrk["timbrados"];
}	
?>
                                                <h2 class="mb-0">$<span class="counter-value" data-target="<?php echo $timbrados; ?>"></span><small class="text-muted fs-13"></small></h2>
                                            </div>
                                            <div class="flex-shrink-0 avatar-sm">
                                                <div class="avatar-title bg-soft-danger text-danger fs-22 rounded">
                                                    <i class="ri-shopping-bag-line"></i>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div><!--end card-->
                            </div><!--end col-->
                            <div class="col-xl-3 col-sm-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <h6 class="text-muted mb-3">Total Comprobantes Activos</h6>
<?php		
$Activos = 0;										
$sSqlWrk = "
SELECT 
Count(vit_comprobantes.Cfdi_ID) AS Activos 
FROM vit_comprobantes 
INNER JOIN Vit_Nominas ON vit_comprobantes.Cfdi_ID = Vit_Nominas.Cfdi_ID
WHERE vit_comprobantes.Cfdi_Retcode = '1'
AND YEAR(Vit_Nominas.Nom_FechaPago) = '2022'
AND MONTH(Vit_Nominas.Nom_FechaPago) = '10'
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
                                                <h2 class="mb-0">$<span class="counter-value" data-target="<?php echo $Activos; ?>"></span><small class="text-muted fs-13"></small></h2>
                                            </div>
                                            <div class="flex-shrink-0 avatar-sm">
                                                <div class="avatar-title bg-soft-info text-info fs-22 rounded">
                                                    <i class="ri-funds-line"></i>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div><!--end card-->
                            </div><!--end col-->
                            <div class="col-xl-3 col-sm-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <h6 class="text-muted mb-3">Total Comprobantes Cancelados</h6>
<?php		
$Cancelados = 0;										
$sSqlWrk = "
SELECT 
Count(vit_comprobantes.Cfdi_ID) AS Cancelados 
FROM vit_comprobantes 
INNER JOIN Vit_Nominas ON vit_comprobantes.Cfdi_ID = Vit_Nominas.Cfdi_ID
WHERE vit_comprobantes.Cfdi_Retcode = '1'
AND YEAR(Vit_Nominas.Nom_FechaPago) = '2022'
AND MONTH(Vit_Nominas.Nom_FechaPago) = '10'
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
                                                <h2 class="mb-0">$<span class="counter-value" data-target="<?php echo $Cancelados; ?>"></span><small class="text-muted fs-13"></small></h2>
                                            </div>
                                            <div class="flex-shrink-0 avatar-sm">
                                                <div class="avatar-title bg-soft-warning text-warning fs-22 rounded">
                                                    <i class="ri-arrow-left-down-fill"></i>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div><!--end card-->
                            </div><!--end col-->
                            <div class="col-xl-3 col-sm-6">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <h6 class="text-muted mb-3">Total Comprobantes No Timbrados</h6>
<?php		
$NTimbrados = 0;										
$sSqlWrk = "
SELECT 
Count(vit_comprobantes.Cfdi_ID) AS NTimbrados 
FROM vit_comprobantes 
INNER JOIN Vit_Nominas ON vit_comprobantes.Cfdi_ID = Vit_Nominas.Cfdi_ID
WHERE vit_comprobantes.Cfdi_Retcode <> '1'
AND YEAR(Vit_Nominas.Nom_FechaPago) = '2022'
AND MONTH(Vit_Nominas.Nom_FechaPago) = '10'
";
if(@$_SESSION["project1_status_Municipio"] != ""){
$sSqlWrk .= "AND vit_comprobantes.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
}
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$NTimbrados = $rowwrk["NTimbrados"];
}	
?>												
                                                <h2 class="mb-0">$<span class="counter-value" data-target="<?php echo $NTimbrados; ?>"></span><small class="text-muted fs-13"></small></h2>
                                            </div>
                                            <div class="flex-shrink-0 avatar-sm">
                                                <div class="avatar-title bg-soft-success text-success fs-22 rounded">
                                                    <i class="ri-arrow-right-up-fill"></i>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div><!--end card-->
                            </div><!--end col-->
                        </div><!--end row-->

                        <div class="row">
                            <div class="col-xxl-9">
								<!---INICIO TABLA---->        
								<div class="card" id="marketList">
									<div class="card-header border-bottom-dashed">
										<div class="row align-items-center">
											<div class="col-3">
												<h5 class="card-title mb-0">Emisores</h5>
											</div><!--end col-->
											<div class="col-auto ms-auto">
												<div class="d-flex gap-2">
													<!--<button class="btn btn-success"><i class="ri-equalizer-line align-bottom me-1"></i> Filters</button>-->
												</div>
											</div><!--end col-->
										</div><!--end row-->
									</div><!--end card-header-->
									<!--<div class="card-body p-0 border-bottom border-bottom-dashed">
										<div class="search-box">
											<input type="text" class="form-control search border-0 py-3" placeholder="Search to currency...">
											<i class="ri-search-line search-icon"></i>
										</div>
									</div><!--end card-body-->
									<div class="card-body">

<div class="table-responsive table-card">
	<table class="table align-middle" id="customerTable">
		<thead class="table-light">
			<tr>
				<th><a href="javascript:void(0);">Emisor</a></th>
				<th><a href="javascript:void(0);">Receptores</a></th>
				<th><a href="javascript:void(0);">Series</a></th>
				<th><a href="javascript:void(0);">Comprobantes</a></th>
				<th><a href="javascript:void(0);">Percepciones</a></th>
				<th><a href="javascript:void(0);">Deducciones</a></th>
				<th><a href="javascript:void(0);">Otros Pagos</a></th>
				<!--<th><a href="javascript:void(0);">&nbsp;</a></th>-->
			</tr>
		</thead>
		<tbody class="list form-check-all">
<?php
// Build SQL
$tot_rep = 0;
$tot_ser = 0;
$tot_com = 0;
$tot_per = 0;
$tot_ded = 0;
$tot_otr = 0;
$sSql = "
SELECT
vit_comprobantes.Emi_RFC,
Vit_Emisor.Emi_Nombre,
Vit_Emisor.Emi_NomCorto
FROM
vit_comprobantes
INNER JOIN Vit_Emisor ON vit_comprobantes.Emi_RFC = Vit_Emisor.Emi_RFC
INNER JOIN Vit_Nominas ON vit_comprobantes.Cfdi_ID = Vit_Nominas.Cfdi_ID
WHERE vit_comprobantes.Cfdi_Retcode = '1'
AND YEAR(Vit_Nominas.Nom_FechaPago) = '2022'
AND MONTH(Vit_Nominas.Nom_FechaPago) = '10'
GROUP BY
vit_comprobantes.Emi_RFC, Vit_Emisor.Emi_Nombre, Vit_Emisor.Emi_NomCorto 
";
#echo "<br />sSql: ".$sSql;
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
if ($rs) {
	
	while ($row = phpmkr_fetch_array($rs)) {
?>
		<tr>
			<td><?php echo strtoupper($row["Emi_NomCorto"]); ?></td>
<?php		
$receptores = 0;										
$sSqlWrk = "
SELECT DISTINCT
vit_comprobantes.Rec_RFC AS receptores
FROM vit_comprobantes
INNER JOIN Vit_Emisor ON vit_comprobantes.Emi_RFC = Vit_Emisor.Emi_RFC
INNER JOIN Vit_Nominas ON vit_comprobantes.Cfdi_ID = Vit_Nominas.Cfdi_ID
WHERE vit_comprobantes.Cfdi_Retcode = '1'
AND YEAR(Vit_Nominas.Nom_FechaPago) = '2022'
AND MONTH(Vit_Nominas.Nom_FechaPago) = '10'
AND vit_comprobantes.Emi_RFC = '".$row["Emi_RFC"]."' ";
if(@$_SESSION["project1_status_Municipio"] != ""){
$sSqlWrk .= "AND vit_comprobantes.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
}
#echo $sSqlWrk;
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	while ($rowwrk = phpmkr_fetch_array($rswrk)) {
		$receptores++;
	}
}
$tot_rep += $receptores;
?>			
			<td align="right">
			<?php echo number_format($receptores,0); ?>
			</td>
<?php		
$series = 0;										
$sSqlWrk2 = "
SELECT DISTINCT
vit_comprobantes.Cfdi_Serie AS series
FROM vit_comprobantes
INNER JOIN Vit_Nominas ON vit_comprobantes.Cfdi_ID = Vit_Nominas.Cfdi_ID
WHERE vit_comprobantes.Emi_RFC = '".$row["Emi_RFC"]."' 
AND YEAR(Vit_Nominas.Nom_FechaPago) = '2022'
AND MONTH(Vit_Nominas.Nom_FechaPago) = '10'
";
if(@$_SESSION["project1_status_Municipio"] != ""){
$sSqlWrk2 .= "AND vit_comprobantes.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
}
#echo $sSqlWrk2;
$rswrk2 = phpmkr_query($sSqlWrk2,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk2);
if ($rswrk2) {
	while ($rowwrk2 = phpmkr_fetch_array($rswrk2)) {
		$series++;
	}
}
$tot_ser += $series;	
?>			
			<td align="right"><?php echo number_format($series,0); ?></td>
<?php		
$uuids = 0;										
$sSqlWrk2 = "
SELECT DISTINCT
vit_comprobantes.Cfdi_UUID AS Cfdi_UUID
FROM vit_comprobantes
WHERE vit_comprobantes.Cfdi_Retcode = '1'
AND vit_comprobantes.Cfdi_UUID <> ''
AND vit_comprobantes.Emi_RFC = '".$row["Emi_RFC"]."' ";
if(@$_SESSION["project1_status_Municipio"] != ""){
$sSqlWrk2 .= "AND vit_comprobantes.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
}
#echo $sSqlWrk2;
$rswrk2 = phpmkr_query($sSqlWrk2,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk2);
if ($rswrk2) {
	while ($rowwrk2 = phpmkr_fetch_array($rswrk2)) {
		$uuids++;
	}
}
$tot_com += $uuids;
?>					
			<td align="right"><?php echo number_format($uuids,0); ?></td>
<?php		
$Nom_TotalPercepciones = 0;
$Nom_TotalDeducciones = 0;
$Nom_TotalOtrosPagos = 0;
$Nom_OrigenRecurso = 0;
$Nom_MontoRecursoPropio = 0;
$sSqlWrk = "
SELECT
SUM(Vit_Nominas.Nom_TotalPercepciones) AS Nom_TotalPercepciones,
SUM(Vit_Nominas.Nom_TotalDeducciones) AS Nom_TotalDeducciones,
SUM(Vit_Nominas.Nom_TotalOtrosPagos) AS Nom_TotalOtrosPagos,
SUM(Vit_Nominas.Nom_OrigenRecurso) AS Nom_OrigenRecurso,
SUM(Vit_Nominas.Nom_MontoRecursoPropio) AS Nom_MontoRecursoPropio
FROM
Vit_Nominas
INNER JOIN vit_comprobantes ON Vit_Nominas.Cfdi_ID = vit_comprobantes.Cfdi_ID
WHERE vit_comprobantes.Cfdi_Retcode = '1' 
AND YEAR(Vit_Nominas.Nom_FechaPago) = '2022'
AND MONTH(Vit_Nominas.Nom_FechaPago) = '10'
AND vit_comprobantes.Emi_RFC = '".$row["Emi_RFC"]."' 
";
if(@$_SESSION["project1_status_Municipio"] != ""){
$sSqlWrk .= "AND vit_comprobantes.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
}
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$Nom_TotalPercepciones = $rowwrk["Nom_TotalPercepciones"];
	$Nom_TotalDeducciones = $rowwrk["Nom_TotalDeducciones"];
	$Nom_TotalOtrosPagos = $rowwrk["Nom_TotalOtrosPagos"];
	$Nom_OrigenRecurso = $rowwrk["Nom_OrigenRecurso"];
	$Nom_MontoRecursoPropio = $rowwrk["Nom_MontoRecursoPropio"];
}
$tot_per += $Nom_TotalPercepciones;
$tot_ded += $Nom_TotalDeducciones;
$tot_otr += $Nom_TotalOtrosPagos;	
?>			
			<td align="right">$ <?php echo number_format($Nom_TotalPercepciones,2); ?></td>
			<td align="right">$ <?php echo number_format($Nom_TotalDeducciones,2); ?></td>
			<td align="right">$ <?php echo number_format($Nom_TotalOtrosPagos,2); ?></td>
			<!--<td><button class="btn btn-sm btn-soft-info">Trade Now</button></td>-->
		</tr>
<?php
	}
?>	
	
		<tr>
			<th><a href="javascript:void(0);">Totales</a></th>
			<td align="right"><b><?php echo number_format($tot_rep,0); ?></b></td>
			<td align="right"><b><?php echo number_format($tot_ser,0); ?></b></td>
			<td align="right"><b><?php echo number_format($tot_com,0); ?></b></td>
			<td align="right"><b>$ <?php echo number_format($tot_per,2); ?></b></td>
			<td align="right"><b>$ <?php echo number_format($tot_ded,2); ?></b></td>
			<td align="right"><b>$ <?php echo number_format($tot_otr,2); ?></b></td>
			<!--<td align="right">&nbsp;</td>-->
		</tr>
<?php
}
@phpmkr_free_result($rs);
?>
		</tbody>
	</table>
</div>										
										
									</div><!--end card-body-->
								</div><!--end card-->
							</div><!--end col-->
                            <div class="col-xxl-3">
                                <div class="card card-height-100">
                                <div class="card-header align-items-center d-flex">
                                    <h4 class="card-title mb-0 flex-grow-1">Percepciones, Deducciones, Otros Pagos</h4>
                                    <div class="flex-shrink-0">
                                        <!--<div class="dropdown card-header-dropdown">
                                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted fs-16"><i
                                                        class="mdi mdi-dots-vertical align-middle"></i></span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#">Today</a>
                                                <a class="dropdown-item" href="#">Last Week</a>
                                                <a class="dropdown-item" href="#">Last Month</a>
                                                <a class="dropdown-item" href="#">Current Year</a>
                                            </div>
                                        </div>-->
                                    </div>
                                </div><!-- end card header -->
<?php		
$Nom_TotalPercepciones = 0;
$Nom_TotalDeducciones = 0;
$Nom_TotalOtrosPagos = 0;
$Nom_OrigenRecurso = 0;
$Nom_MontoRecursoPropio = 0;
$sSqlWrk = "
SELECT
SUM(Vit_Nominas.Nom_TotalPercepciones) AS Nom_TotalPercepciones,
SUM(Vit_Nominas.Nom_TotalDeducciones) AS Nom_TotalDeducciones,
SUM(Vit_Nominas.Nom_TotalOtrosPagos) AS Nom_TotalOtrosPagos,
SUM(Vit_Nominas.Nom_OrigenRecurso) AS Nom_OrigenRecurso,
SUM(Vit_Nominas.Nom_MontoRecursoPropio) AS Nom_MontoRecursoPropio
FROM
Vit_Nominas
INNER JOIN vit_comprobantes ON Vit_Nominas.Cfdi_ID = vit_comprobantes.Cfdi_ID
WHERE vit_comprobantes.Cfdi_Retcode = '1' 
AND YEAR(Vit_Nominas.Nom_FechaPago) = '2022'
AND MONTH(Vit_Nominas.Nom_FechaPago) = '10'
";
if(@$_SESSION["project1_status_Municipio"] != ""){
$sSqlWrk .= "AND vit_comprobantes.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
}
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$Nom_TotalPercepciones = $rowwrk["Nom_TotalPercepciones"];
	$Nom_TotalDeducciones = $rowwrk["Nom_TotalDeducciones"];
	$Nom_TotalOtrosPagos = $rowwrk["Nom_TotalOtrosPagos"];
	$Nom_OrigenRecurso = $rowwrk["Nom_OrigenRecurso"];
	$Nom_MontoRecursoPropio = $rowwrk["Nom_MontoRecursoPropio"];
}	
?>								
                                <div class="card-body">
                                    <div id="user_device_pie_charts" data-colors='["--vz-primary", "--vz-warning", "--vz-info"]' class="apex-charts" dir="ltr"></div>

                                    <div class="table-responsive mt-3">
                                        <table
                                            class="table table-borderless table-sm table-centered align-middle table-nowrap mb-0">
                                            <tbody class="border-0">
                                                <tr>
                                                    <td>
                                                        <h4 class="text-truncate fs-14 fs-medium mb-0"><i class="ri-stop-fill align-middle fs-18 text-primary me-2"></i>Total Percepciones</h4>
                                                    </td>
                                                    <td>
                                                        <p class="text-muted mb-0"><i class="bx bx-dollar"></i>
														<?php echo number_format($Nom_TotalPercepciones,2); ?>														
														</p>
                                                    </td>
												</tr>
                                                <tr>
                                                    <td>
                                                        <h4 class="text-truncate fs-14 fs-medium mb-0"><i class="ri-stop-fill align-middle fs-18 text-warning me-2"></i>Total Deducciones</h4>
                                                    </td>
                                                    <td>
                                                        <p class="text-muted mb-0"><i class="bx bx-dollar"></i><?php echo number_format($Nom_TotalDeducciones,2); ?></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h4 class="text-truncate fs-14 fs-medium mb-0"><i class="ri-stop-fill align-middle fs-18 text-info me-2"></i>Total Otros Pagos</h4>
                                                    </td>
                                                    <td>
                                                        <p class="text-muted mb-0"><i class="bx bx-dollar"></i><?php echo number_format($Nom_TotalOtrosPagos,2); ?></p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div><!-- end card body -->
                            </div><!-- end card -->
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
		<!--<script src="assets/js/pages/dashboard-analytics.init.js"></script>-->
<script type="text/javascript">	
(function () {
	("use strict");
	
		function getChartColorsArray(chartId) {
			if (document.getElementById(chartId) !== null) {
				var colors = document.getElementById(chartId).getAttribute("data-colors");
				if (colors) {
					colors = JSON.parse(colors);
					return colors.map(function (value) {
						var newValue = value.replace(" ", "");
						if (newValue.indexOf(",") === -1) {
							var color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
							if (color) return color;
							else return newValue;;
						} else {
							var val = value.split(',');
							if (val.length == 2) {
								var rgbaColor = getComputedStyle(document.documentElement).getPropertyValue(val[0]);
								rgbaColor = "rgba(" + rgbaColor + "," + val[1] + ")";
								return rgbaColor;
							} else {
								return newValue;
							}
						}
					});
				} else {
					console.warn('data-colors atributes not found on', chartId);
				}
			}
		}
		// User by devices
		var dountchartUserDeviceColors = getChartColorsArray("user_device_pie_charts");
		if (dountchartUserDeviceColors) {
		var options = {
			series: [<?php echo number_format($Nom_TotalPercepciones/1000000,0); ?>, <?php echo number_format($Nom_TotalDeducciones/1000000,0); ?>, <?php echo number_format($Nom_TotalOtrosPagos/1000000,0); ?>],
			/*series: [78.56, 105.02, 42.89],*/
			labels: ["Percepciones", "Deducciones", "Otros pagos"],
			chart: {
				type: "donut",
				height: 219,
			},
			plotOptions: {
				pie: {
					size: 100,
					donut: {
						size: "76%",
					},
				},
			},
			dataLabels: {
				enabled: false,
			},
			legend: {
				show: false,
				position: 'bottom',
				horizontalAlign: 'center',
				offsetX: 0,
				offsetY: 0,
				markers: {
					width: 20,
					height: 6,
					radius: 2,
				},
				itemMargin: {
					horizontal: 12,
					vertical: 0
				},
			},
			stroke: {
				width: 0
			},
			yaxis: {
				labels: {
					formatter: function (value) {
						return "$" + value + "" + " ";
					}
				},
				tickAmount: 4,
				min: 0
			},
			colors: dountchartUserDeviceColors,
		};
		var chart = new ApexCharts(document.querySelector("#user_device_pie_charts"), options);
		chart.render();
		}
})();	
</script>        
        <!-- App js -->
        <script src="assets/js/app.js"></script>
    </body>

</html>