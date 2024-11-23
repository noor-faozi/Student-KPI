<?php
session_start();
include("config.php");

// STEP 1: Form data handling using mysqli_real_escape_string function to escape special characters for use in an SQL query,
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userMatric = mysqli_real_escape_string($conn, $_POST['matricNo']);
    $userEmail = mysqli_real_escape_string($conn, $_POST['userEmail']);
    $userPwd = mysqli_real_escape_string($conn, $_POST['userPwd']);
    $confirmPwd = mysqli_real_escape_string($conn, $_POST['confirmPwd']);

    // Validate pwd and confirmPwd
    if (empty($userMatric) || empty($userEmail) || empty($userPwd) || empty($confirmPwd)) {
        $_SESSION['register_error'] = "All fields are required";
        header("Location: index.php");
        exit();
    } elseif ($userPwd !== $confirmPwd) {
        $_SESSION['register_error'] = "The confirmation password does not match";
        header("Location: index.php");
        exit();
    } 

    // STEP 2: Check if userEmail already exists
    $sql = "SELECT * FROM user WHERE userEmail='$userEmail' OR matricNo='$userMatric' LIMIT 1"; 
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['register_error'] = "User Email already exists";
        header("Location: index.php");
        exit();
    } else {
        // User does not exist, insert new user record, hash the password       
        $pwdHash = trim(password_hash($userPwd, PASSWORD_DEFAULT)); 
        $sql = "INSERT INTO user (matricNo, userEmail, userPwd ) VALUES ('$userMatric', '$userEmail', '$pwdHash')";
        $insertOK = 0;

        if (mysqli_query($conn, $sql)) {
            $_SESSION['register_success'] = "New user record created successfully.";
            $insertOK = 1;
        } else {
            $_SESSION['register_error'] = "Error creating user record";
            header("Location: index.php");
        }

        if ($insertOK == 1) {
            $lastInsertedId = mysqli_insert_id($conn);
            $sql = "INSERT INTO profile (userID, username, program, mentor, motto ) VALUES ('$lastInsertedId', '', '', '', '')";

            if (mysqli_query($conn, $sql)) {
                $_SESSION['register_success'] .= " Welcome " . $userMatric;
                header("Location: index.php");
            } else {
                $_SESSION['register_error'] .= " Error creating user profile record";
                header("Location: index.php");
            }
        }
    }
}

mysqli_close($conn);
?>
