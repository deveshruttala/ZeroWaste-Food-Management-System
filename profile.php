<?php
session_start(); // Ensure session_start() is called before any output

// Check if the session variables are set
if (!isset($_SESSION['name']) || $_SESSION['name'] == '') {
    header("Location: signup.php"); // Redirect if the session is not set
    exit();
}

include("connect.php"); // Include the database connection file

$email = $_SESSION['email'];
$query = "SELECT * FROM food_donations WHERE email='$email'";
$result = mysqli_query($connection, $query);

if (!$result) {
    echo "Error: " . mysqli_error($connection);  // Output any MySQL errors for debugging
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<header>
    <div class="logo">Zero<b style="color: #06C167;">WasteHub</b></div>
    <div class="hamburger">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <nav class="nav-bar">
        <ul>
            <li><a href="home.html">Home</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="profile.php" class="active">Profile</a></li>
        </ul>
    </nav>
</header>
<script>
    hamburger = document.querySelector(".hamburger");
    hamburger.onclick = function () {
        navBar = document.querySelector(".nav-bar");
        navBar.classList.toggle("active");
    }
</script>

<div class="profile">
    <div class="profilebox">
        <p class="headingline" style="text-align: left;font-size:30px;">Profile</p>

        <div class="info" style="padding-left:10px;">
            <p>Name: <?php echo $_SESSION['name']; ?></p><br>
            <p>Email: <?php echo $_SESSION['email']; ?></p><br>
            <p>Gender: <?php echo $_SESSION['gender']; ?></p><br>
            <a href="logout.php" style="float: left; margin-top: 6px; border-radius: 5px; background-color: #06C167; color: white; padding: 10px;">Logout</a>
        </div>

        <hr>
        <p class="heading">Your donations</p>

        <div class="table-container">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Food</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Date/Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr><td>" . $row['food'] . "</td><td>" . $row['type'] . "</td><td>" . $row['category'] . "</td><td>" . $row['date'] . "</td></tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>          
    </div>
</div>
</body>
</html>
