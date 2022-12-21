<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Pragma: no-cache"); // HTTP/1.0 
?>
<?php include ("libs/db.class.php") ?>
<?php include ("libs/db_login.class.php") ?>
<?php //include ("phpmkrfn.php") ?>
<?php

// User levels
define("ewAllowAdd", 1, '');
define("ewAllowDelete", 2, '');
define("ewAllowEdit", 4, '');
define("ewAllowView", 8, '');
define("ewAllowList", 8, '');
define("ewAllowReport", 8, '');
define("ewAllowSearch", 8, '');																														
define("ewAllowAdmin", 16, '');	
if (@$_POST["submit"] <> "") {
	$bValidPwd = false;

	// Setup variables
	$sUserId = @$_POST["userid"];
	$sPassWd = @$_POST["passwd"];
	if ((strtoupper("admin") == strtoupper($sUserId)) && (strtoupper("vita") == strtoupper($sPassWd))) {
		$bValidPwd = true;
	}
	if (!($bValidPwd)) {
			/*$conn = phpmkr_db_connect(HOST, USER, PASS, DB, PORT);
			$sUserId = (!(function_exists("get_magic_quotes_gpc") && (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()))) ? addslashes($sUserId) : $sUserId;
			$sSql = "SELECT * FROM `vit_usuarios`";
			$sSql .= " WHERE `Vit_Usuario` = '" . $sUserId . "'";
			$rs = phpmkr_query($sSql,$conn) or die("Failed to execute query: " . phpmkr_error($conn) . '<br>SQL: ' . $sSql);
			if (phpmkr_num_rows($rs) > 0) {
			$row = phpmkr_fetch_array($rs);
				if (strtoupper($row["Vit_Contrasena"]) == strtoupper($sPassWd)) {
					$_SESSION["project1_status_User"] = $row["Vit_Usuario"];
					$bValidPwd = true;
				}
			}*/
			$conn = new LoginMethods();
			$resultUser = $conn->getPassUser($sUserId);
			if(!is_null($resultUser)){
				$rowUser = $resultUser->fetch_assoc();
				if (strtoupper($rowUser["Vit_Contrasena"]) == strtoupper($sPassWd)) {
					$_SESSION["project1_status_User"] = $rowUser["Vit_Usuario"];
					$bValidPwd = true;
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
		header("Location: index.php");
		exit();
	} else {
		$_SESSION["ewmsg"] = "Usuario o Contraseña incorrecto";
	}
}
?>
<?php include ("header.php") ?>
<script type="text/javascript" src="js/ew.js"></script>
<script type="text/javascript">
<!--
function EW_checkMyForm(EW_this) {
	if (!EW_hasValue(EW_this.userid, "TEXT" )) {
		if  (!EW_onError(EW_this, EW_this.userid, "TEXT", "Please enter user ID"))
			return false;
	}
	if (!EW_hasValue(EW_this.passwd, "PASSWORD" )) {
		if (!EW_onError(EW_this, EW_this.passwd, "PASSWORD", "Please enter password"))
			return false;
	}
	return true;
}

//-->
</script>
<p><span class="phpmaker">Login Page</span></p>
<?php
if (@$_SESSION["ewmsg"] <> "") {
?>
<p><span class="phpmaker" style="color: Red;"><?php echo $_SESSION["ewmsg"]; ?></span></p>
<?php
	$_SESSION["ewmsg"] = ""; // Clear message
}
?>
<form action="login.php" method="post" onSubmit="return EW_checkMyForm(this);">
<table border="0" cellspacing="0" cellpadding="4">
	<tr>
		<td><span class="phpmaker">User Name</span></td>
		<td><span class="phpmaker"><input type="text" name="userid" size="20" value="<?php echo @$_COOKIE["project1_userid"]; ?>"></span></td>
	</tr>
	<tr>
		<td><span class="phpmaker">Password</span></td>
		<td><span class="phpmaker"><input type="password" name="passwd" size="20"></span></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><span class="phpmaker"><input type="checkbox" name="rememberme" value="true">Remember me</span></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><span class="phpmaker"><input type="submit" name="submit" value="Login"></span></td>
	</tr>
</table>
</form>
<br>
<p><span class="phpmaker">
</span></p>
<?php include ("footer.php") ?>
