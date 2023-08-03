<?php

include("../config.php");
session_start();
$user_id = $_SESSION['user_id'];



$db= $conn;
$tableName="order_table";
$columns= ['order_id', 'name','email', 'address','contact_number','total_order','payment','date_ordered'];
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
$query = "SELECT ".$columnName." FROM $tableName"." ORDER BY order_id DESC";
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
   <title>Orders - CCS</title>

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

                    <li><a href="adminOrderItems.php" class="curr-page">
                        Order Items
                    </a></li>

                    <li><a href="adminProducts.php"  class="">
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
            <div class="adminpage-table">
            <?php

                $query = "SELECT order_id, user_id, product_id, product_name, product_quantity, price FROM order_items";
                $result = mysqli_query($conn, $query);
                
            ?>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price of Product</th>
                </tr>
            <?php
            if (mysqli_num_rows($result) > 0) {
                $sn=1;
                while(($data = mysqli_fetch_assoc($result))) {
            ?>
                <tr>    
                    <td><?php echo $data['order_id']; ?> </td>
                    <td><?php echo $data['user_id']; ?> </td>
                    <td><?php echo $data['product_id']; ?> </td>
                    <td><?php echo $data['product_name']; ?> </td>
                    <td><?php echo $data['product_quantity']; ?> </td>
                    <td><?php echo $data['price']; ?> </td>
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