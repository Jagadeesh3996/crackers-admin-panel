<?php
include("../utilities/db.php");

function exportCategory()
{
    global $conn;

    // Query to fetch column names from your table
    $sql_columns = "SHOW COLUMNS FROM tbl_category";
    $result_columns = $conn->query($sql_columns);

    $skip_headers = ['id', 'status', 'created_on']; // headers to skip

    // Fetch column names
    $headers = array();
    while ($row = $result_columns->fetch_assoc()) {
        if (!in_array($row['Field'], $skip_headers)) {
            $headers[] = $row['Field'];
        }
    }

    // Generate CSV data
    $csv_data = "";

    // Initialize CSV content with headers
    $csv_data .= implode(",", $headers) . "\n";

    // Set headers for CSV download
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=sample_category_list.csv");

    // Output CSV data
    echo $csv_data;
}
if (isset($_GET['type'])) {

    switch ($_GET['type']) {
        case 'category':
            exportCategory();
            break;

        default:
            echo 'Error';
            break;
    }
}
