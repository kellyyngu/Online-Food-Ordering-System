<?php
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

// check if order_id is provided and valid
if(isset($_GET['order_id']) && is_numeric($_GET['order_id'])) 
{
    $order_id = $_GET['order_id'];
    
    // delete order items associated with the order
    $sql_delete_items = "DELETE FROM order_items WHERE order_id='$order_id'";
    if ($conn -> query($sql_delete_items) === TRUE) 
    {
        // delete order
        $sql_delete_order = "DELETE FROM orders WHERE order_id='$order_id'";
        if ($conn->query($sql_delete_order) === TRUE) 
        {
            $success_message = "Order and associated items deleted successfully.";
        } 
        else 
        {
            $error_message = "Error deleting order: " . $conn->error;
        }
    } 
    else 
    {
        $error_message = "Error deleting order items: " . $conn->error;
    }
} 
else 
{
    // redirect to the manage orders page if order_id is not provided or invalid
    header("Location: manage_order.php");
    exit();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Delete Order</title>
    <link rel="stylesheet" type="text/css" href="delete_order.css">
</head>

<body>

    <!-- header title -->
    <h1>Delete Order</h1>

    <!-- display whether the item is being deleted successfully or not -->
    <?php if (isset($success_message)): ?>
        <p style='font-weight:bold; font-size:20px;'><?php echo $success_message; ?></p>
    <?php elseif (isset($error_message)): ?>
        <p style='font-weight:bold; ont-size:20px;'><?php echo $error_message; ?></p>
    <?php endif; ?>

    <br><br><br>

    <!-- back button -->
    <div id="back">
        <a href="manage_order.php">Back to Manage Orders</a>
    </div>
</body>
</html>
