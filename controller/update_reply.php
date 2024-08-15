<?php
require_once '../config.php'; // Include configuration file
require CONTROLLER_PATH . 'auth.php';
include MODEL_PATH . 'database.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Unknown error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $replyID = $_POST['replyID'];
    $userID = $_POST['userID'];
    $action = $_POST['action'];

    if (!in_array($action, ['like', 'dislike'])) {
        $response['message'] = 'Invalid action.';
        echo json_encode($response);
        exit();
    }

    // Generate a unique ID using the "tablenameDatetime" format
    $reactionID = 'replyfeedbackreaction' . date('YmdHis') . rand(1000, 9999);

    // Check if the user has already reacted
    $stmt = $con->prepare("SELECT action FROM replyfeedbackreaction WHERE userID = ? AND replyID = ?");
    $stmt->bind_param("ss", $userID, $replyID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['action'] === $action) {
            // User wants to undo the action
            $stmt = $con->prepare("DELETE FROM replyfeedbackreaction WHERE userID = ? AND replyID = ?");
            $stmt->bind_param("ss", $userID, $replyID);
            if ($stmt->execute()) {
                $updateColumn = ($action === 'like') ? 'replyLike' : 'replyDislike';
                $stmt = $con->prepare("UPDATE ReplyFeedback SET $updateColumn = $updateColumn - 1 WHERE replyID = ?");
                $stmt->bind_param("s", $replyID);
                if ($stmt->execute()) {
                    $stmt = $con->prepare("SELECT replyLike, replyDislike FROM ReplyFeedback WHERE replyID = ?");
                    $stmt->bind_param("s", $replyID);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $countRow = $result->fetch_assoc();

                    $response = [
                        'status' => 'success',
                        'replyLike' => $countRow['replyLike'],
                        'replyDislike' => $countRow['replyDislike']
                    ];
                } else {
                    $response['message'] = 'Failed to update reply count.';
                }
            } else {
                $response['message'] = 'Failed to remove reaction.';
            }
        } else {
            $response['message'] = 'You cannot perform multiple actions on the same reply.';
        }
    } else {
        // Insert reaction into replyfeedbackreaction table with unique ID
        $stmt = $con->prepare("INSERT INTO replyfeedbackreaction (id, userID, replyID, action) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $reactionID, $userID, $replyID, $action);
        if ($stmt->execute()) {
            // Update the reply like or dislike count
            $updateColumn = ($action === 'like') ? 'replyLike' : 'replyDislike';
            $stmt = $con->prepare("UPDATE ReplyFeedback SET $updateColumn = $updateColumn + 1 WHERE replyID = ?");
            $stmt->bind_param("s", $replyID);
            if ($stmt->execute()) {
                $stmt = $con->prepare("SELECT replyLike, replyDislike FROM ReplyFeedback WHERE replyID = ?");
                $stmt->bind_param("s", $replyID);
                $stmt->execute();
                $result = $stmt->get_result();
                $countRow = $result->fetch_assoc();

                $response = [
                    'status' => 'success',
                    'replyLike' => $countRow['replyLike'],
                    'replyDislike' => $countRow['replyDislike']
                ];
            } else {
                $response['message'] = 'Failed to update reply count.';
            }
        } else {
            $response['message'] = 'Failed to record reaction.';
        }
    }
}

echo json_encode($response);
$con->close();
?>
