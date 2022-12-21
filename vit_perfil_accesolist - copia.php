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
$x_Acc_Acceso_ID = Null; 
$ox_Acc_Acceso_ID = Null;
$x_Per_Perfil_ID = Null; 
$ox_Per_Perfil_ID = Null;
$x_Mod_Modulo_ID = Null; 
$ox_Mod_Modulo_ID = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=vit_perfil_acceso.xls');
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
$bMasterRecordExist = false;
$x_Per_Descripcion = Null;
$ox_Per_Descripcion = Null;
$nDisplayRecs = 20;
$nRecRange = 10;

// Set up records per page dynamically
SetUpDisplayRecs();

// Open connection to the database
$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);

// Handle Reset Command
ResetCmd();

// Set Up Master Detail Parameters
SetUpMasterDetail();

// Set Up Inline Edit Parameters
$sAction = "";
SetUpInlineEdit($conn);

// Build SQL
$sSql = "SELECT * FROM `vit_perfil_acceso`";

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
// Build Master Record SQL

if ($sDbWhereMaster <> "") {
	$sSqlMasterBase = "SELECT * FROM `vit_perfil`";
	$sWhereMaster = "";
	$sGroupByMaster = "";
	$sHavingMaster = "";
	$sOrderByMaster = "";
	$sSqlMaster = $sSqlMasterBase;
	if ($sWhereMaster <> "") { $sWhereMaster .= " AND "; }
	$sWhereMaster .= $sDbWhereMaster;
	if ($sWhereMaster <> "") {
		$sSqlMaster .= " WHERE " . $sWhereMaster;
	}
	if ($sGroupByMaster <> "") {
		$sSqlMaster .= " GROUP BY " . $sGroupByMaster;
	}
	if ($sHavingMaster <> "") {
		$sSqlMaster .= " HAVING " . $sHavingMaster;
	}
	if ($sOrderByMaster <> "") {
		$sSqlMaster .= " ORDER BY " . $sOrderByMaster;
	}
	$rs = phpmkr_query($sSqlMaster, $conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSqlMaster);
	$bMasterRecordExist = (phpmkr_num_rows($rs) > 0);
	if (!$bMasterRecordExist) {
		$_SESSION["_MasterWhere"] = "";
		$_SESSION["vit_perfil_acceso_DetailWhere"] = "";
		$_SESSION["ewmsg"] = "No records found";
		phpmkr_free_result($rs);
		phpmkr_db_close($conn);
		header("Location: vit_perfillist.php");
	}
}
?>
<?php include ("header.php") ?>
<?php if ($sExport == "") { ?>
<script type="text/javascript" src="ew.js"></script>
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
if ($sDbWhereMaster <> "") {
	if ($bMasterRecordExist) { ?>
<p><span class="phpmaker">Master Record: Perfil
<?php if ($sExport == "") { ?>
<br><a href="vit_perfillist.php">Back to Master Page</a>
<?php } ?>
</span></p>
<table border="0" cellspacing="1" cellpadding="4" bgcolor="#CCCCCC">
	<tr bgcolor="#666666">
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">Perfil</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">Descripcion</span></td>
	</tr>
	<tr bgcolor="#FFFFFF">
<?php
		$row = phpmkr_fetch_array($rs);
		$x_Per_Perfil_ID = $row["Per_Perfil_ID"];
		$x_Per_Descripcion = $row["Per_Descripcion"];
?>
		<td><span class="phpmaker">
<?php echo $x_Per_Perfil_ID; ?>
</span></td>
		<td><span class="phpmaker">
<?php echo str_replace(chr(10), "<br>", $x_Per_Descripcion); ?>
</span></td>
	</tr>
</table>
<br>
<?php
	}
	phpmkr_free_result($rs);
}
?>
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
<p><span class="phpmaker">TABLE: Perfil acceso
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="vit_perfil_accesolist.php?export=excel">Export to Excel</a>
<?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker"><a href="vit_perfil_accesoadd.php">Add</a></span></td>
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
<form name="vit_perfil_accesolist" id="vit_perfil_accesolist" action="vit_perfil_accesolist.php" method="post">
<table border="0" cellspacing="1" cellpadding="4" bgcolor="#CCCCCC">
	<!-- Table header -->
	<tr bgcolor="#666666">
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
Perfil
<?php }else{ ?>
	<a href="vit_perfil_accesolist.php?order=<?php echo urlencode("Per_Perfil_ID"); ?>" class="phpmaker" style="color: #FFFFFF;">Perfil<?php if (@$_SESSION["vit_perfil_acceso_x_Per_Perfil_ID_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_perfil_acceso_x_Per_Perfil_ID_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
Modulo
<?php }else{ ?>
	<a href="vit_perfil_accesolist.php?order=<?php echo urlencode("Mod_Modulo_ID"); ?>" class="phpmaker" style="color: #FFFFFF;">Modulo<?php if (@$_SESSION["vit_perfil_acceso_x_Mod_Modulo_ID_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_perfil_acceso_x_Mod_Modulo_ID_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
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
		$x_Acc_Acceso_ID = $row["Acc_Acceso_ID"];
		$x_Per_Perfil_ID = $row["Per_Perfil_ID"];
		$x_Mod_Modulo_ID = $row["Mod_Modulo_ID"];
	$bEditRow = (($_SESSION["vita_proyecto_Key_Acc_Acceso_ID"] == $x_Acc_Acceso_ID) && ($nEditRowCnt == 0));
	if ($bEditRow) {
		$nEditRowCnt++;
		$sItemRowClass = " bgcolor=\"#FFFF99\"";
	}
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
<?php if ($bEditRow) { ?>
<input type="hidden" id="x_Acc_Acceso_ID" name="x_Acc_Acceso_ID" value="<?php echo htmlspecialchars(@$x_Acc_Acceso_ID); ?>">
<?php } ?>
		<!-- Per_Perfil_ID -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php if (@$_SESSION["vit_perfil_acceso_MasterKey_Per_Perfil_ID"] <> "") { ?>
<?php $x_Per_Perfil_ID = @$_SESSION["vit_perfil_acceso_MasterKey_Per_Perfil_ID"]; ?>
<?php
if ((!is_null($x_Per_Perfil_ID)) && ($x_Per_Perfil_ID <> "")) {
	$sSqlWrk = "SELECT DISTINCT `Per_Descripcion` FROM `vit_perfil`";
	$sTmp = $x_Per_Perfil_ID;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `Per_Perfil_ID` = " . $sTmp . "";
	$sSqlWrk .= " ORDER BY `Per_Descripcion` Asc";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["Per_Descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_Per_Perfil_ID = $x_Per_Perfil_ID; // Backup Original Value
$x_Per_Perfil_ID = $sTmp;
?>
<?php echo $x_Per_Perfil_ID; ?>
<?php $x_Per_Perfil_ID = $ox_Per_Perfil_ID; // Restore Original Value ?>
<input type="hidden" id="x_Per_Perfil_ID" name="x_Per_Perfil_ID" value="<?php echo $x_Per_Perfil_ID; ?>">
<?php } else { ?>
<?php
$x_Per_Perfil_IDList = "<select name=\"x_Per_Perfil_ID\">";
$x_Per_Perfil_IDList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT DISTINCT `Per_Perfil_ID`, `Per_Descripcion` FROM `vit_perfil`";
$sSqlWrk .= " ORDER BY `Per_Descripcion` Asc";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_Per_Perfil_IDList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["Per_Perfil_ID"] == @$x_Per_Perfil_ID) {
			$x_Per_Perfil_IDList .= "' selected";
		}
		$x_Per_Perfil_IDList .= ">" . $datawrk["Per_Descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_Per_Perfil_IDList .= "</select>";
echo $x_Per_Perfil_IDList;
?>
<?php } ?>
<?php }else{ ?>
<?php
if ((!is_null($x_Per_Perfil_ID)) && ($x_Per_Perfil_ID <> "")) {
	$sSqlWrk = "SELECT DISTINCT `Per_Descripcion` FROM `vit_perfil`";
	$sTmp = $x_Per_Perfil_ID;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `Per_Perfil_ID` = " . $sTmp . "";
	$sSqlWrk .= " ORDER BY `Per_Descripcion` Asc";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["Per_Descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_Per_Perfil_ID = $x_Per_Perfil_ID; // Backup Original Value
$x_Per_Perfil_ID = $sTmp;
?>
<?php echo $x_Per_Perfil_ID; ?>
<?php $x_Per_Perfil_ID = $ox_Per_Perfil_ID; // Restore Original Value ?>
<?php } ?>
</span></td>
		<!-- Mod_Modulo_ID -->
		<td><span class="phpmaker">
<?php if ($bEditRow) { // Edit Record ?>
<?php
$x_Mod_Modulo_IDList = "<select name=\"x_Mod_Modulo_ID\">";
$x_Mod_Modulo_IDList .= "<option value=''>Please Select</option>";
$sSqlWrk = "SELECT DISTINCT `Mod_Modulo_ID`, `Mod_Descripcion` FROM `vit_modulos`";
$sSqlWrk .= " ORDER BY `Mod_Descripcion` Asc";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_Mod_Modulo_IDList .= "<option value=\"" . htmlspecialchars($datawrk[0]) . "\"";
		if ($datawrk["Mod_Modulo_ID"] == @$x_Mod_Modulo_ID) {
			$x_Mod_Modulo_IDList .= "' selected";
		}
		$x_Mod_Modulo_IDList .= ">" . $datawrk["Mod_Descripcion"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_Mod_Modulo_IDList .= "</select>";
echo $x_Mod_Modulo_IDList;
?>
<?php }else{ ?>
<?php
if ((!is_null($x_Mod_Modulo_ID)) && ($x_Mod_Modulo_ID <> "")) {
	$sSqlWrk = "SELECT DISTINCT `Mod_Descripcion` FROM `vit_modulos`";
	$sTmp = $x_Mod_Modulo_ID;
	$sTmp = addslashes($sTmp);
	$sSqlWrk .= " WHERE `Mod_Modulo_ID` = " . $sTmp . "";
	$sSqlWrk .= " ORDER BY `Mod_Descripcion` Asc";
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		$sTmp = $rowwrk["Mod_Descripcion"];
	}
	@phpmkr_free_result($rswrk);
} else {
	$sTmp = "";
}
$ox_Mod_Modulo_ID = $x_Mod_Modulo_ID; // Backup Original Value
$x_Mod_Modulo_ID = $sTmp;
?>
<?php echo $x_Mod_Modulo_ID; ?>
<?php $x_Mod_Modulo_ID = $ox_Mod_Modulo_ID; // Restore Original Value ?>
<?php } ?>
</span></td>
<?php if ($sExport == "") { ?>
<td><span class="phpmaker"><a href="<?php if ($x_Acc_Acceso_ID <> "") {echo "vit_perfil_accesoview.php?Acc_Acceso_ID=" . urlencode($x_Acc_Acceso_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">View</a></span></td>
<td><span class="phpmaker">
<?php if ($_SESSION["vita_proyecto_Key_Acc_Acceso_ID"] == $x_Acc_Acceso_ID) { ?>
<a href="" onClick="if (EW_checkMyForm(document.vit_perfil_accesolist)) document.vit_perfil_accesolist.submit();return false;">Update</a>&nbsp;<a href="vit_perfil_accesolist.php?a=cancel">Cancel</a>
<input type="hidden" name="a_list" value="update">
<?php } else { ?>
<a href="<?php if ($x_Acc_Acceso_ID <> "") {echo "vit_perfil_accesolist.php?a=edit&Acc_Acceso_ID=" . urlencode($x_Acc_Acceso_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Inline Edit</a>
<?php } ?>
</span></td>
<td><span class="phpmaker"><a href="<?php if ($x_Acc_Acceso_ID <> "") {echo "vit_perfil_accesoedit.php?Acc_Acceso_ID=" . urlencode($x_Acc_Acceso_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_Acc_Acceso_ID <> "") {echo "vit_perfil_accesodelete.php?Acc_Acceso_ID=" . urlencode($x_Acc_Acceso_ID); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Delete</a></span></td>
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
<form action="vit_perfil_accesolist.php" name="ewpagerform" id="ewpagerform">
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
	<td><a href="vit_perfil_accesolist.php?start=1"><img src="images/first.gif" alt="First" width="16" height="16" border="0"></a></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($PrevStart == $nStartRec) { ?>
	<td><img src="images/prevdisab.gif" alt="Previous" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="vit_perfil_accesolist.php?start=<?php echo $PrevStart; ?>"><img src="images/prev.gif" alt="Previous" width="16" height="16" border="0"></a></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="pageno" value="<?php echo intval(($nStartRec-1)/$nDisplayRecs+1); ?>" size="4"></td>
<!--next page button-->
	<?php if ($NextStart == $nStartRec) { ?>
	<td><img src="images/nextdisab.gif" alt="Next" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="vit_perfil_accesolist.php?start=<?php echo $NextStart; ?>"><img src="images/next.gif" alt="Next" width="16" height="16" border="0"></a></td>
	<?php  } ?>
<!--last page button-->
	<?php if ($LastStart == $nStartRec) { ?>
	<td><img src="images/lastdisab.gif" alt="Last" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="vit_perfil_accesolist.php?start=<?php echo $LastStart; ?>"><img src="images/last.gif" alt="Last" width="16" height="16" border="0"></a></td>
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
<option value="ALL"<?php if (@$_SESSION["vit_perfil_acceso_RecPerPage"] == -1) { echo " selected";  }?>>All Records</option>
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
		$_SESSION["vit_perfil_acceso_RecPerPage"] = $nDisplayRecs; // Save to Session

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_perfil_acceso_REC"] = $nStartRec;
	}else{
		if (@$_SESSION["vit_perfil_acceso_RecPerPage"] <> "") {
			$nDisplayRecs = $_SESSION["vit_perfil_acceso_RecPerPage"]; // Restore from Session
		}else{
			$nDisplayRecs = 20; // Load Default
		}
	}
}

//-------------------------------------------------------------------------------
// Function SetUpMasterDetail
// - Set up Master Detail criteria based on querystring parameter key_m
// - Variables setup: sKeyMaster, sDbWhereMaster, Session("TblVar_masterkey")

function SetUpMasterDetail()
{
	global $sDbWhereMaster;
	global $sDbWhereDetail;
	global $sKeyMaster;
	global $nStartRec;
	global $x_Per_Perfil_ID;

	// Get the keys for master table
	if (strlen(@$_GET["showmaster"]) > 0) {

		// Reset start record counter (new master key)
		$nStartRec = 1;
		$_SESSION["vit_perfil_acceso_REC"] = $nStartRec;
		$sDbWhereMaster = "";
		$sDbWhereDetail = "";	
		$x_Per_Perfil_ID = @$_GET["Per_Perfil_ID"]; // Load Parameter from QueryString
		$sTmp = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Per_Perfil_ID) : $x_Per_Perfil_ID;
		if ($sDbWhereMaster <> "") { $sDbWhereMaster .= " AND "; }
		$sDbWhereMaster .= "`Per_Perfil_ID` =  " . $sTmp . "";
		if ($sDbWhereDetail <> "") { $sDbWhereDetail .= " AND "; }
		$sDbWhereDetail .= "`Per_Perfil_ID` =  " . $sTmp  . "";
		$_SESSION["vit_perfil_acceso_MasterKey_Per_Perfil_ID"] = $sTmp; // Save Master Key Value
		$_SESSION["vit_perfil_acceso_MasterWhere"] = $sDbWhereMaster;
		$_SESSION["vit_perfil_acceso_DetailWhere"] = $sDbWhereDetail;
	}else{
		$sDbWhereMaster = @$_SESSION["vit_perfil_acceso_MasterWhere"];
		$sDbWhereDetail = @$_SESSION["vit_perfil_acceso_DetailWhere"];
	}
}

//-------------------------------------------------------------------------------
// Function SetUpInlineEdit
// - Set up Inline Edit parameters based on querystring parameters a & key
// - Variables setup: sAction, sKey, Session("Proj_InlineEdit_Key")

function SetUpInlineEdit($conn)
{
	global $x_Acc_Acceso_ID;

	// Get the keys for master table
	if (strlen(@$_GET["a"]) > 0) {
		$sAction = @$_GET["a"];
		if (strtoupper($sAction) == "EDIT") { // Change to Inline Edit Mode
			$bInlineEdit = true;
			if (strlen(@$_GET["Acc_Acceso_ID"]) > 0) {
				$x_Acc_Acceso_ID = $_GET["Acc_Acceso_ID"];
			}else{
				$bInlineEdit = false;
			}
			if ($bInlineEdit) {
				if (LoadData($conn)) {
					$_SESSION["vita_proyecto_Key_Acc_Acceso_ID"] = $x_Acc_Acceso_ID; // Set up Inline Edit key
				}
			}
		}
		elseif (strtoupper($sAction) == "CANCEL")  // Switch out of Inline Edit Mode
		{
			$_SESSION["vita_proyecto_Key_Acc_Acceso_ID"] = ""; // Clear Inline Edit key
		}
	}
	else
	{
		$sAction = @$_POST["a_list"];
		if (strtoupper($sAction) == "UPDATE") { // Update Record

			// Get fields from form
			global $x_Acc_Acceso_ID;
			$x_Acc_Acceso_ID = @$_POST["x_Acc_Acceso_ID"];
			global $x_Per_Perfil_ID;
			$x_Per_Perfil_ID = @$_POST["x_Per_Perfil_ID"];
			global $x_Mod_Modulo_ID;
			$x_Mod_Modulo_ID = @$_POST["x_Mod_Modulo_ID"];
			if ($_SESSION["vita_proyecto_Key_Acc_Acceso_ID"] == $x_Acc_Acceso_ID) {
				if (InlineEditData($conn)) {
					$_SESSION["ewmsg"] = "Update Record Successful";
				}
			}
		}
		$_SESSION["vita_proyecto_Key_Acc_Acceso_ID"] = ""; // Clear Inline Edit key
	}
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
			$sLastSort = @$_SESSION["vit_perfil_acceso_x_Per_Perfil_ID_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_perfil_acceso_x_Per_Perfil_ID_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_perfil_acceso_x_Per_Perfil_ID_Sort"] <> "") { @$_SESSION["vit_perfil_acceso_x_Per_Perfil_ID_Sort"] = ""; }
		}

		// Field Mod_Modulo_ID
		if ($sOrder == "Mod_Modulo_ID") {
			$sSortField = "`Mod_Modulo_ID`";
			$sLastSort = @$_SESSION["vit_perfil_acceso_x_Mod_Modulo_ID_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_perfil_acceso_x_Mod_Modulo_ID_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_perfil_acceso_x_Mod_Modulo_ID_Sort"] <> "") { @$_SESSION["vit_perfil_acceso_x_Mod_Modulo_ID_Sort"] = ""; }
		}
		$_SESSION["vit_perfil_acceso_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["vit_perfil_acceso_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["vit_perfil_acceso_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["vit_perfil_acceso_OrderBy"] = $sOrderBy;
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
		$_SESSION["vit_perfil_acceso_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["vit_perfil_acceso_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["vit_perfil_acceso_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["vit_perfil_acceso_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["vit_perfil_acceso_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["vit_perfil_acceso_REC"] = $nStartRec;
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
			$_SESSION["vit_perfil_acceso_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["vit_perfil_acceso_searchwhere"] = $sSrchWhere;
			$_SESSION["vit_perfil_acceso_MasterWhere"] = ""; // Clear master criteria
			$sDbWhereMaster = "";
			$_SESSION["vit_perfil_acceso_DetailWhere"] = ""; // Clear detail criteria
			$sDbWhereDetail = "";
		$_SESSION["vit_perfil_acceso_MasterKey_Per_Perfil_ID"] = ""; // Clear Master Key Value
			$_SESSION["vita_proyecto_InlineEdit_Key"] = ""; // Clear Inline Edit key

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["vit_perfil_acceso_OrderBy"] = $sOrderBy;
			if (@$_SESSION["vit_perfil_acceso_x_Per_Perfil_ID_Sort"] <> "") { $_SESSION["vit_perfil_acceso_x_Per_Perfil_ID_Sort"] = ""; }
			if (@$_SESSION["vit_perfil_acceso_x_Mod_Modulo_ID_Sort"] <> "") { $_SESSION["vit_perfil_acceso_x_Mod_Modulo_ID_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_perfil_acceso_REC"] = $nStartRec;
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
	global $x_Acc_Acceso_ID;
	$sSql = "SELECT * FROM `vit_perfil_acceso`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Acc_Acceso_ID) : $x_Acc_Acceso_ID;
	$sWhere .= "(`Acc_Acceso_ID` = " . addslashes($sTmp) . ")";
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
		$GLOBALS["x_Acc_Acceso_ID"] = $row["Acc_Acceso_ID"];
		$GLOBALS["x_Per_Perfil_ID"] = $row["Per_Perfil_ID"];
		$GLOBALS["x_Mod_Modulo_ID"] = $row["Mod_Modulo_ID"];
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
	global $x_Acc_Acceso_ID;
	$sSql = "SELECT * FROM `vit_perfil_acceso`";
	$sWhere = "";
	$sGroupBy = "";
	$sHaving = "";
	$sOrderBy = "";
	if ($sWhere <> "") { $sWhere .= " AND "; }
	$sTmp =  ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes($x_Acc_Acceso_ID) : $x_Acc_Acceso_ID;	
	$sWhere .= "(`Acc_Acceso_ID` = " . addslashes($sTmp) . ")";
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
		$theValue = ($GLOBALS["x_Per_Perfil_ID"] != "") ? intval($GLOBALS["x_Per_Perfil_ID"]) : "NULL";
		$fieldList["`Per_Perfil_ID`"] = $theValue;
		$theValue = ($GLOBALS["x_Mod_Modulo_ID"] != "") ? intval($GLOBALS["x_Mod_Modulo_ID"]) : "NULL";
		$fieldList["`Mod_Modulo_ID`"] = $theValue;

		// update
		$sSql = "UPDATE `vit_perfil_acceso` SET ";
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
