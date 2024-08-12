<?php
require 'auth.php';
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $replyID = isset($_POST['replyID']) ? mysqli_real_escape_string($con, $_POST['replyID']) : '';
    $newContent = isset($_POST['replyContent']) ? mysqli_real_escape_string($con, $_POST['replyContent']) : '';

    if (empty($replyID) || empty($newContent)) {
        die('Invalid input.');
    }

    $query = "UPDATE ReplyFeedback SET replyContent = ? WHERE replyID = ? AND userID = ?";
    $stmt = $con->prepare($query);
    if (!$stmt) {
        die('Prepare failed: ' . htmlspecialchars($con->error));
    }
    $stmt->bind_param('sss', $newContent, $replyID, $_SESSION['userID']);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header('Location: feedback_detail.php?feedbackID=' . urlencode($_POST['feedbackID']));
        exit();
    } else {
        die('Update failed.');
    }
}
?>
