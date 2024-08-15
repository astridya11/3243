<?php
  include("auth.php");
  require(MODEL_PATH.'database.php');

  $allowedTypes = array('jpg', 'png');
  $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes

  $currentuserID = $_SESSION['userID'];
  $currentusername = $_SESSION['username'];
  $query = "SELECT * FROM users WHERE userName='$currentusername'";
  $result = mysqli_query($con, $query);
  $user = mysqli_fetch_assoc($result);

  // Fetch user information from the database
  $backgroundPic = $user['userBackgroundPic'] ? $user['userBackgroundPic'] : '../model/image/default-bg.png';
  $profilePic = $user['userProfilePic'] ? $user['userProfilePic'] : '../model/image/default-pic.png';
  $description = $user['userDescription'] ? $user['userDescription'] : "Say something here...";
  $gender = $user['userGender'] ? $user['userGender'] : "Male";
  $regdate = $user['userRegDate'];
  $userEmail = $user['userEmail'];
  $userPhoneNumber = $user['userPhoneNumber'] ? $user['userPhoneNumber'] : "";
  $userPassword = $user['userPassword'];

  // handle update basic information
  if(isset($_POST['newbasicinfo']) && $_POST['newbasicinfo']==1)
  {
    $currentusername = stripslashes($_REQUEST['username']);
    $currentusername = mysqli_real_escape_string($con,$currentusername);

    $gender = $_REQUEST['gender'];

    $description = stripslashes($_REQUEST['description']);
    $description = mysqli_real_escape_string($con,$description);

    $update="UPDATE users set userName='".$currentusername."', userGender='".$gender."', userDescription='".$description."' where userID ='".$currentuserID."'";
    if (mysqli_query($con, $update)) 
    {
        // if the query is successful, update the session variable
        $_SESSION['username'] = $currentusername;
    } else 
    {
        die(mysqli_error($con));
    }

    // files
    $targetDirectory = "../model/image/";
    if ($_FILES['profile-pic']['size'] > 0 || $_FILES['background-pic']['size'] > 0) 
    {
        if ($_FILES['profile-pic']['size'] > 0) {
            updateprofilepic($targetDirectory, $_FILES['profile-pic'], $currentuserID);
        }
    
        if ($_FILES['background-pic']['size'] > 0) {
            updatebackgroundpic($targetDirectory, $_FILES['background-pic'], $currentuserID);
        }

        // redirect user to same page to avoid form resubmission issue
        header("Location: ../view/profile.php?fileuploadstatus=$profilepicstatus $backgroundpicstatus");
        exit();
    }

    // no file is uploaded, back to normal profile.php
    header("Location: ../view/profile.php");
    exit();
  }

  // handle update account security
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

    // phone number not null
    if ($userPhoneNumber !== '') 
    {
        // phone number invalid
        if (!preg_match('/^\(\d{3}\) \d{3}-\d{4}$/', $userPhoneNumber) && 
        !preg_match('/^\(\d{3}\) \d{4}-\d{4}$/', $userPhoneNumber)) 
        {
            $phonenumberstatus = "Invalid phone number format. Please use the format (123) 456-7890 or (123) 4567-8901.";
        } 
        // phone number valid
        else 
        {
            $update = "UPDATE users SET userPhoneNumber='".$userPhoneNumber."' WHERE userID ='".$currentuserID."'";
            mysqli_query($con, $update) or die(mysqli_error($con));
        }
    }
    // phone number null, update phone number to empty
    else 
    {
        $update = "UPDATE users SET userPhoneNumber='".$userPhoneNumber."' WHERE userID ='".$currentuserID."'";
        mysqli_query($con, $update) or die(mysqli_error($con));
    }

    // no file is uploaded, back to normal profile.php
    header("Location: ../view/profile.php");
    exit();
  }

  function updateprofilepic($targetDirectory, $file, $currentuserID) 
  {
    global $con;
    global $allowedTypes;
    global $maxFileSize;
    global $profilepicstatus;

    $newProfilePic = $file['name'];
    $newProfilePicName = pathinfo($newProfilePic, PATHINFO_FILENAME);
    $newProfilePicType = strtolower(pathinfo($newProfilePic, PATHINFO_EXTENSION));

    // file type invalid
    if (!in_array($newProfilePicType, $allowedTypes)) 
    {
        $profilepicstatus =  "Profile picture update failed. Only JPG and PNG files are allowed.";
    }
    // file size over maximum limit
    else if ($file['size'] > $maxFileSize) 
    {
        $profilepicstatus =  "Profile picture update failed. Please choose a file below 2MB.";
    }
    // file type valid
    else
    {
        $targetProfilePicPath = $targetDirectory . $newProfilePicName . '_' . uniqid() . '.' . $newProfilePicType;
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

function updatebackgroundpic($targetDirectory, $file, $currentuserID) 
{
    global $con;
    global $allowedTypes;
    global $maxFileSize;
    global $backgroundpicstatus;

    $newBackgroundPic = $file['name'];
    $newBackgroundPicName = pathinfo($newBackgroundPic, PATHINFO_FILENAME);
    $newBackgroundPicType = strtolower(pathinfo($newBackgroundPic, PATHINFO_EXTENSION));

    // file type invalid
    if (!in_array($newBackgroundPicType, $allowedTypes)) {
        $backgroundpicstatus = "Background picture update failed. Only JPG and PNG files are allowed.";
    }
    else if ($file['size'] > $maxFileSize) 
    {
        $backgroundpicstatus =  "Background picture update failed. Please choose a file below 2MB.";
    }
    // file type valid
    else
    {
        $targetBackgroundPicPath = $targetDirectory . $newBackgroundPicName . '_' . uniqid() . '.' . $newBackgroundPicType;
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

// display alert message
// if (isset($profilepicstatus) && isset($backgroundpicstatus))
// {
//     echo "<script>alert('$profilepicstatus $backgroundpicstatus');</script>";
// }
// else if (isset($profilepicstatus))
// {
//     echo "<script>alert('$profilepicstatus');</script>";
// }
// else if (isset($backgroundpicstatus))
// {
//     echo "<script>alert('$backgroundpicstatus');</script>";
// }
if (isset($_GET['fileuploadstatus'])) {
    $statusMessage = htmlspecialchars($_GET['fileuploadstatus'], ENT_QUOTES, 'UTF-8');
    echo "<script>alert('$statusMessage');</script>";
}
?>