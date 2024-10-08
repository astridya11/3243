<?php
    require (CONTROLLER_PATH."processlogin.php");
?>
    
    <!DOCTYPE html>
    <html>
    <head>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Log In</title>
      <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
      <link href="view/header.css" rel="stylesheet">

      <style>
      * {box-sizing: border-box;}

      body { 
        margin: 0;
        font-family: 'Poppins';
        font-size: 15px;
        color: white;
        background-color: black;
      }

      .container{
        display: flex;
        flex-wrap: wrap;
        width: 100%;
        height: 90vh;
      }

      .container div{
        flex: 1;
      }

      .container .intro{
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        background-image: url('model/image/background.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
      }

      .intro img {
        height: 300px;
      }

      .container .log-in-container{
        display: flex;
        background-color: black;
        align-items: center;
        justify-content: center;
        flex-direction: column;
      }

      .log-in-form{
        padding-top: 10%;
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

      .forgot-password {
        font-size: 13px;
        text-align: end;
      }

      .go-to-signup {
        padding-bottom : 5%;
        font-family: 'Poppins';
        font-size: 13px;
        color: white;
      }

      .tosignup {
        color: #f2b704; 
        display: inline;
        font-size: 14px;
        font-weight: bolder;
        text-decoration: none;
      }

      .overlay-container { 
        display: none; 
        position: fixed; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%; 
        background: rgba(0, 0, 0, 0.6); 
        justify-content: center; 
        align-items: center; 
        opacity: 0; 
        transition: opacity 0.3s ease; 
      } 

      .forgot-form { 
        background: #fff; 
        padding: 20px; 
        padding-left: 30px;
        padding-right: 30px;
        border-radius: 12px; 
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.4); 
        width: 50%;
        text-align: center; 
        opacity: 0; 
        transform: scale(0.8); 
        animation: fadeInUp 0.5s ease-out forwards; 
      }
  
      .forgotpasswordForm { 
        display: flex; 
        flex-direction: column; 
      } 
  
      .form-label { 
        margin-bottom: 10px; 
        font-size: 15px; 
        color: #597445;
        text-align: left; 
      } 
  
      .form-input { 
        padding: 10px; 
        /* margin-bottom: 20px;  */
        border: 1px solid #ccc; 
        border-radius: 8px; 
        font-size: 15px; 
        color: #597445;
        width: 100%; 
        box-sizing: border-box; 
      } 
  
      .btn-submit, 
      .btn-close-popup { 
        font-size: 15px;
        width:100%;
        padding: 12px 24px; 
        border: none; 
        border-radius: 8px; 
        cursor: pointer; 
        transition: background-color 0.3s ease, color 0.3s ease; 
      } 
  
      .btn-submit { 
        background-color: green; 
        color: #fff; 
      } 
  
      .btn-close-popup { 
        margin-top: 12px; 
        background-color: #e74c3c; 
        color: #fff; 
      } 
  
      .btn-submit:hover { 
        background-color: #4caf50; 
      } 

      .btn-close-popup:hover {
        background-color: #f37062;
      }
  
      /* Keyframes for fadeInUp animation */ 
      @keyframes fadeInUp { 
        from { 
          opacity: 0; 
          transform: translateY(20px); 
        } 
  
        to { 
          opacity: 1; 
          transform: translateY(0); 
        } 
      } 
  
      /* Animation for popup */ 
      .overlay-container.show { 
        display: flex; 
        opacity: 1; 
      }
      </style>
    </head>

    <body>
      <div class="header">
        <div class="header-left">
          <a href="view/movies_dashboard.php"><img src="model/image/logo-transparent.png"></a>
        </div>
        <div class="header-right">
          <a href="view/profile.php"><img src="model/image/profile-icon.png"></a>
        </div>
      </div>
      <div class="container">
        <div class="intro">
          <img src="model/image/logo-transparent.png">
          <p style="font-size: 20px; font-weight:bolder; margin-top: -20px">Your movie partner, <span style="color: #f2b704;">Four Of Us</span>.</p>
        </div>
        <div class="log-in-container">
          <div class="log-in-form">
            <h1 style="font-size: 40px; font-weight:bold; margin-bottom: 0px;">Welcome Back ;)</h1>
            <h2 style="font-size: 16px; font-weight:bold; color:#f2b704;">Let us know who are you.</h2>

            <form id="loginform" action="" method="post">
            <label style="line-height: 2em;">Username</label><br>
            <input type="text" id="username" name="username" placeholder="Enter your username" required><br>
            <label style="line-height: 2em;">Password</label><br>
            <input type="password" id="password" name="password" placeholder="Enter your password" required><br><br>
            <button type="submit">Log In</button>
            </form>
            <!-- <p id="forgot-password" class="forgot-password" onclick="togglePopup()" style="cursor: pointer;">Forgot Password?</p> -->
          </div>
        
          <pre class="go-to-signup">Don't have an account? <a href="view/signup.php" class="tosignup">Register now.</a></pre>
        </div>
      </div>
      <div id="popupOverlay" class="overlay-container">
        <section class="forgot-form" id="forgot-form">
          <h2 style="color:#597445;">Forgot Password</h2>
          <form id="forgotpasswordForm" class="forgotpasswordForm" action="forgotpassword.php" method="POST">
          <label class="form-label" for="forgotEmail"> Email: </label> 
          <input class="form-input" type="email" id="forgotEmail" name="forgotEmail" placeholder="Email" required><br>
          <button class="btn-submit" type="submit">Reset Password</button>
          </form>
          <button class="btn-close-popup" onclick="togglePopup()"> Close </button> 
        </section>
      </div>
    
      <script>
        // display form when "Forgot Password?" button is clicked and close the form
        window.togglePopup = function() 
        { 
          const overlay = document.getElementById('popupOverlay'); 
          overlay.classList.toggle('show'); 
        };
      </script>
    </body>
  </html>
