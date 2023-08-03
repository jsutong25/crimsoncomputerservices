<?php

include 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendMail($email, $v_code) {

    require("PHPMailer/PHPMailer.php");
    require("PHPMailer/SMTP.php");
    require("PHPMailer/Exception.php");

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.gmail.com';                     
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'jtongshs@gmail.com';                     
        $mail->Password   = 'unomtomhhoiviogq';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            
        $mail->Port       = 587;                                    
    
        $mail->setFrom('jtongshs@gmail.com', 'Crimson Computer Services');
        $mail->addAddress($email);     
    
        $mail->isHTML(true);
        $mail->Subject = 'Email verification - Crimson Computer Services';
        $mail->Body    = "Thanks for registering at Crimson Computer Services! Click the link to verify the email address 
        <a href='http://localhost/CCS/verify.php?email=$email&v_code=$v_code'>Verify</a>";
    
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if(isset($_POST['submit'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpass = $_POST['cpass'];

    $user_exist_query="SELECT * FROM `user_info` WHERE `email`='$_POST[email]'";
    $result = mysqli_query($conn,$user_exist_query);

    if($result) {
        if(mysqli_num_rows($result)>0){
            echo "<script>alert('Email already exists.');window.location.href='register.php';</script>";
        }else {

            $v_code=bin2hex(random_bytes(16));

            $query="INSERT INTO `user_info`(name, email, password, cpass, verification_code, is_verified) VALUES('$name', '$email', '$password', '$cpass', '$v_code', '0')";

            if(mysqli_query($conn,$query) && sendMail($_POST['email'],$v_code)) {
                echo "<script>alert('Registration successful. Go to email to verify account.');window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Server Down');window.location.href='login.php';</script>";
            }
        }
    }

}



?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sign Up - CCS</title>

   <link rel="stylesheet" href="newstyle.css">
   <script src="https://kit.fontawesome.com/a076d05399.js"></script>

</head>
   <body>

        <?php
            if(isset($message)){
            foreach($message as $message){
                echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
                }
            }
        ?>

        <header>
            <nav class="nav-services">
                <input type="checkbox" id="check">
                <label for="check" class="checkbtn">
                    <i class="fas fa-bars"></i>
                </label>
                <label for="logo" class="logo">CCS</label>

                <ul class="nav-items">

                    <li><a href="register.php" class="curr-page">
                        Sign Up
                    </a></li>
                    <li><a href="login.php" class="">
                        Login
                    </a></li>
                </ul>
            </nav>
        </header>
        
        <main>
            <div class="background-img">
                <div class="form-container">
                    <form action="" method="post">
                    <h3>register now</h3>

                    <label for="name">Name</label>
                    <input type="text" name="name" required placeholder="Enter full name" class="box">

                    <label for="name">Email Address</label>
                    <input type="email" name="email" required placeholder="Enter email" class="box">

                    <label for="name">Password</label>
                    <input type="password" name="password" required placeholder="Enter password" class="box">

                    <label for="name">Confirm Password</label>
                    <input type="password" name="cpass" required placeholder="Confirm password" class="box">

                    <input type="submit" name="submit" class="greybutton" value="register now">
                    <p>Already have an account? <a href="login.php">Login now</a></p>
                    </form>
                </div>
            </div>
        </main>

   </body>
</html>