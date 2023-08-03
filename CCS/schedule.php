<?php

include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendMail($email,$service_name,$name) {

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
        $mail->Subject = 'Your booking has been placed - Crimson Computer Services';
        $mail->Body    = "Thank you for trusting Crimson Computer Services! Your booking is $service_name, under the name of $name. 
        <br>This will serve as your official receipt. ";
    
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if(!isset($user_id)){
   header('location:index.php');
};

if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header('location:login.php');
};

$sql = "SELECT * FROM `services`";
$conn = new mysqli('localhost:3306', 'root', '', 'ccs_db');
$all_services = mysqli_query($conn,$sql);

if(isset($_POST['schedule'])){

   $name = $_POST['name'];
   $contact_number = $_POST['contact_number'];
   $service_name = $_POST['service_name'];  
   $date = $_POST['date'];
   $time = $_POST['time'];
   $payment = $_POST['payment'];

   $service_id = mysqli_query($conn, "SELECT service_id FROM services WHERE service_name = '$service_name'");
   $result = mysqli_fetch_array($service_id);
   $service_id = $result['service_id'];

   $service_price = mysqli_query($conn, "SELECT service_price FROM services WHERE service_name = '$service_name'");
   $result = mysqli_fetch_array($service_price);
   $service_price = $result['service_price'];

   $email = mysqli_query($conn, "SELECT email FROM user_info WHERE user_id = '$user_id'");
   $result = mysqli_fetch_array($email);
   $email = $result['email'];
 
   // $currentDate = date('Y-m-d');
   date_default_timezone_set('Asia/Manila');
   $currentDate = date('d-m-y h:i:s');

   $select_ordertable = mysqli_query($conn, "SELECT * FROM `schedule_table` WHERE user_id = '$user_id'") or die('query failed');

   $conn = new mysqli('localhost:3306', 'root', '', 'ccs_db');
   if ($conn->connect_error) {
      die('Connection Failed : ' . $conn->connect_error);
   } else {
      $sql = "SELECT * FROM schedule_table";
      $result = mysqli_query($conn, $sql);


      $stmt = $conn->prepare('insert into schedule_table(user_id, service_id, name, contact_number, service_name, date, time, total_order, payment, date_ordered) values (?,?,?,?,?,?,?,?,?,?)');
      $stmt->bind_param('ssssssssss', $user_id, $service_id, $name, $contact_number, $service_name, $date, $time, $service_price, $payment, $currentDate);
      $stmt->execute();
      $result = mysqli_query($conn, $sql);

      if ($result) {
         sendMail($email,$service_name,$name);
         echo "<script>alert('Booked Successfully.');window.location.href='services.php';</script>";
         $name = "";
         $contact_number = "";
         $services = "";
      } else {
         echo "<script>alert('Error 3.');window.location.href='schedule.php';</script>";
      }
   }

};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Schedule - CCS</title>

   <!-- custom css file link  -->
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
                    <li><a href="services.php" class="">
                        Services
                    </a></li>

                    <li><a href="products.php"  class="">
                        Products
                    </a></li>

                    <li><a href="schedule.php" class="curr-page">
                        Schedule
                    </a></li>

                    <li><a href="checkout.php" class="">
                        Cart
                    </a></li>

                    <li><a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('are your sure you want to logout?');" class="">Logout</a></li>
                </ul>

            </nav>
        </header>

      <main>
         <div class="background-img">
            <div class="form-container">
               <form action="" method="POST">
                  <h3>Schedule Service</h3>

                  <div class="form-group">
                     <label for="name">Name</label>
                     <input type="text" name="name" required placeholder="Enter full name" class="box">
                  </div>

                  <div class="form-group">
                     <label for="contact_number">Contact Number</label>
                     <input type="text" name="contact_number" required placeholder="Enter 11 digit number" class="box">
                  </div>

                  <div class="form-group">
                     <label for="">Select service:</label>
                     <select name="service_name">
                     <option value="">--Select Service--</option>
                     <?php 
                     $sql = mysqli_query($conn, "SELECT * FROM services");

                     while (($row = $sql->fetch_assoc()) ){

                     ?>
                     <option value="<?php echo $row['service_name']; ?>"><?php echo $row['service_name'] . ' - ' . $row['service_price']; ?></option>
                     
                     
                     <?php
                     
                     // close while loop 
                     }
                     ?>
                     </select>
                  </div>

                  <div class="form-group">
                     <label for="date">Select Date:</label>
                     <input type="date" name="date" class="" id="date" required="required">
                  </div>

                  <div class="form-group">
                     <label for="time">Select time (8:00 AM - 5:00 PM ONLY): </label>
                     <input type="time" name="time" class="" id="time" required="required">
                  </div>

                  <br>

                  <div class="form-check">
                     <input class="form-check-input" type="radio" name="payment" value="gcash">
                     <label for="payment">In-store</label>
                  </div>
                  
                  <div class="form-check">
                     <input class="form-check-input" type="radio" name="payment" value="gcash">
                     <label for="payment">Gcash</label>
                  </div>

                  <div class="form-check">
                     <input class="form-check-input" type="radio" name="payment" value="credit card">
                     <label for="payment">Credit Card</label>
                  </div>

                  <div class="form-group">
                     <input type="submit" name="schedule" value="schedule" class="greybutton">
                  </div>
               </form>
            </div>
         </div>
      </main>

      
      <script> 
         var otherInput;
         function checkOptions(select) {
            otherInput = document.getElementById('otherInput');
            if(select.options[select.selectedIndex].value == "Other") {
               otherInput.style.display = 'block';
            }
            else {
               otherInput.style.display = 'none';
            }
         }
   
      </script>
   </body>
</html>