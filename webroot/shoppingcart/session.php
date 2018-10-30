<?php
   include('dbconfig.php');
   session_start();
   
   $user_check = $_SESSION['login_user'];
   
   $ses_sql = mysqli_query($db,"select name,id from customers where email = '$user_check' ");
   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
   $login_session = $row['name'];
   $_SESSION['cus_id'] = $row['id'];

   if(!isset($_SESSION['login_user'])){
      header("location:index.php");
   }
?>