<?php

include_once "include/common.php";

session_start();

// ensure you can't try to send a message if you aren't logged in
if (!isset($_SESSION["id"])) {
    http_response_code(404);
    exit;
}

$message = file_get_contents("php://input");
$message = htmlspecialchars(trim($message));

if ($_GET["action"] == "send") {
    require_once "include/db.php";

    // message too long
    if (strlen($message) > 255) {
        http_response_code(300);
        exit;
    }

    send_message($db, $_SESSION["id"], $message);
    exit;
}

if ($_GET["action"] == "receive") {

}

http_response_code(500);
