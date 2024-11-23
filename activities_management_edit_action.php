<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Values for add or edit
    $id = $_POST["kaid"];
    $semYear = $_POST['semYear'];
    $name = $_POST['name'];
    $type = $_POST['type'];
    $remark = $_POST['remark'];

    // Query to get the corresponding ind_id for the given semYear
    $sqlGetIndicatorID = "SELECT ind_id FROM indicator WHERE semYear = ? AND userID = ?";
    $stmtGetIndicatorID = mysqli_prepare($conn, $sqlGetIndicatorID);
    mysqli_stmt_bind_param($stmtGetIndicatorID, "si", $semYear, $_SESSION["UID"]);
    mysqli_stmt_execute($stmtGetIndicatorID);
    $resultGetIndicatorID = mysqli_stmt_get_result($stmtGetIndicatorID);

    if ($row = mysqli_fetch_assoc($resultGetIndicatorID)) {
        $newIndicatorID = $row['ind_id'];
    
        // Fetch the old indicatorID and type before updating the kpiactivity table
        $sqlGetOldData = "SELECT indicatorID, type FROM kpiactivity WHERE id = ? AND userID = ?";
        $stmtGetOldData = mysqli_prepare($conn, $sqlGetOldData);
        mysqli_stmt_bind_param($stmtGetOldData, "is", $id, $_SESSION["UID"]);
        mysqli_stmt_execute($stmtGetOldData);
        $resultGetOldData = mysqli_stmt_get_result($stmtGetOldData);
        $rowOldData = mysqli_fetch_assoc($resultGetOldData);
        $oldIndicatorID = $rowOldData['indicatorID'];
        $oldType = $rowOldData['type'];
    
        // Update the activity with the obtained ind_id
        $sqlUpdateActivity = "UPDATE kpiactivity SET semYear=?, indicatorID=?, name=?, type=?, remark=? WHERE id=? AND userID=?";
        $stmtUpdateActivity = mysqli_prepare($conn, $sqlUpdateActivity);
        mysqli_stmt_bind_param($stmtUpdateActivity, "sisssii", $semYear, $newIndicatorID, $name, $type, $remark, $id, $_SESSION["UID"]);
    
        $statusUpdateActivity = updateIn_DBTable($conn, $stmtUpdateActivity);
    
        // Update the old indicator table count if type or indicatorID is changed
        if ($oldType != $type || $oldIndicatorID!=$newIndicatorID) {
            updateIndicatorTable($conn, $oldIndicatorID, $oldType);
        }
    
        // Update the new indicator table count
        updateIndicatorTable($conn, $newIndicatorID, $type);
    
        if ($statusUpdateActivity) {
            $_SESSION["success_message"] = "Form data updated successfully!";
            header("Location: activities_management.php");
        } else {
            $_SESSION["error_message"] = "Error updating record: " . mysqli_error($conn);
            header("Location: activities_management.php");
        }
    } else {
        $_SESSION["error_message"] = "No matching indicator found for the given semYear";
        header("Location: activities_management.php");
    }

    mysqli_stmt_close($stmtGetIndicatorID);
    mysqli_stmt_close($stmtGetOldData);
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

function updateIn_DBTable($conn, $stmt) {
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        echo "Error: " . mysqli_error($conn) . "<br>";
        return false;
    }
}
?>
