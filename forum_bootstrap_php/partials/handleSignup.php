<?php
$showError = "false";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    include 'dbconnect.php';
    $user_email = $_POST['signupEmail'];
    $pass = $_POST['signupPassword'];
    $cpass = $_POST['signupcPassword'];

    // untuk mengecek apabila email sudah terdaftar

    $existSql = "SELECT * FROM `users` WHERE user_email='$user_email'";
    $result = mysqli_query($conn, $existSql);
    $numRows = mysqli_num_rows($result);
    if($numRows > 0) {
        $showError = "Email sedang digunakan oleh orang lain.";
    } 
    else {
        if($pass == $cpass) {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `users`(`user_email`, `user_pass`, `timestamp`) VALUES ('$user_email','$hash', CURRENT_TIMESTAMP())";
            $result = mysqli_query($conn, $sql);
            if($result) {
                $showAlert = true;
                header("Location: /forum_bootstrap_php/index.php?signupsuccess=true");
                exit();
            }
        } 
        else {
            $showError = "Password tidak sesuai.";
        } 
    }
            header("Location: /forum_bootstrap_php/index.php?signupsuccess=false&error=$showError");

}






?>