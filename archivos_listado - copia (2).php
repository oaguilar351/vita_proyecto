<?php include 'layouts/session.php'; ?>
<?php include 'layouts/head-main.php'; ?>
<?php

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
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=archivos_rosarito.xls');
}
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
$nDisplayRecs = 20;
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
	$_SESSION["archivos_rosarito_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["archivos_rosarito_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["archivos_rosarito_searchwhere"];
}

// Build SQL
$sSql = "SELECT * FROM `archivos_rosarito`";

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
<?php if ($sExport == "") { ?>
<script type="text/javascript" src="js/ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "-"; // set date separator	

//-->
</script>
<?php } ?>
<head>
        
        <title>Recibos | VitaInsumos</title>
        <?php include 'layouts/title-meta.php'; ?>
        <!-- Sweet Alert css-->
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
				<!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Recibos</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Modulos</a></li>
                                        <li class="breadcrumb-item active">Recibos</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
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
												<form action="receptores_listado.php">
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
                                                <button type="button" class="btn btn-info" data-bs-toggle="offcanvas" href="#offcanvasExample" title="Cargar Nuevo Archivo de Recibos"> Nuevo </button>
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
<?php if ($sExport <> "") { ?>
archivo ID
<?php }else{ ?>
	<a href="archivos_listado.php?order=<?php echo urlencode("arc_archivoID"); ?>">ID<?php if (@$_SESSION["archivos_rosarito_x_arc_archivoID_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["archivos_rosarito_x_arc_archivoID_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
archivo Ruta
<?php }else{ ?>
	<a href="archivos_listado.php?order=<?php echo urlencode("arc_archivoRuta"); ?>">Archivo Recibos&nbsp;(*)<?php if (@$_SESSION["archivos_rosarito_x_arc_archivoRuta_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["archivos_rosarito_x_arc_archivoRuta_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
<?php if ($sExport == "") { ?>
<th>&nbsp;</th>
<th>&nbsp;</th>
<?php } ?>
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
		$x_arc_archivoID = $row["arc_archivoID"];
		$x_arc_archivoRuta = $row["arc_archivoRuta"];
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- arc_archivoID -->
		<td><span class="phpmaker">
<?php echo $x_arc_archivoID; ?>
</span></td>
		<!-- arc_archivoRuta -->
		<td><span class="phpmaker">
<?php if ((!is_null($x_arc_archivoRuta)) &&  $x_arc_archivoRuta <> "") { ?>
<a href="<?php echo ewUploadPath(0) . $x_arc_archivoRuta ?>" target="_blank"><?php echo $x_arc_archivoRuta; ?></a>
<?php } ?>
</span></td>
<?php if ($sExport == "") { ?>
<td><span class="phpmaker"><a href="<?php if ($x_arc_archivoID <> "") {echo "archivos_rosaritodelete.php?arc_archivoID=" . urlencode($x_arc_archivoID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Eliminar</a></span></td>
<td><span class="phpmaker"><a href="CustomView1list.php?cmd=resetall&arcdet_archivoID=<?php echo urlencode($x_arc_archivoID); ?>">listado de recibos</a></span></td>
<?php } ?>
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
<?php if ($sExport == "") { ?>
<form action="archivos_listado.php" name="ewpagerform" id="ewpagerform">
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
	<td><a class="page-item pagination-prev" href="archivos_listado.php?start=1">|<</a></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($PrevStart == $nStartRec) { ?>
	<td><a class="page-item pagination-prev disabled"><</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-prev" href="archivos_listado.php?start=<?php echo $PrevStart; ?>"><</a></td>
	<?php } ?>
<!--current page number-->
	<td><input class="form-control form-control-sm" type="text" name="pageno" value="<?php echo intval(($nStartRec-1)/$nDisplayRecs+1); ?>" size="4"></td>
<!--next page button-->
	<?php if ($NextStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">></a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="archivos_listado.php?start=<?php echo $NextStart; ?>">></a></td>
	<?php  } ?>
<!--last page button-->
	<?php if ($LastStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">>|</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="archivos_listado.php?start=<?php echo $LastStart; ?>">>|</a></td>
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
<?php } ?>
 </div>
	</div>
</div>
<script type="text/javascript">
<!--
function EW_checkMyFormN(EW_this) {
if (EW_this.n_arch_Ruta && !EW_hasValue(EW_this.n_arch_Ruta, "FILE" )) {
	if (!EW_onError(EW_this, EW_this.n_arch_Ruta, "FILE", "Favor de elegir el archivo a cargar."))
		return false;
}
return true;
}

//-->
</script>
<!------INICIO FILTROS--------->
									<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample"
                                        aria-labelledby="offcanvasExampleLabel">
                                        <div class="offcanvas-header bg-light">
                                            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Nuevo - Archivo de recibos</h5>
                                            <button type="button" class="btn-close text-reset"
                                                data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <!--end offcanvas-header-->
										<form name="vit_archivosadd" id="vit_archivosadd" action="vit_archivosadd.php" method="post" enctype="multipart/form-data" onSubmit="return EW_checkMyFormN(this);">
										<input type="hidden" name="EW_Max_File_Size" value="200000000">
                                        <!--<form action="" class="d-flex flex-column justify-content-end h-100">-->
                                            <div class="offcanvas-body">
												<div class="mb-4">
                                                    <label for="RFC" class="form-label text-muted text-uppercase fw-semibold mb-3">Ruta</label>                     
													<input class="form-control" type="file" id="n_arch_Ruta" name="n_arch_Ruta">
                                                </div>
												<div class="mb-4">
                                                    <label for="RFC" class="form-label text-muted text-uppercase fw-semibold mb-3">Municipio</label>                     
													<?php
													$x_Mun_IDList = "<select class=\"form-select form-select\" name=\"n_Mun_ID\">";
													$x_Mun_IDList .= "<option value=''>Favor de elegir</option>";
													$sSqlWrk = "SELECT `Mun_ID`, `Mun_Descrip` FROM `vit_municipios`";
													$sSqlWrk .= " ORDER BY `Mun_Descrip` Asc";
													$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
													if ($rswrk) {
														$rowcntwrk = 0;
														while ($datawrk = phpmkr_fetch_array($rswrk)) {
															$x_Mun_IDList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";															
															$x_Mun_IDList .= ">" . $datawrk["Mun_Descrip"] . "</option>";
															$rowcntwrk++;
														}
													}
													@phpmkr_free_result($rswrk);
													$x_Mun_IDList .= "</select>";
													echo $x_Mun_IDList;
													?>
                                                </div>
												<div class="mb-4">
                                                    <label for="Nombre" class="form-label text-muted text-uppercase fw-semibold mb-3">Status</label><br />
                                                   <input type="radio" name="n_arch_Status"<?php if (@$x_arch_Status == "A") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("A"); ?>">
													<?php echo "Activo"; ?>
													<?php echo EditOptionSeparator(0); ?>
													<input type="radio" name="n_arch_Status"<?php if (@$x_arch_Status == "I") { ?> checked<?php } ?> value="<?php echo htmlspecialchars("I"); ?>">
													<?php echo "Inactivo"; ?>
													<?php echo EditOptionSeparator(1); ?>
                                                </div>												
                                            </div>
                                            <!--end offcanvas-body-->
                                            <div class="offcanvas-footer border-top p-3 text-right hstack gap-2">
                                                <button type="submit" name="Action" class="btn btn-soft-success waves-effect waves-light w-100" value="ADD">Agregar</button>
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
				$nDisplayRecs = 20;  // Non-numeric, Load Default
			}
		}
		$_SESSION["archivos_rosarito_RecPerPage"] = $nDisplayRecs; // Save to Session

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["archivos_rosarito_REC"] = $nStartRec;
	}else{
		if (@$_SESSION["archivos_rosarito_RecPerPage"] <> "") {
			$nDisplayRecs = $_SESSION["archivos_rosarito_RecPerPage"]; // Restore from Session
		}else{
			$nDisplayRecs = 20; // Load Default
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
	$BasicSearchSQL.= "`arc_archivoRuta` LIKE '%" . $sKeyword . "%' OR ";
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

		// Field arc_archivoID
		if ($sOrder == "arc_archivoID") {
			$sSortField = "`arc_archivoID`";
			$sLastSort = @$_SESSION["archivos_rosarito_x_arc_archivoID_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["archivos_rosarito_x_arc_archivoID_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["archivos_rosarito_x_arc_archivoID_Sort"] <> "") { @$_SESSION["archivos_rosarito_x_arc_archivoID_Sort"] = ""; }
		}

		// Field arc_archivoRuta
		if ($sOrder == "arc_archivoRuta") {
			$sSortField = "`arc_archivoRuta`";
			$sLastSort = @$_SESSION["archivos_rosarito_x_arc_archivoRuta_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["archivos_rosarito_x_arc_archivoRuta_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["archivos_rosarito_x_arc_archivoRuta_Sort"] <> "") { @$_SESSION["archivos_rosarito_x_arc_archivoRuta_Sort"] = ""; }
		}
		$_SESSION["archivos_rosarito_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["archivos_rosarito_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["archivos_rosarito_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["archivos_rosarito_OrderBy"] = $sOrderBy;
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
		$_SESSION["archivos_rosarito_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["archivos_rosarito_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["archivos_rosarito_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["archivos_rosarito_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["archivos_rosarito_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["archivos_rosarito_REC"] = $nStartRec;
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
			$_SESSION["archivos_rosarito_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["archivos_rosarito_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["archivos_rosarito_OrderBy"] = $sOrderBy;
			if (@$_SESSION["archivos_rosarito_x_arc_archivoID_Sort"] <> "") { $_SESSION["archivos_rosarito_x_arc_archivoID_Sort"] = ""; }
			if (@$_SESSION["archivos_rosarito_x_arc_archivoRuta_Sort"] <> "") { $_SESSION["archivos_rosarito_x_arc_archivoRuta_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["archivos_rosarito_REC"] = $nStartRec;
	}
}
?>
