<?php

//this class defines a product in the customer's cart
class CartProduct {
  public $name;
  public $price;
  public $image;
  public $customerStock;

  function __construct($name, $price, $image, $customerStock){
    $this->name = $name;
    $this->price = $price;
    $this->image = $image;
    $this->customerStock = $customerStock;
  }
  
  function setProperty($value, $property) {
    switch ($property){
      case "name":
        $this->name = $value;
        break;
      case "price":
        $this->price = $value;
        break;
      case "image":
        $this->image = $value;
        break;
      case "customerStock":
        $this->customerStock = $value;
        break;
      default:
        echo "error when setting variable";
    }
  }
      
  function incrementStock() {
    $this->customerStock += 1;
  }
  
  function getProperty($property) {
    switch ($property){
      case "name":
        return $this->name;
      case "price":
        return $this->price;
      case "image":
        return $this->image;
      case "customerStock":
        return $this->customerStock;
      default:
        echo "error when getting variable";
    }
  }
  
}

function query($conn, $query) {
    $result = mysqli_query($conn, $query);
    if ($result) {
        //echo "query successful";
    }
    return $result;
}

//rewrite this function later to not work off id
function createHTML($conn, $id) {
    $query = "SELECT * FROM products WHERE id = ".$id;
    $result = mysqli_query($conn, $query);
    $row = $result -> fetch_assoc();
    $html = "
    <div class='product'>
        <div class='top-info'>
            <img class = 'thumbnail' src='" . $row["image"] ."'>
            <p class = 'product-name'>" . $row["name"] . "</p>
        </div>
        <div class='bottom-info'>
            <p class = 'price'>$" . number_format($row["price"],2) . "</p>
            <form method = 'post' class = 'buy-button'>
              <button class = 'buy-button' type = 'submit' name = 'product-id' value = " . $id ."><i class='fa fa-shopping-cart'></i></button>
            </form>
        </div>
</div>
";
    echo $html;
}

function createCartProductFromID($conn, $id) {
  $tempInfo = query($conn, "SELECT * FROM products WHERE id =
              ".$id);
  $tempInfo = $tempInfo -> fetch_assoc();
  $temp = new CartProduct($tempInfo["name"], $tempInfo["price"],
                         $tempInfo["image"], 1);
  return $temp;
}

function calculatePrice($cart){
  $total = 0.00;
  foreach ($cart as $product){
    $total += $product->getProperty("price") * 
      $product->getProperty("customerStock");
  }
  return number_format($total, 2);
}

function connect() {
  $host = "sql9.freemysqlhosting.net";
  $dbname = "sql9605786";
  $username = "sql9605786";
  $password = "HgUzgIya2P";
  
  $conn = mysqli_connect($host, $username, $password, $dbname);
  if (mysqli_connect_errno()) {
    die("connection error: " . mysqli_connect_errno());
  }

  return $conn;
}

?>