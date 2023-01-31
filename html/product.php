<?php
include_once "include/common.php";
include_once "include/db.php";

session_start();

$sql = "SELECT * FROM cards WHERE id=?";
$card = query_execute($db, $sql, "i", $_GET["id"])[0];

// Make sure the right part of the type line is used.
if (str_contains($card["type_line"], "planeswalker")) {
    $card_type = "planeswalker";
}
else if (!strrchr($card["type_line"], "—")) {
    $card_type = $card["type_line"];
}
else {
    $card_type = strrchr($card["type_line"], "—");
    $card_type = ltrim($card_type, '— ');
}

if (!strrchr($card_type, " ")) {
    $card_half_type = $card_type;
}
else {
    $card_half_type = strrchr($card_type, " ");
    $card_half_type = ltrim($card_half_type, ' ');
}

    // Suggested cards are determi  ned from multiple keywords.
    // If 7 cards arent found, a broader search is used.
$base_sql = "SELECT * FROM cards
        WHERE real_card='1' AND NOT layout='emblem'
        AND NOT layout='art_series' AND NOT layout='token'
        AND NOT name LIKE 'Substitute Card' AND NOT layout='planar'
        AND NOT name=\"{$card["name"]}\"";

// Search for the exact type line, color identity and cmc.
$suggest_sql = $base_sql;
$suggest_sql .= "AND type_line LIKE '%{$card["type_line"]}%'
                 AND color_identity='{$card["color_identity"]}'
                 AND cmc='{$card["cmc"]}'
                 ORDER BY id LIMIT 7";
$suggested_cards = query_execute_unsafe($db, $suggest_sql);
if (count($suggested_cards) < 7) {
    // Search for partial type line and exact color identity.
    $suggest_sql = $base_sql;
    $suggest_sql .= "AND type_line LIKE '%{$card_type}%'
                     AND color_identity='{$card["color_identity"]}'
                     ORDER BY id LIMIT 7";
    $suggested_cards = query_execute_unsafe($db, $suggest_sql);
}
if (count($suggested_cards) < 7) {
    // Search for single type and partial color identity.
    $suggest_sql = $base_sql;
    $suggest_sql .= "AND type_line LIKE '%{$card_half_type}%'
                     AND color_identity LIKE '%{$card["color_identity"]}%'
                     ORDER BY id LIMIT 7";
    $suggested_cards = query_execute_unsafe($db, $suggest_sql);
}

// Redirect to shop if page is reached without id
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if(!isset($_GET["id"])) {
        header("Location: /shop.php");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = array();
    }

    $amount = $_POST["amount"];
    $card_id = $_POST["id"];

    if (!isset($_SESSION["cart"][$card_id])) {
        $_SESSION["cart"][$card_id] = 0;
    }

    if (isset($amount) && isset($card_id) && $amount > 0) {
        $_SESSION["cart"][$card_id] += $amount;
    }

    header("Location: " . $_SERVER["REQUEST_URI"], true, 303);
}

$formats = array("standard", "pioneer", "modern", "legacy",
                             "vintage", "pauper", "commander");

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
        <div class="left-column">
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
            <br>
        </div>
        <div class="right-column">
            <div id="product-info">
                <table class="info-table">
                <?php if (isset($card["mana_cost"]) and $card["mana_cost"] != ""): ?>
                <tr>
                    <th>mana cost</th>
                    <th><?= $card["mana_cost"] ?></th>
                </tr>
                <?php endif ?>
                <tr>
                    <th>type line</th>
                    <th><?= $card["type_line"] ?></th>
                </tr>
                <?php if (isset($card["oracle_text"])): ?>
                <tr>
                    <th>oracle text</th>
                    <th><?= nl2br($card["oracle_text"]) ?></th>
                </tr>
                <?php endif ?>
                <?php if (isset($card["flavor_text"])): ?>
                <tr>
                    <th>flavor text</th>
                    <th><?= nl2br($card["flavor_text"]) ?></th>
                </tr>
                <?php endif ?>
                <?php if (isset($card["power"])): ?>
                <tr>
                    <th>power/toughness</th>
                    <th><?= $card["power"] ?>/<?= $card["toughness"] ?></th>
                </tr>
                <?php endif ?>
                <?php if (isset($card["loyalty"])): ?>
                    <tr>
                        <th>loyalty</th>
                        <th><?= $card["loyalty"] ?></th>
                    </tr>
                    <?php endif ?>
                    <th>legal in</th>
                    <th>
                    <?php
                    $counter = 0;
                    foreach ($formats as $format) {
                        if ($card["{$format}_legal"] == "legal") {
                            if ($counter != 0) {
                                echo " - ";
                            }
                            echo $format;
                            $counter++;
                        }
                    }
                    ?>
                </th>
                <tr>
                    <th>set</th>
                    <th><?= $card["set_name"] ?></th>
                </tr>
                <tr>
                    <th>artist</th>
                    <th><?= $card["artist"] ?></th>
                </tr>
            </table>
        </div>
            <div id="product-purchase">
                <form method="post" action="/product.php?id=<?= $_GET["id"] ?>" class="form">
                    <fieldset>
                        <legend>
                            Add item(s) to cart
                        </legend>
                        <span>Normal price: <?= format_eur($card_price) ?></span>
                        <span>Foil price: <?= format_eur($foil_price) ?></span>
                        <br>
                        <label for=count>Amount</label>
                        <input id="amount" type="number" name="amount" value="1" min="1" max="50">
                        <!-- <br> -->
                        <input type="hidden" id="id" name="id" value="<?= $_GET["id"] ?>">
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
</div>


<div class="box">
    <div class="box-row box-light">
        <b>Similar cards</b>
    </div>
    <div class="box-row popular-cards">
        <?php
        foreach ($suggested_cards as $suggest_card):
            $card_front = $suggest_card["image"];
            $card_back = $suggest_card["back_image"];
            $card_page = "/product.php?id=" . $suggest_card["id"];

            if (!$card_front) {
                $card_front = "/img/no_image_available.png";
            }
        ?>
        <?php if (isset($card_back)): ?>
                <div class="box-card-small">
                    <div class="box-card-flip">
                        <div class="box-card-front">
                            <a href="<?= $card_page ?>">
                                <img src="<?= $card_front ?>" alt="<?= $suggest_card["name"] ?>">
                            </a>
                        </div>
                        <div class="box-card-back">
                            <a href="<?= $card_page ?>">
                                <img src="<?= $card_back ?>" alt="<?= $suggest_card["name"] ?>">
                            </a>
                        </div>
                    </div>
                </div>
        <?php else: ?>
                <div class="box-card-small">
                    <a href="<?= $card_page ?>">
                        <img src="<?= $card_front ?>" alt="<?= $suggest_card["name"] ?>">
                    </a>
                </div>
        <?php endif; ?>
        <?php endforeach ?>
        <?php if ($suggested_cards == NULL): ?>
            <div class="box-card-small">
                <img src="/img/no_cards_found.png" alt="no cards found">
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once "footer.php"; ?>

</body>

</html>
