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

// handle user deletion
if (isset($_POST['delete_user'])) 
{
    $user_id = $_POST['delete_user'];
    $sql_delete_user = "DELETE FROM users WHERE id='$user_id'";

    if ($conn->query($sql_delete_user) === TRUE) 
    {
        // set success message for user deletion
        $success_message_delete = "User Deleted.";
    } 
    else 
    {
        // set error message for user deletion
        $error_message_delete = "Error deleting user: " . $conn->error;
    }
}

// handle password change
if (isset($_POST['change_password'])) 
{
    $user_id = $_POST['user_id'];
    $new_password = $_POST['new_password'];

    // retrieve the old password from the database
    $sql_select_old_password = "SELECT u_password FROM users WHERE id='$user_id'";
    $result_old_password = $conn->query($sql_select_old_password);

    if ($result_old_password->num_rows > 0) 
    {
        $row_old_password = $result_old_password->fetch_assoc();
        $old_password_hash = $row_old_password['u_password'];

        // check if the new password is the same as the old password
        if (password_verify($new_password, $old_password_hash)) 
        {
            // set error message for same passwords
            $error_message_change[$user_id] = "New password cannot be the same as the old password.";
        } 
        else 
        {
            // hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $sql_change_password = "UPDATE users SET u_password='$hashed_password' WHERE id='$user_id'";

    if ($conn->query($sql_change_password) === TRUE) 
    {
        // display success message for password change
        $success_message_change[$user_id] = "Password Updated.";
    } 
    else 
    {
         // display error message for password change
         $error_message_change[$user_id] = "Error changing password: " . $conn->error;
    }
}
} 
else 
{
    // display error message if old password not found
    $error_message_change[$user_id] = "Error: Old password not found.";
}
}

// retrieve all users from the users table
$sql_select_users = "SELECT id, username, email FROM users";
$result = $conn->query($sql_select_users);

// close connection
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" type="text/css" href="manage_user.css">
    <script src="https://kit.fontawesome.com/0b424c94e9.js" crossorigin="anonymous"></script>
</head>

<body>
    <!-- top navigation bar for the manage user page -->
    <header>
        <img src="logo.png" height="50px">
        <a class="shopname">bakemyday<span>.</span></a>
        <div class="icons">
            <a href="admin_dashboard.php"><i class="fa-solid fa-circle-left"></i></a>
            <span class="back"> Back To Dashboard</span>
        </div>
    </header>

    <!-- manage menu user's header title -->
    <h1>Manage Users</h1>

    <!-- table to display user details -->
    <table>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Action</th>
        </tr>

        <?php
        if ($result->num_rows > 0) 
        {
            while ($row = $result->fetch_assoc()) 
            {
                echo "<tr>";
                echo "<td>" .$row['id']. "</td>";
                echo "<td>" .$row['username']. "</td>";
                echo "<td>" .$row['email']. "</td>";
                echo "<td>";
                echo "<br>";
                echo "<form method='post'>";
                echo "<input type='hidden' name='delete_user' value='" .$row['id']. "'>";
                echo "<button type='submit'>Delete</button>"; 
                echo "<br><br>";
                echo "</form>";
                echo "<form method='post'>";
                echo "<input type='hidden' name='user_id' value='" .$row['id']. "'>";
                echo "<input type='password' name='new_password' placeholder='New Password'>";
                echo "<button type='submit' name='change_password'>Change Password</button>";
                echo "</form>";

                // display error message if there is one and change password button was pressed
                if(isset($error_message_change[$row['id']])) 
                {
                    echo "<p style='color: red;'>".$error_message_change[$row['id']]."</p>";
                }

                // display success message if there is one and change password button was pressed
                if(isset($success_message_change[$row['id']])) 
                {
                    echo "<p style='color: green;'>".$success_message_change[$row['id']]."</p>";
                }
                
                echo "</td>";
                echo "</tr>";
            }
        } 
        else 
        {
            echo "<tr>
            <td colspan='4'>No Users Found</td>
            </tr>";
        }
        ?>
    </table>

</body>
</html>
