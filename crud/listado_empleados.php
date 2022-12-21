<?php //include '../layouts/session.php'; ?>
<?php //include '../layouts/head-main.php'; ?>
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
	$s_Mun_ID = mysqli_real_escape_string($conn, @$Datos['s_Mun_ID']);
	$s_RecRFC = mysqli_real_escape_string($conn, @$Datos['s_RecRFC']);
	$s_RecNom = mysqli_real_escape_string($conn, @$Datos['s_RecNom']);
	$s_FechaInicio = mysqli_real_escape_string($conn, @$Datos['s_FechaInicio']);
	$s_Status = mysqli_real_escape_string($conn, @$Datos['s_Status']);
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
SELECT
Count(Vit_Receptor.Rec_RFC) AS empleados
FROM
Vit_Receptor
LEFT JOIN Vit_Municipios ON Vit_Receptor.Mun_ID = Vit_Municipios.Mun_ID 
WHERE Vit_Receptor.Rec_Status <> '' 
";
if(@$s_Mun_ID!= ""){
$sSqlWrk .= "AND Vit_Receptor.Mun_ID = '".@$s_Mun_ID."' ";
}
if(@$s_RecRFC!= ""){
$sSqlWrk .= "AND Vit_Receptor.Rec_RFC = '".@$s_RecRFC."' ";
}
if(@$s_RecNom!= ""){
$sSqlWrk .= "AND (Vit_Receptor.Rec_Nombre LIKE '%".@$s_RecNom."%' OR Vit_Receptor.Rec_Apellido_Paterno LIKE '%".@$s_RecNom."%' OR Vit_Receptor.Rec_Apellido_Materno LIKE '%".@$s_RecNom."%')";
}
if(@$s_FechaInicio!= ""){
$sSqlWrk .= "AND Vit_Receptor.Rec_FechaInicioRelLaboral LIKE '%".@$s_FechaInicio."%' ";
}
if(@$s_Status!= ""){
$sSqlWrk .= "AND Vit_Receptor.Rec_Status = '".@$s_Status."' ";
}
#echo $sSqlWrk;
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
	$TotalRows = $rowwrk["empleados"];
}
@phpmkr_free_result($rswrk);
$TotalPages = ceil ($TotalRows/$RecordsPerPage);

?>
<div class="table-responsive table-card">
	<table class="table align-middle" id="customerTable">
		<thead class="table-light">
			<tr>
				<th><a href="javascript:void(0);">RFC</a></th>
				<th><a href="javascript:void(0);">Nombre</a></th>
				<th><a href="javascript:void(0);">Puesto</a></th>
				<th><a href="javascript:void(0);">Departamento</a></th>
				<th><a href="javascript:void(0);">Numero Empleado</a></th>
				<th><a href="javascript:void(0);">Inicio Relacion</a></th>
				<th><a href="javascript:void(0);">Domicilio</a></th>
				<th><a href="javascript:void(0);">Municipio</a></th>
				<th><a href="javascript:void(0);">Status</a></th>
				<th><a href="javascript:void(0);">Constancia</a></th>
				<th><a href="javascript:void(0);">&nbsp;</a></th>
			</tr>
		</thead>
		<tbody class="list form-check-all">
