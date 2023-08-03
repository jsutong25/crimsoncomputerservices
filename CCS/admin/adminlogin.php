<?php

include '../config.php';
session_start();

if(isset($_POST['submit'])){

   $username = mysqli_real_escape_string($conn, $_POST['email']);
   $password = mysqli_real_escape_string($conn, $_POST['password']);

   $select = mysqli_query($conn, "SELECT * FROM `user_info` WHERE email = 'admin.ccs@gmail.com' AND password = 'admin'") or die('query failed');

   if(mysqli_num_rows($select) > 0){
      $row = mysqli_fetch_assoc($select);
      $_SESSION['user_id'] = $row['user_id'];
      header('location:adminpage.php');
   }else{
      $message[] = 'incorrect password or email!';
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

   <link rel="stylesheet" href="adminstyle.css">

</head>
   <body>

      <?php
      if(isset($message)){
         foreach($message as $message){
            echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
         }
      }
      ?>
         
         
      <div class="background-img"> 
         <label class="logo"><a href="../index.php"> CCS</a></label>
         <div class="form-container">

            <form method="post">
               <h3>login now (admin)</h3>
               
               <label for="email">Email</label>
               <input type="text" name="email" required placeholder="enter username" class="box">

               <label for="password">Password</label>
               <input type="password" name="password" required placeholder="enter password" class="box">
               
               <input type="submit" name="submit" class="greybutton" value="login now">

            </form>

         </div>
      </div>

   </body>
</html> 