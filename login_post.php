<?php
session_start();
require 'db.php';


$email = $_POST['email'];
$password = $_POST['password'];

$hashpass = password_hash($password, PASSWORD_DEFAULT);


$count_email = "SELECT COUNT(*)as total FROM users WHERE email='$email'";
$result_count_email = mysqli_query($db_connection, $count_email);
$assoc_count = mysqli_fetch_assoc($result_count_email);
$count = $assoc_count['total'];

// echo $count;
if ($count == 1) {
  $selectemail = "SELECT * FROM users WHERE email='$email'";
  $result_email = mysqli_query($db_connection, $selectemail);
  $assoc_pass = mysqli_fetch_assoc($result_email);
  $_SESSION['user_id'] = $assoc_pass['id'];

  if (password_verify($password, $assoc_pass['password'])) {
    header('location:/first_shop/index.php');
  } else {
    $_SESSION['wrong'] = 'Incurrect password';
    header('location:/first_shop/index.php');
  }
} else {
  $_SESSION['warning'] = 'You are not registered';
  header('location:/first_shop/index.php');
}