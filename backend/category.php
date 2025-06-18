<?php
include("../utilities/db.php");

function Fetch()
{
    extract($_REQUEST);
    global $conn;

    $query1 = "SELECT * FROM tbl_category WHERE id = '$id'";
    $result1 = mysqli_query($conn, $query1);

    if ($result1) {
        $data = mysqli_fetch_assoc($result1);
        echo json_encode(['status' => true, 'data' => $data]);
    } else {
        echo json_encode(['status' => false, 'error' => mysqli_error($conn)]);
    }
}

function Add()
{
    extract($_REQUEST);
    global $conn;

    $query2 = "INSERT INTO tbl_category (name, discount, alignment) VALUES ('$name', '$discount', '$alignment')";
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
    
    $query3 = "UPDATE tbl_category SET status = 0 WHERE id = '$id' ";
    // $query3 = "DELETE FROM tbl_category WHERE id = '$id'";

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
    
    $query4 = "UPDATE tbl_category SET name = '$name', discount = '$discount', alignment = '$alignment' WHERE id = '$id'";
    if (mysqli_query($conn, $query4)) {
        echo "Success";
    } else {
        echo "Error updating details! ";
    }
}

function Import()
{
    extract($_REQUEST);
    global $conn;

    // Define allowed headers (database columns)
    $allowed_headers = ['name', 'discount', 'alignment']; // Replace with actual column names


    // Check the image
    if (isset($_FILES['csv_file'])) {
        // Get file details
        $file_tmp = $_FILES['csv_file']['tmp_name'];

        // Read the CSV file
        $csv_data = array_map('str_getcsv', file($file_tmp));

        // Assuming the first row contains column headers
        $headers = array_shift($csv_data);

        // try {
        //     foreach ($csv_data as $row) {
        //         $query5 = "INSERT INTO tbl_category (" . implode(',', $headers) . ") VALUES ('" . implode("','", $row) . "')";
        //         if (!mysqli_query($conn, $query5)) {
        //             echo "Error: " . mysqli_error($conn);
        //         }
        //     }
        //     echo "Success";
        // } catch (Exception $e) {
        //     echo "Error: " . $e->getMessage();
        // }
        try {
            foreach ($csv_data as $row) {
                // Map row data to corresponding allowed headers
                $filtered_row = array_combine($headers, $row);
                $filtered_row = array_intersect_key($filtered_row, array_flip($allowed_headers));

                // Construct the SQL query
                $query5 = "INSERT INTO tbl_category (" . implode(',', array_keys($filtered_row)) . ") 
                           VALUES ('" . implode("','", array_values($filtered_row)) . "')";

                if (!mysqli_query($conn, $query5)) {
                    echo "Error: " . mysqli_error($conn);
                }
            }
            echo "Success";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "File Not Found!";
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

    case 'import':
        Import();
        break;
}
