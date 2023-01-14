<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTG | Cards</title>

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> 
</head>

<body>

<?php include_once "header.php";?>

<div class="box box-row">
    <p>
    Fakka Gangsters!
    </p>
</div>

<div class="box box-row">
    Credits to
    <b>Nicolas Mazzon</b>,
    <b>Sebastian Gielens</b>,
    <b>Ceylan Siegertsz</b> and
    <b>Kas Visser</b>
    <br>
    <a href="/about.php">About us</a></li>
    <br>
</div>

<?php if (isset($_SESSION["id"])): ?>
<script>
    document.getElementById("datetime").innerText = Date().split(' GMT')[0]
</script>
<?php endif; ?>

</body>

</html>
