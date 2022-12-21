<?php include '../layouts/session.php'; ?>
<?php
if(!isset($conn)){
	require("../libs/db.php");
	require("../libs/phpmkrfn.php");
	$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
}
$Mun_ID = mysqli_real_escape_string($conn, @$POST_['Mun_ID']);
?>
<?php
$x_Rec_RFCList = "<select class=\"form-control\" data-choices data-choices-search-false id=\"s_Rec_RFC\" name=\"s_Rec_RFC\">";
$x_Rec_RFCList .= "<option value=''>Receptor</option>";
$sSqlWrk = "SELECT ";
$sSqlWrk .= "Vit_Receptor.Rec_RFC, ";
$sSqlWrk .= "Vit_Receptor.Rec_Nombre, ";
$sSqlWrk .= "Vit_Receptor.Rec_Apellido_Paterno, ";
$sSqlWrk .= "Vit_Receptor.Rec_Apellido_Materno ";
$sSqlWrk .= "FROM Vit_Receptor ";												
$sSqlWrk .= "WHERE Rec_Nombre <> '' ";
if(@$Mun_ID != ""){
$sSqlWrk .= "AND Vit_Receptor.Mun_ID = '".@$Mun_ID."' ";
}
$sSqlWrk .= "GROUP BY Rec_RFC ";
$sSqlWrk .= "ORDER BY Rec_Nombre, Rec_Apellido_Paterno, Rec_Apellido_Materno Asc";
#echo "<br />".$sSqlWrk;
$rswrk = phpmkr_query($sSqlWrk,$conn) or die("Failed to execute query" . phpmkr_error($conn) . ' SQL:' . $sSqlWrk);
if ($rswrk) {
	$rowcntwrk = 0;
	while ($datawrk = phpmkr_fetch_array($rswrk)) {
		$x_Rec_RFCList .= "<option value=\"" . htmlspecialchars($datawrk["Rec_RFC"]) . "\"";
		$x_Rec_RFCList .= ">" . $datawrk["Rec_Nombre"] . " " . $datawrk["Rec_Apellido_Paterno"] . " " . $datawrk["Rec_Apellido_Materno"] . "*</option>";
		$rowcntwrk++;
	}
}
@phpmkr_free_result($rswrk);
$x_Rec_RFCList .= "</select>";
echo $x_Rec_RFCList;
?>