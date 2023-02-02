<?php

include_once "common.php";
require_once "db.php";

session_start();

// ensure you can't reach the profile page if you're not logged in
if (!isset($_SESSION["id"])) {
    header("Location: /");
    exit;
}

// we're visiting another user's profile
if ($_SESSION["id"] != $_GET["id"]) {
    // when the id is invalid
    if (!is_numeric($_GET["id"])) {
        $redirect_title="Unknown user";
        $redirect_msg="The requested user does not exist.";
        include_once "redirect.php";

        // wait two seconds before refreshing
        header("Refresh: 2; url=/");
        exit;
    }
}

$user = find_user_by_uid($db, $_GET["id"]);

// when there is no matching user id
if (!$user) {
    $redirect_title="Unknown user";
    $redirect_msg="The requested user does not exist.";
    include_once "redirect.php";

    // wait two seconds before refreshing
    header("Refresh: 2; url=/");
    exit;
}

$profile_desc = $user["profile_desc"];

if ($profile_desc == "") {
    $profile_desc = "This user has yet to have set a profile description.";
}

// get profile picture of user
$profile_pic = @file_get_contents("./img/user" . $_GET["id"] . ".raw");
$profile_pic_type = @file_get_contents("./img/user" . $_GET["id"] . ".info");

if (!$profile_pic) {
    $profile_pic = file_get_contents("./img/sample.raw");
    $profile_pic_type = "image/png";
}

// continue if it's form submissions
if (!isset($_POST["submit"])) {
    return;
}

$redirect_uri = $_SERVER["PHP_SELF"] . "?id=" . $_GET["id"];

// store picture and display it
if ($_GET["action"] == "picture") {
    // when there is no file specified
    if ($_FILES["img"]["name"] == "") {
        $redirect_title="File missing";
        $redirect_msg="You must specify a profile picture.";
        include_once "redirect.php";

        // wait two seconds before refreshing
        header("Refresh: 2; url=$redirect_uri");
        exit;
    }

    // when the image size is greater than 1Mb
    if ($_FILES["img"]["size"] > 1024 * 1024 * 100) {
        $redirect_title="Too large of file";
        $redirect_msg="All profile picture images must be below 1Mb in size.";
        include_once "redirect.php";

        // wait two seconds before refreshing
        header("Refresh: 2; url=$redirect_uri");
        exit;
    }

    $profile_pic = file_get_contents($_FILES["img"]["tmp_name"]);
    [$width, $height] = getimagesizefromstring($profile_pic);

    if ($width < 256 || $height < 256) {
        $redirect_title="Image too small";
        $redirect_msg="Profile pictures of size 256x256 or larger.";
        include_once "redirect.php";

        // wait two seconds before refreshing
        header("Refresh: 2; url=$redirect_uri");
        exit;
    }

    $profile_pic = base64_encode($profile_pic);
    $profile_pic_type = $_FILES["img"]["type"];

    if (substr($_FILES["img"]["type"], 0, 5) != "image") {
        $redirect_title="Invalid file";
        $redirect_msg="All profile pictures must either be an image or gif.";
        include_once "redirect.php";

        // wait two seconds before refreshing
        header("Refresh: 2; url=$redirect_uri");
        exit;
    }

    // store user picture file and file type
    file_put_contents("./img/user" . $_SESSION["id"] . ".raw", $profile_pic);
    file_put_contents("./img/user" . $_SESSION["id"] . ".info", $profile_pic_type);
}

// store profile description and display it
if ($_GET["action"] == "desc") {
    if (!isset($_POST["desc"]) || $_POST["desc"] == "") {
        $profile_desc = "";
    } else {
        // do a replace text instead
        $profile_desc = htmlspecialchars(trim($_POST["desc"]));
    }

    // profile description text is too long
    if (strlen($profile_desc) > 300) {
        $redirect_title="Description too long";
        $redirect_msg="Description be less than 300 characters.";
        include_once "redirect.php";

        // wait two seconds before refreshing
        header("Refresh: 2; url=$redirect_uri");
        exit;
    }

    update_user_desc($db, $_SESSION["id"], $profile_desc);
}
