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
												<h5 class="card-title mb-0">Informe general</h5>
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
				<th><a href="javascript:void(0);">RFC</a></th>
				<th><a href="javascript:void(0);">Emisor</a></th>
				<th><a href="javascript:void(0);">Ejercicio</a></th>
				<th><a href="javascript:void(0);">Periodo</a></th>
				<th><a href="javascript:void(0);">Total Cargo</a></th>
				<th><a href="javascript:void(0);">Total Pagado</a></th>
				<th><a href="javascript:void(0);">Total Tmbrado</a></th>
				<th><a href="javascript:void(0);">Timbrado Recurso Propio</a></th>
				<th><a href="javascript:void(0);">Timbrado Recurso Federal</a></th>
				<th><a href="javascript:void(0);">Emitidos</a></th>
				<th><a href="javascript:void(0);">Correctos</a></th>
				<th><a href="javascript:void(0);">Cancelados</a></th>
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
			<td><?php echo strtoupper($row["Emi_RFC"]); ?></td>
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
			<td align="right">$ <?php echo number_format($Nom_TotalPercepciones,2); ?></td>
			<td align="right">$ <?php echo number_format($Nom_TotalDeducciones,2); ?></td>
			<td align="right">$ <?php echo number_format($Nom_TotalOtrosPagos,2); ?></td>
			
			<!--<td><button class="btn btn-sm btn-soft-info">Trade Now</button></td>-->
		</tr>
<?php
	}
?>	
	
		<tr>
			<td><a href="javascript:void(0);">Totales</a></td>
			<td align="right"><b>&nbsp;</b></td>
			<td align="right"><b><?php echo number_format($tot_rep,0); ?></b></td>
			<td align="right"><b><?php echo number_format($tot_ser,0); ?></b></td>
			<td align="right"><b><?php echo number_format($tot_com,0); ?></b></td>
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
    </body>

</html>