<?php
    include("../db.php");

    function GetPrd() {
        global $conn;
        // query
        $query1 = "select * from tbl_product where status>=1 group by name asc";
        $result1 = mysqli_query($conn, $query1);
        $object = [];
        if ($result1) {
            while ($row = mysqli_fetch_assoc($result1)) {
                array_push($object ,$row);
            }
            echo json_encode(['success' => true, 'product' => $object]);
        } else {
            echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
        }
    }

    function Add() {
        extract($_REQUEST);
        global $conn;
        $prdlist = $conn->real_escape_string($prdlist);

        // query
        $query2 = "INSERT INTO tbl_billing (name, bid, mobile, whatsapp, id_proof, id_number, date, address, payment, product_list, gst, p_charge, amount) VALUES ('$name', '$bid', '$mobile', '$whatsapp', '$idproof', '$idnumber', '$date', '$address', '$mop', '$prdlist', '$gst', '$packamt', '$amount')";
        if (mysqli_query($conn, $query2)) {
            echo "success";
        } else {
            echo "Error in Bill Creating!";
        }
    }

    function AddInvoice() {
        extract($_REQUEST);
        global $conn;
        $prdlist = $conn->real_escape_string($prdlist);
        
        // query
        $query22 = "INSERT INTO tbl_invoice (name, invoice, mobile, whatsapp, id_proof, id_number, date, address, payment, product_list, gstno, igst, sgst, p_charge, amount) VALUES ('$name', '$invoice', '$mobile', '$whatsapp', '$idproof', '$idnumber', '$date', '$address', '$mop', '$prdlist', '$gstno', '$igst', '$sgst', '$packamt', '$amount')";
        if (mysqli_query($conn, $query22)) {
            echo "success";
        } else {
            echo "Error in Invoice Creating!";
        }
    }

    function Delete($table) {
        extract($_REQUEST);
        global $conn;
        // query
        $query3 = "UPDATE $table SET status='0' WHERE id='$id'";
        if (mysqli_query($conn, $query3)) {
            echo "success";
        } else {
            echo "Error deleting Data!";
        }
    }

    function StatusChange($table) {
        extract($_REQUEST);
        global $conn;
        // query
        $query4 = "UPDATE $table SET status='$status' WHERE id='$id'";
        if (mysqli_query($conn, $query4)) {
            echo "success";
        } else {
            echo "Error Updating Status!";
        }
    }

    switch ($_REQUEST['req_type']) {
        case 'getitem':
            GetPrd();
            break;

        case 'addbill':
            Add();
            break;

        case 'addinvoice':
            AddInvoice();
            break;    

        case 'delete':
            Delete('tbl_billing');
            break;

        case 'deleteinvoice':
            Delete('tbl_invoice');
            break;

        case 'status':
            StatusChange('tbl_billing');
            break;

        case 'statusinvoice':
            StatusChange('tbl_invoice');
            break;    
    }
?>