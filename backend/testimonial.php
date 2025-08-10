<?php
include("../utilities/db.php");

function Add()
{
    extract($_REQUEST);
    global $conn;

    // Check the image
    if (isset($_FILES['image']) && is_array($_FILES['image']['name'])) {

        // Choose a directory to store the uploaded images
        $upload_directory = realpath(__DIR__ . "/../uploads");

        // Check if the directory already exists
        if (!is_dir($upload_directory)) {

            // Create the directory
            if (!mkdir($upload_directory, 0777, true)) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Failed to create directory'
                ]);
                exit();
            }
        }

        // store all images in loop
        foreach ($_FILES['image']['tmp_name'] as $key => $tmp_name) {
            $image_name = uniqid() . '.webp';
            $target_path = $upload_directory . '/' . $image_name;

            if ($_FILES['image']['size'][$key] > (3 * 1024 * 1024)) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Files exceed the 3MB'
                ]);
                exit;
            }

            if (move_uploaded_file($tmp_name, $target_path)) {
                $query = "INSERT INTO tbl_testimonial (name, review, image) VALUES ('$name', '$review', '$image_name')";
                $result = mysqli_query($conn, $query);
                if ($result) {
                    echo json_encode([
                        'status' => true,
                        'message' => 'Data Uploaded Successfully'
                    ]);
                } else {
                    echo json_encode([
                        'status' => false,
                        'message' => 'Failed to upload data'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => 'Failed to upload file'
                ]);
                exit;
            }
        }
    } else {
        echo json_encode([
            'status' => false,
            'message' => 'File not found'
        ]);
    };
}

function Delete()
{
    extract($_REQUEST);
    global $conn;

    $query2 = "DELETE FROM tbl_testimonial WHERE id = '$id'";
    $result2 = mysqli_query($conn, $query2);
    if ($result2) {
        // Delete the image file from server
        $imagePath = "../uploads/" . $name;
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
    if (isset($_FILES['image']) && is_array($_FILES['image']['name'])) {

        // Choose a directory to store the uploaded images
        $upload_directory = realpath(__DIR__ . "/../uploads");

        // Check if the directory already exists
        if (!is_dir($upload_directory)) {

            // Create the directory
            if (!mkdir($upload_directory, 0777, true)) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Failed to create directory'
                ]);
                exit();
            }
        }


        // store all images in loop
        foreach ($_FILES['image']['tmp_name'] as $key => $tmp_name) {
            $imagePath = $upload_directory . '/' . $image_name;

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $image_name = uniqid() . '.webp';
            $target_path = $upload_directory . '/' . $image_name;

            if ($_FILES['image']['size'][$key] > (3 * 1024 * 1024)) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Files exceed the 3MB'
                ]);
                exit;
            }

            if (!move_uploaded_file($tmp_name, $target_path)) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Failed to upload file'
                ]);
                exit;
            }
        }
    };

    $query4 = "UPDATE tbl_testimonial SET name = '$name', review = '$review', image = '$image_name' WHERE id = '$id'";
    $result4 = mysqli_query($conn, $query4);
    if ($result4) {
        echo json_encode([
            'status' => true,
            'message' => 'Data Uploaded Successfully'
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'message' => 'Failed to upload data'
        ]);
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
