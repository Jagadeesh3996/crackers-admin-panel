<?php
include("../utilities/db.php");

function exportCategory()
{
    global $conn;

    // Query to fetch column names from your table
    $sql_columns = "SHOW COLUMNS FROM tbl_category";
    $result_columns = $conn->query($sql_columns);

    // Fetch column names
    $headers = array();
    while ($row = $result_columns->fetch_assoc()) {
        $headers[] = $row['Field'];
    }

    // Query to export database data
    $sql = "SELECT * FROM tbl_category WHERE status = 1 ORDER BY id ASC";
    $result = $conn->query($sql);

    // Generate CSV data
    $csv_data = "";

    // Initialize CSV content with headers
    $csv_data .= implode(",", $headers) . "\n";

    while ($row2 = $result->fetch_assoc()) {
        $csv_data .= implode(",", $row2) . "\n";
    }

    // Set headers for CSV download
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=category_list.csv");

    // Output CSV data
    echo $csv_data;
}

function exportProduct()
{
    global $conn;

    // Query to fetch column names from your table
    $sql_columns = "SHOW COLUMNS FROM tbl_product";
    $result_columns = $conn->query($sql_columns);

    // Fetch column names
    $headers = array();
    while ($row = $result_columns->fetch_assoc()) {
        $headers[] = $row['Field'];
    }

    // Query to export database data
    $sql = "SELECT a.* FROM tbl_product AS a 
                LEFT JOIN tbl_category AS b ON b.name = a.category 
                WHERE b.status = 1 
                AND a.status >= 1 
                ORDER BY a.id ASC";

    $result = $conn->query($sql);

    // Set headers for CSV download
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=product_list.csv");

    // Open PHP output stream
    $output = fopen("php://output", "w");

    // Write column headers
    fputcsv($output, $headers);

    // Write each row
    while ($row2 = $result->fetch_assoc()) {
        // If product_images or similar field contains array-like string, make sure it's quoted
        foreach ($row2 as $key => $value) {
            if (is_string($value) && preg_match('/^\[.*\]$/', $value)) {
                // Optional: keep JSON string format without breaking columns
                $row2[$key] = str_replace('"', '"', $value); // escape double quotes
            }
        }
        fputcsv($output, $row2);
    }

    fclose($output);
}


if (isset($_GET['export'])) {

    switch ($_GET['export']) {
        case 'category':
            exportCategory();
            break;

        case 'product':
            exportProduct();
            break;

        default:
            echo 'Error';
            break;
    }
}
