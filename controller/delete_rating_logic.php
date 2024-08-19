<?php
require_once('../config.php');
require(CONTROLLER_PATH . 'auth.php');
require(MODEL_PATH . 'database.php');

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

// Retrieve userID from session
$userID = $_SESSION['userID'];

if (!isset($_REQUEST['movieID'])) {
    echo "Error: Movie not found! (movieID not provided)";
    exit;
}

$movieID = $_REQUEST['movieID'];

// Check if the user has already rated the movie
$query = "SELECT ratingID FROM rating WHERE movieID = ? AND userID = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'ss', $movieID, $userID);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $ratingID);

if (!mysqli_stmt_fetch($stmt)) {
    echo "Error: You have not rated this movie yet!";
    exit;
}
mysqli_stmt_close($stmt);

// Delete the rating
$query = "DELETE FROM rating WHERE movieID = ? AND userID = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'ss', $movieID, $userID);

if (mysqli_stmt_execute($stmt)) {
    $success = true;
} else {
    $success = false;
    echo "Error: " . mysqli_error($con);
}
mysqli_stmt_close($stmt);
?>