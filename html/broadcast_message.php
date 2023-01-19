<?php

include_once "include/common.php";

session_start();

// ensure you can't try to send a message if you aren't logged in
if (!isset($_SESSION["id"])) {
    http_response_code(404);
    exit;
}

if (!isset($_SESSION["messages_consumed"])) {
    $_SESSION["messages_consumed"] = 0;
}

if ($_GET["action"] == "send") {
    require_once "include/db.php";

    $message = file_get_contents("php://input");
    $message = htmlspecialchars(trim($message));

    // message too long
    if (strlen($message) > 255) {
        http_response_code(300);
        exit;
    }

    send_message($db, $_SESSION["id"], $message);
    exit;
}

if ($_GET["action"] == "receive") {
    require_once "include/db.php";

    $entries = messagebox_messages($db);
    $entry_count = count($entries);

    // chat is cleared / message is deleted
    if ($_SESSION["messages_consumed"] > $entry_count) {
        // probably refresh the message log here
        http_response_code(300);
        exit;
    }

    $new_entries = array_slice($entries, $_SESSION["messages_consumed"]);

    foreach ($new_entries as $entry) {
        $s .= '<a href="/profile.php?id='
            . $entry["uid"]
            . '"'
            . "><b>"
            . "admin"
            . "</b>"
            . ": "
            . $entry["msg"]
            . "\n";
    }

    $_SESSION["messages_consumed"] = $entry_count;

    echo $s;
    exit;
}

if ($_GET["action"] == "receive_log") {
    require_once "include/db.php";

    $sql = "SELECT * from messages";
    $query = mysqli_query($db, $sql);

    while ($row = mysqli_fetch_array($query)) {
        $s .= $row["msg"] . "\n";
    }

    $now = time() - 1;
    $now = new DateTime("@$now");
    $now = $now->format("Y-m-d h:i:s");
    $_SESSION["last_request"] = $now;

    echo "$s";
    exit;
}

http_response_code(500);
