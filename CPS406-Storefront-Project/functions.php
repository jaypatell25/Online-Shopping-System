<?php

//An array of CartProducts
class Cart{
  public $items;

  function __construct(){
    $this->items = array();
  }

  //if item is already in cart, increment its quantity by 1. Else, initialize a new
  //CartProduct.
  function addItem($itemID){
    if (isset($this->items[$itemID])){
      $this->items[$itemID]->incrementStock();
    }
    else {
      $conn = connect();
      $temp = new CartProduct($conn, $itemID, NULL, NULL, NULL);
      $this->items[$itemID] = $temp;
    }
  }

  
  function getCart(){
    return $this->items;
  }

  function deleteItem($itemID){
    unset($this->items[$itemID]);
  }

  //returns overall price of cart
  function calculatePrice(){
    $total = 0.00;
    foreach ($this->items as $product){
      $total += ($product->getProperty("price") * 
        $product->getProperty("customerStock"));
    }
    return number_format($total, 2);
  }

  //checks if every item in the cart is in stock
  function checkCartStock($conn){
    $cart = $this->items;
    if ($cart == null){
      return false;
    }
    foreach ($cart as $product){
      $cartID = $product->getProperty("id");
      $cartStock = $product->getProperty("customerStock");
      $databaseStock = query($conn, "SELECT * FROM products WHERE id = " 
                             . $cartID);
      $databaseStock = $databaseStock -> fetch_assoc();
      if ($cartStock > $databaseStock['stock']) {
        return false;
      }
    }
    return true;
  }
}


//this class defines a product in the customer's cart
class CartProduct {
  public $name;
  public $price;
  public $image;
  public $customerStock;
  public $id;

  //has an alternate constructor if only 2 fields filled are $id and $name
  function __construct($id, $name, $price, $image, $customerStock){
    if (gettype($price) == "NULL"){
      $this->constructFromID($id, $name);
    }
    else{
      $this->id = $id;
      $this->name = $name;
      $this->price = $price;
      $this->image = $image;
      $this->customerStock = $customerStock;
    }
  }

  //creates a CartProduct from the information in the product database
  function constructFromID($conn, $id){
    $tempInfo = query($conn, "SELECT * FROM products WHERE id =
                ".$id);
    $tempInfo = $tempInfo -> fetch_assoc();
    
    $this->id = $id;
    $this->name = $tempInfo["name"];
    $this->price = $tempInfo["price"];
    $this->image = $tempInfo["image"];
    $this->customerStock = 1;
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
      case "id":
        $this->id = $id;
        break;
      default:
        echo "error when setting variable";
    }
  }

  //use when customer adds existing item to cart
  function incrementStock() {
    $this->customerStock += 1;
  }

  //returns a property of the CartProduct object
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
      case "id":
        return $this->id;
      default:
        echo "error when getting variable";
    }
  }
  
}

//does a MySQL query
function query($conn, $query) {
    $result = mysqli_query($conn, $query) or die(mysql_error());
    return $result;
}

//used in index.php, creates the product pages from a product id
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
            <div class='break'></div>
            <p class = 'stock'>Stock:" . $row['stock'] ." </p>
        </div>
</div>
";
    echo $html;
}

