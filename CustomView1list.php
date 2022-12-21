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
$x_arcdet_rfc_vita = Null; 
$ox_arcdet_rfc_vita = Null;
$x_arcdet_seriefolio = Null; 
$ox_arcdet_seriefolio = Null;
$x_arcdet_nombre = Null; 
$ox_arcdet_nombre = Null;
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
	header('Content-Disposition: attachment; filename=CustomView1.xls');
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
$x_arcdet_archivoID = @$_GET["arcdet_archivoID"]; // Load Parameter from QueryString

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
	$_SESSION["CustomView1_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["CustomView1_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["CustomView1_searchwhere"];
}

// Build SQL
$sSql = "
SELECT archivos_rosarito_percep_deduc.arcdet_folio, archivos_rosarito_percep_deduc.arcdet_rfc_vita, archivos_rosarito_percep_deduc.arcdet_seriefolio, archivos_rosarito_percep_deduc.arcdet_nombre, archivos_rosarito.arc_archivoRuta FROM archivos_rosarito_percep_deduc INNER JOIN archivos_rosarito ON (archivos_rosarito_percep_deduc.arcdet_archivoID=archivos_rosarito.arc_archivoID)
";
$sDefaultFilter = "";
if($x_arcdet_archivoID!=""){
$sTmp = (!(function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())) ? addslashes($x_arcdet_archivoID) : $x_arcdet_archivoID;
$sDefaultFilter .= "`arc_archivoID` =  " . $sTmp . "";
}
#$sGroupBy = "arcdet_nombre";
$sGroupBy = "";
$sHaving = "";
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

#echo "***".$sSql; // Uncomment to show SQL for debugging
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
        
        <title>Recibos - Detalle | VitaInsumos</title>
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
                                <h4 class="mb-sm-0">Recibos - Detalle</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Modulos</a></li>
                                        <li class="breadcrumb-item active">Recibos - Detalle</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
					<div class="row">
                        <div class="col-lg-12">
                            <div class="card" id="leadsList">
                                <div class="card-header border-0">

                                    
<?php
if ($x_arcdet_archivoID <> "") {
	
	$sSqlMasterBase = "SELECT * FROM `archivos_rosarito` WHERE arc_archivoID = '".$x_arcdet_archivoID."' ";
	$rs = phpmkr_query($sSqlMasterBase, $conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSqlMasterBase);
	$bMasterRecordExist = (phpmkr_num_rows($rs) > 0);
	if (!$bMasterRecordExist) {
		#$_SESSION["_MasterWhere"] = "";
		#$_SESSION["archivos_rosarito_percep_deduc_DetailWhere"] = "";
		$_SESSION["ewmsg"] = "No records found";
		phpmkr_free_result($rs);
		phpmkr_db_close($conn);
		header("Location: archivos_rosaritolist.php");
	}
	
	?>
	<?php
		$row = phpmkr_fetch_array($rs);
		$x_arc_archivoID = $row["arc_archivoID"];
		$x_arc_archivoRuta = $row["arc_archivoRuta"];
		$x_Mun_ID = $row["Mun_ID"];
		$x_Emi_RFC = $row["Emi_RFC"];
		$x_arc_fechaCarga = $row["arc_fechaCarga"];
		$x_arc_fechaAct = $row["arc_fechaAct"];
		$x_arc_usuarioReg = $row["arc_usuarioReg"];
?>
	<div class="row g-4 align-items-center">
                                        <div class="col-sm-3">
                                            <div class="search-box">
												<form action="CustomView1list.php">
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
                                               <a class="btn btn-primary waves-effect waves-light" href="archivos_listado.php">Regresar</a>
											   <a class="btn btn-primary waves-effect waves-light" href="CustomView1list.php?cmd=reset&cmd=resetall&arcdet_archivoID=<?php echo urlencode($x_arc_archivoID); ?>">Todos</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <div class="table-responsive table-card">
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
	<tr bgcolor="#FFFFFF">

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
	</tbody>
</table
<?php
	phpmkr_free_result($rs);
}
?>
<?php

