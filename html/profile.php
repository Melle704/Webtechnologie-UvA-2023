<?php include_once "include/user_mods.php";?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTG | Deck building</title>

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/css/form.css">
	<link rel="stylesheet" type="text/css" href="/css/profile.css">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
</head>

<body>

<?php include_once "header.php";?>
<?php $pic_submit = $_SERVER["PHP_SELF"] . "?id=" . $_GET["id"] . "&action=picture"; ?>

<?php if ($_SESSION["id"] == $_GET["id"]): ?>
<div class="box">
    <div class="box-row box-light">
        <b>User Profile</b>
    </div>

    <div class="box-row form">
        <form action=<?php echo $pic_submit; ?> method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>
                    Personal profile picture
                </legend>
                <label class="file-upload">
                    <input id="file-input" type="file" name="img" accept="image/*">
                    <span id="file-name"></span>
                </label>
                <label>
                    <input type="submit" name="submit" value="submit">
                </label>
            </fieldset>
        </form>
    </div>
</div>
<?php else: ?>
<div class="box">
    <div class="box-row box-light">
        <b><?php echo $user["uname"];?>'s profile</b>
    </div>

    <div class="box-row">
        <div class="profile">
            <img src="/img/zoolander-stare.gif">
        </div>
    </div>
</div>
<?php endif; ?>

<?php include_once "footer.php"; ?>

<?php if (isset($_SESSION["id"])): ?>
<script src="/js/common.js"></script>
<script>
    update_datetime();
    window.setInterval(update_datetime, 1000);

    let file_input = document.getElementById("file-input");
    let file_name = document.getElementById("file-name");

    file_input.addEventListener("change", function() {
        let file = file_input.files[0];
        file_name.innerText = file.name;
    });
</script>
<?php endif; ?>

</body>

</html>
