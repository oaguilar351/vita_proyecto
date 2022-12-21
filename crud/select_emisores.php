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
$x_Emi_RFCList = "<select class=\"form-control\" data-choices data-choices-search-false id=\"s_Emi_RFC\" name=\"s_Emi_RFC\">";
$x_Emi_RFCList .= "<option value=''>Emisor</option>";
$sSqlWrk = "
SELECT DISTINCT
Vit_Emisor.Emi_RFC, 
Vit_Emisor.Emi_Nombre, 
Vit_Emisor.Emi_NomCorto
FROM Vit_Emisor
WHERE Vit_Emisor.Emi_RFC <> '' ";
if($Mun_ID != ""){
$sSqlWrk .= "AND Vit_Emisor.Mun_ID = '".$Mun_ID."' ";
}
$sSqlWrk .= "
ORDER BY Vit_Emisor.Emi_Nombre ASC";
#echo "<br />".$sSqlWrk;
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_Emi_RFCList .= "<option value=\"" . htmlspecialchars($datawrk["Emi_RFC"]) . "\"";
		$x_Emi_RFCList .= ">" . $datawrk["Emi_Nombre"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_Emi_RFCList .= "</select>";
echo $x_Emi_RFCList;
?>