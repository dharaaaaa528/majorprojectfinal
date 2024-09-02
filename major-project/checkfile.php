<?php
require_once 'configg.php';

function importTable($conn) {
    $filename = 'users.sql';
    $templine = '';
    $lines = file($filename);

    foreach ($lines as $line) {
        if (substr($line, 0, 2) == '--' || $line == '') {
            continue;
        }
        $templine .= $line;
        if (substr(trim($line), -1, 1) == ';') {
            if (!$conn->query($templine)) {
                die('Error performing query \'' . $templine . '\': ' . $conn->error);
            }
            $templine = '';
        }
    }
}

// Select the database
$conn->query("USE testt");

// Check if the table exists
$result = $conn->query("SHOW TABLES LIKE 'users'");
if ($result->num_rows == 0) {
    // If the table doesn't exist, re-import it
    importTable($conn);
}
?>
