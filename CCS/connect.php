<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/SMTP.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$cpass = $_POST['cpass'];

$conn = new mysqli('localhost:3306', 'root', '', 'ccs_db');
if ($conn->connect_error) {
    die('Connection Failed : ' . $conn->connect_error);
} else {
    if ($password == $cpass) {
        $sql = "SELECT * FROM user_info WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        if (!$result->num_rows > 0) {

            $stmt = $conn->prepare('insert into user_info(name, email, password, cpass) values (?,?,?,?)');
            $stmt->bind_param('ssss', $name, $email, $password, $cpass);
            $stmt->execute();
            $result = mysqli_query($conn, $sql);
            if ($result) {
                echo "<script>alert('Registered Successfully. You can now login.');window.location.href='login.php';</script>";
                $name = "";
                $email = "";
                $_POST['password'] = "";
                $_POST['cpass'] = "";
            } else {
                echo "<script>alert('Something went wrong.');window.location.href='register.php';</script>";
            }
        } else {
            echo "<script>alert('Email already exists.');window.location.href='register.php';</script>";
        }
    } else {
        echo "<script>alert('Password not matched.');window.location.href='register.php';</script>";
    }
}

$stmt->close();
