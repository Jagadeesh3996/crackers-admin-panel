<?php
include('../utilities/db.php');

// Redirect if not POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: {$admin_url}/reports/");
    exit();
}

$json = $_POST['data'] ?? '[]';
$dataArray = json_decode($json, true);
// $filter = $_POST['filter'] ?? '[]';
// $filter = json_decode($filter, true);
// <table>
//     <tr>
//         <td><b>Filter by : </b></td>
//         <td colspan="8">' . $filter . '</td>
//     </tr>
// </table>

if (!$dataArray) {
    header("Location: {$admin_url}/reports/");
    exit();
}

// Set headers for Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=donors_report.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo '
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { text-align: center; }
    </style>

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
    echo "<tr>
        <td>{$sno}</td>
        <td>{$data['name']}</td>
        <td>{$data['phone']}</td>
        <td>{$data['address']}</td>
        <td>{$total}</td>
    </tr>";
    $sno++;
}

echo '</tbody></table>';
exit;
