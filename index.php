<?php

//this line makes PHP behave in a more strict way
declare(strict_types=1);
ini_set('display_errors', (string)1);
ini_set('display_startup_errors', (string)1);
error_reporting(E_ALL);

function whatIsHappening()
{
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
    }

//your products with their price.
$products = [
    ['name' => 'Club Ham', 'price' => 3.20],
    ['name' => 'Club Cheese', 'price' => 3],
    ['name' => 'Club Cheese & Ham', 'price' => 4],
    ['name' => 'Club Chicken', 'price' => 4],
    ['name' => 'Club Salmon', 'price' => 5]
];

//if drinks is selected the href will turn into "?food=0". with the $_GET variable we can change the content of the array
if (isset($_GET['food']) && $_GET['food'] == 0) {
    $products = [
        ['name' => 'Cola', 'price' => 2],
        ['name' => 'Fanta', 'price' => 2],
        ['name' => 'Sprite', 'price' => 2],
        ['name' => 'Ice-tea', 'price' => 3],
    ];
}

//we are going to use session variables so we need to enable sessions
session_start();

$totalValue = 0;

//create variables
$email = $street = $number = $city = $zip = $order = "";

//If there is no cookie set already, we'll create it here
if (!isset($_COOKIE["ctotalvalue"])){
    setcookie("ctotalvalue", "0",time() + (86400 * 30), "/");
    header("Location: http://order-form.localhost");
    exit;
}

//because I used these in the form-view.php
if (isset($_SESSION["email"])){
    $email = $_SESSION["email"];
}
if (isset($_SESSION["street"])){
    $street = $_SESSION["street"];
}
if (isset($_SESSION["streetnumber"])){
    $number = $_SESSION["streetnumber"];
}
if (isset($_SESSION["city"])){
    $city = $_SESSION["city"];
}
if (isset($_SESSION["zipcode"])){
    $zip = $_SESSION["zipcode"];
}

//all of this happens when the user pushes the order button
if ($_SERVER["REQUEST_METHOD"] == "POST") {
//create error variables
    $emailErr = $streetErr = $numberErr = $cityErr = $zipErr = $orderErr = "";
    $order = [];

// validation, first check if it's empty. If it's empty it will create an error variable.
// if the error variable is set (isset) it will give a bootstrap warning on the page (form-view.php)
// this error variable will also be used at the end of this block of code to see if all errors are gone and if it can proceed
    if (empty($_POST["email"])) {
        $emailErr = "You didn't fill in the e-mail";
    }

    //if it's not empty we'll put the data of $_POST into a variable
    else {
        $email = test_input($_POST["email"]);
        //check for valid email address is next
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Please enter a valid e-mail address";
        }
        else {
            //only if it's a valid email (and it's not empty) it goes into $_SESSION
            $_SESSION["email"] = $email;
        }
    }

    //street
    if(empty($_POST["street"])){
        $streetErr = "You didn't fill in a street";
    }
    else {
        //only letters and white space allowed
        $street = test_input($_POST["street"]);
        if (!preg_match('/^[a-zA-Z\s]+$/', $street)){
          $streetErr = "only letters and white space allowed";
        }
        else {
            $_SESSION["street"] = $street;
        }
    }

    //streetnumber
    if (empty($_POST["streetnumber"])){
        $numberErr = "You didn't fill in a streetnumber";
    }
    else {
        $number = test_input ($_POST["streetnumber"]);
        //only numbers so if it's not numeric the user will get an error
        if (!is_numeric($number)){
            $numberErr = "Please enter a number";
        }
        else {
            $_SESSION["streetnumber"] = $number;
        }
    }

    //city
    if (empty($_POST["city"])){
        $cityErr = "You didn't fill in a city";
    }
    else {
        $city = test_input($_POST["city"]);
        if (!preg_match('/^[a-zA-Z\s]+$/', $city)){
            $cityErr = "only letters and white space allowed";
        }
        else {
            $_SESSION["city"] = $city;
        }
    }

    //zipcode
    if(empty($_POST["zipcode"])){
        $zipErr = "You didn't fill in a zipcode";
    }
    else {
        $zip = test_input($_POST["zipcode"]);
        if (!is_numeric($zip)){
            $zipErr = "Please enter a number";
        }
        else {
            $_SESSION["zipcode"] = $zip;
        }
    }

    //check if the user selected a product
    if(empty($_POST["products"])){
        $orderErr = "Please order something";
    }
    else {
            $order =  $_POST["products"];
            $_SESSION["order"] = $order;
    }

    // if there are no errors generated in the $POST session, we go further
    if (empty($emailErr) && empty($streetErr) && empty($numberErr) && empty($cityErr) && empty($zipErr) && empty($orderErr)){
        //first the user will get a message stating the order has been sent
        $success = "Your order has been sent";
        //the previous value of our cookie is set to our counter
        $totalValue = $_COOKIE["ctotalvalue"];
        //loop with a foreach over the selected products, the data of _POST[products will be used as an index (=key)
        foreach ($_POST["products"] as $key => $value){
            $totalValue += ($products[$key]['price']);
            }
        //if the user has selected express_delivery, the delivery time will shorten
        //and totalvalue will increase
        //and finally we will update our cookie with the totalvalue data
        if (!empty($_POST["express_delivery"])){
            $_SESSION["time"] = date('Y-m-d H:i:s', time() + 9900);
            $totalValue += 5;
            setcookie("ctotalvalue","$totalValue",time() + (86400 * 30), "/");

        }
        else {
            $_SESSION["time"] = date('Y-m-d H:i:s', time() + 14400);
            setcookie("ctotalvalue","$totalValue",time() + (86400 * 30), "/");
        }
        //go immediately to our page (reload) because the cookie has just been set (but not updated?)
        header("Location: http://order-form.localhost");
        exit;
    }

}

//function to edit the input
function test_input($data){
    $data = trim($data); //delete whitespace beginning
    $data = stripslashes($data); //unquote
    $data = htmlspecialchars($data); //convert special characters for HTML

    return $data;
}

require 'form-view.php';