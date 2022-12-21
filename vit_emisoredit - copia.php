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
$x_Emi_RFC = Null; 
$ox_Emi_RFC = Null;
$x_Emi_Nombre = Null; 
$ox_Emi_Nombre = Null;
$x_Emi_RegimenFiscal = Null; 
$ox_Emi_RegimenFiscal = Null;
$x_Emi_Clave = Null; 
$ox_Emi_Clave = Null;
$x_Emi_FacAtrAdquirente = Null; 
$ox_Emi_FacAtrAdquirente = Null;
$x_Emi_Curp = Null; 
$ox_Emi_Curp = Null;
$x_Emi_RegistroPatronal = Null; 
$ox_Emi_RegistroPatronal = Null;
$x_Emi_RfcPatronOrigen = Null; 
$ox_Emi_RfcPatronOrigen = Null;
$x_Emi_EntidadSNCF = Null; 
$ox_Emi_EntidadSNCF = Null;
$x_Mun_ID = Null; 
$ox_Mun_ID = Null;
$x_Emi_NomCorto = Null; 
$ox_Emi_NomCorto = Null;
$x_Emi_ArchivoKey = Null; 
$ox_Emi_ArchivoKey = Null;
$fs_x_Emi_ArchivoKey = 0;
$fn_x_Emi_ArchivoKey = "";
$ct_x_Emi_ArchivoKey = "";
$w_x_Emi_ArchivoKey = 0;
$h_x_Emi_ArchivoKey = 0;
$a_x_Emi_ArchivoKey = "";
$x_Emi_ArchivoCer = Null; 
$ox_Emi_ArchivoCer = Null;
$fs_x_Emi_ArchivoCer = 0;
$fn_x_Emi_ArchivoCer = "";
$ct_x_Emi_ArchivoCer = "";
$w_x_Emi_ArchivoCer = 0;
$h_x_Emi_ArchivoCer = 0;
$a_x_Emi_ArchivoCer = "";
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/phpmkrfn.php") ?>
<?php

// Load key from QueryString
$x_Emi_RFC = @$_GET["Emi_RFC"];

//if (!empty($x_Emi_RFC )) $x_Emi_RFC  = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Emi_RFC ) : $x_Emi_RFC ;
$x_Mun_ID = @$_GET["Mun_ID"];

//if (!empty($x_Mun_ID )) $x_Mun_ID  = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Mun_ID ) : $x_Mun_ID ;
// Get action

$sAction = @$_POST["a_edit"];
if (($sAction == "") || (is_null($sAction))) {
	$sAction = "I";	// Display with input box
} else {

	// Get fields from form
	$x_Emi_RFC = @$_POST["x_Emi_RFC"];
	$x_Emi_Nombre = @$_POST["x_Emi_Nombre"];
	$x_Emi_RegimenFiscal = @$_POST["x_Emi_RegimenFiscal"];
	$x_Emi_Clave = @$_POST["x_Emi_Clave"];
	$x_Emi_FacAtrAdquirente = @$_POST["x_Emi_FacAtrAdquirente"];
	$x_Emi_Curp = @$_POST["x_Emi_Curp"];
	$x_Emi_RegistroPatronal = @$_POST["x_Emi_RegistroPatronal"];
	$x_Emi_RfcPatronOrigen = @$_POST["x_Emi_RfcPatronOrigen"];
	$x_Emi_EntidadSNCF = @$_POST["x_Emi_EntidadSNCF"];
	$x_Mun_ID = @$_POST["x_Mun_ID"];
	$x_Emi_NomCorto = @$_POST["x_Emi_NomCorto"];
	$x_Emi_ArchivoKey = @$_POST["x_Emi_ArchivoKey"];
	$x_Emi_ArchivoCer = @$_POST["x_Emi_ArchivoCer"];
}

