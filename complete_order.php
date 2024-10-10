<?php
// start session to manage user sessions
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
    // if the connection fails, terminate script execution and display an error message
    die("connection failed: " . $conn->connect_error);
}

// retrieve order_id from URL parameter
if (isset($_GET['order_id'])) 
{
    $order_id = $_GET['order_id'];

    // update the status of the order to "Completed"
    $sql = "UPDATE orders SET status = 'Completed' WHERE order_id = $order_id";
    if ($conn->query($sql) === TRUE) 
    {
        echo "Order status updated successfully";
    } 
    else 
    {
        echo "Error updating order status: " . $conn->error;
    }

    // close connection
    $conn->close();

    // redirect back to manage orders page
    header("Location: manage_order.php");
    exit();
} 
else 
{
    echo "Invalid request";
}
?>
