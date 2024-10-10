<?php
session_start();

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
    die("Connection failed: " . $conn->connect_error); 
}

// fetch orders
$sql = "SELECT o.order_id, o.username, o.total_price, o.order_date, o.status FROM orders o";
$result = $conn->query($sql);

// close connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
    <link rel="stylesheet" type="text/css" href="manage_order.css">
    <script src="https://kit.fontawesome.com/0b424c94e9.js" crossorigin="anonymous"></script>
</head>

<body>
    <!-- top navigation bar for the registration page -->
    <header>
        <img src="logo.png" height="50px">
        <h3 class="shopname">bakemyday<span>.</span></h3>

        <div class="icons">
            <a href="admin_dashboard.php"><i class="fa-solid fa-circle-left"></i></a>
            <span class="back"> Back To Dashboard</span>
        </div>
    </header>

    <!-- manage order page's header title -->
    <h1>Manage Orders</h1>

    <!-- table to display orders -->
    <table> 
        <tr>
            <th>Order ID</th>
            <th>Username</th>
            <th>Total Amount</th>
            <th>Order Time</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        
        <!-- loop through each order and display its details -->
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['order_id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['total_price']; ?></td>
                <td><?php echo $row['order_date']; ?></td>
                <td class="<?php echo $row['status'] == 'Pending' ? 'pending' : 'completed'; ?>">
                     <?php echo $row['status']; ?>   <!-- display the status with different styles based on its value -->
                </td>

                <!-- actions for each order: Edit, Delete, and Complete -->
                <td>
                    <a class="edit" href="edit_order.php?order_id=<?php echo $row['order_id']; ?>">
                        Edit
                    </a>

                    <a class="delete" href="delete_order.php?order_id=<?php echo $row['order_id']; ?>">
                        Delete
                    </a>

                    <!-- display complete action only for pending orders -->
                    <?php if ($row['status'] == 'Pending'): ?>
                        <a class="complete" href="complete_order.php?order_id=<?php echo $row['order_id']; ?>">
                            Complete
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>
