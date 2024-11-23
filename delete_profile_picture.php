<?php
session_start();
include("config.php");

if (isset($_SESSION["UID"])) {
    $userID = $_SESSION["UID"];

    // Retrieve the current profile picture path from the database
    $sqlSelect = "SELECT img_path FROM profile WHERE userID = ?";
    $stmtSelect = mysqli_prepare($conn, $sqlSelect);
    mysqli_stmt_bind_param($stmtSelect, "i", $userID);
    mysqli_stmt_execute($stmtSelect);
    mysqli_stmt_bind_result($stmtSelect, $currentImgPath);
    mysqli_stmt_fetch($stmtSelect);
    mysqli_stmt_close($stmtSelect);

    // Check if the current profile picture is not empty
    if (!empty($currentImgPath)) {
        // Delete the current profile picture from the profile folder
        $targetDir = "profile/"; // Adjust the target directory
        $currentImagePath = $targetDir . basename($currentImgPath);
    
        if (file_exists($currentImagePath)) {
            unlink($currentImagePath);
            echo "Profile picture deleted from folder successfully.<br>";
        } else {
            echo "Error: Profile picture file not found in folder.<br>";
        }
    }

    // Update the database with an empty string for img_path
    $sqlUpdate = "UPDATE profile SET img_path = 'photo.png' WHERE userID = ?";
    $stmtUpdate = mysqli_prepare($conn, $sqlUpdate);
    mysqli_stmt_bind_param($stmtUpdate, "i", $userID);

    if (mysqli_stmt_execute($stmtUpdate)) {
        $_SESSION["img_path"] = 'photo.png';
        echo "Profile picture deleted from database successfully.";
    } else {
        echo "Error updating profile picture in database: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmtUpdate);
} else {
    echo "Error: User not logged in.";
}

mysqli_close($conn);
?>
