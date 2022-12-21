<?php
    class DatabaseConnectionCatalogos{

        public function getConnection(){
          $db = null;
          //$db = new mysqli('127.0.0.1','root','RockFord28','eztrack_contable');
          //$db = new mysqli('35.197.49.80','root','RockFord28','eztrack_contable');
          //$db = new mysqli('127.0.0.1','root','12345','eztrack_contable');
          // $db=new mysqli('35.199.180.126','desarrollo','jaime123.','eztrack_contable');
          $db = new mysqli('34.94.22.174','root','RockFord28','sat_catalogos');
		  if (mysqli_connect_errno()) {
		  		$db = null;
				//printf("<br />Connect failed: %s\n", mysqli_connect_error());
				//exit();
		  }		  
          return $db;
        }
    }

?>
