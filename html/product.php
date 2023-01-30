<?php
include_once "include/common.php";
include_once "include/db.php";

session_start();

$sql = "SELECT * FROM cards WHERE id=?";
$card = query_execute($db, $sql, "i", $_GET["id"])[0];

// Redirect to shop if page is reached without id
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(!isset($_GET["id"])) {
        header("Location: /shop.php");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["id"])) {
    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = array();
    }

    $amount = $_POST["amount"];
    $foil = $_POST["foil"];
    $card_id = $_POST["id"];
    $card_id .= $foil ? "f" : "";

    // Check if foil/non-foil version of this card exists
    if ($card["normal_price"] == 0 && !$foil) {
        reload_err("Non-foil version of this card is not for sale");
    } elseif ($card["foil_price"] == 0 && $foil) {
        reload_err("Foil version of this card is not for sale");
    }

    if (!isset($_SESSION["cart"][$card_id])) {
        $_SESSION["cart"][$card_id] = 0;
    }

    if (isset($amount) && isset($card_id) && $amount > 0) {
        $_SESSION["cart"][$card_id] += $amount;
    }

    header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
}

$card_front = $card["image"];
$card_back  = $card["back_image"];
$card_price = $card["normal_price"];
$foil_price = $card["foil_price"];

if (!$card_front) {
    $card_front = "/img/no_image_available.png";
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTG | Shop</title>

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<link rel="stylesheet" type="text/css" href="/css/form.css">
	<link rel="stylesheet" type="text/css" href="/css/shop.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</head>

<body>
<?php include_once "header.php"; ?>

<?php include_once "include/errors.php"; ?>

<div class="box">
    <div class="box-row box-light">
        <h1>
            <?= $card["name"] ?>
        </h1>
    </div>
    <div class="box-row">
        <br>
<?php if (isset($card_back)): ?>
            <div class="box-card-large">
                <div class="box-card-flip">
                    <div class="box-card-front">
                        <a href="<?= $card_page ?>">
                            <img src="<?= $card_front ?>" alt="<?= $card["name"] ?>">
                        </a>
                    </div>
                    <div class="box-card-back">
                        <a href="<?= $card_page ?>">
                            <img src="<?= $card_back ?>" alt="<?= $card["name"] ?>">
                        </a>
                    </div>
                </div>
            </div>
<?php else: ?>
            <div class="box-card-large">
                <a href="<?= $card_page ?>">
                    <img src="<?= $card_front ?>" alt="<?= $card["name"] ?>">
                </a>
            </div>
<?php endif; ?>
        <div id="product-purchase">
            <form method="post" action="/product.php?id=<?= $_GET["id"] ?>" class="form">
            <form method="post" action="/product.php?id=<?= $_GET["id"] ?>" class="form">
                <fieldset>
                    <legend>
                        Add item(s) to cart
                    </legend>
                    <span>Normal price: <?= format_eur($card_price) ?></span>
                    <span>Foil price: <?= format_eur($foil_price) ?></span>
                    <?php if (isset($_SESSION["id"])): ?>
                    <label>
                        <b>Amount</b>
                        <input type="number" name="amount" value="1" min="1" max="50">
                    </label>
                    <label>
                        <b>Foil</b>
                        <input id="foil" type="checkbox" name="foil">
                    </label>
                    <br><br><br>
                    <input type="hidden" id="id" name="id" value="<?= $_GET["id"] ?>">
                    <input type="submit" value="Add to cart">
                    <?php else: ?>
                    Please <a href="login.php">login</a> or <a href="register.php">register</a> to add this item to your cart.
                    <?php endif; ?>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>

</body>

</html>
