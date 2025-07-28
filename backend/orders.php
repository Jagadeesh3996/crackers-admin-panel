<?php
include("../utilities/db.php");
require("../vendor/autoload.php");
include("../pdf/pdf.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendSms($name, $order_id, $final_total, $number)
{
    $mbl = $number;
    $message_content = urlencode("One Order From Customer Name $name.Estimate ID: $order_id Total Amount Rs $final_total SUNEGP");
    $key = "SIj8XncmVNNHMPPh";
    $senderid = "SUNEGP";
    $route = 1;
    $templateid = "1707168111242423658";
    $url = "https://sms.textspeed.in/vb/apikey.php?apikey=$key&senderid=$senderid&templateid=$templateid&number=$mbl&message=$message_content";
    file_get_contents($url);
}

function generatePdf($order_id)
{
    global $conn;
    $query = "SELECT * FROM tbl_orders WHERE order_id = '$order_id' AND status >= 1 LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($pitem = mysqli_fetch_array($result)) {
        return pdfGenration($pitem, $order_id);
    } else {
        return false;
    }
}

function sendPhpMail($name, $order_id, $final_total, $email)
{
    include("../utilities/db.php");
    $mail = new PHPMailer(true);
    $message = "Hi " . $name . "<br/>";
    $message .= "You have made Estimation for Rs. " . $final_total . "<br/>";
    $message .= "Your Estimation Id is " . $order_id . "<br/>";
    $message .= "Please find the attachment for Estimation details.";

    try {
        // SMTP configuration
        // $mail->isSMTP();
        // $mail->Host = 'smtp.gmail.com';           // Replace with your SMTP server
        // $mail->SMTPAuth = true;
        // $mail->Username = 'example@gmail.com';        // SMTP username
        // $mail->Password = 'dert cyaw esdr nxud';        // SMTP password
        // $mail->SMTPSecure = 'ssl';                     // 'tls' or 'ssl'
        // $mail->Port = 465;                             // 587 for TLS, 465 for SSL

        // Sender and recipient
        $mail->setFrom($site_email, $site_name);
        $mail->addReplyTo($site_email, $site_name);
        $mail->addAddress($email, $name);
        $mail->addCC($site_email, 'Admin');

        $mail->Subject = 'Price Estimation - reg';
        $mail->IsHTML(true);
        $mail->Body = $message;

        // Attachment (only if the file exists)
        $pdfPath = __DIR__ . '/../pdf/pdf/price_estimation_' . $order_id . '.pdf';
        if (file_exists($pdfPath)) {
            $mail->addAttachment($pdfPath, 'price_estimation_' . $order_id . '.pdf');           //Optional name
        }

        // Send the email
        $mail->send();
    } catch (Exception $e) {
        // Log error, but do not return
        error_log("Mailer Error: " . $mail->ErrorInfo);
    }
}

function Add()
{
    extract($_REQUEST);
    global $conn;

    $date = date("Y-m-d");

    // Begin transaction
    $conn->begin_transaction();

    try {
        include("../utilities/db.php");

        // Prepared statement to prevent SQL injection
        $query = "INSERT INTO tbl_orders (date, name, phone, whatsapp, email, address, refer, products, total, packing_charge, promotion_discount, final_total) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param('ssssssssiiii', $date, $name, $phone, $whatsapp, $email, $address, $refer, $products, $total, $packing_charge, $promotion_discount, $final_total);

        // Execute the prepared statement
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        // Get the last inserted ID (auto-incremented)
        $lastInsertedId = $conn->insert_id;

        $order_id = $site_code . date('Y') . 'EST' . $lastInsertedId;

        $query2 = "UPDATE tbl_orders SET order_id = ? WHERE id = ?";
        $stmt2 = $conn->prepare($query2);
        if (!$stmt2) {
            throw new Exception("Prepare failed (update): " . $conn->error);
        }

        $stmt2->bind_param('si', $order_id, $lastInsertedId);
        if (!$stmt2->execute()) {
            throw new Exception("Execute failed (update): " . $stmt2->error);
        }

        // Send SMS first (optional, can be done after insert)
        sendSms($name, $order_id, $final_total, $site_mobile_number);
        generatePdf($order_id);
        sendPhpMail($name, $order_id, $final_total, $email);

        // Commit the transaction
        $conn->commit();

        // Return the last inserted ID
        echo json_encode(['status' => true, 'order_id' => $order_id]);
    } catch (Exception $e) {
        // Rollback transaction if any error occurs
        $conn->rollback();
        echo json_encode(['status' => false, 'message' => "Transaction failed: " . $e->getMessage()]);
    }
}

function AddCt()
{
    extract($_REQUEST);
    global $conn;
    // query
    $query2 = "INSERT INTO tbl_contact (fname, lname, mail, phone, message) VALUES ( '$fname', '$lname', '$mail', '$phone', '$message')";
    if (mysqli_query($conn, $query2)) {
        echo "success";
    } else {
        echo "Error!";
    }
}

function Delete($table)
{
    extract($_REQUEST);
    global $conn;
    // query
    $query3 = "UPDATE $table SET status = 0 WHERE id = '$id'";
    // $query3 = "DELETE FROM $table WHERE id = '$id'";
    if (mysqli_query($conn, $query3)) {
        echo "success";
    } else {
        echo "Error deleting Data!";
    }
}

function StatusChange($table)
{
    extract($_REQUEST);
    global $conn;
    // query
    $query4 = "UPDATE $table SET status = '$status' WHERE id = '$id'";
    if (mysqli_query($conn, $query4)) {
        echo "success";
    } else {
        echo "Error Updating Status!";
    }
}

function GetStatus()
{
    extract($_REQUEST);
    global $conn;

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT order_id, final_total, status
                            FROM tbl_orders
                            WHERE (email = ? OR phone = ?)
                              AND order_id = ?");
    $stmt->bind_param("sss", $user_input, $user_input, $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        echo json_encode(['status' => true, 'data' => $row]);
    } else {
        echo json_encode(['status' => false, 'message' => 'No Order Found']);
    }

    $stmt->close();
}

switch ($_REQUEST['req_type']) {

    case 'add':
        Add();
        break;

    case 'addct':
        AddCt();
        break;

    case 'delete':
        Delete('tbl_orders');
        break;

    case 'deletect':
        Delete('tbl_contact');
        break;

    case 'status':
        StatusChange('tbl_orders');
        break;

    case 'statusct':
        StatusChange('tbl_contact');
        break;

    case 'getStatus':
        GetStatus();
        break;

    default:
        echo 'No Action Found';
        break;
}