// Check if valid key
if (($x_Emi_RFC == "") || (is_null($x_Emi_RFC))) {
	ob_end_clean();
	header("Location: vit_emisorlist.php");
	exit();
}
if (($x_Mun_ID == "") || (is_null($x_Mun_ID))) {
	ob_end_clean();
	header("Location: vit_emisorlist.php");
	exit();
}
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
switch ($sAction)
{
	case "I": // Get a record to display
		if (!LoadData($conn)) { // Load Record based on key
			$_SESSION["ewmsg"] = "No records found";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: vit_emisorlist.php");
			exit();
		}
		break;
	case "U": // Update
		if (EditData($conn)) { // Update Record based on key
			$_SESSION["ewmsg"] = "Update Record Successful";
			phpmkr_db_close($conn);
			ob_end_clean();
			header("Location: vit_emisorlist.php");
			exit();
		}
		break;
}
?>
<script type="text/javascript" src="js/ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "-"; // set date separator	

//-->
</script>
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
if (EW_this.x_Emi_RFC && !EW_hasValue(EW_this.x_Emi_RFC, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_Emi_RFC, "TEXT", "Please enter required field - RFC"))
		return false;
}
if (EW_this.x_Mun_ID && !EW_hasValue(EW_this.x_Mun_ID, "SELECT" )) {
	if (!EW_onError(EW_this, EW_this.x_Mun_ID, "SELECT", "Please enter required field - Municipio"))
		return false;
}
return true;
}

//-->
</script>
<head>
        
        <title>Comprobentes | VitaInsumos</title>
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
                                <h4 class="mb-sm-0">Editar Emisor</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Modulos</a></li>
                                        <li class="breadcrumb-item active">Editar Emisor</li>
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
                                       
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <div class="table-responsive table-card">
<!--<p><span class="phpmaker">Edit TABLE: Emisor<br><br><a href="vit_emisorlist.php">Back to List</a></span></p>-->
<form name="vit_emisoredit" id="vit_emisoredit" action="vit_emisoredit.php" method="post" enctype="multipart/form-data" onSubmit="return EW_checkMyForm(this);">
<p>
<input type="hidden" name="a_edit" value="U">
<input type="hidden" name="EW_Max_File_Size" value="20000000">
<table class="table align-middle" id="customerTable">
	 <tr>
		<th><span class="phpmaker">RFC</span></th>
		<td bgcolor="#F5F5F5"><span class="phpmaker">
<?php echo $x_Emi_RFC; ?><input type="hidden" id="x_Emi_RFC" name="x_Emi_RFC" value="<?php echo htmlspecialchars(@$x_Emi_RFC); ?>">
</span></td>
	</tr>
	<tr>
		<th><span class="phpmaker">Nombre</span></th>
		<td bgcolor="#F5F5F5"><span class="phpmaker">
<input class="form-control" type="text" id="x_Emi_Nombre" name="x_Emi_Nombre" size="100" maxlength="100" value="<?php echo @$x_Emi_Nombre; ?>">
</span></td>
	</tr>
	<tr>
		<th><span class="phpmaker">Regimen Fiscal</span></th>
		<td bgcolor="#F5F5F5"><span class="phpmaker">
<input class="form-control" type="text" name="x_Emi_RegimenFiscal" id="x_Emi_RegimenFiscal" size="30" maxlength="9" value="<?php echo htmlspecialchars(@$x_Emi_RegimenFiscal) ?>">
</span></td>
	</tr>
	<tr>
		<th><span class="phpmaker">Clave</span></th>
		<td bgcolor="#F5F5F5"><span class="phpmaker">
<input class="form-control" type="text" name="x_Emi_Clave" id="x_Emi_Clave" size="30" maxlength="30" value="<?php echo htmlspecialchars(@$x_Emi_Clave) ?>">
</span></td>
	</tr>
	<tr>
		<th><span class="phpmaker">Fac Atr Adquirente</span></th>
		<td bgcolor="#F5F5F5"><span class="phpmaker">
<input class="form-control" type="text" name="x_Emi_FacAtrAdquirente" id="x_Emi_FacAtrAdquirente" size="30" maxlength="30" value="<?php echo htmlspecialchars(@$x_Emi_FacAtrAdquirente) ?>">
</span></td>
	</tr>
	<tr>
		<th><span class="phpmaker">Curp</span></th>
		<td bgcolor="#F5F5F5"><span class="phpmaker">
