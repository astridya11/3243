<?php
require_once '../config.php'; // Include configuration file
include CONTROLLER_PATH . 'auth.php';
include MODEL_PATH . 'database.php';

// Validate and retrieve parameters
$feedbackID = isset($_GET['feedbackID']) ? mysqli_real_escape_string($con, $_GET['feedbackID']) : '';
$userID = $_SESSION['userID'];


if (empty($feedbackID)) {
    die('Invalid feedback ID.');
}

// Function to execute a query and return results
function executeQuery($con, $query, $params) {
    $stmt = $con->prepare($query);
    if (!$stmt) {
        die('Prepare failed: ' . htmlspecialchars($con->error));
    }
    $stmt->bind_param(...$params);
    $stmt->execute();
    return $stmt->get_result();
}

// Fetch feedback details
$query = "
    SELECT f.feedbackTitle, f.feedbackContent, f.feedbackDateTime, f.feedbackLike, f.feedbackDislike, f.userID, f.movieID,
           u.userName, u.userProfilePic 
    FROM Feedback f 
    JOIN users u ON f.userID = u.userID 
    WHERE f.feedbackID = ?
";
$result = executeQuery($con, $query, ["s", $feedbackID]);

if ($result->num_rows === 0) {
    die('Feedback not found.');
}

$feedback = $result->fetch_assoc();
$movieID = $feedback['movieID'];

// Fetch replies
$query_replies = "
    SELECT r.replyID, r.replyContent, r.replyDateTime, r.parentReplyID, r.replyLike, r.replyDislike, r.userID as replyUserID,
           u.userName, u.userProfilePic 
    FROM ReplyFeedback r 
    JOIN users u ON r.userID = u.userID 
    WHERE r.feedbackID = ? 
    ORDER BY r.replyDateTime ASC
";
$result_replies = executeQuery($con, $query_replies, ["s", $feedbackID]);

$all_replies = $result_replies->fetch_all(MYSQLI_ASSOC);

