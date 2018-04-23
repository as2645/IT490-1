<?php
/* Displays user information and some useful messages */
session_start();

// Check if user is logged in using the session variable
if ( $_SESSION['logged_in'] != 1 ) {
  $_SESSION['message'] = "You must log in before viewing your profile page!";
  header("location: error.php");
}
else {
    // Makes it easier to read
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    $email = $_SESSION['email'];
    $active = $_SESSION['active'];
}
?>
<!DOCTYPE html>
<html >
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

          <h1>Welcome</h1>

          <p>
          <?php

          // Display message about account verification link only once
          /*if ( isset($_SESSION['message']) )
          {
              echo $_SESSION['message'];

              // Don't annoy the user with more messages upon page refresh
              unset( $_SESSION['message'] );
          }*/

          ?>
          </p>

          <?php

          // Keep reminding the user this account is not active, until they activate
          /*if ( !$active ){
              echo
              '<div class="info">
              Account is unverified, please confirm your email by clicking
              on the email link!
              </div>';
          }*/

          ?>

          <h2><?php echo $first_name.' '.$last_name; ?></h2>
          <p><?= $email ?></p>

          <div class="row">
            <div class="col-md-6">
                <a href="search.php"><button class="button button-block" name="SEARCH GAMES"/>Search</button></a>
            </div>
            <div class="col-md-6">
                <a href="owned.php"><button class="button button-block" name="OWNED"/>Owned</button></a>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6">
                <a href="watchlist.php"><button class="button button-block" name="WATCH LIST"/>Watch List</button></a>
            </div>
            <div class="col-md-6">
                <a href="recommended.php"><button class="button button-block" name="RECOMMENDED"/>Recommended</button></a>
            </div>
          </div>
          <br><a href="logout.php"><button class="button button-block" name="logout"/>Log Out</button></a>

    </div>

<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>

</body>
</html>
