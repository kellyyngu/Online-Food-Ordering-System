<?php
// start session to manage user sessions
session_start();

// check if the user is logged in
if (!isset($_SESSION['username'])) 
{
    header("Location: login.php");  // redirect the user to the login page if not logged in
    exit(); // stop further execution
}

// define database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "bakemyday";

// create a new connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// check if the connection was successful
if ($conn->connect_error) 
{
    // if the connection fails, terminate script execution and display an error message
    die("connection failed: " . $conn->connect_error);
}


// retrieve orders for the logged-in user
$username = $_SESSION['username'];
$sql = "SELECT * FROM orders WHERE username='$username'";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <link rel="stylesheet" type="text/css" href="myorder.css">
    <script src="https://kit.fontawesome.com/0b424c94e9.js" crossorigin="anonymous"></script>
</head>

<body>

<!-- top nav bar for myorder page -->
    <header>
        <img src="logo.png" height="50px">
        <a href="home.php" class="shopname">bakemyday<span>.</span></a>

        <div class="icons">
            <a href="menu.php"><i class="fa-solid fa-book-open"></i></a>
            <span class="menu">Menu</span>
            <a href="mycart.php"><i class="fa-solid fa-cart-shopping"></i></a>
            <span class="cart">Cart</span>
            <a href="myorder.php"><i class="fa-solid fa-receipt"></i></a>
            <span class="myorder">Order History</span>
            <a href="updateprofile.php"><i class="fa-regular fa-user"></i></a>
            <span class="uprofile">Update Profile</span>
            <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i></a>
            <span class="logout">Log Out</span>
        </div>
    </header>  


<!-- header title for myorder page -->
<h1 id="header">MY ORDER HISTORY</h1>
  
<br><br><br>
 

<!-- welcome message -->
<p style = "color:white; font-weight:bold; font-size:25px;">
    Welcome, <?php echo $_SESSION['username']; ?>!
</p>

<br><br>


<!-- PHP conditional to check if orders exist -->
<?php if ($result -> num_rows > 0): ?>
    <!-- table to display orders -->
    <table>
        <!-- table header -->
        <tr>
            <th>Order ID</th>
            <th>Total Price</th>
            <th>Order Date</th>
            <th>Items</th>
            <th>Status</th>
        </tr>

        <!-- loop to iterate over each order -->
        <?php while ($row = $result -> fetch_assoc()): ?>
            <tr>
                <td>
                    <?php echo $row['order_id']; ?>
                </td>

                <td>$
                    <?php echo $row['total_price']; ?>
                </td>

                <td>
                    <?php echo $row['order_date']; ?>
                </td>

                <!-- items in the order -->
                <td>
                    <?php
                    // retrieve items for this order
                    $order_id = $row['order_id'];
                    $items_sql = "SELECT * FROM order_items WHERE order_id='$order_id'";

                    $items_result = $conn -> query($items_sql);
                    if ($items_result -> num_rows > 0) 
                    {
                        echo "<ul>";
                        // loop through each item in the order
                        while ($item_row = $items_result -> fetch_assoc()) 
                        {
                            echo 
                            "<li>" 
                            .$item_row['item_name']. 
                            " - $"
                            .$item_row['price']
                            . "&nbsp;&nbsp;"
                            ." x " 
                            .$item_row['quantity'].
                            "</li><br><br>";
                        }
                        echo "</ul>";
                    } 
                    else 
                    {
                        echo "No items found.";
                    }
                    ?>
                </td>

                <td>
                    <?php echo $row['status']; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <h2 style="color:white; font-weight:bolder;">NO ORDERS FOUND.</h2>
<?php endif; ?>

<br><br>

<!-- include created footer (shop details) for mycart page -->
<?php include "footer.php" ?>

</body>
</html>
