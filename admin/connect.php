<?php
session_start();
include '../connection.php'; // Make sure this includes the correct database connection setup
$msg = 0; // Initialize message variable for error handling

if (isset($_POST['sign'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Sanitize user input to prevent SQL injection
    $sanitized_emailid = mysqli_real_escape_string($connection, $email);
    $sanitized_password = mysqli_real_escape_string($connection, $password);

    // Prepare and execute the query securely using a prepared statement
    $sql = "SELECT * FROM admin WHERE email=?";
    if ($stmt = mysqli_prepare($connection, $sql)) {
        // Bind the email parameter
        mysqli_stmt_bind_param($stmt, "s", $sanitized_emailid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) == 1) {
            // User found, now check password
            while ($row = mysqli_fetch_assoc($result)) {
                if (password_verify($sanitized_password, $row['password'])) {
                    // Successful login
                    $_SESSION['email'] = $email;
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['location'] = $row['location'];
                    $_SESSION['Aid'] = $row['Aid'];
                    header("Location: admin.php");
                    exit();
                } else {
                    // Incorrect password
                    $msg = 1; // Incorrect password flag
                }
            }
        } else {
            // User not found
            $msg = 2; // User does not exist flag
        }
    } else {
        // SQL query preparation failed
        $msg = 3; // Error flag for SQL issue
    }
}

?>
