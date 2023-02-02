<?php

include_once "include/common.php";
include_once "include/db.php";

error_reporting(0);

$cards_per_page = 60;
$page_offset = 0;
$page = 1;

// if there is a page specified
if (isset($_GET["page"])) {
    // reload the page without a page specified if the page isn't a number
    if (!is_numeric($_GET["page"])) {
        header("Location: /shop");
    }

    $page = intval($_GET["page"]);

    // reload the page without a page specified if the page number is invalid
    if ($page < 1) {
        header("Location: /shop");
    }

    $page_offset = ($page - 1) * $cards_per_page;
}

$sql = "SELECT * FROM cards
        WHERE real_card='1'
        AND NOT layout='art_series'
        AND NOT layout='token'
        AND NOT layout='emblem'
        AND NOT layout='planar'
        AND NOT type_line LIKE '%card%'";


if (!empty($_GET["card_name"])) {
    $card_name = mysqli_real_escape_string($db, $_GET["card_name"]);
    $sql_search .= " AND name LIKE '%$card_name%'";
}
if (!empty($_GET["oracle_text"])) {
    $oracle_text = mysqli_real_escape_string($db, $_GET["oracle_text"]);
    $sql_search .= " AND oracle_text LIKE '%$oracle_text%'";
}
if (!empty($_GET["card_type"])) {
    $card_type = mysqli_real_escape_string($db, $_GET["card_type"]);
    $sql_search .= " AND type_line LIKE '%$card_type%'";
}

if (!empty($_GET["flavor_text"])) {
    $flavor_text = mysqli_real_escape_string($db, $_GET["flavor_text"]);
    $sql_search .= " AND flavor_text LIKE '%$flavor_text%'";
}

if (!empty($_GET["artist"])) {
    $artist = mysqli_real_escape_string($db, $_GET["artist"]);
    $sql_search .= " AND artist LIKE '%$artist%'";
}

if (!empty($_GET["set"])) {
    $set = mysqli_real_escape_string($db, $_GET["set"]);
    $sql_search .= " AND (set_name LIKE '%$set%' OR set_code LIKE '%$set%')";
}

if (isset($_GET["white"])) {
    if ($_GET["color_type"] == "excluding") {
        $sql_search .= " AND NOT color_identity LIKE '%W%'";
    }
    else {
        $sql_search .= " AND color_identity LIKE '%W%'";
    }
}
else if ($_GET["color_type"] == "exact") {
    $sql_search .= " AND NOT color_identity LIKE '%W%'";
}
if (isset($_GET["blue"])) {
    if ($_GET["color_type"] == "excluding") {
        $sql_search .= " AND NOT color_identity LIKE '%U%'";
    }
    else {
        $sql_search .= " AND color_identity LIKE '%U%'";
    }
}
else if ($_GET["color_type"] == "exact") {
    $sql_search .= " AND NOT color_identity LIKE '%U%'";
}
if (isset($_GET["black"])) {
    if ($_GET["color_type"] == "excluding") {
        $sql_search .= " AND NOT color_identity LIKE '%B%'";
    }
    else {
        $sql_search .= " AND color_identity LIKE '%B%'";
    }
}
else if ($_GET["color_type"] == "exact") {
    $sql_search .= " AND NOT color_identity LIKE '%B%'";
}
if (isset($_GET["red"])) {
    if ($_GET["color_type"] == "excluding") {
        $sql_search .= " AND NOT color_identity LIKE '%R%'";
    }
    else {
        $sql_search .= " AND color_identity LIKE '%R%'";
    }
}
else if ($_GET["color_type"] == "exact") {
    $sql_search .= " AND NOT color_identity LIKE '%R%'";
}
if (isset($_GET["green"])) {
    if ($_GET["color_type"] == "excluding") {
        $sql_search .= " AND NOT color_identity LIKE '%G%'";
    }
    else {
        $sql_search .= " AND color_identity LIKE '%G%'";
    }
}
else if ($_GET["color_type"] == "exact") {
    $sql_search .= " AND NOT color_identity LIKE '%G%'";
}

