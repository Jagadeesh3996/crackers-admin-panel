<?php
include("../utilities/session.php");
include("../utilities/db.php");
include("../pdf/pdf.php");

// check url parameter  is set or not
if (isset($_GET['oid'])) {
    $oid = $_GET['oid'];
} else {
    header("Location: $admin_url/orders/");
    exit();
}

global $conn;
$query = "SELECT * FROM tbl_orders WHERE order_id = '$oid' AND status >= 1 LIMIT 1";
$result = mysqli_query($conn, $query);
if ($pitem = mysqli_fetch_array($result)) {
    return pdfGenration($pitem, $oid, true);
} else {
    header("Location: $admin_url/orders/");
    exit();
}
