<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Pragma: no-cache"); // HTTP/1.0 
?>
<?php
$ewCurSec = 0; // Initialise

// User levels
define("ewAllowadd", 1, '');
define("ewAllowdelete", 2, '');
define("ewAllowedit", 4, '');
define("ewAllowview", 8, '');
define("ewAllowlist", 8, '');
define("ewAllowreport", 8, '');
define("ewAllowsearch", 8, '');																														
define("ewAllowadmin", 16, '');						
?>
<?php
if (@$_SESSION["project1_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
?>
<?php

// Initialize common variables
$x_Vit_Usuario = Null; 
$ox_Vit_Usuario = Null;
$x_Vit_Contrasena = Null; 
$ox_Vit_Contrasena = Null;
$x_Vit_Nombre = Null; 
$ox_Vit_Nombre = Null;
$x_Mun_ID = Null; 
$ox_Mun_ID = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=vit_usuarios.xls');
}
?>
<?php include ("libs/db.php") ?>
<?php include ("libs/db.class.php") ?>
<?php include ("libs/db_login.class.php") ?>
<?php //include ("phpmkrfn.php") ?>
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
	$_SESSION["vit_usuarios_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["vit_usuarios_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["vit_usuarios_searchwhere"];
}

// Build SQL
$sSql = "SELECT * FROM `vit_usuarios`";

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
<?php include ("header.php") ?>
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
if (EW_this.x_Vit_Usuario && !EW_hasValue(EW_this.x_Vit_Usuario, "TEXT" )) {
	if (!EW_onError(EW_this, EW_this.x_Vit_Usuario, "TEXT", "Please enter required field - Usuario"))
		return false;
}
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
<p><span class="phpmaker">TABLE: Usuarios
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="vit_usuarioslist.php?export=excel">Export to Excel</a>
<?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<form action="vit_usuarioslist.php">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker">
			<input type="text" name="psearch" size="20">
			<input type="Submit" name="Submit" value="Search &nbsp;(*)">&nbsp;&nbsp;
			<a href="vit_usuarioslist.php?cmd=reset">Show all</a>&nbsp;&nbsp;
		</span></td>
	</tr>
	<tr><td><span class="phpmaker"><input type="radio" name="psearchtype" value="" checked>Exact phrase&nbsp;&nbsp;<input type="radio" name="psearchtype" value="AND">All words&nbsp;&nbsp;<input type="radio" name="psearchtype" value="OR">Any word</span></td></tr>
</table>
</form>
<?php } ?>
<?php if ($sExport == "") { ?>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker"><a href="vit_usuariosadd.php">Add</a></span></td>
	</tr>
</table>
<p>
<?php } ?>
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $_SESSION["ewmsg"]; ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>
<?php if ($nTotalRecs > 0)  { ?>
<form name="vit_usuarioslist" id="vit_usuarioslist" action="vit_usuarioslist.php" method="post">
<table border="0" cellspacing="1" cellpadding="4" bgcolor="#CCCCCC">
	<!-- Table header -->
	<tr bgcolor="#666666">
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
Usuario
<?php }else{ ?>
	<a href="vit_usuarioslist.php?order=<?php echo urlencode("Vit_Usuario"); ?>" class="phpmaker" style="color: #FFFFFF;">Usuario&nbsp;(*)<?php if (@$_SESSION["vit_usuarios_x_Vit_Usuario_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_usuarios_x_Vit_Usuario_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
Contrasena
<?php }else{ ?>
	<a href="vit_usuarioslist.php?order=<?php echo urlencode("Vit_Contrasena"); ?>" class="phpmaker" style="color: #FFFFFF;">Contrasena&nbsp;(*)<?php if (@$_SESSION["vit_usuarios_x_Vit_Contrasena_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_usuarios_x_Vit_Contrasena_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
Nombre
<?php }else{ ?>
	<a href="vit_usuarioslist.php?order=<?php echo urlencode("Vit_Nombre"); ?>" class="phpmaker" style="color: #FFFFFF;">Nombre&nbsp;(*)<?php if (@$_SESSION["vit_usuarios_x_Vit_Nombre_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_usuarios_x_Vit_Nombre_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
Mun ID
<?php }else{ ?>
	<a href="vit_usuarioslist.php?order=<?php echo urlencode("Mun_ID"); ?>" class="phpmaker" style="color: #FFFFFF;">Mun ID<?php if (@$_SESSION["vit_usuarios_x_Mun_ID_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_usuarios_x_Mun_ID_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
<?php if ($sExport == "") { ?>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
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
		$x_Vit_Usuario = $row["Vit_Usuario"];
		$x_Vit_Contrasena = $row["Vit_Contrasena"];
		$x_Vit_Nombre = $row["Vit_Nombre"];
		$x_Mun_ID = $row["Mun_ID"];
	#$bEditRow = (($_SESSION["project1_Key_Vit_Usuario"] == $x_Vit_Usuario && $_SESSION["project1_Key_Mun_ID"] == $x_Mun_ID) && ($nEditRowCnt == 0));
	$bEditRow = (($_SESSION["project1_Key_Vit_Usuario"] == $x_Vit_Usuario) && ($nEditRowCnt == 0));
	if ($bEditRow) {
		$nEditRowCnt++;
		$sItemRowClass = " bgcolor=\"#FFFF99\"";
	}
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- Vit_Usuario -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php echo $x_Vit_Usuario; ?><input type="hidden" id="x_Vit_Usuario" name="x_Vit_Usuario" value="<?php echo htmlspecialchars(@$x_Vit_Usuario); ?>">
<?php }else{ ?>
<?php echo $x_Vit_Usuario; ?>
<?php } ?>
</span></td>
		<!-- Vit_Contrasena -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<input type="text" name="x_Vit_Contrasena" id="x_Vit_Contrasena" size="30" maxlength="64" value="<?php echo htmlspecialchars(@$x_Vit_Contrasena) ?>">
<?php }else{ ?>
<?php echo $x_Vit_Contrasena; ?>
<?php } ?>
</span></td>
		<!-- Vit_Nombre -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<input type="text" name="x_Vit_Nombre" id="x_Vit_Nombre" size="30" maxlength="180" value="<?php echo htmlspecialchars(@$x_Vit_Nombre) ?>">
<?php }else{ ?>
<?php echo $x_Vit_Nombre; ?>
<?php } ?>
</span></td>
		<!-- Mun_ID -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php
if ((!is_null($x_Mun_ID)) && ($x_Mun_ID <> "")) {
	$sSqlWrk = "SELECT DISTINCT `Mun_Descrip`, Mun_ID FROM `vit_municipios`";
	#$sTmp = $x_Mun_ID;
	#$sTmp = addslashes($sTmp);
	#$sSqlWrk .= " WHERE `Mun_ID` = " . $sTmp . "";
	$sSqlWrk .= " ORDER BY `Mun_Descrip` Asc";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
	if ($rswrk) {
?>
		<select name="x_Mun_ID" id="x_Mun_ID" class="phpmaker">
<?php		
		while (($rowwrk = @phpmkr_fetch_array($rswrk))) {
			$selected = ($rowwrk["Mun_ID"]==$x_Mun_ID)?' selected':'';
?>
		<option value="<?php echo $rowwrk["Mun_ID"]; ?>"<?php echo $selected; ?>><?php echo $rowwrk["Mun_Descrip"]; ?></option>
<?php 
		}
?>		
		</select>
<?php
		#$sTmp = $rowwrk["Mun_Descrip"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_Mun_ID = $x_Mun_ID; // Backup Original Value
$x_Mun_ID = $sTmp;
?>
<?php #echo $x_Mun_ID; ?>
<?php #$x_Mun_ID = $ox_Mun_ID; // Restore Original Value ?>
<!--<input type="hidden" id="x_Mun_ID" name="x_Mun_ID" value="<?php echo htmlspecialchars(@$x_Mun_ID); ?>">-->
<?php }else{ ?>
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
<?php } ?>
</span></td>
<?php if ($sExport == "") { ?>
<td><span class="phpmaker"><a href="<?php if ($x_Vit_Usuario <> "" AND $x_Mun_ID <> "") {echo "vit_usuariosview.php?Vit_Usuario=" . urlencode($x_Vit_Usuario) . "&Mun_ID=" . urlencode($x_Mun_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">View</a></span></td>
<td><span class="phpmaker">
<?php 
#if ($_SESSION["project1_Key_Vit_Usuario"] == $x_Vit_Usuario && $_SESSION["project1_Key_Mun_ID"] == $x_Mun_ID) { 
if ($_SESSION["project1_Key_Vit_Usuario"] == $x_Vit_Usuario) {
?>
<a href="" onClick="if (EW_checkMyForm(document.vit_usuarioslist)) document.vit_usuarioslist.submit();return false;">Update</a>&nbsp;<a href="vit_usuarioslist.php?a=cancel">Cancel</a>
<input type="hidden" name="a_list" value="update">
<?php } else { ?>
<a href="<?php if ($x_Vit_Usuario <> "" AND $x_Mun_ID <> "") {echo "vit_usuarioslist.php?a=edit&Vit_Usuario=" . urlencode($x_Vit_Usuario); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Inline Edit</a>
<?php } ?>
</span></td>
<td><span class="phpmaker"><a href="<?php if ($x_Vit_Usuario <> "" AND $x_Mun_ID <> "") {echo "vit_usuariosedit.php?Vit_Usuario=" . urlencode($x_Vit_Usuario) . "&Mun_ID=" . urlencode($x_Mun_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_Vit_Usuario <> "" AND $x_Mun_ID <> "") {echo "vit_usuariosdelete.php?Vit_Usuario=" . urlencode($x_Vit_Usuario) . "&Mun_ID=" . urlencode($x_Mun_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Delete</a></span></td>
<?php } ?>
	</tr>
<?php
	}
}
?>
</table>
</form>
<?php if (strtoupper($sAction) == "EDIT") { ?>
<?php } ?>
<?php } ?>
<?php

// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
<?php if ($sExport == "") { ?>
<form action="vit_usuarioslist.php" name="ewpagerform" id="ewpagerform">
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
	<table border="0" cellspacing="0" cellpadding="0"><tr><td><span class="phpmaker">Page&nbsp;</span></td>
<!--first page button-->
	<?php if ($nStartRec == 1) { ?>
	<td><img src="images/firstdisab.gif" alt="First" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="vit_usuarioslist.php?start=1"><img src="images/first.gif" alt="First" width="16" height="16" border="0"></a></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($PrevStart == $nStartRec) { ?>
	<td><img src="images/prevdisab.gif" alt="Previous" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="vit_usuarioslist.php?start=<?php echo $PrevStart; ?>"><img src="images/prev.gif" alt="Previous" width="16" height="16" border="0"></a></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="pageno" value="<?php echo intval(($nStartRec-1)/$nDisplayRecs+1); ?>" size="4"></td>
<!--next page button-->
	<?php if ($NextStart == $nStartRec) { ?>
	<td><img src="images/nextdisab.gif" alt="Next" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="vit_usuarioslist.php?start=<?php echo $NextStart; ?>"><img src="images/next.gif" alt="Next" width="16" height="16" border="0"></a></td>
	<?php  } ?>
<!--last page button-->
	<?php if ($LastStart == $nStartRec) { ?>
	<td><img src="images/lastdisab.gif" alt="Last" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="vit_usuarioslist.php?start=<?php echo $LastStart; ?>"><img src="images/last.gif" alt="Last" width="16" height="16" border="0"></a></td>
	<?php } ?>
	<td><span class="phpmaker">&nbsp;of <?php echo intval(($nTotalRecs-1)/$nDisplayRecs+1);?></span></td>
	</tr></table>
	<?php if ($nStartRec > $nTotalRecs) { $nStartRec = $nTotalRecs; }
	$nStopRec = $nStartRec + $nDisplayRecs - 1;
	$nRecCount = $nTotalRecs - 1;
	if ($rsEof) { $nRecCount = $nTotalRecs; }
	if ($nStopRec > $nRecCount) { $nStopRec = $nRecCount; } ?>
	<span class="phpmaker">Records <?php echo $nStartRec; ?> to <?php echo $nStopRec; ?> of <?php echo $nTotalRecs; ?></span>
<?php } else { ?>
	<span class="phpmaker">No records found</span>
<?php } ?>
		</td>
<?php if ($nTotalRecs > 0) { ?>
		<td nowrap>&nbsp;&nbsp;&nbsp;&nbsp;</td>
		<td align="right" valign="top" nowrap><span class="phpmaker">Records Per Page&nbsp;
<select name="RecPerPage" onChange="this.form.pageno.value = 1;this.form.submit();" class="phpmaker">
<option value="20"<?php if ($nDisplayRecs == 20) { echo " selected";  }?>>20</option>
<option value="50"<?php if ($nDisplayRecs == 50) { echo " selected";  }?>>50</option>
<option value="100"<?php if ($nDisplayRecs == 100) { echo " selected";  }?>>100</option>
<option value="ALL"<?php if (@$_SESSION["vit_usuarios_RecPerPage"] == -1) { echo " selected";  }?>>All Records</option>
</select>
		</span></td>
<?php } ?>
	</tr>
</table>
</form>
<?php } ?>
<?php if ($sExport <> "word" && $sExport <> "excel") { ?>
<?php include ("footer.php") ?>
<?php } ?>
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
		$_SESSION["vit_usuarios_RecPerPage"] = $nDisplayRecs; // Save to Session

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_usuarios_REC"] = $nStartRec;
	}else{
		if (@$_SESSION["vit_usuarios_RecPerPage"] <> "") {
			$nDisplayRecs = $_SESSION["vit_usuarios_RecPerPage"]; // Restore from Session
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
	global $x_Vit_Usuario;
	global $x_Mun_ID;

	// Get the keys for master table
	if (strlen(@$_GET["a"]) > 0) {
		$sAction = @$_GET["a"];
		if (strtoupper($sAction) == "EDIT") { // Change to Inline Edit Mode
			$bInlineEdit = true;
			if (strlen(@$_GET["Vit_Usuario"]) > 0) {
				$x_Vit_Usuario = $_GET["Vit_Usuario"];
			}else{
				$bInlineEdit = false;
			}
			/*if (strlen(@$_GET["Mun_ID"]) > 0) {
				$x_Mun_ID = $_GET["Mun_ID"];
			}else{
				$bInlineEdit = false;
			}*/
			if ($bInlineEdit) {
				if (LoadData($conn)) {
					$_SESSION["project1_Key_Vit_Usuario"] = $x_Vit_Usuario; // Set up Inline Edit key
					//$_SESSION["project1_Key_Mun_ID"] = $x_Mun_ID; // Set up Inline Edit key
				}
			}
		}
		elseif (strtoupper($sAction) == "CANCEL")  // Switch out of Inline Edit Mode
		{
			$_SESSION["project1_Key_Vit_Usuario"] = ""; // Clear Inline Edit key
			//$_SESSION["project1_Key_Mun_ID"] = ""; // Clear Inline Edit key
		}
	}
	else
	{
		$sAction = @$_POST["a_list"];
		if (strtoupper($sAction) == "UPDATE") { // Update Record

			// Get fields from form
			global $x_Vit_Usuario;
			$x_Vit_Usuario = @$_POST["x_Vit_Usuario"];
			global $x_Vit_Contrasena;
			$x_Vit_Contrasena = @$_POST["x_Vit_Contrasena"];
			global $x_Vit_Nombre;
			$x_Vit_Nombre = @$_POST["x_Vit_Nombre"];
			global $x_Mun_ID;
			$x_Mun_ID = @$_POST["x_Mun_ID"];
			#if ($_SESSION["project1_Key_Vit_Usuario"] == $x_Vit_Usuario && $_SESSION["project1_Key_Mun_ID"] == $x_Mun_ID) {
				if ($_SESSION["project1_Key_Vit_Usuario"] == $x_Vit_Usuario) {
				if (InlineEditData($conn)) {
					$_SESSION["ewmsg"] = "Update Record Successful";
				}
			}
		}
		$_SESSION["project1_Key_Vit_Usuario"] = ""; // Clear Inline Edit key
		$_SESSION["project1_Key_Mun_ID"] = ""; // Clear Inline Edit key
	}
}

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`Vit_Usuario` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Vit_Contrasena` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Vit_Nombre` LIKE '%" . $sKeyword . "%' OR ";
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

		// Field Vit_Usuario
		if ($sOrder == "Vit_Usuario") {
			$sSortField = "`Vit_Usuario`";
			$sLastSort = @$_SESSION["vit_usuarios_x_Vit_Usuario_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_usuarios_x_Vit_Usuario_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_usuarios_x_Vit_Usuario_Sort"] <> "") { @$_SESSION["vit_usuarios_x_Vit_Usuario_Sort"] = ""; }
		}

		// Field Vit_Contrasena
		if ($sOrder == "Vit_Contrasena") {
			$sSortField = "`Vit_Contrasena`";
			$sLastSort = @$_SESSION["vit_usuarios_x_Vit_Contrasena_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_usuarios_x_Vit_Contrasena_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_usuarios_x_Vit_Contrasena_Sort"] <> "") { @$_SESSION["vit_usuarios_x_Vit_Contrasena_Sort"] = ""; }
		}

		// Field Vit_Nombre
		if ($sOrder == "Vit_Nombre") {
			$sSortField = "`Vit_Nombre`";
			$sLastSort = @$_SESSION["vit_usuarios_x_Vit_Nombre_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_usuarios_x_Vit_Nombre_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_usuarios_x_Vit_Nombre_Sort"] <> "") { @$_SESSION["vit_usuarios_x_Vit_Nombre_Sort"] = ""; }
		}

		// Field Mun_ID
		if ($sOrder == "Mun_ID") {
			$sSortField = "`Mun_ID`";
			$sLastSort = @$_SESSION["vit_usuarios_x_Mun_ID_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_usuarios_x_Mun_ID_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_usuarios_x_Mun_ID_Sort"] <> "") { @$_SESSION["vit_usuarios_x_Mun_ID_Sort"] = ""; }
		}
		$_SESSION["vit_usuarios_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["vit_usuarios_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["vit_usuarios_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["vit_usuarios_OrderBy"] = $sOrderBy;
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
		$_SESSION["vit_usuarios_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["vit_usuarios_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["vit_usuarios_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["vit_usuarios_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["vit_usuarios_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["vit_usuarios_REC"] = $nStartRec;
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
			$_SESSION["vit_usuarios_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["vit_usuarios_searchwhere"] = $sSrchWhere;
			$_SESSION["project1_InlineEdit_Key"] = ""; // Clear Inline Edit key

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["vit_usuarios_OrderBy"] = $sOrderBy;
			if (@$_SESSION["vit_usuarios_x_Vit_Usuario_Sort"] <> "") { $_SESSION["vit_usuarios_x_Vit_Usuario_Sort"] = ""; }
			if (@$_SESSION["vit_usuarios_x_Vit_Contrasena_Sort"] <> "") { $_SESSION["vit_usuarios_x_Vit_Contrasena_Sort"] = ""; }
			if (@$_SESSION["vit_usuarios_x_Vit_Nombre_Sort"] <> "") { $_SESSION["vit_usuarios_x_Vit_Nombre_Sort"] = ""; }
			if (@$_SESSION["vit_usuarios_x_Mun_ID_Sort"] <> "") { $_SESSION["vit_usuarios_x_Mun_ID_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_usuarios_REC"] = $nStartRec;
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
	global $x_Vit_Usuario;
	global $x_Mun_ID;
	
	$bLoadData = false;
	$conn2 = new LoginMethods();
	$resultUser = $conn2->getUser($x_Vit_Usuario);
	if(!is_null($resultUser)){
		$rowUser = $resultUser->fetch_assoc();
		$GLOBALS["x_Vit_Usuario"] = $rowUser["Vit_Usuario"];
		$GLOBALS["x_Vit_Contrasena"] = $rowUser["Vit_Contrasena"];
		$GLOBALS["x_Vit_Nombre"] = $rowUser["Vit_Nombre"];
		$GLOBALS["x_Mun_ID"] = $rowUser["Mun_ID"];
		$bLoadData = true;
	}
			
	/*$sSql = "SELECT * FROM `vit_usuarios`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Vit_Usuario) : $x_Vit_Usuario;
	$sWhere .= "(`Vit_Usuario` = '" . addslashes($sTmp) . "')";
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
		$GLOBALS["x_Vit_Usuario"] = $row["Vit_Usuario"];
		$GLOBALS["x_Vit_Contrasena"] = $row["Vit_Contrasena"];
		$GLOBALS["x_Vit_Nombre"] = $row["Vit_Nombre"];
		$GLOBALS["x_Mun_ID"] = $row["Mun_ID"];
	}
	#phpmkr_free_result($rs);*/
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
	global $x_Vit_Usuario;
	global $x_Mun_ID;
	$sWhere = "";
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Vit_Usuario) : $x_Vit_Usuario;	
	$sWhere .= "(`Vit_Usuario` = '" . addslashes($sTmp) . "')";
	/*$sSql = "SELECT * FROM `vit_usuarios`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Vit_Usuario) : $x_Vit_Usuario;	
	$sWhere .= "(`Vit_Usuario` = '" . addslashes($sTmp) . "')";
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
	$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);*/
	$conn2 = new LoginMethods();
	$resultUser = $conn2->getUser($x_Vit_Usuario);
	$row_cnt = $resultUser->num_rows;
	if(!is_null($resultUser) && $row_cnt == 0) {
		$bInlineEditData = false; // Update Failed
	}else{
		$rowUser = $resultUser->fetch_assoc();
		#$GLOBALS["x_Vit_Usuario"] = $rowUser["Vit_Usuario"];
		#$GLOBALS["x_Vit_Contrasena"] = $rowUser["Vit_Contrasena"];
		#$GLOBALS["x_Vit_Nombre"] = $rowUser["Vit_Nombre"];
		#$GLOBALS["x_Mun_ID"] = $rowUser["Mun_ID"];
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Vit_Usuario"]) : $GLOBALS["x_Vit_Usuario"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Vit_Usuario`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Vit_Contrasena"]) : $GLOBALS["x_Vit_Contrasena"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Vit_Contrasena`"] = $theValue;
		$theValue = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($GLOBALS["x_Vit_Nombre"]) : $GLOBALS["x_Vit_Nombre"]; 
		$theValue = ($theValue != "") ? " '" . $theValue . "'" : "NULL";
		$fieldList["`Vit_Nombre`"] = $theValue;
		$theValue = ($GLOBALS["x_Mun_ID"] != "") ? intval($GLOBALS["x_Mun_ID"]) : "NULL";
		$fieldList["`Mun_ID`"] = $theValue;

		// update
		/*$sSql = "UPDATE `vit_usuarios` SET ";
		foreach ($fieldList as $key=>$temp) {
			$sSql .= "$key = $temp, ";
		}
		if (substr($sSql, -2) == ", ") {
			$sSql = substr($sSql, 0, strlen($sSql)-2);
		}
		$sSql .= " WHERE " . $sWhere;
		#phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
		$conn2->query($sSql);*/
		$bInlineEditData = $conn2->setUser($fieldList, $sWhere);
	}
	return $bInlineEditData;
}
?>
