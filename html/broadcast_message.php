<?php

session_start();

// ensure you can't try to send a message if you aren't logged in
if (!isset($_SESSION["id"])) {
    http_response_code(404);
    exit;
}

require_once "include/db.php";
include_once "include/common.php";

logout_user_on_inactivity($db);

if (!isset($_SESSION["messages_consumed"])) {
    $_SESSION["messages_consumed"] = 0;
}

if ($_GET["action"] == "send") {
    $message = file_get_contents("php://input");
    $message = htmlspecialchars(trim($message));

    // message is empty
    if (strlen($message) == 0) {
        exit;
    }

    // message too long
    if (strlen($message) > 255) {
        http_response_code(300);
        exit;
    }

    send_message($db, $_SESSION["id"], $message);
    exit;
}

if ($_GET["action"] == "receive") {
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
        $user = find_user_by_uid($db, $entry["uid"]);

        $s .= "\n            <span class=\"message\">";

        // add a link to the profile's of other users
        if ($_SESSION["id"] != $entry["uid"]) {
            $s .= '<a target="_blank" href="/profile.php?id=' . $entry["uid"]. '">';
        }

        $s .= '<b class="message-content">'
            . $user["uname"]
            . "</b>";

        // add a link to the profile's of other users
        if ($_SESSION["id"] != $entry["uid"]) {
            $s .= "</a>";
        }

        $s .= '<div class="message-content">'
            . ": "
            . $entry["msg"]
            . "</div>"
            . "</span>"
            . "\n";

        $s .= "            \n";
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
