<?php include '../layouts/session.php'; ?>
<?php
if(!isset($conn)){
	require("../libs/db.php");
	require("../libs/phpmkrfn.php");
	$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
}
#echo "<br />Mun_ID: ".
$Mun_ID = mysqli_real_escape_string($conn, $_POST['Mun_ID']);
?>
<?php
$x_Cfdi_SerieList = "<select class=\"form-control\" data-choices data-choices-search-false id=\"s_Cfdi_Serie\" name=\"s_Cfdi_Serie\">";
$x_Cfdi_SerieList .= "<option value=''>Series</option>";
$sSqlWrk = "
SELECT DISTINCT 
vit_comprobantes.Cfdi_Serie, 
vit_comprobantes.Cfdi_Status, 
vit_comprobantes.Cfdi_Error 
FROM 
vit_comprobantes 
WHERE vit_comprobantes.Cfdi_Status <> 'P'  
";
if(@$Mun_ID != ""){
$sSqlWrk .= "AND vit_comprobantes.Mun_ID = '".@$Mun_ID."' ";
}
$sSqlWrk .= "ORDER BY vit_comprobantes.Cfdi_Serie ASC";
#echo "<br />".$sSqlWrk;
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error() . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_Cfdi_SerieList .= "<option value=\"" . htmlspecialchars($datawrk["Cfdi_Serie"]) . "\"";
		$x_Cfdi_SerieList .= ">" . $datawrk["Cfdi_Serie"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_Cfdi_SerieList .= "</select>";
echo $x_Cfdi_SerieList;
?>