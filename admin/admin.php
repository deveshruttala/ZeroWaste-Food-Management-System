<?php
// Start the session at the very beginning
session_start();

// Include the connection file
include("connect.php");

// Check if the session variable 'name' is set, if not, redirect to sign-in page
if (empty($_SESSION['name'])) {
    header("location:signin.php");
    exit(); // Prevent further script execution after redirect
}

// Ensure proper database connection (check the connection if needed)
$connection = mysqli_connect("localhost", "root", "", "demo");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
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
                <li><a href="#"><i class="uil uil-estate"></i><span class="link-name">Dashboard</span></a></li>
                <li><a href="analytics.php"><i class="uil uil-chart"></i><span class="link-name">Analytics</span></a></li>
                <li><a href="donate.php"><i class="uil uil-heart"></i><span class="link-name">Donates</span></a></li>
                <li><a href="feedback.php"><i class="uil uil-comments"></i><span class="link-name">Feedbacks</span></a></li>
                <li><a href="adminprofile.php"><i class="uil uil-user"></i><span class="link-name">Profile</span></a></li>
            </ul>
            <ul class="logout-mode">
                <li><a href="../logout.php"><i class="uil uil-signout"></i><span class="link-name">Logout</span></a></li>
            </ul>
        </div>
    </nav>

    <section class="dashboard">
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>
            <p class="logo">Zero<b style="color: #06C167;">WasteHub</b></p>
        </div>

        <div class="dash-content">
            <div class="overview">
                <div class="title">
                    <i class="uil uil-tachometer-fast-alt"></i>
                    <span class="text">Dashboard</span>
                </div>
                <div class="boxes">
                    <!-- Displaying Total Counts -->
                    <div class="box box1">
                        <i class="uil uil-user"></i>
                        <span class="text">Total users</span>
                        <?php
                           $query = "SELECT count(*) as count FROM login";
                           $result = mysqli_query($connection, $query);
                           $row = mysqli_fetch_assoc($result);
                           echo "<span class=\"number\">" . $row['count'] . "</span>";
                        ?>
                    </div>
                    <div class="box box2">
                        <i class="uil uil-comments"></i>
                        <span class="text">Feedbacks</span>
                        <?php
                           $query = "SELECT count(*) as count FROM user_feedback";
                           $result = mysqli_query($connection, $query);
                           $row = mysqli_fetch_assoc($result);
                           echo "<span class=\"number\">" . $row['count'] . "</span>";
                        ?>
                    </div>
                    <div class="box box3">
                        <i class="uil uil-heart"></i>
                        <span class="text">Total donations</span>
                        <?php
                           $query = "SELECT count(*) as count FROM food_donations";
                           $result = mysqli_query($connection, $query);
                           $row = mysqli_fetch_assoc($result);
                           echo "<span class=\"number\">" . $row['count'] . "</span>";
                        ?>
                    </div>
                </div>
            </div>

            <div class="activity">
                <div class="title">
                    <i class="uil uil-clock-three"></i>
                    <span class="text">Recent Donations</span>
                </div>

                <!-- Fetch and display recent donations -->
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Food</th>
                                <th>Category</th>
                                <th>Phone No</th>
                                <th>Date/Time</th>
                                <th>Address</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $loc = $_SESSION['location'];
                                $sql = "SELECT * FROM food_donations WHERE assigned_to IS NULL AND location = '$loc'";
                                $result = mysqli_query($connection, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                        <td>{$row['name']}</td>
                                        <td>{$row['food']}</td>
                                        <td>{$row['category']}</td>
                                        <td>{$row['phoneno']}</td>
                                        <td>{$row['date']}</td>
                                        <td>{$row['address']}</td>
                                        <td>{$row['quantity']}</td>
                                    </tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
