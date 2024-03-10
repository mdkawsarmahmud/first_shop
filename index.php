<?php
session_start();
require 'db.php';
// get user id
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
} else {
  $user_id = 0;
}

// log out 
if (isset($_GET['logout'])) {
  unset($_SESSION['user_id']);
  session_destroy();
}
$select_product = "SELECT * FROM products";
$result_product = mysqli_query($db_connection, $select_product);

// update
if (isset($_POST['update'])) {
  $coantity = $_POST['card_quantity'];
  $car_id = $_POST['card_id'];
  $update_contity = "UPDATE cards SET p_coantity='$coantity' WHERE id='$car_id' and user_id='$user_id'";
  mysqli_query($db_connection, $update_contity);
  $_SESSION['success'] = 'Card item update successfull';
}

// remove
if (isset($_POST['remove'])) {
  $car_id = $_POST['card_id'];
  $delete_item = "DELETE FROM cards WHERE id='$car_id' and user_id='$user_id'";
  mysqli_query($db_connection, $delete_item);
  $_SESSION['remove'] = 'Card item remove successfull';
}
// remove all
if (isset($_GET['remove_all'])) {
  $delete_item = "DELETE FROM cards WHERE user_id='$user_id'";
  mysqli_query($db_connection, $delete_item);
  $_SESSION['remove'] = 'Card item remove successfull';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-Shop</title>
  <link rel="stylesheet" href="css/styles.css?v=<?= time(); ?>">
</head>

<body>
  <nav class="navbar">
    <div class="logo"><img src="img/logo.png" alt="Your logo"></div>
    <ul class="nav-links">
      <li><a href="#home">Home</a></li>
      <li><a href="#product">Product</a></li>
      <li><a href="#about">About</a></li>
      <li><a href="#contact">Contact</a></li>
      <li><button id="loginBtn">Login</button></li>
      <li id="pfBtn" class="profile_icon"><img width="40" src="img/shop.png" alt="img"></li>
    </ul>
  </nav>
  <!-- profile -->
  <div id="profileFormContainer" class="hidden">
    <form id="loginForm" class="profile-form" action="" method="post">
      <h2>Order cards</h2>
      <?php
      if ($user_id > 0) {
        $select_user = "SELECT * FROM users WHERE id='$user_id'";
        $assoc_count = mysqli_fetch_assoc(mysqli_query($db_connection, $select_user));
      ?>
      <p>Name:<span style="color: #f39c12;"><?= $assoc_count['name'] ?></span> </p>
      <p>email:<span style="color: #f39c12;"><?= $assoc_count['email'] ?></span></p>
      <a href="index.php? logout=<?= $_SESSION['user_id'] ?>" class="delete-btn">Log out</a>
      <?php  } ?>
      <div class="info-sec">
        <div class="info">
          <?php
          if ($user_id > 0) { ?>
          <label for="name">Name</label>
          <input type="text" name="name" value="<?= $assoc_count['name'] ?>">
          <?php } else { ?>
          <label for="name">Name</label>
          <input type="text" name="name" value="">
          <?php }
          ?>
          <label for="number">Phone</label>
          <input type="number" name="name" value="" require>
          <label for="address">Address</label>
          <input type="text" name="address" value="" require>
          <textarea name="other-info" id="p-info" cols="40" rows="5"></textarea>
          <a href="" class="btn"> Conferm</a>
        </div>
        <div class="order-cards">
          <table>
            <thead>
              <th>img</th>
              <th>Name</th>
              <th>price</th>
              <th>cuentity</th>
              <th>total Price</th>
              <th>Action</th>
            </thead>
            <tbody>
            <tbody>
              <?php

              $select_card = "SELECT * FROM cards WHERE user_id='$user_id'";
              $result_card = mysqli_query($db_connection, $select_card);

              $grand_total = 0;

              if (mysqli_num_rows($result_card) > 0) {
                foreach ($result_card as $card) {
              ?>
              <tr>
                <td><img width="80" src="products/<?= $card['p_img'] ?>" alt=""></td>
                <td><?= $card['p_name'] ?></td>
                <td><?= $card['p_price'] ?>&#2547;</td>
                <td>
                  <form action="" method="post">
                    <input type="hidden" name="card_id" value="<?= $card['id'] ?>">
                    <input type="number" min="1" name="card_quantity" value="<?= $card['p_coantity'] ?>">
                    <input type="submit" class="option-btn" name="update" value="Update">
                  </form>
                </td>
                <td><?= $total_price = number_format($card['p_price'] * $card['p_coantity']) ?>&#2547;</td>
                <td>
                  <form action="" method="post">
                    <input type="hidden" name="card_id" value="<?= $card['id'] ?>">
                    <input type="submit" class="delete-btn" name="remove" value="Remove">
                  </form>
                </td>
              </tr>

              <?php
                  $grand_total += ((int)$card['p_price'] * (int)$card['p_coantity']);
                };
              } else { ?>
              <tr>
                <td colspan="6" style="padding:15px; text-transform:capitalize;"><b>No item added</b></td>
              </tr>
              <?PHP }

              ?>

              <tr class="tbottom">
                <td colspan="4">grand total</td>
                <td><?= $grand_total ?>&#2547;</td>
                <td><a href="index.php?remove_all" class="delete-btn <?= ($grand_total > 1) ? '' : 'diseble'; ?>">Remove
                    all</a></td>
              </tr>

            </tbody>

          </table>
        </div>

      </div>
    </form>
  </div>
  <!-- profile end -->

  <!-- login -->
  <div id="loginFormContainer" class="hidden">
    <form id="loginForm" class="login-form" action="login_post.php" method="post">
      <h2>Login</h2>
      <div class="input-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email">
      </div>
      <div class="input-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
      </div>
      <button class="f_button" type="submit">Login</button>
      <p class="register-message">Not registered? <span id="showRegisterForm">Register here</span></p>
    </form>
  </div>
  <!-- register -->
  <div id="registerFormContainer" class="hidden">
    <form id="registerForm" class="register-form" action="register_post.php" method="post">
      <h2>Register</h2>
      <div class="input-group">
        <label for="username">username:</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="input-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="input-group">
        <label for="newPassword">Password:</label>
        <input type="password" id="newPassword" name="password" required>
      </div>
      <div class="input-group">
        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required>
      </div>
      <button class="f_button" type="submit">Register</button>
      <p class="register-message">Already registered? <span id="showLoginForm">Login here</span></p>
    </form>
  </div>

  <!-- home -->
  <div class="container" id="home">
    <div class="home_bannaer"><img src="img/banner.jpg" alt="banner"></div>
  </div>
  <!-- product -->
  <div class="container" id="product">
    <div class="heading">
      <h2>Lettest product</h2>
    </div>
    <div class="box-conteiner">
      <?php
      foreach ($result_product as $product) { ?>
      <div class="card">
        <form action="add_to_card.php" method="post">
          <img src="products/<?= $product['img'] ?>" alt="">
          <div class="name"><?= $product['name'] ?></div>
          <div class="price"><?= $product['price'] ?>&#2547;</div>
          <input type="number" min="1" name="coantity" value="1">

          <input type="hidden" name="img" value="<?= $product['img'] ?>">
          <input type="hidden" name="name" value="<?= $product['name'] ?>">
          <input type="hidden" name="price" value="<?= $product['price'] ?>">
          <button type="submit" class="btn">Add to card</button>
        </form>
      </div>
      <?php } ?>
    </div>

    </p>
  </div>
  <!-- about -->
  <div class="container" id="about">
    <section class="about-section">
      <div class="container">
        <h2 class="about-heading">About Us</h2>
        <div class="about-content">
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer condimentum erat vel ex euismod, vel
            fermentum nunc fermentum. Duis eget ligula sit amet ligula dignissim finibus in a libero. Sed in cursus
            libero. Integer at justo nec eros lacinia efficitur. Morbi viverra vehicula justo at eleifend. Nulla
            facilisi. Nam porttitor quam nec quam consequat commodo. Duis nec posuere purus. Donec vitae lacus ac libero
            aliquet vestibulum. Morbi vitae dapibus sem.</p>
          <p>Phasellus efficitur neque vitae eros dictum, id egestas orci efficitur. Aliquam erat volutpat. Nulla
            mattis, quam sit amet luctus fringilla, urna leo tempus lacus, nec pharetra eros odio sed mi. Nulla
            consequat sem at quam volutpat dignissim. In sodales sapien sit amet nunc rhoncus rhoncus. Aliquam erat
            volutpat. Integer et elit non augue finibus euismod a eget nulla.</p>
        </div>
        <div class="about-image">
          <img width="150" src="img/profile1.jpg" alt="About Us">
        </div>
      </div>
    </section>
  </div>
  <!-- contuc -->
  <div class="container" id="contact">

    <section class="contact-section">
      <div class="container">
        <h2 class="contact-heading">Contact us</h2>
        <div class="contact-info">
          <p>Email: skkawsar.bpi@gmail.com</p>
          <p>Phone: +8801618602865</p>
          <p>Address: mirpur, Dhaka, Bangladesh</p>
        </div>
        <form class="contact-form" action="#" method="post">
          <input type="text" name="name" placeholder="Your Name">
          <input type="email" name="email" placeholder="Your Email">
          <textarea name="message" placeholder="Your Message"></textarea>
          <input class="btn" type="submit" value="Send Message">
        </form>
      </div>
    </section>

  </div>
  <footer class="footer">
    <p>&copy; 2024 Your Website. All rights reserved.</p>
  </footer>

  <script src="js/script.js" <?= time(); ?>></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>

<?php
if (isset($_SESSION['match_pass'])) { ?>
<script>
Swal.fire({
  icon: "error",
  title: "Not Register",
  text: "<?= $_SESSION['match_pass'] ?>"
});
</script>
<?php }
unset($_SESSION['match_pass']) ?>

<?php
if (isset($_SESSION['warning'])) { ?>
<script>
Swal.fire({
  icon: "warning",
  title: "Not Register",
  text: "<?= $_SESSION['warning'] ?>"
});
</script>
<?php }
unset($_SESSION['warning']) ?>

<?php
if (isset($_SESSION['success'])) { ?>
<script>
Swal.fire({
  icon: "success",
  title: "<?= $_SESSION['success'] ?>",
  showConfirmButton: false,
  timer: 1500
});
</script>
<?php }
unset($_SESSION['success']) ?>

<?php
if (isset($_SESSION['wrong'])) { ?>
<script>
Swal.fire({
  icon: "error",
  title: "Not log-in",
  text: "<?= $_SESSION['wrong'] ?>"
});
</script>
<?php }
unset($_SESSION['wrong']) ?>

<?php
if (isset($_SESSION['not_reg'])) { ?>
<script>
Swal.fire({
  icon: "error",
  title: "log-in",
  text: "<?= $_SESSION['not_reg'] ?>"
});
</script>
<?php }
unset($_SESSION['not_reg']) ?>

<?php
if (isset($_SESSION['remove'])) { ?>
<script>
Swal.fire({
  icon: "success",
  title: "<?= $_SESSION['remove'] ?>",
  showConfirmButton: false,
  timer: 1500
});
</script>
<?php }
unset($_SESSION['remove']) ?>