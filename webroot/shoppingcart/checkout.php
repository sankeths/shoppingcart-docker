<?php
// include database configuration file
//include 'dbConfig.php';
include('session.php');

// initializ shopping cart class
include 'Cart.php';
$cart = new Cart;

if (isset($_POST['Submit']))
    {

    // code for check server side validation

    if (empty($_SESSION['captcha_code']) || strcasecmp($_SESSION['captcha_code'], $_POST['captcha_code']) != 0)
        {
        $msg = "<span style='color:red'>The CAPTCHA text does not match!</span>"; // Captcha verification is incorrect.
        }
      else
        { // Captcha verification is Correct. Final Code Execute here!

        // $msg="<span style='color:green'>The Validation code has been matched with Captcha -  " . $_SESSION['captcha_code'] . "</span>";

        header("Location:cartAction.php?action=placeOrder");
        }
    }

// redirect to home if cart is empty
if($cart->total_items() <= 0){
    header("Location: index.php");
}

// set customer ID in session
$_SESSION['sessCustomerID'] = $_SESSION['cus_id'];

// get customer details by session customer ID
$query = $db->query("SELECT * FROM customers WHERE id = ".$_SESSION['sessCustomerID']);
$custRow = $query->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <style>
    .container{width: 100%;padding: 50px;}
    .table{width: 65%;float: left;}
    .shipAddr{width: 30%;float: left;margin-left: 30px;}
    .footBtn{width: 95%;float: left;}
    .orderBtn {float: right;}
    </style>
<script type="text/javascript">
    function refreshCaptcha(){
    var img = document.images['captchaimg'];
    img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
    function myFunctionn() {
    var x = document.getElementById("myDIVV");
    var y = document.getElementById("myDIV");
    
    if (x.style.display === "none") {
        x.style.display = "block";
        y.style.display = "none";
    } else {
        x.style.display = "none";
    }
}


function myFunction() {
    var x = document.getElementById("myDIV");
    var y = document.getElementById("myDIVV");
    if (x.style.display === "none") {
        x.style.display = "block";
            y.style.display = "none";

    } else {
        x.style.display = "none";
    }
}
</script>
</head>
<body>
<?php include("header.php"); ?>
  <form autocomplete="off" action="cartAction.php" method="post">
<div class="container">
        <h1>Order Preview</h1>

    <a href="welcome.php" class="btn btn-warning"><i class="glyphicon glyphicon-menu-left"></i> Continue Shopping</a>
    <a href="print.php" class="btn btn-warning"><i class="glyphicon glyphicon-print"></i> Print Order</a>
    </br>
        </br>


     <?php

if (isset($msg))
    {
?>
   <tr>
<!--       <td colspan="2" align="center" valign="top"><?php
    echo $msg;
?></td> -->
<div class="alert alert-danger" role="alert">
  <?php
    echo $msg;
    // echo '<script type="text/javascript">myFunctionn();</script>';
?>
</div>
    </tr>
    <?php
    }

?>
    <table class="table">
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if($cart->total_items() > 0){
            //get cart items from session
            $cartItems = $cart->contents();
            foreach($cartItems as $item){
        ?>
        <tr>
            <td><?php echo $item["name"]; ?></td>
            <td><?php echo '$'.$item["price"].' USD'; ?></td>
            <td><?php echo $item["qty"]; ?></td>
            <td><?php echo '$'.$item["subtotal"].' USD'; ?></td>
        </tr>
        <?php } }else{ ?>
        <tr><td colspan="4"><p>No items in your cart......</p></td>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3"></td>
            <?php if($cart->total_items() > 0){ ?>
            <td class="text-center"><strong>Total <?php echo '$'.$cart->total().' USD'; ?></strong></td>
            <?php } ?>
        </tr>
    </tfoot>
    </table>
    <div class="shipAddr">
        <h4>Shipping Details</h4>
        <p><?php echo $custRow['name']; ?></p>
        <p><?php echo $custRow['email']; ?></p>
        <p><?php echo $custRow['phone']; ?></p>
        <p><?php echo $custRow['address']; ?></p>
    </div>
    <div class="footBtn">
      <!-- </br>
      </br> -->
        <a  onclick="myFunction()" class="btn btn-success"><i class="glyphicon glyphicon-menu-left"></i>Pay with Card<i class="glyphicon glyphicon-menu-right"></i></a>
      <!-- </br>
      </br> -->
        <a href="cartAction.php?action=placeOrder" class="btn btn-success"><i class="glyphicon glyphicon-menu-left"></i>Pay offline & Place Order <i class="glyphicon glyphicon-menu-right"></i></a>
        <a onclick="myFunctionn()" class="btn btn-success">Secure Pay offline & Place Order <i class="glyphicon glyphicon-menu-right"></i></a>
        <!-- <a href="cartAction.php?action=placeOrder" class="btn btn-success orderBtn">Place Order <i class="glyphicon glyphicon-menu-right"></i></a> -->
    </div>
</div>
<div id="myDIV" style="display:none;width: 30%;margin: 0 auto;">
  <input name="action" value="placeOrdercc" hidden="true" >
  <input name="cc_money" value=<?php echo $cart->total()?> hidden="true" >
  <!--?php $_POST['action1'] = 'placeOrdercc1';?-->


                                <div class="form-group">
                                    <label for="cc_name">Card Holder's Name</label>
                                    <input type="text" class="form-control" id="cc_name" pattern="\w+ \w+.*" title="First and last name" required="required" value="S s" name="cc_name">
                                </div>
                                <div class="form-group">
                                    <label>Card Number</label>
                                    <input type="text" class="form-control" autocomplete="off" maxlength="20" pattern="\d{16}" title="Credit card number" required="" value="1234123412341234" name="cc_number">
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-12">Card Exp. Date</label>
                                    <div class="col-md-4">
                                        <select class="form-control" name="cc_exp_mo" size="0">
                                            <option value="01">01</option>
                                            <option value="02">02</option>
                                            <option value="03">03</option>
                                            <option value="04">04</option>
                                            <option value="05">05</option>
                                            <option value="06">06</option>
                                            <option value="07">07</option>
                                            <option value="08">08</option>
                                            <option value="09">09</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-control" name="cc_exp_yr" size="0">
                                            <option>2018</option>
                                            <option>2019</option>
                                            <option>2020</option>
                                            <option>2021</option>
                                            <option>2022</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" autocomplete="off" maxlength="3" pattern="\d{3}" title="Three digits at back of your card" required="" placeholder="CVC" value="123" name="cc_cvv">
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-md-12">Amount</label>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <button type="reset" class="btn btn-default btn-lg btn-block">Cancel</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-success btn-lg btn-block">Pay <?php echo '$'.$cart->total().' USD'; ?></button>
                                    </div>
                                </div>
                            </form>
</div>

<form action="" method="post" name="form1" id="form1" >
<div id="myDIVV" class="text-center" style="margin:auto;width: 50%;display: none;" >
    <div class="panel panel-success ">
        <div class="panel-heading">Captcha Text</div>
        <div class="panel-body">
            <img src="captcha.php?rand=<?php echo rand();?>" id='captchaimg'><br />
            <label for='message'>Enter the code above here :</label><br />
            <input id="captcha_code" name="captcha_code" type="text"><br />Can't read the image? click 
            <a href='javascript: refreshCaptcha();'>here</a> to refresh.
            </div>
    </div>
    <input name="Submit" type="submit" onclick="" value="Secure Checkout" class="btn btn-success btn-block">
</div>

</body>
  <script  src="js/index.js"></script>
</html>
