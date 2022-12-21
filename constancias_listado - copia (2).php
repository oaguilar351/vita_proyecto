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
$x_const_ContanciaID = Null; 
$ox_const_ContanciaID = Null;
$x_const_RFC = Null; 
$ox_const_RFC = Null;
$x_const_CURP = Null; 
$ox_const_CURP = Null;
$x_const_Nombres = Null; 
$ox_const_Nombres = Null;
$x_const_Apellido1 = Null; 
$ox_const_Apellido1 = Null;
$x_const_Apellido2 = Null; 
$ox_const_Apellido2 = Null;
$x_const_InicioOperaciones = Null; 
$ox_const_InicioOperaciones = Null;
$x_const_EstatusPadron = Null; 
$ox_const_EstatusPadron = Null;
$x_const_UltimoCambio = Null; 
$ox_const_UltimoCambio = Null;
$x_const_NombreComercial = Null; 
$ox_const_NombreComercial = Null;
$x_const_CodigoPostal = Null; 
$ox_const_CodigoPostal = Null;
$x_const_TipoVialidad = Null; 
$ox_const_TipoVialidad = Null;
$x_const_NombreVialidad = Null; 
$ox_const_NombreVialidad = Null;
$x_const_NumExterior = Null; 
$ox_const_NumExterior = Null;
$x_const_NumInterior = Null; 
$ox_const_NumInterior = Null;
$x_const_Colonia = Null; 
$ox_const_Colonia = Null;
$x_const_Localidad = Null; 
$ox_const_Localidad = Null;
$x_const_Municipio = Null; 
$ox_const_Municipio = Null;
$x_const_Entidad = Null; 
$ox_const_Entidad = Null;
$x_const_EntreCalle = Null; 
$ox_const_EntreCalle = Null;
$x_const_YCalle = Null; 
$ox_const_YCalle = Null;
$x_const_Email = Null; 
$ox_const_Email = Null;
$x_const_TelefonoLada = Null; 
$ox_const_TelefonoLada = Null;
$x_const_TelefonoNum = Null; 
$ox_const_TelefonoNum = Null;
$x_const_EstadoDomicilio = Null; 
$ox_const_EstadoDomicilio = Null;
$x_const_EstadoContribuyente = Null; 
$ox_const_EstadoContribuyente = Null;
$x_const_Archivo = Null; 
$ox_const_Archivo = Null;
$fs_x_const_Archivo = 0;
$fn_x_const_Archivo = "";
$ct_x_const_Archivo = "";
$w_x_const_Archivo = 0;
$h_x_const_Archivo = 0;
$a_x_const_Archivo = "";
$x_const_Ruta = Null; 
$ox_const_Ruta = Null;
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/phpmkrfn.php") ?>
<?php
$nStartRec = 0;
$nStopRec = 0;
$nTotalRecs = 0;
$nRecCount = 0;
$nRecActual = 0;
$sKeyMaster = "";
$sDbWhereMaster = "";
$sSrchAdvanced = "";
$sDbWhereDetail = "";
$sSrchBasic = "";
$sSrchWhere = "";
$sDbWhere = "";
$sDefaultOrderBy = "";
$sDefaultFilter = "";
$sWhere = "";
$sGroupBy = "";
$sHaving = "";
$sOrderBy = "";
$sSqlMasterBase = "";
$sSqlMaster = "";
$nDisplayRecs = 10;
$nRecRange = 10;

// Set up records per page dynamically
SetUpDisplayRecs();

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// Handle Reset Command
ResetCmd();

// Get Search Criteria for Basic Search
SetUpBasicSearch();

// Build Search Criteria
if ($sSrchAdvanced != "") {
	$sSrchWhere = $sSrchAdvanced; // Advanced Search
}
elseif ($sSrchBasic != "") {
	$sSrchWhere = $sSrchBasic; // Basic Search
}

