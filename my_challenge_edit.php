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
            <h1 id="title">MY CHALLENGES</h1>
            <br>
            <br>
            <a href="#sec-2" class="scrollDown" id="exploreBtn">
                 Explore
                <div class="scrollDown"></div>
            </a>
        </div>
    </section>

    <section class="sec-2" id="sec-2">
        <h2 style="padding: 10px;" >List of Challenge and Plan</h2>

        <?php if (isset($_SESSION['error_message'])): ?>
            <p class="error"><?php echo $_SESSION['error_message']; ?> </p>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>


        <?php
            $id = "";
            $sem = "";
            $year = "";
            $challenge =" ";
            $plan = "";
            $remark = "";
            $img = "";

            if(isset($_GET["id"]) && $_GET["id"] != ""){
                $id = $_GET["id"];
                $userID = $_SESSION["UID"];
                $sql = "SELECT * FROM challenge WHERE ch_id = ? AND userID = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $id, $userID);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $sem = $row["sem"];
                    $year = $row["year"];
                    $challenge = $row["challenge"];
                    $plan = $row["plan"];
                    $remark = $row["remark"];
                    $img = $row["img_path"];
                }        
            }

            mysqli_close($conn);
        ?>

        <div style="padding: 10px;" id="challengeDiv">
            <h3 align="center">Edit Challenge and Plan</h3>
            <p align="center" style="color: red;">Required field with mark*</p>

            <form method="POST" action="my_challenge_edit_action.php" id="myForm" enctype="multipart/form-data">
                <div id="inputForm">
                    <input type="hidden" id="cid" name="cid" value="<?=$_GET['id']?>">
                            <label style="margin: 20px 20px 20px 0px; display: inline;">Semester*</label>
                                <select size="1" name="sem" id="sem" required>                        
                                    <option value="">&nbsp;</option>
                                    <?php
                                    if($sem=="1")
                                        echo '<option value="1" selected>1</option>';
                                    else
                                        echo '<option value="1">1</option>';
                                    
                                    if($sem=="2")
                                        echo '<option value="2" selected>2</option>';
                                    else
                                        echo '<option value="2">2</option>';
                                    ?>
                                </select>
                            <label style="margin: 20px; display: inline;">Year*</label>
                                <?php
                                if($year!=""){
                                    echo '<input type="text" name="year" size="7" pattern="\d{4}\/\d{4}" title="Enter a year range in the format \'YYYY/YYYY\'" value= "' . $year . '" required id="myInput">';
                                }
                                else {
                                ?>
                                    <input type="text" name="year" size="7" pattern="\d{4}\/\d{4}" title="Enter a year range in the format 'YYYY/YYYY'" required id="myInput">
                                <?php
                                }
                                ?>                
                            <label>Challenge*</label>
                                <textarea rows="4" name="challenge" cols="20" required id="myInput"><?php echo $challenge;?></textarea>
                            <label>Plan*</label>
                                <textarea rows="4" name="plan" cols="20" required id="myInput"><?php echo $plan;?></textarea>
                            <label>Remark</label>
                                <textarea rows="4" name="remark" cols="20" id="myInput"><?php  echo $remark;?></textarea>
                            <label>Photo</label>
                                <input type="text" id="img_path" disabled value="<?=$img;?>" id="myInput">
                                <a href="#" id="remove" onclick="deleteImage('<?=$row["ch_id"]?>', '<?=$row["img_path"]?>'); clearImagePath(); return false;">Delete</a><br>

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
                                <input type="submit" id="form-submit" value="Submit" name="B1">                
                                <input type="button" id="form-reset" value="Reset" name="B2" onclick="resetForm()">
                                <input type="button" id="form-reset" value="Clear" name="B3" onclick="clearForm()">
                            </div>
                </div>
            </form>
        </div>
    </section>
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
    var x = document.getElementById("challengeDiv");
    x.style.display = 'block';
    var firstField = document.getElementById('sem');
    firstField.focus();
}

function deleteImage(id, imgPath) {
    if (confirm('Delete?')) {
        // Use AJAX to send a request to delete_image.php
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Update the UI or provide feedback if needed
                    alert('Image deleted successfully!');
                    
                    // You may choose to update the UI to reflect the deletion without refreshing the page
                    // For example, remove the image element from the DOM or update the image source.
                } else {
                    // Handle the error
                    alert('Failed to delete image. ' + response.error);
                }
            }
        };

        // Send a GET request to delete_image.php with the challenge ID and image path
        xhr.open('GET', 'delete_image.php?id=' + id + '&img_path=' + imgPath, true);
        xhr.send();
    }
}

// Function to clear the image path input
function clearImagePath() {
    var imgPathInput = document.getElementById("img_path");
    if (imgPathInput) {
        imgPathInput.value = "";
    } else {
        console.error("Image path input not found");
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

</script>
</body>
</html>