<?php
// Start the session first
session_start(); 

// Redirect if the session does not contain a valid user
if ($_SESSION['name'] == '') {
    header("Location: signin.php");
    exit(); // Prevent further code execution after redirect
}

include "../connection.php";
include("connect.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>Admin Dashboard Panel</title> 
</head>
<body>

    <nav>
        <div class="logo-name">
            <span class="logo_name">ADMIN</span>
        </div>

        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="admin.php"><i class="uil uil-estate"></i><span class="link-name">Dashboard</span></a></li>
                <li><a href="analytics.php"><i class="uil uil-chart"></i><span class="link-name">Analytics</span></a></li>
                <li><a href="#"><i class="uil uil-heart"></i><span class="link-name">Donates</span></a></li>
                <li><a href="feedback.php"><i class="uil uil-comments"></i><span class="link-name">Feedbacks</span></a></li>
                <li><a href="adminprofile.php"><i class="uil uil-user"></i><span class="link-name">Profile</span></a></li>
            </ul>

            <ul class="logout-mode">
                <li><a href="../logout.php"><i class="uil uil-signout"></i><span class="link-name">Logout</span></a></li>
                <li class="mode"><a href="#"><i class="uil uil-moon"></i><span class="link-name">Dark Mode</span></a></li>
            </ul>
        </div>
    </nav>

    <section class="dashboard">
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>
            <p class="logo">Zero<b style="color: #06C167;">WasteHub</b></p>
        </div>

        <br><br><br>
        <div class="activity">
            <div class="location">
                <form method="post">
                    <label for="location" class="logo">Select Location:</label>
                    <select id="location" name="location">
                        <option value="chennai">chennai</option>
                        <option value="madurai">madurai</option>
                        <option value="coimbatore">coimbatore</option>
                    </select>
                    <input type="submit" value="Get Details">
                </form>
                <br>

                <?php
                    if(isset($_POST['location'])) {
                        $location = $_POST['location'];
                        $sql = "SELECT * FROM food_donations WHERE location='$location'";
                        $result = mysqli_query($connection, $sql);

                        if ($result->num_rows > 0) {
                            echo "<div class=\"table-container\">";
                            echo "<div class=\"table-wrapper\">";
                            echo "<table class=\"table\">";
                            echo "<thead><tr><th>Name</th><th>Food</th><th>Category</th><th>Phone No</th><th>Date/Time</th><th>Address</th><th>Quantity</th></tr></thead><tbody>";

                            while($row = $result->fetch_assoc()) {
                                echo "<tr><td>".$row['name']."</td><td>".$row['food']."</td><td>".$row['category']."</td><td>".$row['phoneno']."</td><td>".$row['date']."</td><td>".$row['address']."</td><td>".$row['quantity']."</td></tr>";
                            }

                            echo "</tbody></table></div></div>";
                        } else {
                            echo "<p>No results found.</p>";
                        }
                    }
                ?>
            </div>
        </div>
    </section>

    <script src="admin.js"></script>
</body>
</html>
