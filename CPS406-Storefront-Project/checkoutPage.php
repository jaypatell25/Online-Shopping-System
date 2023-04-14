<?php
require 'functions.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <div class="mainContainer"
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout</title>
  <link rel="stylesheet" href="style.css">
</head>
    <div class="container">
<body>
  <?php
    $conn = connect();
    if(isset($_POST['deleteButton'])){
      $_SESSION['cart']->deleteItem($_POST['deleteButton']);
    }
    if(isset($_POST['orderButton'])){
      if (!$_SESSION['cart']->checkCartStock($conn)) {
        echo "<p class='error-text'>invalid stock</p>";
      }
      else if (!checkCheckoutForms($_POST['fullname'],
                                  $_POST['shipping_address'], $_POST['email'],
                                  $_POST['credit'])){
        //the function echoes so we don't have to do anything  
      }
      else {
        // Get customer information from form
        $fullname = $_POST['fullname'];
        $shipping_address = $_POST['shipping_address'];
        $email = $_POST['email'];
        
        // Place order
        $orderID = placeOrder($conn, $_SESSION['cart'], $fullname, $shipping_address, $email);
        
        // Clear cart
        $_SESSION['cart'] = new Cart();
        $orderPlaced = true;
      }
    }
  ?>
  <?php 
  if (isset($orderPlaced)) {
    echo "<p>Thank you for shopping! Come back soon.</p>";
    echo "<p>Your order Id is $orderID</p>";
    echo "<a href='index.php'>Home Page</a>";
  } else {
    createCheckoutTableHTML($_SESSION['cart']);
  ?>
  <p class='error-text'>Reminder: Please don't put your actual info in here.</p>
  <form method='post'>
    <label for='fullname'>Full Name:</label><br>
    <input type='text' id='fullname' name='fullname'><br>

    <label for='shipping_address'>Shipping Address:</label><br>
    <input type='text' id='shipping_address' name='shipping_address'><br>
    
    <label for='credit'>Credit Card Number:</label><br>
    <input type='credit' id='credit' name='credit'><br>
    
    <label for='email'>Email:</label><br>
    <input type='email' id='email' name='email'><br>

    <button type='submit' name='orderButton' class="button button1">Order!</button>
  </form>
  <?php } ?>
</body>
    </div>
  </div>
</html>
