<?php
require('database.php');

if (isset($_GET['id'])) {
    $ratingID = $_GET['id'];

    // Fetch existing review data
    $query = "SELECT * FROM rating WHERE ratingID = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $ratingID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $review = mysqli_fetch_assoc($result);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $ratingStar = $_POST['ratingStar'];
        $ratingDescription = $_POST['ratingDescription'];

        // Update review
        $query = "UPDATE rating SET ratingDescription = ?, ratingStar = ? WHERE ratingID = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'sii', $ratingDescription, $ratingStar, $ratingID);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: movie.php'); // Redirect to the movie page
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
}
?>

<!-- HTML form to edit review -->
<form action="edit_review.php?id=<?php echo $ratingID; ?>" method="post">
    <textarea name="ratingDescription"><?php echo htmlspecialchars($review['ratingDescription']); ?></textarea>
    <select name="ratingStar">
        <option value="5" <?php if ($review['ratingStar'] == 5) echo 'selected'; ?>>5 Stars</option>
        <option value="4" <?php if ($review['ratingStar'] == 4) echo 'selected'; ?>>4 Stars</option>
        <option value="3" <?php if ($review['ratingStar'] == 3) echo 'selected'; ?>>3 Stars</option>
        <option value="2" <?php if ($review['ratingStar'] == 2) echo 'selected'; ?>>2 Stars</option>
        <option value="1" <?php if ($review['ratingStar'] == 1) echo 'selected'; ?>>1 Star</option>
    </select>
    <button type="submit">Update Review</button>
</form>
