<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>

<head>

    <title>Datatables | Velzon - Admin & Dashboard Template</title>
    <?php include 'layouts/title-meta.php'; ?>

    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

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
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Scroll - Vertical</h5>
                            </div>
                            <div class="card-body">
                                <table id="scroll-vertical"
                                    class="table table-bordered dt-responsive nowrap align-middle mdl-data-table"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Project</th>
                                            <th>Task</th>
                                            <th>Client Name</th>
                                            <th>Assigned To</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                            <th>Priority</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>VLZ-452</td>
                                            <td>Symox v1.0.0</td>
                                            <td><a href="#!">Add Dynamic Contact List</a></td>
                                            <td>RH Nichols</td>
                                            <td>
                                                <div class="avatar-group">
                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-3.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-3.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>03 Oct, 2021</td>
                                            <td><span class="badge badge-soft-info">Re-open</span></td>
                                            <td><span class="badge bg-danger">High</span></td>
                                        </tr>
                                        <tr>
                                            <td>VLZ-453</td>
                                            <td>Doot - Chat App Template</td>
                                            <td><a href="#!">Additional Calendar</a></td>
                                            <td>Diana Kohler</td>
                                            <td>
                                                <div class="avatar-group">
                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-4.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-4.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>

                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-5.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-5.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>05 Oct, 2021</td>
                                            <td><span class="badge badge-soft-secondary">On-Hold</span></td>
                                            <td><span class="badge bg-info">Medium</span></td>
                                        </tr>
                                        <tr>
                                            <td>VLZ-454</td>
                                            <td>Qexal - Landing Page</td>
                                            <td><a href="#!">Make a creating an account profile</a></td>
                                            <td>David Nichols</td>
                                            <td>
                                                <div class="avatar-group">
                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-6.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-6.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>

                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-7.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-7.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>

                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-8.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-8.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>27 April, 2022</td>
                                            <td><span class="badge badge-soft-danger">Closed</span></td>
                                            <td><span class="badge bg-success">Low</span></td>
                                        </tr>
                                        <tr>
                                            <td>VLZ-455</td>
                                            <td>Dorsin - Landing Page</td>
                                            <td><a href="#!">Apologize for shopping Error!</a></td>
                                            <td>Tonya Noble</td>
                                            <td>
                                                <div class="avatar-group">
                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-6.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-6.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>

                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-7.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-7.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>14 June, 2021</td>
                                            <td><span class="badge badge-soft-warning">Inprogress</span></td>
                                            <td><span class="badge bg-info">Medium</span></td>
                                        </tr>
                                        <tr>
                                            <td>VLZ-456</td>
                                            <td>Minimal - v2.1.0</td>
                                            <td><a href="#!">Support for theme</a></td>
                                            <td>Donald Palmer</td>
                                            <td>
                                                <div class="avatar-group">
                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-2.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-2.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>25 June, 2021</td>
                                            <td><span class="badge badge-soft-danger">Closed</span></td>
                                            <td><span class="badge bg-success">Low</span></td>
                                        </tr>
                                        <tr>
                                            <td>VLZ-457</td>
                                            <td>Dason - v1.0.0</td>
                                            <td><a href="#!">Benner design for FB & Twitter</a></td>
                                            <td>Jennifer Carter</td>
                                            <td>
                                                <div class="avatar-group">
                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-5.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-5.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>

                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-6.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-6.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>

                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-7.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-7.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>

                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-8.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-8.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>14 Aug, 2021</td>
                                            <td><span class="badge badge-soft-warning">Inprogress</span></td>
                                            <td><span class="badge bg-info">Medium</span></td>
                                        </tr>
                                        <tr>
                                            <td>VLZ-458</td>
                                            <td>Velzon v1.6.0</td>
                                            <td><a href="#!">Add datatables</a></td>
                                            <td>James Morris</td>
                                            <td>
                                                <div class="avatar-group">
                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-4.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-4.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>

                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-5.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-5.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>12 March, 2022</td>
                                            <td><span class="badge badge-soft-primary">Open</span></td>
                                            <td><span class="badge bg-danger">High</span></td>
                                        </tr>
                                        <tr>
                                            <td>VLZ-460</td>
                                            <td>Skote v2.0.0</td>
                                            <td><a href="#!">Support for theme</a></td>
                                            <td>Nancy Martino</td>
                                            <td>
                                                <div class="avatar-group">
                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-3.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-3.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>

                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-10.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-10.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>

                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-9.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-9.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>28 Feb, 2022</td>
                                            <td><span class="badge badge-soft-secondary">On-Hold</span></td>
                                            <td><span class="badge bg-success">Low</span></td>
                                        </tr>
                                        <tr>
                                            <td>VLZ-461</td>
                                            <td>Velzon v1.0.0</td>
                                            <td><a href="#!">Form submit issue</a></td>
                                            <td>Grace Coles</td>
                                            <td>
                                                <div class="avatar-group">
                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-5.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-5.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>

                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-9.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-9.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>

                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-10.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-10.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>07 Jan, 2022</td>
                                            <td><span class="badge badge-soft-success">New</span></td>
                                            <td><span class="badge bg-danger">High</span></td>
                                        </tr>
                                        <tr>
                                            <td>VLZ-462</td>
                                            <td>Minimal - v2.2.0</td>
                                            <td><a href="#!">Edit customer testimonial</a></td>
                                            <td>Freda</td>
                                            <td>
                                                <div class="avatar-group">
                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-2.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-2.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>16 Aug, 2021</td>
                                            <td><span class="badge badge-soft-danger">Closed</span></td>
                                            <td><span class="badge bg-info">Medium</span></td>
                                        </tr>
                                        <tr>
                                            <td>VLZ-454</td>
                                            <td>Qexal - Landing Page</td>
                                            <td><a href="#!">Make a creating an account profile</a></td>
                                            <td>David Nichols</td>
                                            <td>
                                                <div class="avatar-group">
                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-6.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-6.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>

                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-7.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-7.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>

                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-8.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-8.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>27 April, 2022</td>
                                            <td><span class="badge badge-soft-danger">Closed</span></td>
                                            <td><span class="badge bg-success">Low</span></td>
                                        </tr>
                                        <tr>
                                            <td>VLZ-455</td>
                                            <td>Dorsin - Landing Page</td>
                                            <td><a href="#!">Apologize for shopping Error!</a></td>
                                            <td>Tonya Noble</td>
                                            <td>
                                                <div class="avatar-group">
                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-6.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-6.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>

                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-7.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-7.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>14 June, 2021</td>
                                            <td><span class="badge badge-soft-warning">Inprogress</span></td>
                                            <td><span class="badge bg-info">Medium</span></td>
                                        </tr>
                                        <tr>
                                            <td>VLZ-456</td>
                                            <td>Minimal - v2.1.0</td>
                                            <td><a href="#!">Support for theme</a></td>
                                            <td>Donald Palmer</td>
                                            <td>
                                                <div class="avatar-group">
                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-2.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-2.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>25 June, 2021</td>
                                            <td><span class="badge badge-soft-danger">Closed</span></td>
                                            <td><span class="badge bg-success">Low</span></td>
                                        </tr>
                                        <tr>
                                            <td>VLZ-457</td>
                                            <td>Dason - v1.0.0</td>
                                            <td><a href="#!">Benner design for FB & Twitter</a></td>
                                            <td>Jennifer Carter</td>
                                            <td>
                                                <div class="avatar-group">
                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-5.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-5.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>

                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-6.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-6.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>

                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-7.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-7.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>

                                                    <a href="javascript: void(0);" class="avatar-group-item"
                                                        data-img="avatar-8.jpg" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Username">
                                                        <img src="assets/images/users/avatar-8.jpg" alt=""
                                                            class="rounded-circle avatar-xxs">
                                                    </a>
                                                </div>
                                            </td>
                                            <td>14 Aug, 2021</td>
                                            <td><span class="badge badge-soft-warning">Inprogress</span></td>
                                            <td><span class="badge bg-info">Medium</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!--datatable js-->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="assets/js/pages/datatables.init.js"></script>
<!-- App js -->
<script src="assets/js/app.js"></script>

</body>

</html>