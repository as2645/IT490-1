<?php

function rate_game($app_id, $rating, $email){

      $user = new mysqli('localhost', 'root', 'Jonathan723', 'userdata');

      $sql = "UPDATE `owned_$email`
              SET rating = '$rating'
              WHERE steam_app_id = $app_id";

      $user->query($sql) or die($user->error);
}

function mark_owned($app_id, $email){

      $user = new mysqli('localhost', 'root', 'Jonathan723', 'userdata');

      $sql = "INSERT INTO `owned_$email`
              (steam_app_id)
              VALUES ($app_id)";

      $user->query($sql) or die($user->error);
}

function watch_game($app_id, $email){

      $user = new mysqli('localhost', 'root', 'Jonathan723', 'userdata');

      $sql = "INSERT INTO `watch_$email`
              (steam_app_id)
              VALUES ($app_id)";

      $user->query($sql) or die($user->error);
}

function add_pref($genre_id, $email){

      $user = new mysqli('localhost', 'root', 'Jonathan723', 'userdata');

      $sql = "INSERT INTO `pref_$email`
              (genre)
              VALUES ($genre_id)";

      $user->query($sql) or die($user->error);
}

function display_owned($email){

      // Create connection
      $user = new mysqli('localhost', 'root', 'Jonathan723', 'userdata');

      $sql = "SELECT * FROM `owned_$email`";
      $result = $user->query($sql);

      if ($result->num_rows > 0) {
          echo "<table><tr><th>Title</th><th>Like / Dislike</th></tr>";
          $rating = "";
          // output data of each row
          while($row = $result->fetch_assoc()) {

              echo "<tr><td>".$row["steam_app_id"]."</td>";
              echo "<td>".$row["rating"].$rating."</td></tr>";

              if($row["rating"] = "like"){
                $rating = "<span class='glyphicon glyphicon-thumbs-up'></span>";
              }
              elseif($row["rating"] = "dislike"){
                $rating = "<span class='glyphicon glyphicon-thumbs-down'></span>";
              }
              else{
                $rating = "Not rated yet!";
              }


          }
          echo "</table>";
      }
      else {
          echo "No games yet!";
      }

}



?>