if (isset($_GET["legality"])) {
    $sql_search .= match ($_GET["legality"]) {
        "standard" => " AND standard_legal='legal'",
        "pioneer" => " AND pioneer_legal='legal'",
        "modern" => " AND modern_legal='legal'",
        "legacy" => " AND legacy_legal='legal'",
        "vintage" => " AND vintage_legal='legal'",
        "pauper" => " AND pauper_legal='legal'",
        "commander" => " AND commander_legal='legal'",
        default => ""
    };
}

if (isset($_GET["cmc"])) {
    $cmc = mysqli_real_escape_string($db, $_GET["cmc"]);
    if ($_GET["cmc_type"] == ">") {
        $sql_search .= " AND cmc>'$cmc'";
    }
    if ($_GET["cmc_type"] == "=") {
        $sql_search .= " AND cmc='$cmc'";
    }
    if ($_GET["cmc_type"] == "<") {
        $sql_search .= " AND cmc<'$cmc'";
    }
}

if (isset($_GET["price"])) {
    $price = mysqli_real_escape_string($db, $_GET["price"]);
    if ($_GET["price_type"] == ">") {
        if ($_GET["card_price_type"] == "normal") {
            $sql_search .= " AND NOT normal_price='0' AND normal_price<'$price'";
        }
        else {
            $sql_search .= " AND NOT foil_price='0' AND foil_price<'$price'";
        }
    }
    if ($_GET["price_type"] == "=") {
        if ($_GET["card_price_type"] == "normal") {
            $sql_search .= " AND NOT normal_price='0' AND normal_price='$price'";
        }
        else {
            $sql_search .= " AND NOT foil_price='0' AND foil_price='$price'";
        }
    }
    if ($_GET["price_type"] == "<") {
        if ($_GET["card_price_type"] == "normal") {
            $sql_search .= " AND NOT normal_price='0' AND normal_price>'$price'";
        }
        else {
            $sql_search .= " AND NOT foil_price='0' AND foil_price>'$price'";
        }
    }
}

if (isset($_GET["card_order"])) {
    $sql_search .= match ($_GET["card_order"]) {
        "ID" => " ORDER BY id",
        "name" => " ORDER BY name",
        "n_price" => " AND NOT normal_price='0' ORDER BY normal_price",
        "f_price" => " AND NOT foil_price='0' ORDER BY foil_price",
        "random" => " ORDER BY RAND()",
        "release" => " ORDER BY released_at",
        "rarity" => " AND NOT rarity_num='0' ORDER BY rarity_num",
        "set" => " ORDER BY set_code",
        "power" => " AND NOT power='' AND NOT power LIKE '%*%'
                     AND NOT power LIKE '%-%'
                     AND NOT power LIKE '%+%'
                     AND NOT power LIKE '%?%' ORDER BY CAST(power as unsigned)",
        "toughness" => " AND NOT toughness='' AND NOT toughness LIKE '%*%'
                         AND NOT toughness LIKE '%-%' AND NOT toughness LIKE '%+%'
                         AND NOT toughness LIKE '%?%' ORDER BY CAST(toughness as unsigned)",
        "loyalty" => "AND NOT loyalty='' ORDER BY CAST(loyalty as unsigned)",
        default => ""
    };
}

if (isset($_GET["asc_dsc"])) {
    if (strcmp($_GET["asc_dsc"], "asc") == 0) {
        $sql_search .= " ASC";
    }
    else {
        $sql_search .= " DESC";
    }
}

if (isset($sql_search)) {
    $_SESSION["search"] = $sql_search;
}
else if (isset($_SESSION["search"])) {
    $sql_search = $_SESSION["search"];
}

$sql .= $sql_search;
$sql .= " LIMIT {$cards_per_page} OFFSET {$page_offset}";

$cards = query_execute_unsafe($db, $sql);

$sql_amount = "SELECT COUNT(1) FROM cards ";
$sql_amount .= "WHERE real_card='1'
                AND NOT layout='art_series'
                AND NOT layout='token'
                AND NOT layout='emblem'
                AND NOT layout='planar'
                AND NOT type_line LIKE '%card%'";
