<?php
include("../utilities/db.php");

function Login()
{
    extract($_REQUEST);
    global $conn;

    $stmt = $conn->prepare("SELECT id, name FROM tbl_admin WHERE mail = ? AND password = ? LIMIT 1");
    $stmt->bind_param("ss", $email, $pwd);
    $stmt->execute();
    $stmt->bind_result($id, $name);
    if ($stmt->fetch()) {
        session_start();
        $_SESSION['userId'] = $id;
        $_SESSION['userName'] = $name;
        echo 'Success';
    } else {
        echo 'Fail';
    }
    $stmt->close();
}

switch ($_REQUEST['req_type']) {
    case 'login':
        Login();
        break;
}
