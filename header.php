<html>
<head>
	<title>Vita Insumos</title>
	<style type="text/css">
	<!--
 	INPUT, TEXTAREA, SELECT {font-family: Verdana; font-size: xx-small;}
	.phpmaker {font-family: Verdana; font-size: xx-small;}
	-->
	</style>
<meta name="generator" content="PHPMaker v3.1.0.2" />
</head>
<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0">
<?php if (@$sExport == "") { ?>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="10">
<tr>
	<td>&nbsp;</td>
	<td><span class="phpmaker"><b>Vita Insumos</b></span></td>
</tr>
<tr>
	<!-- left column -->
	<td width="20%" height="100%" valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
<?php if (@$_SESSION["project1_status"] == "login") { ?>
			<tr><td><span class="phpmaker"><a href="vit_municipioslist.php?cmd=resetall">Municipios</a></span></td></tr>
<?php } ?>
<?php if (@$_SESSION["project1_status"] == "login") { ?>
			<tr><td><span class="phpmaker"><a href="vit_usuarioslist.php?cmd=resetall">Usuarios</a></span></td></tr>
<?php } ?>
<?php if (@$_SESSION["project1_status"] == "login") { ?>
			<tr><td><span class="phpmaker"><a href="logout.php">Logout</a></span></td></tr>
<?php } elseif (substr(@$_SERVER["SCRIPT_NAME"], 0 - strlen("login.php")) <> "login.php") { ?>
			<tr><td><span class="phpmaker"><a href="login.php">Login</a></span></td></tr>
<?php } ?>
		</table>
	</td>
	<!-- right column -->
	<td width="80%" valign="top">
<?php } ?>