// Save Search Criteria
if ($sSrchWhere != "") {
	$_SESSION["constancias_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["constancias_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["constancias_searchwhere"];
}

// Build SQL
$sSql = "SELECT * FROM `constancias`";

// Load Default Filter
$sDefaultFilter = "";
$sGroupBy = "";
$sHaving = "";

// Load Default Order
$sDefaultOrderBy = "";

// Build WHERE condition
$sDbWhere = "";
if ($sDbWhereDetail <> "") {
	$sDbWhere .= "(" . $sDbWhereDetail . ") AND ";
}
if ($sSrchWhere <> "") {
	$sDbWhere .= "(" . $sSrchWhere . ") AND ";
}
if (strlen($sDbWhere) > 5) {
	$sDbWhere = substr($sDbWhere, 0, strlen($sDbWhere)-5); // Trim rightmost AND
}
$sWhere = "";
if ($sDefaultFilter <> "") {
	$sWhere .= "(" . $sDefaultFilter . ") AND ";
}
if ($sDbWhere <> "") {
	$sWhere .= "(" . $sDbWhere . ") AND ";
}
if (substr($sWhere, -5) == " AND ") {
	$sWhere = substr($sWhere, 0, strlen($sWhere)-5);
}
if ($sWhere != "") {
	$sSql .= " WHERE " . $sWhere;
}
if ($sGroupBy != "") {
	$sSql .= " GROUP BY " . $sGroupBy;
}
if ($sHaving != "") {
	$sSql .= " HAVING " . $sHaving;
}

// Set Up Sorting Order
$sOrderBy = "";
SetUpSortOrder();
if ($sOrderBy != "") {
	$sSql .= " ORDER BY " . $sOrderBy;
}

//echo $sSql; // Uncomment to show SQL for debugging
?>

<script type="text/javascript" src="js/ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "/"; // set date separator	

//-->
</script>

<?php

// Set up Record Set
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
<head>
        
        <title>Constancias | VitaInsumos</title>
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
                                <h4 class="mb-sm-0">Constancias</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Modulos</a></li>
                                        <li class="breadcrumb-item active">Constancias</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<script>
$(document).ready(function(){
	Swal.fire({
		icon: 'success',
		title: '<?php echo $_SESSION["ewmsg"]; ?>',
		showConfirmButton: false,
		timer: 1500,
		showCloseButton: true
	});
});
</script>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>			
					<div class="row">
                        <div class="col-lg-12">
                            <div class="card" id="leadsList">
                                <div class="card-header border-0">

                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm-3">
                                            <div class="search-box">
												<form action="constancias_listado.php">
												<table border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td><span class="phpmaker">
															<input type="text" name="psearch" class="form-control search">
															<input type="hidden" name="psearchtype" value="" checked>
														</span></td>
													</tr>
												</table>
												</form>
                                            </div>
                                        </div>
                                        <div class="col-sm-auto ms-auto">											
                                            <div class="hstack gap-2">
												<?php if(@$_SESSION["constancias_OrderBy"]!=""){ ?>
												<a class="btn btn-light" href="constancias_listado.php?cmd=resetsort" title="Quitar Orden"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>X</a>
												<?php } ?>											
												<?php if(@$sWhere!="" || @$sSrchAdvanced!="" && @$_SESSION["constancias_OrderBy"]==""){ ?>
												<a class="btn btn-light" href="constancias_listado.php?cmd=reset" title="Quitar Filtros"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>F</a>
												<?php } ?>
												 <button type="button" class="btn btn-info" data-bs-toggle="offcanvas"
                                                    href="#offcanvasExample"> Nueva </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <div class="table-responsive table-card">

<?php if ($nTotalRecs > 0)  { ?>
<form method="post">
<table class="table align-middle" id="customerTable">
	<thead class="table-light">
	 <tr>
		<th valign="top"><span>
	<a href="constancias_listado.php?order=<?php echo urlencode("const_RFC"); ?>">RFC&nbsp;(*)<?php if (@$_SESSION["constancias_x_const_RFC_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["constancias_x_const_RFC_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="constancias_listado.php?order=<?php echo urlencode("const_CURP"); ?>">CURP&nbsp;(*)<?php if (@$_SESSION["constancias_x_const_CURP_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["constancias_x_const_CURP_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="constancias_listado.php?order=<?php echo urlencode("const_Nombres"); ?>">Nombres&nbsp;(*)<?php if (@$_SESSION["constancias_x_const_Nombres_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["constancias_x_const_Nombres_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="constancias_listado.php?order=<?php echo urlencode("const_Apellido1"); ?>">Apellido Paterno&nbsp;(*)<?php if (@$_SESSION["constancias_x_const_Apellido1_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["constancias_x_const_Apellido1_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="constancias_listado.php?order=<?php echo urlencode("const_Apellido2"); ?>">Apellido Materno&nbsp;(*)<?php if (@$_SESSION["constancias_x_const_Apellido2_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["constancias_x_const_Apellido2_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="constancias_listado.php?order=<?php echo urlencode("const_InicioOperaciones"); ?>">Inicio Operaciones&nbsp;(*)<?php if (@$_SESSION["constancias_x_const_InicioOperaciones_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["constancias_x_const_InicioOperaciones_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="constancias_listado.php?order=<?php echo urlencode("const_EstatusPadron"); ?>">Estatus Padron&nbsp;(*)<?php if (@$_SESSION["constancias_x_const_EstatusPadron_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["constancias_x_const_EstatusPadron_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="constancias_listado.php?order=<?php echo urlencode("const_NombreComercial"); ?>">Nombre Comercial&nbsp;(*)<?php if (@$_SESSION["constancias_x_const_NombreComercial_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["constancias_x_const_NombreComercial_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="constancias_listado.php?order=<?php echo urlencode("const_CodigoPostal"); ?>">Codigo Postal&nbsp;(*)<?php if (@$_SESSION["constancias_x_const_CodigoPostal_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["constancias_x_const_CodigoPostal_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
		<th valign="top"><span>
	<a href="constancias_listado.php?order=<?php echo urlencode("const_Ruta"); ?>">Ruta&nbsp;(*)<?php if (@$_SESSION["constancias_x_const_Ruta_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["constancias_x_const_Ruta_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
		</span></th>
<th>&nbsp;</th>
	</tr>
	</thead>
	<tbody class="list form-check-all">
<?php

// Avoid starting record > total records
if ($nStartRec > $nTotalRecs) {
	$nStartRec = $nTotalRecs;
}

// Set the last record to display
$nStopRec = $nStartRec + $nDisplayRecs - 1;

// Move to first record directly for performance reason
$nRecCount = $nStartRec - 1;
if (phpmkr_num_rows($rs) > 0) {
	phpmkr_data_seek($rs, $nStartRec -1);
}
$nRecActual = 0;
while (($row = @phpmkr_fetch_array($rs)) && ($nRecCount < $nStopRec)) {
	$nRecCount = $nRecCount + 1;
	if ($nRecCount >= $nStartRec) {
		$nRecActual++;

		// Set row color
		$sItemRowClass = " bgcolor=\"#FFFFFF\"";

		// Display alternate color for rows
		if ($nRecCount % 2 <> 0) {
			$sItemRowClass = " bgcolor=\"#F5F5F5\"";
		}
		$x_const_ContanciaID = $row["const_ContanciaID"];
		$x_const_RFC = $row["const_RFC"];
		$x_const_CURP = $row["const_CURP"];
		$x_const_Nombres = $row["const_Nombres"];
		$x_const_Apellido1 = $row["const_Apellido1"];
		$x_const_Apellido2 = $row["const_Apellido2"];
		$x_const_InicioOperaciones = $row["const_InicioOperaciones"];
		$x_const_EstatusPadron = $row["const_EstatusPadron"];
		$x_const_UltimoCambio = $row["const_UltimoCambio"];
		$x_const_NombreComercial = $row["const_NombreComercial"];
		$x_const_CodigoPostal = $row["const_CodigoPostal"];
		$x_const_TipoVialidad = $row["const_TipoVialidad"];
		$x_const_NombreVialidad = $row["const_NombreVialidad"];
		$x_const_NumExterior = $row["const_NumExterior"];
		$x_const_NumInterior = $row["const_NumInterior"];
		$x_const_Colonia = $row["const_Colonia"];
		$x_const_Localidad = $row["const_Localidad"];
		$x_const_Municipio = $row["const_Municipio"];
		$x_const_Entidad = $row["const_Entidad"];
		$x_const_EntreCalle = $row["const_EntreCalle"];
		$x_const_YCalle = $row["const_YCalle"];
		$x_const_Email = $row["const_Email"];
		$x_const_TelefonoLada = $row["const_TelefonoLada"];
		$x_const_TelefonoNum = $row["const_TelefonoNum"];
		$x_const_EstadoDomicilio = $row["const_EstadoDomicilio"];
		$x_const_EstadoContribuyente = $row["const_EstadoContribuyente"];
		$x_const_Archivo = $row["const_Archivo"];
		$x_const_Ruta = $row["const_Ruta"];
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- const_RFC -->
		<td><span class="phpmaker">
<?php echo $x_const_RFC; ?>
</span></td>
		<!-- const_CURP -->
		<td><span class="phpmaker">
<?php echo $x_const_CURP; ?>
</span></td>
		<!-- const_Nombres -->
		<td><span class="phpmaker">
<?php echo $x_const_Nombres; ?>
</span></td>
		<!-- const_Apellido1 -->
		<td><span class="phpmaker">
<?php echo $x_const_Apellido1; ?>
</span></td>
		<!-- const_Apellido2 -->
		<td><span class="phpmaker">
<?php echo $x_const_Apellido2; ?>
</span></td>
		<!-- const_InicioOperaciones -->
		<td><span class="phpmaker">
<?php echo $x_const_InicioOperaciones; ?>
</span></td>
		<!-- const_EstatusPadron -->
		<td><span class="phpmaker">
<?php echo $x_const_EstatusPadron; ?>
</span></td>
		<!-- const_NombreComercial -->
		<td><span class="phpmaker">
<?php echo $x_const_NombreComercial; ?>
</span></td>
		<!-- const_CodigoPostal -->
		<td><span class="phpmaker">
<?php echo $x_const_CodigoPostal; ?>
</span></td>
		<!-- const_Ruta -->
		<td><span class="phpmaker">
<?php echo $x_const_Ruta; ?>
</span></td>
<td><span class="phpmaker"><a href="<?php if ($x_const_ContanciaID <> "") {echo "constanciasview.php?const_ContanciaID=" . urlencode($x_const_ContanciaID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Vista</a></span></td>
<!--<td><span class="phpmaker"><a href="<?php if ($x_const_ContanciaID <> "") {echo "constanciasdelete.php?const_ContanciaID=" . urlencode($x_const_ContanciaID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Eliminar</a></span></td>-->
	</tr>
<?php
	}
}
?>

</tbody>
</table>
</form>
<?php } ?>
</div>
<div class="d-flex justify-content-end mt-3">
	<div class="pagination-wrap hstack gap-2">
<form action="constancias_listado.php" name="ewpagerform" id="ewpagerform">
<table border="0" cellspacing="1" cellpadding="4">
	<tr>
		<td nowrap>
<?php
if ($nTotalRecs > 0) {
	$rsEof = ($nTotalRecs < ($nStartRec + $nDisplayRecs));
	$PrevStart = $nStartRec - $nDisplayRecs;
	if ($PrevStart < 1) { $PrevStart = 1; }
	$NextStart = $nStartRec + $nDisplayRecs;
	if ($NextStart > $nTotalRecs) { $NextStart = $nStartRec ; }
	$LastStart = intval(($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
	?>
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpmaker">Pagina &nbsp;</span></td>
<!--first page button-->
	<?php if ($nStartRec == 1) { ?>
	<td><a class="page-item pagination-prev disabled" href="#">|<</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-prev" href="constancias_listado.php?start=1">|<</a></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($PrevStart == $nStartRec) { ?>
	<td><a class="page-item pagination-prev disabled"><</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-prev" href="constancias_listado.php?start=<?php echo $PrevStart; ?>"><</a></td>
	<?php } ?>
<!--current page number-->
	<td><input class="form-control form-control-sm" type="text" name="pageno" value="<?php echo intval(($nStartRec-1)/$nDisplayRecs+1); ?>" size="4"></td>
<!--next page button-->
	<?php if ($NextStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">></a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="constancias_listado.php?start=<?php echo $NextStart; ?>">></a></td>
	<?php  } ?>
<!--last page button-->
	<?php if ($LastStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">>|</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="constancias_listado.php?start=<?php echo $LastStart; ?>">>|</a></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;de <?php echo intval(($nTotalRecs-1)/$nDisplayRecs+1);?></span></td>
	</tr></table>
	<?php if ($nStartRec > $nTotalRecs) { $nStartRec = $nTotalRecs; }
	$nStopRec = $nStartRec + $nDisplayRecs - 1;
	$nRecCount = $nTotalRecs - 1;
	if ($rsEof) { $nRecCount = $nTotalRecs; }
	if ($nStopRec > $nRecCount) { $nStopRec = $nRecCount; } ?>
	<span class="phpmaker">Registros del <?php echo $nStartRec; ?> al <?php echo $nStopRec; ?> de un total <?php echo $nTotalRecs; ?></span>
<?php } else { ?>
	<span class="phpmaker">
	La consulta no encontro registros con los filtros indicados. <br />
	Favor de intenterlo nuevamente.
	</span>
<?php } ?>
		</td>
<?php if ($nTotalRecs > 0) { ?>
		<td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="right" valign="top" nowrap><span class="phpmaker">Registros por pagina&nbsp;
<select class="form-select form-select-sm" name="RecPerPage" onChange="this.form.pageno.value = 1;this.form.submit();" class="phpmaker">
<option value="10"<?php if ($nDisplayRecs == 10) { echo " selected";  }?>>10</option>
<option value="20"<?php if ($nDisplayRecs == 20) { echo " selected";  }?>>20</option>
<option value="50"<?php if ($nDisplayRecs == 50) { echo " selected";  }?>>50</option>
<option value="100"<?php if ($nDisplayRecs == 100) { echo " selected";  }?>>100</option>
<option value="ALL"<?php if (@$_SESSION["vit_comprobantes_RecPerPage"] == -1) { echo " selected";  }?>>Todos</option>
</select>
		</span></td>
<?php } ?>
	</tr>
</table>
</form>
</div>
	</div>
</div>
<script type="text/javascript">
<!--
function EW_checkMyFormN(EW_this) {
	var valor_ruta = EW_this.x_const_Ruta.value;
	var valor_nuevo = valor_ruta.replaceAll("/", "&");
	var valor_nuevo = valor_nuevo.replace("Ñ--", "://");
	var valor_nuevo = valor_nuevo.replace("?", "_");	
	var valor_nuevo = valor_nuevo.replaceAll("-", "/");
	var valor_nuevo = valor_nuevo.replace(".jsf_", ".jsf?");
	var valor_nuevo = valor_nuevo.replaceAll("¿", "=");	
	//alert("Ruta: "+valor_nuevo);
	EW_this.x_const_Ruta.value = valor_nuevo;
return true;
}

//-->
</script>

									<!------INICIO FILTROS--------->
									<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample"
                                        aria-labelledby="offcanvasExampleLabel">
                                        <div class="offcanvas-header bg-light">
                                            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Nueva - Constancia</h5>
                                            <button type="button" class="btn-close text-reset"
                                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <!--end offcanvas-header-->
										<form name="constanciasadd" id="constanciasadd" action="constanciasadd.php" method="post" onSubmit="return EW_checkMyFormN(this);">
                                        <!--<form action="" class="d-flex flex-column justify-content-end h-100">-->
                                            <div class="offcanvas-body">
												<div class="mb-4">
                                                    <label for="RFC" class="form-label text-muted text-uppercase fw-semibold mb-3">Codigo QR</label>                     
													<input class="form-control" type="text" name="x_const_Ruta" id="x_const_Ruta" size="30" maxlength="200" value="">
                                                </div>
                                            </div>
                                            <!--end offcanvas-body-->
                                            <div class="offcanvas-footer border-top p-3 text-right hstack gap-2">
                                                <button type="submit" name="Action" class="btn btn-soft-success waves-effect waves-light w-100" value="ADD">Enviar</button>
												<input type="hidden" name="a_add" value="A">
                                            </div>
                                            <!--end offcanvas-footer-->
                                        </form>
                                    </div>
                                    <!--end offcanvas-->
									<!------FIN FILTROS------------->
<?php
// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>									
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
// Function SetUpDisplayRecs
// - Set up Number of Records displayed per page based on Form element RecPerPage
// - Variables setup: nDisplayRecs

function SetUpDisplayRecs()
{
	global $nStartRec;
	global $nDisplayRecs;
	global $nTotalRecs;
	$sWrk = @$_GET["RecPerPage"];
	if ($sWrk <> "") {
		if (is_numeric($sWrk)) {
			$nDisplayRecs = $sWrk;
		}else{
			if (strtoupper($sWrk) == "ALL") { // Display All Records
				$nDisplayRecs = -1;
			}else{
				$nDisplayRecs = 10;  // Non-numeric, Load Default
			}
		}
		$_SESSION["constancias_RecPerPage"] = $nDisplayRecs; // Save to Session

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["constancias_REC"] = $nStartRec;
	}else{
		if (@$_SESSION["constancias_RecPerPage"] <> "") {
			$nDisplayRecs = $_SESSION["constancias_RecPerPage"]; // Restore from Session
		}else{
			$nDisplayRecs = 10; // Load Default
		}
	}
}

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`const_RFC` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_CURP` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Nombres` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Apellido1` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Apellido2` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_InicioOperaciones` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_EstatusPadron` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_UltimoCambio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_NombreComercial` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_CodigoPostal` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_TipoVialidad` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_NombreVialidad` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_NumExterior` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_NumInterior` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Colonia` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Localidad` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Municipio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Entidad` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_EntreCalle` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_YCalle` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Email` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_TelefonoLada` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_TelefonoNum` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_EstadoDomicilio` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_EstadoContribuyente` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Archivo` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`const_Ruta` LIKE '%" . $sKeyword . "%' OR ";
	if (substr($BasicSearchSQL, -4) == " OR ") { $BasicSearchSQL = substr($BasicSearchSQL, 0, strlen($BasicSearchSQL)-4); }
	return $BasicSearchSQL;
}

//-------------------------------------------------------------------------------
// Function SetUpBasicSearch
// - Set up Basic Search parameter based on form elements pSearch & pSearchType
// - Variables setup: sSrchBasic

function SetUpBasicSearch()
{
	global $sSrchBasic;
	$sSearch = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes(@$_GET["psearch"]) : @$_GET["psearch"];
	$sSearchType = @$_GET["psearchtype"];
	if ($sSearch <> "") {
		if ($sSearchType <> "") {
			while (strpos($sSearch, "  ") != false) {
				$sSearch = str_replace("  ", " ",$sSearch);
			}
			$arKeyword = split(" ", trim($sSearch));
			foreach ($arKeyword as $sKeyword)
			{
				$sSrchBasic .= "(" . BasicSearchSQL($sKeyword) . ") " . $sSearchType . " ";
			}
		}
		else
		{
			$sSrchBasic = BasicSearchSQL($sSearch);
		}
	}
	if (substr($sSrchBasic, -4) == " OR ") { $sSrchBasic = substr($sSrchBasic, 0, strlen($sSrchBasic)-4); }
	if (substr($sSrchBasic, -5) == " AND ") { $sSrchBasic = substr($sSrchBasic, 0, strlen($sSrchBasic)-5); }
}

//-------------------------------------------------------------------------------
// Function SetUpSortOrder
// - Set up Sort parameters based on Sort Links clicked
// - Variables setup: sOrderBy, Session("Table_OrderBy"), Session("Table_Field_Sort")

function SetUpSortOrder()
{
	global $sOrderBy;
	global $sDefaultOrderBy;

	// Check for an Order parameter
	if (strlen(@$_GET["order"]) > 0) {
		$sOrder = @$_GET["order"];

		// Field const_RFC
		if ($sOrder == "const_RFC") {
			$sSortField = "`const_RFC`";
			$sLastSort = @$_SESSION["constancias_x_const_RFC_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_RFC_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_RFC_Sort"] <> "") { @$_SESSION["constancias_x_const_RFC_Sort"] = ""; }
		}

		// Field const_CURP
		if ($sOrder == "const_CURP") {
			$sSortField = "`const_CURP`";
			$sLastSort = @$_SESSION["constancias_x_const_CURP_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_CURP_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_CURP_Sort"] <> "") { @$_SESSION["constancias_x_const_CURP_Sort"] = ""; }
		}

		// Field const_Nombres
		if ($sOrder == "const_Nombres") {
			$sSortField = "`const_Nombres`";
			$sLastSort = @$_SESSION["constancias_x_const_Nombres_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_Nombres_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_Nombres_Sort"] <> "") { @$_SESSION["constancias_x_const_Nombres_Sort"] = ""; }
		}

		// Field const_Apellido1
		if ($sOrder == "const_Apellido1") {
			$sSortField = "`const_Apellido1`";
			$sLastSort = @$_SESSION["constancias_x_const_Apellido1_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_Apellido1_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_Apellido1_Sort"] <> "") { @$_SESSION["constancias_x_const_Apellido1_Sort"] = ""; }
		}

		// Field const_Apellido2
		if ($sOrder == "const_Apellido2") {
			$sSortField = "`const_Apellido2`";
			$sLastSort = @$_SESSION["constancias_x_const_Apellido2_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_Apellido2_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_Apellido2_Sort"] <> "") { @$_SESSION["constancias_x_const_Apellido2_Sort"] = ""; }
		}

		// Field const_InicioOperaciones
		if ($sOrder == "const_InicioOperaciones") {
			$sSortField = "`const_InicioOperaciones`";
			$sLastSort = @$_SESSION["constancias_x_const_InicioOperaciones_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_InicioOperaciones_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_InicioOperaciones_Sort"] <> "") { @$_SESSION["constancias_x_const_InicioOperaciones_Sort"] = ""; }
		}

		// Field const_EstatusPadron
		if ($sOrder == "const_EstatusPadron") {
			$sSortField = "`const_EstatusPadron`";
			$sLastSort = @$_SESSION["constancias_x_const_EstatusPadron_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_EstatusPadron_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_EstatusPadron_Sort"] <> "") { @$_SESSION["constancias_x_const_EstatusPadron_Sort"] = ""; }
		}

		// Field const_NombreComercial
		if ($sOrder == "const_NombreComercial") {
			$sSortField = "`const_NombreComercial`";
			$sLastSort = @$_SESSION["constancias_x_const_NombreComercial_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_NombreComercial_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_NombreComercial_Sort"] <> "") { @$_SESSION["constancias_x_const_NombreComercial_Sort"] = ""; }
		}

		// Field const_CodigoPostal
		if ($sOrder == "const_CodigoPostal") {
			$sSortField = "`const_CodigoPostal`";
			$sLastSort = @$_SESSION["constancias_x_const_CodigoPostal_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_CodigoPostal_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_CodigoPostal_Sort"] <> "") { @$_SESSION["constancias_x_const_CodigoPostal_Sort"] = ""; }
		}

		// Field const_Ruta
		if ($sOrder == "const_Ruta") {
			$sSortField = "`const_Ruta`";
			$sLastSort = @$_SESSION["constancias_x_const_Ruta_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["constancias_x_const_Ruta_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["constancias_x_const_Ruta_Sort"] <> "") { @$_SESSION["constancias_x_const_Ruta_Sort"] = ""; }
		}
		$_SESSION["constancias_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["constancias_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["constancias_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["constancias_OrderBy"] = $sOrderBy;
	}
}

//-------------------------------------------------------------------------------
// Function SetUpStartRec
//- Set up Starting Record parameters based on Pager Navigation
// - Variables setup: nStartRec

function SetUpStartRec()
{

	// Check for a START parameter
	global $nStartRec;
	global $nDisplayRecs;
	global $nTotalRecs;
	if (strlen(@$_GET["start"]) > 0) {
		$nStartRec = @$_GET["start"];
		$_SESSION["constancias_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["constancias_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["constancias_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["constancias_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["constancias_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["constancias_REC"] = $nStartRec;
		}
	}
}

//-------------------------------------------------------------------------------
// Function ResetCmd
// - Clear list page parameters
// - RESET: reset search parameters
// - RESETALL: reset search & master/detail parameters
// - RESETSORT: reset sort parameters

function ResetCmd()
{

	// Get Reset Cmd
	if (strlen(@$_GET["cmd"]) > 0) {
		$sCmd = @$_GET["cmd"];

		// Reset Search Criteria
		if (strtoupper($sCmd) == "RESET") {
			$sSrchWhere = "";
			$_SESSION["constancias_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["constancias_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["constancias_OrderBy"] = $sOrderBy;
			if (@$_SESSION["constancias_x_const_RFC_Sort"] <> "") { $_SESSION["constancias_x_const_RFC_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_CURP_Sort"] <> "") { $_SESSION["constancias_x_const_CURP_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_Nombres_Sort"] <> "") { $_SESSION["constancias_x_const_Nombres_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_Apellido1_Sort"] <> "") { $_SESSION["constancias_x_const_Apellido1_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_Apellido2_Sort"] <> "") { $_SESSION["constancias_x_const_Apellido2_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_InicioOperaciones_Sort"] <> "") { $_SESSION["constancias_x_const_InicioOperaciones_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_EstatusPadron_Sort"] <> "") { $_SESSION["constancias_x_const_EstatusPadron_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_NombreComercial_Sort"] <> "") { $_SESSION["constancias_x_const_NombreComercial_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_CodigoPostal_Sort"] <> "") { $_SESSION["constancias_x_const_CodigoPostal_Sort"] = ""; }
			if (@$_SESSION["constancias_x_const_Ruta_Sort"] <> "") { $_SESSION["constancias_x_const_Ruta_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["constancias_REC"] = $nStartRec;
	}
}
?>
