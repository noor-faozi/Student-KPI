<?php
session_start();
include("config.php");

$sqlCheck = "SELECT COUNT(*) as count FROM indicator WHERE userID = ?";
$stmtCheck = mysqli_prepare($conn, $sqlCheck);
mysqli_stmt_bind_param($stmtCheck, "i", $_SESSION["UID"]);
mysqli_stmt_execute($stmtCheck);
$resultCheck = mysqli_stmt_get_result($stmtCheck);
$rowCheck = mysqli_fetch_assoc($resultCheck);

if ($rowCheck['count'] == 0) {
    // No records exist, insert initial records
    $semesters = [
        "FACULTY KPI",
        "STUDENT AIM",
        "YEAR 1 SEM 1",
        "YEAR 1 SEM 2",
        "YEAR 2 SEM 1",
        "YEAR 2 SEM 2",
        "YEAR 3 SEM 1",
        "YEAR 3 SEM 2",
        "YEAR 4 SEM 1",
        "YEAR 4 SEM 2"
    ];

    foreach ($semesters as $semester) {
        // Check if the specific semYear already exists for the user
        $sqlCheckSemYear = "SELECT COUNT(*) as count FROM indicator WHERE userID = ? AND semYear = ?";
        $stmtCheckSemYear = mysqli_prepare($conn, $sqlCheckSemYear);
        mysqli_stmt_bind_param($stmtCheckSemYear, "is", $_SESSION["UID"], $semester);
        mysqli_stmt_execute($stmtCheckSemYear);
        $resultSemYearCheck = mysqli_stmt_get_result($stmtCheckSemYear);
        $rowSemYearCheck = mysqli_fetch_assoc($resultSemYearCheck);
        mysqli_stmt_close($stmtCheckSemYear); // Close the check statement
    
        if ($rowSemYearCheck['count'] == 0) {
            // Insert only if the semYear does not exist
            $sqlInsert = "INSERT INTO indicator (userID, semYear) VALUES (?, ?)";
            $stmtInsert = mysqli_prepare($conn, $sqlInsert);
            mysqli_stmt_bind_param($stmtInsert, "is", $_SESSION["UID"], $semester);
            mysqli_stmt_execute($stmtInsert);
            mysqli_stmt_close($stmtInsert); // Close the insert statement
        }
    }
    
    
}


?>
<!DOCTYPE html>
<html>

<head>
<title>My Study KPI</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">   
</head>
<body>
    <section class="sec-1">
        <div class="header">
            <div class="menu">
                <?php 
                    if(isset($_SESSION["UID"])){
                        include 'logged_menu.php';
                    }
                    else {
                        header("location:index.php");
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
                
        <?php 
            include 'kpi_menu.php';
        ?>

        <?php if (isset($_SESSION['success_message'])): ?>
            <p class="success"><?php echo $_SESSION['success_message']; ?> </p>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_message'])): ?>
            <p class="error"><?php echo $_SESSION['error_message']; ?> </p>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        
        <div style="padding: 10px;" id="kpi">
            <div class="table-container">

                <table border="1" width="100%" class="kpi" id="projectable" style="overflow-x: auto;">
                    <tr>
                        <th rowspan="2" width="5%">No</th>
                        <th rowspan="2" width="7%">Sem & Year</th>
                        <th rowspan="2" width="7%">CGPA</th>
                        <th colspan="4">STUDENT ACTIVITIES</th>
                        <th colspan="4">COMPETITION</th>
                        <th rowspan="2" width="7%">CERTIFICATE</th>
                        <th rowspan="2">REMARK</th>
                        <th rowspan="2" width="10%">Action</th>
                    </tr>
                    <tr id="subrow">
                        <th width="5%">FACULTY LEVEL</th>
                        <th width="5%">UNIVERSITY LEVEL</th>
                        <th width="5%">NATIONAL LEVEL</th>
                        <th width="5%">INTERNATIONAL LEVEL</th>
                        <th width="5%">FACULTY LEVEL</th>
                        <th width="5%">UNIVERSITY LEVEL</th>
                        <th width="5%">NATIONAL LEVEL</th>
                        <th width="5%">INTERNATIONAL LEVEL</th>
                    </tr>

                    <?php
                    $sql = "SELECT * FROM indicator WHERE userID=" . $_SESSION["UID"];
                    $result = mysqli_query($conn, $sql);


                    if (mysqli_num_rows($result) > 0) {
                        $numrow = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $numrow . "</td><td>" . $row["semYear"] . "</td><td>" . $row["cgpa"] .
                                "</td><td>" . $row["facultyAct"] . "</td><td>" . $row["uniAct"] . "</td><td>" . $row["nationalAct"] .
                                "</td><td>" . $row["interAct"] . "</td><td>" . $row["facultyComp"] . "</td><td>" . $row["uniComp"] .
                                "</td><td>" . $row["nationalComp"] . "</td><td>" . $row["interComp"] . "</td><td>" . $row["certificate"] .
                                "</td><td>" . $row["remark"] . "</td>";
                            echo '<td style="vertical-align: middle; text-align: center;"> <a class="editBtn" style=" padding: 5px 15px;" href="my_kpi_edit.php?id=' . $row["ind_id"] . '">Edit</a>';
                            echo "</tr>" . "\n\t\t";
                            $numrow++;
                        }
                    } else {
                        echo '<tr><td colspan="16">0 results</td></tr>';
                    }

                    mysqli_close($conn);
                    ?>
                </table>
            </div>
        </div>

        
    </section>
    

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


</script>
</body>
</html>
