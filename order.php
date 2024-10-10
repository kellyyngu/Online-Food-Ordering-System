<!DOCTYPE html> 
<html>

<head>
    <title>Order Page</title>
    <link rel="stylesheet" href="order.css">
    <script src="https://kit.fontawesome.com/0b424c94e9.js" crossorigin="anonymous"></script>
</head>

<body>
    <!-- top navigation bar for the registration page -->
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


    <h1 id="header">
        Welcome to Bake My Day!
    </h1>

    <br><br>
    
            <p id="registerpara">Are you here to explore our delicious menu and place an order? <br>
                Click 'Register' to become our member and start ordering.</p>
            <button type="submit" id="submit" onclick = "location.href='register.php'">Register</button>

    <br><br>

            <p id="loginpara">Already a member? Log in to place orders and enjoy personalized experiences</p>
            <button id="submit" onclick = "location.href='login.php'">Log In</button>



    <!-- include footer (shop details) in registration page -->
    <?php include "footer.php" ?>

</body>
</html>
