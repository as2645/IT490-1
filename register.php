<?php
/* Registration process, inserts user info into the database
   and sends account confirmation email message
 */

// Set session variables to be used on profile.php page
$_SESSION['email'] = $_POST['email'];
$_SESSION['first_name'] = $_POST['firstname'];
$_SESSION['last_name'] = $_POST['lastname'];

// Escape all $_POST variables to protect against SQL injections
$first_name = $mysqli->escape_string($_POST['firstname']);
$last_name = $mysqli->escape_string($_POST['lastname']);
$email = $mysqli->escape_string($_POST['email']);
$password = $mysqli->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
$hash = $mysqli->escape_string( md5( rand(0,1000) ) );

// Check if user with that email already exists
$result = $mysqli->query("SELECT * FROM users WHERE email='$email'") or die($mysqli->error());

// We know user email exists if the rows returned are more than 0
if ( $result->num_rows > 0 ) {

    $_SESSION['message'] = 'User with this email already exists!';
    header("location: error.php");

}
else { // Email doesn't already exist in a database, proceed...


    //connection to database for user related tables
    $user = new mysqli('localhost', 'root', 'Jonathan723', 'userdata');

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
