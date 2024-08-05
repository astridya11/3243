<?php
include("auth.php");
require('database.php');

// retrive movie info
$movieID = $_REQUEST['movieID'];
$query = "SELECT * FROM movies where movieID='" . $movieID . "'";
$result = mysqli_query($con, $query) or die(mysqli_error($con));
$row = mysqli_fetch_assoc($result);

$genresArray = explode(", ", $row['genre']);

$videoURLs = [
    $row['videoURL_1'],
    $row['videoURL_2'],
    $row['videoURL_3'],
    $row['videoURL_4'],
    $row['videoURL_5'],
    $row['videoURL_6'],
    $row['videoURL_7'],
    $row['videoURL_8'],
    $row['videoURL_9'],
    $row['videoURL_10'],

];

// Function to transform URL
function getEmbedUrl($url)
{
    // Parse the URL to get the query parameters
    $queryString = parse_url($url, PHP_URL_QUERY);
    parse_str($queryString, $queryParams);
    // Extract the video ID
    $videoID = $queryParams['v'];
    // Construct the embedded URL
    return "https://www.youtube.com/embed/$videoID";
}

if (isset($_POST['watchedButton'])) {
    $watchedListID = 'watchedList' . date('YmdHis');
    $userID = $_SESSION["userID"];

    // check whether already in watchedlist
    $check_query = "SELECT * FROM watchedlist WHERE movieID='$movieID' AND userID='$userID'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Movie is already in your watchedlist!');</script>";
    } else {

        $ins_query = "INSERT INTO watchedlist 
        (watchedListID, movieID, userID) values
        ('$watchedListID', '$movieID', '$userID')";


        if (mysqli_query($con, $ins_query)) {
            echo "<script>alert('Movie successfully added to your watched list!');</script>";
        } else {
            die(mysqli_error($con));
        }
    }
}


if (isset($_POST['wishlistButton'])) {
    $wishlistID = 'wishlist' . date('YmdHis');
    $userID = $_SESSION["userID"];

    // check whether already in wishlist
    $check_query = "SELECT * FROM wishlist WHERE movieID='$movieID' AND userID='$userID'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo '<script>
        alert("Movie is already in your wishlist!");
        window.location.href = "movies_details.php";
        </script>';
    } else {


        $ins_query = "INSERT INTO wishlist 
        (wishlistID, movieID, userID) values
        ('$wishlistID', '$movieID', '$userID')";

        if (mysqli_query($con, $ins_query)) {
            echo '<script>
            alert("Movie successfully added to your wishlist!");
             window.location.href = "movies_details.php";
            </script>';
        } else {
            die(mysqli_error($con));
        }
    }
}



// rating section

// feedback section

// if user role is admin, show delete button
// linked to delete_movies

