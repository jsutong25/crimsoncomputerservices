<?php

session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: /CCS Website/admin/adminlogin.php");
}

$username = $_POST['email'];
$password = $_POST['password'];

$conn = new mysqli('localhost:3306', 'root', '', 'ccs_db');

?>
