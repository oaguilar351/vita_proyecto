<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index.php" class="logo logo-dark">
            <span class="logo-sm">
                <img src="assets/images/logoH.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="assets/images/logoH.png" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.php" class="logo logo-light">
            <span class="logo-sm">
                <img src="assets/images/logoV.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="assets/images/logoH.png" alt="" height="50">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>Menu Principal</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="las la-table"></i> <span>Modulos</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
							<?php if (@$_SESSION["project1_status"] == "login") { ?>
                            <li class="nav-item">
                                <a href="comprobantes_listado.php" class="nav-link">Comprobantes</a>
                            </li>
							<?php } ?>
							<?php if (@$_SESSION["project1_status"] == "login") { ?>
                            <li class="nav-item">
                                <a href="receptores_listado.php" class="nav-link">Receptores</a>
                            </li>
							<?php } ?>
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->                

                

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>