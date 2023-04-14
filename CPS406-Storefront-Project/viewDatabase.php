<?php
  require 'functions.php';
  session_start();
?>
<!-- This page is for testing purposes. It'll print out the contents of the databases -->
<?php
  $conn = connect();
?>
<html>
  <head>
      <link rel="stylesheet" href="style.css">
  </head>
  <div class="mainContainerX">
  <body>
    <div class ="headContainerX"><h2>Admin Page</h2></div>
    <div class="containerX">
    <form method="post">
      <div class="button-group">
        <button type='submit' name='ordersButton' class="buttonX">View orders database</button>
        <button type='submit' name='productsButton'class="buttonX">View products database</button>
        <button type='submit' name='supportButton'class="buttonX">View support database</button>
      </div>
      <a href='index.php'>Home Page</a>
    </form><br>
    <?php
      if (isset($_POST['ordersButton'])){
        createOrdersDatabaseHTML($conn);
      }
      else if (isset($_POST['productsButton'])){
        createProductsDatabaseHTML($conn);
      }
      else if (isset($_POST['supportButton'])){
        createSupportDatabaseHTML($conn);
      }
    ?>
    </div>
  </body>
</div>
</html>