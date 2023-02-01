<?php
include_once "include/common.php";
include_once "include/db.php";
if (!isset($_SESSION)) {
    session_start();
}

$cards_per_page = 60;
$page_offset = 0;
$page = 1;

// if there is a page specified
if (isset($_GET["page"])) {
    // reload the page without a page specified if the page isn't a number
    if (!is_numeric($_GET["page"])) {
        header("Location: /shop.php");
    }

    $page = intval($_GET["page"]);

    // reload the page without a page specified if the page number is invalid
    if ($page < 1) {
        header("Location: /shop.php");
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
    $sql_search .= " AND set_name LIKE '%$set%'";
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
    switch ($_GET["legality"]) {
        case "standard": $sql_search .= " AND standard_legal='legal'"; break;
        case "pioneer": $sql_search .= " AND pioneer_legal='legal'"; break;
        case "modern": $sql_search .= " AND modern_legal='legal'"; break;
        case "legacy": $sql_search .= " AND legacy_legal='legal'"; break;
        case "vintage": $sql_search .= " AND vintage_legal='legal'"; break;
        case "pauper": $sql_search .= " AND pauper_legal='legal'"; break;
        case "commander": $sql_search .= " AND commander_legal='legal'"; break;
    }
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
    switch ($_GET["card_order"]) {
        case "ID": $sql_search .= " ORDER BY id"; break;
        case "name": $sql_search .= " ORDER BY name"; break;
        case "n_price": $sql_search .= " AND NOT normal_price='0' ORDER BY normal_price"; break;
        case "f_price": $sql_search .= " AND NOT foil_price='0' ORDER BY foil_price"; break;
        case "random": $sql_search .= " ORDER BY RAND()"; break;
        case "release": $sql_search .= " ORDER BY released_at"; break;
        case "rarity": $sql_search .= " AND NOT rarity_num='0' ORDER BY rarity_num"; break;
        case "set": $sql_search .= " ORDER BY set_code"; break;
        case "power": $sql_search .= " AND NOT power='' AND NOT power LIKE '%*%'
              AND NOT power LIKE '%-%' AND NOT power LIKE '%+%' AND NOT power LIKE '%?%' ORDER BY CAST(power as unsigned)"; break;
        case "toughness": $sql_search .= " AND NOT toughness='' AND NOT toughness LIKE '%*%'
              AND NOT toughness LIKE '%-%' AND NOT toughness LIKE '%+%' AND NOT toughness LIKE '%?%' ORDER BY CAST(toughness as unsigned)"; break;
        case "loyalty": $sql_search .= "AND NOT loyalty='' ORDER BY CAST(loyalty as unsigned)"; break;
    }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>

<body>

<?php include_once "header.php"; ?>

<div class="box">
    <button class="collapsible-button" onclick="collapse()">
        <b>Filter cards</b>
    </button>
    <div id="search_bar" class="collapsed-row">
    <form action="shop.php" method="GET">
        <div class="column">
            <b>Card name</b>
            <label>
                <input type="text" name="card_name" maxlength="50"
                value="<?php echo $_GET['card_name']??''; ?>" >
            </label>
            <br><br><br>
            <b>Oracle text</b>
            <label>
                <input type="text" name="oracle_text" maxlength="50"
                value="<?php echo $_GET['oracle_text']??''; ?>" >
            </label>
            <br><br><br>
            <b>Card type</b>
            <label>
                <input type="text" name="card_type" maxlength="50"
                value="<?php echo $_GET['card_type']??''; ?>" >
            </label>
        </div>
        <div class="column">
            <b>Flavor text</b>
            <label>
                <input type="text" name="flavor_text" maxlength="50"
                value="<?php echo $_GET['flavor_text']??''; ?>" >
            </label>
            <br><br><br>
            <b>Artist</b>
            <label>
                <input type="text" name="artist" maxlength="50"
                value="<?php echo $_GET['artist']??''; ?>" >
            </label>
            <br><br><br>
            <b>Set</b>
            <label>
                <input type="text" name="set" maxlength="50"
                value="<?php echo $_GET['set']??''; ?>" >
            </label>
        </div>
        <div class="column">
            <b>Converted mana cost</b>
            <select name="cmc_type">
                <option value="--" <?php if(strcmp($_GET["cmc_type"], "--") == 0)
                                        echo "selected='selected'"; ?> >--</option>
                <option value="=" <?php if(strcmp($_GET["cmc_type"], "=") == 0)
                                        echo "selected='selected'"; ?> >=</option>
                <option value=">" <?php if(strcmp($_GET["cmc_type"], ">") == 0)
                                        echo "selected='selected'"; ?> >&lt;</option>
                <option value="<" <?php if(strcmp($_GET["cmc_type"], "<") == 0)
                                        echo "selected='selected'"; ?> >&gt;</option>
            </select>
            <input type="number" name="cmc" min="-10" max="10" value="<?php echo $_GET['cmc']??''; ?>" >
            <br><br><br>
            <b>Color identity</b>
            <div class="color-checkbox">
                <input class="white_checkbox" type="checkbox" name="white"
                <?php if(isset($_GET['white'])) echo "checked='checked'"; ?> >
                <input class="blue_checkbox" type="checkbox" name="blue"
                <?php if(isset($_GET['blue'])) echo "checked='checked'"; ?> >
                <input class="black_checkbox" type="checkbox" name="black"
                <?php if(isset($_GET['black'])) echo "checked='checked'"; ?> >
                <input class="red_checkbox" type="checkbox" name="red"
                <?php if(isset($_GET['red'])) echo "checked='checked'"; ?> >
                <input class="green_checkbox" type="checkbox" name="green"
                <?php if(isset($_GET['green'])) echo "checked='checked'"; ?> >
            </div>
            <select name="color_type">
                <option value="including" <?php if(strcmp($_GET["color_type"], "including") == 0)
                                        echo "selected='selected'"; ?> >including</option>
                <option value="exact" <?php if(strcmp($_GET["color_type"], "exact") == 0)
                                        echo "selected='selected'"; ?> >exact</option>
                <option value="excluding" <?php if(strcmp($_GET["color_type"], "excluding") == 0)
                                        echo "selected='selected'"; ?> >excluding</option>
            </select>
        </div>
        <div class="column">
            <b>Legal in</b>
            <select name="legality">
                <option value="" <?php if(strcmp($_GET["legality"], "") == 0)
                                        echo "selected='selected'"; ?> >--</option>
                <option value="standard" <?php if(strcmp($_GET["legality"], "standard") == 0)
                                        echo "selected='selected'"; ?> >standard</option>
                <option value="pioneer" <?php if(strcmp($_GET["legality"], "pioneer") == 0)
                                        echo "selected='selected'"; ?> >pioneer</option>
                <option value="modern" <?php if(strcmp($_GET["legality"], "modern") == 0)
                                        echo "selected='selected'"; ?> >modern</option>
                <option value="legacy" <?php if(strcmp($_GET["legality"], "legacy") == 0)
                                        echo "selected='selected'"; ?> >legacy</option>
                <option value="vintage" <?php if(strcmp($_GET["legality"], "vintage") == 0)
                                        echo "selected='selected'"; ?> >vintage</option>
                <option value="pauper" <?php if(strcmp($_GET["legality"], "pauper") == 0)
                                        echo "selected='selected'"; ?> >pauper</option>
                <option value="commander" <?php if(strcmp($_GET["legality"], "commander") == 0)
                                        echo "selected='selected'"; ?> >commander</option>
            </select>
            <br><br>
            <b>Price</b>
            <select name="card_price_type">
                <option value="normal" <?php if(strcmp($_GET["card_price_type"], "normal") == 0)
                                        echo "selected='selected'"; ?> >normal</option>
                <option value="foil" <?php if(strcmp($_GET["card_price_type"], "foil") == 0)
                                        echo "selected='selected'"; ?> >foil</option>
            </select>
            <select name="price_type">
                <option value="--" <?php if(strcmp($_GET["price_type"], "--") == 0)
                                        echo "selected='selected'"; ?> >--</option>
                <option value="=" <?php if(strcmp($_GET["price_type"], "=") == 0)
                                        echo "selected='selected'"; ?> >=</option>
                <option value=">" <?php if(strcmp($_GET["price_type"], ">") == 0)
                                        echo "selected='selected'"; ?> >&lt;</option>
                <option value="<" <?php if(strcmp($_GET["price_type"], "<") == 0)
                                        echo "selected='selected'"; ?> >&gt;</option>
            </select>
            <input type="number" name="price" min="0" max="99999" step="0.01"
                                 value="<?php echo $_GET['price']??''; ?>" >
            <br><br>
            <b>Order by</b>
            <select name="card_order">
                <option value="ID" <?php if($_GET["card_order"] == "ID")
                                        echo "selected='selected'"; ?> >id</option>
                <option value="name" <?php if($_GET["card_order"] =="name")
                                        echo "selected='selected'"; ?> >name</option>
                <option value="n_price"  <?php if($_GET["card_order"] == "n_price")
                                        echo "selected='selected'"; ?> >normal price</option>
                <option value="f_price" <?php if($_GET['card_order'] == "f_price")
                                        echo "selected='selected'"; ?> >foil price</option>
                <option value="release" <?php if($_GET['card_order'] == "release")
                                        echo "selected='selected'"; ?> >release</option>
                <option value="rarity" <?php if($_GET['card_order'] == "rarity")
                                        echo "selected='selected'"; ?> >rarity</option>
                <option value="set" <?php if($_GET['card_order'] == "set")
                                        echo "selected='selected'"; ?> >set</option>
                <option value="power" <?php if($_GET['card_order'] == "power")
                                        echo "selected='selected'"; ?> >power</option>
                <option value="toughness" <?php if($_GET['card_order'] == "toughness")
                                        echo "selected='selected'"; ?> >toughness</option>
                <option value="loyalty" <?php if($_GET['card_order'] == "loyalty")
                                        echo "selected='selected'"; ?> >loyalty</option>
                <option value="random" <?php if($_GET['card_order'] == "random")
                                        echo "selected='selected'"; ?> >random</option>
            </select>
            <select name="asc_dsc">
                <option value="asc" <?php if($_GET["asc_dsc"] == "asc")
                                        echo "selected='selected'"; ?> >ascending</option>
                <option value="dsc" <?php if($_GET["asc_dsc"] == "dsc")
                                        echo "selected='selected'"; ?> >descending</option>
            </select>
            <br><br><br>
        </div>
        <div class="center">
            <b><?php echo $card_amount??''; ?> Results</b>
            <input type="submit" name="submit" value="Search">
        </div>
        </form>
    </div>
</div>

<div class="box box-row box-container">
<?php
foreach ($cards as $card):
    $card_front = $card["image"];
    $card_back = $card["back_image"];
    $card_price = $card["normal_price"];
    $card_page = "/product.php?id=" . $card["id"];

    if (!$card_front) {
        $card_front = "/img/no_image_available.png";
    }
    if ($card["normal_price"] == 0) {
        if ($card["foil_price"] == 0) {
            echo $card["normal_price"];
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
                <a href="product.php?id=<?= $card["id"] ?>"><?= $card["name"] ?></a>
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

<div class="pageinator">
<?php if ($page > 2): ?>
    <a class="first-page" href="/shop.php?page=1";>
        <i class="fa-solid fa-chevron-left"></i>
        <i class="fa-solid fa-chevron-left"></i>
    </a>
<?php endif; ?>
<?php if ($page > 1): ?>
    <a href="/shop.php?page=<?= $page - 1 ?>">
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
        $tag = '<a href="/shop.php?page=' . strval($page_ref). '"';

        $tag .= $page_ref == $page ? ' class="this-page-button">' : ">";
        $tag .= strval($page_ref);
        $tag .= "</a>";

        if (strval($page_ref) <= $last_page And strval($page_ref) > 0) {
            echo "\t$tag\n";
        }
    }
    ?>
<?php if ($last_page != $page): ?>
    <a href="/shop.php?page=<?= $page + 1 ?>">
        <i class="fa-solid fa-chevron-right"></i>
    </a>
<?php endif; ?>
<?php if ($last_page - $page > 1): ?>
    <a class="last-page" href="/shop.php?page=<?= $last_page ?>">
        <i class="fa-solid fa-chevron-right"></i>
        <i class="fa-solid fa-chevron-right"></i>
    </a>
<?php endif; ?>
</div>

<?php include_once "footer.php"; ?>

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

</body>

</html>
