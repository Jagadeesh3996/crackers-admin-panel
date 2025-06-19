<?php
include("../utilities/db.php");

function Fetch()
{
    extract($_REQUEST);
    global $conn;
    // Query to fetch state details
    $query1 = "SELECT * FROM tbl_state WHERE id = '$state_id'";
    $result1 = mysqli_query($conn, $query1);

    if ($result1) {
        $state = mysqli_fetch_assoc($result1);
        // Return state details in JSON format
        echo json_encode(['status' => true, 'data' => $state]);
    } else {
        // Return an error message in JSON format
        echo json_encode(['status' => false, 'error' => mysqli_error($conn)]);
    }
}

function Add()
{
    extract($_REQUEST);
    global $conn;
    // query
    $query2 = "INSERT INTO tbl_state (state, minimum_order_value, max_order_value, packing_charge) VALUES ('$statename', '$movalue', '$mxvalue', '$pcharge')";
    if (mysqli_query($conn, $query2)) {
        echo "Success";
    } else {
        echo "Error adding details!";
    }
}

function Delete()
{
    extract($_REQUEST);
    global $conn;
    // query
    $query3 = "UPDATE tbl_state SET status = 0 WHERE id = '$state_id' ";
    // $query3 = "DELETE FROM tbl_state WHERE id = '$state_id'";
    if (mysqli_query($conn, $query3)) {
        echo "Success";
    } else {
        echo "Error deleting details!";
    }
}

function Edit()
{
    extract($_REQUEST);
    global $conn;
    // query
    $query4 = "UPDATE tbl_state SET state = '$statename', minimum_order_value = '$movalue', max_order_value = '$mxvalue', packing_charge = '$pcharge' WHERE id = '$state_id'";
    if (mysqli_query($conn, $query4)) {
        echo "Success";
    } else {
        echo "Error updating details! ";
    }
}

switch ($_REQUEST['req_type']) {
    case 'add':
        Add();
        break;

    case 'delete':
        Delete();
        break;

    case 'fetch':
        Fetch();
        break;

    case 'edit':
        Edit();
        break;
}
