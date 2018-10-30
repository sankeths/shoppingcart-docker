<?php
include('session.php');
//echo $_SESSION['sessCustomerID'];
// include 'dbConfig.php';
$customer_id = $_SESSION['cus_id'];
$orders_usertablename = $customer_id.'_orders';
$orderitems_usertablename = $customer_id.'_order_items';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Awesome Shopping Store - Settings</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <style>
    .container{padding: 50px;}
    .cart-link{width: 100%;text-align: right;display: block;font-size: 22px;}
    </style>
</head>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["virtserverurl"])) {
    $customer_id   = $_SESSION['cus_id'];
    //echo "signup";
    $virtserverurl = mysqli_real_escape_string($db, $_POST['virtserverurl']);
    // $sql = "INSERT INTO customers (virtualserviceurl) VALUES ('$virtserverurl') where id=".$_SESSION['cus_id'];
    $sql           = "UPDATE customers SET virtualserviceurl='$virtserverurl',inuseservice=2 WHERE id=$customer_id";
    $inuseservice  = 2;
    //echo $sql;
    $result = mysqli_query($db, $sql);
    $result = mysqli_affected_rows($db);
    if ($result == 1) {
        $login_message = "Success!";
    } else {
        $login_message = "Something went wrong.";
    }
    echo $login_message;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["inuseservice"])) {
    $customer_id  = $_SESSION['cus_id'];
    $inuseservice = $_POST["inuseservice"];
    //echo "signup";
    // $virtserverurl = mysqli_real_escape_string($db,$_POST['virtserverurl']);
    // $sql = "INSERT INTO customers (virtualserviceurl) VALUES ('$virtserverurl') where id=".$_SESSION['cus_id'];
    $sql          = "UPDATE customers SET inuseservice=$inuseservice WHERE id=$customer_id";
    //echo $sql;
    $result       = mysqli_query($db, $sql);
    $result       = mysqli_affected_rows($db);
    if ($result == 1) {
        $login_message = "Success!";
    } else {
        $login_message = "Something went wrong.";
    }
    //echo $login_message;
}


?>
 <?php
$customer_id    = $_SESSION['cus_id'];
$sql            = "Select inuseservice,virtualserviceurl from customers WHERE id=$customer_id";
$result         = mysqli_query($db, $sql);
$row            = mysqli_fetch_array($result, MYSQLI_ASSOC);
$currentlyusing = $row['virtualserviceurl'];
$inuseservice   = $row['inuseservice'];

?>


 <?php
$labelmessage = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cleanupordertable"])) {
    $sql = "Delete from $orders_usertablename WHERE customer_id=$customer_id";
    if (mysqli_query($db, $sql)) {
        $labelmessage = "No more mess !";
    } else {
        $labelmessage = "Oops ! Something went wrong. You may need some advanced debugging.";
    }
}
?>
 <?php

//$labelmessage = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["populateorders"])) {
    $sql = "INSERT INTO $orders_usertablename (`id`, `customer_id`, `total_price`, `created`, `modified`, `status`, `paymenttype`) VALUES
