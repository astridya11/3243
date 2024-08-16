<?php

class MoviesModel
{
    private $conn;

    public function __construct($servername, $username, $password, $dbname)
    {
        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function check_existing_movies($title, $dateReleased)
    {
        $stmt = $this->conn->prepare("SELECT * FROM movies WHERE title= ? AND dateReleased= ?");
        $stmt->bind_param("ss", $title, $dateReleased);
        $stmt->execute();
        $check_result = $stmt->get_result();

        $is_exist = $check_result->num_rows > 0;

        // Close the statement after getting the result
        $stmt->close();

        // Return the boolean result
        return $is_exist;
    }


    public function insert_movies($movieID, $title, $dateReleased, $duration, $genre, $language, $country, $director, $cast, $synopsis, $imageURL, $videoURL_1, $videoURL_2, $videoURL_3, $videoURL_4, $videoURL_5, $videoURL_6, $videoURL_7, $videoURL_8, $videoURL_9, $videoURL_10, $submittedBy)
    {
        $stmt = $this->conn->prepare("INSERT INTO movies (movieID, title, dateReleased, duration, genre, `language`, country, director, cast, synopsis, imageURL, videoURL_1, videoURL_2, videoURL_3, videoURL_4, videoURL_5, videoURL_6, videoURL_7, videoURL_8, videoURL_9, videoURL_10, submittedBy) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssssssssssssssssss", $movieID, $title, $dateReleased, $duration, $genre, $language, $country, $director, $cast, $synopsis, $imageURL, $videoURL_1, $videoURL_2, $videoURL_3, $videoURL_4, $videoURL_5, $videoURL_6, $videoURL_7, $videoURL_8, $videoURL_9, $videoURL_10, $submittedBy);

        return $stmt->execute();

        $stmt->close();
    }

    public function read_movies()
    {
        $sql = "SELECT * FROM movies ORDER BY movieID DESC";
        $result = $this->conn->query($sql);

        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['averageRating'] = $this->read_ratings($row['movieID']);
                $data[] = $row;
            }
        }
        return $data;
    }

    public function read_ratings($movieID)
    {
        $stmt = $this->conn->prepare("SELECT ratingStar FROM rating WHERE movieID = ?");
        $stmt->bind_param("s", $movieID);
        $stmt->execute();

        $rating_result = $stmt->get_result();

        $averageRating = 0;
        if ($rating_result) {
            $totalRatings = 0;
            $totalReviews = $rating_result->num_rows;

            while ($ratingRow = $rating_result->fetch_assoc()) {
                $totalRatings += $ratingRow['ratingStar'];
            }
            $averageRating = $totalReviews > 0 ? round($totalRatings / $totalReviews, 1) * 2 : 0;
        }

        $stmt->close();
        return $averageRating;
    }

    public function search_movies($search)
    {
        $likeSearch = "%" . $search . "%";

        $stmt = $this->conn->prepare("SELECT * FROM movies WHERE 
        title LIKE ? OR 
        genre LIKE ? OR 
        language LIKE ? OR 
        country LIKE ? OR 
        director LIKE ? OR 
        cast LIKE ?");

        $stmt->bind_param("ssssss", $likeSearch, $likeSearch, $likeSearch, $likeSearch, $likeSearch, $likeSearch);
        $stmt->execute();

        $search_result = $stmt->get_result();

        $search_data = [];
        if ($search_result->num_rows > 0) {
            while ($row = $search_result->fetch_assoc()) {
                $row['averageRating'] = $this->read_ratings($row['movieID']);
                $search_data[] = $row;
            }
        }

        $stmt->close();
        return $search_data;
    }

    public function read_movies_details($movieID)
    {
        $stmt = $this->conn->prepare("SELECT * FROM movies where movieID = ?");
        $stmt->bind_param("s", $movieID);
        $stmt->execute();

        $result = $stmt->get_result();
        $movieDetails = $result->fetch_assoc();

        $stmt->close();

        return $movieDetails;
    }

    public function update_movies($movieID, $title, $dateReleased, $duration, $genre, $language, $country, $director, $cast, $synopsis, $imageURL, $videoURL_1, $videoURL_2, $videoURL_3, $videoURL_4, $videoURL_5, $videoURL_6, $videoURL_7, $videoURL_8, $videoURL_9, $videoURL_10)
    {
        // Prepare the SQL statement with placeholders
        $stmt = $this->conn->prepare("UPDATE movies SET 
            title = ?, 
            dateReleased = ?, 
            duration = ?, 
            genre = ?, 
            language = ?, 
            country = ?, 
            director = ?, 
            cast = ?, 
            synopsis = ?, 
            imageURL = ?, 
            videoURL_1 = ?, 
            videoURL_2 = ?, 
            videoURL_3 = ?, 
            videoURL_4 = ?, 
            videoURL_5 = ?, 
            videoURL_6 = ?, 
            videoURL_7 = ?, 
            videoURL_8 = ?, 
            videoURL_9 = ?, 
            videoURL_10 = ? 
            WHERE movieID = ?");

        // Bind parameters
        $stmt->bind_param(
            'sssssssssssssssssssss',
            $title,
            $dateReleased,
            $duration,
            $genre,
            $language,
            $country,
            $director,
            $cast,
            $synopsis,
            $imageURL,
            $videoURL_1,
            $videoURL_2,
            $videoURL_3,
            $videoURL_4,
            $videoURL_5,
            $videoURL_6,
            $videoURL_7,
            $videoURL_8,
            $videoURL_9,
            $videoURL_10,
            $movieID
        );

        return $stmt->execute();
    }


    public function delete_movies($movieID)
    {
        $stmt = $this->conn->prepare("DELETE FROM movies where movieID = ?");
        $stmt->bind_param("s", $movieID);
        $stmt->execute();

        $stmt->close();
    }

    public function check_watched_list($movieID, $userID)
    {
        $stmt = $this->conn->prepare("SELECT * FROM watchedlist WHERE movieID= ? AND userID= ?");
        $stmt->bind_param("ss", $movieID, $userID);
        $stmt->execute();
        $check_result = $stmt->get_result();

        $is_in_list = $check_result->num_rows > 0;

        // Close the statement after getting the result
        $stmt->close();

        // Return the boolean result
        return $is_in_list;
    }

    public function insert_watched_list($movieID, $userID)
    {
        $watchedListID = 'watchedList' . date('YmdHis');

        $stmt = $this->conn->prepare("INSERT INTO watchedlist 
        (watchedListID, movieID, userID) values (?,?,?)");
        $stmt->bind_param("sss", $watchedListID, $movieID, $userID);

        return $stmt->execute();
    }

    public function check_wish_list($movieID, $userID)
    {
        $stmt = $this->conn->prepare("SELECT * FROM wishlist WHERE movieID= ? AND userID= ?");
        $stmt->bind_param("ss", $movieID, $userID);
        $stmt->execute();
        $check_result = $stmt->get_result();

        $is_in_list = $check_result->num_rows > 0;

        // Close the statement after getting the result
        $stmt->close();

        // Return the boolean result
        return $is_in_list;
    }

    public function insert_wish_list($movieID, $userID)
    {
        $wishlistID = 'wishlist' . date('YmdHis');

        $stmt = $this->conn->prepare("INSERT INTO wishlist 
        (wishlistID, movieID, userID) values (?,?,?)");
        $stmt->bind_param("sss", $wishlistID, $movieID, $userID);

        return $stmt->execute();
    }

    public function __destruct()
    {
        $this->conn->close();
    }
}
