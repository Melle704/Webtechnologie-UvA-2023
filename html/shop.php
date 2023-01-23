<?php

session_start();

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
WHERE
<body>

<?php include_once "header.php"; ?>

<div class="box box-row box-container">
    <?php
    include_once "include/common.php";
    include_once "include/db.php";

    $sql = "SELECT * FROM cards WHERE layout='modal_dfc' AND NOT layout='token' AND NOT layout='emblem' ORDER BY name LIMIT 60";
    $cards = query_execute($db, $sql);

    foreach ($cards as $card):
        $card_front = $card["image"];
        $card_back = $card["back_image"];
        $card_price = $card["normal_price"];
        if ($card_front == NULL) {
            $card_front = "https://mtgcardsmith.com/view/cards_ip/1674397095190494.png?t=014335";
        }
        if ($card_back == NULL) {
            $card_back = "https://static.wikia.nocookie.net/mtgsalvation_gamepedia/images/f/f8/Magic_card_back.jpg/revision/latest?cb=20140813141013";
        }
        if ($card["normal_price"] == 0) {
            $card_price = "--";
        }
    ?>
        <div class="box box-item">
            <h2>
                <a href="product.php?id=<?= $card["id"] ?>"><?= $card["name"] ?></a>
                <span class="box-right">
                    €<?= $card_price ?>
                </span>
            </h2>
            <img src="<?= $card_front ?>" alt="<?= $card["name"] ?>"/>
            <h2>
                <center>
                    <?= $card["set_name"] ?>
                </center>
            </h2>
        </div>
    <?php endforeach; ?>
</div>

<?php include_once "footer.php"; ?>

</body>

</html>