<input class="form-control" type="text" name="x_Emi_Curp" id="x_Emi_Curp" size="30" maxlength="54" value="<?php echo htmlspecialchars(@$x_Emi_Curp) ?>">
</span></td>
	</tr>
	<tr>
		<th><span class="phpmaker">Registro Patronal</span></th>
		<td bgcolor="#F5F5F5"><span class="phpmaker">
<input class="form-control" type="text" name="x_Emi_RegistroPatronal" id="x_Emi_RegistroPatronal" size="30" maxlength="60" value="<?php echo htmlspecialchars(@$x_Emi_RegistroPatronal) ?>">
</span></td>
	</tr>
	<tr>
		<th><span class="phpmaker">Rfc Patron Origen</span></th>
		<td bgcolor="#F5F5F5"><span class="phpmaker">
<input class="form-control"  type="text" name="x_Emi_RfcPatronOrigen" id="x_Emi_RfcPatronOrigen" size="30" maxlength="39" value="<?php echo htmlspecialchars(@$x_Emi_RfcPatronOrigen) ?>">
</span></td>
	</tr>
	<tr>
		<th><span class="phpmaker">Entidad SNCF</span></th>
		<td bgcolor="#F5F5F5"><span class="phpmaker">
<input type="radio" name="x_Emi_EntidadSNCF"<?php if (@$x_Emi_EntidadSNCF == "0") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("0"); ?>">
<?php echo "No"; ?>
<?php echo EditOptionSeparator(0); ?>
<input type="radio" name="x_Emi_EntidadSNCF"<?php if (@$x_Emi_EntidadSNCF == "1") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("1"); ?>">
<?php echo "Si"; ?>
<?php echo EditOptionSeparator(1); ?>
</span></td>
	</tr>
	<tr>
		<th><span class="phpmaker">Municipio</span></th>
		<td bgcolor="#F5F5F5"><span class="phpmaker">
