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
$x_Mun_IDList = "<select class=\"form-control\" data-choices data-choices-search-false id=\"s_Mun_ID\" name=\"s_Mun_ID\">";
$x_Mun_IDList .= "<option value=''>Municipios</option>";
$sSqlWrk = "SELECT DISTINCT `Mun_ID`, `Mun_Descrip` FROM `Vit_Municipios` WHERE Mun_ID <> '' ";
if(@$Mun_ID != ""){
$sSqlWrk .= "AND Mun_ID = '".@$Mun_ID."' ";
}
$sSqlWrk .= "ORDER BY `Mun_Descrip` ASC ";
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_Mun_IDList .= "<option value=\"" . htmlspecialchars($datawrk["Mun_ID"]) . "\"";
		$x_Mun_IDList .= ">" . $datawrk["Mun_Descrip"] . "</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_Mun_IDList .= "</select>";
echo $x_Mun_IDList;
?>