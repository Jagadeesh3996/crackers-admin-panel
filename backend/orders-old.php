<?php
//Load Composer's autoloader
require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

    include("../db.php");
    include('../pdfgeneration/pdf.php');
    require('../PHPMailer/src/Exception.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

error_reporting(E_ALL);
ini_set('display_errors', 'on');


    function sendSms($name, $order_id, $final_total, $number){
        $mbl = $number;
        $message_content=urlencode("One Order From Customer Name $name.Estimate ID: $order_id Total Amount Rs $final_total SUNEGP");
        $key = "SIj8XncmVNNHMPPh";	
        $senderid="SUNEGP";	$route= 1;
        $templateid="1707168111242423658";
        $url = "https://sms.textspeed.in/vb/apikey.php?apikey=$key&senderid=$senderid&templateid=$templateid&number=$mbl&message=$message_content";
        file_get_contents($url);
    }

    function sendMail($name, $order_id, $final_total, $email, $sent_mail){
        $to = $email;
        $subject = "Price Estimation - reg";
    
        $message = "Hi ".$name."\n";
        $message .= "You have made Estimation for Rs. ".$final_total.".\n";
        $message .= "Your Estimation Id is ".$order_id.".\n";
        
        $headers = "From: ".$sent_mail."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        
        mail($to, $subject, $message, $headers);
    }
    
    function generatePdf($order_id)
    {
        global $conn;
        $query2 = "select * from tbl_orders where order_id='$order_id' and status>='1' limit 1";
        $result2 = mysqli_query($conn, $query2);
        if ($pitem = mysqli_fetch_array($result2)) {
        return pdfGenration($pitem, $order_id, true);
        } else {
            return false;
        }
    }

    
    function sendPhpMail($name, $order_id, $final_total, $email, $sent_mail) {
    
        $mail = new PHPMailer(true);
        $file_to_attach = __DIR__.'/../../../assets/pdf/';
        global $site_url;
        $message = "Hi " . $name . "\n";
        $message .= "You have made Estimation for Rs. " . $final_total . "<br/>";
        $message .= "Your Estimation Id is " . $order_id . "<br/>";
        
        try {
        $mail->AddReplyTo($sent_mail, 'Mnao Crackers');
        $mail->AddAddress($email, $name);
        $mail->SetFrom($sent_mail, 'Mnao Crackers');
        $mail->AddReplyTo($sent_mail, 'Mnao Crackers');
        $mail->Subject = 'Price Estimation - reg';
        $mail->IsHTML(true);
        $mail->Body = $message;
        $mail->AddAttachment($_SERVER['DOCUMENT_ROOT'].'/assets/pdf/price_estimation_'.$order_id.'.pdf', 'price_estimation_'.$order_id.'.pdf');    //Optional name
    
        $mail->Send();
        } catch (phpmailerException $e) {
          echo $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
          echo $e->getMessage(); //Boring error messages from anything else!
        }
    }

    function Add(){
        include("../db.php");
        extract($_REQUEST);
        global $conn;
        $jsonproducts = $conn->real_escape_string($products);

        //sendSms($name, $order_id, $final_total, $sms_number);
        //sendMail($name, $order_id, $final_total, $email, $sent_mail);

        // query
        $query1 = "INSERT INTO tbl_orders (date, name, phone, whatsapp, email, address, products, total, packing_charge, promotion_discount, final_total) 
        VALUES 
        ('$date', '$name', '$phone', '$whatsapp', '$email', '$address', '$jsonproducts', '$total', '$packing_charge', '$promotion_discount', '$final_total')";
        if (mysqli_query($conn, $query1)) {
            // Get the last inserted ID (auto-incremented)
            $lastInsertedId = $conn->insert_id;
            $order_id = $site_code.date('Y').'EST'.$lastInsertedId;
            $query = "UPDATE tbl_orders SET order_id = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('si', $order_id, $lastInsertedId);
            if (!$stmt->execute()) {
                echo 'Error generating Order Id !';
                exit();
            }   

            if(generatePdf($order_id)){
                sendSms($name, $order_id, $final_total, $sms_number);
                // sendPhpMail($name, $order_id, $final_total, $email, $sent_mail); 
            }
            echo "success";
        } else {
            echo "Error Placing Order!";
        }
    }

    function AddCt(){
        extract($_REQUEST);
        global $conn;
        // query
        $query2 = "INSERT INTO tbl_contact (first_name, last_name, mail, phone, message) VALUES ( '$fname', '$lname', '$mail', '$phone', '$message')";
        if (mysqli_query($conn, $query2)) {
            echo "success";
        } else {
            echo "Error!";
        }
    }


    function Delete($table) {
        extract($_REQUEST);
        global $conn;
        // query
        $query3 = "UPDATE $table SET status='0' WHERE id='$id'";
        if (mysqli_query($conn, $query3)) {
            echo "success";
        } else {
            echo "Error deleting Data!";
        }
    }

    function StatusChange($table) {
        extract($_REQUEST);
        global $conn;
        // query
        $query4 = "UPDATE $table SET status='$status' WHERE id='$id'";
        if (mysqli_query($conn, $query4)) {
            echo "success";
        } else {
            echo "Error Updating Status!";
        }
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
    }
?>