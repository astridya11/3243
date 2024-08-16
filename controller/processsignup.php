<?php
      require(MODEL_PATH.'database.php');

      if (isset($_REQUEST['username']))
      {
        // check if username already exists in database
        $username = stripslashes($_REQUEST['username']);
        $username = mysqli_real_escape_string($con,$username);

        $checkquery = "SELECT *
          FROM `users`
          WHERE userName='$username'";
        $checkresult = mysqli_query($con,$checkquery) or die(mysqli_error($con));
        $checkrows = mysqli_num_rows($checkresult);

        // username exists, reject
        if($checkrows == 1)
        {
          echo '<script>
              alert("Username is taken.\nPlease choose another.");
          </script>';
        }
        // username doesn't exists, proceed to registration
        else
        {
          $userID = 'user' . date('YmdHis');

          $email = stripslashes($_REQUEST['email']);
          $email = mysqli_real_escape_string($con,$email);

          $password = stripslashes($_REQUEST['password']);
          $password = mysqli_real_escape_string($con,$password);

          $reg_date = date("Y-m-d");
          $role = "user";

          $query = "INSERT into `users` (userID, userName, userPassword, userEmail, userRegDate, userRole )
                    VALUES ('$userID', '$username', '".md5($password)."', '$email', '$reg_date', '$role')";
          $result = mysqli_query($con,$query);

          if($result)
          {
            echo '<script>
              alert("You are registered successfully.\nClick OK to go to the login page.");
              window.location.href = "../";
            </script>';
          }
          else
          {
            echo '<script>
              alert("Registration failed.\nClick OK to go to the registration page.");
            </script>';
          }
        }
      }
    ?>