<?php
// Build SQL
$sSql = "
SELECT
Vit_Receptor.Rec_RFC,
Vit_Receptor.Rec_Nombre,
Vit_Receptor.Rec_Apellido_Paterno,
Vit_Receptor.Rec_Apellido_Materno,
Vit_Receptor.Rec_DomicilioFiscaleceptor,
Vit_Receptor.Rec_FechaInicioRelLaboral,
Vit_Receptor.Rec_NumEmpleado,
Vit_Receptor.Rec_Departamento,
Vit_Receptor.Rec_Puesto,
Vit_Municipios.Mun_Descrip, 
Vit_Receptor.Rec_Status
FROM
Vit_Receptor
LEFT JOIN Vit_Municipios ON Vit_Receptor.Mun_ID = Vit_Municipios.Mun_ID
WHERE Vit_Receptor.Rec_Status <> '' 
";
if(@$s_Mun_ID!= ""){
$sSql .= "AND Vit_Receptor.Mun_ID = '".@$s_Mun_ID."' ";
}
if(@$s_RecRFC!= ""){
$sSql .= "AND Vit_Receptor.Rec_RFC = '".@$s_RecRFC."' ";
}
if(@$s_RecNom!= ""){
$sSql .= "AND (Vit_Receptor.Rec_Nombre LIKE '%".@$s_RecNom."%' OR Vit_Receptor.Rec_Apellido_Paterno LIKE '%".@$s_RecNom."%' OR Vit_Receptor.Rec_Apellido_Materno LIKE '%".@$s_RecNom."%')";
}
if(@$s_FechaInicio!= ""){
$sSql .= "AND Vit_Receptor.Rec_FechaInicioRelLaboral LIKE '%".@$s_FechaInicio."%' ";
}
if(@$s_Status!= ""){
$sSql .= "AND Vit_Receptor.Rec_Status = '".@$s_Status."' ";
}
$sSql .= "
LIMIT ".$First.", ".$RecordsPerPage." 
";
#echo "<br />sSql: ".$sSql;
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
if ($rs) {
	while ($row = phpmkr_fetch_array($rs)) {
		$Rec_RFC = $row["Rec_RFC"];
		$Rec_Nombre = $row["Rec_Nombre"]." ".$row["Rec_Apellido_Paterno"]." ".$row["Rec_Apellido_Materno"];
		$Rec_Puesto = $row["Rec_Puesto"];
		$Rec_Departamento = $row["Rec_Departamento"];
		$Rec_Fecha = $row["Rec_FechaInicioRelLaboral"];
		$Rec_NumEmpleado = $row["Rec_NumEmpleado"];
		$Rec_Domicilio = $row["Rec_DomicilioFiscaleceptor"];
		$Mun_Descrip = $row["Mun_Descrip"];
		$Rec_Status = ($row["Rec_Status"]=="1")?'Activo':'Inactivo';
?>		
		<tr>
			<td><?php echo $Rec_RFC; ?></td>
			<td><?php echo strtoupper($Rec_Nombre); ?></td>
			<td><?php echo strtoupper($Rec_Puesto); ?></td>
			<td><?php echo strtoupper($Rec_Departamento); ?></td>
			<td><?php echo strtoupper($Rec_NumEmpleado); ?></td>
			<td><?php echo FormatDateTime($Rec_Fecha,5); ?></td>
			<td><?php echo strtoupper($Rec_Domicilio); ?></td>
			<td><?php echo strtoupper($Mun_Descrip); ?></td>
			<td><?php echo strtoupper($Rec_Status); ?></td>
			<td align="center">
<?php
if ((!is_null($Rec_RFC)) && ($Rec_RFC <> "")) {
	$sSqlWrk = "SELECT const_ContanciaID FROM constancias ";
	$sSqlWrk .= " WHERE const_RFC = '" . $Rec_RFC . "'";
	#echo $sSqlWrk;
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		#$sTmp = strtoupper($rowwrk["Mun_Descrip"]);
?>	
	<a href="receptor_constancia.php?showmaster=1&const_RFC=<?php echo urlencode($Rec_RFC); ?>"><i class="ri-checkbox-multiple-fill" style="font-size:16px; color:#689f38;"></i></a>
<?php	
	} else {
?>	
	<i class="ri-checkbox-multiple-blank-fill" style="font-size:16px; color:#CECECE;"></i>
<?php	
	}
	@phpmkr_free_result($rswrk);
}	
?>
			</td>
			<td>
			<a href="<?php if ($Rec_RFC <> "") {echo "receptoredit.php?Rec_RFC=" . urlencode($Rec_RFC); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">
			<i class="ri-pencil-fill" style="font-size:16px; color:#689f38;"></i>&nbsp;Editar&nbsp;
			</a>
			<?php
if ((!is_null($Rec_RFC)) && ($Rec_RFC <> "")) {
	$sSqlWrk = "SELECT Rmv_ID FROM Vit_Receptor_Movimientos ";
	$sSqlWrk .= " WHERE Rec_RFC = '" . $Rec_RFC . "'";
	$sSqlWrk .= " LIMIT 1";
	#echo $sSqlWrk;
	$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
	if ($rswrk && $rowwrk = phpmkr_fetch_array($rswrk)) {
		#$sTmp = strtoupper($rowwrk["Mun_Descrip"]);
?>	
			<a href="<?php if ($Rec_RFC <> "") {echo "vit_receptor_movimientoslist.php?showmaster=1&Rec_RFC=" . urlencode($Rec_RFC); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">
			<i class="ri-profile-line" style="font-size:16px; color:#689f38;"></i>&nbsp;Movimientos
			</a>
<?php	
	} else {
?>	
	&nbsp;
<?php	
	}
	@phpmkr_free_result($rswrk);
}	
?>
				<!--<div class="dropdown">
					<a href="#" role="button" id="dropdownMenuLink1" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="ri-more-2-fill"></i>
					</a>					
					<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
						<li><a class="dropdown-item" href="<?php if ($Rec_RFC <> "") {echo "receptoredit.php?Rec_RFC=" . urlencode($Rec_RFC); } else { echo "javascript:alert('Invalid Record! Key is null');";} ?>">Editar</a></li>
												
					</ul>					
				</div>--->
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