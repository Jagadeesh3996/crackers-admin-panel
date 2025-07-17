<?php

include('../utilities/db.php');
require('../dompdf/autoload.inc.php');

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('enable_html5_parser', true);
$dompdf = new Dompdf($options);

// Tell Dompdf about this font
$dompdf->getOptions()->setChroot(__DIR__ . '/../');


// Create HTML content for the PDF
$html = '
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>Price List</title>
                        <style>
                            @font-face {
                                font-family: "NotoSansTamil";
                                src: url("assets/fonts/NotoSansTamil.ttf") format("truetype");
                            }
                            body {
                                font-family: "NotoSansTamil";
                                font-size: 14px;
                            }
                            table tr th,
                            table tr td{
                                padding: 5px !important;
                                vertical-align: middle !important;
                                border: 1px solid #000 !important;
                                line-height: 15px;
                            }
                            .cat{
                                font-weight: 600 !important;
                            }
                            .w-big{
                                width:300px;
                            }
                            .w-med{
                                width:100px;
                            }
                            .w-small{
                                width: 50px;
                            }
                            .br-0 {
                                border-right: none !important;
                            }
                            .bl-0 {
                                border-left: none !important;
                            }
                        </style>
                    </head>
                    <body>
                        <main>';
$html .= '
                            <table class="table-bordered text-center border">
                                <tr>
                                    <td colspan="2" class="br-0"><center><img height="100" src="' .  $site_url . '/assets/images/logo.png" alt="logo" ></center></td>
                                    <td colspan="4" class="p-0 bl-0"><h2 class="mb-0">' . $site_name . '<br/><a href="' . $site_url . '" target="_blank">(' . $domain . ')</a></h2></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="p-0"><h5 class="mb-0">' . $site_address . '</h5></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="p-0"><h5 class="mb-0">Whatsapp number: ' . $site_whatsapp_number . ', Mobile number: ' . $site_mobile_number . '</h5></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="p-0"><h5 class="mb-0">Discount upto 80% offer! Hurry Up....</h5></td>
                                </tr>
                                <thead class="text-uppercase bg-danger text-white">
                                    <tr>
                                        <th>S.No</th>
                                        <th class="w-big">Product Name</th>
                                        <th class="w-med">MRP Price (Rs)</th>
                                        <th>Discount Price (Rs)</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>';
$pquery = "SELECT * FROM tbl_category WHERE status = 1 GROUP BY CAST(SUBSTRING_INDEX(alignment, ' ', 1) AS UNSIGNED), SUBSTRING(alignment, LOCATE(' ', alignment) + 1)";
$presult = mysqli_query($conn, $pquery);
$sno = 1;
while ($row = mysqli_fetch_array($presult)) {
    $c_id = $row['id'];
    $category = $row['name'];
    $discount  = $row['discount'] ? $row['discount'] : "0";
    $queryitems = "SELECT * FROM tbl_product WHERE category='$category' AND status = 1 GROUP BY CAST(SUBSTRING_INDEX(alignment, ' ', 1) AS UNSIGNED), SUBSTRING(alignment, LOCATE(' ', alignment) + 1)";
    $resultitems = mysqli_query($conn, $queryitems);
    $row_count = mysqli_num_rows($resultitems);
    if ($row_count !== 0) {
        $html .= '
                                                    <tr class="pt-0">
                                                        <td colspan="6" class="bg-info text-white cat">' . $category . ' (' . $discount . '%)</td>
                                                    </tr>';
        while ($items = mysqli_fetch_array($resultitems)) {
            $mrp = $items['mrp'];
            $disprice = round($mrp - ($mrp * ($discount / 100)), 2);
            $html .= '
                                                                <tr>
                                                                    <td class="w-small">' . $sno . '</td>
                                                                    <td>' . $items['name'] . ' (' . $items['type'] . ')</td>
                                                                    <td><s>' . $mrp . '</s></td>
                                                                    <td>' . $disprice . '</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>';
            $sno++;
        }
    }
}
$html .= '
                                </tbody>
                            </table>
                        </main>
                    </body>
                </html>';

// Initialize dompdf
$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();
$dompdf->stream("Price_List_'.$site_name.'.pdf", ["Attachment" => false]);
