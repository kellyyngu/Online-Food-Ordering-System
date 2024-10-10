<!DOCTYPE html>
<html>

<head>
    <title>Order Confirmation Page</title>
    <link rel="stylesheet" href="confirm_order.css">
</head>

<body>
    <div class="confirm-container">


    <!-- top nav bar for confirm order page -->
    <header>
        <img src="logo.png" height="50px">
        <a class="shopname">bakemyday<span>.</span></a>
    </header>
    

    <!-- header title for confirm order page -->
    <h2 class="order-heading">ORDER CONFIRMATION</h2>

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

    // retrieve user input from the form
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['address'])&& isset($_POST['card_number'])) 
    {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $username = $_SESSION['username']; 
        $card_number = $_POST['card_number'];

        // hash the CVV
        $hashed_cvv = hash('sha256', $_POST['cvv']);

        // update user input into the 'users' table
        $updateUserDetailsSQL = "UPDATE users SET address = '$address', phonenum = '$phone', cardnum = '$card_number', cardcvv = '$hashed_cvv' WHERE username = '$username'";
        if ($conn->query($updateUserDetailsSQL) === TRUE) 
        {

        } 
        else 
        {
            echo "Error updating user details: " . $conn->error;
        }
    }
    ?>

    <?php
    // Set the timezone to GMT+8 (Asia/Singapore)
    date_default_timezone_set('Asia/Singapore');
    
    // retrieve cart items for the logged-in user
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM cart WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) 
    {
        // start transaction
        $conn->begin_transaction();

        try 
        {
            // insert order details into the orders table
            $totalPrice = 0;
            $orderDateTime = date("Y-m-d H:i:s");

            // insert order into orders table
            $insertOrderSQL = "INSERT INTO orders (username, total_price, order_date) VALUES ('$username', 0, '$orderDateTime')";
            $conn->query($insertOrderSQL);
            $orderID = $conn->insert_id;

            // insert order items into order_items table
            while ($row = $result->fetch_assoc()) 
            {
                $itemName = $row['item_name'];
                $price = $row['price'];
                $quantity = $row['quantity'];
                $subtotal = $price * $quantity;

                // insert item into order_items table
                $insertOrderItemSQL = "INSERT INTO order_items (order_id, item_name, price, quantity) VALUES ('$orderID', '$itemName', '$price', '$quantity')";
                $conn->query($insertOrderItemSQL);

                $totalPrice += $subtotal;
            }

            // update total price in orders table
            $updateTotalPriceSQL = "UPDATE orders SET total_price='$totalPrice' WHERE order_id='$orderID'";
            $conn->query($updateTotalPriceSQL);

            // commit the transaction
            $conn->commit();

            echo "<table>";
            echo "<tr><th>Item Name</th><th>Price</th><th>Quantity</th></tr>";

            // retrieve order items for display
            $orderItemsSQL = "SELECT * FROM order_items WHERE order_id='$orderID'";
            $orderItemsResult = $conn->query($orderItemsSQL);

            if ($orderItemsResult->num_rows > 0) 
            {
                while ($orderItem = $orderItemsResult->fetch_assoc()) 
                {
                    echo "<tr>";
                    echo "<td>" . $orderItem['item_name'] . "</td>";
                    echo "<td>$" . $orderItem['price'] . "</td>";
                    echo "<td>" . $orderItem['quantity'] . "</td>";
                    echo "</tr>";
                }
            }

            echo 
            "</table>";
            echo
            "<br>";
            echo 
            "<p style=font-weight:bold;'>Order ID: $orderID</p>";
            echo 
            "<br><p style=font-weight:bold;'>Total Price: $" 
            .$totalPrice. 
            "</p>";
            echo 
            "<br><p style=font-weight:bold;'>Order Date: " 
            .$orderDateTime.
            "</p>";
            echo 
            "<br><p style=font-weight:bold;'>Delivery Address: " 
            .$address. 
            "</p>";
            echo 
            "<br><p style=font-weight:bold;'>Phone Number: " 
            .$phone. 
            "</p>";

            // clear the cart 
            $clearCartSQL = "DELETE FROM cart WHERE username='$username'";
            $conn->query($clearCartSQL);
        } 
        catch (Exception $e) 
        {
            // rollback the transaction in case of an error
            $conn->rollback();
            echo "Error occurred while processing order. Please try again later.";
        }
    } 
    else 
    {
        echo "Your cart is empty.";
    }

    ?>

    <br><br><br>


    <!-- display thank you message for their purchase -->
    <div class="thank-you-message">
        <?php
        if ($conn->query($updateUserDetailsSQL) === TRUE) 
        {
            echo "Thank you for your order ".$_SESSION['username']."! We're thrilled to serve you. <br> Your goodies will be on their way to you soon!";
        } 
        else 
        {
            echo "Error updating user details: " . $conn->error;
        }
        ?>
    </div>


    <!-- button container for navigation -->
    <div class="button-container">
        <div id="button1">
            <a href="menu.php">
                Back to Menu
            </a>
        </div>

        <div id="button1" >
            <a href="myorder.php">
                Check Order
            </a>
        </div>

        <div id="button1" >
            <a href="logout.php">
                Log Out
            </a>
        </div>
    </div>
</div>

    <!-- close connection -->
    <?php
    $conn->close();
    ?>


    <!-- include created footer (shop details) for mycart page -->
    <?php include "footer.php" ?>
    
</body>
</html>
