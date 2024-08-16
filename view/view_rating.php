<?php
require_once('../config.php');
require(CONTROLLER_PATH . "view_rating_logic.php");
require(MODEL_PATH.'database.php');
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins'>
    <link rel="stylesheet" href="rating_style.css">
    <title>Rating</title>
</head>

<body>

    <!-- Total rating review section -->
    <div class="rating_container">
        <div class="rating-summary">
            <div class="rating-main">
                <img src="../model/image/star.png">
                <h2><?php echo number_format($averageRating, 1); ?></h2>
            </div>
            <p><?php echo $totalReviews; ?> rated </p>
        </div>

        <!-- Statistic bar -->
        <div class="rating-bars">
            <?php foreach ($ratingCounts as $stars => $count) : ?>
                <div class="bar">
                    <div class="bar-name"><?php echo $stars; ?> star </div>
                    <div class="bar-container">
                        <div class="bar-fill" style="width: <?php echo ($totalReviews > 0 ? ($count / $totalReviews) * 100 : 0); ?>%;"></div>
                    </div>
                    <div class="bar-label"><?php echo $count; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- User review section -->
    <div class="user-review-container">
        <div class="user-review-header">
            <h2>Users' Ratings</h2>
            <?php if ($ratingCount == 0) : ?>
                <button type="button" class="add-review" onclick="addRating()">Add Rating</button>
            <?php endif; ?>
        </div>

        <div class="reviews-scrollable">
            <?php if ($reviewResult && mysqli_num_rows($reviewResult) > 0) : ?>
                <?php while ($review = mysqli_fetch_assoc($reviewResult)) : ?>
                    <div class="user-review">
                        <div class="user-info">
                            <img src="<?php echo htmlspecialchars($review['userProfilePic'] ? $review['userProfilePic'] : '../model/image/default-pic.png'); ?>" class="user-image">
                            <div>
                                <p class="user-name">
                                    <?php echo htmlspecialchars($review['userName']); ?>
                                    <?php
                                    if (!empty($review['lastModified']) && $review['lastModified'] != $review['ratingDate']) {
                                        $editedAgo = timeAgo($review['lastModified']);
                                        echo " <span class='edited-info'>(Edited $editedAgo)</span>";
                                    }
                                    ?>
                                </p>
                                <p class="review-date"><?php echo htmlspecialchars($review['ratingDate']); ?></p>
                                <p class="review-time"><?php echo htmlspecialchars($review['ratingTime']); ?></p>
                                <p class="review-text"><?php echo htmlspecialchars($review['ratingDescription']); ?></p>
                            </div>
                        </div>
                        <div class="review-rating">
                            <span class="stars"><?php echo str_repeat('★', $review['ratingStar']) . str_repeat('☆', 5 - $review['ratingStar']); ?>
                                <span class="rating-score">(<?php echo $review['ratingStar']; ?>)</span>
                            </span>
                            <br>
                            <?php if ($review['reviewUserID'] == $userID) : ?>
                                <span>
                                    <button class="edit-review" onclick="editRating('<?php echo $review['ratingID']; ?>')">Edit Rating</button>
                                    <button class="delete-review" onclick="deleteRating('<?php echo $review['ratingID']; ?>')">Delete Rating</button>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <p>No rating yet.</p>
            <?php endif; ?>
        </div>
    </div>
    <script>
        function addRating() {
            <?php if ($ratingCount > 0) : ?>
                alert('You have already rated this movie.\n If you wish to rate this movie again, kindly edit your rating.');
            <?php else : ?>
                window.location.href = "add_rating.php?movieID=<?php echo $movieID ?>";
            <?php endif; ?>
        }

        function editRating(ratingID) {
            <?php if ($ratingCount > 0) : ?>
                window.location.href = "edit_rating.php?movieID=<?php echo $movieID ?>&ratingID=" + ratingID;
            <?php else : ?>
                alert('You have not rated this movie yet.');
            <?php endif; ?>
        }

        function deleteRating(ratingID) {
            <?php if ($ratingCount > 0) : ?>
                if (confirm('Are you sure you want to delete this rating?')) {
                    window.location.href = "delete_rating.php?movieID=<?php echo $movieID ?>&ratingID=" + ratingID;
                }
            <?php else : ?>
                alert('You have not rated this movie yet.');
            <?php endif; ?>
        }
    </script>
</body>

</html>