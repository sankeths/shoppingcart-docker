<?php
if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
header("WWW-Authenticate: Basic realm=\"Please enter your username and password to proceed further\"");
header("HTTP/1.0 401 Unauthorized");
print "Oops! It require login to proceed further. Please enter your login detail\n";
exit;
} else {
if ($_SERVER['PHP_AUTH_USER'] && $_SERVER['PHP_AUTH_PW']) {
    if ($_SERVER['PHP_AUTH_USER']!= null && $_SERVER['PHP_AUTH_PW']!=null) {

    $email = $_SERVER['PHP_AUTH_USER'];
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
        while($row =mysqli_fetch_assoc($result))
        {
            $emparray[] = $row;
        }
        $count = mysqli_num_rows($result);
        if($count == 1){





                
        }else{
        header("WWW-Authenticate: Basic realm=\"Please enter your username and password to proceed further\"");
        header("HTTP/1.0 401 Unauthorized");
        print "Oops! It require login to proceed further. Please enter your login detail\n";
        }
        //echo $count;
        // header("Content-type:application/json");
        // echo json_encode($emparray);
        // echo $_SERVER['PHP_AUTH_USER'];
        //close the db connection
        mysqli_close($db);
    }
    else{
        header("WWW-Authenticate: Basic realm=\"Please enter your username and password to proceed further\"");
        header("HTTP/1.0 401 Unauthorized");
        print "Oops! It require login to proceed further. Please enter your login detail\n";
    }
exit;
} else {
header("WWW-Authenticate: Basic realm=\"Please enter your username and password to proceed further\"");
header("HTTP/1.0 401 Unauthorized");
print "Oops! It require login to proceed further. Please enter your login detail\n";
exit;
}
}
?>
<?php

?>

