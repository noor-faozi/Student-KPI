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
        // Use the retrieved ind_id to insert into kpicompetition
        $indicatorID = $rowIndicatorID['ind_id'];

        $sqlInsertCompetition = "INSERT INTO kpicompetition (indicatorID, userID, semYear, name, type, remark) 
                              VALUES (?, ?, ?, ?, ?, ?)";
        $stmtInsertCompetition = mysqli_prepare($conn, $sqlInsertCompetition);

        // Use the captured indicatorID in the binding
        mysqli_stmt_bind_param($stmtInsertCompetition, "iissss", $indicatorID, $_SESSION["UID"], $semYear, $name, $type, $remark);
        mysqli_stmt_execute($stmtInsertCompetition);
        mysqli_stmt_close($stmtInsertCompetition);

        updateIndicatorTable($conn, $indicatorID, $type);

        $_SESSION["success_message"] = "Form data added successfully!";
        header("Location: competition_management.php");
    } else {
        // Handle the case where the semYear is not found in the indicator table
        $_SESSION["error_message"] = "Form data addition was unsuccessful. Semester/Year not found.";
        header("Location: competition_management.php");
    }

    mysqli_stmt_close($stmtSelectIndicatorID);
}


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
