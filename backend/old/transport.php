<?php
    include("../db.php");
    require('../vendor/autoload.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    function sendSms($transport, $lrno, $mobile, $number){
        $mbl = $number;
        $message_content=urlencode("One Order From Customer Name $transport.Estimate ID: $lrno Total Amount Rs $mobile SUNEGP");
        $key = "SIj8XncmVNNHMPPh";	
        $senderid="SUNEGP";	$route= 1;
        $templateid="1707168111242423658";
        $url = "https://sms.textspeed.in/vb/apikey.php?apikey=$key&senderid=$senderid&templateid=$templateid&number=$mbl&message=$message_content";
        file_get_contents($url);
    }
    
    function sendMail($transport, $lrno, $mobile, $email, $sender_mail){
        include("../db.php");
        $mail = new PHPMailer(true);

        $message = "Your Order Dispatched Transport : ".$transport."<br/>";
        $message .= "Transport Mobile Number : ".$mobile."<br/>";
        $message .= "LR No : ".$lrno."<br/>";
    
        try {
            $mail->AddReplyTo($sender_mail, $site_name);
            $mail->AddAddress($email);
            $mail->SetFrom($sender_mail, $site_name);
            $mail->Subject = 'Transport details - reg';
            $mail->IsHTML(true);
            $mail->Body = $message;

            if ($mail->Send()){
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    function Send(){
        extract($_REQUEST);
        global $conn;
        
        // Query to fetch state details
        $query4 = "SELECT * FROM tbl_transport_details WHERE id = '$transport' LIMIT 1";
        $result4 = mysqli_query($conn, $query4);
        $details4 = mysqli_fetch_assoc($result4);
        $tname = $details4['name'];

        // sendSms($transport, $lrno, $mobile, $number);
        $sendtp = sendMail($tname, $lrno, $mobile, $email, $sender_mail);

        // query
        if ($sendtp) {
            echo "Success";
        } else {
            echo "Error Sending Details";
        }
    }
    
    function Delete() {
        extract($_REQUEST);
        global $conn;
        
        // query
        $query1 = "UPDATE tbl_transport_details SET status = '0' WHERE id = '$id' ";
        if (mysqli_query($conn, $query1)) {
            echo "Success";
        } else {
            echo "Error deleting details!";
        }
    }
    
    function AddTD(){
        extract($_REQUEST);
        global $conn;
        
        // query
        $query2 = "INSERT INTO tbl_transport_details (name, number) VALUES ('$name', '$number')";
        if (mysqli_query($conn, $query2)) {
            echo "Success";
        } else {
            echo "Error adding details!";
        }
    }
    
    function GetTD(){
        extract($_REQUEST);
        global $conn;
        
        // Query to fetch state details
        $query3 = "SELECT * FROM tbl_transport_details WHERE id = '$id' LIMIT 1";
        $result3 = mysqli_query($conn, $query3);

        if ($result3) {
            $details = mysqli_fetch_assoc($result3);
            // Return state details in JSON format
            echo json_encode(['status' => true, 'data' => $details]);
        } else {
            // Return an error message in JSON format
            echo json_encode(['status' => false, 'error' => mysqli_error($conn)]);
        }

    }

    switch ($_REQUEST['req_type']) {

        case 'send':
            Send();
            break;
            
        case 'add_td':
            AddTD();
            break;
            
        case 'delete_td':
            Delete();
            break;
            
        case 'get_td':
            GetTD();
            break;

    }
?>