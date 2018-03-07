<?php
function login($email, $password){
  $user = new mysqli('192.168.43.66', 'root', 'Jonathan723', 'accounts');
  /* User login process, checks if user exists and password is correct */
  // Escape email to protect against SQL injections
  $email = $mysqli->escape_string($email);
  $result = $mysqli->query("SELECT * FROM users WHERE email='$email'");
  if ( $result->num_rows == 0 ){ // User doesn't exist
      $_SESSION['message'] = "User with email: ".$email." doesn't exist!";
      header("location: error.php");
  }
  else { // User exists
      $user = $result->fetch_assoc();
      if ( password_verify($password, $user['password']) ) {
          $_SESSION['email'] = $user['email'];
          $_SESSION['first_name'] = $user['first_name'];
          $_SESSION['last_name'] = $user['last_name'];
          $_SESSION['active'] = $user['active'];
          // This is how we'll know the user is logged in
          $_SESSION['logged_in'] = true;
          header("location: profile.php");
      }
      else {
          echo $_SESSION['message'] = "You have entered wrong password, try again!";
          header("location: error.php");
      }
  }
}
function register($first_name,$last_name,$email,$password){
  $first_name = $mysqli->escape_string($_POST['firstname']);
  $last_name = $mysqli->escape_string($_POST['lastname']);
  $email = $mysqli->escape_string($_POST['email']);
  $password = $mysqli->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
  $hash = $mysqli->escape_string( md5( rand(0,1000) ) );
  // Check if user with that email already exists
  $result = $mysqli->query("SELECT * FROM users WHERE email='$email'") or die($mysqli->error());
  // We know user email exists if the rows returned are more than 0
  if ( $result->num_rows > 0 ) {
    echo "$_SESSION['message'] = 'User with this email already exists!';
    header(\"location: error.php\");"

  }
  else { // Email doesn't already exist in a database, proceed...
      //connection to database for user related tables
      $user = new mysqli('192.168.43.66', 'root', 'Jonathan723', 'userdata');
      //create user table for owned games
      $owned = "CREATE TABLE `owned_$email`(
        steam_app_id INT NOT NULL,
        rating ENUM('null','like','dislike') DEFAULT 'null',
        game_name VARCHAR(255),
        PRIMARY KEY (steam_app_id)
      )";
      $user->query($owned) or die($user->error);
      //table for user preferences
      $pref = "CREATE TABLE `pref_$email`(
        genre_id INT NOT NULL AUTO_INCREMENT,
        genre INT NOT NULL,
        PRIMARY KEY (genre_id)
      )";
      $user->query($pref) or die($user->error);
      //table for user preferences
      $watch = "CREATE TABLE `watch_$email`(
        steam_app_id INT NOT NULL,
        game_name VARCHAR(255),
        exp_release_date DATE,
        PRIMARY KEY (steam_app_id)
      )";
      $user->query($watch) or die($user->error);
      // active is 0 by DEFAULT (no need to include it here)
      $sql = "INSERT INTO users (first_name, last_name, email, password, hash) "
              . "VALUES ('$first_name','$last_name','$email','$password', '$hash')";
      // Add user to the database
      if ( $mysqli->query($sql) ){
          $_SESSION['active'] = 0; //0 until user activates their account with verify.php
          $_SESSION['logged_in'] = true; // So we know the user has logged in
          $_SESSION['message'] =
                   "Confirmation link has been sent to $email, please verify
                   your account by clicking on the link in the message!";
          // Send registration confirmation link (verify.php)
          $to      = $email;
          $subject = 'Account Verification ( clevertechie.com )';
          $message_body = '
          Hello '.$first_name.',
          Thank you for signing up!
          Please click this link to activate your account:
          http://localhost/login-system/verify.php?email='.$email.'&hash='.$hash;
          mail( $to, $subject, $message_body );
          header("location: profile.php");
          //Log account creation in reg.log
          $date = date_create();
          file_put_contents('reg.log', "[".date_format($date, 'm-d-Y H:i:s')."] "."Account with email: ".$email." successfully registered.".PHP_EOL, FILE_APPEND);
      }
      else {
          $_SESSION['message'] = 'Registration failed!';
          header("location: error.php");
      }
}
function rate_game($app_id, $rating, $email){
      $user = new mysqli('192.168.43.66', 'root', 'Jonathan723', 'userdata');
      $sql = "UPDATE `owned_$email`
              SET rating = '$rating'
              WHERE steam_app_id = $app_id";
      $user->query($sql) or die($user->error);
}
function mark_owned($app_id, $email, $gameName){
      $user = new mysqli('192.168.43.66', 'root', 'Jonathan723', 'userdata');
      $sql = "INSERT INTO `owned_$email`
              (steam_app_id, game_name)
              VALUES ($app_id,'$gameName')";
      $user->query($sql) or die($user->error);
}
function watch_game($app_id, $email, $gameName, $date){
      $user = new mysqli('192.168.43.66', 'root', 'Jonathan723', 'userdata');
      $sql = "INSERT INTO `watch_$email`
              VALUES ($app_id, '$gameName', '$date')";
      $user->query($sql) or die($user->error);
}
function recommend_game($app_id, $first_name, $last_name, $email, $name){
      $user = new mysqli('192.168.43.66', 'root', 'Jonathan723', 'userdata');
      $sql = "INSERT INTO recommended_games
              (steam_app_id, first_name, last_name, email, recommend, game_name)
              VALUES ($app_id, '$first_name', '$last_name', '$email', 'r', '$name')";
      $user->query($sql) or die($user->error);
}
function check_owned($app_id, $email){
    $user = new mysqli('192.168.43.66', 'root', 'Jonathan723', 'userdata');
    $sql = "SELECT * FROM `owned_$email`
           WHERE steam_app_id = $app_id";
    $result = $user->query($sql);
    if($result->num_rows == 0) {
            echo "
            </br></br>
                <button class='button button-block' id='own'>
                Mark owned
                </button>
            </div>";
    }
    else {
            echo "
            </br></br>
            <div class='alert alert-success'>
                <strong>You own this game</strong>
            </div>";
            $row = $result->fetch_assoc();
            $rating = $row["rating"];
            switch($rating){
              case "like":
                  echo
                  "
                  <div class='alert alert-success'>
                      <strong>You liked this game </strong><span class='glyphicon glyphicon-thumbs-up'></span>
                  </div>
                  <button class='button button-block' id='rec'>
                  Recommend
                  </button>
                  </span>
                  </div>";
                  break;
              case "dislike":
                  echo
                  "
                  <div class='alert alert-danger'>
                      <strong>You disliked this game </strong><span class='glyphicon glyphicon-thumbs-down'></span>
                  </div>
                  </span>
                  </div>";
                  break;
              case "null":
                  echo "
                  </br></br>
                  <div class='col-xs-6'>
                      <button class='button button-block' id='like'></strong>
                      <span class='glyphicon glyphicon-thumbs-up' style='color:white;'></span></button>
                  </div>";
                  echo "
                  <div class='col-xs-6'>
                      <button class='button button-block' id='dislike'></strong>
                      <span class='glyphicon glyphicon-thumbs-down' style='color:white;'></span></button>
                  </div>
                  </span>
                  </div>";
                  break;
              }
    }
}
function add_pref($genre_id, $email){
      $user = new mysqli('192.168.43.66', 'root', 'Jonathan723', 'userdata');
      $sql = "INSERT INTO `pref_$email`
              (genre)
              VALUES ($genre_id)";
      $user->query($sql) or die($user->error);
}
function display_owned($email){
      // Create connection
      $user = new mysqli('192.168.43.66', 'root', 'Jonathan723', 'userdata');
      $sql = "SELECT * FROM `owned_$email`";
      $result = $user->query($sql);
      if ($result->num_rows > 0) {
          echo "<table><tr><th>Title</th><th>Like / Dislike</th></tr>";
          $rating = "";
          // output data of each row
          while($row = $result->fetch_assoc()) {
              echo "<tr><td>".$row["game_name"]."</td>";
              switch($row["rating"]){
                case "like":
                    $rating = "<span class='glyphicon glyphicon-thumbs-up'></span>";
                    echo "<td>".$rating."</td></tr>";
                    break;
                case "dislike":
                    $rating = "<span class='glyphicon glyphicon-thumbs-down'></span>";
                    echo "<td>".$rating."</td></tr>";
                    break;
                case "null":
                    $rating = "Not rated yet!";
                    echo "<td>".$rating."</td></tr>";
                    break;
                }
          }
          echo "</table>";
      }
      else {
          echo "
          <div class='alert alert-danger'>
                <strong><center>No games yet!</center></strong>
          </div>";
      }
}
function display_recommended(){
      // Create connection
      $user = new mysqli('192.168.43.66', 'root', 'Jonathan723', 'userdata');
      $sql = "SELECT * FROM recommended_games";
      $result = $user->query($sql);
      if ($result->num_rows > 0) {
          echo "<table><tr><th>Title</th><th>Recommended by</th></tr>";
          $rating = "";
          // output data of each row
          while($row = $result->fetch_assoc()) {
              echo "<tr><td>".$row["game_name"]."</td><td>".ucwords($row["first_name"])." ".ucwords($row["last_name"])."</td></tr>";
          }
          echo "</table>";
      }
      else {
        echo "
        <div class='alert alert-danger'>
              <strong><center>No recommended games yet!</center></strong>
        </div>";
      }
}
function display_watchlist($email){
      // Create connection
      $user = new mysqli('192.168.43.66', 'root', 'Jonathan723', 'userdata');
      $sql = "SELECT * FROM `watch_$email`";
      $result = $user->query($sql);
      $notification = 'Upcoming Releases:\n\n';
      if ($result->num_rows > 0) {
          echo "<table><tr><th>Title</th><th>Expected Release Date</th></tr>";
          // output data of each row
          while($row = $result->fetch_assoc()) {
              $now = time();
              $your_date = strtotime($row["exp_release_date"]);
              $datediff = $your_date - $now;
              $days = round($datediff / (60 * 60 * 24) + 1);
              if($days <= 10){
                $notification .= $row["game_name"]." in ".$days." days".'\n';
              }
              $time = strtotime($row["exp_release_date"]);
              $newformat = date('m-d-Y',$time);
              echo "<tr><td>".$row["game_name"]."</td><td>".$newformat."</td></tr>";
          }
          echo "</table>";
          //echo $notification;
          echo "<script>";
          echo "
          $(document).ready(function(){
            alert('$notification');
          });";
          echo "</script>";
      }
      else {
          echo "
          <div class='alert alert-danger'>
                <strong><center>No games yet!</center></strong>
          </div>";
      }
}
?>
