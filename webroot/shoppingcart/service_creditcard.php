<?php
include("dbconfig.php");
if (isset($_POST['PHP_AUTH_USER']) && isset($_POST['PHP_AUTH_PW']) && !isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
    $_SERVER['PHP_AUTH_USER'] = $_POST['PHP_AUTH_USER'];
    $_SERVER['PHP_AUTH_PW']   = $_POST['PHP_AUTH_PW'];
}

if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
    header("WWW-Authenticate: Basic realm=\"Please enter your username and password to proceed further\"");
    header("HTTP/1.0 401 Unauthorized");
    print "Please enter your username and password to proceed further\n";
    exit;
} else {
    if ($_SERVER['PHP_AUTH_USER'] && $_SERVER['PHP_AUTH_PW']) {
        if ($_SERVER['PHP_AUTH_USER'] != null && $_SERVER['PHP_AUTH_PW'] != null) {
            
            $email    = $_SERVER['PHP_AUTH_USER'];
            // echo $username;
            // echo $_SERVER['PHP_AUTH_PW'];
            $password = $_SERVER['PHP_AUTH_PW'];
            //open connection to mysql db
            //include("../dbconfig.php");
            //fetch table rows from mysql db
            $sql    = "select id,name,lastname,email,phone,address from customers where email='$email' and password='$password'";
            $result = mysqli_query($db, $sql);
            $row    = mysqli_fetch_array($result, MYSQLI_ASSOC);
            
            $count = mysqli_num_rows($result);
            if ($count == 1) {
                $customer_id = $row['id'];
                $orders_usertablename = $customer_id.'_orders';
                $orderitems_usertablename = $customer_id.'_order_items';
                $data = json_decode(file_get_contents('php://input'), true);
                header("Content-type:application/json");
                $value = json_encode($data);
                $value = json_decode($value, true);
                //echo $value["cc_money"];
                if ($value["cc_number"][0] == "1") {
                    // echo $value["id"];
                    $isTouch = isset($value["id"]);
                    //echo isset($isTouch);
                    if (isset($value["id"])) {
                        $orderid     = $value["id"];
                        $sql         = "Select * from $orders_usertablename WHERE id=$orderid";
                        $result      = mysqli_query($db, $sql);
                        $row         = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        $paymenttype = $row['paymenttype'];
                        $totalprice  = $row['total_price'];
                        
                        if ($paymenttype != "card" && $paymenttype != "Offline payment by card") {
                            if ($totalprice == $value["cc_money"]) {
                                $sql    = "UPDATE $orders_usertablename SET paymenttype='Offline payment by card' WHERE id=$orderid";
                                //echo $sql;
                                $result = mysqli_query($db, $sql);
                                $result = mysqli_affected_rows($db);
                                if ($result == 1) {
                                    $login_message = "Payment Success.";
                                } else {
                                    $login_message = "Already Paid or error.Please contact customer care.";
                                }
                                header("Content-type:application/json");
                                $datastring = array(
                                    "Status" => "Transaction Success",
                                    "Reason" => "Approved",
                                    "Status Code" => "100",
                                    "Order ID" => $orderid
                                );
                                $value      = json_encode($datastring);
                                echo $value;
                            } else {
                                header("Content-type:application/json");
                                $datastring = array(
                                    "Status" => "Transaction Declined",
                                    "Reason" => "In Correct Order Value.",
                                    "Status Code" => "97",
                                    "Order ID" => $orderid
                                );
                                $value      = json_encode($datastring);
                                echo $value;
                            }
                            
                        } else {
                            header("Content-type:application/json");
                            $datastring = array(
                                "Status" => "Transaction Declined",
                                "Reason" => "Paid Order.",
                                "Status Code" => "96",
                                "Order ID" => $orderid
                            );
                            $value      = json_encode($datastring);
                            echo $value;
                        }
                    } else
                        header("Content-type:application/json");
                    $datastring = array(
                        "Status" => "Transaction Success",
                        "Reason" => "Approved",
                        "Status Code" => "100"
                    );
                    $value      = json_encode($datastring);
                    echo $value;
                } elseif ($value["cc_number"][0] == "2") {
                    header("Content-type:application/json");
                    $datastring = array(
                        "Status" => "Transaction Failed",
                        "Reason" => "Card Blocked",
                        "Status Code" => "99"
                    );
                    $value      = json_encode($datastring);
                    echo $value;
                } else {
                    header("Content-type:application/json");
                    $datastring = array(
                        "Status" => "Transaction Error",
                        "Reason" => "Declined",
                        "Status Code" => "98"
                    );
                    $value      = json_encode($datastring);
                    echo $value;
                }
                
                
                
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
