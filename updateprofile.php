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
    die("Connection failed: " . $conn->connect_error);
}

// initialize error message and user_email variable
$error_message = "";
$user_email = ""; 

// retrieve the email from the database based on the current user's username
$stmt_get_email = $conn->prepare("SELECT email FROM users WHERE username = ?");
$stmt_get_email->bind_param("s", $_SESSION['username']);
$stmt_get_email->execute();
$result_get_email = $stmt_get_email->get_result();

if ($result_get_email->num_rows > 0) 
{
    // fetch the email from the result set
    $row_email = $result_get_email->fetch_assoc();
    $user_email = $row_email['email'];
} 
else 
{
    // handle the case if the email is not found
    $user_email = "";
}

// handle update profile form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_username']) && isset($_POST['update_email']) && isset($_POST['old_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) 
{
    // retrieve user inputs from the update profile form
    $update_username = $_POST['update_username'];
    $update_email = $_POST['update_email'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

// validate old password by retrieving the hashed password from the database
$stmt_get_password = $conn->prepare("SELECT u_password FROM users WHERE username = ?");
$stmt_get_password->bind_param("s", $_SESSION['username']);
$stmt_get_password->execute();
$result_get_password = $stmt_get_password->get_result();

if ($result_get_password->num_rows > 0) 
{
    // fetch the hashed password from the result set
    $row_password = $result_get_password->fetch_assoc();
    $hashed_password = $row_password['u_password'];

    // verify old password
    if (!password_verify($old_password, $hashed_password)) 
    {
        // display error message for incorrect old password
        $error_message = "Incorrect old password!";
    }
    // validate new password only if old password is correct
    elseif ($new_password !== $confirm_password) 
    {
        $error_message = "New password and confirm password do not match!";
    } 
    elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $new_password)) 
    {
        $error_message = "New password must contain at least 8 characters including at least one uppercase letter, one lowercase letter, and one number.";
    }
}

    if (empty($error_message)) 
    {
        // hash the new password for security
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

        // update the user's profile in the database
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, u_password = ? WHERE username = ?");
        $stmt->bind_param("ssss", $update_username, $update_email, $password_hash, $_SESSION['username']);
        $stmt->execute();

        // update the session with the new username
        $_SESSION['username'] = $update_username;

        // redirect the user to the update profile page with a success message
        header("Location: updateprofile.php?success=1");
        exit(); 
    }
}

// close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html>

<head>
    <title>Update Profile</title>
    <link rel="stylesheet" type="text/css" href="updateprofile.css">
    <script src="https://kit.fontawesome.com/0b424c94e9.js" crossorigin="anonymous"></script>
</head>

<body>


<!-- top nav -->
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


<!-- update profile form -->
<div class="form-container">
    <form id="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <h1 id="header">Update Profile</h1>

        <!-- display success message if redirected with success parameter -->
        <?php if(isset($_GET['success']) && $_GET['success'] == 1): ?>
            <p style="color: green;">Profile Updated Successfully!</p>
            <br>
        <?php endif; ?>

        <!-- display error message if there is one -->
        <?php if(isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
            <br>
        <?php endif; ?>

        <!-- update profile form fields -->
        <label for="update_username">
            Username:
        </label><br>
        <input type="text" id="update_username" name="update_username" value="<?php echo $_SESSION['username']; ?>" required><br><br>

        <label for="update_email">
            Email:
        </label><br>
        <input type="email" id="update_email" name="update_email" value="<?php echo $user_email; ?>" required><br><br>

        <label for="old_password">
            Old Password:
        </label><br>
        <input type="password" id="old_password" name="old_password" required><br><br>

        <label for="new_password">
            New Password:
        </label><br>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <label for="confirm_password">
            Confirm New Password:
        </label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>

        <!-- 'Update' button for the update profile form -->
        <button type="submit">
            Update
        </button>
    </form>
</div>

<!-- include created footer (shop details) for mycart page -->
<?php include "footer.php" ?>

</body>
</html>