// if user role is admin, show update button
// linked to update_movies


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <title>STREAMLAb</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="moviestyle.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <!-- Owl Carousel css-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <!-- Owl Carousel css-->

    <!-- jquery css-->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <!-- jquery css-->

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap");

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            background: #161616;
            font-family: "Poppins", sans-serif;
        }

        /*---------global------*/
        .container {
            max-width: 95%;
            margin: auto;
            margin-top: 5px;
        }

        a {
            text-decoration: none;
            transition: 0.5s;
        }

        img {
            width: 100%;
            height: 100%;
        }

        ul {
            list-style: none;
        }

        .flex {
            display: flex;
        }

        .flex1 {
            display: flex;
            justify-content: space-between;
        }

        i {
            cursor: pointer;
            transition: 0.5s;
        }

        /*---------global------*/
        /*---------header------*/
        .home {
            color: white;
            background-image: url(<?php echo $row['imageURL']; ?>);
            background-repeat: no-repeat;
            background-position: right;
            background-size: contain;
            height: 100vh;
            width: 100%;
        }

        .logo img {
            width: 250px;
        }

        .headerbg {
            height: 100vh;
            width: 100%;
            background: linear-gradient(to right,
                    rgba(34, 31, 31, 1) 0%,
                    rgba(34, 31, 31, 0.4) 100%);
        }

        header {
            height: 10vh;
            background: rgba(0, 0, 0, 0.2);
        }

        header .navbar {
            align-items: center;
            transition: 0.5s;
        }

        header nav ul {
            display: inline-block;
            font-size: 16px;
        }

        header nav ul li a {
            color: white;
            transition: all 300ms ease-in-out;
        }

        header nav ul li a:hover {
            color: #f2b704;
        }

        header nav ul li {
            margin-right: 20px;
            display: inline-block;
        }

        header .navbar span {
            text-align: center;
            margin-left: 20px;
            color: black;
            font-size: 25px;
            display: none;
        }

        .search {
            background-color: white;
            border: 2px solid var(--primary-color);
            padding: 0.2rem 1rem;
            margin: 0.5rem 0;
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

        .subscribe i:nth-child(1) {
            margin-top: 17px;
        }

        .subscribe i {
            font-size: 20px;
            margin-right: 20px;
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
        }

        button {
            background: #f2b70485;
            border: none;
            outline: none;
            color: white;
            padding: 0px 30px;
            cursor: pointer;
            font-weight: bold;
            font-size: 15px;
        }

        .subscribe button {
            margin-left: 15px;
        }

        .subscribe_flex {
            /*adjust the toolbar search and user button*/
            width: 100%;
            justify-content: flex-end;
            /* Align items to the right */
            margin-left: 1080px;
        }

        .subscribe_flex i {
            margin-left: 18px;
            /* Optional: space between icons */
        }

        header.sticky .headerbg {
            position: relative;
        }

        header.sticky {
            z-index: 10;
            position: fixed;
            top: 0;
            width: 100%;
            background: #f2b704;
            transition: 0.5s;
            height: 10vh;
            box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.1);
            transition: 0.5s;
        }

        header.sticky .subscribe {
            display: none;
        }

        @media only screen and (max-width: 768px) {
            header nav ul {
                position: absolute;
                top: 100px;
                left: 0;
                width: 100%;
                background: #f2b704;
                overflow: hidden;
                transition: max-height 0.5s;
                z-index: 100;
            }

            header nav ul li {
                display: block;
                margin: 50px;
                margin-top: 10px;
                margin-bottom: 10px;
            }

            header .navbar span {
                color: white;
                display: block;
                cursor: pointer;
            }

            .home {
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
                height: 70vh;
            }

            .subscribe {
                display: none;
            }
        }

        /*---------home_content------*/
        .left,
        .right {
            width: 50%;
        }

        .top {
            /*adjust distance between section*/
            margin-top: 5%;
        }

        .rate {
            /*adjust distance between section*/
            margin-top: 12%;
        }

        .mtop {
            /*adjust distance between section*/
            margin-top: 5%;
        }

        h1 {
            font-size: 60px;
            letter-spacing: 2px;
            line-height: 90px;
            text-transform: uppercase;
        }

        .time {
            margin: 10px 0px;
            line-height: 40px;
        }

        .time div {
            font-size: 15px;
            line-height: 30px;
            margin-bottom: 10px;
        }

        .time span {
            font-size: 15px;
            font-weight: normal;
            margin-left: 8px;
        }

        .time label {
            width: 40px;
            height: 40px;
            border: 1px solid white;
            text-align: center;
            line-height: 40px;
        }

        .time i {
            font-size: 5px;
            line-height: 40px;
            margin-left: 20px;
            margin-right: 20px;
        }

        .home_content p {
            font-size: 20px;
            font-weight: bold;
        }

        .watchedButton {
            background: #f2b704;
            padding: 15px 25px;
            margin-right: 20px;
            font-size: 15px;
            font-weight: bold;
            border-radius: 8px;
            color: white;
        }

        .wishlistButton {
            background: transparent;
            padding: 15px 25px;
            margin-right: 20px;
            font-size: 15px;
            font-weight: bold;
            border-radius: 8px;
            color: white;
        }

        .home_content #playbtn {
            width: 100px;
            height: 60px;
            line-height: 60px;
            background: transparent;
            color: white;
            display: inline-flex;
            align-items: center;
        }

        .home_content #playbtn span {
            margin-left: 8px;
        }

        .button {
            margin-top: 20px;
        }

        .button p {
            margin-top: 12px;
            margin-left: 20px;
            font-size: 18px;
        }

        /*---------header------*/
        /*---------popular------*/
        .heading h2 {
            font-size: 25px;
            font-weight: 400;
            color: white;
        }

        .heading {
            margin-bottom: 50px;
            /*original 30px*/
        }

        .heading button {
            padding: 15px 50px;
        }

        .popular {
            color: white;
        }

        /*---------image_hover------*/
        .popular .box {
            position: relative;
            background: #221f1f;
            width: 100%;
            cursor: pointer;
        }

        .popular .box .imgBox {
            position: relative;
            height: 60vh;
            overflow: hidden;
        }

        .popular .box .imgBox img {
            width: 100%;
            height: 100%;
            transition: 0.5s;
        }

        .popular .box:hover .imgBox img {
            opacity: 0.6;
            transform: scale(1.2);
        }

        .popular .box .content {
            position: absolute;
            width: 100%;
            top: 30%;
            left: 40%;
            z-index: 2;
        }

        .owl-s .dots span {
            font-size: 20px;
            color: white;
        }

        .owl-nav span {
            font-size: 50px;
            color: white;
        }

        .owl-prev,
        .owl-next {
            position: absolute;
            top: 25%;
        }

        .owl-prev {
            left: -20px;
        }

        .owl-next {
            right: -20px;
        }

        /*---------popular------*/
        /*---------new_realase------*/
        .new_realase {
            color: white;
        }

        .heading h2 {
            margin-bottom: 20px;
        }

        .heading h2 span {
            border-left: 3px solid #f2b704;
            margin-right: 30px;
            font-weight: bold;
        }

        .time h3 {
            font-size: 50px;
        }

        .new_realase .time img {
            width: 60px !important;
            height: 60px;
            margin-top: -10px;
            margin-right: 10px;
        }

        .new_realase .items {
            border-left: 3px solid #f2b704;
            padding: 40px;
            height: 85vh;
        }

        .new_realase .img {
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0px;
            top: 0;
            z-index: -1;
        }

        .new_realase .items {
            width: 100%;
            background: linear-gradient(to right,
                    rgba(34, 31, 31, 1) 0%,
                    rgba(34, 31, 31, 0.4) 100%);
        }

        .new_realase p {
            font-size: 18px;
            line-height: 35px;
        }

        /*---------new_realase------*/
        @media only screen and (max-width: 768px) {
            .left {
                width: 100%;
            }

            .new_realase .items {
                height: 65vh;
            }
        }

        @media only screen and (max-width: 511px) {
            header nav ul {
                top: 70px;
            }

            h1 {
                font-size: 30px;
            }

            .time p {
                margin-top: 10px;
            }

            .left p {
                font-size: 12px;
                line-height: 20px;
            }

            .time button {
                padding: 5px 10px;
            }

            .heading {
                margin-top: 0px;
            }

            .owl-prev {
                left: 20px;
            }

            .owl-next {
                right: 20px;
            }

            .new_realase .items {
                height: 80vh;
            }

            .new_realase .button button {
                padding: 10px;
            }

            .new_realase .time label,
            .new_realase .time img,
            .new_realase .time p {
                margin-right: 10px;
                margin-left: 10px;
            }

            .new_realase .time button {
                display: none;
            }

            .new_realase .time i {
                display: none;
            }
        }
    </style>
