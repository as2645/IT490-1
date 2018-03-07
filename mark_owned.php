<?php

include 'user_functions.php';

$id = $_POST["id"];
$email = $_POST["email"];
$gameName = $_POST["name"];

mark_owned($id, $email, $gameName);

 ?>
