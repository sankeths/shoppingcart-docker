<?php
function callAPI($method, $url, $data, $auth)
{
    $curl = curl_init();
    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'APIKEY: 111111111111111111111',
        'Content-Type: application/json'
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, $auth);

    // EXECUTE:
    $result = curl_exec($curl);
    if (!$result) {
        die("Service Unavailable");
    }
    curl_close($curl);
    return $result;
}
// initialize shopping cart class
include('Cart.php');
include('session.php');
$cart                     = new Cart;
$customer_id              = $_SESSION['cus_id'];
$orders_usertablename     = $customer_id . '_orders';
$orderitems_usertablename = $customer_id . '_order_items';

            $sql    = "select id,name,lastname,password,email,phone,address from customers where id=$customer_id ";
            $result = mysqli_query($db, $sql);
            $row    = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $username = $row['email'];
            $password = $row['password'];
            $auth = $username.':'.$password;
            // echo $_POST['PHP_AUTH_USER'];
            // echo $_POST['PHP_AUTH_PW'];

if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 'addToCart' && !empty($_REQUEST['id'])) {
        $productID = $_REQUEST['id'];
        // get product details
        $query     = $db->query("SELECT * FROM products WHERE id = " . $productID);
        $row       = $query->fetch_assoc();
        $itemData  = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'qty' => 1
        );
        
        $insertItem  = $cart->insert($itemData);
        $redirectLoc = $insertItem ? 'viewCart.php' : 'index.php';
        header("Location: " . $redirectLoc);
    } elseif ($_REQUEST['action'] == 'updateCartItem' && !empty($_REQUEST['id'])) {
        $itemData   = array(
            'rowid' => $_REQUEST['id'],
            'qty' => $_REQUEST['qty']
        );
        $updateItem = $cart->update($itemData);
        echo $updateItem ? 'ok' : 'err';
        die;
    } elseif ($_REQUEST['action'] == 'removeCartItem' && !empty($_REQUEST['id'])) {
        $deleteItem = $cart->remove($_REQUEST['id']);
        header("Location: viewCart.php");
    } elseif ($_REQUEST['action'] == 'placeOrder' && $cart->total_items() > 0 && !empty($_SESSION['sessCustomerID'])) {
        // insert order details into database
        $insertOrder = $db->query("INSERT INTO $orders_usertablename (customer_id, total_price, created, modified,paymenttype) VALUES ('" . $_SESSION['sessCustomerID'] . "', '" . $cart->total() . "', '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "','offline pay')");
        
        if ($insertOrder) {
            $orderID   = $db->insert_id;
            $sql       = '';
            // get cart items
            $cartItems = $cart->contents();
            foreach ($cartItems as $item) {
                $sql .= "INSERT INTO $orderitems_usertablename (order_id, product_id, quantity) VALUES ('" . $orderID . "', '" . $item['id'] . "', '" . $item['qty'] . "');";
            }
            // insert order items into database
            $insertOrderItems = $db->multi_query($sql);
            
            if ($insertOrderItems) {
                $cart->destroy();
                header("Location: orderSuccess.php?id=$orderID");
            } else {
                header("Location: checkout.php");
            }
        }
    } elseif ($_REQUEST['action'] == 'placeOrdercc') {
        // insert order details into database      
        // if($_POST["cc_number"] == '1234123412341234'){
        // foreach ($_POST as $key => $value)
        //     echo $key . ' -> ' . $value . '<br>';
        // echo $_SESSION['sessCustomerID'];
        $value = json_encode($_POST);
        //echo $value."</br>";
        //var_dump($_SERVER);
        
        $customer_id    = $_SESSION['sessCustomerID'];
        //echo $customer_id;
        $sql            = "Select inuseservice,virtualserviceurl from customers WHERE id=$customer_id";
        $result         = mysqli_query($db, $sql);
        $row            = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $currentlyusing = $row['virtualserviceurl'];
        //echo $currentlyusing;
        $inuseservice   = $row['inuseservice'];
        //echo $inuseservice;
        $url            = "";
        
        if ($inuseservice == 0) {
            $url = "http://";
        } elseif ($inuseservice == 1) {
            $sql    = "Select url from config";
            $result = mysqli_query($db, $sql);
            $row    = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $server = $row['url'];
            // $url = $server."service_creditcard.php";
            $url    = str_replace('checkout.php', 'service_creditcard.php', $_SERVER['HTTP_REFERER']);
            
            //echo $url;
        } elseif ($inuseservice == 2) {
            $url = $currentlyusing;
            //echo $url;
        } else {
            // $sql = "Select url from config";
            // $result = mysqli_query($db,$sql);
            // $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            // $server = $row['url'];
            // $url = $server."service_creditcard.php";
            $url = str_replace('checkout.php', 'service_creditcard.php', $_SERVER['HTTP_REFERER']);
            
        }
        
        //var_dump($_SERVER);
        
        
        // $options = array(
        //   'http' => array(
        //     'method'  => 'POST',
        //     'content' => $value,
        //     'header'=>  "Content-Type: application/json\r\n" .
        //                 "Accept: application/json\r\n"
        //     )
        // );
        
        // $context  = stream_context_create( $options );
        // // error_reporting(0);
        
        // if(!file_get_contents( $url, false, $context )){
        //       die("Service Unavailable");
        // }else{
        //     $result = file_get_contents( $url, false, $context );
        // }
        
        // $data_array =  array(
        //       "customer"        => $user['User']['customer_id'],
        //       "payment"         => array(
        //             "number"         => $this->request->data['account'],
        //             "routing"        => $this->request->data['routing'],
        //             "method"         => $this->request->data['method']
        //       ),
        // );
        //echo $url . "</br>";
        $make_call = callAPI('POST', $url, $value,$auth);
        $result    = json_decode($make_call, true);
        //echo 'result -'.json_encode($result);
        // foreach ($result as $key => $value)
        //     echo $key . ' -> ' . $value . '<br>';
        // $errors   = $result['response']['errors'];
        // $data     = $result['response']['data'][0];
        
        
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        $response = $result;
        // echo $response;
        // foreach ($result as $key => $value)
        //    echo $key . ' -> ' . $value . '<br>';
        //$response= json_decode($make_call, true);
        //$response = json_encode($response);
        //echo json_encode($result);
        //          foreach ($response as $key => $value)
        // echo $key . ' -> ' . $value . '<br>';
        $Status = $response['Status'];
        //echo $Status;
        if ($response["Status Code"] == 100) {
            $insertOrder = $db->query("INSERT INTO $orders_usertablename (customer_id, total_price, created, modified,paymenttype) VALUES ('" . $_SESSION['sessCustomerID'] . "', '" . $cart->total() . "', '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "','card')");
            
            if ($insertOrder) {
                $orderID   = $db->insert_id;
                $sql       = '';
                // get cart items
                $cartItems = $cart->contents();
                foreach ($cartItems as $item) {
                    $sql .= "INSERT INTO $orderitems_usertablename (order_id, product_id, quantity) VALUES ('" . $orderID . "', '" . $item['id'] . "', '" . $item['qty'] . "');";
                }
                // insert order items into database
                $insertOrderItems = $db->multi_query($sql);
                
                if ($insertOrderItems) {
                    $cart->destroy();
                    header("Location: orderSuccess.php?id=$orderID&Status=$Status");
                } else {
                    header("Location: checkout.php");
                }
            }
        } else
            //echo "fail";
        header("Location: orderFail.php?id=$orderID&Status=$Status");
        //echo "ok";
        // $insertOrder = $db->query("INSERT INTO orders (customer_id, total_price, created, modified) VALUES ('" . $_SESSION['sessCustomerID'] . "', '" . $cart->total() . "', '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "')");
        
        // if ($insertOrder) {
        //     $orderID   = $db->insert_id;
        //     $sql       = '';
        //     // get cart items
        //     $cartItems = $cart->contents();
        //     foreach ($cartItems as $item) {
        //         $sql .= "INSERT INTO order_items (order_id, product_id, quantity) VALUES ('" . $orderID . "', '" . $item['id'] . "', '" . $item['qty'] . "');";
        //     }
        //     // insert order items into database
        //     $insertOrderItems = $db->multi_query($sql);
        
        //     if ($insertOrderItems) {
        //         $cart->destroy();
        //         echo $orderID;
        //     }
        //     echo 'order -' . $orderID; // else {
        //     header("Location: checkout.php");
        // }
        // }          
        // }else{
        //     header("Content-type:application/json");
        //     $datastring=
        //     array(
        //     "Status"=> "Credit card failure",
        //     );
        //     $value=json_encode($datastring);
        //     echo $value;
        // }
        
    } else {
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
}

?>
