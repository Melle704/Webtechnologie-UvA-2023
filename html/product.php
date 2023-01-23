<?php
include_once "include/common.php";
include_once "include/db.php";

session_start();

// ensure you can't reach the product page if you're not logged in
if (!isset($_SESSION["id"])) {
    header("Location: /index.php");
    exit;
}

$sql = "SELECT * FROM cards WHERE id=?";
$card = query_execute($db, $sql, "i", $_GET["id"])[0];

// Redirect to shop if page is reached without id
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(!isset($_GET["id"])) {
        header("Location: /shop.php");
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

<?php
$card_front = $card["image"];
$card_back = $card["back_image"];
$card_price = $card["normal_price"];
$foil_price = $card["foil_price"];
if ($card_front == NULL) {
    $card_front = "https://mtgcardsmith.com/view/cards_ip/1674397095190494.png?t=014335";
}
if ($card_back == NULL) {
    $card_back = "https://i.etsystatic.com/12913642/r/il/f6f5f7/1010631517/il_570xN.1010631517_c55s.jpg";
}
if ($card["normal_price"] == 0) {
    $card_price = "--";
}
if ($card["foil_price"] == 0) {
    $foil_price = "--";
}
?>

<div class="box box-row box-container">
        <h1>
            <?= $card["name"] ?>
            <!-- <span>€<?= $card_price ?></span> -->
        </h1>
        <br>
    <div id="product-image">
        <img src="<?= $card_front ?>" alt="<?= $card["name"] ?>"/>
        <img src="<?= $card_back ?>" alt="<?= $card["name"] ?>"/>
    </div>
    <div id="product-info">
        <!-- <h1>
            <?= $card["name"] ?>
            <span>€<?= $card_price ?></span>
        </h1> -->
    </div>
    <div id="product-purchase">

         <form method="post" action="<?php echo $_SERVER["REQUEST_URI"];?>" class="form">
            <fieldset>
                <span>Normal price: €<?= $card_price ?></span>
                <span>Foil price: €<?= $foil_price ?></span>
                <br>
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
    </div>
</div>

<?php include_once "footer.php"; ?>

</body>

</html>
