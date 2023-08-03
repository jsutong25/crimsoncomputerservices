<?php

include("../config.php");
session_start();
$user_id = $_SESSION['user_id'];


$db= $conn;
$tableName="products";
$columns= ['product_name','product_price','product_image'];
$fetchData = fetch_data($db, $tableName, $columns);

function fetch_data($db, $tableName, $columns){
 if(empty($db)){
  $msg= "Database connection error";
 }elseif (empty($columns) || !is_array($columns)) {
  $msg="columns Name must be defined in an indexed array";
 }elseif(empty($tableName)){
   $msg= "Table Name is empty";
}else{

$columnName = implode(", ", $columns);
$query = "SELECT ".$columnName." FROM $tableName"." ORDER BY product_name DESC";
$result = $db->query($query);

if($result== true){ 
 if ($result->num_rows > 0) {
    $row= mysqli_fetch_all($result, MYSQLI_ASSOC);
    $msg= $row;
 } else {
    $msg= "No Data Found"; 
 }
}else{
  $msg= mysqli_error($db);
}
}
return $msg;
}

if(isset($_POST['submit'])){
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];

    $insert_query = mysqli_query($conn, "insert into products set product_name ='$product_name', product_price = '$product_price', product_image = '$product_image'");
    if($insert_query>0){
        echo "<script>alert('Product added.');window.location.href='adminProducts.php';</script>";
    } else {
        echo "<script>alert('Error 3.');window.location.href='adminProducts.php';</script>";
    }
}

if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header('location:adminlogin.php');
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products (Admin) - CCS</title>

   <style type="text/css">
        table {
            border-collapse: collapse;
            width: 95%;
            color: #bd1335;
            font-family: 'TT Norms', sans-serif;
            font-size: 20px;
            text-align: center;
            color: #000000;
            margin-left: 2%;
            margin-top: 20px;
            margin-bottom: 70px;
        }

        th {
            background-color: #bd1335;
            color: #FFFFFF;
            padding-left: 5px;
            padding-right: 5px;
        }

        tr:nth-child(even) {
            background-color: #d9798c;
        }
    </style>

    <link rel="stylesheet" href="adminstyle.css">
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

                    <li><a href="adminpage.php" class="">
                        Schedules
                    </a></li>

                    <li><a href="adminOrders.php" class="">
                        Orders
                    </a></li>

                    <li><a href="adminOrderItems.php" class="">
                        Order Items
                    </a></li>

                    <li><a href="adminProducts.php"  class="curr-page">
                        Products
                    </a></li>

                    <li><a href="adminServices.php" class="">
                        Services
                    </a></li>

                    <li><a href="adminCustomer.php" class="">
                        Customer
                    </a></li>

                    <li><a href="adminlogin.php?logout=<?php echo $user_id; ?>" onclick="return confirm('are your sure you want to logout?');" class="">Logout</a></li>
                </ul>

            </nav>
        </header>

        <main>
            <div class="table-responsive">  
                <div class="box">  
                    <form id="validate_form" method="POST">
                    <h3>Add New Product</h3>
                        <div class="form-group">
                            <label for="product_name">Product Name</label>
                            <input type="text" name="product_name" placeholder="Enter product name">
                        </div>

                        <div class="form-group">
                            <label for="product_price">Product Price</label>
                            <input type="text" name="product_price" placeholder="Enter product price">
                        </div>

                        <div class="form-group">
                            <label for="product_image">Product Photo</label>
                            <input type="text" name="product_image" placeholder="Enter file name + file type (laptopfan.jpg)">
                        </div>

                        <div class="form-group">
                        <input type="submit" name="submit" value="add" class="greybutton">
                     </div>
                    </form>
                </div>
            </div>

            <div class="adminpage-table">
            <?php
                $query = "SELECT product_id, product_name, product_price, product_image FROM products";
                $result = mysqli_query($conn, $query);
            ?>
            <table>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Image</th>
                </tr>
            <?php
            if (mysqli_num_rows($result) > 0) {
                $sn=1;
                while($data = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td><?php echo $data['product_id']; ?> </td>
                <td><?php echo $data['product_name']; ?> </td>
                <td><?php echo $data['product_price']; ?> </td>
                <td><?php echo $data['product_image']; ?> </td>
            <tr>
            <?php
            $sn++;}} else { ?>
                <tr>
                    <td colspan="8">No data found</td>
                </tr>
            <?php } ?>
            </table>
            </div>
        </main>


   </body>
</html>