<?php

session_start();
$user_id = $_SESSION['user_id'];

if (isset($_SESSION['user_id'])) {
    header("Location: /CCS/checkout.php");
}

$address = $_POST['address'];
$contact_number = $_POST['contact_number'];
$total_order = $_POST['total_order'];
$payment = $_POST['payment'];

$name = mysqli_query($conn, "SELECT name FROM user_info WHERE user_id = '$user_id'");
$result = mysqli_fetch_array($name);
$name = $result['name'];

$email = mysqli_query($conn, "SELECT email FROM user_info WHERE user_id = '$user_id'");
$result = mysqli_fetch_array($email);
$email = $result['email'];

$conn = new mysqli('localhost:3306', 'root', '', 'ccs_db');
if ($conn->connect_error) {
    die('Connection Failed : ' . $conn->connect_error);
} else {
    $sql = "SELECT * FROM order_table";
    $result = mysqli_query($conn, $sql);
    $stmt = $conn->prepare('insert into order_table(user_id, name, email, address, contact_number, total_order, payment) values (?,?,?,?,?,?,?)');
    $stmt->bind_param('sssssss', $user_id, $name, $email, $address, $contact_number, $total_order, $payment);
    $stmt->execute();
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "<script>alert('Ordered Successfully.');window.location.href='checkout.php';</script>";
        $address = "";
        $contact_number = "";
    } else {
        echo "<script>alert('Error 3.');window.location.href='checkout.php';</script>";
    }
}

$stmt->close();
