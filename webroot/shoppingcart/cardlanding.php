<?php
include('session.php');

// initializ shopping cart class
include 'Cart.php';
$cart = new Cart;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <style>
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
 <?php echo '$'.$cart->total().' USD'; ?>
</div>
</body>
</html>
