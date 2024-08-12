<?php
require 'auth.php'; // Ensure this script contains session start and authentication checks
include 'database.php'; // Database connection

if (!isset($_SESSION['username'])) {
    // Redirect to login if the user is not authenticated
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input and sanitize it
    $feedbackTitle = trim($_POST['feedbackTitle']);
    $movieID = trim($_POST['movieID']);
    $feedbackContent = trim($_POST['feedbackContent']);

    // Validate the input
    if (empty($feedbackTitle) || empty($feedbackContent)) {
        echo "Error: Feedback title and content cannot be empty.";
        exit();
    }

    // Get the user ID from the session
    $userID = $_SESSION['userID'];

    // Verify userID exists in users table
    $query = "SELECT userID FROM users WHERE userID = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $userID); // Assuming userID is an integer
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        echo "Error: User ID does not exist.";
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        exit();
    }
    mysqli_stmt_close($stmt);

    // Generate a unique feedbackID
    $feedbackID = 'feedback' . date('YmdHis') . rand(1000, 9999); // Unique feedback ID

    // Prepare the SQL statement
    $query = "INSERT INTO feedback (feedbackID, userID, movieID, feedbackTitle, feedbackContent, feedbackDateTime, feedbackLike, feedbackDislike) 
              VALUES (?, ?, ?, ?, ?, NOW(), 0, 0)";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt === false) {
        // Handle preparation errors
        echo "Error preparing statement: " . mysqli_error($con);
        exit();
    }

    // Bind parameters (s: string, i: integer, etc.)
    mysqli_stmt_bind_param($stmt, 'sssss', $feedbackID, $userID, $movieID, $feedbackTitle, $feedbackContent);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Redirect to feedback page with movieID
        header("Location: movies_details.php?movieID=" . urlencode($movieID) . "#feedback");
        exit();
    } else {
        // Handle execution errors
        echo "Error executing statement: " . mysqli_stmt_error($stmt);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($con);
?>
