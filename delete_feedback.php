<?php
require 'auth.php';
include 'database.php';

if (!isset($_SESSION['userID']) || !isset($_POST['feedbackID'])) {
    echo 'error';
    exit();
}

$userID = $_SESSION['userID'];
$feedbackID = mysqli_real_escape_string($con, $_POST['feedbackID']);

// Check if feedback exists and belongs to the user
$query = "SELECT userID FROM Feedback WHERE feedbackID = '$feedbackID' LIMIT 1";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    if ($row['userID'] == $userID) {
        // Delete feedback
        $deleteQuery = "DELETE FROM Feedback WHERE feedbackID = '$feedbackID'";
        if (mysqli_query($con, $deleteQuery)) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
