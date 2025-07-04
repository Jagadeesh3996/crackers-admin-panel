<?php
include("../utilities/db.php");

function Fetch()
{
    extract($_REQUEST);
    global $conn;
    // Query to fetch state details
    $query1 = "SELECT * FROM tbl_product WHERE id = '$id'";
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

function Add()
{
    extract($_REQUEST);
    global $conn;

    // store uploaded file in array
    $uploaded_files = [];

    // Check the image
    if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {

        // Choose a directory to store the uploaded images
        // $upload_directory = __DIR__ ."/../uploads";
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
        $uploaded_files = json_encode($uploaded_files);
    }

    $query2 = "INSERT INTO tbl_product (category, name, tamil_name, images, mrp, selling_price, type, url, alignment) VALUES ('$category', '$name', '$tamil_name', '$uploaded_files', '$mrp', '$selling_price', '$type', '$vurl', '$alignment')";
    $result2 = mysqli_query($conn, $query2);
    if ($result2) {
        echo 'Success';
    } else {
        echo 'Error in Adding Product!';
    }

    // if (!empty($_FILES['images'])) {
    //     // store uploaded file in array
    //     $uploaded_files = [];

    //     // Choose a directory to store the uploaded images
    //     $upload_directory = '../uploads';

    //     // Check if the directory already exists
    //     if (!is_dir($upload_directory)) {

    //         // Create the directory
    //         if (!mkdir($upload_directory, 0777, true)) {
    //             echo "Sorry! Failed to create directory.";
    //             exit();
    //         }
    //     }

    //     // Loop through each uploaded file
    //     foreach ($_FILES['images']['tmp_name'] as $key => $image_tmp_name) {
    //         $image = $_FILES['images']['name'][$key];
    //         $size = $_FILES['images']['size'][$key];
    //         $imageFileType = strtolower(pathinfo($image, PATHINFO_EXTENSION));
    //         $image_name = uniqid() . '.webp';

    //         // Move the uploaded image to the chosen directory
    //         $target_path = $upload_directory . '/' . $image_name;

    //         if ($size > (3 * 1024 * 1024)) { // Check file size 3mb
    //             echo "Sorry! File should be less than 3mb.";
    //             return;
    //         }

    //         // Create an image resource from the uploaded file
    //         switch ($imageFileType) {

    //             case 'jpg':
    //             case 'jpeg':
    //                 $image = @imagecreatefromjpeg($image_tmp_name);
    //                 break;
    //             case 'png':
    //                 $image = @imagecreatefrompng($image_tmp_name);
    //                 break;
    //             case 'gif':
    //                 $image = @imagecreatefromgif($image_tmp_name);
    //                 break;
    //             case 'webp':
    //                 $image = @imagecreatefromwebp($image_tmp_name);
    //                 break;
    //             default:
    //                 echo "Sorry! only JPG, JPEG, PNG, WEBP & GIF files are allowed.";
    //                 exit;
    //         }

    //         if (!$image) {
    //             echo "Sorry! Failed to read image.";
    //             exit;
    //         }

    //         if (!imagewebp($image, $target_path, 75)) {
    //             imagedestroy($image);
    //             echo "Sorry! Error in Uploading this file.";
    //             exit;
    //         }

    //         imagedestroy($image);
    //         $uploaded_files[] = $image_name;
    //     }
    //     $uploaded_files = json_encode($uploaded_files);

    //     $query2 = "INSERT INTO tbl_product (category, name, tamil_name, images, mrp, selling_price, type, url, alignment) VALUES ('$category', '$name', '$tamil_name', '$uploaded_files', '$mrp', '$selling_price', '$type', '$vurl', '$alignment')";
    //     $result2 = mysqli_query($conn, $query2);
    //     if ($result2) {
    //         echo 'Success';
    //     } else {
    //         echo 'Error in Uploading Product!';
    //     }
    // } else {
    //     echo "Sorry! File not found.";
    // };
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
    $query3 = "UPDATE tbl_product SET status = 0 WHERE id = '$id' ";
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

    // Check the image
    if (isset($_FILES['edit_image'])) {
        $editimage_tmp_name = $_FILES['edit_image']['tmp_name'];
        $editimage_name = uniqid() . '.' . pathinfo($_FILES['edit_image']['name'], PATHINFO_EXTENSION);
        $editimageFileType = strtolower(pathinfo($editimage_name, PATHINFO_EXTENSION));

        // Choose a directory to store the uploaded images
        $upload_directory = '../uploads/';
        // Move the uploaded image to the chosen directory
        $target_path = $upload_directory . $editimage_name;

        if (!getimagesize($editimage_tmp_name)) {   // Check if image file is a actual image
            echo "Sorry! File is not an image.";
            return;
        } else if ($_FILES["edit-image"]["size"] > (2 * 1024 * 1024)) { // Check file size max - 2mb
            echo "Sorry! File is too large.";
            return;
        } else if ($editimageFileType != "jpg" && $editimageFileType != "png" && $editimageFileType != "jpeg" && $editimageFileType != "gif") {  // Allow certain file formats
            echo "Sorry! only JPG, JPEG, PNG & GIF files are allowed.";
            return;
        } else {
            if (move_uploaded_file($editimage_tmp_name, $target_path)) {
                $query4 = "UPDATE tbl_product SET category='$edit_category', name='$edit_name', tamil_name='$edit_tamil_name', mrp='$edit_mrp', selling_price='$edit_selling_price', type='$edit_type', url='$edit_vurl', alignment='$edit_alignment', image ='$editimage_name' WHERE id = '$edit_id'";
            } else {
                echo "Sorry, there was an error in updating this data.";
            }
        }
    } else {
        $query4 = "UPDATE tbl_product SET category='$edit_category', name='$edit_name', tamil_name='$edit_tamil_name', mrp='$edit_mrp', selling_price='$edit_selling_price', type='$edit_type', url='$edit_vurl', alignment='$edit_alignment' WHERE id = '$edit_id'";
    }
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

    // Check the image
    if (isset($_FILES['csv_file'])) {
        // Get file details
        $file_tmp = $_FILES['csv_file']['tmp_name'];

        // Read the CSV file
        $csv_data = array_map('str_getcsv', file($file_tmp));

        // Assuming the first row contains column headers
        $headers = array_shift($csv_data);
        try {
            // Iterate through the CSV data and insert into the database
            foreach ($csv_data as $row) {
                $query6 = "INSERT INTO tbl_product (" . implode(',', $headers) . ") VALUES ('" . implode("','", $row) . "')";
                if (!mysqli_query($conn, $query6)) {
                    echo "Error: " . mysqli_error($conn);
                }
            }
            echo "Success";
        } catch (Exception $e) {
            echo "Error Uploading file!" . $e;
        }
    } else {
        echo "File Not Found!";
    }
}

// function SectionChange()
// {
//     extract($_REQUEST);
//     global $conn;
//     // query
//     $query12 = "UPDATE tbl_product SET section='$section' WHERE id='$id'";
//     if (mysqli_query($conn, $query12)) {
//         echo "success";
//     } else {
//         echo "Error Updating Section!";
//     }
// }

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

    // case 'section':
    //     SectionChange();
    //     break;

    default:
        echo "No Action Found";
        exit;
}
