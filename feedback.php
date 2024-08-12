<?php
require 'auth.php'; // Ensure this script includes authentication logic
include 'database.php'; // Include the database connection

// Check if userID is set in the session
if (!isset($_SESSION['userID'])) {
    // Redirect to login page or show an error
    header("Location: login.php");
    exit();
}

// Get user ID from session
$userID = $_SESSION['userID'];

// Get the movieID from query parameters
$movieID = isset($_GET['movieID']) ? $_GET['movieID'] : null;

// Ensure movieID is valid
if ($movieID) {
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
    } else {
        die('Database query preparation failed: ' . mysqli_error($con));
    }
} else {
    die('Invalid movie ID.');
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
                <a href="feedback.php?movieID=<?php echo urlencode($movieID); ?>&sort=latest"><button><i class="fas fa-clock"></i> Latest</button></a>
                <a href="feedback.php?movieID=<?php echo urlencode($movieID); ?>&sort=hottest"><button><i class="fas fa-fire"></i> Hottest</button></a>
            </div>
            <button id="openFormBtn"><i class="fas fa-plus"></i> I want to feedback</button>
        </div>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="feedback-item">
                <div class="feedback-content-header">
                    <div class="user-info">
                        <?php if ($row['userProfilePic']): ?>
                            <img src="<?php echo htmlspecialchars($row['userProfilePic']); ?>" alt="<?php echo htmlspecialchars($row['userName']); ?>'s Profile Picture">
                        <?php else: ?>
                            <img src="default-profile-pic.jpg" alt="Default Profile Picture">
                        <?php endif; ?>
                        <div>
                            <div><?php echo htmlspecialchars($row['userName']); ?></div>
                            <div><?php echo htmlspecialchars($row['feedbackDateTime']); ?></div>
                        </div>
                    </div>
                </div>
                <div class="feedback-title"><?php echo htmlspecialchars($row['feedbackTitle']); ?></div>
                <div class="feedback-content"><?php echo nl2br(htmlspecialchars($row['feedbackContent'])); ?></div>
                <div class="feedback-actions">
                    <button class="like-btn" data-feedback-id="<?php echo $row['feedbackID']; ?>" data-feedback-like="<?php echo $row['feedbackLike']; ?>"><i class="fas fa-thumbs-up"></i> <span><?php echo $row['feedbackLike']; ?></span></button>
                    <button class="dislike-btn" data-feedback-id="<?php echo $row['feedbackID']; ?>" data-feedback-dislike="<?php echo $row['feedbackDislike']; ?>"><i class="fas fa-thumbs-down"></i> <span><?php echo $row['feedbackDislike']; ?></span></button>
                    <button class="read-more-btn" onclick="window.location.href='feedback_detail.php?feedbackID=<?php echo $row['feedbackID']; ?>'"><i class="fas fa-eye"></i> Read more</button>
                    <?php if ($row['userID'] == $userID): ?>
                        <button class="delete-btn" data-feedback-id="<?php echo $row['feedbackID']; ?>"><i class="fas fa-trash-alt"></i> Delete</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Feedback Form Popup -->
    <div id="feedbackFormPopup" class="popup">
        <div class="popup-content">
            <span class="close" id="closeForm">&times;</span>
            <h2>Provide Your Feedback</h2>
            <form action="submit_feedback.php" method="POST">
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
        // Handle feedback form popup
        var formPopup = document.getElementById('feedbackFormPopup');
        var openFormBtn = document.getElementById('openFormBtn');
        var closeForm = document.getElementById('closeForm');

        openFormBtn.onclick = function() {
            formPopup.style.display = 'block';
        }

        closeForm.onclick = function() {
            formPopup.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == formPopup) {
                formPopup.style.display = 'none';
            }
        }

        // Handle like/dislike buttons
        document.querySelectorAll('.like-btn, .dislike-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var feedbackID = button.getAttribute('data-feedback-id');
                var action = button.classList.contains('like-btn') ? 'like' : 'dislike';
                var countSpan = button.querySelector('span');

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_reaction.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            countSpan.textContent = response.newCount;
                            button.setAttribute('data-feedback-' + action, response.newCount);
                        } else {
                            alert('Error: ' + response.message);
                        }
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                };

                xhr.send('feedbackID=' + encodeURIComponent(feedbackID) + 
                         '&userID=' + encodeURIComponent('<?php echo $userID; ?>') + 
                         '&action=' + encodeURIComponent(action));
            });
        });

        // Handle delete button
        document.querySelectorAll('.delete-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var feedbackID = button.getAttribute('data-feedback-id');

                // Show confirmation popup
                document.getElementById('confirmationPopup').style.display = 'flex';

                // Handle confirmation
                document.getElementById('confirmDelete').onclick = function() {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'delete_feedback.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                // Remove the feedback item from the DOM
                                button.closest('.feedback-item').remove();
                            } else {
                                alert('Error: ' + response.message);
                            }
                        } else {
                            alert('An error occurred. Please try again.');
                        }
                    };

                    xhr.send('feedbackID=' + encodeURIComponent(feedbackID) + 
                             '&userID=' + encodeURIComponent('<?php echo $userID; ?>'));

                    // Close the confirmation popup
                    document.getElementById('confirmationPopup').style.display = 'none';
                };

                // Handle cancellation
                document.getElementById('cancelDelete').onclick = function() {
                    document.getElementById('confirmationPopup').style.display = 'none';
                };
            });
        });
    </script>
</body>
</html>
