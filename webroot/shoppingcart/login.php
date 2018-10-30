<!DOCTYPE html>
<?php
  $signup_message = "Sign Up for Free";
  $login_message = "Sign in";
   include("dbconfig.php");
   session_start();

   //echo count($_POST);
   
   if($_SERVER["REQUEST_METHOD"] == "POST" ) {
      // username and password sent from form 
    if(count($_POST) == 4){
    //echo "signup";
          $name = mysqli_real_escape_string($db,$_POST['firstname']);
          $lastname = mysqli_real_escape_string($db,$_POST['lastname']); 
          $email = mysqli_real_escape_string($db,$_POST['email']);
          $password = mysqli_real_escape_string($db,$_POST['password']);
          //echo $firstname;
  $sql = "INSERT INTO customers (name,lastname,email,password,phone,address,status,virtualserviceurl,inuseservice) VALUES ('$name','$lastname','$email','$password',0,'india',1,'',1)";
  //echo $sql;
  $result = mysqli_query($db,$sql);
  $id = mysqli_insert_id($db);
  $customer_id = $id;
  echo $customer_id;
  $orders_usertablename = $customer_id.'_orders';
  $orderitems_usertablename = $customer_id.'_order_items';
  $constraint_name = $orders_usertablename.'_ibfk_1';
  $constraint_name1 = $orderitems_usertablename.'_ibfk_1';
  $result=  mysqli_affected_rows($db);
 if($result==1){
    $sql = "CREATE TABLE $orders_usertablename (
             `id` int(20) NOT NULL AUTO_INCREMENT,
             `customer_id` int(11) NOT NULL,
             `total_price` float(10,2) NOT NULL,
             `created` datetime NOT NULL,
             `modified` datetime NOT NULL,
             `status` enum('1','0') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
             `paymenttype` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
             PRIMARY KEY (`id`),
             KEY `customer_id` (`customer_id`),
             CONSTRAINT $constraint_name FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    if(mysqli_query($db, $sql)){
          echo "Order Table created successfully.";
          $sql = "CREATE TABLE $orderitems_usertablename (
                   `id` int(20) NOT NULL AUTO_INCREMENT,
                   `order_id` int(20) NOT NULL,
                   `product_id` int(11) NOT NULL,
                   `quantity` int(5) NOT NULL,
                   PRIMARY KEY (`id`),
                   KEY `order_id` (`order_id`),
                   CONSTRAINT $constraint_name1 FOREIGN KEY (`order_id`) REFERENCES $orders_usertablename (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
                  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
          if(mysqli_query($db, $sql)){
            echo "Order Items Table created successfully.";
          }else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
          }

      } else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
 










    $login_message = "Signup Success! Please login.";
 }else{
  $login_message = "You are already a registered user.";
 }
   }elseif (count($_POST) == 2){
          //echo "hello";
          $email = mysqli_real_escape_string($db,$_POST['login_email']);
          $password = mysqli_real_escape_string($db,$_POST['login_password']); 

          $sql = "SELECT email FROM customers WHERE email = '$email' and password = '$password'";
          //echo $sql;
          // $result = mysqli_query($db,$sql) or die("Error: ".mysqli_error($db));
          $result = mysqli_query($db,$sql);
          $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
          $active = $row['email'];

          $count = mysqli_num_rows($result);
          //echo "Count is ".$count;

          // If result matched $myusername and $mypassword, table row must be 1 row
          //echo $count;
          if($count == 1) {
          $_SESSION['myusername'] = $active;
          $_SESSION['login_user'] = $active;

          header("location: welcome.php");
          //echo $active;
          }else {
           $login_message = "Your Login Name or Password is invalid";
          }
          }
   }
?>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>Sign-Up/Login Form</title>
  <!-- <link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'> -->
    <link rel="stylesheet" href="css/normalize.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
  
      <link rel="stylesheet" href="css/style.css">

  
</head>

<body>
<div class="jumbotron">
  <h1 class="display-4"><span class="glyphicon glyphicon-headphones" aria-hidden="true"></span>
Hello, Welcome to</h1>
  <h1 class="display-5">Awesome Shopping Store</h1>

</div>
  <div class="form">
      
      <ul class="tab-group">
        <li class="tab"><a href="#signup">Sign Up</a></li>
        <li class="tab active"><a href="#login">Log In</a></li>
      </ul>
      
      <div class="tab-content">
        <div id="login">   
          <h1><?php echo $login_message ?></h1>
          <form action="login.php" method="post">
          
            <div class="field-wrap">
            <label>
              Email Address<span class="req">*</span>
            </label>
            <input type="text"required autocomplete="off" name="login_email"/>
          </div>
          
          <div class="field-wrap">
            <label>
              Password<span class="req">*</span>
            </label>
            <input type="password"required autocomplete="off" name="login_password"/>
          </div>
          
          <p class="forgot"><a href="#">Forgot Password?</a></p>
          
          <button class="button button-block"/>Log In</button>
          
          </form>

        </div>
        <div id="signup">   
          <h1><?php echo $signup_message ?></h1>
          <form action="login.php" method="post">
          
          <div class="top-row">
            <div class="field-wrap">
              <label>
                First Name<span class="req">*</span>
              </label>
              <input type="text" required autocomplete="off" name="firstname"/>
            </div>
        
            <div class="field-wrap">
              <label>
                Last Name<span class="req">*</span>
              </label>
              <input type="text"required autocomplete="off" name="lastname"/>
            </div>
          </div>

          <div class="field-wrap">
            <label>
              Email Address<span class="req">*</span>
            </label>
            <input type="email"required autocomplete="off" name="email"/>
          </div>
          
          <div class="field-wrap">
            <label>
              Set A Password<span class="req">*</span>
            </label>
            <input type="password"required autocomplete="off" name="password"/>
          </div>
          
          <button type="submit" class="button button-block"/>Get Started</button>
          
          </form>

        </div>
        

        
      </div><!-- tab-content -->
      
</div> <!-- /form -->
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

  

    <script  src="js/index.js"></script>




</body>

</html>
