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
					$sSqlWrkS = "
					SELECT
					vit_seccion.Sec_SeccionID,
					vit_seccion.Sec_Descripcion, 
					vit_seccion.Sec_Icon
					FROM
					vit_seccion 
					WHERE vit_seccion.Sec_Status = 'A'
					";
					#echo "<br />".$sSqlWrkS;
					$rswrkS = phpmkr_query($sSqlWrkS,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrkS);
					if ($rswrkS) {
						while ($datawrkS = phpmkr_fetch_array($rswrkS)) {
					?>
						<a class="nav-link menu-link" href="#<?php echo $datawrkS["Sec_Descripcion"]; ?>" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
							<i class="las la-<?php echo $datawrkS["Sec_Icon"]; ?>"></i> <span><?php echo $datawrkS["Sec_Descripcion"]; ?></span>
						</a>
						<div class="collapse menu-dropdown" id="<?php echo $datawrkS["Sec_Descripcion"]; ?>">
							<ul class="nav nav-sm flex-column">
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
								AND Sec_SeccionID = '".$datawrkS["Sec_SeccionID"]."'
								";
								if(@$_SESSION["project1_status_Perfil"] != ""){
								$sSqlWrkM .= "AND vit_perfil_acceso.Per_Perfil_ID = '".@$_SESSION["project1_status_Perfil"]."' ";
								}
								#echo "<br />".$sSqlWrkM;
								$rswrkM = phpmkr_query($sSqlWrkM,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrkM);
								if ($rswrkM) {
									while ($datawrkM = phpmkr_fetch_array($rswrkM)) {
								?>
									<li class="nav-item">
										<a href="<?php echo $datawrkM["Mod_Ruta"]; ?>" class="nav-link"><?php echo $datawrkM["Mod_Descripcion"]; ?></a>
									</li>
								<?php		
									}								
								}												
								?>                           
								
							</ul>
						</div>
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