</head>

<body>
    <section class="home" id="title">
        <div class="headerbg">
            <header>
                <div class="container">
                    <div class="navbar flex1">
                        <nav>
                            <ul id="menuitem">
                                <li><a href="#title"><?php echo $row['title']; ?></a></li>
                                <li><a href="#ratings">Ratings</a></li>
                                <li><a href="#trailer">Trailer</a></li>
                                <li><a href="#feedback">Feedback</a></li>
                                <!-- link to min min de profile -->
                                <li><a href="profile.html">Profile</a></li>
                            </ul>
                        </nav>
                        <span class="fa fa-bars" onclick="menutoggle()"></span>

                        <div class="subscribe flex">
                            <form id="form" class="form">
                                <input type="text" placeholder="Search" id="search" class="search" />
                            </form>
                            <i id="searchbtn" class="fas fa-search"></i>
                            <i id="playbtn" class="fas fa-user"></i>
                        </div>
                    </div>
                </div>
            </header>

            <div class="home_content">
                <div class="container">
                    <div class="left">
                        <h1><?php echo $row['title']; ?></h1>

                        <div class="time flex">
                            <span><?php echo $row['duration']; ?></span>
                            <i class="fas fa-circle"></i>
                            <p><?php echo $row['dateReleased']; ?></p>
                            <i class="fas fa-circle"></i>
                            <p><?php echo $row['language']; ?></p>
                        </div>

                        <div class="time flex">
                            <?php
                            // Loop through the genres array and generate HTML
                            $count = count($genresArray);
                            foreach ($genresArray as $index => $genre) {
                                echo "<button>$genre</button>";
                                if ($index < $count - 1) {
                                    echo '<i class="fas fa-circle"></i>';
                                }
                            }
                            ?>
                        </div>

                        <div class="time">

                            <p>Overview</p>
                            <div><?php echo $row['synopsis']; ?></div>
                        </div>
                        <div class="time">
                            <p>Director: <span><?php echo $row['director']; ?></span></p>
                            <p>Cast: <span><?php echo $row['cast']; ?><span></p>
                        </div>

                        <form method="POST" class="button flex">
                            <input type="submit" name="watchedButton" class="watchedButton" value="WATCHED" />
                            <input type="submit" name="wishlistButton" class="wishlistButton" value="WISHLIST" />
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <script>
        var menuitem = document.getElementById("menuitem");
        menuitem.style.maxHeight = "0px";

        function menutoggle() {
            if (menuitem.style.maxHeight == "0px") {
                menuitem.style.maxHeight = "200px";
            } else {
                menuitem.style.maxHeight = "0px";
            }
        }

        window.addEventListener("scroll", function() {
            var header = document.querySelector("header");
            header.classList.toggle("sticky", window.scrollY > 50);
        });
    </script>

    <section class="new_realase rate" id="ratings">
        <div class="container">
            <div class="items">
                <div class="left"></div>
            </div>
        </div>
    </section>

    <section class="popular mtop" id="trailer">
        <div class="container">
            <div class="heading flex1">
                <h2>Trailers</h2>
                <a href="movies_dashboard.php"><button>MORE MOVIES</button></a>
            </div>

            <div class="owl-carousel owl-theme">
                <?php foreach ($videoURLs as $key => $videoURL) : ?>
                    <div class="item">
                        <div class="box">
                            <div class="imgBox">
                                <iframe width="100%" height="100%" src="<?php echo getEmbedUrl($videoURL); ?>"></iframe>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Owl Carousel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js"></script>
    <!-- Owl Carousel -->

    <script>
        $(".owl-carousel").owlCarousel({
            loop: true,
            margin: 20,
            nav: true,
            dots: false,
            responsive: {
                411: {
                    items: 1,
                },
                768: {
                    items: 2,
                },
                1000: {
                    items: 4,
                },
            },
        });
    </script>

    <section class="new_realase top" id="feedback">
        <div class="container">
            <div class="owl-carousel owl-carousel2 owl-theme">
                <div class="items">
                    <div class="left">
                        <div class="img">
                            <img src="image/p2.jpg" alt="" />
                        </div>
                        <div class="heading">
                            <h2><span></span> NEW REALEASE</h2>
                            <h1>THE WARRIOR LIFE</h1>
                        </div>
                        <div class="time flex">
                            <label>R</label>
                            <i class="fas fa-circle"></i>
                            <span>1hrs 50mins</span>
                            <i class="fas fa-circle"></i>
                            <a class="flex1"><img src="https://img.icons8.com/color/95/000000/imdb.png" />
                                8.5</a>
                            <i class="fas fa-circle"></i>
                            <p>2021</p>
                            <i class="fas fa-circle"></i>
                            <button>Action</button>
                        </div>

                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                            eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                            enim ad minim veniam, quis nostrud exercitation ullamco laboris
                            nisi ut aliquip ex ea commodo consequat.
                        </p>

                        <div class="button flex">
                            <button class="btn">PLAY NOW</button>
                            <i id="playbtn" class="fas fa-play"></i>
                            <p>WATCH TRAILER</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(".owl-carousel2").owlCarousel({
            loop: true,
            margin: 20,
            dots: true,
            items: 1,
        });
    </script>

    <!-- paxi mate ko lai aata hai-->
    <script>
        $(".owl-carousel").owlCarousel({
            loop: true,
            margin: 20,
            nav: true,
            dots: false,
            responsive: {
                414: {
                    items: 1,
                },
                600: {
                    items: 2,
                },
                1000: {
                    items: 4,
                },
            },
        });
    </script>
</body>

</html>