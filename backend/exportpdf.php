<?php
require('../dompdf/autoload.inc.php');
include('../utilities/db.php');

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('enable_html5_parser', true);
$dompdf = new Dompdf($options);

// Redirect if not POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: {$admin_url}/reports/");
    exit();
}

$json = $_POST['data'] ?? '[]';
$dataArray = json_decode($json, true);
// $filter = $_POST['filter'] ?? '[]';
// $filter = json_decode($filter, true);
// <h4>Filter by : ' . htmlspecialchars($filter) . '</h4>

if (!$dataArray) {
    header("Location: {$admin_url}/reports/");
    exit();
}

$html = '
            <!DOCTYPE html>
            <html lang="en" dir="ltr">
                 <head>
                    <title>REPORTS</title>
                    <style>
                        body {
                            border: 0;
                            margin: 0;
                            font-family: "Arial", sans-serif;
                            font-size: 12px;
                        }
                        h1 { text-align: center; }
                        td, th { padding: 5px; border: 1px solid #000; text-align: center; }
                        table { width: 100%; border-collapse: collapse; }
                        th { background-color: #f2f2f2; }
                        tr {
                            page-break-inside: avoid;
                        }
                    </style>
                </head>
                <body>
                    <h1>Reports</h1>
                    <table>
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Number</th>
                                <th>Address</th>
                                <th>Order value (Rs)</th>
                            </tr>
                        </thead>
                        <tbody>';
$sno = 1;
foreach ($dataArray as $data) {
    $total = number_format($data['final_total']);
    $html .= "<tr>
                <td>{$sno}</td>
                <td>{$data['name']}</td>
                <td>{$data['phone']}</td>
                <td>{$data['address']}</td>
                <td>{$total}</td>
            </tr>";
    $sno++;
}
$html .= '</tbody></table></body></html>';

$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Add page numbers
$canvas = $dompdf->getCanvas();
$font = $dompdf->getFontMetrics()->getFont('Arial', 'normal');
$canvas->page_text(520, 820, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0, 0, 0));  // Adjust X,Y as needed

header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=reports.pdf");

echo $dompdf->output();
exit;