(CONCAT($customer_id,1), $customer_id, 99.00, '2018-06-01 19:49:06', '2018-06-01 19:49:06', '1', 'card'),
(CONCAT($customer_id,2), $customer_id, 1198.00, '2018-06-01 19:49:14', '2018-06-01 19:49:14', '1', 'offline pay'),
(CONCAT($customer_id,3), $customer_id, 798.00, '2018-06-01 19:49:27', '2018-06-01 19:49:27', '1', 'card'),
(CONCAT($customer_id,4), $customer_id, 7996.00, '2018-06-01 19:49:42', '2018-06-01 19:49:42', '1', 'card'),
(CONCAT($customer_id,5), $customer_id, 9593.00, '2018-06-02 00:00:00', '2018-06-02 00:00:00', '1', 'api offline payment');";
//echo $sql;
                        $result = mysqli_query($db, $sql);
                        //echo $result;
                        $id     = mysqli_insert_id($db);
    if ($id) {
        //var_dump($id);
        $sql ="INSERT INTO $orderitems_usertablename (`id`, `order_id`, `product_id`, `quantity`) VALUES
                (CONCAT($customer_id,1), CONCAT($customer_id,1), 6, 1),
                (CONCAT($customer_id,2), CONCAT($customer_id,2), 4, 1),
                (CONCAT($customer_id,3), CONCAT($customer_id,2), 5, 1),
                (CONCAT($customer_id,4), CONCAT($customer_id,3), 3, 2),
                (CONCAT($customer_id,5), CONCAT($customer_id,4), 1, 2),
                (CONCAT($customer_id,6), CONCAT($customer_id,4), 2, 2),
                (CONCAT($customer_id,7), CONCAT($customer_id,5), 1, 2),
                (CONCAT($customer_id,8), CONCAT($customer_id,5), 2, 2),
                (CONCAT($customer_id,9), CONCAT($customer_id,5), 3, 1),
                (CONCAT($customer_id,10), CONCAT($customer_id,5), 4, 2);";
                //echo $sql;
        $result = mysqli_query($db, $sql);
        //echo $result;
        $new_id     = mysqli_insert_id($db);
        if($new_id){
            $labelmessage = "You are all set for the demo !";
        }else{
            $labelmessage = "Oops ! Something went wrong. You may need some advanced debugging.";
        }       
    } else {
        $labelmessage = "Oops ! Something went wrong. You may need some advanced debugging.";
    }
}


?>

<body>
<?php
include("header.php");
?>
<div class="container">
<div style="width: 80%;margin: 0 auto;">
<h2>User Settings for Service Virtualization</h2>
    <form action="" method="post">
    <div class="panel panel-primary" style="padding: 15px;">
    <div class="form-group">
        <h3 for="exampleFormControlInput1">Update & use a Virtual Service URL for card payment</h3>
        <input type="text" class="form-control" id="virtserverurl" name="virtserverurl" placeholder="Your last known service - <?php
echo $currentlyusing;
?>">
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
       </div>
    </form>

   <div class="panel panel-primary" style="padding: 15px;">

<form action="" method="post">
    <?php
if ($inuseservice == 0) {
?>
    <h3>Currently using -</br></h3>
    <div class="panel panel-primary" style="padding: 15px;"><b>Broken Service</b></div>
    <input type="radio" name="inuseservice" checked value=0>Broken Service<br>
    <input type="radio" name="inuseservice" value=1>Real Service<br>
    <input type="radio" name="inuseservice" value=2>Virtual Service<br>
    <?php
}
?>
   <?php
if ($inuseservice == 1) {
?>
   <h3>Currently using -</br></h3>
    <div class="panel panel-primary" style="padding: 15px;"><b>Real Service</b></div>
    <input type="radio" name="inuseservice" value=0>Broken Service<br>
    <input type="radio" name="inuseservice" checked value=1>Real Service<br>
    <input type="radio" name="inuseservice" value=2>Virtual Service<br>
    <?php
}
?>
   <?php
if ($inuseservice == 2) {
?>
   <h3>Currently using Virtual Service -</br></h3>
    <div class="panel panel-primary" style="padding: 15px;"><b><?php
    echo $currentlyusing;
?></b></div>
    <input type="radio" name="inuseservice" value=0>Broken Service<br>
    <input type="radio" name="inuseservice" value=1>Real Service<br>
    <input type="radio" name="inuseservice" checked value=2>Virtual Service<br>
    <?php
}
?>

    </br>
<button type="submit" class="btn btn-primary">Update</button>
</div>
</form>
 <h2>User Settings for Demo</h2>
   
<div class="row" style="width:100%;margin: 0 auto;">
    <div class="col-sm-14 text-center" Style="justify-content: center;">
    <form action="" method="post">
         <input type="hidden" name="cleanupordertable"/>
         <button id="btnClear" class="col-sm-5 btn btn-danger btn-md" Style="margin:5px;" type="submit" >Clean my mess!</button>
    </form>
    <form action="" method="post">
         <input type="hidden" name="populateorders"/>
         <button id="btnSearch" class="col-sm-5 btn btn-primary btn-md" Style="margin:5px;" type="submit" >Setup my Demo</button>
</form>
    <label Style="margin:10px;"><?php echo $labelmessage;?></label>

     </div> 

</div>
</div>

</body>
</html>