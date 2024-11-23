<?php
session_start();
include "config.php";

if (isset($_GET["id"]) && $_GET["id"] != "") {
    $id = $_GET["id"];

    // Validate $id to ensure it is a positive integer
    if (!ctype_digit($id) || $id <= 0) {
        $_SESSION["error_message"] = "Invalid record ID";
        header("Location: certification_management.php");
        exit();
    }

    // Fetch the indicatorID before deleting the record
    $sqlGetInfo = "SELECT indicatorID FROM kpicertificate WHERE id = ? AND userID = ?";
    $stmtGetInfo = mysqli_prepare($conn, $sqlGetInfo);
    mysqli_stmt_bind_param($stmtGetInfo, "is", $id, $_SESSION["UID"]);
    mysqli_stmt_execute($stmtGetInfo);
    $resultGetInfo = mysqli_stmt_get_result($stmtGetInfo);

    if ($rowInfo = mysqli_fetch_assoc($resultGetInfo)) {
        $indicatorID = $rowInfo['indicatorID'];

        // Delete the record
        $sqlDelete = "DELETE FROM kpicertificate WHERE id = ? AND userID = ?";
        $stmtDelete = mysqli_prepare($conn, $sqlDelete);
        mysqli_stmt_bind_param($stmtDelete, "is", $id, $_SESSION["UID"]);

        if (mysqli_stmt_execute($stmtDelete)) {
            $_SESSION["success_message"] = "Record deleted successfully";

            // Update the indicator table after deletion
            updateIndicatorTable($conn, $indicatorID);

            header("Location: certification_management.php");
        } else {
            $_SESSION["error_message"] = "Error deleting record: " . mysqli_error($conn);
            header("Location: certification_management.php");
        }

        mysqli_stmt_close($stmtDelete);
    } else {
        $_SESSION["error_message"] = "Record not found";
        header("Location: certification_management.php");
    }

    mysqli_stmt_close($stmtGetInfo);
}

mysqli_close($conn);

function updateIndicatorTable($conn, $indicatorID) {
    // Check the indicatorID and update the corresponding column
    $query = "SELECT * FROM kpicertificate WHERE indicatorID = $indicatorID";

    $result = mysqli_query($conn, $query);
    $totalCount = mysqli_num_rows($result);

    $sql = "UPDATE indicator SET certificate = $totalCount WHERE ind_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $indicatorID);

    $status = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $status;
}
?>
