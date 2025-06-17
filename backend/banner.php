<?php
include("../utilities/db.php");

function Add($table)
{
    extract($_REQUEST);
    global $conn;

    // Check the image
    if (isset($_FILES['image'])) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = uniqid() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageFileType = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        // Choose a directory to store the uploaded images
        $upload_directory = '../uploads/homebanners/';
        // Move the uploaded image to the chosen directory
        $target_path = $upload_directory . $image_name;

        if (!getimagesize($image_tmp_name)) {   // Check if image file is a actual image
            echo "Sorry! File is not an image.";
            return;
        } else if ($_FILES["image"]["size"] > (3 * 1024 * 1024)) { // Check file size max - 3mb
            echo "Sorry! File is too large.";
            return;
        } else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {  // Allow certain file formats
            echo "Sorry! only JPG, JPEG, PNG & GIF files are allowed.";
            return;
        } else {
            if (move_uploaded_file($image_tmp_name, $target_path)) {
                $addquery = "INSERT INTO $table (banner) VALUES ('$image_name')";
                $addresult = mysqli_query($conn, $addquery);
                if ($addresult) {
                    echo 'Success';
                } else {
                    echo 'Error in Banner Uploading!';
                }
            } else {
                echo "Sorry, there was an error in uploading this file.";
            }
        }
    } else {
        echo 'Banner Uploading failed';
    };
}

function Delete($table)
{
    extract($_REQUEST);
    global $conn;
    
    // Delete the database record
    $deleteQuery = "UPDATE $table SET status = 0 WHERE id = '$id'";
    // $deleteQuery = "DELETE FROM $table WHERE id = '$id'";
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
