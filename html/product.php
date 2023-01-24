<?php
include_once "include/common.php";
include_once "include/db.php";

session_start();

// ensure you can't reach the product page if you're not logged in
if (!isset($_SESSION["id"])) {
    header("Location: /index.php");
    exit;
}

$sql = "SELECT * FROM products WHERE id=?";
$product = query_execute($db, $sql, "i", $_GET["id"])[0];

// Redirect to shop if page is reached without id
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(!isset($_GET["id"])) {
        header("Location: /shop.php");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST["amount"];
    $product_id = $_POST["product_id"];

    if (isset($amount) && isset($product_id)) {
        $_SESSION["cart"][$product_id] += $amount;
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

<div class="box box-row box-container">
    <div id="product-image">
        <img src="https://gatherer.wizards.com/Handlers/Image.ashx?type=card&multiverseid=580583" alt="<?= $product["name"] ?>"/>
    </div>
    <div id="product-info">
        <h1>
            <?= $product["name"] ?>
            <span>€<?= $product["price"] ?></span>
        </h1>
    </div>
    <div id="product-purchase">
         <form method="post" action="<?php echo $_SERVER["REQUEST_URI"];?>" class="form">
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
    </div>
</div>

<?php include_once "footer.php"; ?>

</body>

</html>
