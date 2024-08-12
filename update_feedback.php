<?php
require 'auth.php'; // Ensure this script contains session start and authentication checks
include 'database.php'; // Database connection

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Unknown error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedbackID = $_POST['feedbackID'];
    $userID = $_SESSION['userID'];
    $action = $_POST['action']; // like or dislike

    // Validate inputs
    if (!in_array($action, ['like', 'dislike'])) {
        $response['message'] = 'Invalid action.';
        echo json_encode($response);
        exit();
    }

    // Table and column names
    $reactionTable = 'userfeedbackreaction';
    $feedbackTable = 'feedback';
    $feedbackLikeColumn = 'feedbackLike';
    $feedbackDislikeColumn = 'feedbackDislike';
    $reactionTypeColumn = 'action';

    // Start transaction
    $con->begin_transaction();

    try {
        // Check if the user has already reacted
        $sql = "SELECT $reactionTypeColumn FROM $reactionTable WHERE userID = ? AND feedbackID = ?";
        $stmt = $con->prepare($sql);
        if ($stmt === false) {
            throw new Exception('Failed to prepare statement: ' . $con->error);
        }
        $stmt->bind_param("ss", $userID, $feedbackID);
        if (!$stmt->execute()) {
            throw new Exception('Failed to execute query: ' . $stmt->error);
        }
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row[$reactionTypeColumn] === $action) {
                // User wants to undo the action
                $sql = "DELETE FROM $reactionTable WHERE userID = ? AND feedbackID = ?";
                $stmt = $con->prepare($sql);
                if ($stmt === false) {
                    throw new Exception('Failed to prepare statement: ' . $con->error);
                }
                $stmt->bind_param("ss", $userID, $feedbackID);
                if (!$stmt->execute()) {
                    throw new Exception('Failed to execute query: ' . $stmt->error);
                }

                // Update the feedback like/dislike count
                $updateColumn = ($action === 'like') ? $feedbackLikeColumn : $feedbackDislikeColumn;
                $sql = "UPDATE $feedbackTable SET $updateColumn = $updateColumn - 1 WHERE feedbackID = ?";
                $stmt = $con->prepare($sql);
                if ($stmt === false) {
                    throw new Exception('Failed to prepare statement: ' . $con->error);
                }
                $stmt->bind_param("s", $feedbackID);
                if (!$stmt->execute()) {
                    throw new Exception('Failed to execute query: ' . $stmt->error);
                }
            } else {
                $response['message'] = 'You cannot perform multiple actions on the same feedback.';
                echo json_encode($response);
                exit();
            }
        } else {
            // Insert new reaction
            $sql = "INSERT INTO $reactionTable (userID, feedbackID, $reactionTypeColumn) VALUES (?, ?, ?)";
            $stmt = $con->prepare($sql);
            if ($stmt === false) {
                throw new Exception('Failed to prepare statement: ' . $con->error);
            }
            $stmt->bind_param("sss", $userID, $feedbackID, $action);
            if (!$stmt->execute()) {
                throw new Exception('Failed to execute query: ' . $stmt->error);
            }

            // Update the feedback like/dislike count
            $updateColumn = ($action === 'like') ? $feedbackLikeColumn : $feedbackDislikeColumn;
            $sql = "UPDATE $feedbackTable SET $updateColumn = $updateColumn + 1 WHERE feedbackID = ?";
            $stmt = $con->prepare($sql);
            if ($stmt === false) {
                throw new Exception('Failed to prepare statement: ' . $con->error);
            }
            $stmt->bind_param("s", $feedbackID);
            if (!$stmt->execute()) {
                throw new Exception('Failed to execute query: ' . $stmt->error);
            }
        }

        // Commit transaction
        $con->commit();

        // Retrieve updated counts
        $sql = "SELECT $feedbackLikeColumn, $feedbackDislikeColumn FROM $feedbackTable WHERE feedbackID = ?";
        $stmt = $con->prepare($sql);
        if ($stmt === false) {
            throw new Exception('Failed to prepare statement: ' . $con->error);
        }
        $stmt->bind_param("s", $feedbackID);
        if (!$stmt->execute()) {
            throw new Exception('Failed to execute query: ' . $stmt->error);
        }
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $countRow = $result->fetch_assoc();
            $response = [
                'status' => 'success',
                'feedbackLike' => $countRow[$feedbackLikeColumn],
                'feedbackDislike' => $countRow[$feedbackDislikeColumn]
            ];
        } else {
            $response['message'] = 'Failed to retrieve updated counts.';
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $con->rollback();
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);
$con->close();
?>
