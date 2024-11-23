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
                if (isset($_SESSION["UID"])) {
                    include 'logged_menu.php';
                } else {
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

        <h2 style="padding: 10px;">List of KPI Activities</h2>

        <?php if (isset($_SESSION['error_message'])) : ?>
            <p class="error"><?php echo $_SESSION['error_message']; ?> </p>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <?php
        $id = "";
        $semYear = "";
        $name = "";
        $type = "";
        $remark = "";

        if (isset($_GET["id"]) && $_GET["id"] != "") {
            $id = $_GET["id"];
            $userID = $_SESSION["UID"];
            $sql = "SELECT * FROM kpiactivity WHERE id = ? AND userID = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $id, $userID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $semYear = $row['semYear'];
                $name = $row['name'];
                $type = $row['type'];
                $remark = $row['remark'];
            }
        }

        mysqli_close($conn);
        ?>

        <div style="padding: 10px;" id="kpiDiv">
            <h3 align="center">Edit KPI Activity</h3>
            <p align="center" style="color: red;">Required fields are marked*</p>

            <form method="POST" action="activities_management_edit_action.php" enctype="multipart/form-data" id="myForm">
                <div id="inputForm">
                    <input type="hidden" id="kaid" name="kaid" value="<?= $_GET['id'] ?>">
                    <label class="indicator">Add a semester/year</label>
                    <label>Semester and Year*</label>
                    <select name="semYear" id="semYear" required>
                        <option value="" >Select Semester and Year</option>

                        <?php
                        $semesterYearOptions = [
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
                    <label>Activity Name*</label>
                    <input type="text" name="name" value="<?php echo $name; ?>" required id="myInput">
                    <label>Activity Type*</label>
                    <select name="type" required>
                        <option value="1" <?php echo ($type == 1) ? 'selected' : ''; ?>>Faculty Activity</option>
                        <option value="2" <?php echo ($type == 2) ? 'selected' : ''; ?>>University Activity</option>
                        <option value="3" <?php echo ($type == 3) ? 'selected' : ''; ?>>National Activity</option>
                        <option value="4" <?php echo ($type == 4) ? 'selected' : ''; ?>>International Activity</option>
                    </select>
                    <label>Remark</label>
                    <textarea rows="4" name="remark" cols="20" id="myInput"><?php echo $remark; ?></textarea>

                    <div align="right">
                        <input type="submit" id="form-submit" value="Submit" name="B1">
                        <input type="button" id="form-reset" value="Reset" name="B2" onclick="resetForm()">
                        <input type="button" id="form-reset" value="Clear" name="B3" onclick="clearForm()">
                    </div>
                </div>
            </form>
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
                var firstField = document.getElementById('semYear');
                firstField.focus();
            }
        </script>
</body>

</html>
