<?php

$redirect_title="Logging out";
$redirect_msg="You are being logged out..";
include_once "include/redirect.php";

session_start();
session_destroy();

// wait two seconds before refreshing
header("Refresh: 2; url=/");
exit;
