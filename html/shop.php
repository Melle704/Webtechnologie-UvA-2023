<?php
// ensure you can't reach the shop page if you're not logged in
if (!isset($_SESSION["id"])) {
    header("Location: /index.php");
    exit;
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> 
</head>

<body>

<?php include_once "header.php"; ?>

<div class="box box-row box-container">
    <?php
    include_once "include/common.php";
    include_once "include/db.php";

    $sql = "SELECT * FROM products LIMIT 21";
    $products = query_execute($db, $sql);

    foreach ($products as $product):
    ?>
        <div class="box box-item">
            <h2>
                <a href="product.php?id=<?= $product["id"] ?>"><?= $product["name"] ?></a>
                <span class="box-right">
                    €<?= $product["price"] ?>
                </span>
            </h2>
            <img src="https://gatherer.wizards.com/Handlers/Image.ashx?type=card&multiverseid=580583" alt="<?= $product["name"] ?>"/>
        </div>
    <?php endforeach; ?>
</div>

<?php include_once "footer.php"; ?>

</body>

</html>
