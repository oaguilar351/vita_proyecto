<?php
if(!isset($conn)){
	require("../libs/db.php");
	require("../libs/phpmkrfn.php");
	$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
}
if(isset($_GET['Data'])){
parse_str($_GET['Data'], $Datos);
$s_Mun_ID = mysqli_real_escape_string($conn, @$Datos['s_Mun_ID']);
$s_Emi_RFC = mysqli_real_escape_string($conn, @$Datos['s_Emi_RFC']);
}
$s_Ejercicio = (isset($Datos['s_Ejercicio']))?$Datos['s_Ejercicio']:date('Y');
$s_Periodo = (isset($Datos['s_Periodo']))?$Datos['s_Periodo']:'';
?>
<table class="table align-middle" id="customerTable">
	<thead class="table-light">
		<tr>
			<th><a href="javascript:void(0);">Municipio</a></th>
			<th><a href="javascript:void(0);">Emisor</a></th>
			<th><a href="javascript:void(0);">Ejercicio</a></th>
			<th><a href="javascript:void(0);">Periodo</a></th>
			<!--<th><a href="javascript:void(0);">Total Cargo</a></th>-->
			<th><a href="javascript:void(0);"> Pagado</a></th>
			<th><a href="javascript:void(0);"> Tmbrado</a></th>
			<th><a href="javascript:void(0);"> Recurso Propio</a></th>
			<th><a href="javascript:void(0);"> Recurso Federal</a></th>
			<th><a href="javascript:void(0);">Emitidos</a></th>
			<th><a href="javascript:void(0);">Correctos</a></th>
			<th><a href="javascript:void(0);">Cancelados</a></th>
			<!--<th><a href="javascript:void(0);">&nbsp;</a></th>-->
		</tr>
	</thead>
	<tbody class="list form-check-all">
<?php
// Build SQL
$totalCargo = 0;
$totalPagado = 0;
$totalTimbrado = 0;
$totalPropio = 0;
$totalFederal = 0;
$totalEmitidos = 0;
$totalCorrectos = 0;
$totalCancelados = 0;
$sSql = "
SELECT 
Vit_Municipios.Mun_Descrip, 
Vit_Emisor.Emi_Nombre, 
Vit_Emisor.Emi_NomCorto,  
vit_informe.inf_ejercicio, 
vit_informe.inf_periodo, 
vit_informe.inf_totalCargo, 
vit_informe.inf_totalPagado, 
vit_informe.inf_totalTimbrado, 
vit_informe.inf_totalPropio, 
vit_informe.inf_totalFederal, 
vit_informe.inf_totalEmitidos, 
vit_informe.inf_totalCorrectos, 
vit_informe.inf_totalCancelados, 
vit_informe.inf_obsValidacion 
FROM 
vit_informe 
INNER JOIN Vit_Municipios ON vit_informe.Mun_ID = Vit_Municipios.Mun_ID 
INNER JOIN Vit_Emisor ON vit_informe.Emi_RFC = Vit_Emisor.Emi_RFC 
WHERE vit_informe.inf_informeID <> '' 
";
if(@$s_Mun_ID!= ""){
$sSql .= "AND vit_informe.Mun_ID = '".@$s_Mun_ID."' ";
}
if(@$s_Emi_RFC!= ""){
$sSql .= "AND vit_informe.Emi_RFC = '".@$s_Emi_RFC."' ";
}
if(@$s_Ejercicio!= ""){
$sSql .= "AND vit_informe.inf_ejercicio = '".@$s_Ejercicio."' ";
}
if(@$s_Periodo!= ""){
$sSql .= "AND vit_informe.inf_periodo = '".@$s_Periodo."' ";
}
$sSql .= "
ORDER BY vit_informe.inf_ejercicio, vit_informe.inf_periodo ASC ";
#echo "<br />sSql: ".$sSql;
$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
if ($rs) {
while ($row = phpmkr_fetch_array($rs)) {
	
	$inf_totalCargo = $row["inf_totalCargo"];
	$inf_totalPagado = $row["inf_totalPagado"];
	$inf_totalTimbrado = $row["inf_totalTimbrado"];
	$inf_totalPropio = $row["inf_totalPropio"];
	$inf_totalFederal = $row["inf_totalFederal"];
	$inf_totalEmitidos = $row["inf_totalEmitidos"];
	$inf_totalCorrectos = $row["inf_totalCorrectos"];
	$inf_totalCancelados = $row["inf_totalCancelados"];
?>
	<tr>
		<td><?php echo strtoupper($row["Mun_Descrip"]); ?></td>
		<td><?php echo strtoupper($row["Emi_NomCorto"]); ?></td>
		<td><?php echo ($row["inf_ejercicio"]); ?></td>
		<td><?php echo ($row["inf_periodo"]); ?></td>
		<!--<td align="right">$ <?php echo number_format($inf_totalCargo,2); ?></td>-->
		<td align="right">$ <?php echo number_format($inf_totalPagado,2); ?></td>
		<td align="right">$ <?php echo number_format($inf_totalTimbrado,2); ?></td>
		<td align="right">$ <?php echo number_format($inf_totalPropio,2); ?></td>
		<td align="right">$ <?php echo number_format($inf_totalFederal,2); ?></td>
		<td align="right"><?php echo number_format($inf_totalEmitidos,0); ?></td>
		<td align="right"><?php echo number_format($inf_totalCorrectos,0); ?></td>
		<td align="right"><?php echo number_format($inf_totalCancelados,0); ?></td>
	</tr>
<?php
	$totalCargo += $row["inf_totalCargo"];
	$totalPagado += $row["inf_totalPagado"];
	$totalTimbrado += $row["inf_totalTimbrado"];
	$totalPropio += $row["inf_totalPropio"];
	$totalFederal += $row["inf_totalFederal"];
	$totalEmitidos += $row["inf_totalEmitidos"];
	$totalCorrectos += $row["inf_totalCorrectos"];
	$totalCancelados += $row["inf_totalCancelados"];
}
?>
	<tr>
		<td><b>Totales</b></td>
		<td align="right"><b>&nbsp;</b></td>
		<td align="right"><b>&nbsp;</b></td>
		<td align="right"><b>&nbsp;</b></td>
		<!--<td align="right"><b>$ <?php echo number_format($totalCargo,2); ?></b></td>-->
		<td align="right"><b>$ <?php echo number_format($totalPagado,2); ?></b></td>
		<td align="right"><b>$ <?php echo number_format($totalTimbrado,2); ?></b></td>
		<td align="right"><b>$ <?php echo number_format($totalPropio,2); ?></b></td>
		<td align="right"><b>$ <?php echo number_format($totalFederal,2); ?></b></td>
		<td align="right"><b><?php echo number_format($totalEmitidos,0); ?></b></td>
		<td align="right"><b><?php echo number_format($totalCorrectos,0); ?></b></td>
		<td align="right"><b><?php echo number_format($totalCancelados,0); ?></b></td>
		<!--<td align="right">&nbsp;</td>-->
	</tr>
<?php
}
@phpmkr_free_result($rs);
?>
	</tbody>
</table>