//takes in a product ID and converts it into a CartProduct Object
function createCartProductFromID($conn, $id) {
  $tempInfo = query($conn, "SELECT * FROM products WHERE id =
              ".$id);
  $tempInfo = $tempInfo -> fetch_assoc();
  $temp = new CartProduct($id, $tempInfo["name"], $tempInfo["price"],
                         $tempInfo["image"], 1);
  return $temp;
}


//creates a table with all the items in cart, a delete button for each item, and the total price
function createCheckoutTableHTML($cart){
  echo "
  <table>
    <tr>
      <th>Product</th>
      <th>Amount</th>
      <th>Price</th>
    </tr>
  ";
  foreach ($cart->getCart() as $product){
    $image = $product->getProperty("image");
    $customerStock = $product->getProperty("customerStock");
    $price = $product->getProperty("price");
    $name = $product->getProperty("name");
    $id = $product->getProperty("id");
    echo "
      <tr>
        <td><img src='".$image."'></td>
        <td>".$customerStock."</td>
        <td>$".number_format($customerStock*$price,2)."</td>
        <td>
            <form method = 'post'>
              <button type='submit' name='deleteButton' value =".$id.">
                delete</button>
            </form>
        </td>
      </tr>
    ";
  }
  echo "
    <tr> <td></td><td></td>
      <td>$". $cart->calculatePrice() ."</td>
  </table>";
}


//connects to the database
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


function checkValidName($name){
  if ($name == null){
    echo "<p class='error-text'>invalid name</p>";
    return false;
  }
  return true;
}

function checkValidAddress($address){
  if ($address == null){
    echo "<p class='error-text'>invalid address</p>";
    return false;
  }
  return true;
}

//regex matches any alphanumeric string followed by @ followed by any
//alphabetic string followed by . followed by 2-4 alphabetic characters
function checkValidEmail($email){
  if (preg_match("/^[A-z0-9]+@[A-z]+\.[A-z]{2,4}$/", $email) == 0){
    echo "<p class='error-text'>invalid email</p>";
    return false;
  }
  return true;
}

//regex matches all strings that consist of 16 digits that can optionally 
//be spaced every 4 characters with dashes or spaces
function checkValidCreditCard($creditCard){
  if (preg_match("/^([0-9]{4}[\s-]?){3}([0-9]{4})$/", $creditCard) == 0){
    echo "<p class='error-text'>invalid credit card</p>";
    return false;
  }
  return true;
}

function checkValidSupportRequest($request){
  if ($request == null){
    echo "<p class='error-text'>Empty support request detected</p>";
    return false;
  }
  return true;
}

//yes i know you can make this 3 lines, i just like readability
function checkCheckoutForms($name, $address, $email, $creditCard){
  if (!checkValidName($name) || !checkValidAddress($address) || !checkValidEmail($email) ||
      !checkValidCreditCard($creditCard)){
    return false;
  }
  
  return true;
}

function checkSupportForms($name, $email, $form){
  if (!checkValidName($name) || !checkValidEmail($email) ||
      !checkValidSupportRequest($form)){
    return false;
  }
  return true;
}

//in orders database, add order
function addOrderToDatabase($conn, $date, $cart, $name, $address, $email){
  $insert = <<<END
    INSERT INTO orders (dateShipped, cart, name, address, email) 
    VALUES ("$date", "$cart", "$name", "$address", "$email");
  END;
  query($conn, $insert);
}

//decreases stock after customer buys something
function updateProductsCheckout($conn, $cart){
  foreach ($cart->getCart() as $product){
    $id = $product->getProperty("id");
    $stock = $product->getProperty("customerStock");
    $decreaseStock = <<<END
      UPDATE products SET stock = stock - $stock
      WHERE id = $id
    END;
    query($conn, $decreaseStock);
  }
}

//adds a valid order to the orders database and decreases item stock
function placeOrder($conn, $cart, $fullname, $shipping_address, $email){
  date_default_timezone_set('Canada/Eastern');
  $date = date('Y-m-d H:i:s');
  $databaseCart = print_r(cartToDatabaseCart($cart), true);

  addOrderToDatabase($conn, $date, $databaseCart, $fullname, $shipping_address,
                    $email);

  updateProductsCheckout($conn, $cart);

  $orderID = query($conn, "SELECT LAST_INSERT_ID();");
  $orderID = $orderID -> fetch_assoc();
  return $orderID['LAST_INSERT_ID()'];
}

function addSupportRequestToDatabase($conn, $name, $email, $request){
  $insert = <<<END
    INSERT INTO support (name, email, request) 
    VALUES ("$name", "$email", "$request");
  END;
  query($conn, $insert);
}

//converts the cart object into an associative array where key = id, value = customerStock
//This lowers the size of the database field
function cartToDatabaseCart($cart){
  $ans = array();
  foreach ($cart->getCart() as $product){
    $id = $product -> getProperty("id");
    $stock = $product -> getProperty("customerStock");
    $ans[$id] = $stock;
  }
  return serialize($ans);
}

//used in viewDatabase.php to visualize the orders database
function createOrdersDatabaseHTML($conn){
  echo "
  <table class = 'database-table'>
    <tr>
      <th>id</th>
      <th>dateShipped</th>
      <th>name</th>
      <th>status</th>
      <th>cart</th>
      <th>address</th>
      <th>email</th>
    </tr>
  ";
  $result = query($conn, 'SELECT * FROM orders');
  while ($row = $result->fetch_assoc()){
    $id = $row['id'];
    $dateShipped = $row['dateShipped'];
    $name = $row['name'];
    $status = $row['status'];
    $cart = $row['cart'];
    $address = $row['address'];
    $email = $row['email'];
    echo "
      <tr>
        <td>$id</td>
        <td>$dateShipped</td>
        <td>$name</td>
        <td>$status</td>
        <td>$cart</td>
        <td>$address</td>
        <td>$email</td>
      </tr>
    ";
  }
  echo "</table>";
}

function createProductsDatabaseHTML($conn){
  echo "
  <table class = 'database-table'>
    <tr>
      <th>id</th>
      <th>name</th>
      <th>price</th>
      <th>image</th>
      <th>stock</th>
    </tr>
  ";
  $result = query($conn, 'SELECT * FROM products');
  while ($row = $result->fetch_assoc()){
    $id = $row['id'];
    $name = $row['name'];
    $price = $row['price'];
    $image = $row['image'];
    $stock = $row['stock'];
    echo "
      <tr>
        <td>$id</td>
        <td>$name</td>
        <td>$price</td>
        <td><img src='$image'>$image</td>
        <td>$stock</td>
      </tr>
    ";
  }
  echo "</table>";
}

function createSupportDatabaseHTML($conn){
  echo "
  <table class = 'database-table'>
    <tr>
      <th>id</th>
      <th>name</th>
      <th>email</th>
      <th>request</th>
    </tr>
  ";
  $result = query($conn, 'SELECT * FROM support');
  while ($row = $result->fetch_assoc()){
    $id = $row['id'];
    $name = $row['name'];
    $email = $row['email'];
    $request = $row['request'];
    echo "
      <tr>
        <td>$id</td>
        <td>$name</td>
        <td>$email</td>
        <td>$request</td>
      </tr>
    ";
  }
  echo "</table>";
}

function searchOrder($conn, $id){
  $query = "SELECT * FROM orders WHERE id = $id";
  $target = query($conn, $query);
  if (mysqli_num_rows($target) == 0){
    echo "<p class='error-text'>Order ID not found in database</p>";
    return false;
  }
  $target = $target->fetch_assoc();
  $name = $target['name'];
  $cart = unserialize($target['cart']);
  echo "<p>$name's Order";
  echo "
  <table>
    <tr>
      <th>Name</th>
      <th>Product</th>
      <th>Quantity</th>
    <tr>";
  foreach ($cart as $productID => $quantity){
    $getProduct = "SELECT * FROM products WHERE id = $productID";
    $product = query($conn, $getProduct)->fetch_assoc();
    $image = $product['image'];
    $name = $product['name'];
    echo "<tr>
            <th><p>$name</p></th>
            <th><img src='$image'></th>
            <th><p>$quantity</p></th>
          </tr>";
  }

  return true;
}

?>