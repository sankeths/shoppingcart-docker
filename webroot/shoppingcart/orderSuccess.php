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
    <!-- <script src="js/jquery.min.js"></script> -->
    <!-- <script src="js/bootstrap.min.js"></script> -->
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <title>Awesome Shopping Store - Order Success</title>
    <meta charset="utf-8">
    <style>
    .container{width: 100%;padding: 50px;}
    p{color: #34a853;font-size: 18px;}
    </style>
</head>
<body>
<?php include("header.php"); ?>
<div class="container">
    <h1>Order Status</h1>
    <p>Your order has submitted successfully.</p>
    <p>Order ID is #<?php echo $_GET['id']; ?></p>
</div>
</body>
</html>