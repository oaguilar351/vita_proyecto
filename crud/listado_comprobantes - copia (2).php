<?php #include '../layouts/session.php'; ?>
<?php #include '../layouts/head-main.php'; ?>
<?php
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
	$s_Cfdi_Serie = mysqli_real_escape_string($conn, $Datos['s_Cfdi_Serie']);
	$s_Cfdi_Folio = mysqli_real_escape_string($conn, $Datos['s_Cfdi_Folio']);
	$s_Cfdi_Fecha = mysqli_real_escape_string($conn, $Datos['s_Cfdi_Fecha']);
	$s_Emi_RFC = mysqli_real_escape_string($conn, $Datos['s_Emi_RFC']);
	$s_Rec_RFC = mysqli_real_escape_string($conn, $Datos['s_Rec_RFC']);
	$s_Cfdi_UUID = mysqli_real_escape_string($conn, $Datos['s_Cfdi_UUID']);
	$s_Status = mysqli_real_escape_string($conn, $Datos['s_Status']);
	$s_StatusSat = mysqli_real_escape_string($conn, $Datos['s_StatusSat']);
}
$RecordsPerPage = 10;
if(isset($_GET['Page'])){
	$Page = mysqli_real_escape_string($conn, $_GET['Page']);
}/*else if(isset($Datos['Page'])){	
	$Page = mysqli_real_escape_string($conn, $Datos['Page']);	
}*/else{	
	$Page = 1; 
}
$First = $RecordsPerPage*($Page-1);
$Last = $RecordsPerPage*$Page;

$sSqlWrk = "
SELECT 
Count(vit_comprobantes.Cfdi_ID) AS comprobantes 
FROM vit_comprobantes 
WHERE vit_comprobantes.Cfdi_ID <> '' ";
if(@$s_Cfdi_Serie!= ""){
$sSqlWrk .= "AND vit_comprobantes.Cfdi_Serie = '".@$s_Cfdi_Serie."' ";
}
if(@$s_Cfdi_Folio!= ""){
$sSqlWrk .= "AND vit_comprobantes.Cfdi_Folio = '".@$s_Cfdi_Folio."' ";
}
if(@$s_Cfdi_Fecha!= ""){
$sSqlWrk .= "AND vit_comprobantes.Cfdi_Fecha LIKE '%".@$s_Cfdi_Fecha."%' ";
}
if(@$s_Emi_RFC!= ""){
$sSqlWrk .= "AND vit_comprobantes.Emi_RFC = '".@$s_Emi_RFC."' ";
}
if(@$s_Rec_RFC!= ""){
$sSqlWrk .= "AND vit_comprobantes.Rec_RFC = '".@$s_Rec_RFC."' ";
}
if(@$s_Cfdi_UUID!= ""){
$sSqlWrk .= "AND vit_comprobantes.Cfdi_UUID = '".@$s_Cfdi_UUID."' ";
}
if(@$s_Status!= ""){
$sSqlWrk .= "AND vit_comprobantes.Cfdi_Status = '".@$s_Status."' ";
}
if(@$s_StatusSat!= ""){
	if(@$s_StatusSat== "Success"){
		$sSqlWrk .= "AND vit_comprobantes.Cfdi_Error = '".@$s_StatusSat."' ";
	}else if(@$s_StatusSat!= "Success"){
		$sSqlWrk .= "AND vit_comprobantes.Cfdi_Error <> 'Success' ";
	}
}
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$TotalRows = $rowwrk["comprobantes"];
}
@phpmkr_free_result($rswrk);
$TotalPages = ceil ($TotalRows/$RecordsPerPage);

?>
<div class="table-responsive table-card">
	<table class="table align-middle" id="customerTable">
		<thead class="table-light">
			<tr>
				<th>Serie</th>
				<th>Folio</th>
				<th>Fecha</th>
				<th>Subtotal</th>
				<th>Descuento</th>
				<th>Total</th>
				<th>Emisor</th>
				<th>Receptor</th>
				<th>UUID</th>
				<th>Status Comprobante</th>
				<th>Status SAT</th>
				<th>Archivos</th>
				<th>Municipio</th>
			</tr>
		</thead>
		<tbody class="list form-check-all">
