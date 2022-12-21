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

$RecordsPerPage = 10;
if(isset($_GET['Page'])){
	$Page = mysqli_real_escape_string($conn, $_GET['Page']);
}else if(isset($Datos['Page'])){	
	$Page = mysqli_real_escape_string($conn, $Datos['Page']);	
}else{	
	$Page = 1; 
}
$First = $RecordsPerPage*($Page-1);
$Last = $RecordsPerPage*$Page;

$sSqlWrk = "SELECT Count(vit_comprobantes.Cfdi_ID) AS comprobantes FROM vit_comprobantes ";
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
				<th>															
			Version&nbsp;(*)
				</th>
				<th>
			Serie&nbsp;(*)
				</th>
				<th>
			Folio
				</th>
				<th>
			Fecha
				</th>
				<th>
			Subtotal
				</th>
				<th>
			Descuento
				</th>
				<th>
			Moneda&nbsp;(*)
				</th>
				<th>
			Total
				</th>
				<th>
			Emisor
				</th>
				<th>
			Receptor
				</th>
				<th>
			UUID&nbsp;(*)
				</th>
				<th>
			Status Comprobante
				</th>
				<th>
			Status SAT
				</th>
				<th>
			Archivos
				</th>
				<th>
			Municipio
				</th
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
vit_municipios.Mun_Descrip AS Municipio 
FROM vit_comprobantes 
LEFT JOIN vit_emisor ON vit_comprobantes.Emi_RFC = vit_emisor.Emi_RFC 
LEFT JOIN vit_receptor ON vit_comprobantes.Rec_RFC = vit_receptor.Rec_RFC 
LEFT JOIN vit_municipios ON vit_comprobantes.Mun_ID = vit_municipios.Mun_ID 
LIMIT ".$First.", ".$RecordsPerPage." ";
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
			<!-- Table body -->
			<tr>
					<!-- Cfdi_Version -->
					<td>
			<?php echo $x_Cfdi_Version; ?>
			</td>
					<!-- Cfdi_Serie -->
					<td>
			<?php echo $x_Cfdi_Serie; ?>
			</td>
					<!-- Cfdi_Folio -->
					<td>
			<?php echo $x_Cfdi_Folio; ?>
			</td>
					<!-- Cfdi_Fecha -->
					<td>
			<?php echo FormatDateTime($x_Cfdi_Fecha,5); ?>
			</td>
					<!-- Cfdi_Subtotal -->
					<td>
			<div align="right"><?php echo (is_numeric($x_Cfdi_Subtotal)) ? FormatCurrency($x_Cfdi_Subtotal,2,-2,-2,-2) : $x_Cfdi_Subtotal; ?></div>
			</td>
					<!-- Cfdi_Descuento -->
					<td>
			<div align="right"><?php echo (is_numeric($x_Cfdi_Descuento)) ? FormatCurrency($x_Cfdi_Descuento,2,-2,-2,-2) : $x_Cfdi_Descuento; ?></div>
			</td>
					<!-- c_Moneda -->
					<td>
			<?php echo $x_c_Moneda; ?>
			</td>
					<!-- Cfdi_Total -->
					<td>
			<div align="right"><?php echo (is_numeric($x_Cfdi_Total)) ? FormatCurrency($x_Cfdi_Total,2,-2,-2,-2) : $x_Cfdi_Total; ?></div>
			</td>
					<!-- Emi_RFC -->
					<td>
			<?php
			if ((!is_null($x_Emi_RFC)) && ($x_Emi_RFC <> "")) {
				$sSqlWrk = "SELECT DISTINCT Emi_Nombre FROM vit_emisor";
				$sTmp = $x_Emi_RFC;
				$sTmp = addslashes($sTmp);
				$sSqlWrk .= " WHERE Emi_RFC = '" . $sTmp . "'";
				$sSqlWrk .= " ORDER BY Emi_RFC Asc";
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
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
			</td>
					<!-- Rec_RFC -->
					<td>
			<?php
			if ((!is_null($x_Rec_RFC)) && ($x_Rec_RFC <> "")) {
				$sSqlWrk = "SELECT DISTINCT Rec_Nombre, Rec_Apellido_Paterno, Rec_Apellido_Materno FROM vit_receptor";
				$sTmp = $x_Rec_RFC;
				$sTmp = addslashes($sTmp);
				$sSqlWrk .= " WHERE Rec_RFC = '" . $sTmp . "'";
				$sSqlWrk .= " ORDER BY Rec_RFC Asc";
				$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
				if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
					$sTmp = $rowwrk["Rec_Nombre"]." ".$rowwrk["Rec_Apellido_Paterno"]." ".$rowwrk["Rec_Apellido_Materno"];
				}
				@phpmkr_free_result($rswrk);
			} else {
				$sTmp = "";
			}
			$ox_Rec_RFC = $x_Rec_RFC; // Backup Original Value
			$x_Rec_RFC = $sTmp;
			?>
			<?php echo $x_Rec_RFC; ?>
			<?php $x_Rec_RFC = $ox_Rec_RFC; // Restore Original Value ?>
			</td>
					<!-- Cfdi_UUID -->
					<td>
			<?php echo $x_Cfdi_UUID; ?>
			</td>
					<!-- Cfdi_Status -->
					<td>
			<?php
			switch ($x_Cfdi_Status) {
				case "A":
					$sTmp = '<span class="badge badge-soft-success">Activo</span>';
					break;
				case "C":
					$sTmp = '<span class="badge badge-soft-danger">Cancelado</span>';
					break;
				case "P":
					$sTmp = '<span class="badge badge-soft-info">Pendiente</span>';
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
					<i class="bx bx-check" style="color:#117a65; font-size:24px;" title="Correcto"></i>
					<?php }else{ ?>
					<i class="bx bx-error" style="color:#b03a2e; font-size:24px;" title="<?php echo $x_Cfdi_Error; ?>"></i>
					<?php 
					}
					?>
					</td>
					<!-- archivos Cfdi_UUID -->
					<td>		
					<?php 
					$archivo_o = $row["Cfdi_Serie"].'/'.$row["Cfdi_Serie"].'-'.$row["Cfdi_Folio"].'-'.$row["Cfdi_UUID"].'.xml';
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
					<!-- Mun_ID -->
					<td>
			<?php
			if ((!is_null($x_Mun_ID)) && ($x_Mun_ID <> "")) {
				$sSqlWrk = "SELECT DISTINCT Mun_Descrip FROM vit_municipios";
				$sTmp = $x_Mun_ID;
				$sTmp = addslashes($sTmp);
				$sSqlWrk .= " WHERE Mun_ID = " . $sTmp . "";
				$sSqlWrk .= " ORDER BY Mun_Descrip Asc";
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
			</td>
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