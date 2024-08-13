<?php
include("auth.php");
require('database.php');

//Fetch movie from database
$query = "SELECT * FROM movies;";
$result = mysqli_query($con, $query);

// Check if there are results
if ($result->num_rows > 0) {
    // Store results in an array
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Fetch ratings from database
        $ratingQuery = "SELECT ratingStar FROM rating WHERE movieID='" . $row['movieID'] . "' ";
        $ratingResult = mysqli_query($con, $ratingQuery);

        if ($ratingResult) {
            $totalRatings = 0;
            $ratingCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
            $totalReviews = mysqli_num_rows($ratingResult);

            while ($ratingRow = mysqli_fetch_assoc($ratingResult)) {
                $totalRatings += $ratingRow['ratingStar'];
            }
            $row['averageRating'] = $totalReviews > 0 ? round($totalRatings / $totalReviews, 1) * 2 : 0;
        } else {
            $row['averageRating'] = 0;
        }
        $data[] = $row;
    }
} else {
    $data = [];
}

function getColor($vote)
{
    if ($vote >= 8) {
        return 'green';
    } elseif ($vote >= 5) {
        return 'orange';
    } else {
        return 'red';
    }
}

// display based on genre

// sort and filter
// search

// when user select, linked to movies_details
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <title>Dashboard</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;700&display=swap");

        * {
            box-sizing: border-box;
        }

        :root {
            --primary-color: rgba(0, 0, 0, 0.925);
            --secondary-color: #373b69;
        }

        body {
            background-color: var(--primary-color);
            font-family: "Poppins", sans-serif;
            margin: 0;
            transition: 0.5s;
        }

        header {
            padding: 1rem;
            display: flex;
            justify-content: flex-end;
            /*align the search bar at right*/
            background-color: var(--secondary-color);
        }

        .search {
            background-color: white;
            border: 2px solid var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 1rem;
            font-family: inherit;
        }

        .search:focus {
            outline: 0;
            background-color: var(--secondary-color);
        }

        .search::placeholder {
            color: #7378c5;
        }

        main {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 0.3rem;
        }

        .movie {
            width: 19%;
            /*adjust how many movies in a row*/
            margin: 0.3rem;
            /*adjust how many movies in a row*/
            border-radius: 3px;
            box-shadow: 0.2px 4px 5px rgba(0, 0, 0, 0.1);
            background-color: var(--secondary-color);
            position: relative;
            overflow: hidden;
        }

        .movie img {
            width: 100%;
        }

        .movie-info {
            color: #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 1rem 1rem;
            letter-spacing: 0.5px;
        }

        .movie-info h3 {
            margin-top: 0;
        }

        .movie-info span {
            background-color: var(--primary-color);
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            font-weight: bold;
        }

        .movie-info span.green {
            color: lightgreen;
        }

        .movie-info span.orange {
            color: orange;
        }

        .movie-info span.red {
            color: red;
        }

        .overview {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #fff;
            padding: 1rem;
            max-height: 100%;
            transform: translateY(101%);
            transition: transform 0.3s ease-in;
        }

        .movie:hover .overview {
            transform: translateY(0);
        }

        .subscribe i:nth-child(1) {
            margin-top: 17px;
        }

        .subscribe i {
            font-size: 20px;
            margin-right: 20px;
        }

        .flex {
            display: flex;
        }

        .subscribe button {
            margin-left: 15px;
        }

        #searchbtn {
            width: 40px;
            height: 50px;
            line-height: 40px;
            text-align: center;
            border-radius: 50%;
            margin-right: 12px;
            color: white;
        }

        #playbtn {
            background: #f2b704;
            color: white;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            border-radius: 50%;
            margin: 0px 12px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <header>
        <div class="subscribe flex">
            <form id="form" class="form">
                <input type="text" placeholder="Search" id="search" class="search" />
            </form>
            <i id="searchbtn" class="fas fa-search"></i>
            <i id="playbtn" class="fas fa-user"></i>
        </div>
    </header>


    <main id="main">
        <?php foreach ($data as $item) : ?>
            <a href="movies_details.php?movieID=<?php echo urlencode($item['movieID']); ?>" class="movie">

                <img src="<?php echo $item['imageURL']; ?>" alt="<?php echo $item['title']; ?>">
                <div class="movie-info">
                    <h3><?php echo $item['title']; ?></h3>
                    <span class="<?php echo getColor($item['averageRating']); ?>"><?php echo $item['averageRating']; ?></span>
                </div>

                <div class="overview">
                    <h3>Overview</h3>
                    <?php echo $item['synopsis']; ?>
                </div>

            </a>

        <?php endforeach; ?>
    </main>

    <script>
        document.getElementById("playbtn").onclick = function() {
            window.location.href = "profile.php";
        };
    </script>

</body>

</html>