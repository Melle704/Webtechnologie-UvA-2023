<?php include_once "include/user_mods.php";?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTG | Deck building</title>

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/css/profile.css">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<link rel="stylesheet" type="text/css" href="/css/form.css">
    <script>
// update's textarea size on text input
function grow_box(self) {
    if (self.scrollHeight > 84) {
        self.style.height = "36px";
        self.style.height = (self.scrollHeight + 4) + "px";
    }
}
    </script>
</head>

<body>
<?php include_once "header.php";?>
<?php $pic_submit = $_SERVER["PHP_SELF"] . "?id=" . $_GET["id"] . "&action=picture"; ?>
<?php $desc_submit = $_SERVER["PHP_SELF"] . "?id=" . $_GET["id"] . "&action=desc"; ?>

<?php if ($_SESSION["id"] == $_GET["id"]): ?>
<div class="box">
    <div class="box-row box-light">
        <b>User Profile</b>
    </div>

    <div class="box-row box-flex">
        <div class="showcase">
            <div class="img-showcase">
                <img src="<?= "data:$profile_pic_type;base64,$profile_pic" ?>">
            </div>
            <div class="text-showcase box-light">
                <div class="box-row"><?= $profile_desc ?></div>
            </div>
        </div>
        <div class="flex-break"></div>
        <div class="form">
            <form action="<?= $pic_submit ?>" method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend>
                        Personal profile picture
                    </legend>
                    <label class="file-upload">
                        <input id="file-input" type="file" name="img" accept="image/*">
                        <span id="file-name"></span>
                    </label>
                    <label>
                        <input type="submit" name="submit" value="upload">
                    </label>
                </fieldset>
            </form>
            <form action="<?= $desc_submit ?>" method="post">
                <fieldset>
                    <legend>
                        Profile description
                    </legend>
                    <label>
                        <textarea maxlength="300" name="desc" oninput="grow_box(this)"></textarea>
                    </label>
                    <label>
                        <input type="submit" name="submit" value="submit">
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
    <div class="form-after"></div>
</div>
<?php else: ?>
<div class="box">
    <div class="box-row box-light">
        <b><?= $user["uname"] ?>'s profile</b>
    </div>

    <div class="box-row box-flex">
        <div class="showcase">
            <div class="img-showcase">
                <img src="<?= "data:$profile_pic_type;base64,$profile_pic" ?>">
            </div>
            <div class="text-showcase box-light">
                <div class="box-row">
                    <?= $profile_desc ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include_once "footer.php"; ?>

<?php if (isset($_SESSION["id"])): ?>
<script>
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
