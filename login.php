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

// initialize error message variable
$error_message = "";

// handle user login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) // if the HTTP request method is POST and username and password are set in the POST data
{
    // retrieve username and password from the login form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // prepare and execute a SELECT query to fetch user data based on the provided username
    $stmt = $conn->prepare("SELECT id, username, u_password FROM users WHERE username = ?");
    $stmt -> bind_param("s", $username);
    $stmt -> execute();
    $result = $stmt -> get_result();

    // check if a user with the provided username exists
    if ($result -> num_rows == 1)  // if a user with the provided username exists
    {
        // fetch the user data
        $row = $result -> fetch_assoc();

        // verify the provided password against the hashed password stored in the database
        if (password_verify($password, $row['u_password'])) 
        {
            // if the password is correct, store the username in the session
            $_SESSION['username'] = $username;

           // redirect the user to the menu page
           header("Location: menu.php");
           exit(); // terminate script execution
        } 
        else 
        {
            // if the password is incorrect, set an error message
            $error_message = "Wrong Password! Please Try Again.";
        }
    } 
    else 
    {
        // if the username is invalid, set an error message
        $error_message = "Invalid Username.";
    }
}

// close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
    <script src="https://kit.fontawesome.com/0b424c94e9.js" crossorigin="anonymous"></script>
</head>

<body>

<!-- top navigation bar for the login page -->
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

<!-- login form section -->
<div class="form-container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <!-- login page header -->
        <h1 id="header">Login to your account</h1>

        <!-- labels and input fields for username and password -->
        <label for="username">
            Username:
        </label>
        <br>
        <input type="text" name="username"  placeholder="Enter your username"  required>
    
        <br><br>

        <label for="password">
            Password:
        </label>
        <br>
        <input type="password"  name="password"  required>
    
        <br><br>

        <!-- display error message if there is one -->
        <?php if(isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
            <br> 
        <?php endif; ?>

        <!-- 'login' button for the login form -->
        <button type="submit">
            Login
        </button>
    </form>
</div>

<br><br>


<!-- include footer (shop details) in login page -->
<?php include "footer.php" ?>

</body>
</html>
