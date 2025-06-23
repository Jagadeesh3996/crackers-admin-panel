
<?php
// Define allowed files
$allowed_files = ['category_list.csv', 'product_list.csv'];

// Get the file name from URL (e.g., download.php?file=category_list.csv)
$file = isset($_GET['file']) ? $_GET['file'] : '';

// Validate file name to prevent unauthorized access
if (!in_array($file, $allowed_files)) {
    die("Invalid file!");
}

// Set the full path
$file_path = '../assets/sample_files/' . $file;

// Check if file exists
if (!file_exists($file_path)) {
    die("File not found!");
}

// Set headers to force download
header('Content-Description: File Transfer');
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . basename($file) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file_path));

// Read the file and send it to the user
readfile($file_path);
exit;
?>
