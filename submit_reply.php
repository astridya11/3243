<?php
require 'auth.php';
include 'database.php';

// Validate and retrieve parameters
$feedbackID = isset($_POST['feedbackID']) ? mysqli_real_escape_string($con, $_POST['feedbackID']) : '';
$parentReplyID = isset($_POST['parentReplyID']) ? mysqli_real_escape_string($con, $_POST['parentReplyID']) : '';
$replyContent = isset($_POST['replyContent']) ? mysqli_real_escape_string($con, $_POST['replyContent']) : '';

// Check if required fields are provided
if (empty($feedbackID) || empty($replyContent)) {
    header("Location: feedback_detail.php?feedbackID=" . urlencode($feedbackID) . "&status=error");
    exit;
}

// Ensure parentReplyID is either a valid ID or NULL
$parentReplyID = ($parentReplyID === '' || $parentReplyID === '0') ? NULL : $parentReplyID;

// Validate feedbackID
$query = $con->prepare("SELECT COUNT(*) FROM Feedback WHERE feedbackID = ?");
$query->bind_param("s", $feedbackID);
$query->execute();
$result = $query->get_result();
if ($result->fetch_row()[0] == 0) {
    header("Location: feedback_detail.php?feedbackID=" . urlencode($feedbackID) . "&status=error");
    exit;
}

// Generate a unique replyID
$replyID = 'replyfeedback' . date('YmdHis') . rand(1000, 9999);

// Ensure parentReplyID is either valid or NULL
if ($parentReplyID !== NULL) {
    $query = $con->prepare("SELECT COUNT(*) FROM ReplyFeedback WHERE replyID = ?");
    $query->bind_param("s", $parentReplyID);
    $query->execute();
    $result = $query->get_result();
    if ($result->fetch_row()[0] == 0) {
        header("Location: feedback_detail.php?feedbackID=" . urlencode($feedbackID) . "&status=error");
        exit;
    }
}

// Insert the reply into the database
$query = $con->prepare("
    INSERT INTO ReplyFeedback (replyID, feedbackID, userID, replyContent, replyDateTime, parentReplyID) 
    VALUES (?, ?, ?, ?, NOW(), ?)
");
$userID = $_SESSION['userID'];
$query->bind_param("sssss", $replyID, $feedbackID, $userID, $replyContent, $parentReplyID);
if ($query->execute()) {
    // Redirect to feedback_detail.php with a success status
    header("Location: feedback_detail.php?feedbackID=" . urlencode($feedbackID) . "&status=success");
} else {
    // Redirect to feedback_detail.php with an error status
    header("Location: feedback_detail.php?feedbackID=" . urlencode($feedbackID) . "&status=error");
}

$con->close();
?>
