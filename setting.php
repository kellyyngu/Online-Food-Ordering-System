<?php
// start or resume a session
session_start();

// define database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "bakemyday"; 

// create a new database connection
$conn = new mysqli($servername, $username, $password, $database);

// check if database connection failed
if ($conn->connect_error) 
{
    // terminate the script and output error message
    die("Connection failed: " . $conn->connect_error);
}

// retrieve admin username from session
$admin_username = $_SESSION['admin_username'];

// check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['old_password']) && isset($_POST['new_password'])) 
{
    // capture the old and new passwords from form data
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    
    // prepare SQL statement to fetch the admin's current password
    $stmt = $conn->prepare("SELECT a_password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $admin_username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // check if old password matches the stored hash
    if ($result->num_rows == 1 && password_verify($old_password, $row['a_password'])) 
    {
        // hash new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        // prepare SQL to update password in database
        $sql_update_password = "UPDATE admins SET a_password='$hashed_password' WHERE username='$admin_username'";
        
        // execute the update query
        if ($conn->query($sql_update_password) === TRUE) {
            // alert success message
            echo "<script>alert('Password updated successfully.');</script>";
        } 
        else 
        {
            // alert error message
            echo "<script>alert('Error updating password: ".$conn->error."');</script>";
        }
    } 
    else 
    {
        // alert wrong old password message
        echo "<script>alert('Incorrect old password.');</script>";
    }
}

// close database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Settings</title>
    <!-- link CSS for styling -->
    <link rel="stylesheet" type="text/css" href="setting.css">
    <!-- include fontawesome for icons -->
    <script src="https://kit.fontawesome.com/0b424c94e9.js" crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <!-- display company logo -->
        <img src="logo.png" height="50px">
        <!-- display site name with decorative span -->
        <a class="shopname">bakemyday<span>.</span></a>
        <!-- navigation icons -->
        <div class="icons">
            <!-- link to return to the dashboard -->
            <a href="admin_dashboard.php"><i class="fa-solid fa-circle-left"></i></a>
            <span class="back"> Back To Dashboard</span>
        </div>
    </header>

    <h1>Admin Settings</h1>

    <div class="container">
        <!-- form for password change -->
        <form method="post">
            <label for="old_password">
                Old Password:
            </label>
            <input type="password" id="old_password" name="old_password" required><br>

            <label for="new_password">
                New Password:
            </label>
            <input type="password" id="new_password" name="new_password" required><br>

            <!-- submit button for form -->
            <button type="submit">
                Change Password
            </button>
        </form>
    </div>
    
</body>
</html>
