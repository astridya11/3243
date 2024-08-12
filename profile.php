<?php
  include("auth.php");
  require('database.php');

  $allowedTypes = array('jpg', 'png');

  $currentuserID = $_SESSION['userID'];
  $currentusername = $_SESSION['username'];
  $query = "SELECT * FROM users WHERE userName='$currentusername'";
  $result = mysqli_query($con, $query);
  $user = mysqli_fetch_assoc($result);

  // Fetch user information from the database
  $backgroundPic = $user['userBackgroundPic'] ? $user['userBackgroundPic'] : 'image/default-bg.png';
  $profilePic = $user['userProfilePic'] ? $user['userProfilePic'] : 'image/default-pic.png';
  $description = $user['userDescription'] ? $user['userDescription'] : "Say something here...";
  $gender = $user['userGender'] ? $user['userGender'] : "Male";
  $regdate = $user['userRegDate'];
  $userEmail = $user['userEmail'];
  $userPhoneNumber = $user['userPhoneNumber'] ? $user['userPhoneNumber'] : "";
  $userPassword = $user['userPassword'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7-beta.19/jquery.inputmask.min.js"></script>
    <script>
        $(document).ready(function()
        {
            $('#phone').inputmask({
                mask: ['(999) 999-9999', '(999) 9999-9999'],
                keepStatic: true
            });
        });
    </script>

    <style>
    * {box-sizing: border-box;}

    body { 
        margin: 0;
        font-family: 'Poppins';
        font-size: 15px;
        color: white;
        background-color: black;
    }

    /* navigation bar */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: black;
        padding: 0px 20px;
        height: 10vh;
    }

    .header a {
        color: white;
        text-align: center;
        text-decoration: none;
        font-size: 18px; 
        line-height: 25px;
        border-radius: 4px;
    }

    .header-left, .header-right {
        display: flex;
        align-items: center;
    }

    .header-left img,
    .header-right img {
        display: block;
    }

    .header-left img {
        height: 45px;
    }

    .header-right img {
        height: 25px;
    }

    /* profile info */
    .profile-info {
        position: relative;
        width: 100%;
        height: 50vh;
    }

    .profile-info img {
        width: 100%;
        height: 50vh;
        object-fit: cover;
        display: block;
    }

    /* .profile-info p {
        position: absolute;
        top: 100px;
        left: 300px;
        color: white;
        font-size: 30px;
        font-weight: bold;
    } */

    .profile-info .profilepic {
        position: absolute;
        bottom: -70px;
        left:100px;
        width: 150px;
        height: 150px;
        object-fit: cover;
        display: block;
    }

    .profile-info p
    {
        position: absolute;
        color: white;
        font-size: 15px;
        font-weight: normal;
    }

    .profile-info button
    {
        position: absolute;
        background-color: #f2b704;
        color: black;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 15px;
        cursor: pointer;
        z-index: 10;
    }

    .profile-info button:hover
    {
        background-color: #d89e00;
    }

    .profile-info .button-overlay {
        position: absolute;
        top: 20px;
        right: 20px;
        text-decoration: none;
        background-color: black;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 15px;
        cursor: pointer;
        z-index: 10;
    }

    .profile-info .button-overlay:hover {
        background-color: #605858;
    }

    .profile-info .list-button {
        position: absolute;
        bottom: -55px;
        padding: 10px 20px;
        background-color: #f2b704;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        color: black;
        font-size: 16px;
        cursor: pointer;
    }

    .profile-info .list-button:hover {
        background-color: #d89e00;
    }

    /*update section*/
    .update-profile {
        display: flex;
        width: 80%;
        margin: 0 auto;
        padding: 20px;
        background-color: black;
        margin-bottom: 30px;
        margin-top: 80px;
        border-radius: 10px;
        font-size: 15px;
        color: white;
    }

    .tab {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .tab div {
        padding: 10px;
        text-align: left;
        cursor: pointer;
        font-size: 16px;
    }

    .tab .active {
        font-weight: bold;
        font-size: 18px;
    }

    .update-info {
        flex: 3;
        padding: 10px;
        background-color: black;
    }

    .hidden {
        display: none;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .form-radio-group input {
        margin-top: 15px;
        margin-bottom: 20px;
    }

    .form-group button {
        padding: 10px 20px;
        margin-top: 20px;
        background-color: #f2b704;
        border: none;
        border-radius: 4px;
        color: black;
        font-size: 16px;
        cursor: pointer;
    }

    .form-group button:hover {
        background-color: #d89e00;
    }

    .deleteacc {
        padding: 10px 20px;
        background-color: red;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        color: black;
        font-size: 16px;
        cursor: pointer;
    }

    .deleteacc:hover {
        background-color: #b23c3c;
    }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <a href="movies_dashboard.php"><img src="image/logo-transparent.png"></a>
        </div>
        <div class="header-right">
            <a href="profile.php"><img src="image/profile-icon.png"></a>
        </div>
    </div>
    <div class="profile-info">
        <img src="<?php echo htmlspecialchars($backgroundPic); ?>" alt="Background Image">
        <a class="button-overlay" href="logout.php">Log Out</a>
        <p style="bottom: -28px; left: 300px; font-weight: bold; font-size: 30px;"><?php echo "$currentusername";?></p>
        <img class="profilepic" src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Image">
        <p style="bottom: -50px; left: 300px;"><?php echo "\" $description \"";?></p>
        <p style="bottom: -80px; left: 300px;"><?php echo "Gender: $gender";?></p>
        <p style="bottom: -80px; left: 450px;"><?php echo "Registration date: $regdate";?></p>

        <a class="list-button" style="right: 150px;" href="wishlist.php">Wishlist</a>
        <a class="list-button" style="right: 20px;" href="watchedlist.php">Watched</a>
    </div>
    <br>
    <div class="update-profile">
        <div class="tab">
            <div id="basic-info-tab" class="active">Basic Information</div>
            <div id="account-security-tab">Account Security</div>
        </div>

        <!-- basic information -->
        <?php
        if(isset($_POST['newbasicinfo']) && $_POST['newbasicinfo']==1)
        {
            $currentusername = stripslashes($_REQUEST['username']);
            $currentusername = mysqli_real_escape_string($con,$currentusername);

            $gender = $_REQUEST['gender'];

            $description = stripslashes($_REQUEST['description']);
            $description = mysqli_real_escape_string($con,$description);

            $update="UPDATE users set userName='".$currentusername."',
                userGender='".$gender."', userDescription='".$description."' where userID ='".$currentuserID."'";
            if (mysqli_query($con, $update)) {
                // if the query is successful, update the session variable
                $_SESSION['username'] = $currentusername;
            } else {
                die(mysqli_error($con));
            }

            // files
            $targetDirectory = "image/";
            if ($_FILES['profile-pic']['size'] > 0 || $_FILES['background-pic']['size'] > 0) {
                if ($_FILES['profile-pic']['size'] > 0) {
                    updateprofilepic($targetDirectory, $_FILES['profile-pic'], $currentuserID);
                }
    
                if ($_FILES['background-pic']['size'] > 0) {
                    updatebackgroundpic($targetDirectory, $_FILES['background-pic'], $currentuserID);
                }
            }
        }
        ?>
        <div id="basic-info" class="update-info">
            <form method='post' enctype='multipart/form-data'>
                <input type="hidden" name="newbasicinfo" value="1" />
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($currentusername); ?>">
                </div>
                <div class="form-radio-group">
                    <label for="gender">Gender</label><br>
                    <input type="radio" id="male" name="gender" value="Male" <?php echo ($gender === 'Male') ? 'checked' : ''; ?>>Male
                    <input type="radio" id="female" name="gender" value="Female" <?php echo ($gender === 'Female') ? 'checked' : ''; ?>>Female
                </div>
                <div class="form-group">
                    <label for="profile-pic">Profile Picture</label>
                    <input type="file" id="profile-pic" name="profile-pic">
                </div>
                <div class="form-group">
                    <label for="background-pic">Background Picture</label>
                    <input type="file" id="background-pic" name="background-pic">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($description); ?>">
                </div>
                <div class="form-group">
                    <button type="submit" name='updateBasic' value='UpdateBasic'>Update</button>
                </div>
                <?php
                    if (isset($profilepicstatus) && isset($backgroundpicstatus)) 
                    {
                        echo "<p style='color: red;'>$profilepicstatus $backgroundpicstatus</p>";
                    }
                    else if (isset($profilepicstatus))
                    {
                        echo "<p style='color: red;'>$profilepicstatus</p>";
                    }
                    else if (isset($backgroundpicstatus))
                    {
                        echo "<p style='color: red;'>$backgroundpicstatus</p>";
                    }
                ?>
            </form>
        </div>


        <!-- account security -->
        <?php
        if(isset($_POST['newaccsecurity']) && $_POST['newaccsecurity']==1)
        {
            $userEmail = stripslashes($_REQUEST['email']);
            $userEmail = mysqli_real_escape_string($con,$userEmail);

            $userPassword = stripslashes($_REQUEST['password']);;
            $userPassword = mysqli_real_escape_string($con,$userPassword);

            $update="UPDATE users set userEmail='".$userEmail."', userPassword='".md5($userPassword)."' where userID ='".$currentuserID."'";
            mysqli_query($con, $update) or die(mysqli_error($con));

            // phone number
            $userPhoneNumber = stripslashes($_REQUEST['phone']);;
            $userPhoneNumber = mysqli_real_escape_string($con,$userPhoneNumber);
            // phone number invalid
            if (!preg_match('/^\(\d{3}\) \d{3}-\d{4}$/', $userPhoneNumber) && 
                !preg_match('/^\(\d{3}\) \d{4}-\d{4}$/', $userPhoneNumber)) {
                $phonenumberstatus = "Invalid phone number format. Please use the format (123) 456-7890 or (123) 4567-8901.";
            } 
            // phone number valid
            else 
            {
                $update = "UPDATE users SET userPhoneNumber='".$userPhoneNumber."' WHERE userID ='".$currentuserID."'";
                mysqli_query($con, $update) or die(mysqli_error($con));
            }
        }
        ?>
        <div id="account-security" class="update-info hidden">
            <form method='post'>
            <input type="hidden" name="newaccsecurity" value="1" />
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userEmail); ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($userPhoneNumber); ?>" pattern="\(\d{3}\) \d{3}-\d{4}|\(\d{3}\) \d{4}-\d{4}" title="Phone number format: (123) 456-7890 or (123) 4567-8901">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($userPassword); ?>">
                </div>
                <div class="form-group">
                    <button type="submit" name='updateSecurity' value='updateSecurity' style="margin-bottom: 5px;">Update</button>
                </div>
                <div class="form-group">
                    <a class="deleteacc" href="delete_acc.php?userID=<?php echo $currentuserID; ?>" onclick="return confirm('Are you sure you want to delete account? This operation cannot be undo.')">Delete Account</a>
                </div>
                <?php
                    if (isset($phonenumberstatus)) 
                    {
                        echo "<p style='color: red;'>$phonenumberstatus</p>";
                    }
                ?>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('basic-info-tab').addEventListener('click', function() 
        {
            document.getElementById('basic-info').classList.remove('hidden');
            document.getElementById('account-security').classList.add('hidden');
            this.classList.add('active');
            document.getElementById('account-security-tab').classList.remove('active');
        });

        document.getElementById('account-security-tab').addEventListener('click', function() 
        {
            document.getElementById('account-security').classList.remove('hidden');
            document.getElementById('basic-info').classList.add('hidden');
            this.classList.add('active');
            document.getElementById('basic-info-tab').classList.remove('active');
        });
    </script>
</body>
</html>

<?php
    function updateprofilepic($targetDirectory, $file, $currentuserID) {
        global $con;
        global $allowedTypes;
        global $profilepicstatus;

        $newProfilePicName = $file['name'];
        $newProfilePicType = strtolower(pathinfo($newProfilePicName, PATHINFO_EXTENSION));
    
        // file type invalid
        if (!in_array($newProfilePicType, $allowedTypes)) {
            $profilepicstatus =  "Profile picture update failed. Only JPG and PNG files are allowed.";
        }
        // file type valid
        else
        {
            $targetProfilePicPath = $targetDirectory . $newProfilePicName;
            if (move_uploaded_file($file['tmp_name'], $targetProfilePicPath)) 
            {
                $selectQuery = "SELECT userProfilePic FROM users WHERE userID = '$currentuserID'";
                $result = mysqli_query($con, $selectQuery);
                $row = mysqli_fetch_assoc($result);
                $oldFilePath = $row['userProfilePic'];
    
                if ($oldFilePath && file_exists($oldFilePath) && !is_dir($oldFilePath)) 
                {
                    unlink($oldFilePath);
                }
    
                $updateFileQuery = "UPDATE users SET userProfilePic = '$targetProfilePicPath' WHERE userID = '$currentuserID'";
                mysqli_query($con, $updateFileQuery) or die(mysqli_error($con));
    
                $profilepicstatus = "Profile picture updated successfully.";
            } 
            else 
            {
                $profilepicstatus = "Profile picture update failed.";
            }
        }
    }
    
    function updatebackgroundpic($targetDirectory, $file, $currentuserID) {
        global $con;
        global $allowedTypes;
        global $backgroundpicstatus;

        $newBackgroundPicName = $file['name'];
        $newBackgroundPicType = strtolower(pathinfo($newBackgroundPicName, PATHINFO_EXTENSION));
    
        // file type invalid
        if (!in_array($newBackgroundPicType, $allowedTypes)) {
            $backgroundpicstatus = "Background picture update failed. Only JPG and PNG files are allowed.";
        }
        // file type valid
        else
        {
            $targetBackgroundPicPath = $targetDirectory . $newBackgroundPicName;
            if (move_uploaded_file($file['tmp_name'], $targetBackgroundPicPath)) 
            {
                $selectQuery = "SELECT userBackgroundPic FROM users WHERE userID = '$currentuserID'";
                $result = mysqli_query($con, $selectQuery);
                $row = mysqli_fetch_assoc($result);
                $oldFilePath = $row['userBackgroundPic'];
    
                if ($oldFilePath && file_exists($oldFilePath) && !is_dir($oldFilePath)) 
                {
                    unlink($oldFilePath);
                }
    
                $updateFileQuery = "UPDATE users SET userBackgroundPic = '$targetBackgroundPicPath' WHERE userID = '$currentuserID'";
                mysqli_query($con, $updateFileQuery) or die(mysqli_error($con));
    
                $backgroundpicstatus = "Background picture updated successfully.";
            } 
            else 
            {
                $backgroundpicstatus = "Background picture update failed.";
            }
        }
    }
?>

