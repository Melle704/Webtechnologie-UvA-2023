<?php

include_once "common.php";
include_once "messaging.php";

session_start();

// ensure you can't reach the registration or login page if you're logged in
if (isset($_SESSION["id"])) {
    header("Location: /");
    exit;
}

// continue if it's a valid form submissions
if (!isset($_POST["submit"]) || !isset($_POST["h-captcha-response"])) {
    return;
}

// ensure hcaptcha was sucessful
if ($_GET["action"] == "register" || $_GET["action"] == "login") {
    $hcaptcha_query = array(
        "secret" => "0x0000000000000000000000000000000000000000",
        "response" => $_POST["h-captcha-response"],
        "remoteip" => $_SERVER["REMOTE_ADDR"]
    );

    $request = curl_init();
    curl_setopt($request, CURLOPT_POST, true);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($request, CURLOPT_URL, "https://hcaptcha.com/siteverify");
    curl_setopt($request, CURLOPT_POSTFIELDS, http_build_query($hcaptcha_query));
    $response = curl_exec($request);

    if (curl_getinfo($request, CURLINFO_HTTP_CODE) != 200) {
        curl_close($request);
        reload_err("Failed to complete a valid captcha");
    }

    if (!json_decode($response, true)["success"]) {
        curl_close($request);
        reload_err("Failed to complete a valid captcha");
    }

    curl_close($request);
}

// handle register button submit from `/register`
if ($_GET["action"] == "register") {
    $username = trim($_POST["uname"]);
    $passwd1 = trim($_POST["passwd1"]);
    $passwd2 = trim($_POST["passwd2"]);
    $email = strtolower(trim($_POST["email"]));
    $day = intval(trim($_POST["day"]));
    $month = intval(trim($_POST["month"]));
    $year = intval(trim($_POST["year"]));

    require_once "db.php";

    validate_not_empty(
        ["username", $username],
        ["password", $passwd1],
        ["confirmation password", $passwd2],
        ["email", $email],
        ["day", $day],
        ["month", $month],
        ["year", $year]
    );

    validate_predicates(
        ["Passwords don't match", $passwd1 == $passwd2],
        ["Password is too short", strlen($passwd1) > 7],
        ["Password is too long", strlen($passwd1) < 500],
        ["Password doesn't include special characters", preg_match("/[\'^£$%&*()}{@#~?><>,|=_+¬-]/", $passwd1)],
        ["Password contains invalid characters", alphanumeric_plus_plus($passwd2)],
        ["Confirmation password is too long", strlen($passwd2) < 7],
        ["Confirmation password is too short", strlen($passwd2) > 500],
        ["Confirmation Password contains invalid characters", alphanumeric_plus_plus($passwd2)],
        ["Confirmation password doesn't include special characters", preg_match("/[\'^£$%&*()}{@#~?><>,|=_+¬-]/", $passwd2)],
        ["Name is too long", strlen($username) < 25],
        ["Name must be alphanumeric", ctype_alnum($username)],
        ["Date format is incorrect", checkdate($month, $day, $year)],
        ["You can't be the oldest person alive", $year > 1900],
        ["You can't be from the future", $year <= intval(date("Y"))],
        ["Email is too long", strlen($email) < 30],
        ["Email format is incorrect", filter_var($email, FILTER_VALIDATE_EMAIL)],
        ["User '$username' already exists", !find_user($db, $username)]
    );

    $charset = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $verification_code = substr(str_shuffle($charset), 0, 22);

    user_create($db, $username, $email, "$year-$month-$day", $passwd1, $verification_code);
    send_confirmation_email($email, $username, $verification_code);

    $redirect_title="Registering";
    $redirect_msg="You are being registered.<br>Make sure to check your email for a confirmation message.";
    include_once "redirect.php";

    // wait four seconds before refreshing
    header("Refresh: 4; url=/");
    exit;
}

// handle login button submit from `/login`
if ($_GET["action"] == "login") {
    $username = trim($_POST["uname"]);
    $passwd = trim($_POST["passwd"]);

    require_once "db.php";

    validate_not_empty(
        ["username", $username],
        ["password", $passwd],
    );

    validate_predicates(
        ["Name is too long", strlen($username) < 25],
        ["Name must be alphanumeric", ctype_alnum($username)],
        ["Password is too long", strlen($passwd) < 500],
        ["Password contains invalid characters", alphanumeric_plus_plus($passwd)],
    );

    $user = find_user($db, $username);

    validate_predicates(
        ["Incorrect username and/or password", $user && password_verify($passwd, $user["passwd"])],
        ["Check '" . $user["email"] . "' for your account registration email", $user["email_verified"]]
    );

    $_SESSION["id"] = $user["id"];
    $_SESSION["uname"] = $user["uname"];
    $_SESSION["stay_logged"] = isset($_POST["stay_logged"]) ? $_POST["stay_logged"] == "1" : false;
    $_SESSION["role"] = isset($user["role"]) ? $user["role"] : "default";

    $now = time();
    $_SESSION["last_activity"] = new DateTime("@$now");

    logout_user_on_inactivity($db, $user["id"]);

    $redirect_title="Logging in";
    $redirect_msg="You are being logged in..";
    include_once "redirect.php";

    // wait two seconds before refreshing
    header("Refresh: 2; url=/");
    exit;
}

http_response_code(500);
