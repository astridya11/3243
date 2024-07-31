<?php
session_start();
require('database.php');

// Check if the user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

// Redirect if no movie ID is provided
if(isset($_GET['get_id'])){
    $get_id = $_GET['get_id'];
} else {
    $get_id = '';
    header('location:movie.php');
}

// Handle form submission
if(isset($_POST['submit'])){
    if($user_id != ''){
        $id = create_unique_id();
        $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
        $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $rating = filter_var($_POST['rating'], FILTER_SANITIZE_STRING);
        $ratingDate = date('Y-m-d');
        $ratingTime = date('H:i:s');

        // Check if the review already exists
        $verify_review = $conn->prepare("SELECT * FROM `reviews` WHERE post_id = ? AND user_id = ?");
        $verify_review->execute([$get_id, $user_id]);

        if($verify_review->rowCount() > 0){
            $warning_msg[] = 'Your review has already been added!';
        } else {
            // Add the new review
            $add_review = $conn->prepare("INSERT INTO `reviews`(id, post_id, user_id, rating, title, description, ratingDate, ratingTime) VALUES(?,?,?,?,?,?,?,?)");
            $add_review->execute([$id, $get_id, $user_id, $rating, $title, $description, $ratingDate, $ratingTime]);
            $success_msg[] = 'Review added!';
        }
    } else {
        $warning_msg[] = 'Please log in first!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add Review</title>

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<!-- Header Section Starts -->
<?php include 'components/header.php'; ?>
<!-- Header Section Ends -->

<!-- Add Review Section Starts -->
<section class="account-form">
   <form action="" method="post">
      <h3>Post Your Review</h3>
      <p class="placeholder">Review Title <span>*</span></p>
      <input type="text" name="title" required maxlength="50" placeholder="Enter review title" class="box">
      <p class="placeholder">Review Description</p>
      <textarea name="description" class="box" placeholder="Enter review description" maxlength="1000" cols="30" rows="10"></textarea>
      <p class="placeholder">Review Rating <span>*</span></p>
      <select name="rating" class="box" required>
         <option value="1">1</option>
         <option value="2">2</option>
         <option value="3">3</option>
         <option value="4">4</option>
         <option value="5">5</option>
      </select>
      <input type="submit" value="Submit Review" name="submit" class="btn">
      <a href="view_post.php?get_id=<?= $get_id; ?>" class="option-btn">Go Back</a>
   </form>
</section>
<!-- Add Review Section Ends -->

<!-- Sweetalert CDN Link -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- Custom JS File Link -->
<script src="js/script.js"></script>

<?php include 'components/alerts.php'; ?>

</body>
</html>
