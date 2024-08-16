<?php
require_once '../config.php'; // Include configuration file
require CONTROLLER_PATH . 'auth.php';
include MODEL_PATH . 'database.php';

$response = ['status' => 'error', 'message' => 'Unknown error']; // Initialize response

try {
    // Check if the user is authenticated and if the feedbackID is provided
    if (!isset($_SESSION['userID']) || !isset($_POST['feedbackID'])) {
        throw new Exception('User not authenticated or feedback ID missing.');
    }

    $userID = $_SESSION['userID'];
    $feedbackID = trim($_POST['feedbackID']);

    // Prepare the SQL query to check if the feedback exists and belongs to the user
    $query = "SELECT userID FROM Feedback WHERE feedbackID = ? LIMIT 1";
    $stmt = mysqli_prepare($con, $query);

    if ($stmt === false) {
        throw new Exception('Failed to prepare statement.');
    }

    mysqli_stmt_bind_param($stmt, 's', $feedbackID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result === false) {
        throw new Exception('Failed to execute query.');
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($row['userID'] == $userID) {
            // Prepare the SQL query to delete the feedback
            $deleteQuery = "DELETE FROM Feedback WHERE feedbackID = ?";
            $deleteStmt = mysqli_prepare($con, $deleteQuery);
            header("Location: ../view/movies_details.php?movieID=" . urlencode($movieID) . "#feedback");

            if ($deleteStmt === false) {
                throw new Exception('Failed to prepare delete statement.');
            }

            mysqli_stmt_bind_param($deleteStmt, 's', $feedbackID);

            if (mysqli_stmt_execute($deleteStmt)) {
                $response = ['status' => 'success', 'message' => 'Feedback deleted successfully.'];
            } else {
                throw new Exception('Error executing delete query.');
            }

            mysqli_stmt_close($deleteStmt);
        } else {
            throw new Exception('Feedback does not belong to the user.');
        }
    } else {
        throw new Exception('Feedback not found.');
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>