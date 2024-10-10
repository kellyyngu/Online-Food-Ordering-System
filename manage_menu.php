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
    die("Connection failed: " . $conn->connect_error);
}

function showPopupMessage() 
{
    if(isset($_SESSION['popup_message'])) 
    {
        echo "<div id='popupMessage' class='popup-message'>";
        echo "<span id='popupText'>".$_SESSION['popup_message']."</span>";
        echo "</div>";
        echo "<script>";
        echo "document.getElementById('popupMessage').style.display = 'block';";
        echo "setTimeout(function() {";
        echo "document.getElementById('popupMessage').style.display = 'none';";
        echo "}, 1000);";
        echo "</script>";
        unset($_SESSION['popup_message']);
    }
}

// handle addition of new menu item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_item'])) 
{
    $category = $_POST['category'];
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    // insert new menu item into the database
    $sql_add_item = "INSERT INTO menu (category, item_name, description, price, image_url) 
                     VALUES ('$category', '$item_name', '$description', '$price', '$image_url')";
    if ($conn -> query($sql_add_item) === TRUE) 
    {

    } 
    else 
    {
 
    }
}

// handle removal of menu item
if (isset($_GET['remove_item']) && is_numeric($_GET['remove_item'])) 
{
    $item_id_to_remove = $_GET['remove_item'];
    $sql_remove_item = "DELETE FROM menu WHERE id='$item_id_to_remove'";
    if ($conn->query($sql_remove_item) === TRUE) 
    {

    } 
    else 
    {

    }
}

// handle update of availability
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_availability'])) 
{
    $item_id = $_POST['item_id'];
    $availability = $_POST['availability'];

    // update availability in the database
    $sql_update_availability = "UPDATE menu SET availability='$availability' WHERE id='$item_id'";
    if ($conn->query($sql_update_availability) === TRUE) 
    {
        // display a popup message after updating availability
        $_SESSION['popup_message'] = 'Availability Updated.';
    } 
    else 
    {
        // display a popup message if there's error updating availability
        $_SESSION['popup_message'] = 'Error updating availability: ' . $conn->error;    
    }
}

// Fetch all menu items
$sql_menu_items = "SELECT * FROM menu";
$result_menu_items = $conn->query($sql_menu_items);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Menu</title>
    <link rel="stylesheet" type="text/css" href="manage_menu.css">
    <script src="https://kit.fontawesome.com/0b424c94e9.js" crossorigin="anonymous"></script>
</head>

<body>
    <!-- top navigation bar for the manage menu page -->
    <header>
        <img src="logo.png" height="50px">
        <h3 class="shopname">bakemyday<span>.</span></h3>

        <div class="icons">
            <a href="admin_dashboard.php"><i class="fa-solid fa-circle-left"></i></a>
            <span class="backd"> Back To Dashboard</span>
        </div>
    </header>

    <!-- manage menu page's header title -->
    <h1>Manage Menu</h1>

    <!-- form for admin to add new item -->
    <h2 style="text-align: center;">Add New Item</h2>
    <div class="form-container">
        <form method="post">
            <label for="category">
                Category:
            </label>
            <select id="category" name="category" required>
            <option value="Cakes">Cakes</option>
            <option value="Pastries">Pastries</option>
            <option value="Beverages">Beverages</option>
            <option value="Pancakes">Pancakes</option>
            <option value="Breads">Breads</option>
            <option value="Smoothie Bowls">Smoothie Bowls</option>
        </select>
        <br><br>

            <label for="item_name">
                Item Name:
            </label>
            <input type="text" id="item_name" name="item_name" required><br><br>

            <label for="description">
                Description:
            </label>
            <textarea id="description" name="description" required></textarea><br><br>

            <label for="price">
                Price:
            </label>
            <input type="number" id="price" name="price" min="0" step="0.01" required><br><br>

            <label for="image_url">
                Image URL:
            </label>
            <input type="text" id="image_url" name="image_url"><br><br>

            <button id="add" type="submit" name="add_item">Add Item</button>
        </form>
    </div>

    <!-- table to display menu items -->
    <h2>Menu Items</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Item Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Availability</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>

        <!-- loop through each menu item and display its details -->
        <?php while ($row = $result_menu_items->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['category']; ?></td>
                <td><?php echo $row['item_name']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td>$<?php echo $row['price']; ?></td>
                <td>
                    <!-- form for selecting availability -->
                    <form method="post">
                        <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                        <select name="availability">
                            <option value="available" <?php if ($row['availability'] === 'available') echo 'selected'; ?>>
                                Available
                            </option>
                            <option value="unavailable" <?php if ($row['availability'] === 'unavailable') echo 'selected'; ?>>
                                Unavailable
                            </option>
                        </select>

                        <br>
                        <button type="submit" name="update_availability">
                            Update
                        </button>
                    </form>
                </td>

                <td><img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['item_name']; ?>" style="max-width: 100px;"></td>  <!-- display item image -->
                <td id="action">
                    <a href="edit_menu.php?id=<?php echo $row['id']; ?>">
                    Edit
                    </a>
                    <a href="manage_menu.php?remove_item=<?php echo $row['id']; ?>">
                    Remove
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

        <!-- Call to show and immediately clear the popup message -->
        <?php showPopupMessage(); ?>
        <br><br>
</body>
</html>
