<?php
session_start();
include 'connection.php'; // Make sure this file includes a valid connection to the MySQL database
$msg = 0;

if (isset($_POST['sign'])) {
    // Sanitize input to prevent SQL injection
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    // Query to check if the user exists in the database
    $sql = "SELECT * FROM login WHERE email='$email'";
    $result = mysqli_query($connection, $sql);
    $num = mysqli_num_rows($result);

    if ($num == 1) {
        // Fetch user data
        while ($row = mysqli_fetch_assoc($result)) {
            // Verify the password using password_verify
            if (password_verify($password, $row['password'])) {
                // Set session variables for the logged-in user
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $row['name'];
                $_SESSION['gender'] = $row['gender'];
                
                // Redirect user to the home page
                header("Location: home.html");
                exit();
            } else {
                // Incorrect password
                $msg = 1;
            }
        }
    } else {
        // No user found with this email
        echo "<h1><center>Account does not exist</center></h1>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="loginstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>

<body>
    <style>
        .uil {
            top: 42%;
        }
    </style>

    <div class="container">
        <div class="regform">
            <form action="signin.php" method="post">
                <p class="logo">Food <b style="color:#06C167;">Donate</b></p>
                <p id="heading">Welcome back!</p>

                <!-- Email Input -->
                <div class="input">
                    <input type="email" placeholder="Email address" name="email" value="" required />
                </div>

                <!-- Password Input -->
                <div class="password">
                    <input type="password" placeholder="Password" name="password" id="password" required />
                    <i class="uil uil-eye-slash showHidePw"></i>

                    <!-- Error Message for Incorrect Password -->
                    <?php
                    if ($msg == 1) {
                        echo '<i class="bx bx-error-circle error-icon"></i>';
                        echo '<p class="error">Password does not match.</p>';
                    }
                    ?>
                </div>

                <!-- Submit Button -->
                <div class="btn">
                    <button type="submit" name="sign">Sign In</button>
                </div>

                <!-- Link to Sign Up Page -->
                <div class="signin-up">
                    <p id="signin-up">Don't have an account? <a href="signup.php">Register</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="login.js"></script>
    <script src="admin/login.js"></script>
</body>

</html>
