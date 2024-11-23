<?php
session_start();
include('config.php');

if (isset($_GET["id"]) && $_GET["id"] != "") {
    $id = $_GET["id"];
    $img = isset($_GET["img_path"]) ? $_GET["img_path"] : "";

    // Validate $id to ensure it is not manipulated

    // Update the img_path column to NULL
    $sql = "UPDATE challenge SET img_path = NULL WHERE ch_id=? AND userID=?";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "is", $id, $_SESSION["UID"]);

    if (mysqli_stmt_execute($stmt)) {
        if (!empty($img)) {
            $imgPath = "uploads/" . $img;

            // Check if the file exists before attempting to delete it
            if (file_exists($imgPath)) {
                unlink($imgPath);
                echo "Image deleted successfully<br>";
            } else {
                echo "Image not found: $imgPath<br>";
            }
        } else {
            echo "Image parameter not provided<br>";
        }

        echo '<a href="my_challenge.php">Back</a>';
    } else {
        echo "Error updating record: " . mysqli_error($conn) . "<br>";
        echo '<a href="my_challenge.php">Back</a>';
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
