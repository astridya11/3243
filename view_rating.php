<?php
require('database.php');

// Fetch the movieID
$movieID = isset($_GET['movieID']) ? trim($_GET['movieID']) : null;

if (!$movieID) {
    echo "Error: Movie not found! (movieID not provided)";
    exit;
}


// Check if user has already rated the movie
$userID = $_SESSION['userID'] ?? null;
$ratingCount = 0;

if ($userID) {
    $checkRatingQuery = "SELECT COUNT(*) as count FROM rating WHERE movieID = ? AND userID = ?";
    $checkRatingStmt = mysqli_prepare($con, $checkRatingQuery);
    mysqli_stmt_bind_param($checkRatingStmt, 'ss', $movieID, $userID);
    mysqli_stmt_execute($checkRatingStmt);
    $checkRatingResult = mysqli_stmt_get_result($checkRatingStmt);
    $ratingCount = mysqli_fetch_assoc($checkRatingResult)['count'];
}

// Fetch ratings and reviews from database
$ratingQuery = "SELECT ratingStar FROM rating WHERE movieID = ?";
$ratingStmt = mysqli_prepare($con, $ratingQuery);
mysqli_stmt_bind_param($ratingStmt, 's', $movieID);
mysqli_stmt_execute($ratingStmt);
$ratingResult = mysqli_stmt_get_result($ratingStmt);

// Fetch rating with lastModified field and user details
$reviewQuery = "SELECT r.ratingID, r.ratingTime, r.ratingDate, r.ratingDescription, r.ratingStar, r.lastModified, r.userID as reviewUserID, u.userName, u.userProfilePic
                FROM rating r
                JOIN users u ON r.userID = u.userID
                WHERE r.movieID = ?";
$stmt = mysqli_prepare($con, $reviewQuery);
mysqli_stmt_bind_param($stmt, 's', $movieID);
mysqli_stmt_execute($stmt);
$reviewResult = mysqli_stmt_get_result($stmt);

if ($ratingResult) {
    $totalRatings = 0;
    $ratingCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
    $totalReviews = mysqli_num_rows($ratingResult);

    while ($row = mysqli_fetch_assoc($ratingResult)) {
        $ratingStar = $row['ratingStar'];
        $totalRatings += $ratingStar;
        if (isset($ratingCounts[$ratingStar])) {
            $ratingCounts[$ratingStar]++;
        }
    }

    $averageRating = $totalReviews > 0 ? round($totalRatings / $totalReviews, 1) : 0;
} else {
    echo "Error: " . mysqli_error($con);
    exit;
}

// Function to calculate time ago
function timeAgo($datetime)
{
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->getTimestamp() - $ago->getTimestamp();

    if ($diff < 60) {
        return "just now";
    } elseif ($diff < 3600) { // Less than an hour
        $minutes = round($diff / 60);
        return "$minutes minute" . ($minutes > 1 ? 's' : '') . " ago";
    } elseif ($diff < 86400) { // Less than a day
        $hours = round($diff / 3600);
        return "$hours hour" . ($hours > 1 ? 's' : '') . " ago";
    } elseif ($diff < 604800) { // Less than a week
        $days = round($diff / 86400);
        return "$days day" . ($days > 1 ? 's' : '') . " ago";
    } elseif ($diff < 2419200) { // Less than a month
        $weeks = round($diff / 604800);
        return "$weeks week" . ($weeks > 1 ? 's' : '') . " ago";
    } elseif ($diff < 29030400) { // Less than a year
        $months = round($diff / 2419200);
        return "$months month" . ($months > 1 ? 's' : '') . " ago";
    } else {
        $years = round($diff / 29030400);
        return "$years year" . ($years > 1 ? 's' : '') . " ago";
    }
}
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
                <img src="image/star.png">
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
                            <img src="<?php echo htmlspecialchars($review['userProfilePic'] ? $review['userProfilePic'] : 'image/default-pic.png'); ?>" class="user-image">
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