// Function to display replies recursively
function displayReplies($parentID, $replies, $feedbackID) {
    foreach ($replies as $reply) {
        if ($reply['parentReplyID'] == $parentID) {
            ?>
            <div class="reply-item" id="reply-<?php echo htmlspecialchars($reply['replyID']); ?>">
                <div class="user-info">
                    <img src="<?php echo htmlspecialchars($reply['userProfilePic'] ?? 'default-profile-pic.jpg'); ?>" alt="<?php echo htmlspecialchars($reply['userName']); ?>'s Profile Picture">
                    <div>
                        <div class="user-name"><?php echo htmlspecialchars($reply['userName']); ?></div>
                        <div class="reply-date"><?php echo htmlspecialchars($reply['replyDateTime']); ?></div>
                    </div>
                </div>
                <div class="reply-content"><?php echo nl2br(htmlspecialchars($reply['replyContent'])); ?></div>
                <div class="reply-actions">
                    <button type="button" class="like-reply-btn" data-reply-like="<?php echo htmlspecialchars($reply['replyLike']); ?>" data-item-id="<?php echo htmlspecialchars($reply['replyID']); ?>" data-reaction="like" data-user-id="<?php echo htmlspecialchars($_SESSION['userID']); ?>">Like <?php echo $reply['replyLike']; ?></button>
                    <button type="button" class="dislike-reply-btn" data-reply-dislike="<?php echo htmlspecialchars($reply['replyDislike']); ?>" data-item-id="<?php echo htmlspecialchars($reply['replyID']); ?>" data-reaction="dislike" data-user-id="<?php echo htmlspecialchars($_SESSION['userID']); ?>">Dislike <?php echo $reply['replyDislike']; ?></button>
                    <?php if ($_SESSION['userID'] == $reply['replyUserID']) { ?>
                        <button type="button" class="edit-reply-btn" 
                            data-reply-id="<?php echo htmlspecialchars($reply['replyID']); ?>" 
                            data-feedback-id="<?php echo htmlspecialchars($feedbackID); ?>" 
                            data-reply-content="<?php echo htmlspecialchars($reply['replyContent']); ?>"
                            onclick="showEditReplyForm('<?php echo htmlspecialchars($reply['replyID']); ?>', '<?php echo htmlspecialchars(addslashes($reply['replyContent'])); ?>', '<?php echo htmlspecialchars($feedbackID); ?>')"> 
                            <i class="fas fa-edit"></i>Edit
                        </button>
                        <button type="button" class="delete-reply-btn" 
                            data-reply-id="<?php echo htmlspecialchars($reply['replyID']); ?>" 
                            data-feedback-id="<?php echo htmlspecialchars($feedbackID); ?>" 
                            onclick="confirmDelete('<?php echo htmlspecialchars($reply['replyID']); ?>', '<?php echo htmlspecialchars($feedbackID); ?>')"> 
                            <i class="fas fa-trash"></i>Delete
                        </button>
                    <?php } ?>
                    <button type="button" class="reply-btn" onclick="toggleReplyForm('<?php echo $reply['replyID']; ?>')">Reply</button>
                </div>
                <div id="reply-form-<?php echo $reply['replyID']; ?>" class="reply-form">
                    <h4>Reply to this reply</h4>
                    <form action="..\controller\submit_reply.php" method="POST">
                        <input type="hidden" name="feedbackID" value="<?php echo htmlspecialchars($feedbackID); ?>">
                        <input type="hidden" name="parentReplyID" value="<?php echo htmlspecialchars($reply['replyID']); ?>">
                        <div class="form-group">
                            <label for="replyContent-<?php echo $reply['replyID']; ?>">Reply Content</label>
                            <textarea id="replyContent-<?php echo $reply['replyID']; ?>" name="replyContent" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit">Submit Reply</button>
                        </div>
                    </form>
                </div>
                <div class="nested-replies">
                    <?php displayReplies($reply['replyID'], $replies, $feedbackID); ?>
                </div>
            </div>
            <?php
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($feedback['feedbackTitle']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="feedback_detail.css">
</head>
<body>
    <script src="feedback_detail.js"></script>
    <div class="feedback-detail">
    <a href="../movies_details.php?movieID=<?php echo urlencode($movieID); ?>" class="back-btn">Back</a>
        <h1><?php echo htmlspecialchars($feedback['feedbackTitle']); ?></h1>
        <div class="feedback-item">
            <div class="user-info">
                <img src="<?php echo htmlspecialchars($feedback['userProfilePic'] ?? 'default-profile-pic.jpg'); ?>" 
                     alt="<?php echo htmlspecialchars($feedback['userName']); ?>'s Profile Picture">
                <div>
                    <div class="user-name"><?php echo htmlspecialchars($feedback['userName']); ?></div>
                    <div class="feedback-date"><?php echo htmlspecialchars($feedback['feedbackDateTime']); ?></div>
                </div>
            </div>
            <div class="feedback-content"><?php echo nl2br(htmlspecialchars($feedback['feedbackContent'])); ?></div>
            <div class="feedback-actions">
                <button type="button" class="like-btn" data-item-id="<?php echo htmlspecialchars($feedbackID); ?>" data-reaction="like" data-user-id="<?php echo htmlspecialchars($userID); ?>">
                    Like <?php echo $feedback['feedbackLike']; ?>
                </button>
                <button type="button" class="dislike-btn" data-item-id="<?php echo htmlspecialchars($feedbackID); ?>" data-reaction="dislike" data-user-id="<?php echo htmlspecialchars($userID); ?>">
                    Dislike <?php echo $feedback['feedbackDislike']; ?>
                </button>
                    <?php if ($_SESSION['userID'] == $feedback['userID']) { ?>
                    <button type="button" class="edit-feedback-btn" 
                        data-feedback-id="<?php echo htmlspecialchars($feedbackID); ?>" 
                        data-feedback-title="<?php echo htmlspecialchars(addslashes($feedback['feedbackTitle'])); ?>" 
                        data-feedback-content="<?php echo htmlspecialchars(addslashes($feedback['feedbackContent'])); ?>" 
                        onclick="showEditFeedbackForm('<?php echo htmlspecialchars($feedbackID); ?>', '<?php echo htmlspecialchars(addslashes($feedback['feedbackTitle'])); ?>', '<?php echo htmlspecialchars(addslashes($feedback['feedbackContent'])); ?>')">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button type="button" class="delete-feedback-btn" data-feedback-id="<?php echo htmlspecialchars($feedbackID); ?>" onclick="confirmDeleteFeedback('<?php echo htmlspecialchars($feedbackID); ?>')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                <?php } ?>
            </div>
        </div>
        <div class="reply-section">
            <h2>Replies</h2>
            <div id="reply-form-0" class="reply-form">
                <form action="../controller/submit_reply.php" method="POST">
                    <input type="hidden" name="feedbackID" value="<?php echo htmlspecialchars($feedbackID); ?>">
                    <div class="form-group">
                        <label for="replyContent">Your Reply</label>
                        <textarea id="replyContent" name="replyContent" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit">Submit Reply</button>
                    </div>
                </form>
            </div>
            <?php displayReplies(0, $all_replies, $feedbackID); ?>
            <h2>Reply</h2>
            <form action="../controller/submit_reply.php" method="POST">
                <input type="hidden" name="feedbackID" value="<?php echo htmlspecialchars($feedbackID); ?>">
                <div class="form-group">
                    <label for="replyContent">Reply Content</label>
                    <textarea id="replyContent" name="replyContent" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <button type="submit">Submit Reply</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Edit Modal -->
    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h4>Edit Reply</h4>
            <form id="edit-reply-form" action="..\controller\edit_reply.php" method="POST">
                <input type="hidden" name="replyID" id="edit-reply-id">
                <input type="hidden" name="feedbackID" id="edit-feedback-id" value="<?php echo htmlspecialchars($feedbackID); ?>">
                <div class="form-group">
                    <label for="edit-reply-content">Reply Content</label>
                    <textarea id="edit-reply-content" name="replyContent" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <button type="submit">Update Reply</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Edit Feedback Modal -->
    <div id="edit-feedback-modal" class="modal">
            <div class="modal-content">
                <span class="close-feedback">&times;</span>
                <h4>Edit Feedback</h4>
                <form id="edit-feedback-form" action="..\controller\edit_feedback.php" method="POST">
                <input type="hidden" name="feedbackID" id="edit-feedback-id" value="<?php echo htmlspecialchars($feedbackID); ?>">
                    <div class="form-group">
                        <label for="edit-feedback-title">Feedback Title</label>
                        <input type="text" id="edit-feedback-title" name="feedbackTitle" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-feedback-content">Feedback Content</label>
                        <textarea id="edit-feedback-content" name="feedbackContent" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit">Update Feedback</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>