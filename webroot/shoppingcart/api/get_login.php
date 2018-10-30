
<?php
header('Access-Control-Allow-Origin: *'); 
// session_start();
          // foreach ($_GET as $key => $value) {
          //   $divert = $key."=".$value."   ";
          //   echo $divert;
          // }

 if(isset($_GET['PHP_AUTH_USER']) && isset($_GET['PHP_AUTH_PW']) && !isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])){
     $_SERVER['PHP_AUTH_USER'] = $_GET['PHP_AUTH_USER'];
     $_SERVER['PHP_AUTH_PW'] = $_GET['PHP_AUTH_PW'];
 }


// echo $_SERVER['PHP_AUTH_USER'];
// echo $_SERVER['PHP_AUTH_PW'];




if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
    header("WWW-Authenticate: Basic realm=\"Please enter your username and password to proceed further\"");
    header("HTTP/1.0 401 Unauthorized");
    print "Oops! I dont think you are sending any credentials to login.\n";
    exit;
} else {
    if ($_SERVER['PHP_AUTH_USER'] && $_SERVER['PHP_AUTH_PW']) {
        if ($_SERVER['PHP_AUTH_USER'] != null && $_SERVER['PHP_AUTH_PW'] != null) {
            
            $email    = $_SERVER['PHP_AUTH_USER'];
            // echo $username;
            // echo $_SERVER['PHP_AUTH_PW'];
            $password = $_SERVER['PHP_AUTH_PW'];
            //open connection to mysql db
            include("../dbconfig.php");
            //fetch table rows from mysql db
            $sql = "select id,name,lastname,email,phone,address from customers where email='$email' and password='$password'";
            $result = mysqli_query($db, $sql) or die("Error in Selecting " . mysqli_error($connection));
            
            //create an array
            $emparray = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $emparray[] = $row;
            }
            $count = mysqli_num_rows($result);
            //echo $count;
            if ($count == 1) {
                // echo $_GET['hello'].'1';
                
                //header("Content-type:application/json");
                // echo json_encode($emparray[0]['id']);
                //echo json_encode($emparray);
                // echo $_SERVER['PHP_AUTH_USER'];
                //close the db connection
                if ($_GET['type'] == "json") {
                    header("Content-type:application/json");
                    echo json_encode($emparray);
                } else {
                    header("Content-type:application/xml");
                    echo "<User>";
                    foreach ($emparray as $key => $value) {
                        foreach ($value as $key1 => $value1) {
                            echo '<' . str_replace(' ', '', $key1) . '>' . $value1 . '</' . str_replace(' ', '', $key1) . '>';
                        }
                    }
                    echo "</User>";
                    
                }
                mysqli_close($db);
            } else {
                header("WWW-Authenticate: Basic realm=\"Please enter your username and password to proceed further\"");
                header("HTTP/1.0 401 Unauthorized");
                print "Oops! Authentication pls\n";
            }
        } else {
            header("WWW-Authenticate: Basic realm=\"Please enter your username and password to proceed further\"");
            header("HTTP/1.0 401 Unauthorized");
            print "Oops! Authentication pls\n";
            exit;
        }
        exit;
    } else {
        header("WWW-Authenticate: Basic realm=\"Please enter your username and password to proceed further\"");
        header("HTTP/1.0 401 Unauthorized");
        print "Oops! Authentication pls\n";
        exit;
    }
}
?>
<?php

?>