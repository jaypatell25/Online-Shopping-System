<?php require 'functions.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>View Order</title>
</head>
  <div class="mainContainer">
<body>
  <div class="headContainer"><h1>View Your Order</h1></div>
  <?php
    if (isset($_POST['searchButton'])){
      $conn = connect();
      searchOrder($conn, $_POST['orderID']);
    }
  ?>
      <div class="container">
  <form method='post'>
    <label for='orderID'>Order ID</label>
    <input type='text' id='orderID' name='orderID'><br>
    <button type='submit' name='searchButton' class="button button1">Search for Order</button>
  </form>
      </div>
  <a href='index.php'>Home Page</a>
</body>
</div>
</html>