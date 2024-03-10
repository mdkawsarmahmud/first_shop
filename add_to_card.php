<?php
session_start();
require 'db.php';
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
} else {
  $user_id = 0;
}
if ($user_id < 1) {
  $_SESSION['not_reg'] = 'Please log in at first';
  header('location:/first_shop/index.php');
} else {
  $name = $_POST['name'];
  $img = $_POST['img'];
  $price = $_POST['price'];
  $coantity = $_POST['coantity'];

  $card_insert = "INSERT INTO cards(user_id,p_name,p_price,p_coantity,p_img) VALUES ('$user_id','$name','$price','$coantity','$img')";
  mysqli_query($db_connection, $card_insert);
  $_SESSION['success'] = 'Prduct add succesfully';
  header('location:/first_shop/index.php');
}