<?php

include("../config.php");
session_start();
$user_id = $_SESSION['user_id'];



$db= $conn;
$tableName="services";
$columns= ['service_id','service_name','description', 'service_price','service_image'];
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
$query = "SELECT ".$columnName." FROM $tableName"." ORDER BY service_id DESC";
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
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];
    $service_price = $_POST['service_price'];
    $service_image = $_POST['service_image'];

    $insert_query = mysqli_query($conn, "insert into services set service_name ='$service_name', description = '$description', service_price = '$service_price', service_image = '$service_image'");
    if($insert_query>0){
        echo "<script>alert('Service added.');window.location.href='adminServices.php';</script>";
    } else {
        echo "<script>alert('Error 3.');window.location.href='adminServices.php';</script>";
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
   <title>Services - CCS</title>

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

                    <li><a href="adminProducts.php"  class="">
                        Products
                    </a></li>

                    <li><a href="adminServices.php" class="curr-page">
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
                    <form method="POST">
                    <h3>Add New Service</h3>
                        <div class="form-group">
                            <label for="service_name">Service Name</label>
                            <input type="text" name="service_name" placeholder="Enter service name">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" name="description" placeholder="Enter service description">
                        </div>

                        <div class="form-group">
                            <label for="service_price">Price</label>
                            <input type="text" name="service_price" placeholder="Enter service price">
                        </div>

                        <div class="form-group">
                            <label for="service_image">Service Photo</label>
                            <input type="text" name="service_image" placeholder="Enter file name + file type (laptopfan.jpg)">
                        </div>

                        <div class="form-group">
                            <input type="submit" name="submit" value="add" class="greybutton">
                        </div>
                    </form>
                </div>
            </div>

            <div class="adminpage-table">
            <?php
                $query = "SELECT service_id, service_name, description, service_price, service_image FROM services";
                $result = mysqli_query($conn, $query);
            ?>
            <table>
                <tr>
                    <th>Service ID</th>
                    <th>Service Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                </tr>
            <?php
            if (mysqli_num_rows($result) > 0) {
                $sn=1;
                while($data = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td><?php echo $data['service_id']; ?> </td>
                <td><?php echo $data['service_name']; ?> </td>
                <td><?php echo $data['description']; ?> </td>
                <td><?php echo $data['service_price']; ?> </td>
                <td><?php echo $data['service_image']; ?> </td>
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