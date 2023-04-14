<?php
  require 'functions.php';
  session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="style.css">
  <title>Support Form</title>
  <style>
  body {font-family: Arial}
  * {box-sizing: border-box;}
    body {
    margin: 8px;
    }
  </style>

</head>
<div class ="mainContainer1">
<body>
<div class ="headContainer"><h2>Support Form</h2></div>

<?php
  //if support request has been sent, see if it's valid, and add it to the database if it is
  if (isset($_POST['submitSupport'])) {
    if (!checkSupportForms($_POST['fullName'], $_POST['email'],
                         $_POST['subject'])){
    //do nothing  
    }
    else{
      $conn = connect();
      addSupportRequestToDatabase($conn, $_POST['fullName'], $_POST['email'],
                                 $_POST['subject']);
      $request_sent = true;
    }
  }

  //if a support request has been successfully sent, send a confirmation and don't render the rest of the page
  if (isset($request_sent)){
    echo "<p>Support request successfully sent!</p>";
    echo "<a href='index.php'>Home Page</a>";
  }
    
  else {
?>

<div class="container1">
  <form method="post">
    <label for="fullName"><b>Full Name</b></label><br>
    <input type="text" id='fullName' name="fullName"><br>
    <label for="email"><b>Email</b></label><br>
    <input type="text" id ='email' name="email"><br>
    <label for="subject"><b>Support</b> </label><br>
    <textarea maxlength="500" id="subject" name="subject" style="height:100px" placeholder="max 500 characters" class = "text"></textarea>
    <br>
    <input type="submit" value="Submit" name="submitSupport" class = "button button1">
  </form>
</div>
  <?php } ?>
</body>
</div>
</html>
