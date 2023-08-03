<?php

include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendMail($email,$order_id) {

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
        $mail->Subject = 'Your order has been placed - Crimson Computer Services';
        $mail->Body    = "Thank you for trusting Crimson Computer Services! Your order id is $order_id. 
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
   header('location:index.php');
};

if(isset($_POST['update_cart'])){
   $update_quantity = $_POST['product_quantity'];
   $update_id = $_POST['cart_id'];
   mysqli_query($conn, "UPDATE `cart` SET product_quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
}

if(isset($_GET['remove'])){
   $remove_id = $_GET['remove'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('query failed');
   header('location:checkout.php');
}
  
if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:checkout.php');
}

if(isset($_SESSION['user_id'])) {
   if(isset($_POST['order'])) {

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

   $product_name = mysqli_query($conn, "SELECT product_name FROM cart WHERE user_id = '$user_id'");
   $result = mysqli_fetch_array($product_name);
   $product_name = $result['product_name'];

   $product_quantity = mysqli_query($conn, "SELECT product_quantity FROM cart WHERE user_id = '$user_id'");
   $result = mysqli_fetch_array($product_quantity);
   $product_quantity = $result['product_quantity'];

   $product_id = mysqli_query($conn, "SELECT product_id FROM products WHERE product_name = '$product_name'");
   $result = mysqli_fetch_array($product_id);
   $product_id = $result['product_id'];

   $product_price = mysqli_query($conn, "SELECT product_price FROM products WHERE product_name = '$product_name'");
   $result = mysqli_fetch_array($product_price);
   $product_price = $result['product_price'];

   // $currentDate = date('Y-m-d');
   date_default_timezone_set('Asia/Manila');
   $currentDate = date('d-m-y h:i:s');

   if($address == "" || $contact_number == "" || $payment == "") {
       
       echo "<script>alert('All fields are mandatory.');window.location.href='checkout.php';</script>";
   }

       $user_id = $_SESSION['user_id'];
       $query = "SELECT user_id, product_id, product_name, product_price, product_quantity FROM cart";

       $query_run = mysqli_query($conn,$query);

       $insert_query = "INSERT INTO order_table(name, email, address, contact_number, total_order, payment, date_ordered) VALUES ('$name', '$email', '$address', '$contact_number', '$total_order', '$payment', '$currentDate')";
       $insert_query_run = mysqli_query($conn,$insert_query);

       if($insert_query_run) {

           $order_id = mysqli_insert_id($conn);
           foreach($query_run as $citem) {

               $user_id = $citem['user_id'];
               $product_id = $citem['product_id'];
               $product_name = $citem['product_name'];
               $product_quantity = $citem['product_quantity'];
               $product_price = $citem['product_price'];

               $insert_items_query = "INSERT INTO order_items (order_id, user_id, product_id, product_name, product_quantity, price) VALUES ('$order_id', '$user_id', '$product_id', '$product_name', '$product_quantity', '$product_price')";
               $insert_items_query_run = mysqli_query($conn,$insert_items_query);
               sendMail($email,$order_id);
               echo "<script>alert('Ordered Successfully.');window.location.href='services.php';</script>";
               $address = "";
               $contact_number = "";
               mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
            }
            
         }


      }
} else {
   header('location:checkout.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout - CCS</title>

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

               <li><a href="schedule.php" class="">
                     Schedule
               </a></li>

               <li><a href="checkout.php" class="curr-page">
                     Cart
               </a></li>

               <li><a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('are your sure you want to logout?');" class="">Logout</a></li>
            </ul>

         </nav>
      </header>

      <main>
         <div class="container">
            <div class="shopping-cart">

               <h1 class="heading">shopping cart</h1>

               <table>
                  <thead>
                     <th>Image</th>
                     <th>Name</th>
                     <th>Price</th>
                     <th>Quantity</th>
                     <th>Total Price</th>
                     <th>Action</th>
                  </thead>
                  <tbody>
                  <?php
                     $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                     $grand_total = 0;
                     if(mysqli_num_rows($cart_query) > 0){
                        while($fetch_cart = mysqli_fetch_assoc($cart_query)){
                  ?>
                     <tr>
                        <td><img src="assets/<?php echo $fetch_cart['product_image']; ?>" height="100" alt=""></td>
                        <td><?php echo $fetch_cart['product_name']; ?></td>
                        <td>P<?php echo $fetch_cart['product_price']; ?>/-</td>
                        <td>
                           <form action="" method="post">
                              <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                              <input type="number" min="1" name="product_quantity" value="<?php echo $fetch_cart['product_quantity']; ?>">
                              <input type="submit" name="update_cart" value="update" class="option-btn">
                           </form>
                        </td>
                        <td>P<?php echo $sub_total = ($fetch_cart['product_price'] * $fetch_cart['product_quantity']); ?>/-</td>
                        <td><a href="checkout.php?remove=<?php echo $fetch_cart['id']; ?>" class="delete-btn" onclick="return confirm('remove item from cart?');">remove</a></td>
                     </tr>
                  <?php
                     $grand_total += $sub_total;
                        }
                     }else{
                        echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">no item added</td></tr>';
                     }
                  ?>
                  <tr class="table-bottom">
                     <td colspan="4">Total :</td>
                     <td>P<?php echo $grand_total; ?>/-</td>
                     <td><a href="checkout.php?delete_all" onclick="return confirm('delete all from cart?');" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">delete all</a></td>
                  </tr>
               </tbody>
               </table>

            </div>
         </div>

         <div class="container-payment">
            <div class="payment">
                  <form action="" method="POST">

                     <h2>Total: P<?php echo $grand_total; ?> </h2>
                     <input type="hidden" name="total_order" value="<?php echo $grand_total; ?>">
                     
                     
                     <div class="form-check">
                        <label for="address">Address: </label>
                        <input type="text" name="address" placeholder="Enter address">
                     </div>

                     <div class="form-check">
                        <label for="contact_number">Contact Number: </label>
                        <input type="text" name="contact_number" placeholder="Enter contact number">
                     </div>
                        
                     <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment" value="cod" checked>
                        <label for="payment">Cash on delivery</label>
                     </div>
                     
                     <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment" value="gcash">
                        <label for="payment">Gcash</label>
                     </div>

                     <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment" value="credit card">
                        <label for="payment">Credit Card</label>
                     </div>
                     
                     
                     <div class="form-check">
                        <input type="submit" name="order" value="order" class="btn">
                     </div>

                  </form>
               </div>
            </div>
         </div>
      </main>


   </body>
</html>