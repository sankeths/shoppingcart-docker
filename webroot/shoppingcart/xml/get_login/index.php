<?php
			
          header("location: ../../api/get_login.php?type=xml".$_SERVER['QUERY_STRING'].'&PHP_AUTH_USER='.$_SERVER['PHP_AUTH_USER'].'&PHP_AUTH_PW='.$_SERVER['PHP_AUTH_PW']);
 ?>