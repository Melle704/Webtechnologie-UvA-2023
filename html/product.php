<?php
include_once "include/common.php";
include_once "include/db.php";

session_start();

$sql = "SELECT * FROM cards WHERE id=?";
$card = query_execute($db, $sql, "i", $_GET["id"])[0];

// Redirect to shop if page is reached without id
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(!isset($_GET["id"])) {
        header("Location: /database.php");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST["amount"];
    $card_id = $_POST["id"];

    if (isset($amount) && isset($card_id)) {
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

if ($card["normal_price"] == 0) {
    $card_price = "--";
}

if ($card["foil_price"] == 0) {
    $foil_price = "--";
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
                <fieldset>
                    <legend>
                        Add item(s) to cart
                    </legend>
                    <span>Normal price: €<?= $card_price ?></span>
                    <span>Foil price: €<?= $foil_price ?></span>
                    <br>
                    <label for=count>Amount</label>
                    <input id="amount" type="number" name="amount" value="1" min="1" max="50">
                    <br>
                    <input type="hidden" id="product_id" name="product_id" value="<?= $_GET["id"] ?>">
                    <?php if (isset($_SESSION["id"])): ?>
                    <input type="submit" value="Add to cart">
                    <?php else: ?>
                    <a href="/register.php">
                        <input type="button" value="Add to cart">
                    </a>
                    <?php endif; ?>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>

</body>

</html>
