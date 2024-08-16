<?php
  session_start();
  require(MODEL_PATH.'database.php');

  if (isset($_POST['username']))
  {
    $username = stripslashes($_REQUEST['username']);
    $username = mysqli_real_escape_string($con,$username);

    $password = stripslashes($_REQUEST['password']);
    $password = mysqli_real_escape_string($con,$password);

    $query = "SELECT *
          FROM `users`
          WHERE userName='$username'
          AND userPassword='".md5($password)."'";
        
    $result = mysqli_query($con,$query) or die(mysqli_error($con));
    // return the number of rows in result set
    $rows = mysqli_num_rows($result);
    $userData = mysqli_fetch_assoc($result);
        
    // profile exists
    if($rows == 1)
    {
      $_SESSION['username'] = $username;
      $_SESSION['userID'] = $userData["userID"];
      $_SESSION['userRole'] = $userData["userRole"];

      // header function must come with exit() or die()
      // die() is used to throw exception
      if($_SESSION['userRole'] == "admin"){
        header("Location: view/movies_dashboard.php");
      }else if($_SESSION['userRole'] == 'user'){
        header("Location: view/profile.php");
      }else{
        echo("Invalid user");
      }
      
      exit();
    }
    else
    {
      echo '<script>
              alert("Username/password is incorrect.\nClick OK to try again.");
          </script>';
    }
  }
?>