<?php
  require 'functions.php';
  $conn = connect();
  //Assignment requires at least 1 unit test case for 2 selected methods in each class
  //We'll be testing methods from the Cart and CartProduct classes
  //We'll also test the various checkValid functions

  $passedTests = 0;
  $totalTests = 0;

  function test($test, $ans){
    global $totalTests;
    if ($test != $ans){
      echo "<p>Failed test ". $totalTests + 1 . "</p>";
      echo "<p>Expected: $ans</p>";
      echo "<p>Given: $test</p><br>";
    }
    else{
      global $passedTests;
      $passedTests += 1;
    }
    $totalTests += 1;
  }
  
  //Cart Tests
  //testing calculatePrice()
  $ans1 = "190.05";
  $testCart = new Cart();
  $testCart->addItem(2);
  $testCart->addItem(2);
  $testCart->addItem(1);
  $testCart->addItem(3);
  $testCart->addItem(3);
  $test1 = $testCart->calculatePrice();
  test($test1, $ans1);

  $ans2 = "3,019.70";
  $testCart = new Cart();
  $testCart->addItem(12);
  $testCart->addItem(15);
  $testCart->addItem(10);
  $test2 = $testCart->calculatePrice();
  test($test2, $ans2);

  //testing checkCartStock
  $ans3 = false;
  $testCart = new Cart();
  $testCart->addItem(15);
  $testCart->addItem(15);
  $testCart->addItem(15);
  $testCart->addItem(15);
  $test3 = $testCart->checkCartStock($conn);
  test($test3, $ans3);

  //note: this tests depends on there being 3 gold frying pans in stock
  $ans4 = true;
  $testCart = new Cart();
  $testCart->addItem(15);
  $testCart->addItem(15);
  $testCart->addItem(15);
  $test4 = $testCart->checkCartStock($conn);
  test($test4, $ans4);

  //CartProduct Tests
  //testing getProperty
  $ans5 = "test 5";
  $testCartProduct = new CartProduct(5, "test 5", 1500, "test 5", 5);
  $test5 = $testCartProduct -> getProperty("image");
  test($test5, $ans5);

  //testing incrementStock
  $ans6 = 6;
  $testCartProduct = new CartProduct(5, "test 5", 1500, "test 5", 5);
  $testCartProduct -> incrementStock();
  $test6 = $testCartProduct -> getProperty("customerStock");
  test($test6, $ans6);


  //Regex tests
  //email tests
  $ans7 = true;
  $test7 = checkValidEmail("testman@gmail.coma");
  test($test7, $ans7);

  //this test will have the side effect of printing "invalid email"
  $ans8 = false;
  $test8 = checkValidEmail("testman@gmail.comas");
  test($test8, $ans8);

  //credit card tests
  $ans9 = true;
  $test9 = checkValidCreditCard("1234567890123456");
  test($test9, $ans9);

  //this test will have the side effect of printing "invalid credit card"
  $ans10 = false;
  $test10 = checkValidCreditCard("123456789012345s");
  test($test10, $ans10);


  echo "<h2>$passedTests out of $totalTests tests passed</h2>";
?>