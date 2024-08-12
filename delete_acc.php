<?php
include("auth.php");
require('database.php');

// delete user from database
$userid=$_GET['userID'];
$query = "DELETE FROM users WHERE userID='$userid'";
mysqli_query($con,$query) or die ( mysqli_error($con));

// destroy the session
session_destroy();

header("Location: login.php");
exit();
?>