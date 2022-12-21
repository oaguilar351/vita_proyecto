<?php
function phpmkr_db_connect_sat($HOST,$USER,$PASS, $DB, $PORT)
{
	/*$conn = mysqli_connect($HOST, $USER, $PASS, $DB, $PORT);
	return $conn;*/
	$conn_sat = new mysqli('34.94.22.174','root','RockFord28','sat_catalogos');
	if ($conn_sat->connect_errno) {
    printf("Connect failed: %s\n", $conn_sat->connect_error);
    exit();
}
	/*if (mysqli_connect_errno()) {
		$conn_sat->null;
	}*/		  
	return $conn_sat;
}
function phpmkr_db_close_sat($conn_sat)
{
	//mysqli_close($conn);
	 $conn_sat ->close();
}
function phpmkr_query_sat($strsql,$conn_sat)
{
	/*$rs = mysqli_query($conn, $strsql);
	return $rs;*/
	$rs = $conn_sat->query($strsql);
	return $rs;
}
function phpmkr_num_rows_sat($rs)
{
	#return @mysqli_num_rows($rs);	
	return $rs->num_rows;
}
function phpmkr_fetch_array_sat($rs)
{
	#return mysqli_fetch_array($rs);
	return $rs->fetch_array(MYSQLI_ASSOC);
}
function phpmkr_free_result_sat($rs)
{
	#@mysqli_free_result($rs);
	$rs->free();
}
function phpmkr_data_seek_sat($rs,$cnt)
{
	#@mysqli_data_seek($rs, $cnt);
	$rs->data_seek($cnt);
}
function phpmkr_error_sat($conn_sat)
{
	#return mysqli_error($conn);
	//$mysqli->error
	return $conn_sat->error;
}
?>
