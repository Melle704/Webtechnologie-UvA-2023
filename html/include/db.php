<?php

$hostname = "localhost";
$user_name = "admin";
$passwd = "mLqXRHVJ7B2c";
$db_name = "test";

$db = mysqli_connect($hostname, $user_name, $passwd, $db_name);

if (!$db) {
    return_home("failed to connect to database: " . mysqli_connect_error());
}
