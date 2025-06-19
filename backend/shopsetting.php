<?php
include("../utilities/db.php");

function UpShop()
{
    extract($_REQUEST);
    global $conn;
    $data = json_encode($data);
    $scanner = json_encode($scanner);
    $bank = json_encode($bank);
    // query
    $query = "UPDATE tbl_shopsetting SET shop_details = '$data', scanner_details = '$scanner', bank_details = '$bank' WHERE id = 1 AND status = 1";
    if (mysqli_query($conn, $query)) {
        echo "success";
    } else {
        echo "Error Updating Shop Settings!";
    }
}

function WebStatus()
{
    extract($_REQUEST);
    global $conn;
    // query
    $query = "UPDATE tbl_shopsetting SET website_status = '$website_status' WHERE id = 1 AND status = 1";
    if (mysqli_query($conn, $query)) {
        echo "success";
    } else {
        echo "Error Updating Shop Settings!";
    }
}

switch ($_REQUEST['req_type']) {
    case 'up_shop':
        UpShop();
        break;

    case 'web_status':
        WebStatus();
        break;
};
