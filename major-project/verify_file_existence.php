<?php
// Replace with the actual path to the profile picture
$profilePicturePath = 'path/to/profile/picture.jpg'; 

if (file_exists($profilePicturePath)) {
    echo "File exists: " . $profilePicturePath;
} else {
    echo "File does not exist: " . $profilePicturePath;
}
?>
