<?php
$dir = __DIR__ . '/pdf/';
$files = glob($dir . '*');
foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file);
    }
}
echo "Deleted all files in $dir";
?>
