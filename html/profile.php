<?php
session_start();

// ensure you can't reach the registration or login page if you're not logged in
if (!isset($_SESSION["id"])) {
    header("Location: /index.php");
    exit;
}

// we're visiting another user's profile
if ($_SESSION["id"] != $_GET["id"]) {
    require_once "include/db.php";
    include_once "include/common.php";

    // when the id is invalid
    if (!is_numeric($_GET["id"])) {
        $redirect_title="Unknown user";
        $redirect_msg="The requested user does not exist.";
        include_once "include/redirect.php";

        // wait two seconds before refreshing
        header("Refresh: 2; url=/index.php");
        exit;
    }

    $user = find_user_by_uid($db, $_GET["id"]);

    // when there is no matching user id
    if (!$user) {
        $redirect_title="Unknown user";
        $redirect_msg="The requested user does not exist.";
        include_once "include/redirect.php";

        // wait two seconds before refreshing
        header("Refresh: 2; url=/index.php");
        exit;
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTG | Deck building</title>

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> 
</head>

<body>

<?php include_once "header.php";?>

<?php if ($_SESSION["id"] == $_GET["id"]): ?>
<div class="box">
    <div class="box-row box-light">
        <b>User Profile</b>
    </div>

    <div class="box-row">
        <img src="/img/zoolander-stare.gif">
    </div>
</div>
<?php else: ?>
<div class="box">
    <div class="box-row box-light">
        <b><?php echo $user["uname"];?>'s profile</b>
    </div>

    <div class="box-row">
        <img src="/img/zoolander-stare.gif">
    </div>
</div>
<?php endif; ?>

<div class="box box-row">
    Credits to
    <b>Nicolas Mazzon</b>,
    <b>Sebastian Gielens</b>,
    <b>Ceylan Siegertsz</b> and
    <b>Kas Visser</b>
    <br>
    <a href="/about.php">About us</a>
    <br>
</div>

<?php if (isset($_SESSION["id"])): ?>
<script src="/js/common.js"></script>
<script>
    update_datetime();
    window.setInterval(update_datetime, 1000);
</script>
<?php endif; ?>

</body>

</html>
