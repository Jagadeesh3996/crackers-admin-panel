<?php
    include('../db.php');

    function exportDatabase() {
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
        $sql = "SELECT * FROM tbl_product WHERE status>='1' ORDER BY id ASC";
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
        header("Content-Disposition: attachment; filename=product_list.csv");
    
        // Output CSV data
        echo $csv_data;
    }
    if (isset($_GET['export'])) {
        exportDatabase();
        exit();
    }
?>