<?php
include("api_functions.php");

 if(isset($_GET['PHP_AUTH_USER']) && isset($_GET['PHP_AUTH_PW']) && !isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])){
     $_SERVER['PHP_AUTH_USER'] = $_GET['PHP_AUTH_USER'];
     $_SERVER['PHP_AUTH_PW'] = $_GET['PHP_AUTH_PW'];
 }

//$quantity = 0;
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
            $sql = "select id,name,lastname,email,phone,address from customers where email='$email' and password='$password'";
            $result = mysqli_query($db, $sql) or die("Error in Selecting " . mysqli_error($connection));
            //create an array
            // $emparray = array();
            // while($row =mysqli_fetch_assoc($result))
            // {
            //     $emparray[] = $row;
            // }
            $row    = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $custid = $row['id'];
            //echo $custid;
            $count  = mysqli_num_rows($result);
            $order_id_array = array();
            if ($count == 1) {
                $customer_id = $row['id'];
                $orders_usertablename = $customer_id.'_orders';
                $orderitems_usertablename = $customer_id.'_order_items';
                // $data1    = json_decode(file_get_contents('php://input'), true);
                $data1 = json_decode(file_get_contents('php://input'), true);

                // echo $data;
                $value    = json_encode($data1);
                //header("Content-type:application/json");
                // echo $value;
                $total    = 0;
                $quantity = 0;
                $price = 0;
                foreach ($data1 as $key => $value){
                    //echo $value['product_id'].'-pi';
                    $id = $value['product_id'];
                                $sql    = "Select price from products WHERE id=$id";
                                $result = mysqli_query($db, $sql);
                                $row    = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                $count  = mysqli_num_rows($result);
                                $price  = $row['price'];

                    $quantity = $value['quantity'];
                    $total = $total + ($price * $quantity);

                }
                    // foreach ($value as $key1 => $value1) {
                    //     $count = 0;
                    //     if ($key1 == "product_id") {
                    //         $sql    = "Select price from products WHERE id=$value1";
                    //         $result = mysqli_query($db, $sql);
                    //         $row    = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    //         $count  = mysqli_num_rows($result);
                    //         $price  = $row['price'];
                    //         //echo $quantity.'</br>';
                    //         echo $price.'</br>';
                    //     }elseif ($key1 == "quantity") {
                    //         $quantity =  $value1;
                    //         //echo $quantity.'</br>';
                    //         //echo $quantity.'</br>';
                    //     }
                    //     
                    // }               

                //echo 'total - '.$total;
                if($total != 0){
                $sql            = "INSERT INTO $orders_usertablename (`id`, `customer_id`, `total_price`, `created`, `modified`, `status`, `paymenttype`) VALUES (null,$custid,$total,CURRENT_DATE(), CURRENT_DATE(),1,'api offline payment')";
                //echo $sql;
                $result         = mysqli_query($db, $sql);
                $id             = mysqli_insert_id($db);
                $order_id       = $id;
                $order_id_array = array();
                }

                foreach ($data1 as $key => $value) {
                    $product_id = 0;
                    $quantity   = 0;
                    foreach ($value as $key => $value) {
                    // $price = 0;
                        //
                        if ($key == "product_id") {
                            $product_id = $value;
                            $sql        = "Select price from products WHERE id=$value";
                            $result     = mysqli_query($db, $sql);
                            $row        = mysqli_fetch_array($result, MYSQLI_ASSOC);
                            $price      = $row['price'];
                            //echo $quantity.'</br>';
                            //echo $price.'</br>';
                        }
                        if ($key == "quantity") {
                            $quantity = $quantity + $value;
                            //echo $quantity.'</br>';
                            //echo $quantity.'</br>';
                        }
                        
                        // $sql = "INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`) VALUES (NULL, $order_id, $product_id,$quantity)";
                        // //echo $sql;
                        // $result = mysqli_query($db,$sql);
                        // $id = mysqli_insert_id($db);
                        
                        
                        
                    }
                    // echo $product_id.'</br></br>';
                    // echo $quantity.'</br>';
                    
                    if($price != 0 && $quantity !=0){
                        $sql = "INSERT INTO $orderitems_usertablename (`id`, `order_id`, `product_id`, `quantity`) VALUES (NULL, $order_id, $product_id,$quantity)";
                        //echo $sql;
                        
                        $result = mysqli_query($db, $sql);
                        $id     = mysqli_insert_id($db);
                        $cID = '';
                        $cTotal = '';
                        if ($id) {
                            $cID = $cID.' ,'.$id;
                            $cTotal = $cTotal.' ,'.$id;
                            $order_id_array[] = array(
                                "Order ID" => $order_id,
                                "Order Item ID" => $id,
                                "Item Total Price" => bcadd($row['price'] * $quantity, 0, 2),
                                "Total Order Amount" => bcadd($total, 0, 2)
                            );
                        } else {
                            $order_id_array[] = "error";
                        }
                    }

                }
                
                
                if ($_GET['type'] == "json") {
                    header("Content-type:application/json");
                    // $order_id_array[] = array(
                    //     "Total Amount" => $total
                    // );
                    echo json_encode($order_id_array);
                } else {
                    header("Content-type:application/xml");
                    echo "<Orders>";
                        foreach ($order_id_array as $key => $value) {
                        //echo "<OrderItem>";
                        if(array_key_exists("Order ID",$value)){
                        foreach ($value as $key1 => $value1) {
                            echo '<' . str_replace(' ', '', $key1) . '>' . $value1 . '</' . str_replace(' ', '', $key1) . '>';
                        }
                    }
                        //echo "</OrderItem>";
                        
                    }
                    
                    //echo "<TotalAmount>" . $total . "</TotalAmount>";
                    
                    echo "</Orders>";
                    
                }
            } else {
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
        } else {
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