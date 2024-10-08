<?php
include MODEL_PATH . 'database.php';

// Get user ID from session
$userID = $_SESSION['userID'] ?? null;

// Get the movieID from query parameters and validate it
$movieID = isset($_REQUEST['movieID']) ? trim($_GET['movieID']) : null;

// Determine sort order based on query parameter
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'latest';
$orderBy = ($sort === 'hottest') ? 'f.feedbackLike DESC' : 'f.feedbackDateTime DESC';

// Prepare and execute query
$query = "SELECT f.feedbackID, f.feedbackTitle, f.feedbackContent, f.feedbackDateTime, f.feedbackLike, f.feedbackDislike, u.userName, u.userProfilePic, f.userID, f.movieID
        FROM Feedback f 
        JOIN Users u ON f.userID = u.userID 
        WHERE f.movieID = ?
        ORDER BY $orderBy";

if ($stmt = mysqli_prepare($con, $query)) {
    mysqli_stmt_bind_param($stmt, 's', $movieID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        echo "<p>No feedback found for this movie.</p>";
    }
} else {
    die('Database query preparation failed: ' . mysqli_error($con));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback and Discussion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        .feedback-section {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        .feedback-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .feedback-header .button-group {
            display: flex;
            gap: 10px;
        }
        .feedback-header button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #FFD700; /* Yellow accent */
            color: #000;
            font-size: 16px;
            display: flex;
            align-items: center;
        }
        .feedback-header button:hover {
            background-color: #FFC107; /* Darker yellow */
        }
        .feedback-header button i {
            margin-right: 8px;
        }
        .feedback-item {
            border: 1px solid #444;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            background-color: #111;
        }
        .feedback-content-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .user-info {
            display: flex;
            align-items: center;
        }
        .user-info img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .feedback-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #FFD700; /* Yellow accent */
        }
        .feedback-content {
            margin-bottom: 10px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .feedback-actions {
            display: flex;
            gap: 10px;
        }
        .feedback-actions button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            color: #fff;
        }
        .like-btn, .dislike-btn, .read-more-btn, .delete-btn {
            background-color: #333;
        }
        .like-btn {
            background-color: #4CAF50;
        }
        .dislike-btn {
            background-color: #f44336;
        }
        .read-more-btn {
            background-color: #555;
        }
        .delete-btn {
            background-color: #f44336;
        }
        .like-btn:hover, .dislike-btn:hover, .read-more-btn:hover, .delete-btn:hover {
            opacity: 0.8;
        }
        .like-btn i, .dislike-btn i, .read-more-btn i, .delete-btn i {
            margin-right: 5px;
        }

        /* Popup form styles */
        .popup {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7); /* Darker overlay */
        }
        .popup-content {
            background-color: #222;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 5px;
            color: #fff;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: #fff;
            text-decoration: none;
            cursor: pointer;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #444;
            background-color: #333;
            color: #fff;
        }
        .form-group button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #FFD700; /* Yellow accent */
            color: #000;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #FFC107; /* Darker yellow */
        }

        /* Confirmation Popup styles */
        .confirmation-popup {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7); /* Darker overlay */
        }
        .confirmation-popup-content {
            background-color: #222;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 5px;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="feedback-section">
        <h1>Feedback and Discussion</h1>
        <div class="feedback-header">
            <div class="button-group">
                <a href="movies_details.php?movieID=<?php echo urlencode($movieID); ?>&sort=latest"><button><i class="fas fa-clock"></i> Latest</button></a>
                <a href="movies_details.php?movieID=<?php echo urlencode($movieID); ?>&sort=hottest"><button><i class="fas fa-fire"></i> Hottest</button></a>
            </div>
            <button id="openFormBtn"><i class="fas fa-plus"></i> I want to feedback</button>
        </div>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="feedback-item">
                    <div class="feedback-content-header">
                        <div class="user-info">
                           
                                <img src="<?php echo htmlspecialchars($row['userProfilePic'] ?$row['userProfilePic'] : '../model/image/_default-pic.png'); ?> " >
                           
                            <div>
                                <div><?php echo htmlspecialchars($row['userName']); ?></div>
                                <div><?php echo htmlspecialchars($row['feedbackDateTime']); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="feedback-title"><?php echo htmlspecialchars($row['feedbackTitle']); ?></div>
                    <div class="feedback-content"><?php echo nl2br(htmlspecialchars($row['feedbackContent'])); ?></div>
                    <div class="feedback-actions">
                        <button class="like-btn" 
                            data-feedback-id="<?php echo $row['feedbackID']; ?>" 
                            data-movie-id="<?php echo $row['movieID']; ?>"
                            data-feedback-like="<?php echo $row['feedbackLike']; ?>">
                            <i class="fas fa-thumbs-up"></i> 
                            <span><?php echo $row['feedbackLike']; ?></span> Like
                        </button>

                        <button class="dislike-btn" 
                            data-feedback-id="<?php echo $row['feedbackID']; ?>" 
                            data-movie-id="<?php echo $row['movieID']; ?>"
                            data-feedback-dislike="<?php echo $row['feedbackDislike']; ?>">
                            <i class="fas fa-thumbs-down"></i> 
                            <span><?php echo $row['feedbackDislike']; ?></span> Dislike
                        </button>
                        <button class="read-more-btn" onclick="window.location.href='feedback_detail.php?feedbackID=<?php echo $row['feedbackID']; ?>'"><i class="fas fa-eye"></i> Read more</button>
                        <?php if ($row['userID'] == $userID): ?>
                            <button class="delete-btn" data-feedback-id="<?php echo $row['feedbackID']; ?>"><i class="fas fa-trash-alt"></i> Delete</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No feedback found for this movie.</p>
        <?php endif; ?>
    </div>

    <!-- Feedback Form Popup -->
    <div id="feedbackFormPopup" class="popup">
        <div class="popup-content">
            <span class="close" id="closeForm">&times;</span>
            <h2>Provide Your Feedback</h2>
            <form action="../controller/submit_feedback.php" method="POST">
                <input type="hidden" name="movieID" value="<?php echo htmlspecialchars($movieID); ?>">
                <div class="form-group">
                    <label for="feedbackTitle">Title</label>
                    <input type="text" id="feedbackTitle" name="feedbackTitle" required>
                </div>
                <div class="form-group">
                    <label for="feedbackContent">Content</label>
                    <textarea id="feedbackContent" name="feedbackContent" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <button type="submit"><i class="fas fa-paper-plane"></i> Submit Feedback</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Popup -->
    <div id="confirmationPopup" class="confirmation-popup">
        <div class="confirmation-popup-content">
            <p>Are you sure you want to delete this feedback?</p>
            <button id="confirmDelete">Yes, Delete</button>
            <button id="cancelDelete">Cancel</button>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements for popups and buttons
        const confirmationPopup = document.getElementById("confirmationPopup");
        const feedbackFormPopup = document.getElementById("feedbackFormPopup");
        const openFormBtn = document.getElementById('openFormBtn');
        const closeForm = document.getElementById('closeForm');
        const confirmDeleteBtn = document.getElementById("confirmDelete");
        const cancelDeleteBtn = document.getElementById("cancelDelete");
        let deleteFeedbackID = null;

        // Event listener for opening the feedback form popup
        openFormBtn.addEventListener('click', () => {
            feedbackFormPopup.style.display = 'block';
        });

        // Event listener for closing the feedback form popup
        closeForm.addEventListener('click', () => {
            feedbackFormPopup.style.display = 'none';
        });

        // Event listener for closing popups when clicking outside
        window.addEventListener('click', (event) => {
            if (event.target === feedbackFormPopup) {
                feedbackFormPopup.style.display = 'none';
            }
            if (event.target === confirmationPopup) {
                confirmationPopup.style.display = 'none';
            }
        });

        // Show confirmation popup for deletion
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', () => {
                deleteFeedbackID = button.getAttribute('data-feedback-id');
                confirmationPopup.style.display = 'block';
            });
        });

        // Confirm deletion of feedback
        confirmDeleteBtn.addEventListener('click', () => {
            if (deleteFeedbackID) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../controller/delete_feedback.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        location.reload();
                    } else {
                        alert('Error deleting feedback.');
                    }
                };
                xhr.send(`feedbackID=${deleteFeedbackID}`);
            }
            confirmationPopup.style.display = 'none';
        });

        // Cancel deletion
        cancelDeleteBtn.addEventListener('click', () => {
            confirmationPopup.style.display = 'none';
        });

        document.querySelectorAll('.like-btn, .dislike-btn').forEach(button => {
            button.addEventListener('click', function() {
                const feedbackID = this.getAttribute('data-feedback-id');
                const movieID = this.getAttribute('data-movie-id');
                const action = this.classList.contains('like-btn') ? 'like' : 'dislike';
                const currentCount = parseInt(this.getAttribute(`data-feedback-${action}`));

                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../controller/update_feedback.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.status === 'success') {
                                const countElement = button.querySelector('span');
                                countElement.textContent = action === 'like' ? response.feedbackLike : response.feedbackDislike;

                                // Optionally update button state
                                // button.disabled = true; // Disable button if needed
                            } else {
                                alert(response.message || 'Error processing your request.');
                            }
                        } catch (e) {
                            alert('Please log in first to perform the action.');
                            window.location.href = "../";
                        }
                    } else {
                        alert('Error processing your request.');
                    }
                };
                xhr.onerror = function() {
                    alert('Request failed.');
                };
                xhr.send(`feedbackID=${encodeURIComponent(feedbackID)}&movieID=${encodeURIComponent(movieID)}&action=${encodeURIComponent(action)}`);
            });
        });
    });
    </script>
</body>
</html>