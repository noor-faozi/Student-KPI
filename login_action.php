<?php
session_start();
include("config.php");

if (isset($_POST['userName']) && isset($_POST['pwd'])) {
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $userName = validate($_POST['userName']);
    $userPwd = validate($_POST['pwd']);

    if (empty($userName) || empty($userPwd)) {
        $_SESSION['login_error'] = "User Name and Password are required";
        header("Location: index.php");
        exit();
    } else {
        // Hashing the password using a secure algorithm
        $hashedPwd = password_hash($userPwd, PASSWORD_DEFAULT);
        
        $sql = "SELECT user.*, profile.img_path FROM user 
            INNER JOIN profile ON user.userID = profile.userID 
            WHERE user.matricNo=?";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userName);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($userPwd, $row['userPwd'])) {
                $_SESSION["UID"] = $row["userID"];
                $_SESSION["img_path"] = $row["img_path"];
                $_SESSION['loggedin_time'] = time();  // set logged in time
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['login_error'] = "Incorrect User Name or Password";
                header("Location: index.php");
                exit();
            }
        } else {
            $_SESSION['login_error'] = "User does not exist";
            header("Location: index.php");
            exit();
        }
    }
} else {
    $_SESSION['login_error'] = "Invalid request";
    header("Location: index.php");
    exit();
}

mysqli_close($conn);
?>
