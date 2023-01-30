<?php
include_once "include/payment.php";
include_once "include/common.php";
include_once "include/db.php";

// Always send same response to prevent leaking valid payment ids.
http_response_code(200);

if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST["id"])) {
    exit;
}

$status = payment_status($_POST["id"]);

if ($status === false) {
    exit;
}

$sql = "UPDATE purchases SET status=? WHERE mollie_id=?";
query_execute($db, $sql, "ss", $status, $_POST["id"]);
