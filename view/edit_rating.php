<?php 
require_once('../config.php');
require(CONTROLLER_PATH . 'auth.php');
require(CONTROLLER_PATH.'edit_rating_logic.php');
require(MODEL_PATH . 'database.php');
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins'>
    <link rel="stylesheet" href="rating_style.css">
    <title>Edit Rating</title>
    <script>
        window.onload = function() {
            <?php if (isset($success) && $success) : ?>
                alert('Your rating has been successfully updated.');
                window.location.href = 'movies_details.php?movieID=<?php echo htmlspecialchars($movieID); ?>';
            <?php endif; ?>
        }
    </script>
</head>

<body>
    <!-- header section -->
    <div class="header">
        <div class="header-left">
            <a href="movies_dashboard.php"><img src="../model/image/logo-transparent.png"></a>
        </div>
        <div class="header-right">
            <a href="profile.php"><img src="../model/image/profile-icon.png"></a>
        </div>
    </div>

    <!-- Edit rating section -->
    <div class="rating-box">
        <h2>Edit Your Rating</h2>
        <form method="POST" action="edit_rating.php">
            <input type="hidden" name="movieID" value="<?php echo htmlspecialchars($movieID); ?>">

            <label for="ratingStar">Star Rating:</label>
            <div class="star-rating">
                <input type="radio" id="star5" name="ratingStar" value="5" <?php echo $ratingStar == 5 ? 'checked' : ''; ?> required>
                <label for="star5">&#9733;</label>
                <input type="radio" id="star4" name="ratingStar" value="4" <?php echo $ratingStar == 4 ? 'checked' : ''; ?>>
                <label for="star4">&#9733;</label>
                <input type="radio" id="star3" name="ratingStar" value="3" <?php echo $ratingStar == 3 ? 'checked' : ''; ?>>
                <label for="star3">&#9733;</label>
                <input type="radio" id="star2" name="ratingStar" value="2" <?php echo $ratingStar == 2 ? 'checked' : ''; ?>>
                <label for="star2">&#9733;</label>
                <input type="radio" id="star1" name="ratingStar" value="1" <?php echo $ratingStar == 1 ? 'checked' : ''; ?>>
                <label for="star1">&#9733;</label>
            </div>

            <label for="ratingDescription">Your Review:</label>
            <textarea name="ratingDescription" id="ratingDescription" rows="5" placeholder="Write your review here..."><?php echo htmlspecialchars($ratingDescription); ?></textarea>

            <button type="submit">Update Rating</button>
        </form>
    </div>
</body>

</html>
