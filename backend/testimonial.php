<?php
include("../utilities/db.php");

function Add()
{
    extract($_REQUEST);
    global $conn;

    // Check the image
    if (isset($_FILES['image'])) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $image_name = uniqid() . '.webp';

        // Choose a directory to store the uploaded images
        $upload_directory = '../uploads/testimonials';

        // Move the uploaded image to the chosen directory
        $target_path = $upload_directory . '/' . $image_name;

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

        if (!$image) {
            echo "Sorry! Failed to read image.";
            exit;
        }

        if ($_FILES["image"]["size"] > (3 * 1024 * 1024)) { // Check file size
            echo "Sorry! File should be less than 3mb.";
            return;
        } else {
            if (imagewebp($image, $target_path)) {
                imagedestroy($image);
                $query = "INSERT INTO tbl_testimonial (name, review, image) VALUES ('$name', '$review', '$image_name')";
                $result = mysqli_query($conn, $query);
                if ($result) {
                    echo 'Success';
                } else {
                    echo "Sorry! Error in Adding this data.";
                }
            } else {
                echo "Sorry, there was an error in uploading this file.";
            }
        }
    } else {
        echo "Sorry! File not found.";
    };
}

function Delete()
{
    extract($_REQUEST);
    global $conn;

    // $query2 = "UPDATE tbl_testimonial SET status = 0 WHERE id = '$id'";
    $query2 = "DELETE FROM tbl_testimonial WHERE id = '$id'";
    $result2 = mysqli_query($conn, $query2);
    if ($result2) {
        // Delete the image file from server
        $imagePath = "../uploads/testimonials/" . $name;
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        echo 'Success';
    } else {
        echo 'Sorry, there was an error in deleting this data.';
    }
}

function Fetch()
{
    extract($_REQUEST);
    global $conn;

    $query3 = "SELECT * FROM tbl_testimonial WHERE id = '$id' AND status = 1";
    $result3 = mysqli_query($conn, $query3);
    if ($result3) {
        $data = mysqli_fetch_assoc($result3);
        echo json_encode(['status' => true, 'data' => $data]);
    } else {
        echo json_encode(['status' => false, 'error' => mysqli_error($conn), 'query' => $query]);
    }
}

function Edit()
{
    extract($_REQUEST);
    global $conn;

    // Check the image
    if (isset($_FILES['editImage'])) {
        $image_tmp_name = $_FILES['editImage']['tmp_name'];
        $imageFileType = strtolower(pathinfo($_FILES['editImage']['name'], PATHINFO_EXTENSION));

        // Choose a directory to store the uploaded images
        $upload_directory = '../uploads/testimonials';

        // Move the uploaded image to the chosen directory
        $target_path = $upload_directory . '/' . $image_name;

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

        if (!$image) {
            echo "Sorry! Failed to read image.";
            exit;
        }


        if ($_FILES["editImage"]["size"] > (3 * 1024 * 1024)) { // Check file size
            echo "Sorry! File should be less than 3mb.";
            return;
        } else {
            if (imagewebp($image, $target_path)) {
                imagedestroy($image);
                $query4 = "UPDATE tbl_testimonial SET name='$name', review='$review', image ='$image_name' WHERE id = '$id'";
            } else {
                echo "Sorry, there was an error in updating this file.";
            }
        }
    } else {
        $query4 = "UPDATE tbl_testimonial SET name='$name', review='$review' WHERE id = '$id'";
    };
    $result4 = mysqli_query($conn, $query4);
    if ($result4) {
        echo 'Success';
    } else {
        echo 'Error in updating Details!';
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
