<?php
include('./db.php');
session_start();
$_SESSION['userId'];
unset($_SESSION['userId']);
unset($_SESSION['userName']);
session_destroy();
header("Location: $admin_url/login/");
exit();