$sql_amount .= $sql_search;

$card_amount = mysqli_query($db, $sql_amount);
$card_amount = mysqli_fetch_array($card_amount)[0];
$last_page = intdiv(intval($card_amount), $cards_per_page) + 1;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>

<body>
<?php include_once "header.php"; ?>

<div class="box">
    <button class="collapsible-button" onclick="collapse()">
        <b>Filter cards</b>
    </button>
    <div id="search_bar" class="collapsed-row">
        <form action="/shop" method="GET">
            <div class="shop-column">
                <b>Card name</b>
                <label>
                    <input type="text" name="card_name" maxlength="50" value="<?= $_GET["card_name"] ?>">
                </label>
                <br><br><br>
                <b>Oracle text</b>
                <label>
                    <input type="text" name="oracle_text" maxlength="50" value="<?= $_GET["oracle_text"] ?>">
                </label>
                <br><br><br>
                <b>Card type</b>
                <label>
                    <input type="text" name="card_type" maxlength="50" value="<?= $_GET["card_type"] ?>">
                </label>
            </div>
            <div class="shop-column">
                <b>Flavor text</b>
                <label>
                    <input type="text" name="flavor_text" maxlength="50" value="<?= $_GET["flavor_text"] ?>">
                </label>
                <br><br><br>
                <b>Artist</b>
                <label>
                    <input type="text" name="artist" maxlength="50" value="<?= $_GET["artist"] ?>">
                </label>
                <br><br><br>
                <b>Set</b>
                <label>
                    <input type="text" name="set" maxlength="50" value="<?= $_GET["set"] ?>">
                </label>
            </div>
            <div class="shop-column">
                <b>Converted mana cost</b>
                <select name="cmc_type">
                    <option value="--" <?= ($_GET["cmc_type"] == "--") ? "selected" : "" ?>>--</option>
                    <option value="=" <?= ($_GET["cmc_type"] == "=") ? "selected" : "" ?>>=</option>
                    <option value=">" <?= ($_GET["cmc_type"] == ">") ? "selected" : "" ?>>&lt;</option>
                    <option value="<" <?= ($_GET["cmc_type"] == "<") ? "selected" : "" ?>>&gt;</option>
                </select>
                <input type="number" name="cmc" min="-10" max="10" value="<?= $_GET["cmc"] ?>">
                <br><br><br>
                <b>Color identity</b>
                <div class="color-checkbox">
                    <input
                        class="white_checkbox"
                        type="checkbox"
                        name="white"
                        <?= isset($_GET["white"]) ? "checked" : "" ?>
                    >
                    <input
                        class="blue_checkbox"
                        type="checkbox"
                        name="blue"
                        <?= isset($_GET["blue"]) ? "checked" : "" ?>
                    >
                    <input
                        class="black_checkbox"
                        type="checkbox"
                        name="black"
                        <?= isset($_GET["black"]) ? "checked" : "" ?>
                    >
                    <input
                        class="red_checkbox"
                        type="checkbox"
                        name="red"
                        <?= isset($_GET["red"]) ? "checked" : "" ?>
                    >
                    <input
                        class="green_checkbox"
                        type="checkbox"
                        name="green"
                        <?= isset($_GET["green"]) ? "checked" : "" ?>
                    >
                </div>
                <select name="color_type">
                    <option
                        value="including"
                        <?= ($_GET["color_type"] == "including") ? "selected" : "" ?>
                    >
                        including
                    </option>
                    <option
                        value="exact"
                        <?= ($_GET["color_type"] == "exact") ? "selected" : "" ?>
                    >
                        exact
                    </option>
                    <option
                        value="excluding"
                        <?= ($_GET["color_type"] == "excluding") ? "selected" : "" ?>
                    >
                        excluding
                    </option>
                </select>
            </div>
            <div class="shop-column">
                <b>Legal in</b>
                <select name="legality">
                    <option
                        value=""
                        <?= ($_GET["legality"] == "") ? "selected" : "" ?>
                    >
                        --
                    </option>
                    <option
                        value="standard"
                        <?= ($_GET["legality"] == "standard") ? "selected" : "" ?>
                    >
                        standard
                    </option>
                    <option
                        value="pioneer"
                        <?= ($_GET["legality"] == "poineer") ? "selected" : "" ?>
                    >
                        pioneer
                    </option>
                    <option
                        value="modern"
                        <?= ($_GET["legality"] == "modern") ? "selected" : "" ?>
                    >
                        modern
                    </option>
                    <option
                        value="legacy"
                        <?= ($_GET["legality"] == "legacy") ? "selected" : "" ?>
                    >
                        legacy
                    </option>
                    <option
                        value="vintage"
                        <?= ($_GET["legality"] == "vintage") ? "selected" : "" ?>
                    >
                        vintage
                    </option>
                    <option
                        value="pauper"
                        <?= ($_GET["legality"] == "pauper") ? "selected" : "" ?>
                    >
                        pauper
                    </option>
                    <option
                        value="commander"
                        <?= ($_GET["legality"] == "commander") ? "selected" : "" ?>
                    >
                        commander
                    </option>
                </select>
                <br><br>
                <b>Price</b>
                <select name="card_price_type">
                    <option
                        value="normal"
                        <?= ($_GET["card_price_type"] == "normal") ? "selected" : "" ?>
                    >
                        normal
                    </option>
                    <option
                        value="foil"
                        <?= ($_GET["card_price_type"] == "foil") ? "selected" : "" ?>
                    >
                        foil
                    </option>
                </select>
                <select name="price_type">
                    <option value="--" <?= ($_GET["price_type"] == "--") ? "selected" : "" ?>>
                        --
                    </option>
                    <option value="=" <?= ($_GET["price_type"] == "=") ? "selected" : "" ?>>
                        =
                    </option>
                    <option value=">" <?= ($_GET["price_type"] == ">") ? "selected" : "" ?>>
                        &lt;
                    </option>
                    <option value="<" <?= ($_GET["price_type"] == "<") ? "selected" : "" ?>>
                        &gt;
                    </option>
                </select>
                <input
                    type="number"
                    name="price"
                    min="0"
                    max="99999"
                    step="0.01"
                    value="<?= $_GET["price"] ?>"
                >
                <br><br>
                <b>Order by</b>
                <select name="card_order">
                    <option value="ID" <?= ($_GET["card_order"] == "ID") ? "selected" : "" ?>>
                        id
                    </option>
                    <option value="name" <?= ($_GET["card_order"] == "name") ? "selected" : "" ?>>
                        name
                    </option>
                    <option value="n_price" <?= ($_GET["card_order"] == "n_price") ? "selected" : "" ?>>
                        normal price
                    </option>
                    <option value="f_price" <?= ($_GET["card_order"] == "f_price") ? "selected" : "" ?>>
                        foil price
                    </option>
                    <option value="release" <?= ($_GET["card_order"] == "release") ? "selected" : "" ?>>
                        release
                    </option>
                    <option value="rarity" <?= ($_GET["card_order"] == "rarity") ? "selected" : "" ?>>
                        rarity
                    </option>
                    <option value="set" <?= ($_GET["card_order"] == "set") ? "selected" : "" ?>>
                        set
                    </option>
                    <option value="power" <?= ($_GET["card_order"] == "power") ? "selected" : "" ?>>
                        power
                    </option>
                    <option value="toughness" <?= ($_GET["card_order"] == "toughness") ? "selected" : "" ?>>
                        toughness
                    </option>
                    <option value="loyality" <?= ($_GET["card_order"] == "loyality") ? "selected" : "" ?>>
                        loyality
                    </option>
                    <option value="random" <?= ($_GET["card_order"] == "random") ? "selected" : "" ?>>
                        random
                    </option>
                </select>
                <select name="asc_dsc">
                    <option value="asc" <?= ($_GET["asc_dsc"] == "asc") ? "selected" : "" ?>>
                        ascending
                    </option>
                    <option value="dsc" <?= ($_GET["asc_dsc"] == "dsc") ? "selected" : "" ?>>
                        descending
                    </option>
                </select>
                <br><br><br>
            </div>
            <div class="center">
                <b><?= $card_amount ?> Results </b>
                <input type="submit" name="submit" value="Search">
            </div>
        </form>
    </div>
