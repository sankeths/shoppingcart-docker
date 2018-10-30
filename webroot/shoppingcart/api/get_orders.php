<?php
 if(isset($_GET['PHP_AUTH_USER']) && isset($_GET['PHP_AUTH_PW']) && !isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])){
     $_SERVER['PHP_AUTH_USER'] = $_GET['PHP_AUTH_USER'];
     $_SERVER['PHP_AUTH_PW'] = $_GET['PHP_AUTH_PW'];
 }

if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
    header("WWW-Authenticate: Basic realm=\"Please enter your username and password to proceed further\"");
    header("HTTP/1.0 401 Unauthorized");
    print "Oops! It require login to proceed further. Please enter your login detail\n";
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
            $sql    = "select id,name,lastname,email,phone,address from customers where email='$email' and password='$password'";
            $result = mysqli_query($db, $sql);
            $row    = mysqli_fetch_array($result, MYSQLI_ASSOC);
            
            $count = mysqli_num_rows($result);
            if ($count == 1) {
                //$rowdata =mysqli_fetch_assoc($result);
                $customer_id = $row['id'];
                $orders_usertablename = $customer_id.'_orders';
                $orderitems_usertablename = $customer_id.'_order_items';

                $sql0 = "SELECT id FROM $orders_usertablename where customer_id = " . $row['id'] . " ORDER BY id DESC";
                $result0 = mysqli_query($db, $sql0) or die("Error in Selecting " . mysqli_error($connection));
                $emparray0     = array();
                $superemparray = array();
                while ($row0 = mysqli_fetch_assoc($result0)) {
                    $emparray0[] = $row0;
                    //echo json_encode($row0['id']);
                    $orderid     = $row0['id'];
                    //echo $orderid;
                    
                    $total = 0;
                    $sql   = "SELECT o.id,o.customer_id,c.name,o.total_price,o.status,oi.order_id,oi.quantity,p.price,p.name,p.image,o.paymenttype
                                    FROM $orders_usertablename AS o
                                      INNER JOIN $orderitems_usertablename AS oi ON o.id=oi.order_id
                                      INNER JOIN products AS p ON oi.product_id = p.id
                                      INNER JOIN customers AS c ON o.customer_id = c.id
                                         where o.id = $orderid";
                    $result = mysqli_query($db, $sql) or die("Error in Selecting " . mysqli_error($connection));
                    $emparray = array();
                    while ($row = mysqli_fetch_assoc($result)) {
                        $emparray[] = $row;
                    }
                    $superemparray[] = $emparray;
                }
                //header("Content-type:application/json");
                if (sizeof($superemparray) > 0) {
                    //echo json_encode($superemparray);
                    if ($_GET['type'] == "json") {
                    header("Content-type:application/json");
                    echo json_encode($superemparray);
                } else {
                    header("Content-type:application/xml");
                    echo "<Orders>";
                    foreach ($superemparray as $key => $value) {
                        echo "<Order>";
                        foreach ($value as $key1 => $value1) {
                            echo "<Items>";
                                foreach ($value1 as $key2 => $value2) {
                                    echo '<' . str_replace(' ', '', $key2) . '>' . $value2 . '</' . str_replace(' ', '', $key2) . '>';
                                }
                            echo "</Items>";
                        }
                        echo "</Order>";
                    }
                    echo "</Orders>";
                    
                }


                } else {
                    $error_array[] = array(
                        "Error" => "No orders found"
                    );
                    //echo json_encode($error_array);
                    if ($_GET['type'] == "json") {
                    header("Content-type:application/json");
                    // if(!empty($error_array)){
                    echo json_encode($error_array);
                    // }else{

                    // }
                } else {
                    header("Content-type:application/xml");
                    echo "<User>";
                    foreach ($error_array as $key => $value) {
                        foreach ($value as $key1 => $value1) {
                            echo '<' . str_replace(' ', '', $key1) . '>' . $value1 . '</' . str_replace(' ', '', $key1) . '>';
                        }
                    }
                    echo "</User>";
                    
                }





                }
                
                
                
                
                // $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 100";
                // $result = mysqli_query($db, $sql) or die("Error in Selecting " . mysqli_error($connection));
                // $emparray = array();
                // while($row =mysqli_fetch_assoc($result))
                // {
                //     $emparray[] = $row;
                // }
                // header("Content-type:application/json");
                // echo json_encode($emparray);
            } else {
                header("WWW-Authenticate: Basic realm=\"Please enter your username and password to proceed further\"");
                header("HTTP/1.0 401 Unauthorized");
                print "Oops! Authentication pls\n";
            }
            //echo $count;
            // header("Content-type:application/json");
            // echo json_encode($emparray);
            // echo $_SERVER['PHP_AUTH_USER'];
            //close the db connection
            mysqli_close($db);
        } else {
            header("WWW-Authenticate: Basic realm=\"Please enter your username and password to proceed further\"");
            header("HTTP/1.0 401 Unauthorized");
            print "Oops! Authentication pls\n";
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
