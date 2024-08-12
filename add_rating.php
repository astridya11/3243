<?php
include('auth.php');
require('database.php');

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit;
}

// Retrieve userID from session
$userID = $_SESSION['userID'];
if (isset($_REQUEST['movieID'])) {
    $movieID = $_REQUEST['movieID']; // Retrieve movieID from request
} else {
    echo "Error: movieID not provided!";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ratingID = 'rating' . date('YmdHis');
    $ratingStar = $_POST['ratingStar'];
    $ratingDescription = $_POST['ratingDescription'];

    $query = "INSERT INTO rating (ratingID, ratingDate, ratingTime, ratingDescription, ratingStar, userID, movieID )
              VALUES ('$ratingID', CURDATE(), NOW(), '$ratingDescription', '$ratingStar', '$userID', '$movieID')";
    //$stmt = mysqli_prepare($con, $query);
    // mysqli_stmt_bind_param($stmt, 'ssiis', $ratingStar, $ratingDescription);

    if (mysqli_query($con, $query)) {
        header("Location: movies_details.php?movieID=" . urlencode($movieID)); // Redirect to the movie page
        exit;
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins'>
    <link rel="stylesheet" href="rating_style.css">
    <title>Add Rating</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }

        .rating-box {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 90%;
        }

        .rating-box h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .rating-box form {
            display: flex;
            flex-direction: column;
        }

        .rating-box label {
            margin-bottom: 10px;
            font-weight: bold;
        }

        .rating-box select,
        .rating-box textarea {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }

        .rating-box button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .rating-box button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    
    <div class="rating-box">
        <h2>Add Your Rating</h2>
        <form method="POST" action="add_rating.php">
        <input type="hidden" name="movieID" value="<?php echo htmlspecialchars($movieID); ?>">
            <label for="ratingStar">Star Rating:</label>
            <select name="ratingStar" id="ratingStar" required>
                <option value="" disabled selected>Select your rating</option>
                <option value="1">1 Star</option>
                <option value="2">2 Stars</option>
                <option value="3">3 Stars</option>
                <option value="4">4 Stars</option>
                <option value="5">5 Stars</option>
            </select>

            <label for="ratingDescription">Your Review:</label>
            <textarea name="ratingDescription" id="ratingDescription" rows="5" placeholder="Write your review here..." required></textarea>

            <button type="submit">Submit Review</button>
        </form>
    </div>
</body>

</html>