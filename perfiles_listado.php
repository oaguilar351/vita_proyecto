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
$x_Per_Perfil_ID = Null; 
$ox_Per_Perfil_ID = Null;
$x_Per_Descripcion = Null; 
$ox_Per_Descripcion = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=vit_perfil.xls');
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

// Set Up Inline Edit Parameters
$sAction = "";
SetUpInlineEdit($conn);

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
	$_SESSION["vit_perfil_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["vit_perfil_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["vit_perfil_searchwhere"];
}

// Build SQL
$sSql = "SELECT * FROM `vit_perfil`";

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
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
return true;
}

//-->
</script>
<?php } ?>
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
                                <h4 class="mb-sm-0">Perfiles</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Modulos</a></li>
                                        <li class="breadcrumb-item active">Perfiles</li>
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
                                        <div class="col-sm-3">
                                            <div class="search-box">
												<form action="perfiles_listado.php">
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
												<?php if(@$_SESSION["vit_perfil_OrderBy"]!=""){ ?>
												<a class="btn btn-light" href="perfiles_listado.php?cmd=resetsort" title="Quitar Orden"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>X</a>
												<?php } ?>											
												<?php if(@$sWhere!="" || @$sSrchAdvanced!="" && @$_SESSION["vit_perfil_OrderBy"]==""){ ?>
												<a class="btn btn-light" href="perfiles_listado.php?cmd=reset" title="Quitar Filtros"><i class="mdi mdi-filter-variant-remove align-bottom me-1"></i>F</a>
												<?php } ?>
                                               <button type="button" class="btn btn-info" data-bs-toggle="offcanvas"
                                                    href="#offcanvasExample"> Nuevo </button>                                  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <div class="table-responsive table-card">
<?php if ($nTotalRecs > 0)  { ?>
											<form name="vit_perfillist" id="vit_perfillist" action="perfiles_listado.php" method="post">
											<table class="table align-middle" id="customerTable">
                                                <thead class="table-light">
												 <tr>
													<td valign="top">
													<?php if ($sExport <> "") { ?>
													Perfil
													<?php }else{ ?>
														<a href="perfiles_listado.php?order=<?php echo urlencode("Per_Perfil_ID"); ?>">Perfil<?php if (@$_SESSION["vit_perfil_x_Per_Perfil_ID_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_perfil_x_Per_Perfil_ID_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
													<?php } ?>
															</span></td>
															<td valign="top">
													<?php if ($sExport <> "") { ?>
													Descripcion
													<?php }else{ ?>
														<a href="perfiles_listado.php?order=<?php echo urlencode("Per_Descripcion"); ?>">Descripcion&nbsp;(*)<?php if (@$_SESSION["vit_perfil_x_Per_Descripcion_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_perfil_x_Per_Descripcion_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
													<?php } ?>
															</span></td>
													<?php if ($sExport == "") { ?>
													<!--<td>&nbsp;</td>-->
													<td>&nbsp;</td>
													<!--<td>&nbsp;</td>-->
													<!--<td>&nbsp;</td>-->
													<!--<td>&nbsp;</td>-->
													<?php } ?>
												</tr>
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
$nEditRowCnt = 0;
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
		$x_Per_Perfil_ID = $row["Per_Perfil_ID"];
		$x_Per_Descripcion = $row["Per_Descripcion"];
	$bEditRow = (($_SESSION["vita_proyecto_Key_Per_Perfil_ID"] == $x_Per_Perfil_ID) && ($nEditRowCnt == 0));
	if ($bEditRow) {
		$nEditRowCnt++;
		$sItemRowClass = " bgcolor=\"#FFFF99\"";
	}
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- Per_Perfil_ID -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php echo $x_Per_Perfil_ID; ?><input type="hidden" id="x_Per_Perfil_ID" name="x_Per_Perfil_ID" value="<?php echo htmlspecialchars(@$x_Per_Perfil_ID); ?>">
<?php }else{ ?>
<?php echo $x_Per_Perfil_ID; ?>
<?php } ?>
</span></td>
		<!-- Per_Descripcion -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<input class="form-control form-control-sm" type="text" name="x_Per_Descripcion" id="x_Per_Descripcion" size="30" maxlength="30" value="<?php echo htmlspecialchars(@$x_Per_Descripcion) ?>">
<?php }else{ ?>
<?php echo str_replace(chr(10), "<br>", $x_Per_Descripcion); ?>
<?php } ?>
</span></td>
<?php if ($sExport == "") { ?>
<!--<td><span class="phpmaker"><a href="<?php if ($x_Per_Perfil_ID <> "") {echo "vit_perfilview.php?Per_Perfil_ID=" . urlencode($x_Per_Perfil_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">View</a></span></td>-->
<td><span class="phpmaker">
<?php if ($_SESSION["vita_proyecto_Key_Per_Perfil_ID"] == $x_Per_Perfil_ID) { ?>
<a href="" onClick="if (EW_checkMyForm(document.vit_perfillist)) document.vit_perfillist.submit();return false;">Actualizar</a>&nbsp;<a href="perfiles_listado.php?a=cancel">Cancelar</a>
<input type="hidden" name="a_list" value="update">
<?php } else { ?>

	<div class="dropdown">
		<a href="#" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="false">
			<i class="ri-more-2-fill"></i>
		</a>
		
		<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
			<li>
			<a class="dropdown-item" href="<?php if ($x_Per_Perfil_ID <> "") {echo "perfiles_listado.php?a=edit&Per_Perfil_ID=" . urlencode($x_Per_Perfil_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Editar</a>
			</li>
			<li>
			<a class="dropdown-item" href="vit_perfil_accesolist.php?showmaster=1&Per_Perfil_ID=<?php echo urlencode($x_Per_Perfil_ID); ?>">Accesos</a>
			</li>
		</ul>
	</div>
	
<?php } ?>
</span></td>
<!--<td><span class="phpmaker"><a href="<?php if ($x_Per_Perfil_ID <> "") {echo "vit_perfiledit.php?Per_Perfil_ID=" . urlencode($x_Per_Perfil_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a></span></td>-->
<!--<td><span class="phpmaker"><a href="<?php if ($x_Per_Perfil_ID <> "") {echo "vit_perfildelete.php?Per_Perfil_ID=" . urlencode($x_Per_Perfil_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Delete</a></span></td>-->
<!--<td><span class="phpmaker"><a href="vit_perfil_accesolist.php?showmaster=1&Per_Perfil_ID=<?php echo urlencode($x_Per_Perfil_ID); ?>">Accesos</a></span></td>-->
<?php } ?>
	</tr>
<?php
	}
}
?>
</tbody>
</table>
</form>
<?php if (strtoupper($sAction) == "EDIT") { ?>
<?php } ?>
<?php } ?>
</div>
                                        <div class="d-flex justify-content-end mt-3">
                                            <div class="pagination-wrap hstack gap-2">
