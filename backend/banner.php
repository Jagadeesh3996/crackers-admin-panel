<?php
include("../utilities/db.php");

function Add($table)
{
    extract($_REQUEST);
    global $conn;

    // Check the image
    if (isset($_FILES['image'])) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = uniqid() . '.webp';
        // $image_name = uniqid() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageFileType = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        // Choose a directory to store the uploaded images
        $upload_directory = '../uploads/homebanners/';

        // Move the uploaded image to the chosen directory
        $target_path = $upload_directory . $image_name;

        // Check if the directory already exists
        if (!file_exists($upload_directory)) {

            // Create the directory
            if (!mkdir($upload_directory, 0777, true)) {
                echo "Sorry! Failed to create directory.";
                exit();
            }
        }

        // Create an image resource from the uploaded file
        switch ($imageFileType) {

            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($image_tmp_name);
                break;
            case 'png':
                $image = imagecreatefrompng($image_tmp_name);
                break;
            case 'gif':
                $image = imagecreatefromgif($image_tmp_name);
                break;
            case 'webp':
                $image = imagecreatefromwebp($image_tmp_name);
                break;
            default:
                echo "Sorry! only JPG, JPEG, PNG, WEBP & GIF files are allowed.";
                exit;
        }


        if ($_FILES["image"]["size"] > (3 * 1024 * 1024)) { // Check file size max - 3mb
            echo "Sorry! File should be less than 3 mb.";
            return;
        } else {
            if (imagewebp($image, $target_path)) {
                $addquery = "INSERT INTO $table (banner) VALUES ('$image_name')";
                $addresult = mysqli_query($conn, $addquery);
                if ($addresult) {
                    echo 'Success';
                } else {
                    echo "Sorry! Error in Adding this data.";
                }
            } else {
                echo "Sorry, there was an error in uploading this file.";
            }

            // if (move_uploaded_file($image_tmp_name, $target_path)) {
            //     $query4 =  "UPDATE tbl_portfolio_details SET thump_path='$save_path' WHERE id = '$new_id'";
            //     $result4 = mysqli_query($conn, $query4);
            //     if ($result4) {
            //         echo 'Success';
            //     } else {
            //         echo 'Sorry! Error in Uploading this data.';
            //     }
            // } else {
            //     echo "Sorry! Error in Adding this data.";
            // }
        }
    } else {
        echo "Sorry! File not found.";
    };
}

function Delete($table)
{
    extract($_REQUEST);
    global $conn;

    // Delete the database record
    // $deleteQuery = "UPDATE $table SET status = 0 WHERE id = '$id'";
    $deleteQuery = "DELETE FROM $table WHERE id = '$id'";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if ($deleteResult) {
        // Delete the image file from server
        $imagePath = "../uploads/homebanners/" . $name;
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        echo 'Success';
    } else {
        echo 'Sorry, there was an error in deleting this record.';
    }
}

switch ($_REQUEST['req_type']) {
    case 'addhb':
        Add('tbl_homebanner');
        break;

    case 'deletehb':
        Delete('tbl_homebanner');
        break;
}
