<?php
    include("../db.php");

    function Fetch(){
        extract($_REQUEST);
        global $conn;
        // Query to fetch state details
        $query1 = "SELECT * FROM tbl_promocode WHERE id = '$id'";
        $result1 = mysqli_query($conn, $query1);

        if ($result1) {
            $data = mysqli_fetch_assoc($result1);
            // Return state details in JSON format
            echo json_encode(['status' => true, 'data' => $data]);
        } else {
            // Return an error message in JSON format
            echo json_encode(['status' => false, 'error' => mysqli_error($conn)]);
        }
    }

    function Add(){
        extract($_REQUEST);
        global $conn;
        // query
        $query2 = "INSERT INTO tbl_promocode (agent, promocode, discount) VALUES ('$agent', '$code', '$discount')";
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
        $query3 = "UPDATE tbl_promocode SET status = '0' WHERE id = '$id' ";
        if (mysqli_query($conn, $query3)) {
            echo "Success";
        } else {
            echo "Error deleting details!";
        }
    }

    function Edit(){
        extract($_REQUEST);
        global $conn;
        // query
        $query4 = "UPDATE tbl_promocode SET agent = '$agent', promocode = '$code', discount = '$discount' WHERE id = '$id'";
        if (mysqli_query($conn, $query4)) {
            echo "Success";
        } else {
            echo "Error updating details! ";
        }
    }

    function CheckCode(){
        extract($_REQUEST);
        global $conn;
        // query
        $query5 = "select * from tbl_promocode where promocode='$promocode' and  status=1";
        $result5 =  mysqli_query($conn, $query5);
        
        if (mysqli_num_rows($result5)>0){
            $row5 = mysqli_fetch_array($result5);
            echo $row5['discount'];
        } else {
            echo "No Discount";
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

        case 'checkCode':
            CheckCode();
            break;    
    }
?>