<?php
    class DatabaseConnection
    {
        public function getConnection()
        {
          $db = new mysqli('34.94.22.174','vitainsumos','vita123.','Vitainsumos');
		      if (mysqli_connect_errno()) {
		  		  $db = null;
		      }		  
          return $db;
        }
    }
?>
