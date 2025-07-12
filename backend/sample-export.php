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

function exportProduct()
{
    global $conn;

    // Query to fetch column names from your table
    $sql_columns = "SHOW COLUMNS FROM tbl_product";
    $result_columns = $conn->query($sql_columns);

    $skip_headers = ['id', 'section', 'status', 'created_on', 'modified_on']; // headers to skip

    // Fetch column names
    $headers = array();
    foreach ($result_columns as $row) {
        if (!in_array($row['Field'], $skip_headers)) {
            $headers[] = $row['Field'];
        }
    }

    // Set headers for CSV download
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=sample_product_list.csv");

    // Open output buffer
    $output = fopen("php://output", "w");

    // Write headers
    fputcsv($output, $headers);

    // Create a sample row based on headers
    $sample_row = [];
    foreach ($headers as $column) {
        switch ($column) {
            case 'category':
                $sample_row[] = "Category Name";
                break;
            case 'name':
                $sample_row[] = "Product Name";
                break;
            case 'tamil_name':
                $sample_row[] = "தமிழ் பெயர்";
                break;
            case 'images':
                $sample_row[] = '["img_1.webp","img_2.webp"]';
                break;
            case 'mrp':
                $sample_row[] = "50";
                break;
            case 'selling_price':
                $sample_row[] = "6";
                break;
            case 'type':
                $sample_row[] = "1 BOX";
                break;
            case 'url':
                $sample_row[] = "https://youtube.com";
                break;
            case 'alignment':
                $sample_row[] = "1";
                break;
            default:
                $sample_row[] = "";
        }
    }

    // Write sample row
    fputcsv($output, $sample_row);

    fclose($output);
}

if (isset($_GET['type'])) {

    switch ($_GET['type']) {
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
