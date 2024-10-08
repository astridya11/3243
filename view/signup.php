<?php
    require_once ('../config.php');
    require (CONTROLLER_PATH."processsignup.php");
?>

<!DOCTYPE html>
<html>
  <head>
    <base target="_top">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="header.css" rel="stylesheet">
    
    <style>
      * {box-sizing: border-box;}

      body { 
        margin: 0;
        font-family: 'Poppins';
        font-size: 15px;
        color: white;
        background-color: black;
      }

      .container {
        display: flex;
        flex-wrap: wrap;
        width: 100%;
        height: 90vh;
      }

      .container div {
        flex: 1;
      }

      .container .logo {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        background-image: url('../model/image/background.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
      }
      
      .logo img {
        height: 300px;
      }

      .container .sign-up-container {
        display: flex;
        background-color: black;
        align-items: center;
        justify-content: center;
        flex-direction: column;
      }

      .sign-up-form {
        padding-top: 5%;
        background-color: black;
      }

      input {
        width: 100%; 
        line-height: 1.5em; 
        margin-bottom: 5px;
        border-radius: 5px;
        border: none;
        padding: 3px 8px;
      }

      button {
        font-size: 15px; 
        width: 100%; 
        line-height: 1.5em; 
        background-color: #f2b704; 
        color: black;
        border-radius: 5px;
        border: none;
        padding-top: 3px;
        padding-bottom: 3px;
      }

      .go-to-login {
        padding-bottom : 5%;
        font-family: 'Poppins';
        font-size: 13px;
        color: white;
      }

      .tologin {
        color: #f2b704; 
        display: inline;
        font-size: 14px;
        font-weight: bolder;
        text-decoration: none;
      }

      @media screen and (max-width: 500px) {
        .header a {
          float: none;
          display: block;
          text-align: left;
        }
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
      <div class="container">
      <div class="logo">
        <img src="../model/image/logo-transparent.png">
        <p style="font-size: 20px; font-weight:bolder; margin-top: -20px">Your movie partner, <span style="color: #f2b704;">Four Of Us</span>.</p>
      </div>
      <div class="sign-up-container">
        <div class="sign-up-form">
          <h1 style="font-size: 40px; font-weight:bolder; margin-bottom: 0px;">Create Account</h1>
          <h2 style="font-size: 16px; font-weight:bold; color:#f2b704;">Join Us Now!</h2>
          <form id="signupform" action="" method="post">
          <label style="line-height: 2em;">Username</label><br>
          <input type="text" id="username" name="username" placeholder="Enter your username" required style="width: 100%"><br>
          <label style="line-height: 2em;">Email</label><br>
          <input type="email" id="email" name="email" placeholder="Enter your email" required style="width: 100%"><br>
          <label style="line-height: 2em;">Password</label><br>
          <input type="password" id="password" name="password" placeholder="Enter your password" required style="width: 100%">
          <!-- <label style="line-height: 2em;">Role</label><br>
          <input type="radio" id="admin" name="role" value="Admin" required> Admin
          <input type="radio" id="personal" name="role" value="Personal"> Personal -->
          <br><br>
          <button type="submit">Sign Up</button>
          </form>
        </div>
        <pre class="go-to-login">Already have an account? <a href="../" class="tologin">Login here.</a></pre>
      </div>
    </div>
  </body>
</html>
