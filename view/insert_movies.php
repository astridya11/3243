<?php
require_once('../config.php');
require(CONTROLLER_PATH . "create_movies_controller.php");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Insert New Movie</title>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="callAPI.js"></script>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap");

        body {
            background: linear-gradient(to right, rgba(34, 31, 31, 1) 0%, rgba(34, 31, 31, 0.4) 100%);
            margin: -10px -4px 0;
        }

        .container {
            max-width: 95%;
            margin: auto;
            margin-top: 10px;
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

        .form-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin: 12px 12px 0px;
            padding: 12px 0px;
            background-color: rgb(240, 240, 240);
        }


        .form-container div {
            width: 45%;
            box-sizing: border-box;
            margin: 10px 20px;
        }

        .button-container {
            display: flex;
            justify-content: space-evenly;
            margin: 0px 12px;
            padding: 10px 0px;
            background-color: rgb(240, 240, 240);
        }


        input[type="text"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            box-sizing: border-box;
            justify-content: center;
            background-color: #f2b704;
            padding: 15px 25px;
            font-size: 15px;
            font-weight: bold;
            border-radius: 8px;
            color: white;
            cursor: pointer;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .reminder {
            text-align: center;
            background-color: rgb(240, 240, 240);
            margin: 0px 12px;
            padding: 0px 12px 10px;
        }

        .status {
            color: #008000;
            background-color: rgb(240, 240, 240);
            margin: 0px 12px;
            padding: 10px 12px 20px;
            text-align: center;
        }

        @media (max-width: 860px) {
            .form-container div {
                width: 100%;
            }
        }

        header {
            height: 10vh;
            background: linear-gradient(to right, #f2b704 0%, rgba(34, 31, 31, 0.4) 100%);
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
            color: #161616;
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

        .subscribe i {
            font-size: 20px;
            margin-right: 20px;
        }

        #playbtn {
            background: #f2b704;
            color: white;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            border-radius: 50%;
            margin: 4px 12px;
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

        header.sticky {
            z-index: 10;
            position: fixed;
            top: 0;
            width: 100%;
            background: #f2b704;
            transition: 0.5s;
            height: 8vh;
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

        /* #loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid rgba(89, 59, 0, 100);
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
            margin: auto;
        }


        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        } */
    </style>
</head>

<body>
    <header>
        <div class="container">
            <div class="navbar flex1">
                <nav>
                    <ul id="menuitem">
                        <li><a href="../view/movies_dashboard.php">Movies Dashboard </a></li>
                    </ul>
                </nav>
                <span class="fa fa-bars" onclick="menutoggle()"></span>

                <div class="subscribe flex">
                    <i id="playbtn" class="fas fa-user"></i>
                </div>
            </div>
        </div>
    </header>
    <h1 style="color:white; margin-left: 12px;">Insert New Movies</h1>

    <form name="insert_form" style="margin-bottom: 0px;" method="post" action="../controller/create_movies_controller.php">
        <div class="form-container">
            <div>
                <label for="title">Movie Title</label>
                <input type="text" id="title" name="title" placeholder="Enter Movie Title" autocapitalize="characters" required />
            </div>
            <div>
                <label for="dateReleased">Date Released</label>
                <input type="text" id="dateReleased" name="dateReleased" pattern="\d{4}-\d{2}-\d{2}" placeholder="YYYY-MM-DD" required />
            </div>
            <div>
                <label for="duration">Duration</label>
                <input type="text" id="duration" name="duration" placeholder="eg. 2 hrs 45 mins" autocapitalize="none" required />
            </div>
            <div>
                <label for="genre">Genre</label>
                <input type="text" id="genre" name="genre" placeholder="eg. Action, Crime, Thriller, Comedy" autocapitalize="words" required />
            </div>
            <div>
                <label for="language">Language</label>
                <input type="text" id="language" name="language" placeholder="eg. English" autocapitalize="words" required />
            </div>
            <div>
                <label for="country">Country</label>
                <input type="text" id="country" name="country" placeholder="eg. United States of America" autocapitalize="words" required />
            </div>
            <div>
                <label for="director">Director</label>
                <input type="text" id="director" name="director" placeholder="eg. Adil El Arbi, Bilall Fallah" autocapitalize="words" required />
            </div>
            <div>
                <label for="cast">Cast</label>
                <input type="text" id="cast" name="cast" placeholder="eg. Will Smith, Martin Lawrence " autocapitalize="words" required />
            </div>
            <div>
                <label for="synopsis">Synopsis</label>
                <textarea id="synopsis" name="synopsis" rows="4" cols="50" required></textarea>
            </div>
            <div>
                <label for="imageURL">Image URL</label>
                <input type="text" id="imageURL" name="imageURL" placeholder="Enter Movie ImageURL" autocapitalize="none" required />
            </div>
            <div>
                <label for="videoURL_1">Trailer 1 (optional)</label>
                <input type="text" id="videoURL_1" name="videoURL_1" placeholder="Enter Movie Trailer 1" autocapitalize="none" />
            </div>
            <div>
                <label for="videoURL_2">Trailer 2 (optional)</label>
                <input type="text" id="videoURL_2" name="videoURL_2" placeholder="Enter Movie Trailer 2" autocapitalize="none" />
            </div>
            <div>
                <label for="videoURL_3">Trailer 3 (optional)</label>
                <input type="text" id="videoURL_3" name="videoURL_3" placeholder="Enter Movie Trailer 3" autocapitalize="none" />
            </div>
            <div>
                <label for="videoURL_4">Trailer 4 (optional)</label>
                <input type="text" id="videoURL_4" name="videoURL_4" placeholder="Enter Movie Trailer 4" autocapitalize="none" />
            </div>
            <div>
                <label for="videoURL_5">Trailer 5 (optional)</label>
                <input type="text" id="videoURL_5" name="videoURL_5" placeholder="Enter Movie Trailer 5" autocapitalize="none" />
            </div>
            <div>
                <label for="videoURL_6">Trailer 6 (optional)</label>
                <input type="text" id="videoURL_6" name="videoURL_6" placeholder="Enter Movie Trailer 6" autocapitalize="none" />
            </div>
            <div>
                <label for="videoURL_7">Trailer 7 (optional)</label>
                <input type="text" id="videoURL_7" name="videoURL_7" placeholder="Enter Movie Trailer 7" autocapitalize="none" />
            </div>
            <div>
                <label for="videoURL_8">Trailer 8 (optional)</label>
                <input type="text" id="videoURL_8" name="videoURL_8" placeholder="Enter Movie Trailer 8" autocapitalize="none" />
            </div>
            <div>
                <label for="videoURL_9">Trailer 9 (optional)</label>
                <input type="text" id="videoURL_9" name="videoURL_9" placeholder="Enter Movie Trailer 9" autocapitalize="none" />
            </div>
            <div>
                <label for="videoURL_10">Trailer 10 (optional)</label>
                <input type="text" id="videoURL_10" name="videoURL_10" placeholder="Enter Movie Trailer 10" autocapitalize="none" />
            </div>
            <!-- <div id="loader" style="display: none;"></div> -->
            <p class="status"><?php echo $status; ?></p>
        </div>
        <div class="button-container">
            <input id="manual" name="manual" type="submit" value="Submit" />
            <input name="callAPI" type="submit" value="Call API" id="callAPIButton" />
        </div>

    </form>
    <script>
        document.getElementById("playbtn").onclick = function() {
            window.location.href = "../view/profile.php";
        };
        // document.getElementById("loader").style.display = "none";
    </script>
</body>

</html>