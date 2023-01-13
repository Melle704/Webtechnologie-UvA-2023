<?php

// credentials of test database
$db_hostname = "localhost";
$db_user_name = "admin";
$db_passwd = "mLqXRHVJ7B2c";
$db_name = "test";

$db = mysqli_connect($db_hostname, $db_user_name, $db_passwd, $db_name);

if (!$db) {
    return_home("failed to connect to database: " . mysqli_connect_error());
}
