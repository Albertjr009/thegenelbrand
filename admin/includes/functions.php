<?php
// functions.php — helper functions for admin

// Sanitize user input
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Handle image uploads
function upload_image($file, $targetDir) {
    $fileName = basename($file["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Allow only image file types
    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
            return $fileName; // return the name for database save
        } else {
            return false; // failed to upload
        }
    } else {
        return false; // invalid file type
    }
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}
?>
