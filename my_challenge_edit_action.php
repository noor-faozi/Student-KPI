<?php
session_start();
include('config.php');

// Variables
$action = "";
$id = "";
$sem = "";
$year = "";
$challenge = "";
$remark = "";

// For upload
$target_dir = "uploads/";
$target_file = "";
$uploadOk = 0;
$imageFileType = "";
$uploadfileName = "";

// This block is called when the Submit button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Values for add or edit
    $id = $_POST["cid"];
    $sem = $_POST["sem"];
    $year = $_POST["year"];
    $challenge = trim($_POST["challenge"]);
    $plan = trim($_POST["plan"]);
    $remark = trim($_POST["remark"]);

    $filetmp = $_FILES["fileToUpload"];
    // File of the image/photo file
    $uploadfileName = $filetmp["name"];

    // Check if there is an image to be uploaded
    // If no image
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["name"] == "") {
        $sql = "UPDATE challenge SET sem = ?, year = ?, challenge = ?, 
            plan = ?, remark = ? WHERE ch_id = ? AND userID = ?";
        $stmt = mysqli_prepare($conn, $sql);

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "isssssi", $sem, $year, $challenge, $plan, $remark, $id, $_SESSION["UID"]);
  
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Rest of your code for successful execution
            $_SESSION["success_message"] = "Form data updated successfully!";
            header("Location: my_challenge.php");
        } else {
            // Handle the error
            $_SESSION["error_message"] = "Error updating record: " . mysqli_error($conn);
            header("Location: my_challenge_edit.php");
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == UPLOAD_ERR_OK) {
        // Variable to determine if image upload is OK
        $uploadOk = 1;
        $filetmp = $_FILES["fileToUpload"];

        // File of the image/photo file
        $uploadfileName = $filetmp["name"];

        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "ERROR: Sorry, image file $uploadfileName already exists.<br>";
            $uploadOk = 0;
        }

        // Check file size <= 488.28KB or 500000 bytes
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

        // If uploadOk, then try to add to database first
        // uploadOK=1 if there is an image to be uploaded, filename not exists, file size is ok and format ok
        if ($uploadOk) {
            // Retrieve the old image path
            $oldImagePathQuery = "SELECT img_path FROM challenge WHERE ch_id = ? AND userID = ?";
            $stmt = mysqli_prepare($conn, $oldImagePathQuery);

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "is", $id, $_SESSION["UID"]);

            // Execute the statement
            mysqli_stmt_execute($stmt);

            // Bind the result
            mysqli_stmt_bind_result($stmt, $oldImage);

            // Fetch the result
            mysqli_stmt_fetch($stmt);

            // Close the statement
            mysqli_stmt_close($stmt);

            if (!empty($oldImage)) {
                $oldImagePath = "uploads/" . $oldImage;

                // Check if the old image file exists and is not a directory
                if (file_exists($oldImagePath) && !is_dir($oldImagePath)) {
                    // Delete the old image file
                    unlink($oldImagePath);
                }
            }

            $sql = "UPDATE challenge SET sem = ?, year = ?, challenge = ?, 
            plan = ?, remark = ?, img_path = ? WHERE ch_id = ? AND userID = ?";
            $stmt = mysqli_prepare($conn, $sql);

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "isssssii", $sem, $year, $challenge, $plan, $remark, $uploadfileName, $id, $_SESSION["UID"]);


            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    // Image file successfully uploaded

                    // Tell successful record
                    $_SESSION["success_message"] = "Form data updated successfully!";
                    header("Location: my_challenge.php");
                } else {
                    // There is an error while uploading image
                    $_SESSION["error_message"] = "Error uploading your file";
                    header("Location: my_challenge_edit.php");
                }
            } else {
                $_SESSION["error_message"] = "Error updating record: " . mysqli_error($conn);
                header("Location: my_challenge_edit.php");
            }


            // Close the statement
            mysqli_stmt_close($stmtDeleteImage);
        }

            // Close the statement
            mysqli_stmt_close($stmt);
    } else {
            header("Location: my_challenge_edit.php");
        }
}


// Close db connection
mysqli_close($conn);
?>