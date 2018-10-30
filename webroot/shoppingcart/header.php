

<nav class="navbar navbar-inverse" style="margin-bottom:2px">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Awesome shopping store</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li ><a href="welcome.php">Products</a></li>
        <li ><a href="orders.php">Orders <span class="sr-only">(current)</span></a></li>
        <li ><a href="viewCart.php">Shopping Cart <span class="sr-only">(current)</span></a></li>

        <li><a href="#"></a></li>
      </ul>
      <form class="navbar-form navbar-right" action="searchPage.php" method="get">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search" name="item">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#">Welcome <?php echo $login_session; ?></a></li>
        <li><a href="#" class="dropdown-toggle" data-toggle="dropdown">API Docs</a>
            <ul class="nav dropdown-menu"> 
                <li class="dropdown-item"><a target="_blank" href="https://app.swaggerhub.com/apis/sanketh/Awesome_Shopping_Store_Sanketh/1.0.0"><i class="glyphicon glyphicon-link"></i>SwaggerHub</a></li>
                <li class="dropdown-item"><a target="_blank" href="../swagger/index.html"><i class="glyphicon glyphicon-link"></i>Swagger UI</a></li>
            </ul>        
          </li>
        <li><a href="settings.php" title="Settings"></i> Settings</a></li><!--i class="glyphicon glyphicon-asterisk"-->
        <li class="active"><a href="logout.php">Logout <span class="sr-only">(current)</span></a></li>
       </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>