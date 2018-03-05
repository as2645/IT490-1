<?php
/* Displays user information and some useful messages */
session_start();

// Check if user is logged in using the session variable
/*if ( $_SESSION['logged_in'] != 1 ) {
  $_SESSION['message'] = "You must log in before viewing your profile page!";
  header("location: error.php");
}
else {
    // Makes it easier to read
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];

}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css'>
  <title>Welcome <?= $first_name.' '.$last_name ?></title>
  <?php include 'css/css.html'; ?>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="form">

    <h1>Search PC games</h1>



          <p>

          </p>

          <?php



          <h2><?php echo $first_name.' '.$last_name; ?></h2>
          <p><?= $email ?></p>

    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                <header id="header">

      <div class="slider">
      <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

                        <!-- Brand and toggle get grouped for better mobile display -->
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="mainNav" >
                          <!--<ul class="nav main-menu navbar-nav">-->
                <!-- replace # with link -->
                  <!--<a href="../logout.php"><button class="button button-block" name="LOGOUT"/>LOGOUT</button></a>
        <!--</ul>-->
            </div>
            </div>
    <!--<a class="active" href="#home">Home</a>-->
      <!--<a href="#about">About</a>-->
      <!--<a href="#contact">Contact</a>-->
      <input type="text" placeholder="search for games here">
    <b> </br></br></br></br></br></br></br></br></br>
    <a href="profile.php"><button class="button button-block" name="HOME"/>HOME</button></a>

    </div>

<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>

</body>
</html>
