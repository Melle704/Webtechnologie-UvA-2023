<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTG | Cards</title>

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/css/form.css">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
</head>

<body>

<?php include_once "header.php";?>
<div class="box">
    <div class="box-row box-light">
        <b>Simple search</b>
    </div>
    <div class="box-row form">
    <form action="" method="GET">
            <b>card name</b>
            <input type="text" id="card_name">
            <br><br>
            <b>oracle text</b>
            <input type="text" id="oracle_text">
            <br><br>
            <b>card type</b>
            <input type="text" id="card_type">
            <br><br>
            <b>colors</b>
            <input class="white_checkbox" type="checkbox" id="white" value="white">
            <input class="blue_checkbox" type="checkbox" id="blue" value="blue">
            <input class="black_checkbox" type="checkbox" id="black" value="black">
            <input class="red_checkbox" type="checkbox" id="red" value="red">
            <input class="green_checkbox" type="checkbox" id="green" value="green">
            <input class="colorless_checkbox" type="checkbox" id="colorless" value="colorless">
            <br><br>
            <input type="submit" name="submit" value="Search">
    </form>
    </div>
</div>

<?php include_once "footer.php"; ?>

</body>

</html>
