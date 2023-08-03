<?php

include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:index.php');
};

if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header('location:index.php');
};

if(isset($_POST['add_to_cart'])){

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $product_id = mysqli_query($conn, "SELECT product_id FROM products WHERE product_name = '$product_name'");
   $result = mysqli_fetch_array($product_id);
   $product_id = $result['product_id'];

   $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE product_name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($select_cart) > 0){
      echo "<script>alert('Product already in cart.');window.location.href='products.php';</script>";
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(user_id, product_id, product_name, product_price, product_image, product_quantity) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('query failed');
      echo "<script>alert('Added to cart.');window.location.href='products.php';</script>";
   }

};

if(isset($_POST['update_cart'])){
   $update_quantity = $_POST['product_quantity'];
   $update_id = $_POST['cart_id'];
   mysqli_query($conn, "UPDATE `cart` SET product_quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
}

if(isset($_GET['remove'])){
   $remove_id = $_GET['remove'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('query failed');
   header('location:products.php');
}
  
if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:products.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products - CCS</title>

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

               <li><a href="products.php"  class="curr-page">
                  Products
               </a></li>

               <li><a href="schedule.php" class="">
                  Schedule
               </a></li>

               <li><a href="checkout.php" class="">
                  Cart
               </a></li>

               <li><a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('are your sure you want to logout?');" class="">Logout</a></li>
            </ul>

      </nav>
   </header>

      <div class="container">
         <div class="products">

            <h1 class="heading">latest products</h1>

            <div class="box-container">

            <?php
               $select_product = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
               if(mysqli_num_rows($select_product) > 0){
                  while($fetch_product = mysqli_fetch_assoc($select_product)){
            ?>
               <form method="post" class="box" action="">
                  <img src="assets/<?php echo $fetch_product["product_image"]; ?>" alt="" width="250">
                  <div class="name"><?php echo $fetch_product['product_name']; ?></div>
                  <div class="price">P<?php echo $fetch_product['product_price']; ?>/-</div>

                  <label for="quantity">Quantity:</label>
                  <input type="number" min="1" name="product_quantity" value="1">
                  <input type="hidden" name="product_image" value="<?php echo $fetch_product['product_image']; ?>">
                  <input type="hidden" name="product_name" value="<?php echo $fetch_product['product_name']; ?>">
                  <input type="hidden" name="product_price" value="<?php echo $fetch_product['product_price']; ?>">
                  <input type="submit" value="add to cart" name="add_to_cart" class="btn">
               </form>
            <?php
               };
            };
            ?>

            </div>

         </div>

         

         </div>

   </body>
</html>