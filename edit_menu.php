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

// check if the item ID is provided in the URL
if (!isset($_GET['id'])) 
{
    // redirect back to manage_menu.php or show an error message
    header("Location: manage_menu.php");
    exit;
}

// fetch the menu item details from database using item ID
$item_id = $_GET['id'];
$sql_fetch_item = "SELECT * FROM menu WHERE id='$item_id'";
$result_fetch_item = $conn->query($sql_fetch_item);
$row = $result_fetch_item->fetch_assoc();

// handle update of the menu item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_item'])) 
{
    // retrieve updated item details from the form
    $itemId = $_POST['edit_item_id'];
    $itemName = $_POST['edit_item_name'];
    $description = $_POST['edit_description'];
    $price = $_POST['edit_price'];
    $imageUrl = $_POST['edit_image_url'];

    // update the menu item in database
    $sql_update_item = "UPDATE menu SET item_name='$itemName', description='$description', price='$price', image_url='$imageUrl' WHERE id='$itemId'";
    if ($conn->query($sql_update_item) === TRUE) 
    {
        // redirect back to manage_menu.php after updating
        echo "<script>window.location.href = 'manage_menu.php';</script>";
    } 
    else 
    {
        echo "<script>alert('Error updating item: " . $conn->error . "');</script>";
    }
}

// close database connection
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Edit Menu Item</title>
    <link rel="stylesheet" type="text/css" href="edit_menu.css">
</head>

<body>
    <!-- header title -->
    <h1>Edit Menu Item</h1>    
    
    <!-- form for admin to update/edit menu item -->
    <div class="form-container">
    <form method="post">
        <!-- hidden field to store item ID -->
        <input type="hidden" name="edit_item_id" value="<?php echo $row['id']; ?>">

        <label for="edit_item_name">
            Item Name:
        </label>
        <input type="text" id="edit_item_name" name="edit_item_name" value="<?php echo $row['item_name']; ?>" required><br><br>

        <label for="edit_description">
            Description:
        </label>
        <textarea id="edit_description" name="edit_description" required><?php echo $row['description']; ?></textarea><br><br>

        <label for="edit_price">
            Price:
        </label>
        <input type="number" id="edit_price" name="edit_price" min="0" step="0.01" value="<?php echo $row['price']; ?>" required><br><br>

        <label for="edit_image_url">
            Image URL:
        </label>
        <input type="text" id="edit_image_url" name="edit_image_url" value="<?php echo $row['image_url']; ?>"><br><br>

        <button type="submit" name="update_item">
            Update
        </button>
    </form>
    </div>

    <!-- back to menage menu button -->
    <div class="back">
    <a href="manage_menu.php" style="color: white;">
        Back to Manage Menu
    </a>
</div>
</body>
</html>
