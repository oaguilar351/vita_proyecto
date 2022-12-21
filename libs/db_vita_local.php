<?php
function phpmkr_db_connect($HOST,$USER,$PASS, $DB, $PORT)
{
	$conn = mysqli_connect($HOST, $USER, $PASS, $DB, $PORT);
	return $conn;
}
function phpmkr_db_close($conn)
{
	mysqli_close($conn);
}
function phpmkr_query($strsql,$conn)
{
	$rs = mysqli_query($conn, $strsql);
	return $rs;
}
function phpmkr_num_rows($rs)
{
	return @mysqli_num_rows($rs);	
}
function phpmkr_fetch_array($rs)
{
	return mysqli_fetch_array($rs);
}
function phpmkr_free_result($rs)
{
	@mysqli_free_result($rs);
}
function phpmkr_data_seek($rs,$cnt)
{
	@mysqli_data_seek($rs, $cnt);
}
function phpmkr_error($conn)
{
	return mysqli_error();
}
?>
<?php
define("HOST", "localhost");
define("PORT", 3306);
define("USER", "root");
define("PASS", "");
define("DB", "vita");
?>
