<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$database = "bakemyday";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}                       

// Function to display menu items by category
function displayMenuByCategory($category)
{
    global $conn;

    $sql = "SELECT * FROM menu WHERE  category='$category'";
    $result = $conn->query($sql);

    echo "<div class='menu-itemm'>";
    echo "<div class='box-container'>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='menu-item'>";

            echo "<div class='product'>";
            echo "<img src='" . $row['image_url'] . "' alt='" . $row['item_name'] . "'>";
            echo "</div>"; //close .product

            echo "<div class='item-details'>";
            echo
            "<h3 class='item-name'>"
                . $row['item_name'] .
                "</h3>";
            echo
            "<p class='price'>
            Price: $"
                . $row['price'] .
                "</p>";
            echo
            "<p class='description'>"
                . $row['description'] .
                "</p>";
            echo "</div>"; //close .item-details

            echo
            "<p class='availability'>
            Availability: "
                . $row['availability'] .
                "</p>";  // Display availability

            echo "<div class='cart-controls'>";

            // Check if item availability is 'available' or 'unavailable'
            if ($row['availability'] == 'available') {
                // If available, display the add to cart controls
                echo "<div class='quantity-controls'>";
                echo
                "<button class='decrement'>
                    -
                </button>";

                echo
                "<input type='text' class='quantity' value='0'>";

                echo
                "<button class='increment'>
                    +
                </button>";
                echo "</div>"; //close quantity-controls

                echo
                "<button class='add-to-cart'>
                    Add to Cart
                </button><br><br><br><br>";

                
            } else {
                // If unavailable, display a disabled add to cart button
                echo
                "<button class='add-to-cart' disabled>
                Add to Cart (Unavailable)
                </button>
                <br><br><br><br>";
            }
            echo "</div>"; //close .cart-controls

            echo "</div>"; //close .box
        }
    } 
    else 
    {
        echo "No menu items found in this category";
    }
    echo "</div>"; //close .box-container
    echo "</div>"; //close .menu-item
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Menu</title>

    <link rel="stylesheet" href="menu2.css">
    <script src="menu.js" defer></script>
    <script src="https://kit.fontawesome.com/0b424c94e9.js" crossorigin="anonymous"></script>

    <style>
        .popup-message 
        {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        #closePopup 
        {
            display: block;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <header>
        <img src="logo.png" height="50px">
        <a href="home.php" class="shopname">bakemyday<span>.</span></a>

        <div class="icons">
            <a href="home.php"><i class="fa-solid fa-house"></i></a></i></a>
            <span class="home">Home</span>
            <a href="menu2.php"><i class="fa-solid fa-book-open"></i></a>
            <span class="menu">Menu</span>
            <a href="order.php"><i class="fa-solid fa-user"></i></a>
            <span class="order-here">Order Here</span>
        </div>
    </header>

    <section>
        <h1>Menu</h1>

        <!-- Filter buttons -->
        <div class="filter-container">
            <div class="filter-buttons">
                <button class="active" data-name="all">All</button>
                <button data-name="cakes">Cakes</button>
                <button data-name="pastries">Pastries</button>
                <button data-name="breads">Breads</button>
                <button data-name="pancakes">Pancakes</button>
                <button data-name="smoothie_bowls">Smoothie Bowls</button>
                <button data-name="beverages">Beverages</button>
            </div>
        </div>

        <!-- Message area -->
        <div class="menu-categories">

            <div data-name="cakes">
                <h2>Cakes</h2>
                <?php displayMenuByCategory("Cakes"); ?>
            </div>

            <div data-name="pastries">
                <h2>Pastries</h2>
                <?php displayMenuByCategory("Pastries"); ?>
            </div>

            <div data-name="breads">
                <h2>Breads</h2>
                <?php displayMenuByCategory("Breads"); ?>
            </div>

            <div data-name="pancakes">
                <h2>Pancakes</h2>
                <?php displayMenuByCategory("Pancakes"); ?>
            </div>

            <div data-name="smoothie_bowls">
                <h2>Smoothie Bowls</h2>
                <?php displayMenuByCategory("Smoothie Bowls"); ?>
            </div>

            <div data-name="beverages">
                <h2>Beverages</h2>
                <?php displayMenuByCategory("Beverages"); ?>
            </div>

        </div>

        <div id="popupMessage" class="popup-message" style="display: none;">
            <span id="popupText"></span>
        </div>


        <script>
            const decrementButtons = document.querySelectorAll('.decrement');
            const incrementButtons = document.querySelectorAll('.increment');

            decrementButtons.forEach(button =>
                // iterates over each button in the decrementButtons array using forEach() method
                {
                    button.addEventListener('click', function()
                        // when any of the decrement buttons is clicked, function inside the event listener will be executed
                        {
                            const quantityInput = this.nextElementSibling;
                            let quantity = parseInt(quantityInput.value) || 0; // extracts the current value of the quantity input field and converts it to an integer using parseInt()
                            quantity = Math.max(quantity - 1, 0); // decrements the quantity variable by 1 and prevent negative quantities by using Math.max()
                            quantityInput.value = quantity; // updates the value of the quantity input field with the new quantity value obtained in the previous step
                        });
                });

            incrementButtons.forEach(button => 
            {
                button.addEventListener('click', function() 
                {
                    const quantityInput = this.previousElementSibling;
                    let quantity = parseInt(quantityInput.value) || 0; // Handle non-numeric values
                    quantity++;
                    quantityInput.value = quantity;
                });
            });


            // Function to show the pop-up message with the given text
            function showPopupMessage(text) 
            {
                const popupMessage = document.getElementById('popupMessage');
                const popupText = document.getElementById('popupText');
                popupText.textContent = text;
                popupMessage.style.display = 'block';

                // Automatically hide the popup after 1 second
                setTimeout(function() 
                {
                    popupMessage.style.display = 'none';
                }, 2000);
            }

            document.querySelectorAll('.add-to-cart').forEach(button => 
            {
                button.addEventListener('click', function(event) {
                event.preventDefault(); // prevents default form submission behavior when 'add-to-cart' button is clicked
                const menuItem = this.closest('.menu-item');
                const quantityInput = menuItem.querySelector('.quantity'); // Get the quantity input field
                const quantity = parseInt(quantityInput.value); // Get the quantity value

                // Check if quantity is greater than 0 before adding to the cart
                if (quantity > 0) 
                {
                    // If the user is not logged in, display a message
                    showPopupMessage('Please Register Or Login To Order.');
                    quantityInput.value = 0;
                } 
                else
                {
                    // If the user is not logged in and no quantity selected, display the same message
                    showPopupMessage('Please Register Or Login To Order.');
                }
            });
        });
        </script>
    </section>

    <?php include "footer.php" ?>

</body>

</html>


<?php
$conn->close();
?>