<?php
session_start(); // Start the session
// error_log("Contact.php - Session data: " . print_r($_SESSION, true));

// // Check if the user is logged in
// if (!isset($_SESSION['is_donor']) || $_SESSION['is_donor'] !== true) {
//     error_log("Unauthorized access attempt to contact.php");
//     header("Location: signin.php"); // Redirect to login page
//     exit();
// }
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us</title>
  <link rel="stylesheet" href="css/home.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="chatbot/chatbot.css">


</head>


<body>
  <header>
    <div class="logo"><a href="home.php" style="text-decoration: none; color: inherit;">Food <b style="color: #06C167;">Donate</b></a></div>
    <div class="hamburger">
      <div class="line"></div>
      <div class="line"></div>
      <div class="line"></div>
    </div>
    <nav class="nav-bar">
      <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php" class="active">Contact</a></li>
        <?php if(isset($_SESSION['donor_email']) && isset($_SESSION['is_donor']) && $_SESSION['is_donor'] === true): ?>
          <!-- Show these links only when logged in -->
          <li><a href="donation.php">Donation</a></li>
          <li><a href="feedback_response.php">Feedback</a></li>
          <li><a href="profile.php">Profile</a></li>
        <?php else: ?>
          <!-- Show these buttons when not logged in -->
          <li><a href="signin.php" class="auth-btn">Login</a></li>
          <li><a href="signup.php" class="auth-btn">Sign Up</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>
  <script>
    hamburger = document.querySelector(".hamburger");
    hamburger.onclick = function() {
      navBar = document.querySelector(".nav-bar");
      navBar.classList.toggle("active");
    }
  </script>
  <section class="cover">

  </section>
  <p class="heading" style="margin: 20px;">Contact Us</p>

  <div class="contact-form">
    <!-- <div class="form-group">
      <select name="inquiry_type" required style="width: 100%; 
                                                          padding: 12px;
                                                          border: 1px solid #ccc;
                                                          border-radius: 4px;
                                                          background-color: #fff;
                                                          font-size: 16px;
                                                          color: #333;
                                                          cursor: pointer;
                                                          outline: none;
                                                          transition: border-color 0.3s ease;">
        <option value="">Select Inquiry Type</option>
        <option value="donation">Food Donation</option>
        <option value="volunteer">Volunteering</option>
        <option value="support">Support</option>
        <option value="other">Other</option>
      </select>
    </div><br> -->

    <?php if(isset($_SESSION['donor_email']) && isset($_SESSION['is_donor']) && $_SESSION['is_donor'] === true): ?>
        <!-- Show contact form only when logged in -->
        <form action="feedback.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name">
            <br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email">
            <br>
            <label for="message">Message:</label>
            <textarea id="message" name="message"></textarea>
            <br>
            <input type="submit" value="Send" name="send">
        </form>
    <?php else: ?>
        <!-- Show login prompt when not logged in -->
        <div class="login-prompt" style="text-align: center; padding: 20px; background: #f9f9f9; border-radius: 8px; margin: 20px 0;">
            <p style="margin-bottom: 15px; font-size: 16px;">Please login to send us a message</p>
            <a href="signin.php" class="auth-btn" style="text-decoration: none;">Login</a>
            <p style="margin-top: 15px; font-size: 14px;">Don't have an account? <a href="signup.php" style="color: #06C167; text-decoration: none;">Sign up here</a></p>
        </div>
    <?php endif; ?>
  </div>
  <div class="contact-info" style="padding: 10px;">
    <p>Email: fooddonate@gmail.com</p>
    <p>Phone: 9876543210</p>
    <p>Address: Sri Takshashila Gurukul</p>
  </div>
  <br><br>
