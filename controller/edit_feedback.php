<?php
require_once '../config.php'; // Include configuration file
require CONTROLLER_PATH . 'auth.php';
include MODEL_PATH . 'database.php';

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['status' => 'error', 'message' => 'User not authenticated']);
    exit();
}

// Check if the necessary POST parameters are provided
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedbackID = $_POST['feedbackID'] ?? null;
    $feedbackTitle = $_POST['feedbackTitle'] ?? null;
    $feedbackContent = $_POST['feedbackContent'] ?? null;

    // Debugging: Print received POST data
    error_log(print_r($_POST, true));

    // Validate input
    if (!$feedbackID || !$feedbackTitle || !$feedbackContent) {
        http_response_code(400); // Bad Request
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit();
    }

    // Prepare and execute update query
    try {
        $query = "UPDATE Feedback SET feedbackTitle = ?, feedbackContent = ? WHERE feedbackID = ?";
        $stmt = $con->prepare($query);
        if (!$stmt) {
            die('Prepare failed: ' . htmlspecialchars($con->error));
        }
        $stmt->bind_param('sss', $feedbackTitle, $feedbackContent, $feedbackID);
        $stmt->execute();

    } catch (PDOException $e) {
        // Handle database error
        http_response_code(500); // Internal Server Error
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
mysqli_stmt_close($stmt);
mysqli_close($con);
?>
