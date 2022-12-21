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
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
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
					<?php if (@$_SESSION["project1_status"] == "login") { ?>
					<?php
					$sSqlWrkM = "
					SELECT
					vit_modulos.Mod_Descripcion, 
					vit_modulos.Mod_Ruta,
					vit_perfil_acceso.Per_Perfil_ID
					FROM
					vit_perfil_acceso
					INNER JOIN vit_modulos ON vit_perfil_acceso.Mod_Modulo_ID = vit_modulos.Mod_Modulo_ID
					WHERE vit_perfil_acceso.Acc_Acceso_ID <> ''
					AND vit_modulos.Mod_Status = 'A'
					";
					if(@$_SESSION["project1_status_Perfil"] != ""){
					$sSqlWrkM .= "AND vit_perfil_acceso.Per_Perfil_ID = '".@$_SESSION["project1_status_Perfil"]."' ";
					}
					#echo "<br />".$sSqlWrkM;
					$rswrkM = phpmkr_query($sSqlWrkM,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrkM);
					if ($rswrkM) {
						while ($datawrkM = phpmkr_fetch_array($rswrkM)) {
					?>
						<a class="nav-link" href="<?php echo $datawrkM["Mod_Ruta"]; ?>">
							<i class="las la-table"></i> <span><?php echo $datawrkM["Mod_Descripcion"]; ?></span>
						</a>
					<?php		
						}								
					}
                    ?>                    
					<?php } ?>
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