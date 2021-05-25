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

$email = $street = $number = $city = $zip = $order = "";

if (!isset($_COOKIE["ctotalvalue"])){
    setcookie("ctotalvalue", "0",time() + (86400 * 30), "/");
    header("Location: http://order-form.localhost");
    exit;
}

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





//validate that the field e-mail is filled in and a valid e-mail address once the order!button is pushed



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $emailErr = $streetErr = $numberErr = $cityErr = $zipErr = $orderErr = "";
    $order = [];


    if (empty($_POST["email"])) {
        $emailErr = "You didn't fill in the e-mail";
    }
    else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Please enter a valid e-mail address";
        }
        else {
            $_SESSION["email"] = $email;
        }
    }

    if(empty($_POST["street"])){
        $streetErr = "You didn't fill in a street";
    }
    else {
        $street = test_input($_POST["street"]);
        if (!preg_match('/^[a-zA-Z\s]+$/', $street)){
          $streetErr = "only letters and white space allowed";
        }
        else {
            $_SESSION["street"] = $street;
        }
    }

    if (empty($_POST["streetnumber"])){
        $numberErr = "You didn't fill in a streetnumber";
    }
    else {
        $number = test_input ($_POST["streetnumber"]);
        if (!is_numeric($number)){
            $numberErr = "Please enter a number";
        }
        else {
            $_SESSION["streetnumber"] = $number;
        }
    }

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


    if(empty($_POST["products"])){
        $orderErr = "Please order something";
    }
    else {
            $order =  $_POST["products"];
            $_SESSION["order"] = $order;


    }

    if (empty($emailErr) && empty($streetErr) && empty($numberErr) && empty($cityErr) && empty($zipErr) && empty($orderErr)){
        $success = "Your order has been sent";
        $totalValue = $_COOKIE["ctotalvalue"];
        foreach ($_POST["products"] as $key => $value){
            $totalValue += ($products[$key]['price']);
            }
        if (!empty($_POST["express_delivery"])){
            $_SESSION["time"] = date('Y-m-d H:i:s', time() + 9900);
            $totalValue += 5;
            setcookie("ctotalvalue","$totalValue",time() + (86400 * 30), "/");

        }
        else {
            $_SESSION["time"] = date('Y-m-d H:i:s', time() + 14400);
            setcookie("ctotalvalue","$totalValue",time() + (86400 * 30), "/");
        }
        header("Location: http://order-form.localhost");
        exit;
    }

}




function test_input($data){
    $data = trim($data); //delete whitespace beginning
    $data = stripslashes($data); //unquote
    $data = htmlspecialchars($data); //convert special characters for HTML

    return $data;
}

require 'form-view.php';