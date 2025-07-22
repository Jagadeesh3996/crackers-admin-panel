<?php
include('../utilities/session.php');
require('../dompdf/autoload.inc.php');
include("../utilities/db.php");

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$dompdf->set('isRemoteEnabled', true);
$options->set('enable_html5_parser', true);
$dompdf = new Dompdf($options);

// check url parameter  is set or not
if (isset($_GET['bid'])) {
    $bid = $_GET['bid'];
} else {
    header("Location: $admin_url/pages/billing/");
    exit();
}

// fetch data
$query = "SELECT * FROM tbl_billing WHERE bid = '$bid' AND status >= 1 LIMIT 1";
$result = mysqli_query($conn, $query);
if ($item = mysqli_fetch_array($result)) {
    $c_name = $item['name'];
    $c_mobile = $item['mobile'];
    $c_whatsno = $item['whatsapp'];
    $c_idproof = $item['id_proof'];
    $c_idnumber = $item['id_number'];
    $c_bdate = $item['date'];
    $c_mop = $item['payment'];
    $c_address = $item['address'];
    $c_gst = $item['gst'];
    $c_pcamt = $item['p_charge'];
    $c_roAmt = $item['amount'];
    $c_prdlist = json_decode($item['product_list']);
    $dateonly = date("d-m-Y");

    // Create HTML content for the PDF
    $html = '
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>Billing Report</title>
                        <link rel="preconnect" href="https://fonts.googleapis.com">
                        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Tamil:wght@100..900&display=swap" rel="stylesheet">
                        <style>
                            body {
                                font-family: "Noto Sans Tamil", sans-serif;
                                font-size: 14px;
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
                                    <td colspan="3" class="bline">Billing No : <b>' . $bid . '</b></td>
                                    <td colspan="2" class="bline"><b>BILLING REPORT</b></td>
                                    <td colspan="3" class="bline right">Billing Date : <b>' . $c_bdate . '</b></td>
                                </tr>
                                <tr class="bd">
                                    <td colspan="4" class="bline">Phone : <b>' . $site_mobile_number . ' , ' . $site_whatsapp_number . ' </b></td>
                                    <td colspan="4" class="bline right">Email : <b>' . $site_email . '</b></td>
                                </tr>
                                <tr>
                                    <td colspan="1" class="bline"><center><img height="100" src="' . $admin_url . '/assets/images/logo.png" alt="logo" ></center></td>
                                    <td colspan="6" class="bline"><center>
                                        <h2><b>' . $site_name . '</b></h2>
                                        <p>' . $site_address . '</p>
                                    </center></td>
                                    <td class="bline"></td>
                                </tr>
                                <tr>
                                    <td colspan="8" class="none rline">
                                        <h4><center><b>Customer Details</b></center></h4>
                                        <p>Name : <b>' . $c_name . '</b></p>
                                        <p>Mobile: <b>' . $c_mobile . '</b></p>
                                        <p>Whatsapp : <b>' . $c_whatsno . '</b></p>
                                        <p>Id Proof : <b>' . $c_idproof . '</b></p>
                                        <p>Id Number : <b>' . $c_idnumber . '</b></p>
                                        <p>Mode of Payment : <b>' . $c_mop . '</b></p>
                                        <p>Address : <b>' . $c_address . '</b></p>
                                    </td>
                                </tr>
                                <tr class="rbd">
                                    <td><b>S.No</b></td>
                                    <td><b>Code</b></td>
                                    <td style="min-width:150px"><b>Product Name</b></td>
                                    <td><b>MRP (Rs)</b></td>
                                    <td><b>Quantity</b></td>
                                    <td><b>Discount (%)</b></td>
                                    <td><b>Price (Rs)</b></td>
                                    <td><b>Total (Rs)</b></td>
                                </tr>';
    $k = 1;
    $totalqty = 0;
    $total = 0;
    foreach ($c_prdlist as $key => $prod) {
        $totalqty += $prod->p_qty;
        $total += $prod->p_total;
        $p_id = $prod->p_id;
        $qu2 = "SELECT * FROM tbl_product WHERE id = '$p_id' LIMIT 1";
        $re2 = mysqli_query($conn, $qu2);
        $data = mysqli_fetch_array($re2);
        $html .= '<tr class="rbd">
                                                <td>' . $k . '</td>
                                                <td>' . $data['alignment'] . '</td>
                                                <td>' . $prod->p_name . '</td>
                                                <td>' . number_format($prod->p_mrp) . '</td>
                                                <td>' . number_format($prod->p_qty) . '</td>
                                                <td>' . $prod->p_dis . '</td>
                                                <td>' . number_format($prod->p_disprice, 2) . '</td>
                                                <td>' . number_format($prod->p_total, 2) . '</td>
                                            </tr>';
        $k++;
    }
    $html .= '
                                <tr class="right">
                                    <td colspan="7">Total (Rs) : </td>
                                    <td><b>' . number_format($total, 2) . '</b></td>
                                </tr>
                                <tr class="right">
                                    <td colspan="7">GST (Rs) : </td>
                                    <td><b>' . number_format($c_gst, 2) . '</b></td>
                                </tr>
                                <tr class="right">
                                    <td colspan="7">Packing Charge (Rs) : </td>
                                    <td><b>' . number_format($c_pcamt, 2) . '</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="tline">Total items : <b>' . ($k - 1) . '</b></td>
                                    <td colspan="2" style="text-align:center;" class="tline">Total Quantity : <b>' . $totalqty . '</b></td>
                                    <td colspan="3" class="tline right"><b>Final Amount (Rs) : </b></td>
                                    <td colspan="1" class="tline right"><b>' . number_format($c_roAmt) . '</b></td>
                                </tr>
                            </table>
                        </main>
                    </body>
                </html>';

    // Initialize dompdf
    $dompdf->loadHtml($html);
    $dompdf->setPaper('letter', 'portrait');
    $dompdf->render();
    $dompdf->stream("'$site_name'_'$bid'_'$dateonly'.pdf", ["Attachment" => false]);
} else {
    header("Location: $admin_url/pages/billing/");
    exit();
}
