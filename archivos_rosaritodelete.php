<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php
if (@$_SESSION["project1_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
?>
<?php

// Initialize common variables
$x_arc_archivoID = Null; 
$ox_arc_archivoID = Null;
$x_arc_archivoRuta = Null; 
$ox_arc_archivoRuta = Null;
$fs_x_arc_archivoRuta = 0;
$fn_x_arc_archivoRuta = "";
$ct_x_arc_archivoRuta = "";
$w_x_arc_archivoRuta = 0;
$h_x_arc_archivoRuta = 0;
$a_x_arc_archivoRuta = "";
$x_Mun_ID = Null; 
$ox_Mun_ID = Null;
$x_Emi_RFC = Null; 
$ox_Emi_RFC = Null;
$x_arc_fechaCarga = Null; 
$ox_arc_fechaCarga = Null;
$x_arc_fechaAct = Null; 
$ox_arc_fechaAct = Null;
$x_arc_usuarioReg = Null; 
$ox_arc_usuarioReg = Null;
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/phpmkrfn.php") ?>
<?php
$arRecKey = Null;

// Load Key Parameters
$sKey = "";
$bSingleDelete = true;
$x_arc_archivoID = @$_GET["arc_archivoID"];
if (!empty($x_arc_archivoID)) {
	if ($sKey <> "") { $sKey .= ","; }
	$sKey .= $x_arc_archivoID;
}else{
	$bSingleDelete = false;
}
if (!$bSingleDelete) {
	$sKey = @$_POST["key_d"];
}
if (!is_array($sKey)) {
	if (strlen($sKey) > 0) {	
		$arRecKey = explode(",", $sKey);
	}
}else {
	$sKey = implode(",", $sKey);
	$arRecKey = explode(",", $sKey);
}
if (count($arRecKey) <= 0) {
	ob_end_clean();
	header("Location: archivos_listado.php");
	exit();
}
$sKey = implode(",", $arRecKey);
$i = 0;
$sDbWhere = "";
$sDbWhere2 = "";
while ($i < count($arRecKey)){
	$sDbWhere .= "(";
	$sDbWhere2 .= "(";
	// Remove spaces
	$sRecKey = trim($arRecKey[$i+0]);
	$sRecKey = (!(function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())) ? addslashes($sRecKey) : $sRecKey ;

	// Build the SQL
	$sDbWhere .= "`arc_archivoID`=" . $sRecKey . " AND ";
	$sDbWhere2 .= "`arcdet_archivoID`=" . $sRecKey . " AND ";
	if (substr($sDbWhere, -5) == " AND ") { $sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5) . ") OR "; }
	if (substr($sDbWhere2, -5) == " AND ") { $sDbWhere2 = substr($sDbWhere2, 0, strlen($sDbWhere2)-5) . ") OR "; }
	$i += 1;
}
if (substr($sDbWhere, -4) == " OR ") { $sDbWhere = substr($sDbWhere, 0 , strlen($sDbWhere)-4); }
if (substr($sDbWhere2, -4) == " OR ") { $sDbWhere2 = substr($sDbWhere2, 0 , strlen($sDbWhere2)-4); }

// Get action
$sAction = @$_POST["a_delete"];
if (($sAction == "") || ((is_null($sAction)))) {
	$sAction = "I";	// Display with input box
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Display
		if (LoadRecordCount($sDbWhere,$conn) <= 0) {
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: archivos_listado.php");
			exit();
		}
		break;
	case "D": // Delete
		if (DeleteData($sDbWhere,$sDbWhere2,$conn)) {
			$_SESSION["ewmsg"] = "Registro Eliminado";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: archivos_listado.php");
			exit();
		}
		break;
}
?>
<head>
        
        <title>Recibos | VitaInsumos</title>
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
                                <h4 class="mb-sm-0">Recibos - Eliminar Archivo</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Modulos</a></li>
                                        <li class="breadcrumb-item active">Recibos</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-lg-12">
                            <div class="card" id="leadsList">
                                <div class="card-header border-0">

                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm-auto ms-auto">
										
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <div class="table-responsive table-card">
<!--<p><span class="phpmaker">Delete from TABLE: Rosarito Archivos<br><br><a href="archivos_rosaritolist.php">Back to List</a></span></p>-->
<form action="archivos_rosaritodelete.php" method="post">
<?php $sKey = ((function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())) ? stripslashes($sKey) : $sKey; ?>
<input type="hidden" name="key_d" value="<?php echo htmlspecialchars($sKey); ?>">
<table class="table align-middle" id="customerTable">
	<thead class="table-light">
		<tr>
		<th valign="top"><span><a href="javascript:void(0);">ID</a></span></th>
		<th valign="top"><span><a href="javascript:void(0);">Archivo</a></span></th>
		<th valign="top"><span><a href="javascript:void(0);">Municipio</a></span></th>
		<th valign="top"><span><a href="javascript:void(0);">Emisor</a></span></th>
		<th valign="top"><span><a href="javascript:void(0);">Fecha Carga</a></span></th>
		<th valign="top"><span><a href="javascript:void(0);">Fecha Act</a></span></th>
		<th valign="top"><span><a href="javascript:void(0);">Usuario Reg</a></span></th>
		</tr>
	</thead>
	<tbody class="list form-check-all">
<?php
$nRecCount = 0;
$i = 0;
while ($i < count($arRecKey)) {
	$nRecCount++;

	// Set row color
	$sItemRowClass = " bgcolor=\"#FFFFFF\"";

	// Display alternate color for rows
	if ($nRecCount % 2 <> 0) {
		$sItemRowClass = " bgcolor=\"#F5F5F5\"";
	}
	$sRecKey = trim($arRecKey[$i+0]);
	$sRecKey = ((function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())) ? stripslashes($sRecKey) : $sRecKey;
	$x_arc_archivoID = $sRecKey;
	if (LoadData($conn)) {
?>
	<tr<?php echo $sItemRowClass;?>>
		<td><span class="phpmaker">
<?php echo $x_arc_archivoID; ?>
</span></td>
		<td><span class="phpmaker">
<?php if ((!is_null($x_arc_archivoRuta)) &&  $x_arc_archivoRuta <> "") { ?>
<a href="<?php echo ewUploadPath(0) . $x_arc_archivoRuta ?>" target="_blank"><?php echo $x_arc_archivoRuta; ?></a>
<?php } ?>
</span></td>
		<td><span class="phpmaker">
<?php
if ((!is_null($x_Mun_ID)) && ($x_Mun_ID <> "")) {
	$sSqlWrk = "SELECT `Mun_Descrip` FROM `Vit_Municipios`";
	$sTmp = $x_Mun_ID;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `Mun_ID` = " . $sTmp . "";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["Mun_Descrip"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_Mun_ID = $x_Mun_ID; // Backup Original Value
$x_Mun_ID = $sTmp;
?>
<?php echo $x_Mun_ID; ?>
<?php $x_Mun_ID = $ox_Mun_ID; // Restore Original Value ?>
</span></td>
		<td><span class="phpmaker">
<?php
if ((!is_null($x_Emi_RFC)) && ($x_Emi_RFC <> "")) {
	$sSqlWrk = "SELECT `Emi_Nombre` FROM `Vit_Emisor`";
	$sTmp = $x_Emi_RFC;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `Emi_RFC` = '" . $sTmp . "'";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["Emi_Nombre"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_Emi_RFC = $x_Emi_RFC; // Backup Original Value
$x_Emi_RFC = $sTmp;
?>
<?php echo $x_Emi_RFC; ?>
<?php $x_Emi_RFC = $ox_Emi_RFC; // Restore Original Value ?>
</span></td>
		<td><span class="phpmaker">
<?php echo FormatDateTime($x_arc_fechaCarga,5); ?>
</span></td>
		<td><span class="phpmaker">
<?php echo FormatDateTime($x_arc_fechaAct,5); ?>
</span></td>
		<td><span class="phpmaker">
<?php
if ((!is_null($x_arc_usuarioReg)) && ($x_arc_usuarioReg <> "")) {
	$sSqlWrk = "SELECT `Vit_Nombre` FROM `vit_usuarios`";
	$sTmp = $x_arc_usuarioReg;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `Vit_Usuario` = '" . $sTmp . "'";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["Vit_Nombre"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_arc_usuarioReg = $x_arc_usuarioReg; // Backup Original Value
$x_arc_usuarioReg = $sTmp;
?>
<?php echo $x_arc_usuarioReg; ?>
<?php $x_arc_usuarioReg = $ox_arc_usuarioReg; // Restore Original Value ?>
</span></td>
	</tr>
<?php
	}
	$i += 1;
}
?>
		<tr>
		<td><span class="phpmaker">&nbsp;</span></td>
		<td><span class="phpmaker">&nbsp;</span></td>
		<td><span class="phpmaker">&nbsp;</span></td>
		<td><span class="phpmaker">&nbsp;</span></td>
		<td><span class="phpmaker">&nbsp;</span></td>
		<td><span class="phpmaker">&nbsp;</span></td>
		<td align="right"><span class="phpmaker">
		<a class="btn btn-primary waves-effect waves-light" href="archivos_listado.php">Cancelar</a>
		<button type="submit" name="Action" class="btn btn-success waves-effect waves-light" value="">CONFIRMAR ELIMINAR</button>
		<input type="hidden" name="a_delete" value="D"></span></td>
		</tr>
	</tbody>
</table>
</form>
</div>
	<div class="d-flex justify-content-end mt-3">
	<div class="pagination-wrap hstack gap-2">
	</div>
                                        </div>
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
        <!--<script src="assets/js/app.js"></script>-->
    </body>

</html>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($conn)
{
	global $x_arc_archivoID;
	$sSql = "SELECT * FROM `archivos_rosarito`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())) ? stripslashes($x_arc_archivoID) : $x_arc_archivoID;
	$sWhere .= "(`arc_archivoID` = " . addslashes($sTmp) . ")";
	$sSql .= " WHERE " . $sWhere;
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	if ($sOrderBy <> "") {
		$sSql .= " ORDER BY " . $sOrderBy;
	}
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$bLoadData = false;
	}else{
		$bLoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		$GLOBALS["x_arc_archivoID"] = $row["arc_archivoID"];
		$GLOBALS["x_arc_archivoRuta"] = $row["arc_archivoRuta"];
		$GLOBALS["x_Mun_ID"] = $row["Mun_ID"];
		$GLOBALS["x_Emi_RFC"] = $row["Emi_RFC"];
		$GLOBALS["x_arc_fechaCarga"] = $row["arc_fechaCarga"];
		$GLOBALS["x_arc_fechaAct"] = $row["arc_fechaAct"];
		$GLOBALS["x_arc_usuarioReg"] = $row["arc_usuarioReg"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadRecordCount
// - Load Record Count based on input sql criteria sqlKey

function LoadRecordCount($sqlKey,$conn)
{
	global $x_arc_archivoID;
	$sSql = "SELECT * FROM `archivos_rosarito`";
	$sSql .= " WHERE " . $sqlKey;
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sGroupBy <> "") {
		$sSql .= " GROUP BY " . $sGroupBy;
	}
	if ($sHaving <> "") {
		$sSql .= " HAVING " . $sHaving;
	}
	if ($sOrderBy <> "") {
		$sSql .= " ORDER BY " . $sOrderBy;
	}
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return phpmkr_num_rows($rs);
	phpmkr_free_result($rs);
}
?>
<?php

//-------------------------------------------------------------------------------
// Function DeleteData
// - Delete Records based on input sql criteria sqlKey

function DeleteData($sqlKey,$sqlKey2,$conn)
{
	global $x_arc_archivoID;
	$sSql = "Delete FROM `archivos_rosarito`";
	$sSql .= " WHERE " . $sqlKey;
	phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	
	$sSql = "Delete FROM `archivos_rosarito_percep_deduc`";
	$sSql .= " WHERE " . $sqlKey2;
	phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
	return true;
}
?>
