<?php
session_start();
include_once 'config.php';

$id = $_SESSION['UID'];

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $program = $_POST['program'];
    $mentor = $_POST['mentor'];
    $motto = $_POST['motto'];

    // Upload picture if available
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == UPLOAD_ERR_OK) {
        // Target directory for the uploaded picture
        $target_dir = "profile/"; 

        // Retrieve the old image path from the database
        $sqlSelect = "SELECT img_path FROM profile WHERE userID = ?";
        $stmtSelect = mysqli_prepare($conn, $sqlSelect);
        mysqli_stmt_bind_param($stmtSelect, "i", $id);
        mysqli_stmt_execute($stmtSelect);
        mysqli_stmt_bind_result($stmtSelect, $oldImgPath);
        mysqli_stmt_fetch($stmtSelect);
        mysqli_stmt_close($stmtSelect);

        // Check if the old image is the default image
        $isDefaultImage = $oldImgPath === 'photo.png';

        // If the old image is not the default image, delete it
        if (!$isDefaultImage && !empty($oldImgPath)) {
            $oldImagePath = "profile/" . basename($oldImgPath);

            // Check if the file exists and it is a file (not a directory) before attempting to delete
            if (file_exists($oldImagePath) && is_file($oldImagePath)) {
                if (unlink($oldImagePath)) {
                    $_SESSION["success_message"] = "Profile picture updated successfully.";
                    header("Location: profile.php");
                    exit();
                } else {
                    echo "Error deleting old image file: $oldImagePath<br>";
                }
            } else {
                echo "Old image file not found or it is a directory: $oldImagePath<br>";
            }
        }

        // File of the image/photo file
        $uploadfileName = basename($_FILES["fileToUpload"]["name"]);

        // Path of the image/photo file
        $target_file = $target_dir . $uploadfileName;

        // Variables to determine if image upload is OK
        $uploadOk = 1;
        $filetmp = $_FILES["fileToUpload"];

        // Get file type and check its format
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file already exists
        if (file_exists($target_file)) {
            echo "ERROR: Sorry, image file $uploadfileName already exists.<br>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "ERROR: Sorry, your file is too large. Try resizing your image.<br>";
            $uploadOk = 0;
        }

        // Allow only these file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            echo "ERROR: Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
            $uploadOk = 0;
        }

        // If uploadOk, then try adding to the database first
        if ($uploadOk) {
            $sql = "UPDATE profile SET username = ?, program = ?, mentor = ?, motto = ?, img_path = ? WHERE userID = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssssi", $username, $program, $mentor, $motto, $uploadfileName, $id);

            $status = update_DBTable($conn, $stmt);

            if ($status) {
                $_SESSION["img_path"] = $uploadfileName;
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    // Image file successfully uploaded
                    // Tell successful record
                    $_SESSION["success_message"] = "Profile updated successfully.";
                    header("Location: profile.php");
                } else {
                    // There is an error while uploading the image
                    $_SESSION["error_message"] = "Sorry, there was an error uploading your profile picture";
                    header("Location: profile_edit.php");
                    exit();
                }
            } else {
                echo '<a href="javascript:history.back()">Back</a>';
            }
        } else {
            echo '<a href="javascript:history.back()">Back</a>';
        }
    }
    // If there is no image to be uploaded
    else {
        $sql = "UPDATE profile SET username = ?, program = ?, mentor = ?, motto = ? WHERE userID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $username, $program, $mentor, $motto, $id);

        $status = update_DBTable($conn, $stmt);

        if ($status) {
            $_SESSION["success_message"] = "Profile updated successfully!";
            header("Location: profile.php");
            exit();
        } else {
            $_SESSION["error_message"] = "Error updating profile.";
            header("Location: profile_edit.php");
            exit();
        }
    }
} else {
    echo "Form not submitted.";
}

// Close db connection
mysqli_close($conn);

// Function to insert data into the database table
function update_DBTable($conn, $stmt)
{
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        echo "Error: " . mysqli_stmt_error($stmt) . "<br>";
        return false;
    }
}
?>
