<?php
include("../utilities/db.php");

function Add()
{
    extract($_REQUEST);
    global $conn;

    // store uploaded file in array
    $uploaded_files = [];

    // Check the image
    if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {

        // Choose a directory to store the uploaded images
        $upload_directory = realpath(__DIR__ . "/../uploads");

        // Check if the directory already exists
        if (!is_dir($upload_directory)) {

            // Create the directory
            if (!mkdir($upload_directory, 0777, true)) {
                echo "Sorry! Failed to create directory.";
                exit();
            }
        }

        // store all images in loop
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $image_name = uniqid() . '.webp';
            $target_path = $upload_directory . '/' . $image_name;

            if ($_FILES['images']['size'][$key] > (3 * 1024 * 1024)) {
                echo "One or more files exceed the 3MB.";
                exit;
            }

            if (move_uploaded_file($tmp_name, $target_path)) {
                $uploaded_files[] = $image_name;
            } else {
                echo "Failed to upload";
                exit;
            }
        }
    }
    $uploaded_files = json_encode($uploaded_files);

    $query2 = "INSERT INTO tbl_product (category, name, tamil_name, images, mrp, selling_price, type, url, alignment) VALUES ('$category', '$name', '$tamil_name', '$uploaded_files', '$mrp', '$selling_price', '$type', '$vurl', '$alignment')";
    $result2 = mysqli_query($conn, $query2);
    if ($result2) {
        echo 'Success';
    } else {
        echo 'Error in Adding Product!';
    }
}

function AddImages()
{
    extract($_REQUEST);
    global $conn;

    // store uploaded file in array
    $uploaded_files = [];

    // Check the image
    if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {

        // Choose a directory to store the uploaded images
        $upload_directory = realpath(__DIR__ . "/../uploads");

        // Check if the directory already exists
        if (!is_dir($upload_directory)) {

            // Create the directory
            if (!mkdir($upload_directory, 0777, true)) {
                echo "Sorry! Failed to create directory.";
                exit();
            }
        }

        // store all images in loop
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $image_name = uniqid() . '.webp';
            $target_path = $upload_directory . '/' . $image_name;

            if ($_FILES['images']['size'][$key] > (3 * 1024 * 1024)) {
                echo "One or more files exceed the 3MB.";
                exit;
            }

            if (move_uploaded_file($tmp_name, $target_path)) {
                $uploaded_files[] = $image_name;
            } else {
                echo "Failed to upload";
                exit;
            }
        }

        // Decode the JSON string to an array
        $decoded_oldimages = json_decode($oldimages, true); // true gives associative array

        if (is_array($decoded_oldimages)) {
            $uploaded_files = array_merge($uploaded_files, $decoded_oldimages);
        }

        $uploaded_files = json_encode($uploaded_files);
        $query2 = "UPDATE tbl_product SET images = '$uploaded_files' WHERE id = '$edit_id' ";
        $result2 = mysqli_query($conn, $query2);
        if ($result2) {
            echo 'Success';
        } else {
            echo 'Error in Adding Product!';
        }
    } else {
        echo 'No Files Found!';
    }
}

function DeleteImg()
{
    extract($_REQUEST);
    global $conn;

    // query
    $query3 = "UPDATE tbl_product SET images = '$images' WHERE id = '$id' ";
    if (mysqli_query($conn, $query3)) {

        // Delete the image file from server
        $imagePath = "../uploads/" . $img_name;
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        echo "Success";
    } else {
        echo "Error deleting details!";
    }
}

function Delete()
{
    extract($_REQUEST);
    global $conn;
    // query
    $query3 = "DELETE FROM tbl_product WHERE id = '$id'";

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

    $query4 = "UPDATE tbl_product SET category='$edit_category', name='$edit_name', tamil_name='$edit_tamil_name', mrp='$edit_mrp', selling_price='$edit_selling_price', type='$edit_type', url='$edit_vurl', alignment='$edit_alignment' WHERE id = '$edit_id'";
    $result4 = mysqli_query($conn, $query4);
    if ($result4) {
        echo 'Success';
    } else {
        echo 'Error in updating data!';
    }
}

function Change()
{
    extract($_REQUEST);
    global $conn;
    // query
    $query5 = "UPDATE tbl_product SET status = '$status' WHERE id = '$id' ";
    if (mysqli_query($conn, $query5)) {
        echo "Success";
    } else {
        echo "Error in Changing Product Availability!";
    }
}

