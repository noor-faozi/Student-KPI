<?php
session_start();
include("config.php");
?>
<!DOCTYPE html>
<html>

<head>
<title>My Study KPI</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">   
</head>
<body onLoad="show_AddEntry()">

    <section class="sec-1">
        <div class="header">
            <div class="menu">
                <?php 
                    if(isset($_SESSION["UID"])){
                        include 'logged_menu.php';
                    }
                    else {
                        include 'menu.php';
                    }
                ?>
            </div>
            <?php 
                $sql = "SELECT profile.username
                FROM profile 
                WHERE profile.userID = ?";
        
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "s", $_SESSION["UID"]);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $username = $row["username"];
                    echo '<h3 id="myName">' . $username . '</h3>';
                }
            ?>
            <h1 id="title">MY STUDY KPI</h1>
            <br>
            <br>
            <a href="#sec-2" class="scrollDown" id="exploreBtn">
                 Explore
                <div class="scrollDown"></div>
            </a>
        </div>
    </section>

    <section class="sec-2" id="sec-2">

    <h2 style="padding: 10px;">List of KPI Indicators</h2>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p class="error"><?php echo $_SESSION['error_message']; ?> </p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php
        $id = "";
        $semYear = "";
        $cgpa = "";
        $facultyAct =" ";
        $uniAct =" ";
        $nationalAct =" ";
        $interAct =" ";
        $facultyComp =" ";
        $uniComp =" ";
        $nationalComp =" ";
        $interComp =" ";
        $certificate =" ";
        $remark = "";

        if(isset($_GET["id"]) && $_GET["id"] != ""){
            $id = $_GET["id"];
            $userID = $_SESSION["UID"];
            $sql = "SELECT * FROM indicator WHERE ind_id = ? AND userID = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $id, $userID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $semYear = $row['semYear'];
                $cgpa = $row['cgpa'];
                $facultyAct = $row['facultyAct'];
                $uniAct = $row['uniAct'];
                $nationalAct = $row['nationalAct'];
                $interAct = $row['interAct'];
                $facultyComp = $row['facultyComp'];
                $uniComp = $row['uniComp'];
                $nationalComp = $row['nationalComp'];
                $interComp = $row['interComp'];
                $certificate = $row['certificate'];
                $remark = $row['remark'];
            }        
        }

        mysqli_close($conn);
    ?>

    <div style="padding: 10px;" id="kpiDiv">
    <?php
            // Check the condition and display the appropriate form
            if ($semYear == "FACULTY KPI" || $semYear == "STUDENT AIM"):
            ?>
        <h3 align="center">Edit KPI Indicator</h3>
        <p align="center" style="color: red;">Required field with mark*</p>

        <form method="POST" action="my_kpi_edit_action.php" enctype="multipart/form-data" id="myForm">
            <div id="inputForm">
                <input type="hidden" id="kid" name="kid" value="<?=$_GET['id']?>">
                    <label class="indicator">Add a semester/year</label>
                    <label>Semester and Year*</label>
                    <select name="semYear" style="cursor: not-allowed;" id="semYear" disabled required>
                        <option value="" >Select Semester and Year</option>

                        <?php
                        $semesterYearOptions = [
                            "FACULTY KPI",
                            "RTUDENT AIM",
                            "YEAR 1 SEM 1",
                            "YEAR 1 SEM 2",
                            "YEAR 2 SEM 1",
                            "YEAR 2 SEM 2",
                            "YEAR 3 SEM 1",
                            "YEAR 3 SEM 2",
                            "YEAR 4 SEM 1",
                            "YEAR 4 SEM 2"
                        ];

                        foreach ($semesterYearOptions as $option) {
                            $selected = ($semYear == $option) ? 'selected' : '';
                            echo "<option value='$option' $selected>$option</option>";
                        }
                        ?>
                    </select>
                    <label>CGPA*</label>
                        <?php
                        if($cgpa!=""){
                            echo '<input type="number" step="0.01" min="0" name="cgpa" style="width: 5em" value="' . $cgpa . '" required id="myInput">';
                        }
                        else {
                        ?>
                            <input type="number" step="0.01" min="0" name="cgpa" style="width: 5em" required id="myInput">
                        <?php
                        }
                        ?>   
                    <label class="indicator">Activities</label>
                    <label style="display:inline; margin-right: 10px;">Faculty Level*</label>
                        <?php
                        if($facultyAct!=""){
                            echo '<input type="number" step="1" min="0" name="facultyAct" style="width: 5em; margin-right: 10px;" value="' . $facultyAct . '" required id="myInput">';
                        }
                        else {
                        ?>
                            <input type="number" step="1" min="0" name="facultyAct" style="width: 5em; margin-right: 10px;" required id="myInput">
                        <?php
                        }
                        ?>  
                    <label style="display:inline; margin-right: 25px;">University Level*</label>
                        <?php
                        if($uniAct!=""){
                            echo '<input type="number" step="1" min="0" name="uniAct" style="width: 5em" value="' . $uniAct . '" required id="myInput">';
                        }
                        else {
                        ?>
                            <input type="number" step="1" min="0" name="uniAct" style="width: 5em" required id="myInput">
                        <?php
                        }
                        ?> 
                    <label style="display:inline; margin-right: 5px;">National Level*</label>
                        <?php
                        if($nationalAct!=""){
                            echo '<input type="number" step="1" min="0" name="nationalAct" style="width: 5em; margin-right: 9px;" value="' . $nationalAct . '" required id="myInput">';
                        }
                        else {
                        ?>
                            <input type="number" step="1" min="0" name="nationalAct" style="width: 5em; margin-right: 9px;" required id="myInput">
                        <?php
                        }
                        ?>
                    <label style="display:inline;">International Level*</label>
                        <?php
                        if($interAct!=""){
                            echo '<input type="number" step="1" min="0" name="interAct" style="width: 5em" value="' . $interAct . '" required id="myInput">';
                        }
                        else {
                        ?>
                            <input type="number" step="1" min="0" name="interAct" style="width: 5em" required id="myInput">
                        <?php
                        }
                        ?>
                    <label class="indicator">Competitions</label>
                
                    <label style="display:inline; margin-right: 10px;">Faculty Level*</label>

                        <?php
                        if($facultyComp!=""){
                            echo '<input type="number" step="1" min="0" name="facultyComp" style="width: 5em; margin-right: 10px;" value="' . $facultyComp . '" required id="myInput">';
                        }
                        else {
                        ?>
                            <input type="number" step="1" min="0" name="facultyComp" style="width: 5em; margin-right: 10px;" required id="myInput">
                        <?php
                        }
                        ?>

                    <label style="display:inline; margin-right: 25px;">University Level*</label>
                        <?php
                        if($uniComp!=""){
                            echo '<input type="number" step="1" min="0" name="uniComp" style="width: 5em" value="' . $uniComp . '" required id="myInput">';
                        }
                        else {
                        ?>
                            <input type="number" step="1" min="0" name="uniComp" style="width: 5em" required id="myInput">
                        <?php
                        }
                        ?>
                    <label style="display:inline; margin-right: 5px;">National Level*</label>
                        <?php
                        if($nationalComp!=""){
                            echo '<input type="number" step="1" min="0" name="nationalComp" style="width: 5em; margin-right: 9px;" value="' . $nationalComp . '" required id="myInput">';
                        }
                        else {
                        ?>
                            <input type="number" step="1" min="0" name="nationalComp" style="width: 5em; margin-right: 9px;" required id="myInput">
                        <?php
                        }
                        ?>

                    <label style="display:inline;">International Level*</label>
                        <?php
                        if($interComp!=""){
                            echo '<input type="number" step="1" min="0" name="interComp" style="width: 5em" value="' . $interComp . '" required id="myInput">';
                        }
                        else {
                        ?>
                            <input type="number" step="1" min="0" name="interComp" style="width: 5em" required id="myInput">
                        <?php
                        }
                        ?>

                    <label class="indicator">Certificate*</label>

                        <?php
                        if($certificate!=""){
                            echo '<input type="number" step="1" min="0" name="certificate" style="width: 5em" value="' . $certificate . '" required id="myInput">';
                        }
                        else {
                        ?>
                            <input type="number" step="1" min="0" name="certificate" style="width: 5em" required id="myInput">
                        <?php
                        }
                        ?>
                    <label>Remark</label>
                        <textarea rows="4" name="remark" cols="20" id="myInput"><?php  echo $remark;?></textarea>

                    <div align="right"> 
                        <input type="hidden" name="form_type" value="form1">
                        <input type="submit" id="form-submit" value="Submit" name="B1">                
                        <input type="button" id="form-reset" value="Reset" name="B2" onclick="resetForm()">
                        <input type="button" id="form-reset" value="Clear" name="B3" onclick="clearForm()">
                    </div>
            </div>
        </form>

        <?php else: ?>
                <!-- Form for other semYear values -->
                <h3 align="center">Edit KPI Indicator</h3>
                <form method="POST" action="my_kpi_edit_action.php" enctype="multipart/form-data" id="myForm">
            <div id="inputForm">
                <input type="hidden" id="kid" name="kid" value="<?=$_GET['id']?>">
                    <label class="indicator">Add a semester/year</label>
                    <label>Semester and Year*</label>
                    <select name="semYear" style="cursor: not-allowed;" id="semYear" disabled required>
                        <option value="" >Select Semester and Year</option>

                        <?php
                        $semesterYearOptions = [
                            "FACULTY KPI",
                            "RTUDENT AIM",
                            "YEAR 1 SEM 1",
                            "YEAR 1 SEM 2",
                            "YEAR 2 SEM 1",
                            "YEAR 2 SEM 2",
                            "YEAR 3 SEM 1",
                            "YEAR 3 SEM 2",
                            "YEAR 4 SEM 1",
                            "YEAR 4 SEM 2"
                        ];

                        foreach ($semesterYearOptions as $option) {
                            $selected = ($semYear == $option) ? 'selected' : '';
                            echo "<option value='$option' $selected>$option</option>";
                        }
                        ?>
                    </select>
                    <label>CGPA*</label>
                        <?php
                        if($cgpa!=""){
                            echo '<input type="number" step="0.01" min="0" name="cgpa" style="width: 5em" value="' . $cgpa . '" required id="myInput">';
                        }
                        else {
                        ?>
                            <input type="number" step="0.01" min="0" name="cgpa" style="width: 5em" required id="myInput">
                        <?php
                        }
                        ?>   
                    <label>Remark</label>
                        <textarea rows="4" name="remark" cols="20" id="myInput"><?php  echo $remark;?></textarea>

                    <div align="right"> 
                        <input type="hidden" name="form_type" value="form2">
                        <input type="submit" id="form-submit" value="Submit" name="B1">                
                        <input type="button" id="form-reset" value="Reset" name="B2" onclick="resetForm()">
                        <input type="button" id="form-reset" value="Clear" name="B3" onclick="clearForm()">
                    </div>
            </div>
        </form>
            <?php endif; ?>
        </div>
    </script>
<p></p>
<footer>
    <p>Copyright &#169; 2023 - Noor</p>
</footer>

<script>
//for responsive sandwich menu
function myFunction() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}

//reset form after modification to a php echo to fields
function resetForm() {
    document.getElementById("myForm").reset();
}

//this clear form to empty the form for new data
function clearForm() {
    var form = document.getElementById("myForm");
    if (form) {
        // Clear file input
        var fileInput = form.querySelector('input[type="file"]');
        fileInput.value = "";

        // Clear all inputs and textareas
        var elements = form.querySelectorAll('input, textarea');
        elements.forEach(function (element) {
            if (element.type !== "button" && element.type !== "submit" && element.type !== "reset" && element.type !== "file") {
                element.value = "";
            }
        });

        // Clear select
        form.getElementsByTagName("select")[0].selectedIndex = 0;
    } else {
        console.error("Form not found");
    }
}

function show_AddEntry() {  
    var x = document.getElementById("kpiDiv");
    x.style.display = 'block';
    var firstField = document.getElementById('sem');
    firstField.focus();
}




</script>
</body>
</html>