<?php
session_start();
include('config.php');

// this block is called when button Submit is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $formType = $_POST['form_type'];

    if ($formType === 'form1') {
       // values for add or edit
    $id = $_POST["kid"];
    $cgpa = $_POST['cgpa'];
    $facultyAct = $_POST['facultyAct'];
    $uniAct = $_POST['uniAct'];
    $nationalAct = $_POST['nationalAct'];
    $interAct = $_POST['interAct'];
    $facultyComp = $_POST['facultyComp'];
    $uniComp = $_POST['uniComp'];
    $nationalComp = $_POST['nationalComp'];
    $interComp = $_POST['interComp'];
    $certificate = $_POST['certificate'];
    $remark = $_POST['remark'];

    $sql = "UPDATE indicator SET cgpa=?, facultyAct=?, uniAct=?, nationalAct=?, interAct=?, facultyComp=?, uniComp=?, nationalComp=?, interComp=?, certificate=?, remark=? WHERE ind_id =? AND userID=?";

    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "diiiiiiiiisii", $cgpa, $facultyAct, $uniAct, $nationalAct, $interAct, $facultyComp, $uniComp, $nationalComp, $interComp, $certificate, $remark, $id, $_SESSION["UID"]);

    // Execute the statement
    $status = mysqli_stmt_execute($stmt);

    if ($status) {
        $_SESSION["success_message"] = "Form data updated successfully!";
        header("Location: my_kpi.php");        
    } else {
        $_SESSION["error_message"] = "Error updating record: " . mysqli_error($conn);
        header("Location: my_kpi_edit.php"); 
    }   
        // Your Form 1 processing logic here
    }

    // Handle Form 2
    elseif ($formType === 'form2') {
       // values for add or edit
    $id = $_POST["kid"];
    $cgpa = $_POST['cgpa'];
    $remark = $_POST['remark'];

    $sql = "UPDATE indicator SET cgpa=?, remark=? WHERE ind_id =? AND userID=?";

    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "dsii", $cgpa, $remark, $id, $_SESSION["UID"]);

    // Execute the statement
    $status = mysqli_stmt_execute($stmt);

    if ($status) {
        $_SESSION["success_message"] = "Form data updated successfully!";
        header("Location: my_kpi.php");        
    } else {
        $_SESSION["error_message"] = "Error updating record: " . mysqli_error($conn);
        header("Location: my_kpi_edit.php"); 
    }   
    }
    

    // Close the statement
    mysqli_stmt_close($stmt);
}

// Close db connection
mysqli_close($conn);

// Function to insert data into the database table
function insertTo_DBTable($conn, $sql)
{
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
        return false;
    }
}
?>
