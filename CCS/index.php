<?php

include 'config.php';

session_start();

error_reporting(0);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crimson Computer Services</title>
    <link rel="stylesheet" href="newstyle.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    
</head>
<body>

    <header>
        <nav class="nav-services">
            <input type="checkbox" id="check">
            <label for="check" class="checkbtn">
                <i class="fas fa-bars"></i>
            </label>
            <label for="logo" class="logo">CCS</label>

            <ul class="nav-items">
                <li><a href="register.php" class="">
                    Sign Up
                </a></li>
                <li><a href="login.php" class="">
                    Login
                </a></li>
            </ul>
        </nav>
    </header>
    
    <main>

        <div class="container-lp">
            <div class="container-body">
                <h1 class="main-text">Technical Support and more,<br> right at your service.</h1>
                
                <div class="big-sign-up">
                    <h3>Sign up now to avail.</h3>
                    <a href="register.php"><button class="big-sign-up-button" id="signup2">Sign Up</button></a>
                </div>
            </div>
        </div>
    </main>

</body>
</html>