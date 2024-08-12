<?php
require 'auth.php';
include 'database.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Unknown error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemID = $_POST['itemID']; // This should be either feedbackID or replyID
    $userID = $_POST['userID'];
    $action = $_POST['reaction'];
    $itemType = $_POST['itemType'];

    if (!in_array($action, ['like', 'dislike']) || !in_array($itemType, ['feedback', 'reply'])) {
        $response['message'] = 'Invalid action or item type.';
        echo json_encode($response);
        exit();
    }

    // Determine the table and columns based on item type
    $table = ($itemType === 'feedback') ? 'userfeedbackreaction' : 'replyfeedbackreaction';
    $tableID = ($itemType === 'feedback') ? 'id' : 'reactionID';
    $reactionType = ($itemType === 'feedback') ? 'action' : 'reactionType';
    $feedbackColumn = ($itemType === 'feedback') ? 'feedbackLike' : 'replyLike';
    $dislikeColumn = ($itemType === 'feedback') ? 'feedbackDislike' : 'replyDislike';
    $itemTable = ($itemType === 'feedback') ? 'Feedback' : 'replyfeedback';
    $itemIDColumn = ($itemType === 'feedback') ? 'feedbackID' : 'replyID';

    // Generate a unique ID for new reactions
    $reactionID = $table . date('YmdHis') . rand(1000, 9999);

    // Check if the user has already reacted
    $sql = "SELECT $reactionType FROM $table WHERE userID = ? AND {$itemType}ID = ?";
    $stmt = $con->prepare($sql);
    if ($stmt === false) {
        $response['message'] = 'Failed to prepare statement: ' . $con->error . ' - SQL: ' . $sql;
        error_log('Failed to prepare statement: ' . $con->error . ' - SQL: ' . $sql, 3, 'C:/xampp/htdocs/ServersideGroupAssignment/error.log');
        echo json_encode($response);
        exit();
    }
    $stmt->bind_param("ss", $userID, $itemID);
    if (!$stmt->execute()) {
        $response['message'] = 'Failed to execute query: ' . $stmt->error;
        echo json_encode($response);
        exit();
    }
    $result = $stmt->get_result();

    if ($result === false) {
        $response['message'] = 'Failed to get result set: ' . $stmt->error;
        echo json_encode($response);
        exit();
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row[$reactionType] === $action) {
            // User wants to undo the action
            $sql = "DELETE FROM $table WHERE userID = ? AND {$itemType}ID = ?";
            $stmt = $con->prepare($sql);
            if ($stmt === false) {
                $response['message'] = 'Failed to prepare statement: ' . $con->error . ' - SQL: ' . $sql;
                error_log('Failed to prepare statement: ' . $con->error . ' - SQL: ' . $sql, 3, 'C:/xampp/htdocs/ServersideGroupAssignment/error.log');
                echo json_encode($response);
                exit();
            }
            $stmt->bind_param("ss", $userID, $itemID);
            if (!$stmt->execute()) {
                $response['message'] = 'Failed to execute query: ' . $stmt->error;
                echo json_encode($response);
                exit();
            }

            // Update the item like/dislike count
            $updateColumn = ($action === 'like') ? $feedbackColumn : $dislikeColumn;
            $sql = "UPDATE $itemTable SET $updateColumn = $updateColumn - 1 WHERE $itemIDColumn = ?";
            $stmt = $con->prepare($sql);
            if ($stmt === false) {
                $response['message'] = 'Failed to prepare statement: ' . $con->error . ' - SQL: ' . $sql;
                error_log('Failed to prepare statement: ' . $con->error . ' - SQL: ' . $sql, 3, 'C:/xampp/htdocs/ServersideGroupAssignment/error.log');
                echo json_encode($response);
                exit();
            }
            $stmt->bind_param("s", $itemID);
            if (!$stmt->execute()) {
                $response['message'] = 'Failed to execute query: ' . $stmt->error;
                echo json_encode($response);
                exit();
            }
        } else {
            $response['message'] = 'You cannot perform multiple actions on the same item.';
            echo json_encode($response);
            exit();
        }
    } else {
        // Insert reaction into table with unique ID
        $sql = "INSERT INTO $table ($tableID, {$itemType}ID, userID, $reactionType) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        if ($stmt === false) {
            $response['message'] = 'Failed to prepare statement: ' . $con->error . ' - SQL: ' . $sql;
            error_log('Failed to prepare statement: ' . $con->error . ' - SQL: ' . $sql, 3, 'C:/xampp/htdocs/ServersideGroupAssignment/error.log');
            echo json_encode($response);
            exit();
        }
        $stmt->bind_param("ssss", $reactionID, $itemID, $userID, $action);
        if (!$stmt->execute()) {
            $response['message'] = 'Failed to execute query: ' . $stmt->error;
            echo json_encode($response);
            exit();
        }

        // Update the item like/dislike count
        $updateColumn = ($action === 'like') ? $feedbackColumn : $dislikeColumn;
        $sql = "UPDATE $itemTable SET $updateColumn = $updateColumn + 1 WHERE $itemIDColumn = ?";
        $stmt = $con->prepare($sql);
        if ($stmt === false) {
            $response['message'] = 'Failed to prepare statement: ' . $con->error . ' - SQL: ' . $sql;
            error_log('Failed to prepare statement: ' . $con->error . ' - SQL: ' . $sql, 3, 'C:/xampp/htdocs/ServersideGroupAssignment/error.log');
            echo json_encode($response);
            exit();
        }
        $stmt->bind_param("s", $itemID);
        if (!$stmt->execute()) {
            $response['message'] = 'Failed to execute query: ' . $stmt->error;
            echo json_encode($response);
            exit();
        }
    }

    // Retrieve updated counts
    $sql = "SELECT $feedbackColumn, $dislikeColumn FROM $itemTable WHERE $itemIDColumn = ?";
    $stmt = $con->prepare($sql);
    if ($stmt === false) {
        $response['message'] = 'Failed to prepare statement: ' . $con->error . ' - SQL: ' . $sql;
        error_log('Failed to prepare statement: ' . $con->error . ' - SQL: ' . $sql, 3, 'C:/xampp/htdocs/ServersideGroupAssignment/error.log');
        echo json_encode($response);
        exit();
    }
    $stmt->bind_param("s", $itemID);
    if (!$stmt->execute()) {
        $response['message'] = 'Failed to execute query: ' . $stmt->error;
        echo json_encode($response);
        exit();
    }
    $result = $stmt->get_result();

    if ($result === false) {
        $response['message'] = 'Failed to get result set: ' . $stmt->error;
        echo json_encode($response);
        exit();
    }

    $countRow = $result->fetch_assoc();

    $response = [
        'status' => 'success',
        'feedbackLike' => ($itemType === 'feedback') ? $countRow['feedbackLike'] : 0,
        'feedbackDislike' => ($itemType === 'feedback') ? $countRow['feedbackDislike'] : 0,
        'replyLike' => ($itemType === 'reply') ? $countRow['replyLike'] : 0,
        'replyDislike' => ($itemType === 'reply') ? $countRow['replyDislike'] : 0
    ];
}

echo json_encode($response);
$con->close();
?>
