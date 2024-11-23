<?php
session_start();
include("config.php");
?>
<!DOCTYPE html>
<html>

<head>
<title>My Study KPI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <h1 id="title">MY ACTIVITIES</h1>
            <br>
            <br>
            <a href="#sec-2" class="scrollDown" id="exploreBtn">
                 Explore
                <div class="scrollDown"></div>
            </a>
        </div>
    </section>

    <section class="sec-2" id="sec-2">

        <h2 style="padding: 10px;" >List of Activites</h2>

        <?php if (isset($_SESSION['success_message'])): ?>
            <p class="success"><?php echo $_SESSION['success_message']; ?> </p>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_message'])): ?>
            <p class="error"><?php echo $_SESSION['error_message']; ?> </p>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <div style="padding: 10px;" id="activity">
            <div style="text-align: center; padding:10px;">
                <form action="my_activities_search.php" method="post">
                    <div style="text-align: center; padding-top:10px;">
                        <div class="searchWrapper">
                            <input type="text" name="search" id="search" onkeyup="searchFunction()" placeholder="Search for activites.." title="Type in a challenge">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </div>
                    </div>
                </form> 
            </div>
            <div class="table-container">
                <table border="1" width="100%" id="projectable">
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Sem & Year</th>
                        <th width="30%">Activity</th>
                        <th width="15%">Remark</th>
                        <th width="10%">Photo</th>
                        <th width="10%">Action</th>
                    </tr>
                    <?php
                        $sql = "SELECT * FROM activity WHERE userID=". $_SESSION["UID"];
                        $result = mysqli_query($conn, $sql);
                        
                        if (mysqli_num_rows($result) > 0) {
                            // output data of each row
                            $numrow=1;
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $numrow . "</td><td>". $row["sem"] . " " . $row["year"]. "</td><td>" . $row["activity"] .
                                    "</td><td>" . $row["remark"] . "</td><td>";

                                    if (!empty($row["img_path"])) {
                                        echo "<img src='activity/" . $row["img_path"] . "' width='150' height='100'>";
                                    }
                                    
                                echo "</td>";
                                echo '<td> <a class="editBtn" href="my_activities_edit.php?id=' . $row["act_id"] . '">Edit</a>&nbsp;|&nbsp;';
                                echo '<a class="deleteBtn" href="my_activities_delete.php?id=' . $row["act_id"] . '&img_path=' . $row["img_path"] . '" onClick="return confirm(\'Delete?\');">Delete</a> </td>';
                                echo "</tr>" . "\n\t\t";
                                $numrow++;
                            }
                        } else {
                            echo '<tr><td colspan="7">0 results</td></tr>';
                        } 
                        
                        mysqli_close($conn);
                    ?>
                </table>
                <button type="button" id="new" onclick="show_AddEntry()" data-state="add">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add New
                </button>
            </div>
        </div>

        <div style="padding:0 10px;" id="activityDiv">
            <h3 align="center">Add activity</h3>
            <p align="center" style="color: red;">Required field with mark*</p>

            <form method="POST" action="my_activities_action.php" enctype="multipart/form-data" id="myForm">
                <div id="inputForm">
                    <label style="margin: 20px 20px 20px 0px; display: inline;">Semester*</label>
                        <select size="1" id="sem" name="sem" required>                        
                            <option value="">&nbsp;</option>
                            <option value="1">1</option>;                           
                            <option value="2">2</option>;                        
                        </select>
                    <label style="margin: 20px; display: inline;">Year*</label>
                        <input type="text" name="year" size="7" pattern="\d{4}\/\d{4}" title="Enter a year range in the format 'YYYY/YYYY'" required id="myInput">                                 
                    <label>Activity*</label>
                        <textarea rows="4" name="activity" cols="20" required id="myInput"></textarea>
                    <label>Remark</label>
                        <textarea rows="4" name="remark" cols="20" id="myInput"></textarea>
                    <label>Upload photo</label>
                    <p>Max size: 488.28KB</p>
                        <div class="uploadContainer">
                            <figure class="uploadImgContainer">
                                <img id="chosenImage" >
                                <figcaption id="file-name"></figcaption>
                            </figure>
                            <input type="file" name="fileToUpload" id="fileToUpload" accept=".jpg, .jpeg, .png" onclick="uploadPhoto()">
                            <label for="fileToUpload" id="fileLabel">
                                <i class="fa fa-upload" aria-hidden="true"></i> &nbsp;
                                Choose A Photo
                            </label>
                        </div>
                    <div align="right">
                        <input  type="submit" id="form-submit" value="Submit" name="B1">                
                        <input  type="reset" id="form-reset" value="Reset" name="B2">
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

function show_AddEntry() {
    var addButton = document.getElementById("new");
    var icon = addButton.querySelector("i");

    if (addButton.getAttribute("data-state") === "add") {
        // Switch to "Cancel" state
        addButton.innerHTML = '<i class="fa fa-times" aria-hidden="true" id="close-icon"></i> Cancel';
        addButton.setAttribute("data-state", "cancel");

        var x = document.getElementById("activityDiv");
        x.style.display = 'block';
        var firstField = document.getElementById('sem');
        firstField.focus();

    } else {
        // Switch back to "Add New" state
        addButton.innerHTML = '<i class="fa fa-plus" aria-hidden="true"></i> Add New';
        addButton.setAttribute("data-state", "add");

        var x = document.getElementById("activityDiv");
        x.style.display = 'none';
    }
}

function uploadPhoto() {
    let uploadButton = document.getElementById("fileToUpload");
    let chosenImage = document.getElementById("chosenImage");
    let fileName = document.getElementById("file-name");

    uploadButton.onchange = () => {
        let reader = new FileReader();
        reader.readAsDataURL(uploadButton.files[0]);
        console.log(uploadButton.files[0]);
        reader.onload = () => {
            chosenImage.setAttribute("src", reader.result);
        }
        fileName.textContent = uploadButton.files[0].name;   
    }
}

document.addEventListener("DOMContentLoaded", function() {
    uploadPhoto();
});

function searchFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search");
  filter = input.value.toUpperCase();
  table = document.getElementById("projectable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[2];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
</script>
</body>
</html>
