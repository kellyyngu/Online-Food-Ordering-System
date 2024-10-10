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
?>



<!DOCTYPE html>
<html>

<head>
    <title>Checkout</title>
    <link rel="stylesheet" type="text/css" href="checkout.css">
    <script src="https://kit.fontawesome.com/0b424c94e9.js" crossorigin="anonymous"></script>
</head>

<body>

    <div id="background"></div>

    <!-- top nav bar for checkout page -->
    <header>
        <img src="logo.png" height="50px">
        <a class="shopname">bakemyday<span>.</span></a>

        <div class="icons">
            <a href="menu.php"><i class="fa-solid fa-book-open"></i></a>
            <span class="menu">Menu</span>
            <a href="mycart.php"><i class="fa-solid fa-cart-shopping"></i></a>
            <span class="cart">Cart</span>
            <a href="myorder.php"><i class="fa-solid fa-receipt"></i></a>
            <span class="myorder">Order History</span>
            <a href="home.php"><i class="fa-solid fa-right-from-bracket"></i></a>
            <span class="logout">Log Out</span>
        </div>
    </header>


    <!-- header title for checkout page -->
    <h1 id="header">Checkout Page</h1>


    <!-- display user's order summary -->
    <h2 id="header">Order Summary</h2>
    <table>
        <tr>
            <th>Item</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Subtotal</th>
        </tr>
        

        <!-- display cart items dynamically using PHP -->
        <?php
            $totalPrice = 0;
            $totalItems = 0;

            $username = $_SESSION['username'];
            $sql = "SELECT * FROM cart WHERE username ='$username'";
            $result = $conn -> query($sql);

            if ($result->num_rows > 0) 
            {
                while($row = $result -> fetch_assoc()) 
                {
                    $subtotal = $row['price'] * $row['quantity'];
                    $totalPrice = $totalPrice + $subtotal;
                    $totalItems = $totalItems + $row['quantity'];
                    echo "<tr>";
                    echo "<td>".$row['item_name']."</td>";
                    echo "<td>".$row['quantity']."</td>";
                    echo "<td>$".$row['price']."</td>";
                    echo "<td>$".$subtotal."</td>";
                    echo "</tr>";
                }
                echo 
                "<tr class='total' style='color:white; font-weight:bold'>
                <td colspan='3'><p style='color:black;'>Total</p></td>
                <td><p style='color:black;'>$".$totalPrice."</p></td>
                </tr>";
            } 
            else 
            {
                echo 
                "<tr>
                <td colspan='4'>Your cart is empty</td>
                </tr>";
            }
        ?>
    </table>


    <!-- customer details section -->
    <h2 id="header">Provide Your Details</h2>
    <!-- customer details form -->
    <div class="checkout-container">
    <form action="confirm_order.php" method="post" onsubmit="return validateForm()">
            <label for="name">
                Name:
            </label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required>
            
            <br><br>
        
            <label for="phone">
                Phone Number:
            </label>
            <input type="text" id="phone" name="phone" maxlength="12" placeholder="XXX-XXX-XXXX"  required>
            
            <br><br>
        
            <label for="address">
                Address:
            </label>
            <textarea id="address" name="address" placeholder="Enter your address"  required></textarea>
            
            <br><br>
    </div>
        
    <!-- payment methods section -->
    <h2 id="header">Select Payment Method</h2>
    <div class="checkout-container2">
        <input type="radio" id="cash_on_delivery" name="payment_method" value="cash_on_delivery" checked>
        <label for="cash_on_delivery">
            Cash on Delivery
        </label>

        <br><br>
        <input type="radio" id="card" name="payment_method" value="card">
        <label for="card">
            Credit/Debit Card
        </label>
        <br>
    </div>


    <br><br>


    <!-- credit/debit Card Details Section -->
    <div id="card_details" style="display: none;">
        <h2 id="cd_header">
            Enter Card Details
        </h2>

        <br><br>

        <label for="card_number">
            Card Number:
        </label>
        <input type="text" id="card_number" name="card_number" placeholder="XXXX XXXX XXXX XXXX" maxlength="19">

        <br><br>

        <label for="expiry_date">
            Expiry Date:
        </label>
        <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY">

        <br><br>

        <label for="cvv">
            CVV:
        </label>
        <input type="password" id="cvv" name="cvv" placeholder="Enter CVV" maxlength="3">
        <br><br>
    </div>

    <!-- JavaScript function to validate form -->
    <script>
    function validateForm() 
    {
        // get form elements by id
        var name = document.getElementById('name').value;
        var phone = document.getElementById('phone').value;
        var address = document.getElementById('address').value;
        var cardNumber = document.getElementById('card_number').value;
        var expiryDate = document.getElementById('expiry_date').value;
        var cvv = document.getElementById('cvv').value;
        var paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

        // check if any of the fields are empty
        if (name === '' || phone === '' || address === '') 
        {
            showMessage('Please fill in all the details.');
            return false; // prevent form submission
        }

        // check if phone number is valid
        var phonePattern = /^\d{3}-\d{3}-\d{4}$/; // regular expression for XXX-XXX-XXXX phone number format
        if (!phone.match(phonePattern)) 
        {
            showMessage('Please enter a valid phone number in the format XXX-XXX-XXXX!');
            return false; // prevent form submission
        }

        // validate card details only if payment method is "Credit/Debit Card"
        if (paymentMethod === 'card') 
        {
            // check if card number format is valid
            var cardNumberPattern = /^\d{4} \d{4} \d{4} \d{4}$/; // regular expression for "XXXX XXXX XXXX XXXX"
            if (!cardNumber.match(cardNumberPattern)) 
            {
                showMessage('Please enter a valid card number in the format XXXX XXXX XXXX XXXX');
                return false; // prevent form submission
            }

            // check if expiry date format is valid
            var expiryDatePattern = /^\d{2}\/\d{2}$/; 
            if (!expiryDate.match(expiryDatePattern)) 
            {
                showMessage('Please enter a valid expiry date in the format MM/YY');
                return false; // Prevent form submission
            }

            // extract month and year from the expiry date
            var month = parseInt(expiryDate.split('/')[0]);
            var year = parseInt(expiryDate.split('/')[1]);

            // check if month is between 1 and 12
            if (isNaN(month) || month < 1 || month > 12) 
            {
                showMessage('Please enter a valid month (01-12).');
                return false; // prevent form submission
            }

            // check if year is between 2024 and 2029
            if (isNaN(year) || year < 24 || year > 29) 
            {
                showMessage('Please enter a valid year (between 24 and 29).');
                return false; // prevent form submission
            }

            // check if CVV is exactly 3 digits
            var cvvPattern = /^\d{3}$/; // regular expression for exactly 3 digits
            if (!cvv.match(cvvPattern)) 
            {
                showMessage('CVV must be 3 digits.');
                return false; // prevent form submission
            }

             // hash card number and CVV
            var hashedCardNumber = CryptoJS.SHA256(cardNumber).toString();
            var hashedCVV = CryptoJS.SHA256(cvv).toString();

            // replace original values with hashed values
            document.getElementById('card_number').value = hashedCardNumber;
            document.getElementById('cvv').value = hashedCVV;
        }
        return true; // allow form submission
    }

    function showMessage(message) 
    {
        document.getElementById("popupMessage").innerText = message;
        document.getElementById("popup").style.display = "block";

        // set timeout to hide the popup after 2 seconds
        setTimeout(function() {
        document.getElementById("popup").style.display = "none";
    }, 3000);
}

    function hideMessage() 
    {
        document.getElementById("popup").style.display = "none";
    }

    function toggleCardDetails() 
    {
        var cardDetails = document.getElementById("card_details");
        var cardPayment = document.getElementById("card");

        if (cardPayment.checked) 
        {
            cardDetails.style.display = "block";
        } 
        else 
        {
            cardDetails.style.display = "none";
            hideMessage(); // hide any error messages related to card details
        }
    }

    document.getElementById("card").addEventListener("change", toggleCardDetails);
    document.getElementById("cash_on_delivery").addEventListener("change", toggleCardDetails);
    toggleCardDetails(); 
</script>

    
    <br><br>

    <!-- confirm order button -->
    <div id="confirm">
        <button type="submit">
            Place Order
        </button>
    </div>

    </form>
    
    <br><br>

   
    <!-- popup message -->
    <div id="popup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: white; padding: 20px; border: 2px solid black;">
        <p id="popupMessage" style="color: black; font-weight: bold;"></p>
    </div>

    <!-- include created footer (shop details) for checkout page -->
    <?php include "footer.php" ?>

</body>
</html>
