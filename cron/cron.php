<?php
include("../utilities/db.php");

// List of tables to clean
$tables = ['tbl_category']; // 🔁 Add your table names here

foreach ($tables as $table) {
    $sql = "DELETE FROM `$table` 
            WHERE status = 0 
            AND modified_on < NOW() - INTERVAL 10 DAY";

    if ($conn->query($sql) === TRUE) {
        echo "Deleted old records from $table\n";
    } else {
        echo "Error deleting from $table: " . $conn->error . "\n";
    }
}

// delete images from table
$sql = "SELECT id, images FROM tbl_product 
        WHERE status = 0 
        AND modified_on < NOW() - INTERVAL 10 DAY";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $images = json_decode($row['images'], true);

        if (is_array($images)) {
            foreach ($images as $img) {
                $filePath = $uploadDir . $img;
                if (file_exists($filePath)) {
                    unlink($filePath);
                    echo "Deleted image: $filePath\n";
                } else {
                    echo "Image not found: $filePath\n";
                }
            }
        }

        // Now delete the row
        $deleteSql = "DELETE FROM `$imageTable` WHERE id = $id";
        if ($conn->query($deleteSql)) {
            echo "Deleted row with ID $id from $imageTable\n";
        } else {
            echo "Failed to delete row $id: " . $conn->error . "\n";
        }
    }
} else {
    echo "No old image records to delete.\n";
}

$conn->close();
