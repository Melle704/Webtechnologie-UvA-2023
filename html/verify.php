<?php

session_start();

// ensure you can't verify an account when you're logged in
if (isset($_SESSION["id"])) {
    header("Location: /");
    exit;
}

// only try to verify email address if the verification code is of a valid format
if (!isset($_GET["id"]) || strlen($_GET["id"]) != 22) {
    header("Location: /");
    exit;
}

require_once "include/common.php";
require_once "include/db.php";

$user = query_execute($db, "SELECT * FROM users where verification_code=?", "s", $_GET["id"]);

if (count($user) == 1) {
    $user = $user[0];

    if ($user["email_verified"]) {
        $redirect_title="Already verified";
        $redirect_msg="Your email address has already been verified.";
        include_once "include/redirect.php";

        // wait two seconds before refreshing
        header("Refresh: 2; url=/");
        exit;
    }

    // update email as being verified
    query_execute_unsafe($db, "UPDATE users SET email_verified=1 WHERE id=" . $user["id"]);

    // log user in on success
    $_SESSION["id"] = $user["id"];
    $_SESSION["uname"] = $user["uname"];
    $_SESSION["stay_logged"] = false;
    $_SESSION["role"] = isset($user["role"]) ? $user["role"] : "default";

    $now = time();
    $_SESSION["last_activity"] = new DateTime("@$now");

    logout_user_on_inactivity($db, $user["id"]);

    $redirect_title="Successfully registered";
    $redirect_msg="Your email has successfully been verified.<br>You are now logging in.";
    include_once "include/redirect.php";

    // wait two seconds before refreshing
    header("Refresh: 2; url=/");
    exit;
}

// return home when the verification code ends up being incorrect
header("Location: /");
exit;
