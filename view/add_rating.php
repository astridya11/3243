<?php
require_once('../config.php');
require(CONTROLLER_PATH . 'auth.php');
require(CONTROLLER_PATH.'add_rating_logic.php');
require(MODEL_PATH . 'database.php');
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins'>
    <link rel="stylesheet" href="rating_style.css">
    <title>Add Rating</title>
</head>

<body>
    <!-- header section -->
    <div class="header">
        <div class="header-left">
            <a href="movies_dashboard.php"><img src="image/logo-transparent.png"></a>
        </div>
        <div class="header-right">
            <a href="profile.php"><img src="image/profile-icon.png"></a>
        </div>
    </div>

    <!-- Add rating section -->
    <div class="rating-box">
        <h2>Add Your Rating</h2>
        <form method="POST" action="add_rating.php">
            <input type="hidden" name="movieID" value="<?php echo htmlspecialchars($movieID); ?>">

            <label for="ratingStar">Star Rating:</label>
            <div class="star-rating">
                <input type="radio" id="star5" name="ratingStar" value="5" required>
                <label for="star5">&#9733;</label>
                <input type="radio" id="star4" name="ratingStar" value="4">
                <label for="star4">&#9733;</label>
                <input type="radio" id="star3" name="ratingStar" value="3">
                <label for="star3">&#9733;</label>
                <input type="radio" id="star2" name="ratingStar" value="2">
                <label for="star2">&#9733;</label>
                <input type="radio" id="star1" name="ratingStar" value="1">
                <label for="star1">&#9733;</label>
            </div>

            <label for="ratingDescription">Your Review:</label>
            <textarea name="ratingDescription" id="ratingDescription" rows="5" placeholder="Write your review here..."></textarea>

            <button type="submit">Submit Review</button>
        </form>
    </div>
</body>

</html>