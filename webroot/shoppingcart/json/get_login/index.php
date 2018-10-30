<?php
header('Access-Control-Allow-Origin: *'); 
		// $divert="&";
		 // foreach ($_SERVER as $key => $value) {
		 //    $divert = $key."=".$value."   ";
		 //    echo $divert;
		 // }
		//$divert = http_build_query($_GET);

		//$_SERVER['SERVER_NAME'];
		// session_start();
  		// $_SESSION['email'] = $_SERVER['PHP_AUTH_USER'];
		// $_SESSION['password'] = $_SERVER['PHP_AUTH_PW'];
		//  foreach ($_SESSION as $key => $value) {
		//     $divert = $key."=".$value."   ";
		//     echo $divert;
		//  }
//echo php_sapi_name();
//echo ($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
//		var_dump($_SERVER['PHP_AUTH_USER']);

  header("location: ../../api/get_login.php?type=json".$_SERVER['QUERY_STRING'].'&PHP_AUTH_USER='.$_SERVER['PHP_AUTH_USER'].'&PHP_AUTH_PW='.$_SERVER['PHP_AUTH_PW']);
 ?>