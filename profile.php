<?php
session_start();
include("config.php");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    // Check if the user is signed in
    if(isset($_SESSION["UID"])){
        // Query the user and profile table for this user
        $sql = "SELECT user.*, profile.* FROM user 
        INNER JOIN profile ON user.userID = profile.userID 
        WHERE user.userID = '{$_SESSION["UID"]}'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            $matricNo = $row["matricNo"];
            $userEmail = $row["userEmail"];
            $username = $row["username"];
            $program = $row["program"];
            $mentor = $row["mentor"];
            $motto = $row["motto"];
            $profile = $row["img_path"];
        }
    ?>
    <section class="sec-2" id="sec-2">
        <div class="row">
            <?php if (isset($_SESSION['success_message'])): ?>
                <p class="success"><?php echo $_SESSION['success_message']; ?> </p>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <div class="col-left">
                <?php
                if (!empty($profile)) {
                    echo '<img class="profileimg" src="profile/' . $profile . '">';
                }
                ?>
                <div style="text-align: center; padding-bottom:20px;">
                    <a href="profile_edit.php" id="edit"><i class="fa fa-pencil" aria-hidden="true"></i>Edit My Profile</a>
                </div>  

            </div>
            <div class="col-right" > 
                <div id="profile-card">
                    <div id="header-profile" style="margin: 10px;">
                        <h2>Student Profle</h3> 
                    </div><hr>
                    
                    <table width="100%" height="60px">
                        <tr>
                            <td width="164" id="heading">Name :</td>
                            <td><?=$username?></td>
                        </tr>
                        <tr>
                            <td width="164" id="heading">Matric No. :</td>
                            <td><?=$matricNo?></td>
                        </tr>
                        <tr>
                            <td width="164" id="heading">Email :</td>
                            <td><?=$userEmail?></td>
                        </tr>
                        <tr>
                            <td width="164" id="heading">Program :</td>
                            <td><?=$program?></td>
                        </tr>
                        <tr>
                            <td width="164" id="heading">Mentor Name :</td>
                            <td><?=$mentor?></td>
                        </tr>
                    </table>
                    <br>
                    
                    <table width="100%">
                        <tr>
                            <td id="heading">My Study Motto :</td>
                        </tr>
                        <tr>
                            <td style="box-shadow: 0 0 4px 0px rgba(85, 85, 85, 0.8);">
                                <?php
                                if($motto==""){
                                    echo "&nbsp;";
                                }
                                else{
                                    echo $motto;
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
        </div>
    </section>
    <?php
    } else {
        echo '<p>No user is currently signed in.</p>';
    }
    ?>
    <footer>
        <p>Copyright &#169; 2023 - Noor</p>
    </footer>
</body>
</html>