<!-- 
  <div class="chatbot" style="padding: 30px; background-color: rgba(151, 243, 199, 0.5);">
    <p style="font-size: 23px; text-align: center;">Chat Bot Support <img src="bot-mini.png" alt="" height="20"></p>


    <div id="container" class="container">


      <div id="chat" class="chat">
        <div id="messages" class="messages"></div>
        <input id="input" type="text" placeholder="Say something..." autocomplete="off" />
      </div>

    </div> -->
    <br>
    <div class="help">
      <p style="font-size: 23px; text-align: center; padding:10px;">Help & FAQs?</p>

      <button class="accordion">How to Donate Food?</button>
      <div class="panel">
        <p>1)Click on <a href="fooddonateform.php">Donate</a> in Home Page </p>
        <p>2)Fill the Details </p>
        <p>3)Click on Submit</p>
        <img src="img/mobile.jpg" alt="" width="100%">
      </div>

      <button class="accordion">How will your Donation be used?</button>
      <div class="panel">
        <p style="padding: 10px;">Your donation will be used to support our mission and the various programs and
          initiatives that we have in place. Your contribution will help us continue providing assistance and support to
          those in need. You can find more information about our programs and initiatives on our website. If you have
          any specific questions or concerns, please feel free to contact us.</p>
      </div>

      <button class="accordion">What should I do if my food donation is near or past its expiration date?</button>
      <div class="panel">
        <p style="padding: 10px;">We appreciate your willingness to donate, but to ensure the safety of our clients, we
          cannot accept food that is near or past its expiration date. We recommend checking expiration dates before
          making a donation, or please contact us for further guidance.</p>
      </div>

      <button class="accordion">What types of food can I donate?</button>
      <div class="panel">
        <p style="padding: 10px;">We accept non-perishable food items, fresh produce, and packaged foods within their expiry date.</p>
      </div>

      <button class="accordion">How can I volunteer?</button>
      <div class="panel">
        <p style="padding: 10px;">Fill out our volunteer form or contact us directly. We have various opportunities available.</p>
      </div>
    </div>

  </div>

  <footer class="footer" style="display: flex;">
    <div class="footer-left col-md-4 col-sm-6">
      <p class="about">
        <span>About us</span>The basic concept of this project Food Donate is to collect the excess/leftover
        food from donors such as hotels, restaurants,<br> etc and distribute to the needy people.
      </p>

    </div>
    <div class="footer-center col-md-4 col-sm-6">
      <div>
        <p><span>Contact</span> </p>

      </div>
      <div>

        <p> (+91) 9876543210</p>

      </div>
      <div class="email-container">
        <i class="fa fa-envelope" style="font-size: 17px; color:white;"></i>
        <p style="margin: 0;"><a href="#"> fooddonate@gmail.com</a></p>
      </div>

      <div class="sociallist">
        <ul class="social">
          <li><a href=""><i class="fa fa-facebook" style="font-size:50px;color: black;"></i></a></li>
          <li><a href=""><i class="fa fa-twitter" style="font-size:50px;color: black;"></i></a></li>
          <li><a href=""><i class="fa fa-instagram" style="font-size:50px;color: black;"></i></a></li>
          <li><a href=""><i class="fa fa-whatsapp" style="font-size:50px;color: black;"></i></a></li>
        </ul>
      </div>
    </div>
    <div class="footer-right col-md-4 col-sm-6">
      <h2> Food<span> Donate</span></h2>
      <p class="menu">
        <a href="home.php"> Home</a> |
        <a href="about.php"> About</a> |
        <a href="profile.php"> Profile</a> |
        <a href="contact.php"> Contact</a>
      </p>
      <p class="name"> Food Donate &copy 2025</p>
    </div>
  </footer>


</body>
<script type="text/javascript" src="chatbot/chatbot.js"></script>
<script type="text/javascript" src="chatbot/constants.js"></script>
<script type="text/javascript" src="chatbot/speech.js"></script>
<script>
  var acc = document.getElementsByClassName("accordion");
  var i;

  for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
      this.classList.toggle("active");
      var panel = this.nextElementSibling;
      if (panel.style.maxHeight) {
        panel.style.maxHeight = null;
      } else {
        panel.style.maxHeight = panel.scrollHeight + "px";
      }
    });
  }
</script>

</html>