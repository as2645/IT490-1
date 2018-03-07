<?php

include 'user_functions.php';

$id = $_POST["id"];
$email = $_POST["email"];
$gameName = $_POST["name"];
$date = $_POST["date"];

watch_game($id, $email, $gameName, $date);

 ?>
