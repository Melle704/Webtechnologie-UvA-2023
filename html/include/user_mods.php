<?php

include_once "common.php";

session_start();

// ensure you can't reach the profile page if you're not logged in
if (!isset($_SESSION["id"])) {
    header("Location: /index.php");
    exit;
}

// we're visiting another user's profile
if ($_SESSION["id"] != $_GET["id"]) {
    require_once "include/db.php";

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

// continue if it's form submissions
if (!isset($_POST["submit"])) {
    return;
}

// handle upload of a picture
if ($_GET["action"] == "picture") {
    // when there is no file specified
    if (!isset($_FILES["img"])) {
        $redirect_title="File missing";
        $redirect_msg="You must specify a profile picture.";
        include_once "include/redirect.php";

        // wait two seconds before refreshing
        header("Refresh: 2; url=". $_SERVER["REQUEST_URI"]);
        exit;
    }

    // when the image size is greater than 1Mb
    if ($_FILES["img"]["size"] > 1024 * 1024) {
        $redirect_title="Too large of file";
        $redirect_msg="All profile picture images must be below 1Mb in size.";
        include_once "include/redirect.php";

        // wait two seconds before refreshing
        header("Refresh: 2; url=". $_SERVER["REQUEST_URI"]);
        exit;
    }

    $file = file_get_contents($_FILES["img"]["tmp_name"]);

    if (file_type($file) != "image") {
        $redirect_title="Invalid file";
        $redirect_msg="Any profile picture must either be an image or gif.";
        include_once "include/redirect.php";

        // wait two seconds before refreshing
        header("Refresh: 2; url=". $_SERVER["REQUEST_URI"]);
        exit;
    }
}
