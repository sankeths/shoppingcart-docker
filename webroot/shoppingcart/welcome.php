<?php
include('session.php');
//echo $_SESSION['sessCustomerID'];
// include 'dbConfig.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Awesome Shopping Store - Products</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <!-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet"> -->
    <style>
    .container{padding: 50px;}
    .cart-link{width: 100%;text-align: right;display: block;font-size: 22px;}
    </style>
</head>
<body>
<?php include("header.php"); ?>
<div class="jumbotron">
  <h1 class="display-4"><span class="glyphicon glyphicon-headphones" aria-hidden="true"></span>
Hello <?php echo $login_session; ?>, Welcome to</h1>
  <h1 class="display-5">Awesome Shopping Store</h1>
  </div>
<div class="container">
<!--       <h1>Welcome <?php echo $login_session; ?></h1> 
      <h2><a href = "logout.php">Sign Out</a></h2> -->
    <!-- <h1>Products</h1> -->
    <div id="products" class="row list-group">
        <?php
        //get rows query
        $query = $db->query("SELECT * FROM products ORDER BY id DESC LIMIT 10");
        if($query->num_rows > 0){ 
            while($row = $query->fetch_assoc()){
        ?>
        <div class="item col-lg-4">
            <div class="thumbnail">
                <div class="caption" >
         		<a href="moreInfo.php?id=<?php echo $row["id"]?>"><img src="images/<?php echo $row["image"];?>" alt="<?php echo $row["image"];?>"  class="img-thumbnail"></a>
         			</br></br></br>
                    <h4 class="list-group-item-heading"><?php echo $row["name"]; ?></h4>
                    <p class="list-group-item-text"><?php echo $row["description"]; ?></p>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="lead"><?php echo '$'.$row["price"].' USD'; ?></p>
                        </div>
                        <div class="col-md-6">
                            <a class="btn btn-success" href="cartAction.php?action=addToCart&id=<?php echo $row["id"]; ?>">Add to cart</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } }else{ ?>
        <p>Product(s) not found.....</p>
        <?php } ?>
    </div>
</div>
</body>
</html>