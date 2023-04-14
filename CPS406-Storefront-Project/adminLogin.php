<?php
  if (isset($_POST['login'])){
    if ($_POST['userName'] == "abcd" && $_POST['password'] == "1234"){
      header("Location: viewDatabase.php");
    }
  }
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="style.css">
  <title>Admin Login</title>

<style>
body {font-family: Arial, Helvetica, sans-serif;}
</style>
</head>

<div class = "mainContainer">
<body>
  
    <div class="headContainer" ><h2> Admin Login</h2></div>
    <div class="container">
    <form method="post">
      <br>
    <label for="fullName"><b>Admin Username</b></label><br>
    <input type="text" id='userName' name="userName"><br><br><br>
    <label for="password"><b>Password</b></label><br>
    <input type="password" id ='password' name="password"><br>
    <input type="submit" value="login" name="login" class = "button button1">
    </form>
      
    </div>
</body>
</div>
</html>