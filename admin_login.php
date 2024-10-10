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
    die("Connection failed: " . $conn -> connect_error);
}

// handle user login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) 
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    // fetch admin credentials from database
    $stmt = $conn->prepare("SELECT id, username, a_password FROM admins WHERE username = ?");
    $stmt -> bind_param("s", $username);
    $stmt -> execute();
    $result = $stmt -> get_result();

    // check if a user with the provided username exists
    if ($result -> num_rows == 1) 
    {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['a_password'])) 
        {
            // wtore username in session
            $_SESSION['admin_username'] = $username;

            // redirect the admin to the dashboard page after a delay
            header("Location: admin_dashboard.php");
            exit(); // terminate script execution
        } 
        else 
        {
            // set the pop-up message for wrong password
            $popup_message = "Wrong Password! Please Try Again.";
        }
    } 
    else 
    {
        // set the pop-up message for invalid username
        $popup_message = "Invalid Username.";
    }
}

// close connection 
$conn->close();
?>


<!DOCTYPE html>
<html>

<head>
    <title>Admin Login</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>

<body>
    <!-- top nav bar for admin login page -->
    <header>
        <img src="logo.png" height="50px">
        <a class="shopname">bakemyday<span>.</span></a>
    </header>

    <div class="form-container">
        <!-- header for admin login page -->
        <h1 id="header">Admin Login</h1>

        <!-- admin login form starts here -->
        <form id="form" action="admin_login.php" method="post">
            <label for="username">
                Username:
            </label>
            <br>
            <input type="text" id="username" name="username" placeholder="Enter Your Username" required>
            
            <br><br>

            <label for="password">
                Password:
            </label>
            <br>
            <input type="password" id="password" name="password" required>
            
            <br><br>

            <!-- display error message if there is one -->
            <?php if(isset($popup_message)): ?>
                <p style="color: red;"><?php echo $popup_message; ?></p>
                <br> 
            <?php endif; ?>

            <!-- 'login' button for the login form -->
            <button type="submit">
                Login
            </button>
        </form>

    </div>

</body>
</html>
