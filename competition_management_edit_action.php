<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Values for add or edit
    $id = $_POST["kcid"];
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
    
        // Fetch the old indicatorID and type before updating the kpicompetition table
        $sqlGetOldData = "SELECT indicatorID, type FROM kpicompetition WHERE id = ? AND userID = ?";
        $stmtGetOldData = mysqli_prepare($conn, $sqlGetOldData);
        mysqli_stmt_bind_param($stmtGetOldData, "is", $id, $_SESSION["UID"]);
        mysqli_stmt_execute($stmtGetOldData);
        $resultGetOldData = mysqli_stmt_get_result($stmtGetOldData);
        $rowOldData = mysqli_fetch_assoc($resultGetOldData);
        $oldIndicatorID = $rowOldData['indicatorID'];
        $oldType = $rowOldData['type'];
    
        // Update the activity with the obtained ind_id
        $sqlUpdateCompetition = "UPDATE kpicompetition SET semYear=?, indicatorID=?, name=?, type=?, remark=? WHERE id=? AND userID=?";
        $stmtUpdateCompetition = mysqli_prepare($conn, $sqlUpdateCompetition);
        mysqli_stmt_bind_param($stmtUpdateCompetition, "sisssii", $semYear, $newIndicatorID, $name, $type, $remark, $id, $_SESSION["UID"]);
    
        $statusUpdateCompetition = updateIn_DBTable($conn, $stmtUpdateCompetition);
    
        // Update the old indicator table count if type or indicatorID is changed
        if ($oldType != $type || $oldIndicatorID!=$newIndicatorID) {
            updateIndicatorTable($conn, $oldIndicatorID, $oldType);
        }
    
        // Update the new indicator table count (adding)
        updateIndicatorTable($conn, $newIndicatorID, $type);
    
        if ($statusUpdateCompetition) {
            $_SESSION["success_message"] = "Form data updated successfully!";
            header("Location: competition_management.php");
        } else {
            $_SESSION["error_message"] = "Error updating record: " . mysqli_error($conn);
            header("Location: competition_management.php");
        }
    } else {
        $_SESSION["error_message"] = "No matching indicator found for the given semYear";
        header("Location: competition_management.php");
    }

    // Close the statements
    mysqli_stmt_close($stmtGetIndicatorID);
    mysqli_stmt_close($stmtGetOldData);
}

// Close db connection
mysqli_close($conn);

// Function to update data in a database table
function updateIndicatorTable($conn, $indicatorID, $type) {
    // Check the type and update the corresponding column
    $query = "SELECT * FROM kpicompetition WHERE indicatorID = $indicatorID AND type = $type";
    switch ($type) {
        case 1:
            $updateColumn = 'facultyComp';
            break;
        case 2:
            $updateColumn = 'uniComp';
            break;
        case 3:
            $updateColumn = 'nationalComp';
            break;
        case 4:
            $updateColumn = 'interComp';
            break;
        default:
            return;
    }

    $result = mysqli_query($conn, $query);
    $totalCount = mysqli_num_rows($result);

    // Construct the SQL query
    $sql = "UPDATE indicator SET $updateColumn = $totalCount WHERE ind_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "i", $indicatorID);

    // Execute the statement
    $status = mysqli_stmt_execute($stmt);

    // Close the statement
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
