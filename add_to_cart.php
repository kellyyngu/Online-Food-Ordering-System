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

// check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    // retrieve item data from the form
    $itemName = $_POST["item_name"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $username = $_SESSION['username']; // retrieve username from session

    // check if the item is already in the cart
    $checkSql = "SELECT * FROM cart WHERE item_name='$itemName' AND username='$username'";
    $checkResult = $conn->query($checkSql);
    if ($checkResult->num_rows > 0) 
    {
        // update the quantity of the existing item in the cart
        $row = $checkResult->fetch_assoc();
        $currentQuantity = $row["quantity"];
        $newQuantity = $currentQuantity + $quantity;
        $updateSql = "UPDATE cart SET quantity='$newQuantity' WHERE item_name='$itemName' AND username='$username'";
        if ($conn->query($updateSql) === TRUE) 
        {
            echo "Quantity updated successfully";
        } 
        else 
        {
            echo "Error updating quantity: " . $conn->error;
        }
    } 
    else 
    {
        // add the item to the cart table
        $insertSql = "INSERT INTO cart (username, item_name, price, quantity) VALUES ('$username', '$itemName', '$price', '$quantity')";
        if ($conn->query($insertSql) === TRUE) 
        {
            echo "Item added successfully";
        } 
        else 
        {
            echo "Error adding item to cart: " . $conn->error;
        }
    }
}

// close connection
$conn->close();
?>
