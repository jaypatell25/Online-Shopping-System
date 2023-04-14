<?php 
//ignore the random images in the shop, they're just placeholders
//NOTE: you'll want to test the website by copypasting the link in a new tab, replit's webview doesn't work with some php functions
require 'functions.php';
session_start();
?>
<?php

if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = new Cart();
}

$conn = connect();
?>


<?php
    //when a customer clicks on the cart button, we add the item to cart
    if (isset($_POST['product-id'])) {
      $addedProduct = $_POST['product-id'];
      $_SESSION['cart']->addItem($addedProduct);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  

</head>
<div class="general">
<body>
  <div class="header">
    <h1>Online Shopping Department</h1>
    
    <p> <button onclick="window.location.href=
          'supportForm.php';" class="button button1"> Support Form</button></p>
    <p> <button onclick="window.location.href='viewOrder.php'" class="button button1"> View Order </button></p>
    <p> <button onclick="window.location.href='adminLogin.php'" class="button button1"> Super Secret Admin Page </button></p>
  </div>
    <div class="checkout-preview">
        <button onclick="window.location.href=
          'checkoutPage.php';">
            <i class="fa fa-fw fa-shopping-cart"></i>
            <p id="total">$<?php echo $_SESSION['cart']->calculatePrice()?><p>
        </button>
    </div>

    <div id="products-section" class="container" >
        <?php
            //create a function later to just print all entries
            $result = mysqli_query($conn, "SELECT * FROM products");
            $rows = mysqli_num_rows($result);
            for ($x = 1; $x < $rows+1; $x++){
              createHTML($conn, $x);
            }
        ?>

    </div>
</body>
</div>
</html>