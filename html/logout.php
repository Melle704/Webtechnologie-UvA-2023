<?php

// TODO: show a message for a second or two to indicate a logout is in process.

include_once "include/common.php";

session_start();
session_destroy();
home();
