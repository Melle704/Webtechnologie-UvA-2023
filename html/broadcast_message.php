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

if (!isset($_SESSION["messages_consumed"])) {
    $_SESSION["messages_consumed"] = 0;
}

if ($_GET["action"] == "reset") {
    $_SESSION["messages_consumed"] = 0;
    exit;
}

if ($_GET["action"] == "send") {
    $text = file_get_contents("php://input");
    $text = htmlspecialchars(trim($text));

    // message too long or empty
    if (strlen($text) > 255 || strlen($text) == 0) {
        http_response_code(300);
        exit;
    }

    if ($text == "/clear" && $_SESSION["role"] == "admin") {
        mysqli_query($db, "DELETE FROM messages");
        exit;
    }

    echo send_message($db, $_SESSION["id"], $text);
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
    foreach ($new_messages as &$message) {
        $message = ["id" => $message["id"], "body" => format_message($db, $message)];
    }

    $_SESSION["messages_consumed"] = $msg_count;

    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($new_messages);
    exit;
}

http_response_code(500);