// Set up Record Set
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error() . '<br>SQL: ' . $sSql);
$nTotalRecs = phpmkr_num_rows($rs);
if ($nDisplayRecs <= 0) { // Display All Records
	$nDisplayRecs = $nTotalRecs;
}
$nStartRec = 1;
SetUpStartRec(); // Set Up Start Record Position
?>
<?php if ($nTotalRecs > 0)  { ?>
<form method="post">
<table class="table align-middle" id="customerTable">
	<thead class="table-light">
		<tr>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Rfc Vita
<?php }else{ ?>
	<a href="CustomView1list.php?order=<?php echo urlencode("arcdet_rfc_vita"); ?>">Rfc Vita&nbsp;<?php if (@$_SESSION["CustomView1_x_arcdet_rfc_vita_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["CustomView1_x_arcdet_rfc_vita_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Serie Folio
<?php }else{ ?>
	<a href="CustomView1list.php?order=<?php echo urlencode("arcdet_seriefolio"); ?>">Serie Folio&nbsp;<?php if (@$_SESSION["CustomView1_x_arcdet_seriefolio_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["CustomView1_x_arcdet_seriefolio_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Empleado
<?php }else{ ?>
	<a href="CustomView1list.php?order=<?php echo urlencode("arcdet_nombre"); ?>">Empleado&nbsp;<?php if (@$_SESSION["CustomView1_x_arcdet_nombre_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["CustomView1_x_arcdet_nombre_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
		<th valign="top"><span>
<?php if ($sExport <> "") { ?>
Detalle
<?php }else{ ?>
	<a href="CustomView1list.php?order=<?php echo urlencode("arc_archivoRuta"); ?>">Detalle&nbsp;<?php if (@$_SESSION["CustomView1_x_arc_archivoRuta_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["CustomView1_x_arc_archivoRuta_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></th>
<?php if ($sExport == "") { ?>
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
		$x_arcdet_rfc_vita = $row["arcdet_rfc_vita"];
		$x_arcdet_seriefolio = $row["arcdet_seriefolio"];
		$x_arcdet_nombre = $row["arcdet_nombre"];
		$x_arc_archivoRuta = $row["arc_archivoRuta"];
		$arcdet_folio = $row["arcdet_folio"];
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- arcdet_rfc_vita -->
		<td><span class="phpmaker">
<?php echo $x_arcdet_rfc_vita; ?>
</span></td>
		<!-- arcdet_seriefolio -->
		<td><span class="phpmaker">
<?php echo $x_arcdet_seriefolio; ?>
</span></td>
		<!-- arcdet_nombre -->
		<td><span class="phpmaker">
<?php echo $x_arcdet_nombre; ?>
</span></td>
		<!-- arc_archivoRuta -->
		<td><span class="phpmaker">
<?php if ((!is_null($x_arc_archivoRuta)) &&  $x_arc_archivoRuta <> "") { ?>
<a href="archivos_rosarito_percep_deduclist.php?showmaster=1&arcdet_archivoID=<?php echo urlencode($x_arc_archivoID); ?>&arcdet_folio=<?php echo urlencode($arcdet_folio); ?>">Percepciones/Deducciones</a>
<?php } ?>
</span></td>
<?php if ($sExport == "") { ?>
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
<form action="CustomView1list.php" name="ewpagerform" id="ewpagerform">
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
	<table border="0" cellspacing="0" cellpadding="2"><tr><td><span class="phpmaker">Pagina &nbsp;</span></td>
<!--first page button-->
	<?php if ($nStartRec == 1) { ?>
	<td><a class="page-item pagination-prev disabled" href="#">|<</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-prev" href="CustomView1list.php?start=1&cmd=resetall&arcdet_archivoID=<?php echo urlencode($x_arc_archivoID); ?>">|<</a></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($PrevStart == $nStartRec) { ?>
	<td><a class="page-item pagination-prev disabled"><</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-prev" href="CustomView1list.php?start=<?php echo $PrevStart; ?>&cmd=resetall&arcdet_archivoID=<?php echo urlencode($x_arc_archivoID); ?>"><</a></td>
	<?php } ?>
<!--current page number-->
	<td><input class="form-control form-control-sm" type="text" name="pageno" value="<?php echo intval(($nStartRec-1)/$nDisplayRecs+1); ?>" size="4"></td>
<!--next page button-->
	<?php if ($NextStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">></a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="CustomView1list.php?start=<?php echo $NextStart; ?>&cmd=resetall&arcdet_archivoID=<?php echo urlencode($x_arc_archivoID); ?>">></a></td>
	<?php  } ?>
<!--last page button-->
	<?php if ($LastStart == $nStartRec) { ?>
	<td><a class="page-item pagination-next disabled" href="#">>|</a></td>
	<?php } else { ?>
	<td><a class="page-item pagination-next" href="CustomView1list.php?start=<?php echo $LastStart; ?>&cmd=resetall&arcdet_archivoID=<?php echo urlencode($x_arc_archivoID); ?>">>|</a></td>
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
<option value="ALL"<?php if (@$_SESSION["archivos_rosarito_RecPerPage"] == -1) { echo " selected";  }?>>Todos</option>
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

// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
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
		$_SESSION["CustomView1_RecPerPage"] = $nDisplayRecs; // Save to Session

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["CustomView1_REC"] = $nStartRec;
	}else{
		if (@$_SESSION["CustomView1_RecPerPage"] <> "") {
			$nDisplayRecs = $_SESSION["CustomView1_RecPerPage"]; // Restore from Session
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
	$sKeyword = (!(function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "archivos_rosarito_percep_deduc.arcdet_rfc_vita LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "archivos_rosarito_percep_deduc.arcdet_seriefolio LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "archivos_rosarito_percep_deduc.arcdet_nombre LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "archivos_rosarito.arc_archivoRuta LIKE '%" . $sKeyword . "%' OR ";
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
	$sSearch = (!(function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc())) ? addslashes(@$_GET["psearch"]) : @$_GET["psearch"];
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

		// Field arcdet_rfc_vita
		if ($sOrder == "arcdet_rfc_vita") {
			$sSortField = "archivos_rosarito_percep_deduc.arcdet_rfc_vita";
			$sLastSort = @$_SESSION["CustomView1_x_arcdet_rfc_vita_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["CustomView1_x_arcdet_rfc_vita_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["CustomView1_x_arcdet_rfc_vita_Sort"] <> "") { @$_SESSION["CustomView1_x_arcdet_rfc_vita_Sort"] = ""; }
		}

		// Field arcdet_seriefolio
		if ($sOrder == "arcdet_seriefolio") {
			$sSortField = "archivos_rosarito_percep_deduc.arcdet_seriefolio";
			$sLastSort = @$_SESSION["CustomView1_x_arcdet_seriefolio_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["CustomView1_x_arcdet_seriefolio_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["CustomView1_x_arcdet_seriefolio_Sort"] <> "") { @$_SESSION["CustomView1_x_arcdet_seriefolio_Sort"] = ""; }
		}

		// Field arcdet_nombre
		if ($sOrder == "arcdet_nombre") {
			$sSortField = "archivos_rosarito_percep_deduc.arcdet_nombre";
			$sLastSort = @$_SESSION["CustomView1_x_arcdet_nombre_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["CustomView1_x_arcdet_nombre_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["CustomView1_x_arcdet_nombre_Sort"] <> "") { @$_SESSION["CustomView1_x_arcdet_nombre_Sort"] = ""; }
		}

		// Field arc_archivoRuta
		if ($sOrder == "arc_archivoRuta") {
			$sSortField = "archivos_rosarito.arc_archivoRuta";
			$sLastSort = @$_SESSION["CustomView1_x_arc_archivoRuta_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["CustomView1_x_arc_archivoRuta_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["CustomView1_x_arc_archivoRuta_Sort"] <> "") { @$_SESSION["CustomView1_x_arc_archivoRuta_Sort"] = ""; }
		}
		$_SESSION["CustomView1_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["CustomView1_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["CustomView1_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["CustomView1_OrderBy"] = $sOrderBy;
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
		$_SESSION["CustomView1_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["CustomView1_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["CustomView1_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["CustomView1_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["CustomView1_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["CustomView1_REC"] = $nStartRec;
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
			$_SESSION["CustomView1_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["CustomView1_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["CustomView1_OrderBy"] = $sOrderBy;
			if (@$_SESSION["CustomView1_x_arcdet_rfc_vita_Sort"] <> "") { $_SESSION["CustomView1_x_arcdet_rfc_vita_Sort"] = ""; }
			if (@$_SESSION["CustomView1_x_arcdet_seriefolio_Sort"] <> "") { $_SESSION["CustomView1_x_arcdet_seriefolio_Sort"] = ""; }
			if (@$_SESSION["CustomView1_x_arcdet_nombre_Sort"] <> "") { $_SESSION["CustomView1_x_arcdet_nombre_Sort"] = ""; }
			if (@$_SESSION["CustomView1_x_arc_archivoRuta_Sort"] <> "") { $_SESSION["CustomView1_x_arc_archivoRuta_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["CustomView1_REC"] = $nStartRec;
	}
}
?>
