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
</head>

<body>

<?php include_once "header.php"; ?>
<?php
include_once "include/common.php";
include_once "include/db.php";

$sql = "SELECT * FROM cards
        WHERE NOT layout='art_series' AND NOT layout='token' AND NOT layout='emblem'
        ORDER BY ID DESC LIMIT 50";
$single_sided_cards = query_execute($db, $sql);

$sql = "SELECT * FROM cards
        WHERE NOT layout='art_series' AND NOT layout='token' AND NOT layout='emblem'
        AND back_image IS NOT NULL
        ORDER BY ID DESC LIMIT 10";
$double_sided_cards = query_execute($db, $sql);

$cards = array_merge($single_sided_cards, $double_sided_cards);
shuffle($cards);
?>
<div class="box box-row box-container">
    <?php
    foreach ($cards as $card):
        $card_front = $card["image"];
        $card_back = $card["back_image"];
        $card_price = $card["normal_price"];
        $card_page = "/product.php?id=" . $card["id"];

        if (!$card_front) {
            $card_front = "https://mtgcardsmith.com/view/cards_ip/1674397095190494.png?t=014335";
        }

        if ($card["normal_price"] == 0) {
            $card_price = "--";
        }
    ?>
    <div class="box box-item">
        <div class="box-row">
            <div class="box-left item-name">
                <a href="product.php?id=<?= $card["id"] ?>"><?= $card["name"] ?></a>
            </div>
            <div class="box-right item-price">
                €<?= $card_price ?>
            </div>
        </div>

        <div class="box-row item-set">
            <?= $card["set_name"] ?>
        </div>

        <div class="box-row">
            <?php if (isset($card_back)): ?>
            <div class="box-card">
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
            <div class="box-card">
                <a href="<?= $card_page ?>">
                    <img src="<?= $card_front ?>" alt="<?= $card["name"] ?>">
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php include_once "footer.php"; ?>

</body>

</html>
