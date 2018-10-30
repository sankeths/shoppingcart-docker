<?php
include('session.php');
if(!isset($_REQUEST['id'])){
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <style>
    <title>Awesome Shopping Store - Order Success</title>
    <meta charset="utf-8">
    <style>
    .container{width: 100%;padding: 50px;}
    p{color: #4d0000;font-size: 18px;}
    </style>
</head>
<body>
<?php include("header.php"); ?>
<div class="container">
    <h1>Order Status</h1>
    <p>Oops! Your order failed.Your credit card company says - <?php echo $_GET['Status']; ?></p>


    <div class="footBtn">
        <a href="welcome.php" class="btn btn-warning"><i class="glyphicon glyphicon-menu-left"></i>Home</a>
      <!-- </br>
      </br> -->
        <a  href="checkout.php" class="btn btn-success"><i class="glyphicon glyphicon-menu-left"></i>Retry Payment<i class="glyphicon glyphicon-menu-right"></i></a>
      <!-- </br>
      </br> -->
        <a href="cartAction.php?action=placeOrder" class="btn btn-success">Place Order & Pay Offline<i class="glyphicon glyphicon-menu-right"></i></a>
        <!-- <a href="cartAction.php?action=placeOrder" class="btn btn-success orderBtn">Place Order <i class="glyphicon glyphicon-menu-right"></i></a> -->
    </div>
    </div>
</body>
</html>