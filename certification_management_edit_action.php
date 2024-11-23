<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Values for add or edit
    $id = $_POST["kcrid"];
    $semYear = $_POST['semYear'];
    $name = $_POST['name'];
    $remark = $_POST['remark'];

    // Query to get the corresponding ind_id for the given semYear
    $sqlGetIndicatorID = "SELECT ind_id FROM indicator WHERE semYear = ? AND userID = ?";
    $stmtGetIndicatorID = mysqli_prepare($conn, $sqlGetIndicatorID);
    mysqli_stmt_bind_param($stmtGetIndicatorID, "si", $semYear, $_SESSION["UID"]);
    mysqli_stmt_execute($stmtGetIndicatorID);
    $resultGetIndicatorID = mysqli_stmt_get_result($stmtGetIndicatorID);

    if ($row = mysqli_fetch_assoc($resultGetIndicatorID)) {
        $newIndicatorID = $row['ind_id'];
    
        // Fetch the old indicatorID before updating the kpicertificate table
        $sqlGetOldData = "SELECT indicatorID FROM kpicertificate WHERE id = ? AND userID = ?";
        $stmtGetOldData = mysqli_prepare($conn, $sqlGetOldData);
        mysqli_stmt_bind_param($stmtGetOldData, "is", $id, $_SESSION["UID"]);
        mysqli_stmt_execute($stmtGetOldData);
        $resultGetOldData = mysqli_stmt_get_result($stmtGetOldData);
        $rowOldData = mysqli_fetch_assoc($resultGetOldData);
        $oldIndicatorID = $rowOldData['indicatorID'];
    
        // Update the kpicertificate with the obtained ind_id
        $sqlUpdateCertificate = "UPDATE kpicertificate SET semYear=?, indicatorID=?, name=?,remark=? WHERE id=? AND userID=?";
        $stmtUpdateCertificate = mysqli_prepare($conn, $sqlUpdateCertificate);
        mysqli_stmt_bind_param($stmtUpdateCertificate, "sissii", $semYear, $newIndicatorID, $name, $remark, $id, $_SESSION["UID"]);
    
        $statusUpdateCertificate = updateIn_DBTable($conn, $stmtUpdateCertificate);
    
        // Update the old indicator table count only if indicatorID is changed
        if ($oldIndicatorID!=$newIndicatorID) {
            updateIndicatorTable($conn, $oldIndicatorID);
        }
    
        // Update the new indicator table count
        updateIndicatorTable($conn, $newIndicatorID);
    
        if ($statusUpdateCertificate) {
            $_SESSION["success_message"] = "Form data updated successfully!";
            header("Location: certification_management.php");
        } else {
            $_SESSION["error_message"] = "Error updating record: " . mysqli_error($conn);
            header("Location: certification_management.php");
        }
    } else {
        $_SESSION["error_message"] = "No matching indicator found for the given semYear";
        header("Location: certification_management.php");
    }

    // Close the statements
    mysqli_stmt_close($stmtGetIndicatorID);
    mysqli_stmt_close($stmtGetOldData);
}

// Close db connection
mysqli_close($conn);

// Function to update data in a database table
function updateIndicatorTable($conn, $indicatorID) {
    // Check the indicatorID and update the corresponding column
    $query = "SELECT * FROM  kpicertificate WHERE indicatorID = $indicatorID";
    
    $result = mysqli_query($conn, $query);
    $totalCount = mysqli_num_rows($result);

    $sql = "UPDATE indicator SET certificate = $totalCount WHERE ind_id = ?";
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