<?php

// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
<?php if ($sExport == "") { ?>
<form action="perfiles_listado.php" name="ewpagerform" id="ewpagerform">
<table bgcolor="" border="0" cellspacing="1" cellpadding="4" bgcolor="#CCCCCC">
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
	<td><a class="page-item pagination-prev" href="perfiles_listado.php?start=1">|<</a></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($PrevStart == $nStartRec) { ?>
	<td><a class="page-item pagination-prev disabled"><</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-prev" href="perfiles_listado.php?start=<?php echo $PrevStart; ?>"><</a></td>
	<?php } ?>
<!--current page number-->
	<td><input class="form-control form-control-sm" type="text" name="pageno" value="<?php echo intval(($nStartRec-1)/$nDisplayRecs+1); ?>" size="4"></td>
<!--next page button-->
	<?php if ($NextStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">></a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="perfiles_listado.php?start=<?php echo $NextStart; ?>">></a></td>
	<?php  } ?>
<!--last page button-->
	<?php if ($LastStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">>|</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="perfiles_listado.php?start=<?php echo $LastStart; ?>">>|</a></td>
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
<?php } ?>
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
		$_SESSION["vit_perfil_RecPerPage"] = $nDisplayRecs; // Save to Session

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_perfil_REC"] = $nStartRec;
	}else{
		if (@$_SESSION["vit_perfil_RecPerPage"] <> "") {
			$nDisplayRecs = $_SESSION["vit_perfil_RecPerPage"]; // Restore from Session
		}else{
			$nDisplayRecs = 20; // Load Default
		}
	}
}

//-------------------------------------------------------------------------------
// Function SetUpInlineEdit
// - Set up Inline Edit parameters based on querystring parameters a & key
// - Variables setup: sAction, sKey, Session("Proj_InlineEdit_Key")

