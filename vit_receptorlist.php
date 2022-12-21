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
if (@$_SESSION["vita_proyecto_status"] <> "login") {
	header("Location:  login.php");
	exit();
}
?>
<?php

// Initialize common variables
$x_Rec_RFC = Null; 
$ox_Rec_RFC = Null;
$x_Rec_Nombre = Null; 
$ox_Rec_Nombre = Null;
$x_Rec_Apellido_Paterno = Null; 
$ox_Rec_Apellido_Paterno = Null;
$x_Rec_Apellido_Materno = Null; 
$ox_Rec_Apellido_Materno = Null;
$x_Rec_DomicilioFiscaleceptor = Null; 
$ox_Rec_DomicilioFiscaleceptor = Null;
$x_Rec_ResidenciaFiscal = Null; 
$ox_Rec_ResidenciaFiscal = Null;
$x_Rec_NumRegIdTrib = Null; 
$ox_Rec_NumRegIdTrib = Null;
$x_Rec_RegimenFiscalReceptor = Null; 
$ox_Rec_RegimenFiscalReceptor = Null;
$x_Rec_Curp = Null; 
$ox_Rec_Curp = Null;
$x_Rec_NumSeguridadSocial = Null; 
$ox_Rec_NumSeguridadSocial = Null;
$x_Rec_FechaInicioRelLaboral = Null; 
$ox_Rec_FechaInicioRelLaboral = Null;
$x_Rec_Antiguedad = Null; 
$ox_Rec_Antiguedad = Null;
$x_Rec_TipoContrato = Null; 
$ox_Rec_TipoContrato = Null;
$x_Rec_Sindicalizado = Null; 
$ox_Rec_Sindicalizado = Null;
$x_Rec_TipoJornada = Null; 
$ox_Rec_TipoJornada = Null;
$x_Rec_TipoRegimen = Null; 
$ox_Rec_TipoRegimen = Null;
$x_Rec_NumEmpleado = Null; 
$ox_Rec_NumEmpleado = Null;
$x_Rec_Departamento = Null; 
$ox_Rec_Departamento = Null;
$x_Rec_Puesto = Null; 
$ox_Rec_Puesto = Null;
$x_Rec_RiesgoPuesto = Null; 
$ox_Rec_RiesgoPuesto = Null;
$x_Rec_PeriodicidadPago = Null; 
$ox_Rec_PeriodicidadPago = Null;
$x_Rec_Banco = Null; 
$ox_Rec_Banco = Null;
$x_Rec_CuentaBancaria = Null; 
$ox_Rec_CuentaBancaria = Null;
$x_Rec_SalarioBaseCotApor = Null; 
$ox_Rec_SalarioBaseCotApor = Null;
$x_Rec_SalarioDiarioIntegrado = Null; 
$ox_Rec_SalarioDiarioIntegrado = Null;
$x_Rec_ClaveEntFed = Null; 
$ox_Rec_ClaveEntFed = Null;
$x_Rec_Status = Null; 
$ox_Rec_Status = Null;
$x_Mun_ID = Null; 
$ox_Mun_ID = Null;
$x_Rec_CreationDate = Null; 
$ox_Rec_CreationDate = Null;
?>
<?php
$sExport = @$_GET["export"]; // Load Export Request
if ($sExport == "excel") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=vit_receptor.xls');
}
?>
<?php include ("db.php") ?>
<?php include ("phpmkrfn.php") ?>
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

// Get Search Criteria for Advanced Search
SetUpAdvancedSearch();

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
	$_SESSION["vit_receptor_searchwhere"] = $sSrchWhere;

	// Reset start record counter (new search)
	$nStartRec = 1;
	$_SESSION["vit_receptor_REC"] = $nStartRec;
}
else
{
	$sSrchWhere = @$_SESSION["vit_receptor_searchwhere"];
}