<?php
// Build SQL
$sSql = "
SELECT 
vit_comprobantes.Cfdi_ID,
vit_comprobantes.Cfdi_Version,
vit_comprobantes.Cfdi_Serie,
vit_comprobantes.Cfdi_Folio,
vit_comprobantes.Cfdi_Fecha,
vit_comprobantes.Cfdi_Sello,
vit_comprobantes.c_FormaPago,
vit_comprobantes.Cfdi_Certificado,
vit_comprobantes.Cfdi_NoCertificado,
vit_comprobantes.Cfdi_CondicionesDePago,
vit_comprobantes.Cfdi_Subtotal,
vit_comprobantes.Cfdi_Descuento,
vit_comprobantes.c_Moneda,
vit_comprobantes.Cfdi_TipoCambio,
vit_comprobantes.Cfdi_Total,
vit_comprobantes.c_TipoDeComprobante,
vit_comprobantes.c_Exportacion,
vit_comprobantes.c_MetodoPago,
vit_comprobantes.c_CodigoPostal,
vit_comprobantes.Cfdi_Confirmacion,
vit_comprobantes.Emi_RFC,
vit_comprobantes.Rec_RFC,
vit_comprobantes.Cfdi_Error,
vit_comprobantes.Cfdi_UsoCFDI,
vit_comprobantes.Cfdi_UUID,
vit_comprobantes.Cfdi_Retcode,
vit_comprobantes.Cfdi_Timestamp,
vit_comprobantes.Cfdi_Acuse,
vit_comprobantes.Cfdi_Status,
vit_comprobantes.Mun_ID,
vit_comprobantes.Cfdi_CreationDate,
vit_comprobantes.Cfdi_Cotejado,
vit_comprobantes.Cfdi_UUID_Sustitucion,
vit_comprobantes.Cfdi_Motivo_Cancelacion,
vit_comprobantes.Cfdi_Fecha_Cancelacion,
vit_emisor.Emi_Nombre AS Emisor, 
CONCAT(vit_receptor.Rec_Nombre,' ',vit_receptor.Rec_Apellido_Paterno,' ',vit_receptor.Rec_Apellido_Materno) AS Receptor, 
vit_comprobantes.Cfdi_UUID AS UUID, 
IF(vit_comprobantes.Cfdi_Status = 'A', 'Activo', if(vit_comprobantes.Cfdi_Status = 'C', 'Cancelado', 'Pendiente')) AS Estatus, 
vit_municipios.Mun_Descrip AS Municipio,
c_motivocancelacion.Descripcion AS Motivo 
FROM vit_comprobantes 
LEFT JOIN vit_emisor ON vit_comprobantes.Emi_RFC = vit_emisor.Emi_RFC 
LEFT JOIN vit_receptor ON vit_comprobantes.Rec_RFC = vit_receptor.Rec_RFC 
LEFT JOIN vit_municipios ON vit_comprobantes.Mun_ID = vit_municipios.Mun_ID 
LEFT JOIN c_motivocancelacion ON vit_comprobantes.Cfdi_Motivo_Cancelacion = c_motivocancelacion.c_MotivoCancelacion 
WHERE vit_comprobantes.Cfdi_ID <> '' ";
if(@$s_Cfdi_Serie!= ""){
$sSql .= "AND vit_comprobantes.Cfdi_Serie = '".@$s_Cfdi_Serie."' ";	
}
if(@$s_Cfdi_Folio!= ""){
$sSql .= "AND vit_comprobantes.Cfdi_Folio = '".@$s_Cfdi_Folio."' ";	
}
if(@$s_Cfdi_Fecha!= ""){
$sSql .= "AND vit_comprobantes.Cfdi_Fecha LIKE '%".@$s_Cfdi_Fecha."%' ";	
}
if(@$s_Emi_RFC!= ""){
$sSql .= "AND vit_comprobantes.Emi_RFC = '".@$s_Emi_RFC."' ";
}
if(@$s_Rec_RFC!= ""){
$sSql .= "AND vit_comprobantes.Rec_RFC = '".@$s_Rec_RFC."' ";
}
if(@$s_Cfdi_UUID!= ""){
$sSql .= "AND vit_comprobantes.Cfdi_UUID = '".@$s_Cfdi_UUID."' ";
}
if(@$s_Status!= ""){
$sSql .= "AND vit_comprobantes.Cfdi_Status = '".@$s_Status."' ";
}
if(@$s_StatusSat!= ""){
	if(@$s_StatusSat== "Success"){
		$sSql .= "AND vit_comprobantes.Cfdi_Error = '".@$s_StatusSat."' ";
	}else if(@$s_StatusSat!= "Success"){
		$sSql .= "AND vit_comprobantes.Cfdi_Error <> 'Success' ";
	}
}
$sSql .= "
LIMIT ".$First.", ".$RecordsPerPage." ";
#echo "<br />sSql: ".$sSql;
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
if ($rs) {
	while ($row = phpmkr_fetch_array($rs)) {
		$x_Cfdi_ID = $row["Cfdi_ID"];
		$x_Cfdi_Version = $row["Cfdi_Version"];
		$x_Cfdi_Serie = $row["Cfdi_Serie"];
		$x_Cfdi_Folio = $row["Cfdi_Folio"];
		$x_Cfdi_Fecha = $row["Cfdi_Fecha"];
		$x_Cfdi_Sello = $row["Cfdi_Sello"];
		$x_c_FormaPago = $row["c_FormaPago"];
		$x_Cfdi_Certificado = $row["Cfdi_Certificado"];
		$x_Cfdi_NoCertificado = $row["Cfdi_NoCertificado"];
		$x_Cfdi_CondicionesDePago = $row["Cfdi_CondicionesDePago"];
		$x_Cfdi_Subtotal = $row["Cfdi_Subtotal"];
		$x_Cfdi_Descuento = $row["Cfdi_Descuento"];
		$x_c_Moneda = $row["c_Moneda"];
		$x_Cfdi_TipoCambio = $row["Cfdi_TipoCambio"];
		$x_Cfdi_Total = $row["Cfdi_Total"];
		$x_c_TipoDeComprobante = $row["c_TipoDeComprobante"];
		$x_c_Exportacion = $row["c_Exportacion"];
		$x_c_MetodoPago = $row["c_MetodoPago"];
		$x_c_CodigoPostal = $row["c_CodigoPostal"];
		$x_Cfdi_Confirmacion = $row["Cfdi_Confirmacion"];
		$x_Emi_RFC = $row["Emi_RFC"];
		$x_Rec_RFC = $row["Rec_RFC"];
		$x_Cfdi_Error = $row["Cfdi_Error"];
		$x_Cfdi_UsoCFDI = $row["Cfdi_UsoCFDI"];
		$x_Cfdi_UUID = $row["Cfdi_UUID"];
		$x_Cfdi_Retcode = $row["Cfdi_Retcode"];
		$x_Cfdi_Timestamp = $row["Cfdi_Timestamp"];
		$x_Cfdi_Acuse = $row["Cfdi_Acuse"];
		$x_Cfdi_Status = $row["Cfdi_Status"];
		$x_Mun_ID = $row["Mun_ID"];
		$x_Cfdi_CreationDate = $row["Cfdi_CreationDate"];
		$x_Cfdi_Cotejado = $row["Cfdi_Cotejado"];
?>
		
		<tr>
			<td><?php echo $x_Cfdi_Serie; ?></td>
			<td><?php echo $x_Cfdi_Folio; ?></td>
			<td><?php echo FormatDateTime($x_Cfdi_Fecha,5); ?></td>
			<td><div align="right"><?php echo (is_numeric($x_Cfdi_Subtotal)) ? FormatCurrency($x_Cfdi_Subtotal,2,-2,-2,-2) : $x_Cfdi_Subtotal; ?></div></td>
			<td><div align="right"><?php echo (is_numeric($x_Cfdi_Descuento)) ? FormatCurrency($x_Cfdi_Descuento,2,-2,-2,-2) : $x_Cfdi_Descuento; ?></div></td>
			<td><div align="right"><?php echo (is_numeric($x_Cfdi_Total)) ? FormatCurrency($x_Cfdi_Total,2,-2,-2,-2) : $x_Cfdi_Total; ?></div></td>
			<td><?php echo $row["Emisor"]; ?></td>
			<td><?php echo ($row["Receptor"]=="")?$x_Rec_RFC:$row["Receptor"]; ?></td>
			<td><?php echo $x_Cfdi_UUID; ?></td>
			<td>
			<?php
			switch ($x_Cfdi_Status) {
				case "A":
					$sTmp = '<span class="badge badge-soft-success">Activo</span>';
					break;
				case "C":
					$sTmp = '<span class="badge badge-soft-danger">Cancelado<br />'.FormatDateTime($row["Cfdi_Fecha_Cancelacion"],5).'<br /> '.$row["Motivo"].'</span>';
					break;
				case "P":
					$sTmp = '<span class="badge badge-soft-warning">Pendiente de cancelar</span>';
					break;
				default:
					$sTmp = "";
			}
			$ox_Cfdi_Status = $x_Cfdi_Status; // Backup Original Value
			$x_Cfdi_Status = $sTmp;
			?>
			<?php echo $x_Cfdi_Status; ?>
			<?php $x_Cfdi_Status = $ox_Cfdi_Status; // Restore Original Value ?>
			</td>
			<td>
			<?php 
			if($x_Cfdi_Error=='Success'){
			?>	
			<span class="badge badge-soft-success" title="Correcto">Timbrado</span><i class="bx bx-check" style="color:#82e0aa; font-size:24px;" title="Correcto"></i>
			<?php }else{ ?>
			<span class="badge badge-soft-warning" title="<?php echo $x_Cfdi_Error; ?>">Sin Timbrar</span><i class="bx bx-error" style="color:#f39c12; font-size:24px;" title="<?php echo $x_Cfdi_Error; ?>"></i>
			<?php 
			}
			?>
			</td>
			<td>		
			<?php 
			$archivo_o = $row["Cfdi_Serie"].'/'.$row["Cfdi_Serie"].'-'.$row["Cfdi_Folio"].'-'.$row["Cfdi_UUID"].'.xml';
			if($x_Cfdi_Status=="C"){
			$archivo_o = $row["Cfdi_Serie"].'/'.$row["Cfdi_Serie"].'-'.$row["Cfdi_Folio"].'-'.$row["Cfdi_UUID_Sustitucion"].'.xml';
			}
			#echo 
			$url = 'https://admin.vitainsumos.mx/XML/'.$archivo_o;
			$file_headers = @get_headers($url);
			if($file_headers[0] != 'HTTP/1.1 404 Not Found') {
			?>
			<a href="<?php echo $url; ?>" target="_blank"><i class="las la-file-code" style="color:#2874a6; font-size:24px;"></i></a>
			<?php 
			}
			?>
			</td>
			<td><?php echo $row["Municipio"]; ?></td>
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