function SetUpInlineEdit($conn)
{
	global $x_Per_Perfil_ID;

	// Get the keys for master table
	if (strlen(@$_GET["a"]) > 0) {
		$sAction = @$_GET["a"];
		if (strtoupper($sAction) == "EDIT") { // Change to Inline Edit Mode
			$bInlineEdit = true;
			if (strlen(@$_GET["Per_Perfil_ID"]) > 0) {
				$x_Per_Perfil_ID = $_GET["Per_Perfil_ID"];
			}else{
				$bInlineEdit = false;
			}
			if ($bInlineEdit) {
				if (LoadData($conn)) {
					$_SESSION["vita_proyecto_Key_Per_Perfil_ID"] = $x_Per_Perfil_ID; // Set up Inline Edit key
				}
			}
		}
		elseif (strtoupper($sAction) == "CANCEL")  // Switch out of Inline Edit Mode
		{
			$_SESSION["vita_proyecto_Key_Per_Perfil_ID"] = ""; // Clear Inline Edit key
		}
	}
	else
	{
		$sAction = @$_POST["a_list"];
		if (strtoupper($sAction) == "UPDATE") { // Update Record

			// Get fields from form
			global $x_Per_Perfil_ID;
			$x_Per_Perfil_ID = @$_POST["x_Per_Perfil_ID"];
			global $x_Per_Descripcion;
			$x_Per_Descripcion = @$_POST["x_Per_Descripcion"];
			if ($_SESSION["vita_proyecto_Key_Per_Perfil_ID"] == $x_Per_Perfil_ID) {
				if (InlineEditData($conn)) {
					$_SESSION["ewmsg"] = "Update Record Successful";
				}
			}
		}
		$_SESSION["vita_proyecto_Key_Per_Perfil_ID"] = ""; // Clear Inline Edit key
	}
}

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`Per_Descripcion` LIKE '%" . $sKeyword . "%' OR ";
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

		// Field Per_Perfil_ID
		if ($sOrder == "Per_Perfil_ID") {
			$sSortField = "`Per_Perfil_ID`";
			$sLastSort = @$_SESSION["vit_perfil_x_Per_Perfil_ID_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_perfil_x_Per_Perfil_ID_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_perfil_x_Per_Perfil_ID_Sort"] <> "") { @$_SESSION["vit_perfil_x_Per_Perfil_ID_Sort"] = ""; }
		}

		// Field Per_Descripcion
		if ($sOrder == "Per_Descripcion") {
			$sSortField = "`Per_Descripcion`";
			$sLastSort = @$_SESSION["vit_perfil_x_Per_Descripcion_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_perfil_x_Per_Descripcion_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_perfil_x_Per_Descripcion_Sort"] <> "") { @$_SESSION["vit_perfil_x_Per_Descripcion_Sort"] = ""; }
		}
		$_SESSION["vit_perfil_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["vit_perfil_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["vit_perfil_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["vit_perfil_OrderBy"] = $sOrderBy;
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
		$_SESSION["vit_perfil_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["vit_perfil_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["vit_perfil_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["vit_perfil_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["vit_perfil_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["vit_perfil_REC"] = $nStartRec;
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
			$_SESSION["vit_perfil_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["vit_perfil_searchwhere"] = $sSrchWhere;
			$_SESSION["vita_proyecto_InlineEdit_Key"] = ""; // Clear Inline Edit key

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["vit_perfil_OrderBy"] = $sOrderBy;
			if (@$_SESSION["vit_perfil_x_Per_Perfil_ID_Sort"] <> "") { $_SESSION["vit_perfil_x_Per_Perfil_ID_Sort"] = ""; }
			if (@$_SESSION["vit_perfil_x_Per_Descripcion_Sort"] <> "") { $_SESSION["vit_perfil_x_Per_Descripcion_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_perfil_REC"] = $nStartRec;
	}
}
?>
<?php

//-------------------------------------------------------------------------------
// Function LoadData
// - Load Data based on Key Value sKey
// - Variables setup: field variables

function LoadData($conn)
{
	global $x_Per_Perfil_ID;
	$sSql = "SELECT * FROM `vit_perfil`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Per_Perfil_ID) : $x_Per_Perfil_ID;
	$sWhere .= "(`Per_Perfil_ID` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_Per_Perfil_ID"] = $row["Per_Perfil_ID"];
		$GLOBALS["x_Per_Descripcion"] = $row["Per_Descripcion"];
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

function InlineEditData($conn)
{
	global $x_Per_Perfil_ID;
	$sSql = "SELECT * FROM `vit_perfil`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Per_Perfil_ID) : $x_Per_Perfil_ID;	
	$sWhere .= "(`Per_Perfil_ID` = " . addslashes($sTmp) . ")";
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
		$bInlineEditData = false; // Update Failed
	}else{
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Per_Descripcion"]) : $GLOBALS["x_Per_Descripcion"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Per_Descripcion`"] = $theValue;

		// update
		$sSql = "UPDATE `vit_perfil` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
		$bInlineEditData = true; // Update Successful
	}
	return $bInlineEditData;
}
?>
