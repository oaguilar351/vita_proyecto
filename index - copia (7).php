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
				<th><a href="javascript:void(0);">Municipio</a></th>
				<th><a href="javascript:void(0);">Emisor</a></th>
				<th><a href="javascript:void(0);">Ejercicio</a></th>
				<th><a href="javascript:void(0);">Periodo</a></th>
				<!--<th><a href="javascript:void(0);">Total Cargo</a></th>-->
				<th><a href="javascript:void(0);"> Pagado</a></th>
				<th><a href="javascript:void(0);"> Tmbrado</a></th>
				<th><a href="javascript:void(0);"> Recurso Propio</a></th>
				<th><a href="javascript:void(0);"> Recurso Federal</a></th>
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
Vit_Municipios.Mun_Descrip,
Vit_Emisor.Emi_Nombre,
Vit_Emisor.Emi_NomCorto, 
vit_informe.inf_ejercicio,
vit_informe.inf_periodo,
vit_informe.inf_totalCargo,
vit_informe.inf_totalPagado,
vit_informe.inf_totalTimbrado,
vit_informe.inf_totalPropio,
vit_informe.inf_totalFederal,
vit_informe.inf_totalEmitidos,
vit_informe.inf_totalCorrectos,
vit_informe.inf_totalCancelados,
vit_informe.inf_obsValidacion
FROM
vit_informe
INNER JOIN Vit_Municipios ON vit_informe.Mun_ID = Vit_Municipios.Mun_ID
INNER JOIN Vit_Emisor ON vit_informe.Emi_RFC = Vit_Emisor.Emi_RFC
";
#echo "<br />sSql: ".$sSql;
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
if ($rs) {
	
	while ($row = phpmkr_fetch_array($rs)) {
		
		$inf_totalCargo = $row["inf_totalCargo"];
		$inf_totalPagado = $row["inf_totalPagado"];
		$inf_totalTimbrado = $row["inf_totalTimbrado"];
		$inf_totalPropio = $row["inf_totalPropio"];
		$inf_totalFederal = $row["inf_totalFederal"];
		$inf_totalEmitidos = $row["inf_totalEmitidos"];
		$inf_totalCorrectos = $row["inf_totalCorrectos"];
		$inf_totalCancelados = $row["inf_totalCancelados"];
?>
		<tr>
			<td><?php echo strtoupper($row["Mun_Descrip"]); ?></td>
			<td><?php echo strtoupper($row["Emi_NomCorto"]); ?></td>
			<td><?php echo ($row["inf_ejercicio"]); ?></td>
			<td><?php echo ($row["inf_periodo"]); ?></td>
			<!--<td align="right">$ <?php echo number_format($inf_totalCargo,2); ?></td>-->
			<td align="right">$ <?php echo number_format($inf_totalPagado,2); ?></td>
			<td align="right">$ <?php echo number_format($inf_totalTimbrado,2); ?></td>
			<td align="right">$ <?php echo number_format($inf_totalPropio,2); ?></td>
			<td align="right">$ <?php echo number_format($inf_totalFederal,2); ?></td>
			<td align="right"><?php echo number_format($inf_totalEmitidos,0); ?></td>
			<td align="right"><?php echo number_format($inf_totalCorrectos,0); ?></td>
			<td align="right"><?php echo number_format($inf_totalCancelados,0); ?></td>
		</tr>
<?php
	}
?>
		<tr>
			<th><a href="javascript:void(0);">Totales</a></th>
			<td align="right"><b>&nbsp;</b></td>
			<td align="right"><b>&nbsp;</b></td>
			<td align="right"><b>&nbsp;</b></td>
			<!--<td align="right"><b>$ <?php echo number_format($tot_ser,2); ?></b></td>-->
			<td align="right"><b>$ <?php echo number_format($tot_com,2); ?></b></td>
			<td align="right"><b>$ <?php echo number_format($tot_rep,2); ?></b></td>
			<td align="right"><b>$ <?php echo number_format($tot_ser,2); ?></b></td>
			<td align="right"><b>$ <?php echo number_format($tot_com,2); ?></b></td>
			<td align="right"><b><?php echo number_format($tot_per,0); ?></b></td>
			<td align="right"><b><?php echo number_format($tot_ded,0); ?></b></td>
			<td align="right"><b><?php echo number_format($tot_otr,0); ?></b></td>
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