function Import()
{
    extract($_REQUEST);
    global $conn;

    // Define allowed headers (database columns)
    $allowed_headers = ['category', 'name', 'tamil_name', 'images', 'mrp', 'selling_price', 'type', 'url', 'alignment']; // Replace with actual column names

    // Check the image
    if (isset($_FILES['csv_file'])) {
        // Get file details
        $file_tmp = $_FILES['csv_file']['tmp_name'];

        // Read the CSV file
        $csv_data = array_map('str_getcsv', file($file_tmp));

        // Assuming the first row contains column headers
        $headers = array_shift($csv_data);
        // try {
        //     // Iterate through the CSV data and insert into the database
        //     foreach ($csv_data as $row) {
        //         $query6 = "INSERT INTO tbl_product (" . implode(',', $headers) . ") VALUES ('" . implode("','", $row) . "')";
        //         if (!mysqli_query($conn, $query6)) {
        //             echo "Error: " . mysqli_error($conn);
        //         }
        //     }
        //     echo "Success";
        // } catch (Exception $e) {
        //     echo "Error Uploading file!" . $e;
        // }
        try {
            foreach ($csv_data as $row) {
                // Map row data to corresponding allowed headers
                $filtered_row = array_combine($headers, $row);
                $filtered_row = array_intersect_key($filtered_row, array_flip($allowed_headers));

                // Escape each value to prevent SQL errors
                $escaped_values = array_map(function ($value) use ($conn) {
                    return mysqli_real_escape_string($conn, $value);
                }, array_values($filtered_row));

                // Construct the SQL query
                $query5 = "INSERT INTO tbl_product (" . implode(',', array_keys($filtered_row)) . ") 
                           VALUES ('" . implode("','", array_values($escaped_values)) . "')";

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

// below are frontend (website) ajax calls

function GetData()
{
    global $conn;
    // query
    $query7 = "SELECT p.*, c.discount FROM tbl_product p JOIN tbl_category c ON p.category = c.name WHERE c.status = 1 AND p.status = 1 
                    GROUP BY CAST(SUBSTRING_INDEX(p.alignment, ' ', 1) AS UNSIGNED), SUBSTRING(p.alignment, LOCATE(' ', p.alignment) + 1) ASC";
    $result7 = mysqli_query($conn, $query7);

    $object1 = [];
    if ($result7) {
        while ($row7 = mysqli_fetch_assoc($result7)) {
            array_push($object1, $row7);
        }
        echo json_encode(['status' => true, 'products' => $object1]);
    } else {
        echo json_encode(['status' => false, 'error' => mysqli_error($conn)]);
    }
}

function FilterData()
{
    extract($_REQUEST);
    global $conn;
    // query
    $query8 = "SELECT p.*, c.discount FROM tbl_product p JOIN tbl_category c ON p.category = c.name WHERE p.status = 1 and c.status = 1 and p.category IN ('" . implode("','", $catName) . "')
                    GROUP BY CAST(SUBSTRING_INDEX(p.alignment, ' ', 1) AS UNSIGNED), SUBSTRING(p.alignment, LOCATE(' ', p.alignment) + 1) ASC";
    $result8 = mysqli_query($conn, $query8);

    $object2 = [];
    if ($result8) {
        while ($row8 = mysqli_fetch_assoc($result8)) {
            array_push($object2, $row8);
        }
        echo json_encode(['status' => true, 'products' => $object2]);
    } else {
        echo json_encode(['status' => false, 'error' => mysqli_error($conn)]);
    }
}

function FilterPCData()
{
    extract($_REQUEST);
    global $conn;
    // query
    $query9 = "SELECT p.*, c.discount FROM tbl_product p JOIN tbl_category c ON p.category = c.name WHERE p.status = 1 and c.status = 1 AND p.category IN ('" . implode("','", $catName) . "') AND p.mrp BETWEEN '$min_mrp' AND '$max_mrp'
                    GROUP BY CAST(SUBSTRING_INDEX(p.alignment, ' ', 1) AS UNSIGNED), SUBSTRING(p.alignment, LOCATE(' ', p.alignment) + 1) ASC";
    $result9 = mysqli_query($conn, $query9);

    $object3 = [];
    if ($result9) {
        while ($row9 = mysqli_fetch_assoc($result9)) {
            array_push($object3, $row9);
        }
        echo json_encode(['status' => true, 'products' => $object3]);
    } else {
        echo json_encode(['status' => false, 'error' => mysqli_error($conn)]);
    }
}

function GetPData()
{
    extract($_REQUEST);
    global $conn;
    // query
    $query10 = "SELECT p.*, c.discount FROM tbl_product p JOIN tbl_category c ON p.category = c.name WHERE p.status = 1 and c.status = 1 AND p.mrp BETWEEN '$min_mrp' AND '$max_mrp'
                    GROUP BY CAST(SUBSTRING_INDEX(p.alignment, ' ', 1) AS UNSIGNED), SUBSTRING(p.alignment, LOCATE(' ', p.alignment) + 1) ASC";
    $result10 = mysqli_query($conn, $query10);

    $object4 = [];
    if ($result10) {
        while ($row10 = mysqli_fetch_assoc($result10)) {
            array_push($object4, $row10);
        }
        echo json_encode(['status' => true, 'products' => $object4]);
    } else {
        echo json_encode(['status' => false, 'error' => mysqli_error($conn)]);
    }
}


function GetPrd()
{
    extract($_REQUEST);
    global $conn;
    // Query to fetch state details
    $query11 = "SELECT p.*, c.discount FROM tbl_product p JOIN tbl_category c ON p.category = c.name WHERE p.status = 1 and c.status = 1 AND p.id = '$id'
                    GROUP BY CAST(SUBSTRING_INDEX(p.alignment, ' ', 1) AS UNSIGNED), SUBSTRING(p.alignment, LOCATE(' ', p.alignment) + 1) ASC";
    $result11 = mysqli_query($conn, $query11);

    if ($result11) {
        $data = mysqli_fetch_assoc($result11);
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

    case 'deletImg':
        DeleteImg();
        break;

    case 'addImages':
        AddImages();
        break;

    case 'fetch':
        Fetch();
        break;

    case 'edit':
        Edit();
        break;

    case 'change':
        Change();
        break;

    case 'import':
        Import();
        break;

    case 'getData':
        GetData();
        break;

    case 'getPData':
        GetPData();
        break;

    case 'filterPCData':
        FilterPCData();
        break;

    case 'filterData':
        FilterData();
        break;

    case 'getPrd':
        GetPrd();
        break;

    default:
        echo "No Action Found";
        exit;
}
