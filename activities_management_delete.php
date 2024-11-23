<?php
session_start();
include "config.php";

if (isset($_GET["id"]) && $_GET["id"] != "") {
    $id = $_GET["id"];

    // Validate $id to ensure it is a positive integer
    if (!ctype_digit($id) || $id <= 0) {
        $_SESSION["error_message"] = "Invalid record ID";
        header("Location: activities_management.php");
        exit();
    }

    // Fetch the indicatorID and type before deleting the record
    $sqlGetInfo = "SELECT indicatorID, type FROM kpiactivity WHERE id = ? AND userID = ?";
    $stmtGetInfo = mysqli_prepare($conn, $sqlGetInfo);
    mysqli_stmt_bind_param($stmtGetInfo, "is", $id, $_SESSION["UID"]);
    mysqli_stmt_execute($stmtGetInfo);
    $resultGetInfo = mysqli_stmt_get_result($stmtGetInfo);

    if ($rowInfo = mysqli_fetch_assoc($resultGetInfo)) {
        $indicatorID = $rowInfo['indicatorID'];
        $type = $rowInfo['type'];

        // Delete the record
        $sqlDelete = "DELETE FROM kpiactivity WHERE id = ? AND userID = ?";
        $stmtDelete = mysqli_prepare($conn, $sqlDelete);
        mysqli_stmt_bind_param($stmtDelete, "is", $id, $_SESSION["UID"]);

        if (mysqli_stmt_execute($stmtDelete)) {
            $_SESSION["success_message"] = "Record deleted successfully";

            // Update the indicator table after deletion
            updateIndicatorTable($conn, $indicatorID, $type);

            header("Location: activities_management.php");
        } else {
            $_SESSION["error_message"] = "Error deleting record: " . mysqli_error($conn);
            header("Location: activities_management.php");
        }

        mysqli_stmt_close($stmtDelete);
    } else {
        $_SESSION["error_message"] = "Record not found";
        header("Location: activities_management.php");
    }

    mysqli_stmt_close($stmtGetInfo);
}

mysqli_close($conn);

function updateIndicatorTable($conn, $indicatorID, $type) {

    $query = "SELECT * FROM kpiactivity WHERE indicatorID = $indicatorID AND type = $type";
    // Check the type and update the corresponding column
    switch ($type) {
        case 1:
            $updateColumn = 'facultyAct';
            break;
        case 2:
            $updateColumn = 'uniAct';
            break;
        case 3:
            $updateColumn = 'nationalAct';
            break;
        case 4:
            $updateColumn = 'interAct';
            break;
        default:
            // Handle unknown type
            return;
    }

    $result = mysqli_query($conn, $query);
    $totalCount = mysqli_num_rows($result);

    $sql = "UPDATE indicator SET $updateColumn = $totalCount WHERE ind_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $indicatorID);

    $status = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $status;
}
?>
