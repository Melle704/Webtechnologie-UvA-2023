<?php
session_start();

if ($_GET["result"] == "success") {
    unset($_SESSION["cart"]);
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTG | Checkout</title>

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<link rel="stylesheet" type="text/css" href="/css/form.css">
	<link rel="stylesheet" type="text/css" href="/css/shop.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</head>

<body>

<?php include_once "header.php"; ?>
<?php include_once "include/errors.php"; ?>

<div class="box box-row">
    <?php if ($_GET["result"] == "success"): ?>
    <h1>Purchase succesful!</h1>
    <p>Thanks for choosing us for your deck-shopping needs.</p>
    <?php elseif ($_GET["result"] == "failure"): ?>
    <h1>Purchase failed.</h1>
    <p>Unfortunately your purchase did not go through.</p>
    <p>
        Your cart is still saved, so feel free to
        <a href="checkout.php">try again</a>.
    </p>
    <?php
    else:
        // Return to home when reached without result
        header("Location: index.php");
        exit;
    endif;
    ?>
</div>

<?php include_once "footer.php"; ?>

</body>

</html>
