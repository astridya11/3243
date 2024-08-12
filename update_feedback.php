<?php
require 'auth.php';
include 'database.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Unknown error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedbackID = $_POST['feedbackID'];
    $userID = $_POST['userID'];
    $action = $_POST['action'];

    if (!in_array($action, ['like', 'dislike'])) {
        $response['message'] = 'Invalid action.';
        echo json_encode($response);
        exit();
    }

    // Generate a unique ID using the "tablenameDatetime" format
    $reactionID = 'userfeedbackreaction' . date('YmdHis') . rand(1000, 9999);

    // Check if the user has already reacted
    $stmt = $con->prepare("SELECT action FROM userfeedbackreaction WHERE userID = ? AND feedbackID = ?");
    $stmt->bind_param("ss", $userID, $feedbackID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['action'] === $action) {
            // User wants to undo the action
            $stmt = $con->prepare("DELETE FROM userfeedbackreaction WHERE userID = ? AND feedbackID = ?");
            $stmt->bind_param("ss", $userID, $feedbackID);
            if ($stmt->execute()) {
                $updateColumn = ($action === 'like') ? 'feedbackLike' : 'feedbackDislike';
                $stmt = $con->prepare("UPDATE Feedback SET $updateColumn = $updateColumn - 1 WHERE feedbackID = ?");
                $stmt->bind_param("s", $feedbackID);
                if ($stmt->execute()) {
                    $stmt = $con->prepare("SELECT feedbackLike, feedbackDislike FROM Feedback WHERE feedbackID = ?");
                    $stmt->bind_param("s", $feedbackID);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $countRow = $result->fetch_assoc();

                    $response = [
                        'status' => 'success',
                        'feedbackLike' => $countRow['feedbackLike'],
                        'feedbackDislike' => $countRow['feedbackDislike']
                    ];
                } else {
                    $response['message'] = 'Failed to update feedback count.';
                }
            } else {
                $response['message'] = 'Failed to remove reaction.';
            }
        } else {
            $response['message'] = 'You cannot perform multiple actions on the same feedback.';
        }
    } else {
        // Insert reaction into userfeedbackreaction table with unique ID
        $stmt = $con->prepare("INSERT INTO userfeedbackreaction (id, userID, feedbackID, action) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $reactionID, $userID, $feedbackID, $action);
        if ($stmt->execute()) {
            // Update the feedback like or dislike count
            $updateColumn = ($action === 'like') ? 'feedbackLike' : 'feedbackDislike';
            $stmt = $con->prepare("UPDATE Feedback SET $updateColumn = $updateColumn + 1 WHERE feedbackID = ?");
            $stmt->bind_param("s", $feedbackID);
            if ($stmt->execute()) {
                $stmt = $con->prepare("SELECT feedbackLike, feedbackDislike FROM Feedback WHERE feedbackID = ?");
                $stmt->bind_param("s", $feedbackID);
                $stmt->execute();
                $result = $stmt->get_result();
                $countRow = $result->fetch_assoc();

                $response = [
                    'status' => 'success',
                    'feedbackLike' => $countRow['feedbackLike'],
                    'feedbackDislike' => $countRow['feedbackDislike']
                ];
            } else {
                $response['message'] = 'Failed to update feedback count.';
            }
        } else {
            $response['message'] = 'Failed to record reaction.';
        }
    }
}

echo json_encode($response);
$con->close();
?>
