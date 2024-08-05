document.addEventListener('DOMContentLoaded', function() {
  var callApiButton = document.getElementById('callAPIButton');
  callApiButton.addEventListener('click', function(event) {
      event.preventDefault(); // Prevent the default action
      callAPI();
  });
});

const options = {
  method: "GET",
  headers: {
    accept: "application/json",
    Authorization: "Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI3ZDg0MTc1YjA3MGYzM2MyNTgzNzQzNTdjYmQ3YzE5OSIsIm5iZiI6MTcyMjE4OTIyNy43OTA0NjIsInN1YiI6IjY2YTY3OGI3OTk5OWZjMGI2Njg3MTE2YSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.4s5spVbAewIS2XCOVHgYg2mx5kYT_HhnZLpp-xPpqyo",
  },
};

function callAPI() {
  console.log("Calling API");

  const page = Math.floor(Math.random() * 100);

  fetch(`https://api.themoviedb.org/3/discover/movie?page=${page}&sort_by=popularity.desc`, options)
    .then((response) => response.json())
    .then((data) => {
      showMovies(data.results);
    })
    .catch((err) => console.error(err));
}

function showMovies(data) {

  const baseImageURL = "https://image.tmdb.org/t/p/w500";

  data.forEach((movie) => {
    const movieData = {
      id: movie.id,
      title: movie.title,
      dateReleased: movie.release_date,
      synopsis: movie.overview,
      imageURL: baseImageURL + movie.poster_path,
    };

    fetch(
      `https://api.themoviedb.org/3/movie/${movieData.id}?api_key=7d84175b070f33c258374357cbd7c199&append_to_response=videos,credits`,
      options
    )
      .then((response) => response.json())
      .then((details) => {
        getMovieDetails(details, movieData);
      })
      .catch((err) => console.error(err));
  });
}

function getMovieDetails(movie, movieData) {
  const baseVideoURL = "https://www.youtube.com/watch?v=";
  movieData.duration = formatRuntime(movie.runtime);
  movieData.genre = formatGenres(movie.genres);
  movieData.country = formatCountry(movie.production_countries);
  movieData.language = movie.spoken_languages[0]?.english_name || "Unknown";
  movieData.director = getDirectorName(movie.credits.crew);
  movieData.cast = getActorName(movie.credits.cast);
  movieData.videoURL_1 = movie.videos.results[0] ? baseVideoURL + movie.videos.results[0].key : null;
  movieData.videoURL_2 = movie.videos.results[1] ? baseVideoURL + movie.videos.results[1].key : null;
  movieData.videoURL_3 = movie.videos.results[2] ? baseVideoURL + movie.videos.results[2].key : null;
  movieData.videoURL_4 = movie.videos.results[3] ? baseVideoURL + movie.videos.results[3].key : null;
  movieData.videoURL_5 = movie.videos.results[4] ? baseVideoURL + movie.videos.results[4].key : null;
  movieData.videoURL_6 = movie.videos.results[5] ? baseVideoURL + movie.videos.results[5].key : null;
  movieData.videoURL_7 = movie.videos.results[6] ? baseVideoURL + movie.videos.results[6].key : null;
  movieData.videoURL_8 = movie.videos.results[7] ? baseVideoURL + movie.videos.results[7].key : null;
  movieData.videoURL_9 = movie.videos.results[8] ? baseVideoURL + movie.videos.results[8].key : null;
  movieData.videoURL_10 = movie.videos.results[9] ? baseVideoURL + movie.videos.results[9].key : null;
  

  console.log("MovieData", movieData);
  sendMovieData(movieData);
}

function sendMovieData(movieData) {
  $.ajax({
    type:'POST',
    url: "create_movies.php",
    data:{
      callAPI: 1,
      new: 1,
      data: movieData
    },
success: function(response){
  console.log("success");
},
error: function(repsosne){
  console.log("error",data)
}
});
  // fetch("create_movies.php", {
  //   method: "POST",
  //   headers: {
  //     "Content-Type": "application/json; charset=utf-8",
  //   },
  //   body: JSON.stringify(movieData),
  // })
  // .then(response => response.json())
  // .then(result => {
  //     if (result.status === "New Movie Inserted Successfully.") {
  //         console.log('Success:', result.status);
  //     } else {
  //         console.log('Error:', result.status); // Handle the case where the movie already exists
  //     }
  // })
  // .catch(error => {
  //     console.error('Error sending data:', error);
  // });
}

function formatRuntime(minutes) {
  const hours = Math.floor(minutes / 60);
  const remainingMinutes = minutes % 60;

  let formattedTime = "";
  if (hours > 0) {
    formattedTime += `${hours} ${hours === 1 ? "hr" : "hrs"}`;
  }
  if (remainingMinutes > 0) {
    if (hours > 0) {
      formattedTime += " ";
    }
    formattedTime += `${remainingMinutes} ${remainingMinutes === 1 ? "min" : "mins"}`;
  }
  return formattedTime;
}

function formatGenres(genres) {
  return genres.map((genre) => genre.name).join(", ");
}

function formatCountry(country) {
  return country.map((c) => c.name).join(", ");
}

function getDirectorName(crew) {
  const director = crew.find((member) => member.job === "Director");
  return director ? director.name : "Director not found.";
}

function getActorName(cast) {
  return cast
    .slice(0, 3)
    .map((actor) => actor.name)
    .join(", ");
}
