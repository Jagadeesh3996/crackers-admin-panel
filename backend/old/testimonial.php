<?php
    include("../db.php");

    function Add(){
        extract($_REQUEST);
        global $conn;

        // Check the image
        if (isset($_FILES['image'])) {
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_name = uniqid() . '.' . pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION);
            $imageFileType = strtolower(pathinfo($image_name,PATHINFO_EXTENSION));
            
            // Choose a directory to store the uploaded images
            $upload_directory = '../uploads/';
            // Move the uploaded image to the chosen directory
            $target_path = $upload_directory . $image_name;

            if(!getimagesize($image_tmp_name)) {   // Check if image file is a actual image
                echo "Sorry! File is not an image.";
                return;
            } else if ($_FILES["image"]["size"] > (2 * 1024 * 1024)) { // Check file size
                echo "Sorry! File is too large.";
                return;
            }else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {  // Allow certain file formats
                echo "Sorry! only JPG, JPEG, PNG files only are allowed.";
                return;
            } else {
                if (move_uploaded_file($image_tmp_name, $target_path)) {
                    $query = "INSERT INTO tbl_testimonial (name, review, image) VALUES ('$name', '$review', '$image_name')";
                    $result = mysqli_query($conn, $query);
                    if ($result) {
                        echo 'Success';
                    } else {
                        echo 'Error in Uploading Details!';
                    }
                } else {
                    echo "Sorry, there was an error in uploading this data.";
                }
            }
        } else {
            echo 'Details Uploading failed';
        };
    }

    function Delete(){
        extract($_REQUEST);
        global $conn;

        $query2 = "UPDATE tbl_testimonial SET status = 0 WHERE id = '$id'";
        $result2 = mysqli_query($conn, $query2);
        if ($result2) {
            echo 'Success';
        } else {
            echo 'Sorry, there was an error in deleting this data.';
        }
    }

    function Fetch(){
        extract($_REQUEST);
        global $conn;

        $query3 = "SELECT * FROM tbl_testimonial WHERE id = '$id' and status = '1'";
        $result3 = mysqli_query($conn, $query3);
        if ($result3) {
            $data = mysqli_fetch_assoc($result3);
            echo json_encode(['status' => true, 'data' => $data]);
        } else {
            echo json_encode(['status' => false, 'error' => mysqli_error($conn), 'query' => $query]);
        }
    }

    function Edit(){
        extract($_REQUEST);
        global $conn;

        // Check the image
        if (isset($_FILES['editImage'])) {
            $editimage_tmp_name = $_FILES['editImage']['tmp_name'];
            $editimage_name = uniqid() . '.' . pathinfo($_FILES['editImage']['name'],PATHINFO_EXTENSION);
            $editimageFileType = strtolower(pathinfo($editimage_name,PATHINFO_EXTENSION));
            
            // Choose a directory to store the uploaded images
            $upload_directory = '../uploads/';
            // Move the uploaded image to the chosen directory
            $target_path = $upload_directory . $editimage_name;

            if(!getimagesize($editimage_tmp_name)) {   // Check if image file is a actual image
                echo "Sorry! File is not an image.";
                return;
            } else if ($_FILES["editImage"]["size"] > (2 * 1024 * 1024)) { // Check file size
                echo "Sorry! File is too large.";
                return;
            }else if($editimageFileType != "jpg" && $editimageFileType != "png" && $editimageFileType != "jpeg" && $editimageFileType != "gif" ) {  // Allow certain file formats
                echo "Sorry! only JPG, JPEG, PNG & GIF files are allowed.";
                return;
            } else {
                if (move_uploaded_file($editimage_tmp_name, $target_path)) {
                    $query4 = "UPDATE tbl_testimonial SET name='$name', review='$review', image ='$editimage_name' WHERE id = '$id'";
                } else {
                    echo "Sorry, there was an error in updating this data.";
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
?>