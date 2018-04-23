<?php

include 'user_functions.php';

$id = $_POST["id"];
$rating  = $_POST["rating"];
$email = $_POST["email"];

rate_game($id, $rating, $email);

 ?>
