<?php
session_start();
require 'db.php';

$name = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confpass = $_POST['confirmPassword'];
$hashpass = password_hash($password, PASSWORD_DEFAULT);


if ($password != $confpass) {
  $_SESSION['match_pass'] = 'Confirmpasswrd does not match';
  header('location:/first_shop/index.php');
} else {
  $count_email = "SELECT COUNT(*)as total FROM users WHERE email='$email'";
  $result_count_email = mysqli_query($db_connection, $count_email);
  $assoc_count = mysqli_fetch_assoc($result_count_email);
  $count = $assoc_count['total'];

  // echo $count;
  if ($count > 0) {
    $_SESSION['warning'] = 'Email Addess Already exist';
    header('location:/first_shop/index.php');
  } else {
    $insert_users = "INSERT INTO users(name,email,password) VALUES ('$name','$email','$hashpass')";
    mysqli_query($db_connection, $insert_users);
    $_SESSION['success'] = 'Registation Successfully Compleate!';
    header('location:/first_shop/index.php');
  }
}