</div>

<div class="box box-row box-container">
<?php
foreach ($cards as $card):
    $card_front = $card["image"] ? $card["image"] : "/img/no_image_available.png";
    $card_back = $card["back_image"];
    $card_price = $card["normal_price"];
    $card_page = "/product?id=" . $card["id"];

    if ($card["normal_price"] == 0) {
        if ($card["foil_price"] == 0) {
            $card_price = "--";
        }
        else {
            $card_price = $card["foil_price"];
        }
    }
?>
    <div class="box box-item">
        <div class="box-row item-header">
            <div class="box-left item-name">
                <a href="/product?id=<?= $card["id"] ?>"><?= $card["name"] ?></a>
            </div>
            <div class="box-right item-price"><?= format_eur($card_price) ?></div>
        </div>

        <div class="box-row item-set"><?= $card["set_name"] ?></div>

        <div class="box-row">
<?php if (isset($card_back)): ?>
            <div class="box-card-small">
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
            <div class="box-card-small">
                <a href="<?= $card_page ?>">
                    <img src="<?= $card_front ?>" alt="<?= $card["name"] ?>">
                </a>
            </div>
<?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
<?php if ($card_amount == 0): ?>
    <div class="center-img box-row box-card-small">
            <img src="/img/no_cards_found.png" alt="no cards found">
    </div>
