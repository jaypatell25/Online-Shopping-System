<?php 
//ignore the random images in the shop, they're just placeholders
//NOTE: you'll want to test the website by copypasting the link in a new tab, replit's webview doesn't work with some php functions
require 'functions.php';
session_start();
?>
<?php
//uncomment to reset cart
//$_SESSION['cart'] = array();

$conn = connect();
?>



<?php
    //when a customer clicks on the cart button, we add the item to cart
    if (isset($_POST['product-id'])) {
      $addedProduct = $_POST['product-id'];
      if (isset($_SESSION['cart'][$addedProduct])){          
        $_SESSION['cart'][$addedProduct]->incrementStock();
      }
      else {
        $temp = createCartProductFromID($conn, $addedProduct);
        $_SESSION['cart'][$addedProduct] = $temp;
      }
      print_r($_SESSION['cart']);
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

<body>
    <div class="checkout-preview">
        <button class="checkout-button" onclick="test()">
            <i class="fa fa-fw fa-shopping-cart"></i>
            <p id="total">$<?php echo calculatePrice($_SESSION['cart'])?><p>
        </button>
    </div>

    <div id="products-section">
        <div class="product">
            <div class="top-info">
                <img class = "thumbnail" src="https://steamcdn-a.akamaihd.net/apps/440/icons/key.be0a5e2cda3a039132c35b67319829d785e50352.png">
                <p class = "product-name">Mann Co. Key</p>
            </div>

            <div class="bottom-info">
                <p class = "price">$2.05</p>
                <button class="buy-button">
                    <i class="fa fa-shopping-cart"></i>
                </button>
            </div>
        </div>
        <div class="product">
            <div class="top-info">
                <img class = "thumbnail" src="https://steamcdn-a.akamaihd.net/apps/440/icons/medic_ttg_max.08cc3314af9fa3c9f7d6a08ad1b1f418feba04dc.png">
                <p class = "product-name">Max's Severed Head</p>
            </div>
            <div class="bottom-info">
                <p class = "price">$47.00</p>
                <button class="buy-button">
                    <i class="fa fa-shopping-cart"></i>
                </button>
            </div>
        </div>
        <div class="product">
            <div class="top-info">
                <img class = "thumbnail" src="https://steamcdn-a.akamaihd.net/apps/440/icons/mvm_ticket.174c8f33d98ff44117cce8ed73a689bbd2328281.png">
                <p class = "product-name">Uncraftable Tour of Duty Ticket</p>
            </div>
            <div class="bottom-info">
                <p class = "price">$0.98</p>
                <button class="buy-button"><i class="fa fa-shopping-cart"></i></button>
            </div>
        </div>
      
        <?php
            //create a function later to just print all entries
            $result = mysqli_query($conn, "SELECT * FROM products");
            $rows = mysqli_num_rows($result);
            for ($x = 1; $x < $rows+1; $x++){
              createHTML($conn, $x);
            }
        ?>

    </div>
    
   <!-- <script src="script.js" await></script> -->
</body>
</html>