<?php
if ((!is_null($x_Mun_ID)) && ($x_Mun_ID <> "")) {
	$sSqlWrk = "SELECT DISTINCT `Mun_Descrip` FROM `vit_municipios`";
	$sTmp = $x_Mun_ID;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `Mun_ID` = " . $sTmp . "";
	$sSqlWrk .= " ORDER BY `Mun_Descrip` Asc";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
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
<input type="hidden" id="x_Mun_ID" name="x_Mun_ID" value="<?php echo htmlspecialchars(@$x_Mun_ID); ?>">
</span></td>
	</tr>
	<tr>
		<th><span class="phpmaker">Nom Corto</span></th>
		<td bgcolor="#F5F5F5"><span class="phpmaker">
<input class="form-control" type="text" name="x_Emi_NomCorto" id="x_Emi_NomCorto" size="30" maxlength="75" value="<?php echo htmlspecialchars(@$x_Emi_NomCorto) ?>">
</span></td>
	</tr>
	<tr>
		<th><span class="phpmaker">Archivo Key</span></th>
		<td bgcolor="#F5F5F5"><span class="phpmaker">
<?php if ((!is_null($x_Emi_ArchivoKey)) && $x_Emi_ArchivoKey <> "") {  ?>
<input type="hidden" name="a_x_Emi_ArchivoKey" value="1">
<?php } else {?>
<input type="hidden" name="a_x_Emi_ArchivoKey" value="3">
<?php } ?>
<input class="form-control" type="file" id="x_Emi_ArchivoKey" name="x_Emi_ArchivoKey" size="30" onChange="if (this.form.a_x_Emi_ArchivoKey[2]) this.form.a_x_Emi_ArchivoKey[2].checked=true;">
</span></td>
	</tr>
	<tr>
		<th><span class="phpmaker">Archivo Cer</span></th>
		<td bgcolor="#F5F5F5"><span class="phpmaker">
<?php if ((!is_null($x_Emi_ArchivoCer)) && $x_Emi_ArchivoCer <> "") {  ?>
<input type="hidden" name="a_x_Emi_ArchivoCer" value="1">
<?php } else {?>
<input type="hidden" name="a_x_Emi_ArchivoCer" value="3">
<?php } ?>
<input class="form-control" type="file" id="x_Emi_ArchivoCer" name="x_Emi_ArchivoCer" size="30" onChange="if (this.form.a_x_Emi_ArchivoCer[2]) this.form.a_x_Emi_ArchivoCer[2].checked=true;">
</span></td>
	</tr>
	<tr>
		<th><span class="phpmaker">&nbsp;</span></th>
		<td bgcolor="#F5F5F5"><span class="phpmaker">
		<button type="submit" name="Action" class="btn btn-soft-success waves-effect waves-light" value="EDIT">Actualizar</button>
		<a class="btn btn-soft-primary waves-effect waves-light" href="emisores_listado.php">Cancelar</a>
		</span></td>
	</tr>
</table>
</form>
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
phpmkr_db_close($conn);
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($conn)
{
	global $x_Emi_RFC;
	global $x_Mun_ID;
	$sSql = "SELECT * FROM `vit_emisor`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Emi_RFC) : $x_Emi_RFC;
	$sWhere .= "(`Emi_RFC` = '" . addslashes($sTmp) . "')";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Mun_ID) : $x_Mun_ID;
	$sWhere .= "(`Mun_ID` = " . addslashes($sTmp) . ")";
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
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$bLoadData = false;
	}else{
		$bLoadData = true;
		$row = phpmkr_fetch_array($rs);

		// Get the field contents
		$GLOBALS["x_Emi_RFC"] = $row["Emi_RFC"];
		$GLOBALS["x_Emi_Nombre"] = $row["Emi_Nombre"];
		$GLOBALS["x_Emi_RegimenFiscal"] = $row["Emi_RegimenFiscal"];
		$GLOBALS["x_Emi_Clave"] = $row["Emi_Clave"];
		$GLOBALS["x_Emi_FacAtrAdquirente"] = $row["Emi_FacAtrAdquirente"];
		$GLOBALS["x_Emi_Curp"] = $row["Emi_Curp"];
		$GLOBALS["x_Emi_RegistroPatronal"] = $row["Emi_RegistroPatronal"];
		$GLOBALS["x_Emi_RfcPatronOrigen"] = $row["Emi_RfcPatronOrigen"];
		$GLOBALS["x_Emi_EntidadSNCF"] = $row["Emi_EntidadSNCF"];
		$GLOBALS["x_Mun_ID"] = $row["Mun_ID"];
		$GLOBALS["x_Emi_NomCorto"] = $row["Emi_NomCorto"];
		$GLOBALS["x_Emi_ArchivoKey"] = $row["Emi_ArchivoKey"];
		$GLOBALS["x_Emi_ArchivoCer"] = $row["Emi_ArchivoCer"];
	}
	phpmkr_free_result($rs);
	return $bLoadData;
}
?>
<?php

//-------------------------------------------------------------------------------
// Function EditData
// - Edit Data based on Key Value sKey
// - Variables used: field variables

