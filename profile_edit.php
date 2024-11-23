<?php
session_start();
include("config.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,  initial-scale=1.0">
    <title>My Study Kpi</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script>
function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}

function deleteProfilePicture() {
    if (confirm("Are you sure you want to delete your profile picture?")) {
        // AJAX to send a request to the server to delete the profile picture
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_profile_picture.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Reload the page after successful deletion
                location.reload();
            }
        };
        xhr.send();
    }
}

function handleFileSelect() {
            var fileInput = document.getElementById('fileToUpload');
            var previewDiv = document.getElementById('preview');

            // Check if a file is selected
            if (fileInput.files && fileInput.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    // Display the selected image in a preview
                    previewDiv.innerHTML = '<img src="' + e.target.result + '" class="image">';
                };

                // Read the selected file as a data URL
                reader.readAsDataURL(fileInput.files[0]);
            }
}

</script>
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
            <h1 id="title">MY PROFILE</h1>
            <br>
            <br>
            <a href="#sec-2" class="scrollDown" id="exploreBtn">
                 Explore
                <div class="scrollDown"></div>
            </a>
        </div>
    </section>

    <?php
    //query the table user and profile
    $sql = "SELECT user.*, profile.* FROM user 
    INNER JOIN profile ON user.userID = profile.userID 
    WHERE user.userID = '{$_SESSION["UID"]}'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $matricNo = $row["matricNo"];
        $userEmail = $row["userEmail"];
        $name = $row["username"];
        $program = $row["program"];
        $mentor = $row["mentor"];
        $motto = $row["motto"];
        $profile = $row["img_path"];
    }
    ?>

    <section class="sec-2" id="sec-2">
        <div class="profile">

            <?php if (isset($_SESSION['error_message'])): ?>
                <p class="error"><?php echo $_SESSION['error_message']; ?> </p>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            <form id="profile" action="profile_edit_action.php" method="post" enctype="multipart/form-data">
                    <div class="left">
                        <div class="upload">
                            <div id="preview">
                                <?php
                                    echo '<img class="image" src="profile/' . $profile . '">';
                                ?>
                            </div>
                            <div class="round">
                                <input type="file" name="fileToUpload" id="fileToUpload" accept=".jpg, .jpeg, .png" onchange="handleFileSelect()">
                                <i class="fa fa-camera" style="color: #fff;"></i>
                            </div>
                        </div>
                        <button type = "button" onclick="deleteProfilePicture()">
                            <i class="fa fa-times" aria-hidden="true"></i> Remove Picture
                        </button>
                    </div>      
                    
                    <div class="right">             
                        <table id="profile-table" width="100%">
                            <tr>
                                <td width="164">Matric No. :</td>
                                    <td id="unedited"><?=$matricNo?></td>
                            </tr>
                            <tr>
                                <td width="164">Email :</td>
                                <td id="unedited"><?=$userEmail?></td>
                            </tr>
                            <tr>
                                <td width="164">Name :</td>
                                <td><input type="text" name="username" size="20" value="<?=$name?>"></td>
                            </tr>                    
                            <tr>
                                <td width="164">Program :</td>
                                <td>
                                    <select size="1" name="program">
                                        <option value="" <?php echo ($program == '') ? 'selected' : ''; ?> disabled>Select Program</option>   
                                        <option <?php echo ($program == 'Software Engineering') ? 'selected' : ''; ?>>Software Engineering</option>
                                        <option <?php echo ($program == 'Network Engineering') ? 'selected' : ''; ?>>Network Engineering</option>
                                        <option <?php echo ($program == 'Data Science') ? 'selected' : ''; ?>>Data Science</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td width="164">Mentor Name :</td>
                                <td><input type="text" name="mentor" size="20" value="<?=$mentor?>"></td>
                            </tr>
                            <tr>
                                <td colspan="2"> 
                                    My Study Motto :                           
                                    <textarea rows="2" name="motto" style="width:100%"><?=$motto?></textarea>
                                </td>
                            </tr>
                        </table>
                        
                        <div style="text-align: right; padding-bottom:5px;">
                            <input type="submit" id="form-submit" name="submit" value="Update"> 
                            <input type="reset" id="form-reset" value="Reset">
                        </div>
                    </div>
            </form>

        </div>
    </section>
    
    <footer>
        <p>Copyright &#169; 2023 - Noor</p>
    </footer>
</body>
</html>
