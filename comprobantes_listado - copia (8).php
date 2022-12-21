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
        
        <title>Comprobentes | VitaInsumos</title>
        <?php include 'layouts/title-meta.php'; ?>
        <link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
		<!--datatable css-->
		<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
		<!--datatable responsive css-->
		<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

		<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">


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
											
											<div class="col-sm-auto ms-auto">
												
												<div class="hstack gap-2"> 
													<?php if(@$_SESSION["vit_comprobantes_OrderBy"]!=""){ ?>
													<a class="btn btn-light" href="comprobantes_listado.php?cmd=resetsort" title="Quitar Orden"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>X</a>
													<?php } ?>											
													<?php if(@$sSrchAdvanced!="" && @$_SESSION["vit_comprobantes_OrderBy"]==""){ ?>
													<a class="btn btn-light" href="comprobantes_listado.php?cmd=reset" title="Quitar Filtros"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>F</a>
													<?php } ?>
													<button type="button" class="btn btn-info" data-bs-toggle="offcanvas"
														href="#offcanvasExample"><i class="ri-filter-3-line align-bottom me-1" title="Elegir Filtros"></i> Filtros</button>                                                
												</div>
											</div>
										</div>
									</div>
								   <div class="card-body">
										<table id="comprobantes" class="display table table-bordered" style="width:100%">
											<thead>
												<tr>
													<th>Version&nbsp;(*)</th>
													<th>Serie&nbsp;(*)</th>
													<th>Moneda&nbsp;(*)</th>
													<th>Emisor</th>
													<th>Receptor</th>
													<th>UUID&nbsp;(*)</th>
													<th>Status</th>
												</tr>
											</thead>
										</table>
									</div> 
								</div>
							</div>
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
		
		<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>-->
		<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

		<!--datatable js-->					 
		<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
		<!--<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>-->
		<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
		<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

		<!--<script src="assets/js/pages/datatables.init.js"></script>-->
        <script>
		$(document).ready(function(){
			$.fn.dataTable.ext.errMode = 'none';
			$('#comprobantes').dataTable( {
				"bProcessing": true,
				"bServerSide": true,
				"sAjaxSource": "serverside/seversideComprobantes.php", 
				"columnDefs":[{
					"data":null
				}]
			});	
			/*$("#comprobantes_datatables").DataTable({
				"processing": true,
				"serverSide": true,
				"sAjaxSource": 'severside.php'
			});	*/
		});
		</script>
        <!-- App js -->
        <!--<script src="assets/js/app.js"></script>-->
    </body>

</html>