<?php
    include("../db.php");

    function Add(){
        extract($_REQUEST);
        global $conn;
        // query
        $query1 = "INSERT INTO tbl_section (section) VALUES ('$section')";
        if (mysqli_query($conn, $query1)) {
            echo "Success";
        } else {
            echo "Error adding details!";
        }
    }

    function Delete() {
        extract($_REQUEST);
        global $conn;
        // query
        $query2 = "UPDATE tbl_section SET status = '0' WHERE id = '$id' ";
        if (mysqli_query($conn, $query2)) {
            echo "Success";
        } else {
            echo "Error deleting details!";
        }
    }

    function GetData(){
        extract($_REQUEST);
        global $conn;
        ($section == 'all') ? $q = "p.section != 'none' AND p.section != 'Top Trending'" : $q = "p.section = '$section'"; 
        // Query to fetch state details
        $query3 = "SELECT p.*, c.discount FROM tbl_product p JOIN tbl_category c ON p.category = c.name WHERE $q AND p.status = '1' 
        GROUP BY CAST(SUBSTRING_INDEX(p.alignment, ' ', 1) AS UNSIGNED), SUBSTRING(p.alignment, LOCATE(' ', p.alignment) + 1) ASC limit 12";
        $result3 = mysqli_query($conn, $query3);

        $data = [];
        if ($result3) {
            while ($row = mysqli_fetch_assoc($result3)) {
                array_push($data ,$row);
            }
            // Return state details in JSON format
            echo json_encode(['status' => true, 'data' => $data]);
        } else {
            // Return an error message in JSON format
            echo json_encode(['status' => false, 'error' => mysqli_error($conn)]);
        }
    }

    switch ($_REQUEST['req_type']) {
        case 'add':
            Add();
            break;

        case 'delete':
            Delete();
            break;

        case  'getData':
            GetData();
            break;
    }
?>