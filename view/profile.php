<?php
    require_once ('../config.php');
    require (CONTROLLER_PATH."processprofile.php");
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="header.css" rel="stylesheet">

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
            <a href="movies_dashboard.php"><img src="../model/image/logo-transparent.png"></a>
        </div>
        <div class="header-right">
            <a href="profile.php"><img src="../model/image/profile-icon.png"></a>
        </div>
    </div>
    <div class="profile-info">
        <img id ="backgroundpicdisplay" src="<?php echo htmlspecialchars($backgroundPic); ?>" alt="Background Image">
        <a class="button-overlay" href="../controller/logout.php">Log Out</a>
        <p style="bottom: -28px; left: 300px; font-weight: bold; font-size: 30px;"><?php echo "$currentusername";?></p>
        <img class="profilepic" id="profilepicdisplay" src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Image">
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
                    <button type="submit" id='updateBasic' name='updateBasic' value='UpdateBasic'>Update</button>
                </div>
            </form>
        </div>


        <!-- account security -->
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
                    <button type="submit" id='updateSecurity' name='updateSecurity' value='updateSecurity' style="margin-bottom: 5px;">Update</button>
                </div>
                <div class="form-group">
                    <a class="deleteacc" href="../controller/delete_acc.php?userID=<?php echo $currentuserID; ?>" onclick="return confirm('Are you sure you want to delete account? This operation cannot be undo.')">Delete Account</a>
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