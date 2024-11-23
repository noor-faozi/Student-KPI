<?php
session_start();
include("config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Study KPI</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script>
   document.addEventListener("DOMContentLoaded", function () {
    // Check local storage for the registration form visibility
    var registerFormVisible = localStorage.getItem('registerFormVisible');

    if (registerFormVisible === 'true') {
        showRegisterForm();
    }

    function showRegisterForm() {
        var x = document.getElementById("registerDiv");
        x.style.display = 'block';

        var y = document.getElementById("newsDiv");
        y.style.display = 'none';

        var firstField = document.getElementById('matricNo');
        firstField.focus();
    }

    function hideRegisterForm() {
        var x = document.getElementById("registerDiv");
        x.style.display = 'none';

        var y = document.getElementById("newsDiv");
        y.style.display = 'block';
    }

    // Attach the showRegisterForm function to the element click event
    document.getElementById("showRegisterButton").addEventListener("click", function () {
        showRegisterForm();
        // Store the visibility state in local storage
        localStorage.setItem('registerFormVisible', 'true');
    });

    // Attach the hideRegisterForm function to the element click event
    document.getElementById("cancelRegisterButton").addEventListener("click", function () {
        hideRegisterForm();
        // Store the visibility state in local storage
        localStorage.setItem('registerFormVisible', 'false');
    });

    const togglePasswordElements = document.querySelectorAll(".fa-eye-slash");
    const passwordElements = document.querySelectorAll(".password");

    togglePasswordElements.forEach((togglePassword, index) => {
        togglePassword.addEventListener("click", function () {
            // toggle the type attribute
            const type = passwordElements[index].getAttribute("type") === "password" ? "text" : "password";
            passwordElements[index].setAttribute("type", type);

            // toggle the icon
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    });
});

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
        <div class="row">
            <div class="col-login">
                <?php 
                    if(isset($_SESSION["UID"])){
                        $sql = "SELECT user.matricNo, profile.username
                        FROM user 
                        INNER JOIN profile ON user.userID = profile.userID 
                        WHERE user.userID = ?";

                        $stmt = mysqli_prepare($conn, $sql);
                        mysqli_stmt_bind_param($stmt, "s", $_SESSION["UID"]);
                        mysqli_stmt_execute($stmt);

                        $result = mysqli_stmt_get_result($stmt);
                        
                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $matricNo = $row["matricNo"];
                            $username = $row["username"];
                        }
                        // Fetch user's profile picture
                        $profile = isset($_SESSION["img_path"]) ? $_SESSION["img_path"] : "";

                        echo '<div class="imgcontainer">';
                        echo '<img class="image" src="profile/' . $profile . '">';
                        echo '</div>';
                        if (!empty($username)) {
                            echo '<p align="center" style="font-weight: bold;">Welcome</p><p align="center">'. $username . "</p>";
                        } else {
                            echo '<p align="center" style="font-weight: bold;">Welcome</p><p align="center">' . $matricNo . "</p>";
                        }
                    }
                    else {
                        ?>
                        <form action="login_action.php" method="post" id="login">
                            <div class="imgcontainer">
                                <img src="profile/photo.png" alt="Avatar" class="avatar">
                            </div>

                            <div class="container">
                                <h2>Login</h2>
                                <?php if (isset($_SESSION['login_error'])): ?>
                                    <p class="error"><?php echo $_SESSION['login_error']; ?></p>
                                    <?php unset($_SESSION['login_error']); ?>
                                <?php endif; ?>
                                <div class="input-container">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                    <input type="text" placeholder="Username" name="userName" id="userName" required>
                                    
                                </div>
                                <div class="input-container">
                                    <i class=" fa fa-lock" aria-hidden="true"></i>
                                    <input type="password" class="password" placeholder="Enter Password" name="pwd" id="pwd" required>
                                    <i class="fa fa-eye-slash" aria-hidden="true" id="togglePassword1"></i>
                                </div>
                                <div class="button-container">
                                    <button type="submit">Login Now <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                                </div>
                                <label id="remember">
                                    <input type="checkbox" checked="checked" name="remember" > Remember me
                                </label>
                            </div>

                            <div class="container" style="">
                                <span class="psw">
                                    <a id="showRegisterButton" style="cursor: pointer;"> Register | </a>
                                    <a style="cursor: pointer;">Forgot password?</a>
                                </span>
                            </div>
                        </form>
                    <?php 
                    }   
                    ?>
            </div>

            <div class="col-news">
            <?php 
                if(isset($_SESSION["UID"])){
                    echo '
                    <div id="newsDiv">
                        <p><b>Announcement</b></p>         
                        <p>Important Notice Regarding Exam Schedule:<br>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. The final exam schedule for Semester 1 Session 2023/2024 have been uploaded. Please check your student portal for the scheduled dates and venues.
                        </p>
                        <p><b>News</b></p>
                        <p>Online Classes Until The End of The Semester:<br>
                        Our Computer and Informatics Faculty informs students that all lectures and classes will be held online until Week 14. Please check with your lecturer before purchasing a return flight or bus ticket.
                        </p>
                    </div>';
                } else {
                    echo '
                        <div id="newsDiv">
                            <p><b>Announcement</b></p>         
                            <p>Important Notice Regarding Exam Schedule:<br>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. The final exam schedule for Semester 1 Session 2023/2024 have been uploaded. Please check your student portal for the scheduled dates and venues.
                            </p>
                            <p><b>News</b></p>
                            <p>Online Classes Until The End of The Semester:<br>
                            Our Computer and Informatics Faculty informs students that all lectures and classes will be held online until Week 14. Please check with your lecturer before purchasing a return flight or bus ticket.
                            </p>
                        </div>
                    <div id="registerDiv">
                        <form action="register_action.php" method="post" id="register">
                            <div class="container">
                            
                                <h2> User Registration </h2>';
                    
                                if (isset($_SESSION['register_error'])) {
                                    echo '<p class="error">' . $_SESSION['register_error'] . '</p>';
                                    unset($_SESSION['register_error']);
                                }
                                
                                if (isset($_SESSION['register_success'])) {
                                    echo '<p class="success">' . $_SESSION['register_success'] . '</p>';
                                    unset($_SESSION['register_success']);
                                }
                                
                                echo '
                                <div class="input-container">
                                    <i class="fa fa-id-card-o" aria-hidden="true"></i>
                                    <input type="text" name="matricNo" placeholder="Enter Matric No" id="matricNo" required><br><br>
                                </div>
                                
                                <div class="input-container">
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                    <input type="email" id="userEmail" placeholder="Enter Email Address" name="userEmail" required><br><br>
                                </div>
                                
                                <div class="input-container">
                                    <i class=" fa fa-lock" aria-hidden="true"></i>
                                    <input type="password" id="userPwd" class="password" name="userPwd" placeholder="Enter Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Password must contain at least one digit, one lowercase letter, one uppercase letter, and be at least 8 characters long." required><br><br>
                                    <i class="fa fa-eye-slash" aria-hidden="true" id="togglePassword2"></i>
                                    
                                </div>
                                
                                <div class="input-container">
                                    <i class=" fa fa-lock" aria-hidden="true"></i>
                                    <input type="password" id="confirmPwd" class="password" name="confirmPwd" placeholder="Confirm Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required><br><br>
                                    <i class="fa fa-eye-slash" aria-hidden="true" id="togglePassword3"></i>
                                </div>
                                
                                <div class="button-container">
                                    <button type="submit">Register <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                                    <input type="reset" value="Reset">
                                    <input type="reset" value="Cancel" id="cancelRegisterButton" onClick="cancelRegister()">
                                </div>
                            </div>
                        </form>
                    </div>';
                } ?>
            </div>
        </div>
    </section>

    <footer>
        <p>Copyright &#169; 2023 - Noor</p>
    </footer>
</body>
</html>
