<?php

include 'config.php';
session_start();

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $password = mysqli_real_escape_string($conn, $_POST['password']);

   $select = mysqli_query($conn, "SELECT * FROM `user_info` WHERE email = '$email' AND password = '$password'") or die('query failed');


   if(mysqli_num_rows($select) > 0){
      $row = mysqli_fetch_assoc($select);

      $query= "SELECT * FROM `user_info` WHERE email= '$email'";
      $result = mysqli_query($conn, $query);

      if($result) {
         if(mysqli_num_rows($result) == 1) {
            $result_fetch = mysqli_fetch_assoc($result);

            if($result_fetch['is_verified'] == 1) {
               $_SESSION['user_id'] = $row['user_id'];
               echo "<script>alert('Login Successfully.');window.location.href='services.php';</script>";
            } elseif($result_fetch['is_verified'] == 2) {
               $message[] = 'Incorrect email or password.';
            }else{
               $message[] = 'Email not verified!';
            }

         } else {
            $message[] = 'Incorrect email or password.';
         }
      } 
   } else {
      $message[] = 'Incorrect email or password.';
   }
   

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login - CCS</title>

   <!-- custom css file link  -->
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

               <li><a href="register.php" class="">
                  Sign Up
               </a></li>
               <li><a href="login.php" class="curr-page">
                  Login
               </a></li>
            </ul>
         </nav>
      </header>
         
         
      <div class="background-img"> 
         <div class="form-container">

            <form action="" method="post">
               <h3>login now</h3>
               
               <label for="email">Email</label>
               <input type="email" name="email" required placeholder="enter email" class="box">

               <label for="password">Password</label>
               <input type="password" name="password" required placeholder="enter password" class="box">
               
               <input type="submit" name="submit" class="greybutton" value="login now">
               <p>Don't have an account? <a href="register.php">Register now</a></p>
            </form>

         </div>
      </div>

   </body>
</html>