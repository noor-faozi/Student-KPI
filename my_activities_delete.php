<?php
session_start();
include('config.php');

if (isset($_GET["id"]) && $_GET["id"] != "") {
    $id = $_GET["id"];
    $img = isset($_GET["img_path"]) ? $_GET["img_path"] : "";

    // Validate $id to ensure it is not manipulated

    $sql = "DELETE FROM activity WHERE act_id=? AND userID=?";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "is", $id, $_SESSION["UID"]);

    if (mysqli_stmt_execute($stmt)) {
        if (!empty($img)) {
            $imgPath = "activity/" . $img;

            // Check if the file exists before attempting to delete it
            if (file_exists($imgPath)) {
                unlink($imgPath);
                $_SESSION["success_message"] = "Record deleted successfully, and image deleted";
            } else {
                $_SESSION["success_message"] = "Record deleted successfully, but image not found: $imgPath";
            }
        } else {
            $_SESSION["success_message"] = "Record deleted successfully";
        }

        header("Location: my_activities.php");
    } else {
        $_SESSION["error_message"] = "Error deleting record: " . mysqli_error($conn);
        header("Location: my_activities.php"); 
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>