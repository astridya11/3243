<?php
require_once '../config.php'; // Include configuration file
require CONTROLLER_PATH . 'auth.php';
include MODEL_PATH . 'database.php';

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
        header('Location: ../view/feedback_detail.php?feedbackID=' . urlencode($_POST['feedbackID']));
        exit();
    } else {
        die('Update failed.');
    }
}
mysqli_stmt_close($stmt);
mysqli_close($con);
?>
