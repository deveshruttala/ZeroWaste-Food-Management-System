<?php
// Change mysqli_connect(host_name, username, password);
$connection = mysqli_connect("db", "phpmyadmin", "phpmyadmin");
$db = mysqli_select_db($connection, 'demo');
?>
