<?php
require_once '../config.php'; // Include configuration file
require CONTROLLER_PATH . 'auth.php';
include MODEL_PATH . 'database.php';

// Check if the user is authenticated and if the feedbackID is provided
if (!isset($_SESSION['userID']) || !isset($_POST['feedbackID'])) {
    echo 'error';
    exit();
}

$userID = $_SESSION['userID'];
$feedbackID = trim($_POST['feedbackID']);

// Prepare the SQL query to check if the feedback exists and belongs to the user
$query = "SELECT userID FROM Feedback WHERE feedbackID = ? LIMIT 1";
$stmt = mysqli_prepare($con, $query);

if ($stmt === false) {
    echo 'error';
    exit();
}

mysqli_stmt_bind_param($stmt, 's', $feedbackID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    if ($row['userID'] == $userID) {
        // Prepare the SQL query to delete the feedback
        $deleteQuery = "DELETE FROM Feedback WHERE feedbackID = ?";
        $deleteStmt = mysqli_prepare($con, $deleteQuery);

        if ($deleteStmt === false) {
            echo 'error';
            exit();
        }

        mysqli_stmt_bind_param($deleteStmt, 's', $feedbackID);

        if (mysqli_stmt_execute($deleteStmt)) {
            echo 'success';
        } else {
            echo 'error';
        }

        mysqli_stmt_close($deleteStmt);
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}

mysqli_stmt_close($stmt);
mysqli_close($con);
?>
