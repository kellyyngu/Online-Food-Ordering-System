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

// handle quantity updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['item_id']) && isset($_POST['quantity'])) 
{
    $itemId = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    // update quantity in the cart
    if ($quantity == 0) 
    {
        // if quantity is zero, remove item from cart
        $deleteSql = "DELETE FROM cart WHERE id='$itemId'";

        // if user wish to remove an item
        if ($conn->query($deleteSql) === TRUE) 
        {
            // display a popup message after successfully removing item from cart
            showPopupMessage("Item Removed From Cart.");
        } 
        else 
        {
             // display a popup message if item cannot be removed from cart
            showPopupMessage("Error removing item from cart: " . $conn->error);
        }
    } 
    else 
    {
        // update quantity in database
        $updateSql = "UPDATE cart SET quantity='$quantity' WHERE id='$itemId'";

        if ($conn->query($updateSql) === TRUE) 
        {
            // display a popup message after updating the item's quantity
            showPopupMessage("Quantity Updated.");
        } 
        else 
        {
            // display a popup message if there's error updating item's quantity
            showPopupMessage("Error Updating Quantity: " . $conn->error);
        }
    }
}

// retrieve cart items for the logged-in user
$username = $_SESSION['username'];
$sql = "SELECT * FROM cart WHERE username='$username'";
$result = $conn->query($sql);

// function to display cart items
function displayCartItems($conn) 
{
    $username = $_SESSION['username']; // retrieve the username from the session
    $sql = "SELECT * FROM cart WHERE username ='$username'"; // SQL query to retrieve cart items for the logged-in user
    $result = $conn->query($sql); // execute the SQL query

    // check if there are cart items for the user
    if ($result->num_rows > 0) 
    {
        // begin displaying the cart items table
        echo "<table>";
        echo "<tr>
        <th>Item Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Subtotal</th>
        </tr>";

        // initialize variables to calculate total price and total items
        $totalPrice = 0;
        $totalItems = 0;

        // loop through each cart item retrieved from the database
        while($row = $result->fetch_assoc()) 
        {
            $subtotal = $row['price'] * $row['quantity']; // calculate the subtotal for the current item
            // update total price and total items
            $totalPrice = $totalPrice + $subtotal;  
            $totalItems = $totalItems + $row['quantity']; 

            // display a row for each cart item
            echo "<tr>";
            echo "<td>" . $row['item_name'] . "</td>";
            echo "<td>$" . $row['price'] . "</td>";

            // display quantity column and update button
            echo "<td>";
            echo "<form id='update_form_" . $row['id'] . "' class='update-form' method='post'>";
            echo "<input type='hidden' name='item_id' value='" . $row['id'] . "'>";
            echo "<input type='number' name='quantity' value='" . $row['quantity'] . "' min='0'>";
            echo "<button type='button' class='update-button' data-item-id='".$row['id']."'>update</button>";
            echo "</form>";
            echo "</td>";
                
            // display subtotal for the current item
            echo "<td>$" . $subtotal . "</td>";

            echo "</tr>"; 
        }

        // display the total amount to be paid at the bottom of the table
        echo 
        "<tr style='color: black; font-weight:bold;' class='total'>
        <td colspan='3'>
        Total
        </td>
        <td style='color: black;'>
        $".$totalPrice. 
        "</td></tr>";
        echo 
        "</table>";
        echo 
        "<br><br>";
        echo 
        "<b style='color: white;'>Total Items: " 
        .$totalItems. 
        "</b>";
    } 
    else 
    {
        // display message when the cart is empty
        echo 
        "<br><br><br><p style='font-weight:bold; color:white;font-size:25px;'>
            YOUR CART IS EMPTY.
        </p>";
    }
}

    // function to show the pop-up message with the given text
function showPopupMessage($text) 
{
    echo "<div id='popupMessage' class='popup-message'>";
    echo "<span id='popupText'>$text</span>";
    echo "</div>";
    echo "<script>";
    echo "document.getElementById('popupMessage').style.display = 'block';"; // Display the popup
    echo "setTimeout(function() {";
    echo "document.getElementById('popupMessage').style.display = 'none';";
    echo "}, 2000);"; // Automatically hide the popup after 2 seconds
    echo "</script>";
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>my cart</title>
    <link rel="stylesheet" type="text/css" href="mycart.css">
    <script src="https://kit.fontawesome.com/0b424c94e9.js" crossorigin="anonymous"></script>
</head>

<body>

    <!-- top nav bar for mycart page -->
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

    <!-- header for mycart page -->
    <h1 id="header">MY CART</h1>

    <br><br><br>

    <!-- welcome message -->
    <p style="color:white; font-weight:bold; font-size:25px;">
        Welcome, <?php echo $_SESSION['username']; ?>!
    </p>

    <!-- display cart items -->
    <?php
    displayCartItems($conn);
    ?>

    <br><br>

    <!-- function call to show the popup message  -->
    <?php
    if(isset($_SESSION['popup_message'])) 
    {
        showPopupMessage($_SESSION['popup_message']);
        unset($_SESSION['popup_message']);
    }
    ?>

    <?php
    // check if there are items in the cart
    if ($result->num_rows > 0) 
    {
        // display the "check out" button only if the cart is not empty
        echo 
        "<div id='button1'>
        <a href='checkout.php'>
            Check Out
        </a>
        </div>";
    }
    ?>

    <br><br>

    <script>
        // add event listeners to update buttons
        document.querySelectorAll('.update-button').forEach(button => 
        {
            button.addEventListener('click', function() 
            {
                const itemId = this.getAttribute('data-item-id');
                const form = document.getElementById('update_form_' + itemId);
                form.submit();
            });
        });
    </script>


    <!-- include created footer (shop details) for mycart page -->
    <?php include "footer.php" ?>

</body>
</html>
  