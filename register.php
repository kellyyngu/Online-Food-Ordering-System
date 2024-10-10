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

// initialize error message variable
$error_message = "";

// handle user registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reg_username']) && isset($_POST['email']) && isset($_POST['reg_password']) && isset($_POST['confirm_password'])) 
{
    // retrieve user inputs from the registration form
    $reg_username = $_POST['reg_username']; // get username from the form data
    $email = $_POST['email']; // get user's email from the form data
    $reg_password = $_POST['reg_password']; // get user's password from the form data
    $confirm_password = $_POST['confirm_password']; // get user's confirmed password from the form data

    // check if the username already exists in the database
    $stmt_check_username = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt_check_username->bind_param("s", $reg_username);
    $stmt_check_username->execute();
    $result_check_username = $stmt_check_username->get_result();

    if ($result_check_username->num_rows > 0) 
    {
        // set error message if username already exists
        $error_message = "Username Already Exists!";
    } 
    else 
    {
        // check if the email already exists in the database
        $stmt_check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt_check_email->bind_param("s", $email);
        $stmt_check_email->execute();
        $result_check_email = $stmt_check_email->get_result();

        if ($result_check_email->num_rows > 0) 
        {
            // set error message if email already exists
            $error_message = "Email Already Exists!";
        } 
        else 
        {
            // check if passwords match
            if ($reg_password !== $confirm_password) 
            {
                $error_message = "Passwords Do Not Match!";
            } 
            elseif (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/", $reg_password)) 
            {
                // check if the password meets the criteria: must include capital letters, small letters, and numbers
                $error_message = "Password must contain at least 8 characters including at least one uppercase letter, one lowercase letter, and one number.";
            } 
            else 
            {
                // hash the password for security
                $password_hash = password_hash($reg_password, PASSWORD_DEFAULT);

                // insert the user into database in order to become our shop member
                $stmt = $conn->prepare("INSERT INTO users (username, email, u_password ) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $reg_username, $email, $password_hash);
                $stmt->execute();

                // store the username in the session for future use
                $_SESSION['username'] = $reg_username;

                // redirect the user to the menu page
                header("Location: menu.php");
                exit(); // terminate script execution
            }
        }
    }
}

// close the database connection
$conn->close();
?>



<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <link rel="stylesheet" href="register.css">
    <script src="https://kit.fontawesome.com/0b424c94e9.js" crossorigin="anonymous"></script>
</head>

<body>

<!-- top navigation bar for the registration page -->
<header>
    <img src="logo.png" height="50px">
    <a href="home.php" class="shopname">bakemyday<span>.</span></a>

    <div class="icons">
      <a href="home.php"><i class="fa-solid fa-house"></i></a>
      <span class="home">Home</span>
      <a href="menu2.php"><i class="fa-solid fa-book-open"></i></a>
      <span class="menu">Menu</span>
      <a href="order.php"><i class="fa-solid fa-user"></i></a>
      <span class="order-here">Order Here</span>
    </div>
</header>
 

<!-- registration form section -->
<div class="form-container">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validateForm()">
        <!-- registration form header -->
        <h1 id="header">Register</h1>

        <!-- registration form fields -->
        <label for="reg_username">
            Username:
        </label><br>
        <input type="text" name="reg_username" required><br><br>

        <label for="email">
            Email:
        </label><br>
        <input type="email" name="email" required><br>
        <span id="emailError" style="color: red;"></span><br><br>

        <label for="reg_password">
            Password:
        </label><br>
        <input type="password" name="reg_password" required><br><br>

        <label for="confirm_password">
            Confirm Password:
        </label><br>
        <input type="password" name="confirm_password" required><br><br>


        <!-- display error message if there is one -->
        <?php if(isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
            <br> 
        <?php endif; ?>

        <!-- 'register'' button for the registration form -->
        <button type="submit">
            Register
        </button>
    </form>
</div>

<!-- include footer (shop details) in registration page -->
<?php include "footer.php" ?>

</body>
</html>
