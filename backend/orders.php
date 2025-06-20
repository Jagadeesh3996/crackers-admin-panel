<?php
include("../utilities/db.php");
require('../vendor/autoload.php');
require('../dompdf/autoload.inc.php');

use Dompdf\Dompdf;

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

function generatePdf($oid)
{
    global $conn;

    // fetch data
    $q2 = "SELECT * FROM tbl_orders WHERE order_id = '$oid' AND status >= 1 LIMIT 1";
    $res2 = mysqli_query($conn, $q2);

    if ($pitem = mysqli_fetch_array($res2)) {
        include("../utilities/db.php");
        $dompdf = new Dompdf();
        $date = $pitem['date'];
        $c_name = $pitem['name'];
        $c_email = $pitem['email'];
        $c_mobile = $pitem['phone'];
        $c_whatsapp = $pitem['whatsapp'];
        $c_address = $pitem['address'];
        $total = $pitem['total'];
        $pcharge = $pitem['packing_charge'];
        $promodiscount = $pitem['promotion_discount'];
        $finialtotal = $total + $pcharge - $promodiscount;
        $products = json_decode($pitem['products']);

        // Create HTML content for the PDF
        $html = '
                    <!DOCTYPE html>
                    <html>
                        <head>
                            <title>Estimate Report</title>
                            <link rel="preconnect" href="https://fonts.googleapis.com">
                            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                            <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Tamil:wght@100..900&display=swap" rel="stylesheet">
                            <style>
                                .tamil {
                                  font-family: "Noto Sans Tamil", sans-serif;
                                  font-optical-sizing: auto;
                                  font-weight: 400;
                                  font-style: normal;
                                  font-variation-settings: "wdth" 100;
                                }
                                body {
                                    font-family: "Arial", sans-serif;
                                }
                                td{
                                    padding: 5px;
                                }
                                .bd{
                                    border: 2px solid black;
                                }
                                .pd-10{
                                    padding: 10px;
                                }
                                .rbd td{
                                    border: 2px solid black;
                                    text-align: center;
                                }
                                .fs-20{
                                    font-size: 20px;
                                }
                                .none h4,
                                .none p{
                                    margin:5px;
                                }
                                .rline{
                                    border-right: 2px solid black;
                                }
                                .bline{
                                    border-bottom: 2px solid black;
                                }
                                .tline{
                                    border-top: 2px solid black;
                                }
                                .right{
                                    text-align:right;
                                }
                                .none h4{
                                    text-decoration: underline;
                                }
                            </style>
                        </head>
                        <body>
                            <main class="main">
                                <table class="bd" style="width:100%" cellspacing="0">
                                    <tr class="bd">
                                        <td colspan="3" class="bline">Estimate No : <b>' . $oid . '</b></td>
                                        <td colspan="3" class="bline"><b>ESTIMATE REPORT</b></td>
                                        <td colspan="2" class="bline right">Date : <b>' . $date . '</b></td>
                                    </tr>
                                    <tr class="bd">
                                        <td colspan="4" class="bline">Phone : <b>' . $site_mobile_number . ' , ' . $site_whatsapp_number . ' </b></td>
                                        <td colspan="4" class="bline right">Email : <b>' . $site_email . '</b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="bline"><center><img height="100" src="' . $admin_url . '/assets/images/logo.png" alt="logo" ></center></td>
                                        <td colspan="4" class="bline"><center>
                                            <h2><b>' . $site_name . '</b></h2>
                                            <p>' . $site_address . '</p>
                                        </center></td>
                                        <td class="bline"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="none rline">
                                            <h4><center><b>Customer Details</b></center></h4>
                                            <p class="tamil">Name : <b>' . $c_name . '</b></p>
                                            <p>Mobile : <b>' . $c_mobile . '</b></p>
                                            <p>Whatsapp : <b>' . $c_whatsapp . '</b></p>
                                            <p>E-Mail Id : <b>' . $c_email . '</b></p>
                                            <p class="tamil">Address : <b>' . $c_address . '</b></p>
                                            <p class="tamil">Refer by : <b>' . $pitem['refer'] . '</b></p>
                                        </td>
                                        <td colspan="4" class="none">
                                            <h4><center><b>Bank Details</b></center></h4>
                                            <p>Acc Name : <b>' . $account_holder_name . '</b></p>
                                            <p>Acc Number : <b>' . $account_number . '</b></p>
                                            <p>IFSC Code : <b>' . $ifsc_code . '</b></p>
                                            <p>Bank Name : <b>' . $bank_name . '</b></p>
                                            <p>Branch : <b>' . $branch_name . '</b></p>
                                        </td>
                                    </tr>
                                    <tr class="rbd">
                                        <td><b>S.No</b></td>
                                        <td><b>Code</b></td>
                                        <td colspan="2" style="min-width:1000px"><b>Product Name</b></td>
                                        <td><b>MRP (Rs)</b></td>
                                        <td><b>Quantity</b></td>
                                        <td><b>Price (Rs)</b></td>
                                        <td><b>Amount (Rs)</b></td>
                                    </tr>';

        $k = 1;
        $totalqty = 0;
        $nettotal = 0;
        foreach ($products as $key => $prd) {
            $totalqty += $prd->prd_qty;
            $nettotal += $prd->prd_nettotal;
            $p_id = $prd->prd_id;
            $q2 = "SELECT * FROM tbl_product WHERE id = '$p_id' AND status >= 1 LIMIT 1";
            $res2 = mysqli_query($conn, $q2);
            $item = mysqli_fetch_array($res2);
            $html .= '<tr class="rbd">
                        <td>' . $k . '</td>
                        <td>' . $item['alignment'] . '</td>
                        <td colspan="2">' . $prd->prd_name . '</td>
                        <td>' . number_format($prd->prd_mrp, 2) . '</td>
                        <td>' . $prd->prd_qty . '</td>
                        <td>' . number_format($prd->prd_price, 2) . '</td>
                        <td>' . number_format($prd->prd_total, 2) . '</td>
                    </tr>';
            $k++;
        }
        $html .= '
                                    <tr class="right">
                                        <td colspan="7">Net Total (Rs) : </td>
                                        <td><b>' . number_format($nettotal, 2) . '</b></td>
                                    </tr>
                                    <tr class="right">
                                        <td colspan="7">Discount Price (Rs) : </td>
                                        <td><b>' . number_format(($nettotal - $total), 2) . '</b></td>
                                    </tr>
                                    <tr class="right">
                                        <td colspan="7">Sub Total (Rs) : </td>
                                        <td><b>' . number_format($total, 2) . '</b></td>
                                    </tr>
                                    <tr class="right">
                                        <td colspan="7">Packing Charge (Rs) : </td>
                                        <td><b>' . number_format($pcharge, 2) . '</b></td>
                                    </tr>
                                    <tr class="right">
                                        <td colspan="7">Promotion Discount (Rs) : </td>
                                        <td><b>' . number_format($promodiscount, 2) . '</b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="tline">Total items : <b>' . ($k - 1) . '</b></td>
                                        <td colspan="2" style="text-align:center;" class="tline">Total Quantity : <b>' . number_format($totalqty) . '</b></td>
                                        <td colspan="3" class="tline right"><b>Overall Total (Rs) : </b></td>
                                        <td colspan="1" class="tline right"><b>' . number_format($finialtotal, 2) . '</b></td>
                                    </tr>
                                </table>
                            </main>
                        </body>
                    </html>';

        // Initialize dompdf
        $dompdf->set_option('enable_html5_parser', TRUE);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();
        $pdfOutput = $dompdf->output();
        file_put_contents(__DIR__ . '/../price_estimation.pdf', $pdfOutput);
        return true;
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
        $mail->AddReplyTo($site_email, $site_name);
        $mail->AddAddress($email, $name);
        $mail->addBCC($site_email, 'Admin');
        $mail->SetFrom($site_email, $site_name);
        $mail->Subject   = 'Price Estimation - reg';
        $mail->IsHTML(true);
        $mail->Body = $message;
        $mail->AddAttachment(__DIR__ . '/../price_estimation.pdf', 'Price_Estimation_' . $order_id . '.pdf');    //Optional name

        if ($mail->Send()) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}

function Add()
{
    extract($_REQUEST);
    global $conn;

    // Begin transaction
    $conn->begin_transaction();

    try {
        include("../utilities/db.php");
        // Prepared statement to prevent SQL injection
        $query = "INSERT INTO tbl_orders (date, name, phone, whatsapp, email, address, refer, products, total, packing_charge, promotion_discount, final_total) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssssssssiiii', $date, $name, $phone, $whatsapp, $email, $address, $refer, $products, $total, $packing_charge, $promotion_discount, $final_total);

        // Execute the prepared statement
        if ($stmt->execute()) {

            // Get the last inserted ID (auto-incremented)
            $lastInsertedId = $conn->insert_id;

            $order_id = $site_code . date('Y') . 'EST' . $lastInsertedId;

            $query = "UPDATE tbl_orders SET order_id = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('si', $order_id, $lastInsertedId);
            if (!$stmt->execute()) {
                echo 'Error generating Order Id !';
                exit();
            }

            // Send SMS first (optional, can be done after insert)
            sendSms($name, $order_id, $final_total, $site_mobile_number);

            // Generate PDF and send mail after successful insert
            if (!generatePdf($order_id)) {
                echo 'Error generating PDF !';
                exit();
            }

            if (!sendPhpMail($name, $order_id, $final_total, $email)) {
                echo 'Error Sending mail !';
                exit();
            }

            // Commit the transaction
            $conn->commit();

            // Return the last inserted ID
            echo json_encode(['status' => 'success', 'order_id' => $order_id]);
        } else {
            // If the query fails, rollback the transaction
            echo "Error: " . $stmt->error;
        }
    } catch (Exception $e) {
        // Rollback transaction if any error occurs
        $conn->rollback();
        echo "Transaction failed: " . $e->getMessage();
    }
}

function AddCt()
{
    extract($_REQUEST);
    global $conn;
    // query
    $query2 = "INSERT INTO tbl_contact (name, mail, phone, message) VALUES ( '$name', '$mail', '$phone', '$message')";
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