function EditData($conn)
{
	global $x_Emi_RFC;
	global $x_Mun_ID;
	$sSql = "SELECT * FROM `vit_emisor`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Emi_RFC) : $x_Emi_RFC;	
	$sWhere .= "(`Emi_RFC` = '" . addslashes($sTmp) . "')";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Mun_ID) : $x_Mun_ID;	
	$sWhere .= "(`Mun_ID` = " . addslashes($sTmp) . ")";
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
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
	if (phpmkr_num_rows($rs) == 0) {
		$bEditData = false; // Update Failed
	}else{

		// check file size
		$EW_MaxFileSize = @$_POST["EW_Max_File_Size"];
		if (!empty($_FILES["x_Emi_ArchivoKey"]["size"])) {
			if (!empty($EW_MaxFileSize) && $_FILES["x_Emi_ArchivoKey"]["size"] > $EW_MaxFileSize) {
				die("Max. file upload size exceeded");
			}
		}
		$a_x_Emi_ArchivoKey = @$_POST["a_x_Emi_ArchivoKey"];
		if (!empty($_FILES["x_Emi_ArchivoCer"]["size"])) {
			if (!empty($EW_MaxFileSize) && $_FILES["x_Emi_ArchivoCer"]["size"] > $EW_MaxFileSize) {
				die("Max. file upload size exceeded");
			}
		}
		$a_x_Emi_ArchivoCer = @$_POST["a_x_Emi_ArchivoCer"];
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_RFC"]) : $GLOBALS["x_Emi_RFC"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_RFC`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_Nombre"]) : $GLOBALS["x_Emi_Nombre"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_Nombre`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_RegimenFiscal"]) : $GLOBALS["x_Emi_RegimenFiscal"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_RegimenFiscal`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_Clave"]) : $GLOBALS["x_Emi_Clave"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_Clave`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_FacAtrAdquirente"]) : $GLOBALS["x_Emi_FacAtrAdquirente"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_FacAtrAdquirente`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_Curp"]) : $GLOBALS["x_Emi_Curp"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_Curp`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_RegistroPatronal"]) : $GLOBALS["x_Emi_RegistroPatronal"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_RegistroPatronal`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_RfcPatronOrigen"]) : $GLOBALS["x_Emi_RfcPatronOrigen"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_RfcPatronOrigen`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_EntidadSNCF"]) : $GLOBALS["x_Emi_EntidadSNCF"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_EntidadSNCF`"] = $theValue;
		$theValue = ($GLOBALS["x_Mun_ID"] != "") ? intval($GLOBALS["x_Mun_ID"]) : "NULL";
		$fieldList["`Mun_ID`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Emi_NomCorto"]) : $GLOBALS["x_Emi_NomCorto"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Emi_NomCorto`"] = $theValue;
		if ($a_x_Emi_ArchivoKey == "2") { // Remove
			$fieldList["`Emi_ArchivoKey`"] = "NULL";
		} else if ($a_x_Emi_ArchivoKey == "3") { // Update
			if (is_uploaded_file($_FILES["x_Emi_ArchivoKey"]["tmp_name"])) {
				$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_Emi_ArchivoKey"]["name"]);
						if (!move_uploaded_file($_FILES["x_Emi_ArchivoKey"]["tmp_name"], $destfile)) // move file to destination path
						die("You didn't upload a file or the file couldn't be moved to" . $destfile);

				// File Name
				$theName = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes(ewUploadFileName($_FILES["x_Emi_ArchivoKey"]["name"])) : ewUploadFileName($_FILES["x_Emi_ArchivoKey"]["name"]);
				$fieldList["`Emi_ArchivoKey`"] = " '" . $theName . "'";
				@unlink($_FILES["x_Emi_ArchivoKey"]["tmp_name"]);
			}
		}
		if ($a_x_Emi_ArchivoCer == "2") { // Remove
			$fieldList["`Emi_ArchivoCer`"] = "NULL";
		} else if ($a_x_Emi_ArchivoCer == "3") { // Update
			if (is_uploaded_file($_FILES["x_Emi_ArchivoCer"]["tmp_name"])) {
				$destfile = ewUploadPath(1) . ewUploadFileName($_FILES["x_Emi_ArchivoCer"]["name"]);
						if (!move_uploaded_file($_FILES["x_Emi_ArchivoCer"]["tmp_name"], $destfile)) // move file to destination path
						die("You didn't upload a file or the file couldn't be moved to" . $destfile);

				// File Name
				$theName = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes(ewUploadFileName($_FILES["x_Emi_ArchivoCer"]["name"])) : ewUploadFileName($_FILES["x_Emi_ArchivoCer"]["name"]);
				$fieldList["`Emi_ArchivoCer`"] = " '" . $theName . "'";
				@unlink($_FILES["x_Emi_ArchivoCer"]["tmp_name"]);
			}
		}

		// update
		$sSql = "UPDATE `vit_emisor` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
		$bEditData = true; // Update Successful
	}
	return $bEditData;
}
?>
