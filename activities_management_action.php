<?php
session_start();
include('config.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Values for add or edit
    $semYear = $_POST['semYear'];
    $name = $_POST['name'];
    $type = $_POST['type'];
    $remark = $_POST['remark'];

    // Retrieve ind_id based on semYear
    $sqlSelectIndicatorID = "SELECT ind_id FROM indicator WHERE semYear = ?";
    $stmtSelectIndicatorID = mysqli_prepare($conn, $sqlSelectIndicatorID);
    mysqli_stmt_bind_param($stmtSelectIndicatorID, "s", $semYear);
    mysqli_stmt_execute($stmtSelectIndicatorID);
    $resultIndicatorID = mysqli_stmt_get_result($stmtSelectIndicatorID);
    $rowIndicatorID = mysqli_fetch_assoc($resultIndicatorID);

    if ($rowIndicatorID) {
       
        $indicatorID = $rowIndicatorID['ind_id'];

        $sqlInsertActivity = "INSERT INTO kpiactivity (indicatorID, userID, semYear, name, type, remark) 
                              VALUES (?, ?, ?, ?, ?, ?)";
        $stmtInsertActivity = mysqli_prepare($conn, $sqlInsertActivity);

        // Use the captured indicatorID in the binding
        mysqli_stmt_bind_param($stmtInsertActivity, "iissss", $indicatorID, $_SESSION["UID"], $semYear, $name, $type, $remark);
        mysqli_stmt_execute($stmtInsertActivity);
        mysqli_stmt_close($stmtInsertActivity);

        updateIndicatorTable($conn, $indicatorID, $type);

        $_SESSION["success_message"] = "Form data added successfully!";
        header("Location: activities_management.php");
    } else {
        // Handle the case where the semYear is not found in the indicator table
        $_SESSION["error_message"] = "Form data addition was unsuccessful. Semester/Year not found.";
        header("Location: activities_management.php");
    }

    mysqli_stmt_close($stmtSelectIndicatorID);
}


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
// Close db connection
mysqli_close($conn);
?>
