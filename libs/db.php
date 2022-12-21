<?php
function phpmkr_db_connect($HOST,$USER,$PASS, $DB, $PORT)
{
	/*$conn = mysqli_connect($HOST, $USER, $PASS, $DB, $PORT);
	return $conn;*/
	$conn = new mysqli('34.94.22.174','vitainsumos','vita123.','Vitainsumos');
	if ($conn->connect_errno) {
    printf("Connect failed: %s\n", $conn->connect_error);
    exit();
}
	/*if (mysqli_connect_errno()) {
		$conn->null;
	}*/		  
	return $conn;
}
function phpmkr_db_close($conn)
{
	//mysqli_close($conn);
	 $conn ->close();
}
function phpmkr_query($strsql,$conn)
{
	/*$rs = mysqli_query($conn, $strsql);
	return $rs;*/
	$rs = $conn->query($strsql);
	return $rs;
}
function phpmkr_num_rows($rs)
{
	#return @mysqli_num_rows($rs);	
	return $rs->num_rows;
}
function phpmkr_fetch_array($rs)
{
	#return mysqli_fetch_array($rs);
	return $rs->fetch_array(MYSQLI_ASSOC);
}
function phpmkr_free_result($rs)
{
	#@mysqli_free_result($rs);
	$rs->free();
}
function phpmkr_data_seek($rs,$cnt)
{
	#@mysqli_data_seek($rs, $cnt);
	$rs->data_seek($cnt);
}
function phpmkr_error($conn)
{
	#return mysqli_error($conn);
	//$mysqli->error
	return $conn->error;
}
?>
<?php
define("HOST", "34.94.22.174");
define("PORT", '');
define("USER", "vitainsumos");
define("PASS", "vita123.");
define("DB", "Vitainsumos");
?>
