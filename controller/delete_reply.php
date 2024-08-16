<?php
require_once '../config.php'; // Include configuration file
require CONTROLLER_PATH . 'auth.php';
include MODEL_PATH . 'database.php';

// Retrieve parameters
$replyID = isset($_GET['replyID']) ? mysqli_real_escape_string($con, $_GET['replyID']) : '';
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : '';
$feedbackID = isset($_GET['feedbackID']) ? mysqli_real_escape_string($con, $_GET['feedbackID']) : '';

// Debugging output
if (empty($replyID)) {
    die('Reply ID is missing or invalid.');
}

if (empty($userID)) {
    die('User ID is missing or invalid.');
}

if (empty($feedbackID)) {
    die('Feedback ID is missing or invalid.');
}

// Check if the reply exists and belongs to the current user
$stmt = $con->prepare("SELECT userID FROM ReplyFeedback WHERE replyID = ?");
if (!$stmt) {
    die("Prepare failed: " . $con->error);
}
$stmt->bind_param("s", $replyID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('Reply not found.');
}

$reply = $result->fetch_assoc();

if ($reply['userID'] !== $userID) {
    die('Unauthorized request.');
}

// Prepare and execute the deletion query
$stmt = $con->prepare("DELETE FROM ReplyFeedback WHERE replyID = ? AND userID = ?");
if (!$stmt) {
    die("Prepare failed: " . $con->error);
}
$stmt->bind_param("si", $replyID, $userID); // Adjust parameter type if needed
$stmt->execute();

// Check if deletion was successful
if ($stmt->affected_rows > 0) {
    // Redirect back to the feedback detail page with the feedbackID
    header("Location: feedback_detail.php?feedbackID=" . urlencode($feedbackID));
    exit();
} else {
    die('Error deleting reply or reply not found.');
}
mysqli_stmt_close($stmt);
mysqli_close($con);
?>
