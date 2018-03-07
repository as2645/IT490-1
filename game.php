<?php
  $app_id = $_GET["gameid"];

  $options = array('http' => array('user_agent' => 'custom user agent string'));
  $context = stream_context_create($options);
  $apiResult = file_get_contents("https://www.giantbomb.com/api/game/3030-".$app_id."/?api_key=1eb68d69b5b8c3a4d37f93116ba4968ccf789a33&format=json&resources=game", false, $context);
  $json = json_decode($apiResult);
?>


<?php
session_start();

// Check if user is logged in using the session variable
if ( $_SESSION['logged_in'] != 1 ) {
  $_SESSION['message'] = "You must log in before viewing this page!";
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
<html>
<head>
  <meta charset="UTF-8">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>


<title><?php echo $json->results->name; ?></title>

<?php include 'css/css.html'; ?>

<link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="form">

    <div container>

    <h1><?php echo $json->results->name; ?></h1>


    <style>
    #HASH {
      display: flex;
      justify-content: space-between;
      }
    </style>

    <div id="HASH" class="blue-msg">
    <span class="image"><img src="<?php  echo $json->results->image->thumb_url;   ?>"></span>
    <span class="ios-circle">
    <?php

    $released = "";

    if($json->results->original_release_date){

              $time = strtotime($json->results->original_release_date);

              $newformat = date('m-d-Y',$time);

              echo "Release Date: ".$newformat;

              $released = "yes";

      }else{

              $exp_release_date = $json->results->expected_release_month."-".$json->results->expected_release_day."-".$json->results->expected_release_year;
              echo "Expected Release Date: ".$exp_release_date;

              $format = $json->results->expected_release_year."-".$json->results->expected_release_month."-".$json->results->expected_release_day;

              $released = "no";

      }?>
    </br></br>Genre:
    <?php

    echo $json->results->genres[0]->name;

    ?>

    <?php

    header("access-control-allow-origin: *");

    require 'user_functions.php';

    switch($released){
      case "yes":
            check_owned($app_id, $email);
            break;
      case "no":
            echo "
            </br></br>
                <button class='button button-block' id='watch'>
                Watch
                </button>
            </div>";
            break;
    }


    $like = "like";
    $dislike = "dislike";

    ?>
        <script>

        $('#like').click(function() {
             $('#like').load('like.php', {id: "<?php echo $app_id;?>", rating: "<?php echo $like;?>", email: "<?php echo $email;?>"}, function(e){
             $('#like').hide();
             $('#dislike').hide();
             alert("You liked this game!");
             location.reload();
             });
        });

        $('#dislike').click(function() {
             $('#dislike').load('like.php', {id: "<?php echo $app_id;?>", rating: "<?php echo $dislike;?>", email: "<?php echo $email;?>"}, function(e){
             $('#dislike').hide();
             $('#like').hide();
             alert("You disliked this game!");
             location.reload();
             });
        });

        $('#own').click(function() {
             $('#own').load('mark_owned.php', {id: "<?php echo $app_id;?>", email: "<?php echo $email;?>", name: "<?php echo $json->results->name; ?>"}, function(e){
             $('#own').hide();
             alert("Added to owned games!");
             location.reload();
             });
        });

        $('#watch').click(function() {
             $('#watch').load('watch_game.php', {id: "<?php echo $app_id;?>", email: "<?php echo $email;?>", name: "<?php echo $json->results->name; ?>", date: "<?php echo $format; ?>"}, function(e){
             $('#watch').hide();
             alert("You are now watching this game!");
             });
        });

        $('#rec').click(function() {
             $('#rec').load('recommend.php', {id: "<?php echo $app_id;?>", first_name: "<?php echo $first_name;?>", last_name: "<?php echo $last_name;?>", email: "<?php echo $email;?>", name: "<?php echo $json->results->name; ?>"}, function(e){
             $('#rec').hide();
             alert("Recommended!");
             });
        });

        </script>


    </br></br>
    <span class="text"><center><?php   echo $json->results->deck;    ?></center></span>
    </br>

    </div>

    <a href="search.php"><button class="button button-block" name="search"/>Find more games</button></a>
    <br><a href="profile.php"><button class="button button-block" name="Back"/>Back to profile</button></a>

    </div>

<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>

</body>
</html>
