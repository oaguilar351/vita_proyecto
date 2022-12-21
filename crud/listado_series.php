<?php include '../layouts/session.php'; ?>
<?php #include '../layouts/head-main.php'; ?>
<?php
ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
/*if (@$_SESSION["project1_status"] <> "login") {
	header("Location:  login.php");
	exit();
}*/
?>
<?php //require("libs/db.php"); ?>
<?php //require("libs/phpmkrfn.php"); ?>
<?php
if(!isset($conn)){
	require("../libs/db.php");
	require("../libs/phpmkrfn.php");
	$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
}
if(isset($_GET['Page'])){
	parse_str($_GET['Data'], $Datos);
	$s_Cfdi_Serie = mysqli_real_escape_string($conn, @$Datos['s_Cfdi_Serie']);
	$s_Cfdi_Fecha = mysqli_real_escape_string($conn, @$Datos['s_Nom_FechaPago']);
	$s_Emi_RFC = mysqli_real_escape_string($conn, @$Datos['s_Emi_RFC']);
	$s_Status = mysqli_real_escape_string($conn, @$Datos['s_Status']);
	$s_Mun_ID = mysqli_real_escape_string($conn, @$Datos['s_Mun_ID']);
}
$RecordsPerPage = 10;
if(isset($_GET['Page'])){
	$Page = mysqli_real_escape_string($conn, @$_GET['Page']);
}/*else if(isset($Datos['Page'])){	
	$Page = mysqli_real_escape_string($conn, $Datos['Page']);	
}*/else{	
	$Page = 1; 
}
$First = $RecordsPerPage*($Page-1);
$Last = $RecordsPerPage*$Page;

$sSqlWrk = "
SELECT COUNT(DISTINCT vit_comprobantes.Cfdi_Serie) AS series 
FROM 
vit_comprobantes 
INNER JOIN Vit_Nominas ON vit_comprobantes.Cfdi_ID = Vit_Nominas.Cfdi_ID 
WHERE vit_comprobantes.Cfdi_Retcode = '1' 
 ";
if(@$s_Cfdi_Serie!= ""){
$sSqlWrk .= "AND vit_comprobantes.Cfdi_Serie = '".@$s_Cfdi_Serie."' ";
}
if(@$s_Cfdi_Fecha!= ""){
$sSqlWrk .= "AND Vit_Nominas.Nom_FechaPago = '".@$s_Cfdi_Fecha."' ";
}
if(@$s_Emi_RFC!= ""){
$sSqlWrk .= "AND vit_comprobantes.Emi_RFC = '".@$s_Emi_RFC."' ";
}
if(@$s_Mun_ID!= ""){
$sSqlWrk .= "AND vit_comprobantes.Mun_ID = '".@$s_Mun_ID."' ";
}
if(@$_SESSION["project1_status_Municipio"] != ""){
$sSqlWrk .= "AND vit_comprobantes.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
}
$sSqlWrk .= "
AND vit_comprobantes.Cfdi_Status <> 'P' 
";
#echo $sSqlWrk;
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$TotalRows = $rowwrk["series"];
}
@phpmkr_free_result($rswrk);
$TotalPages = ceil ($TotalRows/$RecordsPerPage);

?>
<div class="table-responsive table-card">
	<table class="table align-middle" id="customerTable">
		<thead class="table-light">
			<tr>
				<th><a href="javascript:void(0);">Emisor</a></th>
				<th><a href="javascript:void(0);">Serie</a></th>
				<th><a href="javascript:void(0);">Fecha Pago</a></th>
				<th><a href="javascript:void(0);">Fecha Inicial</a></th>
				<th><a href="javascript:void(0);">Fecha Final</a></th>
				<th><a href="javascript:void(0);">Dias Pagados</a></th>
				<th><a href="javascript:void(0);">Municipio</a></th>
			</tr>
		</thead>
		<tbody class="list form-check-all">
