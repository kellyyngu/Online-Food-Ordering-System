<?php
// start or resume a session
session_start();

// define database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "bakemyday"; 

// establish a new connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// check if the database connection was successful
if ($conn->connect_error) 
{
    // terminate the script and display an error message if connection fails
    die("Connection failed: " . $conn->connect_error);
}

// verify that a numeric order_id is provided in the URL
if(isset($_GET['order_id']) && is_numeric($_GET['order_id'])) 
{
    $order_id = $_GET['order_id'];

    // execute a SQL query to fetch details for the specified order
    $sql_order = "SELECT * FROM orders WHERE order_id='$order_id'";
    $result_order = $conn->query($sql_order);
    if ($result_order->num_rows > 0) 
    {
        // retrieve order details from the query result
        $row_order = $result_order->fetch_assoc();
        $username = $row_order['username'];
        $total_price = $row_order['total_price'];
        $order_date = $row_order['order_date'];
    } 
    else 
    {
        // display a message and exit if no order is found with the provided ID
        echo "Order not found.";
        exit();
    }

    // fetch items associated with the order
    $sql_items = "SELECT * FROM order_items WHERE order_id='$order_id'";
    $result_items = $conn->query($sql_items);
} 
else 
{
    // display a message and exit if the order_id is invalid
    echo "Invalid order ID.";
    exit();
}

// check if remove_item parameter is set and valid, then delete the specified item
if (isset($_GET['remove_item']) and is_numeric($_GET['remove_item'])) {
    $item_id_to_remove = $_GET['remove_item'];
    $sql_delete_item = "DELETE FROM order_items WHERE id='$item_id_to_remove'";
    if ($conn->query($sql_delete_item) === TRUE) {
        echo "<script>alert('Item removed successfully.');</script>";
        // redirect to the same page to refresh the item list after deletion
        header("Location: edit_order.php?order_id=$order_id");
        exit();
    } else {
        echo "<script>alert('Error removing item: " . $conn->error . "');</script>";
    }
}

// process quantity updates from the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    foreach ($_POST['quantity'] as $item_id => $quantity) 
    {
        if (is_numeric($quantity)) 
        {
            // update the quantity of the order item
            $sql_update_quantity = "UPDATE order_items SET quantity='$quantity' WHERE id='$item_id'";
            $conn->query($sql_update_quantity);

            // delete the item if its quantity is set to 0
            if ($quantity == 0) 
            {
                $sql_remove_item = "DELETE FROM order_items WHERE id='$item_id'";
                $conn->query($sql_remove_item);
            }
        }
    }

    // redirect to the same page to reflect the quantity updates
    header("Location: edit_order.php?order_id=$order_id");
    exit();
}

// recalculate the total price for the order after updates
$sql_total_price = "SELECT SUM(price * quantity) AS total FROM order_items WHERE order_id='$order_id'";
$result_total_price = $conn->query($sql_total_price);
if ($result_total_price->num_rows > 0) 
{
    $row_total_price = $result_total_price->fetch_assoc();
    $total_price = $row_total_price['total'];

    // update the total price in the orders table
    $sql_update_total_price = "UPDATE orders SET total_price='$total_price' WHERE order_id='$order_id'";
    $conn->query($sql_update_total_price);
}

// close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Order</title>
    <link rel="stylesheet" type="text/css" href="edit_order.css">
</head>

<body>
    <h1>Edit Order</h1><br>

    <h2>Order Details:</h2>

<!-- display the order details -->
<div class="order-details">
    <p style='font-weight:bold;'>Order ID: <?php echo $order_id; ?></p>
    <p style='font-weight:bold;'>Username: <?php echo $username; ?></p>
    <p style='font-weight:bold;'>Total Price: $<?php echo $total_price; ?></p>
    <p style='font-weight:bold;'>Order Date: <?php echo $order_date; ?></p>
</div>

<br><br>

    <h2>Edit Items:</h2>
    <form method="post">
    <div class="table-container">
        <table>
            <tr>
                <th>Item Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
            <!-- loop through each item in the order for editing -->
            <?php while ($row_item = $result_items->fetch_assoc()): ?>
                <tr>
                <td>
                    <?php echo $row_item['item_name']; ?>
                </td>

                <td>$
                    <?php echo $row_item['price']; ?>
                </td>

                <td>
                    <input type of="number" name="quantity[<?php echo $row_item['id']; ?>]" min="0" step="1" value="<?php echo $row_item['quantity']; ?>">
                    <input type="submit" value="Update" class="button-container">
                </td>

                <td><a href="edit_order.php?order_id=<?php echo $order_id; ?>&remove_item=<?php echo $row_item['id']; ?>">Remove</a></td>
            </tr>
            <?php endwhile; ?>
        </table>

        </div>
    </form>

    <br><br>

<!-- link to return to the manage orders page -->
<div class="back">
    <a href="manage_order.php" style="color: white;">
        Back to Manage Orders
    </a>
</div>
</body>
</html>
