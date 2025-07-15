<?php
require("../dompdf/autoload.inc.php");

use Dompdf\Dompdf;
use Dompdf\Options;

function pdfGenration($pitem, $oid, $view = false)
{
    include("../utilities/db.php");

    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $options->set('enable_html5_parser', true);
    $dompdf = new Dompdf($options);

    // Tell Dompdf about this font
    $dompdf->getOptions()->setChroot(__DIR__ . '/../');

    $date = $pitem['date'];
    $total = $pitem['total'];
    $pcharge = $pitem['packing_charge'];
    $promodiscount = $pitem['promotion_discount'];
    $finialtotal = $total + $pcharge - $promodiscount;
    $products = json_decode($pitem['products']);
    $dateonly = date("d-m-Y");

    // Create HTML content for the PDF
    $html = '<!DOCTYPE html>
                <html>
                    <head>
                        <title>Estimate Report</title>
                        <link rel="preconnect" href="https://fonts.googleapis.com">
                        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Tamil:wght@100..900&display=swap" rel="stylesheet">
                        <style>
                            @font-face {
                                font-family: "NotoSansTamil";
                                src: url("assets/fonts/NotoSansTamil.ttf") format("truetype");
                            }
                            .tamil {
                                font-family: "Noto Sans Tamil", sans-serif;
                                font-optical-sizing: auto;
                                font-weight: 400;
                                font-style: normal;
                                font-variation-settings: "wdth" 100;
                            }
                            body {
                                font-family: "NotoSansTamil";
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
                                    <td colspan="3" class="bline">Estimate No : <b>' . $oid . '</b></td>
                                    <td colspan="3" class="bline"><b>ESTIMATE REPORT</b></td>
                                    <td colspan="2" class="bline right">Date : <b>' . $date . '</b></td>
                                </tr>
                                <tr class="bd">
                                    <td colspan="4" class="bline">Phone : <b>' . $site_mobile_number . ' , ' . $site_whatsapp_number . ' </b></td>
                                    <td colspan="4" class="bline right">Email : <b>' . $site_email . '</b></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="bline"><center><img height="100" src="' .  $admin_url . '/assets/images/logo.png" alt="logo" ></center></td>
                                    <td colspan="4" class="bline"><center>
                                        <h2><b>' . $site_name . '</b></h2>
                                        <p>' . $site_address . '</p>
                                    </center></td>
                                    <td class="bline"></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="none rline">
                                        <h4><center><b>Customer Details</b></center></h4>
                                        <p class="tamil">Name : <b>' . $pitem['name'] . '</b></p>
                                        <p>Mobile : <b>' . $pitem['phone'] . '</b></p>
                                        <p>Whatsapp : <b>' . $pitem['whatsapp'] . '</b></p>
                                        <p>E-Mail Id : <b>' . $pitem['email'] . '</b></p>
                                        <p class="tamil">Address : <b>' . $pitem['address'] . '</b></p>
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
        $totalqty += $prd->p_quantity;
        $nettotal += $prd->p_nettotal;
        $p_id = $prd->p_id;
        $q2 = "SELECT * FROM tbl_product WHERE id = '$p_id' AND status >= 1 LIMIT 1";
        $res2 = mysqli_query($conn, $q2);
        $item = mysqli_fetch_array($res2);
        $html .= '<tr class="rbd">
                        <td>' . $k . '</td>
                        <td>' . $item['alignment'] . '</td>
                        <td colspan="2">' . $prd->p_name . '</td>
                        <td>' . number_format($prd->p_mrp, 2) . '</td>
                        <td>' . $prd->p_quantity . '</td>
                        <td>' . number_format($prd->p_price, 2) . '</td>
                        <td>' . number_format($prd->p_total, 2) . '</td>
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
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('letter', 'portrait');
    $dompdf->render();

    if ($view) {
        $dompdf->stream("'$site_name'_'$dateonly'.pdf", ["Attachment" => false]);
    } else {
        file_put_contents(__DIR__ . '/price_estimation_' . $oid . '.pdf', $dompdf->output());
        return true;
    }
}
