<?php include '../layouts/session.php'; ?>
<?php include "db.class.php"; ?>
<?php include "db_login.class.php"; ?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$error = 0;
if (@$_POST["Submit"] <> "") {
	$bValidPwd = false;
	// Setup variables
	$sUserId = @$_POST["Userid"];
	$sPassWd = @$_POST["Passwd"];
	if ((strtoupper("admin") == strtoupper($sUserId)) && (strtoupper("vita") == strtoupper($sPassWd))) {
		$_SESSION["project1_status_User"] = 'admin';
		$_SESSION["project1_status_Name"] = 'VitaInsumos';
		$_SESSION["project1_status_Perfil"] = 1;
		$_SESSION["project1_status_Municipio"] = '';
		$bValidPwd = true;
		$error = 1;
	}
	if (!($bValidPwd)) {		
			$conn = new LoginMethods();
			$resultUser = $conn->getPassUser($sUserId);
			if(!is_null($resultUser)){
				$rowUser = $resultUser->fetch_assoc();
				if (strtoupper($rowUser["Vit_Contrasena"]) == strtoupper($sPassWd)) {
					$_SESSION["project1_status_User"] = $rowUser["Vit_Usuario"];
					$_SESSION["project1_status_Name"] = $rowUser["Vit_Nombre"];
					$_SESSION["project1_status_Perfil"] = $rowUser["Perfil_ID"];
					$_SESSION["project1_status_Municipio"] = $rowUser["Mun_ID"];
					$bValidPwd = true;
					$error = 1;
				}
			}
	}
	if ($bValidPwd) {
		// Write cookies
		if (@$_POST["rememberme"] <> "") {
			setCookie("project1_userid", $sUserId, time()+365*24*60*60); // change cookie expiry time here
		}
		$_SESSION["project1_status"] = "login";
		ob_end_clean();
		//header("Location: index.php");
		//exit();
	}
	echo $error;
}
?>