<?php
// Build SQL
$sSql = "
SELECT
vit_comprobantes.Cfdi_Serie, 
Vit_Nominas.Nom_FechaPago, 
Vit_Nominas.Nom_FechaInicialPago, 
Vit_Nominas.Nom_FechaFinalPago, 
Vit_Nominas.Nom_NumDiasPagados, 
Vit_Emisor.Emi_Nombre, 
Vit_Municipios.Mun_Descrip 
FROM 
vit_comprobantes 
INNER JOIN Vit_Nominas ON vit_comprobantes.Cfdi_ID = Vit_Nominas.Cfdi_ID 
INNER JOIN Vit_Emisor ON vit_comprobantes.Emi_RFC = Vit_Emisor.Emi_RFC 
INNER JOIN Vit_Municipios ON vit_comprobantes.Mun_ID = Vit_Municipios.Mun_ID 
WHERE vit_comprobantes.Cfdi_Retcode = '1' 
";
if(@$s_Cfdi_Serie!= ""){
$sSql .= "AND vit_comprobantes.Cfdi_Serie = '".@$s_Cfdi_Serie."' ";	
}
if(@$s_Cfdi_Fecha!= ""){
$sSql .= "AND Vit_Nominas.Nom_FechaPago = '".@$s_Cfdi_Fecha."' ";	
}
if(@$s_Emi_RFC!= ""){
$sSql .= "AND vit_comprobantes.Emi_RFC = '".@$s_Emi_RFC."' ";
}
if(@$s_Mun_ID!= ""){
$sSql .= "AND vit_comprobantes.Mun_ID = '".@$s_Mun_ID."' ";
}
if(@$_SESSION["project1_status_Municipio"] != ""){
$sSql .= "AND vit_comprobantes.Mun_ID = '".@$_SESSION["project1_status_Municipio"]."' ";
}
$sSql .= "
AND vit_comprobantes.Cfdi_Status <> 'P' 
GROUP BY vit_comprobantes.Cfdi_Serie, Vit_Nominas.Nom_FechaPago, Vit_Nominas.Nom_FechaInicialPago, Vit_Nominas.Nom_FechaFinalPago, Vit_Nominas.Nom_NumDiasPagados, Vit_Emisor.Emi_Nombre, Vit_Municipios.Mun_Descrip 
ORDER BY Vit_Nominas.Nom_FechaInicialPago, Vit_Nominas.Nom_FechaFinalPago ASC 
LIMIT ".$First.", ".$RecordsPerPage." ";
#echo "<br />sSql: ".$sSql;
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
if ($rs) {
	while ($row = phpmkr_fetch_array($rs)) {
?>
		
		<tr>
			<td><?php echo strtoupper($row["Emi_Nombre"]); ?></td>
			<td><?php echo ($row["Cfdi_Serie"]); ?></td>
			<td><?php echo FormatDateTime($row["Nom_FechaPago"],5); ?></td>
			<td><?php echo FormatDateTime($row["Nom_FechaInicialPago"],5); ?></td>
			<td><?php echo FormatDateTime($row["Nom_FechaFinalPago"],5); ?></td>
			<td><?php echo ($row["Nom_NumDiasPagados"]); ?></td>
			<td><?php echo strtoupper($row["Mun_Descrip"]); ?></td>
		</tr>
<?php		
	}
}
@phpmkr_free_result($rs);
?>
		</tbody>
	</table>
</div>
<div class="d-flex justify-content-end mt-3">
	<div class="pagination-wrap hstack gap-2">
<?php
if ($TotalPages>0){
?>
	Total de registros: <b><?php echo $TotalRows; ?></b> &nbsp; Registros por pagina: <b><?php echo $RecordsPerPage; ?></b>&nbsp;	
<?php	
echo '<input type="hidden" name="Page" id="Page" value="'.$Page.'">';
if($Page>1 && $Page<$TotalPages){
	$Prev=$Page-1;
	$Post=$Page+1;
}else if($Page==1 && $Page<$TotalPages){
	$Prev=1;
	$Post=$Page+1;
}else if($Page==$TotalPages && $TotalPages==1){
	$Prev=1;
	$Post=1;
}else if($Page==$TotalPages && $TotalPages>1){
	$Prev=$Page-1;
	$Post=$TotalPages;
}
?>
<a class="page-item pagination-prev" href="1">&lsaquo;&lsaquo;</a>
<a class="page-item pagination-prev" href="<?php echo $Prev; ?>">&lsaquo;</a>
<a class="page-item pagination-prev" href="<?php echo $Post; ?>">&rsaquo;</a>
<a class="page-item pagination-prev" href="<?php echo $TotalPages; ?>">&rsaquo;&rsaquo;</a>
<?php
}
?>
	&nbsp;
	Pagina <?php echo '<b>'.$Page.'</b> de <b>'.$TotalPages.'</b>'; ?>
	</div>
</div> 