<?php
// accepted variable name is "on" or "off"

// initializ shopping cart class

include ('session.php');
include 'Cart.php';

$cart = new Cart;


?>
<!DOCTYPE html>
<html lang="en">
<script type='text/javascript'>

</script>
<head>
    <title>Awesome Shopping Store - View Cart</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
<!--     <link rel="stylesheet" href="css/style.css"> -->
    <style>
    .container{padding: 50px;}
    input[type="number"]{width: 20%;}
    </style>
    <script>
    function updateCartItem(obj,id){
        $.get("cartAction.php", {action:"updateCartItem", id:id, qty:obj.value}, function(data){
            if(data == 'ok'){
                location.reload();
            }else{
                alert('Cart update failed, please try again.');
            }
        });
    }
    </script>
</head>
<body>
<?php
include ("header.php");

?>
<div class="container">
    <h1>Shopping Cart</h1>
       
 <td><a href="welcome.php" class="btn btn-warning"><i class="glyphicon glyphicon-menu-left"></i> Continue Shopping</a></td>
   <table class="table">
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php

if ($cart->total_items() > 0)
    {

    // get cart items from session

    $cartItems = $cart->contents();
    foreach($cartItems as $item)
        {
?>
       <tr>
            <td><?php
        echo $item["name"];
?></td>
            <td><?php
        echo '$' . $item["price"] . ' USD';
?></td>
            <td><input type="number" class="form-control text-center" value="<?php
        echo $item["qty"];
?>" onchange="updateCartItem(this, '<?php
        echo $item["rowid"];
?>')"></td>
            <td><?php
        echo '$' . $item["subtotal"] . ' USD';
?></td>
            <td>
                <a href="cartAction.php?action=removeCartItem&id=<?php
        echo $item["rowid"];
?>" class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="glyphicon glyphicon-trash"></i></a>
            </td>
        </tr>
        <?php
        }
    }
  else
    {
?>
       <tr><td colspan="5"><p>Your cart is empty.....</p></td>
        <?php
    }

?>
   </tbody>
    <tfoot>
         <tr>
    </tr>


    </tfoot>
    </table>

<?php

if ($cart->total_items() > 0)
    {
?>
<table>
<tr>
                <td><strong></strong></td>
<h3 class="text-right"><span class="label label-default"><strong>Total <?php echo '$' . $cart->total() . ' USD';?></strong></span></h3>
</tr>

</table>
            <?php
    }
?>

<?php if($cart->total_items() > 0){ ?>
<!-- <a href="checkout.php" class="btn btn-success btn-block">Checkout <i class="glyphicon glyphicon-menu-right"></i></a> -->
<input name="Submit" onclick="location.href='checkout.php'" value="Checkout" class="btn btn-success btn-block">
<?php } ?>

</form>

</div>
</body>
</html>