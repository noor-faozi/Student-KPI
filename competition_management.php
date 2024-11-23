<?php
session_start();
include("config.php");

// Redirect to the login page if the user is not logged in
if (!isset($_SESSION["UID"])) {
    header("location:index.php");
    exit();
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
                include 'logged_menu.php';
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
        <h2 style="padding: 10px;">KPI Competitions Management</h2>

        <?php
        include 'kpi_menu.php';
        ?>

        <?php if (isset($_SESSION['success_message'])) : ?>
            <p class="success"><?php echo $_SESSION['success_message']; ?> </p>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_message'])) : ?>
            <p class="error"><?php echo $_SESSION['error_message']; ?> </p>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <div style="padding: 10px;" id="kpi">
            <div class="table-container">

                <table border="1" width="100%" class="kpi" id="projectable" style="overflow-x: auto;">
                    <tr>
                        <th width="5%">No</th>
                        <th>Sem & Year</th>
                        <th>Competition Name</th>
                        <th>Competition Type</th>
                        <th>Remark</th>
                        <th width="10%">Action</th>
                    </tr>

                    <?php
                    $sql = "SELECT * FROM kpicompetition WHERE userID=" . $_SESSION["UID"];
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        // output data of each row
                        $numrow = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $numrow . "</td><td>" . $row["semYear"] . "</td><td>" . $row["name"] . "</td><td>" . getCompetitionTypeDescription($row["type"]) . "</td><td>" . $row["remark"] .
                                "</td><td> <a class='editBtn' href='competition_management_edit.php?id=" . $row["id"] . "'>Edit</a>&nbsp;|&nbsp;";
                            echo '<a class="deleteBtn" href="competition_management_delete.php?id=' . $row["id"] . '" onClick="return confirm(\'Delete?\');">Delete</a> </td>';
                            echo "</tr>" . "\n\t\t";
                            $numrow++;
                        }
                    } else {
                        echo '<tr><td colspan="16">0 results</td></tr>';
                    }

                    mysqli_close($conn);

                    function getCompetitionTypeDescription($type)
                    {
                        switch ($type) {
                            case 1:
                                return 'Faculty';
                            case 2:
                                return 'University';
                            case 3:
                                return 'National';
                            case 4:
                                return 'International';
                            default:
                                return 'Unknown';
                        }
                    }
                    ?>
                </table>
                <button type="button" id="new" onclick="show_AddEntry()" data-state="add">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add New
                </button>
            </div>
        </div>

        <div style="padding:0 10px;" id="kpiDiv">
            <h3 align="center">Add KPI Competition</h3>
            <p align="center" style="color: red;">Required field with mark*</p>

            <form method="POST" action="competition_management_action.php" enctype="multipart/form-data" id="myForm">
                <div id="inputForm">
                    <label class="indicator">Add a semester/year</label>
                    <label>Semester and Year*<label>
                            <select size="1" id="semYear" name="semYear" required>
                                <option value=""></option>
                                <option value="YEAR 1 SEM 1">YEAR 1 SEM 1</option>;
                                <option value="YEAR 1 SEM 2">YEAR 1 SEM 2</option>;
                                <option value="YEAR 2 SEM 1">YEAR 2 SEM 1</option>;
                                <option value="YEAR 2 SEM 2">YEAR 2 SEM 2</option>;
                                <option value="YEAR 3 SEM 1">YEAR 3 SEM 1</option>;
                                <option value="YEAR 3 SEM 2">YEAR 3 SEM 2</option>;
                                <option value="YEAR 4 SEM 1">YEAR 4 SEM 1</option>;
                                <option value="YEAR 4 SEM 2">YEAR 4 SEM 2</option>;
                            </select>
                    <label class="indicator">Competitions</label>
                    <label for="name">Competition Name*</label>
                    <input type="text" name="name" required id="myInput">

                    <label for="type">Competition Type*</label>
                    <select size="1" id="myInput" name="type" required>
                        <option value="1">Faculty Competition</option>
                        <option value="2">University Competition</option>
                        <option value="3">National Competition</option>
                        <option value="4">International Competition</option>
                    </select>

                    <label for="remark" class="indicator">Remark</label>
                    <textarea name="remark" id="myInput" rows="4" cols="50"></textarea>

                    <div colspan="3" align="right">
                        <input id="form-submit" type="submit" value="Submit" name="B1">
                        <input id="form-reset" type="reset" value="Reset" name="B2">
                    </div>
                </div>
            </form>
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

        function show_AddEntry() {
            var addButton = document.getElementById("new");
            var icon = addButton.querySelector("i");

            if (addButton.getAttribute("data-state") === "add") {
                // Switch to "Cancel" state
                addButton.innerHTML = '<i class="fa fa-times" aria-hidden="true" id="close-icon"></i> Cancel';
                addButton.setAttribute("data-state", "cancel");

                var x = document.getElementById("kpiDiv");
                x.style.display = 'block';
                var firstField = document.getElementById('semYear');
                firstField.focus();

            } else {
                // Switch back to "Add New" state
                addButton.innerHTML = '<i class="fa fa-plus" aria-hidden="true"></i> Add New';
                addButton.setAttribute("data-state", "add");

                var x = document.getElementById("kpiDiv");
                x.style.display = 'none';
            }
        }
    </script>
</body>

</html>
