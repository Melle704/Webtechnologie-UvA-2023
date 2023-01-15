<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTG | Shop</title>

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> 

    <style>
        * {
            box-sizing: border-box;
        }

        img {
            display: block;
            max-width: 100%;
        }

        .box-container {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }

        #product-image {
            flex-grow: 1;
        }

        #product-info {
            flex-grow: 6;
        }

        #product-purchase {
            flex-grow: 1;
        }

        .box-item {
            padding: 16px;
        }

        h1 {
            margin-top: 8px;
        }

        img {
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>

<body>
    <div class="box">
        <div class="box-head">
            <div class="box-row box-title">
                <span>MAGIC</span> THE GATHERING
            </div>
            <div class="box-row box-light">
                <div class="box-left">
                    <ul>
                        <li><a href="index.php">Index</a></li>
                        <li><a href="cards.php">Cards</a></li>
                    </ul>
                </div>
                <div class="box-right">
                    <ul>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-row box-container">
        <div id="product-image">
            <img src="https://gatherer.wizards.com/Handlers/Image.ashx?type=card&multiverseid=580583" alt="Example image"/>
        </div>
        <div id="product-info">
            <h1>
                Test product
                <span>€3,-</span>
            </h1>
        </div>
        <div id="product-purchase">
             <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" class="form">
                <fieldset>
                    <legend>
                        Add item(s) to cart
                    </legend>
                    <label for=count>Amount</label>
                    <input id="amount" type="number" name="amount" value="1" min="1" max="50">
                    <br/>
                    <input type="hidden" id="product_id" name="product_id" value="<?= $_GET["id"] ?>" />
                    <input type="submit" value="Add to cart">
                </fieldset>
             </form>

            <?php
            session_start();

            // Redirect to shop if page is reached without id
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                if(!isset($_GET["id"])) {
                    header("Location: /shop.php");
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $amount = $_POST["amount"];
                $product_id = $_POST["product_id"];

                if (empty($amount) || empty($product_id)) {
                    header("Location: " . $_SERVER["PHP_SELF"] . "?id=" . $product_id, true, 303);
                    die;
                }

                $_SESSION["cart"][$product_id] += $amount;

                header("Location: " . $_SERVER["PHP_SELF"] . "?id=" . $product_id, true, 303);
                exit;
            }
            var_dump($_SESSION["cart"]);
            ?>
        </div>
    </div>

</body>

</html>