// Build SQL
$sSql = "SELECT * FROM `vit_receptor`";

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
<script type="text/javascript" src="ew.js"></script>
<script type="text/javascript">
<!--
EW_dateSep = "-"; // set date separator	

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
<p><span class="phpmaker">TABLE: Receptor
<?php if ($sExport == "") { ?>
&nbsp;&nbsp;<a href="vit_receptorlist.php?export=excel">Export to Excel</a>
<?php } ?>
</span></p>
<?php if ($sExport == "") { ?>
<form action="vit_receptorlist.php">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker">
			<input type="text" name="psearch" size="20">
			<input type="Submit" name="Submit" value="Search &nbsp;(*)">&nbsp;&nbsp;
			<a href="vit_receptorlist.php?cmd=reset">Show all</a>&nbsp;&nbsp;
			<a href="vit_receptorsrch.php">Advanced Search</a>
		</span></td>
	</tr>
	<tr><td><span class="phpmaker"><input type="radio" name="psearchtype" value="" checked>Exact phrase&nbsp;&nbsp;<input type="radio" name="psearchtype" value="AND">All words&nbsp;&nbsp;<input type="radio" name="psearchtype" value="OR">Any word</span></td></tr>
</table>
</form>
<?php } ?>
<?php if ($sExport == "") { ?>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><span class="phpmaker"><a href="vit_receptoradd.php">Add</a></span></td>
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
<form method="post">
<table border="0" cellspacing="1" cellpadding="4" bgcolor="#CCCCCC">
	<!-- Table header -->
	<tr bgcolor="#666666">
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
RFC
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Rec_RFC"); ?>" class="phpmaker" style="color: #FFFFFF;">RFC&nbsp;(*)<?php if (@$_SESSION["vit_receptor_x_Rec_RFC_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Rec_RFC_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
Nombre
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Rec_Nombre"); ?>" class="phpmaker" style="color: #FFFFFF;">Nombre&nbsp;(*)<?php if (@$_SESSION["vit_receptor_x_Rec_Nombre_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Rec_Nombre_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
Apellido Paterno
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Rec_Apellido_Paterno"); ?>" class="phpmaker" style="color: #FFFFFF;">Apellido Paterno&nbsp;(*)<?php if (@$_SESSION["vit_receptor_x_Rec_Apellido_Paterno_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Rec_Apellido_Paterno_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
Apellido Materno
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Rec_Apellido_Materno"); ?>" class="phpmaker" style="color: #FFFFFF;">Apellido Materno&nbsp;(*)<?php if (@$_SESSION["vit_receptor_x_Rec_Apellido_Materno_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Rec_Apellido_Materno_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
Curp
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Rec_Curp"); ?>" class="phpmaker" style="color: #FFFFFF;">Curp&nbsp;(*)<?php if (@$_SESSION["vit_receptor_x_Rec_Curp_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Rec_Curp_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
Num Seguridad Social
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Rec_NumSeguridadSocial"); ?>" class="phpmaker" style="color: #FFFFFF;">Num Seguridad Social&nbsp;(*)<?php if (@$_SESSION["vit_receptor_x_Rec_NumSeguridadSocial_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Rec_NumSeguridadSocial_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
Num Empleado
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Rec_NumEmpleado"); ?>" class="phpmaker" style="color: #FFFFFF;">Num Empleado&nbsp;(*)<?php if (@$_SESSION["vit_receptor_x_Rec_NumEmpleado_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Rec_NumEmpleado_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
Departamento
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Rec_Departamento"); ?>" class="phpmaker" style="color: #FFFFFF;">Departamento&nbsp;(*)<?php if (@$_SESSION["vit_receptor_x_Rec_Departamento_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Rec_Departamento_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
Puesto
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Rec_Puesto"); ?>" class="phpmaker" style="color: #FFFFFF;">Puesto&nbsp;(*)<?php if (@$_SESSION["vit_receptor_x_Rec_Puesto_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Rec_Puesto_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
Status
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Rec_Status"); ?>" class="phpmaker" style="color: #FFFFFF;">Status<?php if (@$_SESSION["vit_receptor_x_Rec_Status_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Rec_Status_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
		<td valign="top"><span class="phpmaker" style="color: #FFFFFF;">
<?php if ($sExport <> "") { ?>
Municipio
<?php }else{ ?>
	<a href="vit_receptorlist.php?order=<?php echo urlencode("Mun_ID"); ?>" class="phpmaker" style="color: #FFFFFF;">Municipio<?php if (@$_SESSION["vit_receptor_x_Mun_ID_Sort"] == "ASC") { ?><img src="images/sortup.gif" width="10" height="9" border="0"><?php } elseif (@$_SESSION["vit_receptor_x_Mun_ID_Sort"] == "DESC") { ?><img src="images/sortdown.gif" width="10" height="9" border="0"><?php } ?></a>
<?php } ?>
		</span></td>
<?php if ($sExport == "") { ?>
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
		$x_Rec_RFC = $row["Rec_RFC"];
		$x_Rec_Nombre = $row["Rec_Nombre"];
		$x_Rec_Apellido_Paterno = $row["Rec_Apellido_Paterno"];
		$x_Rec_Apellido_Materno = $row["Rec_Apellido_Materno"];
		$x_Rec_DomicilioFiscaleceptor = $row["Rec_DomicilioFiscaleceptor"];
		$x_Rec_ResidenciaFiscal = $row["Rec_ResidenciaFiscal"];
		$x_Rec_NumRegIdTrib = $row["Rec_NumRegIdTrib"];
		$x_Rec_RegimenFiscalReceptor = $row["Rec_RegimenFiscalReceptor"];
		$x_Rec_Curp = $row["Rec_Curp"];
		$x_Rec_NumSeguridadSocial = $row["Rec_NumSeguridadSocial"];
		$x_Rec_FechaInicioRelLaboral = $row["Rec_FechaInicioRelLaboral"];
		$x_Rec_Antiguedad = $row["Rec_Antiguedad"];
		$x_Rec_TipoContrato = $row["Rec_TipoContrato"];
		$x_Rec_Sindicalizado = $row["Rec_Sindicalizado"];
		$x_Rec_TipoJornada = $row["Rec_TipoJornada"];
		$x_Rec_TipoRegimen = $row["Rec_TipoRegimen"];
		$x_Rec_NumEmpleado = $row["Rec_NumEmpleado"];
		$x_Rec_Departamento = $row["Rec_Departamento"];
		$x_Rec_Puesto = $row["Rec_Puesto"];
		$x_Rec_RiesgoPuesto = $row["Rec_RiesgoPuesto"];
		$x_Rec_PeriodicidadPago = $row["Rec_PeriodicidadPago"];
		$x_Rec_Banco = $row["Rec_Banco"];
		$x_Rec_CuentaBancaria = $row["Rec_CuentaBancaria"];
		$x_Rec_SalarioBaseCotApor = $row["Rec_SalarioBaseCotApor"];
		$x_Rec_SalarioDiarioIntegrado = $row["Rec_SalarioDiarioIntegrado"];
		$x_Rec_ClaveEntFed = $row["Rec_ClaveEntFed"];
		$x_Rec_Status = $row["Rec_Status"];
		$x_Mun_ID = $row["Mun_ID"];
		$x_Rec_CreationDate = $row["Rec_CreationDate"];
?>
	<!-- Table body -->
	<tr<?php echo $sItemRowClass; ?>>
		<!-- Rec_RFC -->
		<td><span class="phpmaker">
<?php echo $x_Rec_RFC; ?>
</span></td>
		<!-- Rec_Nombre -->
		<td><span class="phpmaker">
<?php echo str_replace(chr(10), "<br>", $x_Rec_Nombre); ?>
</span></td>
		<!-- Rec_Apellido_Paterno -->
		<td><span class="phpmaker">
<?php echo str_replace(chr(10), "<br>", $x_Rec_Apellido_Paterno); ?>
</span></td>
		<!-- Rec_Apellido_Materno -->
		<td><span class="phpmaker">
<?php echo str_replace(chr(10), "<br>", $x_Rec_Apellido_Materno); ?>
</span></td>
		<!-- Rec_Curp -->
		<td><span class="phpmaker">
<?php echo $x_Rec_Curp; ?>
</span></td>
		<!-- Rec_NumSeguridadSocial -->
		<td><span class="phpmaker">
<?php echo $x_Rec_NumSeguridadSocial; ?>
</span></td>
		<!-- Rec_NumEmpleado -->
		<td><span class="phpmaker">
<?php echo $x_Rec_NumEmpleado; ?>
</span></td>
		<!-- Rec_Departamento -->
		<td><span class="phpmaker">
<?php echo str_replace(chr(10), "<br>", $x_Rec_Departamento); ?>
</span></td>
		<!-- Rec_Puesto -->
		<td><span class="phpmaker">
<?php echo str_replace(chr(10), "<br>", $x_Rec_Puesto); ?>
</span></td>
		<!-- Rec_Status -->
		<td><span class="phpmaker">
<?php
switch ($x_Rec_Status) {
	case "0":
		$sTmp = "Inactivo";
		break;
	case "1":
		$sTmp = "Activo";
		break;
	default:
		$sTmp = "";
}
$ox_Rec_Status = $x_Rec_Status; // Backup Original Value
$x_Rec_Status = $sTmp;
?>
<?php echo $x_Rec_Status; ?>
<?php $x_Rec_Status = $ox_Rec_Status; // Restore Original Value ?>
</span></td>
		<!-- Mun_ID -->
		<td><span class="phpmaker">
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
</span></td>
<?php if ($sExport == "") { ?>
<td><span class="phpmaker"><a href="<?php if ($x_Rec_RFC <> "") {echo "vit_receptorview.php?Rec_RFC=" . urlencode($x_Rec_RFC); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">View</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_Rec_RFC <> "") {echo "vit_receptoredit.php?Rec_RFC=" . urlencode($x_Rec_RFC); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Edit</a></span></td>
<td><span class="phpmaker"><a href="<?php if ($x_Rec_RFC <> "") {echo "vit_receptordelete.php?Rec_RFC=" . urlencode($x_Rec_RFC); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Delete</a></span></td>
<?php } ?>
	</tr>
<?php
	}
}
?>
</table>
</form>
<?php } ?>
<?php

// Close recordset and connection
phpmkr_free_result($rs);
phpmkr_db_close($conn);
?>
<?php if ($sExport == "") { ?>
<form action="vit_receptorlist.php" name="ewpagerform" id="ewpagerform">
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
	<td><a href="vit_receptorlist.php?start=1"><img src="images/first.gif" alt="First" width="16" height="16" border="0"></a></td>
	<?php } ?>
<!--previous page button-->
	<?php if ($PrevStart == $nStartRec) { ?>
	<td><img src="images/prevdisab.gif" alt="Previous" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="vit_receptorlist.php?start=<?php echo $PrevStart; ?>"><img src="images/prev.gif" alt="Previous" width="16" height="16" border="0"></a></td>
	<?php } ?>
<!--current page number-->
	<td><input type="text" name="pageno" value="<?php echo intval(($nStartRec-1)/$nDisplayRecs+1); ?>" size="4"></td>
<!--next page button-->
	<?php if ($NextStart == $nStartRec) { ?>
	<td><img src="images/nextdisab.gif" alt="Next" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="vit_receptorlist.php?start=<?php echo $NextStart; ?>"><img src="images/next.gif" alt="Next" width="16" height="16" border="0"></a></td>
	<?php  } ?>
<!--last page button-->
	<?php if ($LastStart == $nStartRec) { ?>
	<td><img src="images/lastdisab.gif" alt="Last" width="16" height="16" border="0"></td>
	<?php } else { ?>
	<td><a href="vit_receptorlist.php?start=<?php echo $LastStart; ?>"><img src="images/last.gif" alt="Last" width="16" height="16" border="0"></a></td>
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
<option value="ALL"<?php if (@$_SESSION["vit_receptor_RecPerPage"] == -1) { echo " selected";  }?>>All Records</option>
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
		$_SESSION["vit_receptor_RecPerPage"] = $nDisplayRecs; // Save to Session

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_receptor_REC"] = $nStartRec;
	}else{
		if (@$_SESSION["vit_receptor_RecPerPage"] <> "") {
			$nDisplayRecs = $_SESSION["vit_receptor_RecPerPage"]; // Restore from Session
		}else{
			$nDisplayRecs = 20; // Load Default
		}
	}
}

//-------------------------------------------------------------------------------
// Function SetUpAdvancedSearch
// - Set up Advanced Search parameter based on querystring parameters from Advanced Search Page
// - Variables setup: sSrchAdvanced

function SetUpAdvancedSearch()
{
	global $sSrchAdvanced;

	// Field Rec_RFC
	$x_Rec_RFC = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_RFC"]) : @$_GET["x_Rec_RFC"];
	$arrFldOpr = "";
	$z_Rec_RFC = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_RFC"]) : @$_GET["z_Rec_RFC"];
	$arrFldOpr = explode(",",$z_Rec_RFC);
	if ($x_Rec_RFC <> "") {
		$sSrchAdvanced .= "`Rec_RFC` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_RFC) : $x_Rec_RFC; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_Nombre
	$x_Rec_Nombre = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_Nombre"]) : @$_GET["x_Rec_Nombre"];
	$arrFldOpr = "";
	$z_Rec_Nombre = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_Nombre"]) : @$_GET["z_Rec_Nombre"];
	$arrFldOpr = explode(",",$z_Rec_Nombre);
	if ($x_Rec_Nombre <> "") {
		$sSrchAdvanced .= "`Rec_Nombre` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_Nombre) : $x_Rec_Nombre; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_Apellido_Paterno
	$x_Rec_Apellido_Paterno = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_Apellido_Paterno"]) : @$_GET["x_Rec_Apellido_Paterno"];
	$arrFldOpr = "";
	$z_Rec_Apellido_Paterno = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_Apellido_Paterno"]) : @$_GET["z_Rec_Apellido_Paterno"];
	$arrFldOpr = explode(",",$z_Rec_Apellido_Paterno);
	if ($x_Rec_Apellido_Paterno <> "") {
		$sSrchAdvanced .= "`Rec_Apellido_Paterno` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_Apellido_Paterno) : $x_Rec_Apellido_Paterno; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_Apellido_Materno
	$x_Rec_Apellido_Materno = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_Apellido_Materno"]) : @$_GET["x_Rec_Apellido_Materno"];
	$arrFldOpr = "";
	$z_Rec_Apellido_Materno = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_Apellido_Materno"]) : @$_GET["z_Rec_Apellido_Materno"];
	$arrFldOpr = explode(",",$z_Rec_Apellido_Materno);
	if ($x_Rec_Apellido_Materno <> "") {
		$sSrchAdvanced .= "`Rec_Apellido_Materno` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_Apellido_Materno) : $x_Rec_Apellido_Materno; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_DomicilioFiscaleceptor
	$x_Rec_DomicilioFiscaleceptor = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_DomicilioFiscaleceptor"]) : @$_GET["x_Rec_DomicilioFiscaleceptor"];
	$arrFldOpr = "";
	$z_Rec_DomicilioFiscaleceptor = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_DomicilioFiscaleceptor"]) : @$_GET["z_Rec_DomicilioFiscaleceptor"];
	$arrFldOpr = explode(",",$z_Rec_DomicilioFiscaleceptor);
	if ($x_Rec_DomicilioFiscaleceptor <> "") {
		$sSrchAdvanced .= "`Rec_DomicilioFiscaleceptor` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_DomicilioFiscaleceptor) : $x_Rec_DomicilioFiscaleceptor; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_ResidenciaFiscal
	$x_Rec_ResidenciaFiscal = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_ResidenciaFiscal"]) : @$_GET["x_Rec_ResidenciaFiscal"];
	$arrFldOpr = "";
	$z_Rec_ResidenciaFiscal = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_ResidenciaFiscal"]) : @$_GET["z_Rec_ResidenciaFiscal"];
	$arrFldOpr = explode(",",$z_Rec_ResidenciaFiscal);
	if ($x_Rec_ResidenciaFiscal <> "") {
		$sSrchAdvanced .= "`Rec_ResidenciaFiscal` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_ResidenciaFiscal) : $x_Rec_ResidenciaFiscal; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_NumRegIdTrib
	$x_Rec_NumRegIdTrib = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_NumRegIdTrib"]) : @$_GET["x_Rec_NumRegIdTrib"];
	$arrFldOpr = "";
	$z_Rec_NumRegIdTrib = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_NumRegIdTrib"]) : @$_GET["z_Rec_NumRegIdTrib"];
	$arrFldOpr = explode(",",$z_Rec_NumRegIdTrib);
	if ($x_Rec_NumRegIdTrib <> "") {
		$sSrchAdvanced .= "`Rec_NumRegIdTrib` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_NumRegIdTrib) : $x_Rec_NumRegIdTrib; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_RegimenFiscalReceptor
	$x_Rec_RegimenFiscalReceptor = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_RegimenFiscalReceptor"]) : @$_GET["x_Rec_RegimenFiscalReceptor"];
	$arrFldOpr = "";
	$z_Rec_RegimenFiscalReceptor = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_RegimenFiscalReceptor"]) : @$_GET["z_Rec_RegimenFiscalReceptor"];
	$arrFldOpr = explode(",",$z_Rec_RegimenFiscalReceptor);
	if ($x_Rec_RegimenFiscalReceptor <> "") {
		$sSrchAdvanced .= "`Rec_RegimenFiscalReceptor` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_RegimenFiscalReceptor) : $x_Rec_RegimenFiscalReceptor; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_Curp
	$x_Rec_Curp = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_Curp"]) : @$_GET["x_Rec_Curp"];
	$arrFldOpr = "";
	$z_Rec_Curp = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_Curp"]) : @$_GET["z_Rec_Curp"];
	$arrFldOpr = explode(",",$z_Rec_Curp);
	if ($x_Rec_Curp <> "") {
		$sSrchAdvanced .= "`Rec_Curp` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_Curp) : $x_Rec_Curp; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_NumSeguridadSocial
	$x_Rec_NumSeguridadSocial = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_NumSeguridadSocial"]) : @$_GET["x_Rec_NumSeguridadSocial"];
	$arrFldOpr = "";
	$z_Rec_NumSeguridadSocial = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_NumSeguridadSocial"]) : @$_GET["z_Rec_NumSeguridadSocial"];
	$arrFldOpr = explode(",",$z_Rec_NumSeguridadSocial);
	if ($x_Rec_NumSeguridadSocial <> "") {
		$sSrchAdvanced .= "`Rec_NumSeguridadSocial` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_NumSeguridadSocial) : $x_Rec_NumSeguridadSocial; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_FechaInicioRelLaboral
	$x_Rec_FechaInicioRelLaboral = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_FechaInicioRelLaboral"]) : @$_GET["x_Rec_FechaInicioRelLaboral"];
	$arrFldOpr = "";
	$z_Rec_FechaInicioRelLaboral = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_FechaInicioRelLaboral"]) : @$_GET["z_Rec_FechaInicioRelLaboral"];
	$arrFldOpr = explode(",",$z_Rec_FechaInicioRelLaboral);
	if ($x_Rec_FechaInicioRelLaboral <> "") {
		$sSrchAdvanced .= "`Rec_FechaInicioRelLaboral` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_FechaInicioRelLaboral) : $x_Rec_FechaInicioRelLaboral; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_Antiguedad
	$x_Rec_Antiguedad = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_Antiguedad"]) : @$_GET["x_Rec_Antiguedad"];
	$arrFldOpr = "";
	$z_Rec_Antiguedad = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_Antiguedad"]) : @$_GET["z_Rec_Antiguedad"];
	$arrFldOpr = explode(",",$z_Rec_Antiguedad);
	if ($x_Rec_Antiguedad <> "") {
		$sSrchAdvanced .= "`Rec_Antiguedad` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_Antiguedad) : $x_Rec_Antiguedad; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_TipoContrato
	$x_Rec_TipoContrato = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_TipoContrato"]) : @$_GET["x_Rec_TipoContrato"];
	$arrFldOpr = "";
	$z_Rec_TipoContrato = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_TipoContrato"]) : @$_GET["z_Rec_TipoContrato"];
	$arrFldOpr = explode(",",$z_Rec_TipoContrato);
	if ($x_Rec_TipoContrato <> "") {
		$sSrchAdvanced .= "`Rec_TipoContrato` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_TipoContrato) : $x_Rec_TipoContrato; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_Sindicalizado
	$x_Rec_Sindicalizado = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_Sindicalizado"]) : @$_GET["x_Rec_Sindicalizado"];
	$arrFldOpr = "";
	$z_Rec_Sindicalizado = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_Sindicalizado"]) : @$_GET["z_Rec_Sindicalizado"];
	$arrFldOpr = explode(",",$z_Rec_Sindicalizado);
	if ($x_Rec_Sindicalizado <> "") {
		$sSrchAdvanced .= "`Rec_Sindicalizado` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_Sindicalizado) : $x_Rec_Sindicalizado; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_TipoJornada
	$x_Rec_TipoJornada = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_TipoJornada"]) : @$_GET["x_Rec_TipoJornada"];
	$arrFldOpr = "";
	$z_Rec_TipoJornada = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_TipoJornada"]) : @$_GET["z_Rec_TipoJornada"];
	$arrFldOpr = explode(",",$z_Rec_TipoJornada);
	if ($x_Rec_TipoJornada <> "") {
		$sSrchAdvanced .= "`Rec_TipoJornada` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_TipoJornada) : $x_Rec_TipoJornada; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_TipoRegimen
	$x_Rec_TipoRegimen = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_TipoRegimen"]) : @$_GET["x_Rec_TipoRegimen"];
	$arrFldOpr = "";
	$z_Rec_TipoRegimen = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_TipoRegimen"]) : @$_GET["z_Rec_TipoRegimen"];
	$arrFldOpr = explode(",",$z_Rec_TipoRegimen);
	if ($x_Rec_TipoRegimen <> "") {
		$sSrchAdvanced .= "`Rec_TipoRegimen` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_TipoRegimen) : $x_Rec_TipoRegimen; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_NumEmpleado
	$x_Rec_NumEmpleado = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_NumEmpleado"]) : @$_GET["x_Rec_NumEmpleado"];
	$arrFldOpr = "";
	$z_Rec_NumEmpleado = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_NumEmpleado"]) : @$_GET["z_Rec_NumEmpleado"];
	$arrFldOpr = explode(",",$z_Rec_NumEmpleado);
	if ($x_Rec_NumEmpleado <> "") {
		$sSrchAdvanced .= "`Rec_NumEmpleado` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_NumEmpleado) : $x_Rec_NumEmpleado; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_Departamento
	$x_Rec_Departamento = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_Departamento"]) : @$_GET["x_Rec_Departamento"];
	$arrFldOpr = "";
	$z_Rec_Departamento = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_Departamento"]) : @$_GET["z_Rec_Departamento"];
	$arrFldOpr = explode(",",$z_Rec_Departamento);
	if ($x_Rec_Departamento <> "") {
		$sSrchAdvanced .= "`Rec_Departamento` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_Departamento) : $x_Rec_Departamento; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_Puesto
	$x_Rec_Puesto = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_Puesto"]) : @$_GET["x_Rec_Puesto"];
	$arrFldOpr = "";
	$z_Rec_Puesto = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_Puesto"]) : @$_GET["z_Rec_Puesto"];
	$arrFldOpr = explode(",",$z_Rec_Puesto);
	if ($x_Rec_Puesto <> "") {
		$sSrchAdvanced .= "`Rec_Puesto` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_Puesto) : $x_Rec_Puesto; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_RiesgoPuesto
	$x_Rec_RiesgoPuesto = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_RiesgoPuesto"]) : @$_GET["x_Rec_RiesgoPuesto"];
	$arrFldOpr = "";
	$z_Rec_RiesgoPuesto = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_RiesgoPuesto"]) : @$_GET["z_Rec_RiesgoPuesto"];
	$arrFldOpr = explode(",",$z_Rec_RiesgoPuesto);
	if ($x_Rec_RiesgoPuesto <> "") {
		$sSrchAdvanced .= "`Rec_RiesgoPuesto` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_RiesgoPuesto) : $x_Rec_RiesgoPuesto; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_PeriodicidadPago
	$x_Rec_PeriodicidadPago = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_PeriodicidadPago"]) : @$_GET["x_Rec_PeriodicidadPago"];
	$arrFldOpr = "";
	$z_Rec_PeriodicidadPago = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_PeriodicidadPago"]) : @$_GET["z_Rec_PeriodicidadPago"];
	$arrFldOpr = explode(",",$z_Rec_PeriodicidadPago);
	if ($x_Rec_PeriodicidadPago <> "") {
		$sSrchAdvanced .= "`Rec_PeriodicidadPago` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_PeriodicidadPago) : $x_Rec_PeriodicidadPago; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_Banco
	$x_Rec_Banco = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_Banco"]) : @$_GET["x_Rec_Banco"];
	$arrFldOpr = "";
	$z_Rec_Banco = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_Banco"]) : @$_GET["z_Rec_Banco"];
	$arrFldOpr = explode(",",$z_Rec_Banco);
	if ($x_Rec_Banco <> "") {
		$sSrchAdvanced .= "`Rec_Banco` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_Banco) : $x_Rec_Banco; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_CuentaBancaria
	$x_Rec_CuentaBancaria = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_CuentaBancaria"]) : @$_GET["x_Rec_CuentaBancaria"];
	$arrFldOpr = "";
	$z_Rec_CuentaBancaria = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_CuentaBancaria"]) : @$_GET["z_Rec_CuentaBancaria"];
	$arrFldOpr = explode(",",$z_Rec_CuentaBancaria);
	if ($x_Rec_CuentaBancaria <> "") {
		$sSrchAdvanced .= "`Rec_CuentaBancaria` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_CuentaBancaria) : $x_Rec_CuentaBancaria; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_SalarioBaseCotApor
	$x_Rec_SalarioBaseCotApor = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_SalarioBaseCotApor"]) : @$_GET["x_Rec_SalarioBaseCotApor"];
	$arrFldOpr = "";
	$z_Rec_SalarioBaseCotApor = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_SalarioBaseCotApor"]) : @$_GET["z_Rec_SalarioBaseCotApor"];
	$arrFldOpr = explode(",",$z_Rec_SalarioBaseCotApor);
	if ($x_Rec_SalarioBaseCotApor <> "") {
		$sSrchAdvanced .= "`Rec_SalarioBaseCotApor` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_SalarioBaseCotApor) : $x_Rec_SalarioBaseCotApor; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_SalarioDiarioIntegrado
	$x_Rec_SalarioDiarioIntegrado = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_SalarioDiarioIntegrado"]) : @$_GET["x_Rec_SalarioDiarioIntegrado"];
	$arrFldOpr = "";
	$z_Rec_SalarioDiarioIntegrado = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_SalarioDiarioIntegrado"]) : @$_GET["z_Rec_SalarioDiarioIntegrado"];
	$arrFldOpr = explode(",",$z_Rec_SalarioDiarioIntegrado);
	if ($x_Rec_SalarioDiarioIntegrado <> "") {
		$sSrchAdvanced .= "`Rec_SalarioDiarioIntegrado` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_SalarioDiarioIntegrado) : $x_Rec_SalarioDiarioIntegrado; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_ClaveEntFed
	$x_Rec_ClaveEntFed = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_ClaveEntFed"]) : @$_GET["x_Rec_ClaveEntFed"];
	$arrFldOpr = "";
	$z_Rec_ClaveEntFed = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_ClaveEntFed"]) : @$_GET["z_Rec_ClaveEntFed"];
	$arrFldOpr = explode(",",$z_Rec_ClaveEntFed);
	if ($x_Rec_ClaveEntFed <> "") {
		$sSrchAdvanced .= "`Rec_ClaveEntFed` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_ClaveEntFed) : $x_Rec_ClaveEntFed; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_Status
	$x_Rec_Status = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_Status"]) : @$_GET["x_Rec_Status"];
	$arrFldOpr = "";
	$z_Rec_Status = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_Status"]) : @$_GET["z_Rec_Status"];
	$arrFldOpr = explode(",",$z_Rec_Status);
	if ($x_Rec_Status <> "") {
		$sSrchAdvanced .= "`Rec_Status` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_Status) : $x_Rec_Status; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Mun_ID
	$x_Mun_ID = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Mun_ID"]) : @$_GET["x_Mun_ID"];
	$arrFldOpr = "";
	$z_Mun_ID = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Mun_ID"]) : @$_GET["z_Mun_ID"];
	$arrFldOpr = explode(",",$z_Mun_ID);
	if ($x_Mun_ID <> "") {
		$sSrchAdvanced .= "`Mun_ID` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Mun_ID) : $x_Mun_ID; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}

	// Field Rec_CreationDate
	$x_Rec_CreationDate = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["x_Rec_CreationDate"]) : @$_GET["x_Rec_CreationDate"];
	$arrFldOpr = "";
	$z_Rec_CreationDate = ((function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? stripslashes(@$_GET["z_Rec_CreationDate"]) : @$_GET["z_Rec_CreationDate"];
	$arrFldOpr = explode(",",$z_Rec_CreationDate);
	if ($x_Rec_CreationDate <> "") {
		$sSrchAdvanced .= "`Rec_CreationDate` "; // Add field
		$sSrchAdvanced .= @$arrFldOpr[0] . " "; // Add operator
		if (count($arrFldOpr) >= 1) {
			$sSrchAdvanced .= @$arrFldOpr[1]; // Add search prefix
		}
		$sSrchAdvanced .= (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($x_Rec_CreationDate) : $x_Rec_CreationDate; // Add input parameter
		if (count($arrFldOpr) >=2) {
			$sSrchAdvanced .= @$arrFldOpr[2]; // Add search suffix
		}
		$sSrchAdvanced .= " AND ";
	}
	if (strlen($sSrchAdvanced) > 4) {
		$sSrchAdvanced = substr($sSrchAdvanced, 0, strlen($sSrchAdvanced)-4);
	}
}

//-------------------------------------------------------------------------------
// Function BasicSearchSQL
// - Build WHERE clause for a keyword

function BasicSearchSQL($Keyword)
{
	$sKeyword = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($Keyword) : $Keyword;
	$BasicSearchSQL = "";
	$BasicSearchSQL.= "`Rec_RFC` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Nombre` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Apellido_Paterno` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Apellido_Materno` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_DomicilioFiscaleceptor` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_ResidenciaFiscal` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_NumRegIdTrib` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_RegimenFiscalReceptor` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Curp` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_NumSeguridadSocial` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_FechaInicioRelLaboral` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Antiguedad` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_TipoContrato` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Sindicalizado` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_TipoJornada` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_TipoRegimen` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_NumEmpleado` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Departamento` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Puesto` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_RiesgoPuesto` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_PeriodicidadPago` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Banco` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_CuentaBancaria` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_SalarioBaseCotApor` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_SalarioDiarioIntegrado` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_ClaveEntFed` LIKE '%" . $sKeyword . "%' OR ";
	$BasicSearchSQL.= "`Rec_Status` LIKE '%" . $sKeyword . "%' OR ";
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

		// Field Rec_RFC
		if ($sOrder == "Rec_RFC") {
			$sSortField = "`Rec_RFC`";
			$sLastSort = @$_SESSION["vit_receptor_x_Rec_RFC_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Rec_RFC_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Rec_RFC_Sort"] <> "") { @$_SESSION["vit_receptor_x_Rec_RFC_Sort"] = ""; }
		}

		// Field Rec_Nombre
		if ($sOrder == "Rec_Nombre") {
			$sSortField = "`Rec_Nombre`";
			$sLastSort = @$_SESSION["vit_receptor_x_Rec_Nombre_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Rec_Nombre_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Rec_Nombre_Sort"] <> "") { @$_SESSION["vit_receptor_x_Rec_Nombre_Sort"] = ""; }
		}

		// Field Rec_Apellido_Paterno
		if ($sOrder == "Rec_Apellido_Paterno") {
			$sSortField = "`Rec_Apellido_Paterno`";
			$sLastSort = @$_SESSION["vit_receptor_x_Rec_Apellido_Paterno_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Rec_Apellido_Paterno_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Rec_Apellido_Paterno_Sort"] <> "") { @$_SESSION["vit_receptor_x_Rec_Apellido_Paterno_Sort"] = ""; }
		}

		// Field Rec_Apellido_Materno
		if ($sOrder == "Rec_Apellido_Materno") {
			$sSortField = "`Rec_Apellido_Materno`";
			$sLastSort = @$_SESSION["vit_receptor_x_Rec_Apellido_Materno_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Rec_Apellido_Materno_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Rec_Apellido_Materno_Sort"] <> "") { @$_SESSION["vit_receptor_x_Rec_Apellido_Materno_Sort"] = ""; }
		}

		// Field Rec_Curp
		if ($sOrder == "Rec_Curp") {
			$sSortField = "`Rec_Curp`";
			$sLastSort = @$_SESSION["vit_receptor_x_Rec_Curp_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Rec_Curp_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Rec_Curp_Sort"] <> "") { @$_SESSION["vit_receptor_x_Rec_Curp_Sort"] = ""; }
		}

		// Field Rec_NumSeguridadSocial
		if ($sOrder == "Rec_NumSeguridadSocial") {
			$sSortField = "`Rec_NumSeguridadSocial`";
			$sLastSort = @$_SESSION["vit_receptor_x_Rec_NumSeguridadSocial_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Rec_NumSeguridadSocial_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Rec_NumSeguridadSocial_Sort"] <> "") { @$_SESSION["vit_receptor_x_Rec_NumSeguridadSocial_Sort"] = ""; }
		}

		// Field Rec_NumEmpleado
		if ($sOrder == "Rec_NumEmpleado") {
			$sSortField = "`Rec_NumEmpleado`";
			$sLastSort = @$_SESSION["vit_receptor_x_Rec_NumEmpleado_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Rec_NumEmpleado_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Rec_NumEmpleado_Sort"] <> "") { @$_SESSION["vit_receptor_x_Rec_NumEmpleado_Sort"] = ""; }
		}

		// Field Rec_Departamento
		if ($sOrder == "Rec_Departamento") {
			$sSortField = "`Rec_Departamento`";
			$sLastSort = @$_SESSION["vit_receptor_x_Rec_Departamento_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Rec_Departamento_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Rec_Departamento_Sort"] <> "") { @$_SESSION["vit_receptor_x_Rec_Departamento_Sort"] = ""; }
		}

		// Field Rec_Puesto
		if ($sOrder == "Rec_Puesto") {
			$sSortField = "`Rec_Puesto`";
			$sLastSort = @$_SESSION["vit_receptor_x_Rec_Puesto_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Rec_Puesto_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Rec_Puesto_Sort"] <> "") { @$_SESSION["vit_receptor_x_Rec_Puesto_Sort"] = ""; }
		}

		// Field Rec_Status
		if ($sOrder == "Rec_Status") {
			$sSortField = "`Rec_Status`";
			$sLastSort = @$_SESSION["vit_receptor_x_Rec_Status_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Rec_Status_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Rec_Status_Sort"] <> "") { @$_SESSION["vit_receptor_x_Rec_Status_Sort"] = ""; }
		}

		// Field Mun_ID
		if ($sOrder == "Mun_ID") {
			$sSortField = "`Mun_ID`";
			$sLastSort = @$_SESSION["vit_receptor_x_Mun_ID_Sort"];
			if ($sLastSort == "ASC") { $sThisSort = "DESC"; } else{  $sThisSort = "ASC"; }
			$_SESSION["vit_receptor_x_Mun_ID_Sort"] = $sThisSort;
		}
		else
		{
			if (@$_SESSION["vit_receptor_x_Mun_ID_Sort"] <> "") { @$_SESSION["vit_receptor_x_Mun_ID_Sort"] = ""; }
		}
		$_SESSION["vit_receptor_OrderBy"] = $sSortField . " " . $sThisSort;
		$_SESSION["vit_receptor_REC"] = 1;
	}
	$sOrderBy = @$_SESSION["vit_receptor_OrderBy"];
	if ($sOrderBy == "") {
		$sOrderBy = $sDefaultOrderBy;
		$_SESSION["vit_receptor_OrderBy"] = $sOrderBy;
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
		$_SESSION["vit_receptor_REC"] = $nStartRec;
	}elseif (strlen(@$_GET["pageno"]) > 0) {
		$nPageNo = @$_GET["pageno"];
		if (is_numeric($nPageNo)) {
			$nStartRec = ($nPageNo-1)*$nDisplayRecs+1;
			if ($nStartRec <= 0) {
				$nStartRec = 1;
			}elseif ($nStartRec >= (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1) {
				$nStartRec = (($nTotalRecs-1)/$nDisplayRecs)*$nDisplayRecs+1;
			}
			$_SESSION["vit_receptor_REC"] = $nStartRec;
		}else{
			$nStartRec = @$_SESSION["vit_receptor_REC"];
			if  (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
				$nStartRec = 1; // Reset start record counter
				$_SESSION["vit_receptor_REC"] = $nStartRec;
			}
		}
	}else{
		$nStartRec = @$_SESSION["vit_receptor_REC"];
		if (!(is_numeric($nStartRec)) || ($nStartRec == "")) {
			$nStartRec = 1; //Reset start record counter
			$_SESSION["vit_receptor_REC"] = $nStartRec;
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
			$_SESSION["vit_receptor_searchwhere"] = $sSrchWhere;

		// Reset Search Criteria & Session Keys
		}elseif (strtoupper($sCmd) == "RESETALL") {
			$sSrchWhere = "";
			$_SESSION["vit_receptor_searchwhere"] = $sSrchWhere;

		// Reset Sort Criteria
		}
		elseif (strtoupper($sCmd) == "RESETSORT") {
			$sOrderBy = "";
			$_SESSION["vit_receptor_OrderBy"] = $sOrderBy;
			if (@$_SESSION["vit_receptor_x_Rec_RFC_Sort"] <> "") { $_SESSION["vit_receptor_x_Rec_RFC_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_x_Rec_Nombre_Sort"] <> "") { $_SESSION["vit_receptor_x_Rec_Nombre_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_x_Rec_Apellido_Paterno_Sort"] <> "") { $_SESSION["vit_receptor_x_Rec_Apellido_Paterno_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_x_Rec_Apellido_Materno_Sort"] <> "") { $_SESSION["vit_receptor_x_Rec_Apellido_Materno_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_x_Rec_Curp_Sort"] <> "") { $_SESSION["vit_receptor_x_Rec_Curp_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_x_Rec_NumSeguridadSocial_Sort"] <> "") { $_SESSION["vit_receptor_x_Rec_NumSeguridadSocial_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_x_Rec_NumEmpleado_Sort"] <> "") { $_SESSION["vit_receptor_x_Rec_NumEmpleado_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_x_Rec_Departamento_Sort"] <> "") { $_SESSION["vit_receptor_x_Rec_Departamento_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_x_Rec_Puesto_Sort"] <> "") { $_SESSION["vit_receptor_x_Rec_Puesto_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_x_Rec_Status_Sort"] <> "") { $_SESSION["vit_receptor_x_Rec_Status_Sort"] = ""; }
			if (@$_SESSION["vit_receptor_x_Mun_ID_Sort"] <> "") { $_SESSION["vit_receptor_x_Mun_ID_Sort"] = ""; }
		}

		// Reset Start Position (Reset Command)
		$nStartRec = 1;
		$_SESSION["vit_receptor_REC"] = $nStartRec;
	}
}
?>
