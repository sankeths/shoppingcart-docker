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
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
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
        <?php
            $id = $_GET['id'];
            if($id){
                $sql      = "Select * from products where id = $id";
                $result   = mysqli_query($db, $sql);
                $row      = mysqli_fetch_array($result, MYSQLI_ASSOC);
                //var_dump(json_encode($row));                
            }
        ?>
                <div >
            <div class="thumbnail">
                <div class="caption" >
                <a href="moreInfo.php?id=<?php echo $row["id"]?>"><img src="images/<?php echo $row["image"];?>" alt="<?php echo $row["image"];?>"  class="img-thumbnail"></a>
                    </br>
                    <h4 class="list-group-item-heading"><?php echo $row["name"]; ?></h4></br>
                    <p class="list-group-item-text"><?php echo $row["description"]; ?></p></br>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="lead"><?php echo '$'.$row["price"].' USD'; ?></p>
                            <p class="lead">In Stock</p>
                        </div>
                        <div class="col-md-12">
                            <a class="btn btn-primary" href="welcome.php">Back</a>
                            <a class="btn btn-primary" href="cartAction.php?action=addToCart&id=<?php echo $row["id"]; ?>">Add to cart</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>