<?php endif; ?>
</div>

<?php
function query_with_page($page_wat) {
    $_GET["page"] = strval($page_wat);
    return http_build_query($_GET);
}
?>

<?php if ($card_amount > $cards_per_page): ?>
<div class="pageinator">
<?php if ($page > 2): ?>
    <a class="first-page" href="/shop?<?= query_with_page(1) ?>";>
        <i class="fa-solid fa-chevron-left"></i>
        <i class="fa-solid fa-chevron-left"></i>
    </a>
<?php endif; ?>
<?php if ($page > 1): ?>
    <a href="/shop?<?= query_with_page($page - 1) ?>">
        <i class="fa-solid fa-chevron-left"></i>
    </a>
<?php endif; ?>
<?php
    function window($page, $last_page) {
        if ($page < 4) {
            return range(1, 7);
        }

        if ($last_page - $page < 4) {
            return range($last_page - 6, $last_page);
        }

        return range($page - 3, $page + 3);
    }

    foreach (window($page, $last_page) as $page_ref) {
        $_GET["page"] = strval($page_ref);
        $tag = '<a href="/shop?' . http_build_query($_GET) . '"';

        $tag .= $page_ref == $page ? ' class="this-page-button">' : ">";
        $tag .= strval($page_ref);
        $tag .= "</a>";

        if (strval($page_ref) <= $last_page and strval($page_ref) > 0) {
            echo "\t$tag\n";
        }
    }
    ?>
<?php if ($last_page != $page): ?>
    <a href="/shop?page=<?= query_with_page($page + 1) ?>">
        <i class="fa-solid fa-chevron-right"></i>
    </a>
<?php endif; ?>
<?php if ($last_page - $page > 1): ?>
    <a class="last-page" href="/shop?<?= query_with_page($last_page) ?>">
        <i class="fa-solid fa-chevron-right"></i>
        <i class="fa-solid fa-chevron-right"></i>
    </a>
<?php endif; ?>
</div>
<?php endif; ?>

<script>
function collapse() {
    if (document.getElementById('search_bar').classList == "collapsible-row form") {
        document.getElementById('search_bar').setAttribute("class", "collapsed-row");
    }
    else {
        document.getElementById('search_bar').setAttribute("class", "collapsible-row form");
    }
}
</script>

<?php include_once "footer.php"; ?>

</body>

</html>
