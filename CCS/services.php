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

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Services - CCS</title>

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
                    <li><a href="services.php" class="curr-page">
                        Services
                    </a></li>

                    <li><a href="products.php"  class="">
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

        <main>
            <div class="featured-product-header">
                <h3>FEATURED PRODUCT</h3>
                </div>
                <div class="featured-product">
                    <h2>Apple Airpods (2nd Generation)</h2>
                    <a href="products.php"><img src="assets/airpods.jpg" alt="Airpods (2nd generation)" width="225" height="225"></a>
                    <p>Limited Stocks only!</p>
                </div>
                
                <div class="container">
                    <div class="products">  
                        <h1 class="heading">SERVICES OFFERED</h1>

                        <div class="box-container">
                            <?php
                            $select_service = mysqli_query($conn, "SELECT * FROM `services`") or die('query failed');
                            if(mysqli_num_rows($select_service) > 0){
                                while($fetch_service = mysqli_fetch_assoc($select_service)){
                            ?>
                            <form method="post" class="box" action="">
                                <img src="assets/<?php echo $fetch_service["service_image"]; ?>" alt="" width="260">
                                <div class="name"><?php echo $fetch_service['service_name']; ?></div>
                                <div class="description"><?php echo $fetch_service['description']; ?></div>
                                <div class="price">P<?php echo $fetch_service['service_price']; ?>/-</div>
                                <a href="schedule.php" class="btn">Schedule now</a>

                                <input type="hidden" name="service_image" value="<?php echo $fetch_service['service_image']; ?>">
                                <input type="hidden" name="service_name" value="<?php echo $fetch_service['service_name']; ?>">
                                <input type="hidden" name="service_price" value="<?php echo $fetch_service['service_price']; ?>">
                            </form>
                            <?php
                            };
                            };
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
   </body>
</html>