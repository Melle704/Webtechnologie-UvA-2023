<?php

session_start();

// ensure you can't try to send a message if you aren't logged in
if (!isset($_SESSION["id"])) {
    http_response_code(404);
    exit;
}

require_once "include/db.php";
include_once "include/common.php";
include_once "include/messaging.php";

logout_user_on_inactivity($db);

if (!isset($_SESSION["messages_consumed"]) || $_GET["action"] == "reset") {
    $_SESSION["messages_consumed"] = 0;
}

if ($_GET["action"] == "send") {
    $text = file_get_contents("php://input");
    $text = htmlspecialchars(trim($text));

    // message is empty
    if (strlen($text) == 0) {
        exit;
    }

    // message too long
    if (strlen($text) > 255) {
        http_response_code(300);
        exit;
    }

    send_message($db, $_SESSION["id"], $text);
    exit;
}

if ($_GET["action"] == "receive") {
    $messages = retrieve_messages($db);
    $msg_count = count($messages);

    // chat is cleared / message is deleted
    if ($_SESSION["messages_consumed"] > $msg_count) {
        // probably refresh the message log here
        http_response_code(300);
        exit;
    }

    $new_messages = array_slice($messages, $_SESSION["messages_consumed"]);

    $concatenated_msgs = "";
    foreach ($new_messages as $message) {
        $concatenated_msgs .= format_message($db, $message);
    }

    $_SESSION["messages_consumed"] = $msg_count;

    echo $concatenated_msgs;
    exit;
}

http_response_code(500);
