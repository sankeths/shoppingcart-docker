<?php
include('session.php');
ini_set('display_errors', 'Off');
ini_set('log_errors', 'On');
ini_set("error_log", "/absolute/path/to/my/error_log");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Awesome Shopping Store - Order Success</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <link rel="stylesheet" href="css/bootstrap-glyphicons.css">
    <meta charset="utf-8">
    <style>
    .container{width: 100%;padding: 50px;}
    p{color: #34a853;font-size: 18px;}
    .search-bar { background-color: #222; }
    .search-bar * { color: white !important; }
    .search-bar input { border-bottom-color: white; }
    .md-errors-spacer { display:none; }

    </style>
</head>
<body>
<?php
include("header.php");
?>
<nav class="navbar navbar-inverse" style="margin-bottom:2px">
  <div class="container-fluid">
    <!-- Collect the nav links, forms, and other content for toggling -->
      <form class="navbar-form navbar-left" action="orders.php" method="get">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search for Order" name="orderNo">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
      </form>
  </div><!-- /.container-fluid -->
</nav>
<?php
if(isset($_GET['orderNo'])) {?>
  <div class="container">
          <?php
  $customer_id              = $_SESSION['cus_id'];
  $orders_usertablename     = $customer_id . '_orders';
  $orderitems_usertablename = $customer_id . '_order_items';
  $order_id                 = $_GET['orderNo'];


  $sql      = "Select count(id) from $orders_usertablename WHERE id = $order_id";
  $result   = mysqli_query($db, $sql);
  $row      = mysqli_fetch_array($result, MYSQLI_ASSOC);
  $count    = mysqli_num_rows($result);
  $rowcount = $row['count(id)'];
  if ($rowcount == 0) {
      echo "<h2 style='padding=1px'>Hey ! No Order found for - ".$order_id."</h2>";
      echo "<a style='padding=1px' href='welcome.php'><h3 style='padding=1px'>Browse products and order.</h3></a>";
  }
  else{
  $sql0 = "SELECT id,paymenttype FROM $orders_usertablename where id = " . $order_id;
  $result0 = mysqli_query($db, $sql0) or die("Error in Selecting " . mysqli_error($connection));
  $emparray0 = array();
  //  if(empty(mysqli_fetch_assoc($result0))){
  //     echo "<h2 style='padding=1px'>Hey ! You are yet to make your first order.</h2>";
  //     echo "<a style='padding=1px' href='welcome.php'><h3 style='padding=1px'>Browse products and order.</h3></a>";
  // }
  while ($row0 = mysqli_fetch_assoc($result0)) {
      $emparray0[] = $row0;
      //echo json_encode($row0['id']);
      $orderid     = $row0['id'];
      $paymenttype = $row0['paymenttype'];

      $total = 0;
      $sql   = "SELECT o.id,o.customer_id,c.name,o.total_price,o.status,oi.order_id,oi.quantity,p.price,p.name,p.image
                          FROM $orders_usertablename AS o
                            INNER JOIN $orderitems_usertablename AS oi ON o.id=oi.order_id
                            INNER JOIN products AS p ON oi.product_id = p.id
                            INNER JOIN customers AS c ON o.customer_id = c.id
                               where o.id = $orderid";
      $result = mysqli_query($db, $sql) or die("Error in Selecting " . mysqli_error($connection));
      $emparray = array();
  ?>
                <div class="jumbotron">
          <div class="panel panel-primary" style="padding: 15px;"><b><?php
      echo 'Order no - ' . $orderid;
  ?></br><?php
      echo 'Payment Status - ' . $paymenttype;
  ?></b></div>

           <table id="cart" class="table table-hover table-condensed">
              <thead>

                <tr>
                  <th style="width:50%">Product</th>
                  <th style="width:10%">Price</th>
                  <th style="width:8%">Quantity</th>
                  <th style="width:22%" class="text-center">Subtotal</th>
                </tr>
          </thead>
          <?php
      while ($row = mysqli_fetch_assoc($result)) {
          $emparray[] = $row;
          $total      = $row['total_price'];
          //echo json_encode($row['name']);
  ?>
              <tbody>
                <tr>
                  <td data-th="Product">
                    <div class="row">
                      <div class="col-sm-2 hidden-xs"><img src=<?php
          echo 'images/' . $row['image'];
  ?> alt="..." class="img-responsive" /></div>
                      <div class="col-sm-10">
                        <h4 class="nomargin"><?php
          echo $row['name'];
  ?></h4>
                    </div>
                  </td>
                  <td data-th="Price"><?php
          echo $row['price'];
  ?></td>
                  <td data-th="Quantity">
                  <label><?php
          echo $row['quantity'];
  ?></label>
                  </td>
                  <td data-th="Subtotal" class="text-center"><?php
          echo ($row['quantity'] * $row['price']);
  ?></td>
                </tr>
              </tbody>
                  <?php
      }

      //header("Content-type:application/json");
      //echo json_encode($emparray);
  ?>
            <tfoot>
                <tr class="visible-xs">
                    <td class="text-center"><strong>Total</strong></td>
                  <td class="text-center"><strong><?php
      echo $total;
  ?></strong></td>
                </tr>
                <tr>
                  <td></td>
                  <td colspan="2" class="hidden-xs"></td>
                  <td class="hidden-xs text-center">Total : <strong><?php
      echo $total;
  ?></strong></td>
               </tr>
              </tfoot>
            </table>
          </div>


                      <?php
  }
  }
}
else{?>
<div class="container">
        <?php
$customer_id              = $_SESSION['cus_id'];
$orders_usertablename     = $customer_id . '_orders';
$orderitems_usertablename = $customer_id . '_order_items';

$sql      = "Select count(id) from $orders_usertablename WHERE customer_id = $customer_id";
$result   = mysqli_query($db, $sql);
$row      = mysqli_fetch_array($result, MYSQLI_ASSOC);
$count    = mysqli_num_rows($result);
$rowcount = $row['count(id)'];
if ($rowcount == 0) {
    echo "<h2 style='padding=1px'>Hey ! You are yet to make your first order.</h2>";
    echo "<a style='padding=1px' href='welcome.php'><h3 style='padding=1px'>Browse products and order.</h3></a>";
}
else{
$sql0 = "SELECT id,paymenttype FROM $orders_usertablename where customer_id = " . $_SESSION['cus_id'] . " ORDER BY id DESC";
$result0 = mysqli_query($db, $sql0) or die("Error in Selecting " . mysqli_error($connection));
$emparray0 = array();
//  if(empty(mysqli_fetch_assoc($result0))){
//     echo "<h2 style='padding=1px'>Hey ! You are yet to make your first order.</h2>";
//     echo "<a style='padding=1px' href='welcome.php'><h3 style='padding=1px'>Browse products and order.</h3></a>";
// }
while ($row0 = mysqli_fetch_assoc($result0)) {
    $emparray0[] = $row0;
    //echo json_encode($row0['id']);
    $orderid     = $row0['id'];
    $paymenttype = $row0['paymenttype'];

    $total = 0;
    $sql   = "SELECT o.id,o.customer_id,c.name,o.total_price,o.status,oi.order_id,oi.quantity,p.price,p.name,p.image
                        FROM $orders_usertablename AS o
                          INNER JOIN $orderitems_usertablename AS oi ON o.id=oi.order_id
                          INNER JOIN products AS p ON oi.product_id = p.id
                          INNER JOIN customers AS c ON o.customer_id = c.id
                             where o.id = $orderid";
    $result = mysqli_query($db, $sql) or die("Error in Selecting " . mysqli_error($connection));
    $emparray = array();
?>
              <div class="jumbotron">
        <div class="panel panel-primary" style="padding: 15px;"><b><?php
    echo 'Order no - ' . $orderid;
?></br><?php
    echo 'Payment Status - ' . $paymenttype;
?></b></div>

         <table id="cart" class="table table-hover table-condensed">
            <thead>

              <tr>
                <th style="width:50%">Product</th>
                <th style="width:10%">Price</th>
                <th style="width:8%">Quantity</th>
                <th style="width:22%" class="text-center">Subtotal</th>
              </tr>
        </thead>
        <?php
    while ($row = mysqli_fetch_assoc($result)) {
        $emparray[] = $row;
        $total      = $row['total_price'];
        //echo json_encode($row['name']);
?>
            <tbody>
              <tr>
                <td data-th="Product">
                  <div class="row">
                    <div class="col-sm-2 hidden-xs"><img src=<?php
        echo 'images/' . $row['image'];
?> alt="..." class="img-responsive" /></div>
                    <div class="col-sm-10">
                      <h4 class="nomargin"><?php
        echo $row['name'];
?></h4>
                  </div>
                </td>
                <td data-th="Price"><?php
        echo $row['price'];
?></td>
                <td data-th="Quantity">
                <label><?php
        echo $row['quantity'];
?></label>
                </td>
                <td data-th="Subtotal" class="text-center"><?php
        echo ($row['quantity'] * $row['price']);
?></td>
              </tr>
            </tbody>
                <?php
    }

    //header("Content-type:application/json");
    //echo json_encode($emparray);
?>
          <tfoot>
              <tr class="visible-xs">
                  <td class="text-center"><strong>Total</strong></td>
                <td class="text-center"><strong><?php
    echo $total;
?></strong></td>
              </tr>
              <tr>
                <td></td>
                <td colspan="2" class="hidden-xs"></td>
                <td class="hidden-xs text-center">Total : <strong><?php
    echo $total;
?></strong></td>
             </tr>
            </tfoot>
          </table>
        </div>


                    <?php
}
}
}
?>
</div>
</body>
<script type="text/javascript">

</script>
</html>
