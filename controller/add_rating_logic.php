<?php
require_once('../config.php');
require(MODEL_PATH . 'database.php');

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

// Retrieve userID from session
$userID = $_SESSION['userID'];

if (isset($_REQUEST['movieID'])) {
    $movieID = $_REQUEST['movieID']; // Retrieve movieID from request
} else {
    echo "Error: movieID not provided!";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ratingID = 'rating' . date('YmdHis');
    $ratingStar = $_POST['ratingStar'];
    
    // Escape special characters in ratingDescription
    $ratingDescription = mysqli_real_escape_string($con, $_POST['ratingDescription']);

    // Insert rating into database
    $query = "INSERT INTO rating (ratingID, ratingDate, ratingTime, ratingDescription, ratingStar, userID, movieID)
              VALUES ('$ratingID', CURDATE(), NOW(), '$ratingDescription', '$ratingStar', '$userID', '$movieID')";

    if (mysqli_query($con, $query)) {
        header("Location: movies_details.php?movieID=" . urlencode($movieID)); // Redirect to the movie page
        exit;
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
