<?php
    include("../db.php");

    function Add(){
        extract($_REQUEST);
        global $conn;
        // query
        $query2 = "INSERT INTO tbl_scrollbar (name) VALUES ('$name')";
        if (mysqli_query($conn, $query2)) {
            echo "Success";
        } else {
            echo "Error adding details!";
        }
    }

    function Delete() {
        extract($_REQUEST);
        global $conn;
        // query
        $query3 = "UPDATE tbl_scrollbar SET status = '0' WHERE id = '$id' ";
        if (mysqli_query($conn, $query3)) {
            echo "Success";
        } else {
            echo "Error deleting details!";
        }
    }


    switch ($_REQUEST['req_type']) {
        case 'add':
            Add();
            break;

        case 'delete':
            Delete();
            break;
    }
?>