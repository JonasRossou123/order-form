
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" type="text/css"
          rel="stylesheet"/>
    <title>Order food & drinks</title>
</head>
<body>

<div class="container">
    <h1>Order food in restaurant "the Personal Ham Processors"</h1>
    <nav>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link active" href="?food=1">Order food</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?food=0">Order drinks</a>
            </li>
        </ul>
    </nav>
    <form method="post">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="email">E-mail:</label>
                <input type="text" id="email" name="email" class="form-control" value="<?php if (isset($_SESSION["email"])){echo $email;}?>"/>
                <?php if (!empty($emailErr)){?>
                    <div class="alert alert-warning" role="alert">
                        <p><?php echo $emailErr ?></p>
                    </div>
                <?php } ?>
            </div>
            <div></div>
        </div>

        <fieldset>
            <legend>Address</legend>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="street">Street:</label>
                    <input type="text" name="street" id="street" class="form-control" value="<?php if (isset($_SESSION["street"])){echo $street;}?>"/>
                    <?php if (!empty($streetErr)){?>
                        <div class="alert alert-warning" role="alert">
                            <p><?php echo $streetErr ?></p>
                        </div>
                    <?php } ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="streetnumber">Street number:</label>
                    <input type="text" id="streetnumber" name="streetnumber" class="form-control" value="<?php if (isset($_SESSION["streetnumber"])){echo $number;}?>">
                    <?php if (!empty($numberErr)){?>
                        <div class="alert alert-warning" role="alert">
                            <p><?php echo $numberErr ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" class="form-control" value="<?php if (isset($_SESSION["city"])){echo $city;}?>"/>
                    <?php if (!empty($cityErr)){?>
                        <div class="alert alert-warning" role="alert">
                            <p><?php echo $cityErr ?></p>
                        </div>
                    <?php } ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="zipcode">Zipcode</label>
                    <input type="text" id="zipcode" name="zipcode" class="form-control" value="<?php if (isset($_SESSION["zipcode"])){echo $zip;}?>"/>
                    <?php if (!empty($zipErr)){?>
                        <div class="alert alert-warning" role="alert">
                            <p><?php echo $zipErr ?></p>
                        </div>
                    <?php }?>
                </div>
            </div>
            <?php if (isset($success)){?>
            <div class="alert alert-success" role="alert">
                <?php echo $success ?>
            </div>
            <?php }?>
        </fieldset>

        <fieldset>
            <legend>Products</legend>
            <?php foreach ($products AS $i => $product): ?>
                <label>
                    <input type="checkbox" value="1" name="products[<?php echo $i ?>]"/> <?php echo $product['name'] ?> -
                    &euro; <?php echo number_format($product['price'], 2) ?></label><br />
            <?php endforeach; ?>
        </fieldset>
        <?php if (!empty($orderErr)){?>
            <div class="alert alert-warning" role="alert">
                <p><?php echo $orderErr ?></p>
            </div>
        <?php }?>


        <label>
            <input type="checkbox" name="express_delivery" value="5" />
            Express delivery (+ 5 EUR)
        </label>

        <button type="submit" class="btn btn-primary">Order!</button>
    </form>

    <footer>You already ordered <strong>&euro; <?php if (isset($_COOKIE["ctotalvalue"])) {echo $_COOKIE["ctotalvalue"];} else {echo "0";} ?></strong> in food and drinks.
    <br />
        Delivery time will be around <?php echo $time;?>
    </footer>

</div>



<?php whatIsHappening()?>

<style>
    footer {
        text-align: center;
    }
</style>
</body>
</html>