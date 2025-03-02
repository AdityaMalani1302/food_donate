<?php
session_start(); // Start the session
// error_log("Home.php - Session data: " . print_r($_SESSION, true));

// // Check if the user is logged in
// if (!isset($_SESSION['is_donor']) || $_SESSION['is_donor'] !== true) {
//   error_log("Unauthorized access attempt to home.php");
//   header("Location: signin.php"); // Redirect to login page
//   exit();
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Food Donate</title>
  <link rel="stylesheet" href="css/home.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
  <header>
    <div class="logo"><a href="home.php" style="text-decoration: none; color: inherit;">Food <b
          style="color: #06C167;">Donate</b></a></div>
    <div class="hamburger">
      <div class="line"></div>
      <div class="line"></div>
      <div class="line"></div>
    </div>
    <nav class="nav-bar">
      <ul>
        <li><a href="home.php" class="active">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
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
  <section class="banner">
    <!-- <a href="fooddonateform.php">Donate Food</a> -->
  </section>
  <div class="content" style="display: flex; justify-content: center; align-items: center; text-align: center;">
    <p style="font-size: 23px;">
      "Cutting food waste is a delicious way of saving money, helping to feed the world and protect the planet."
    </p>
  </div>
  <div class="photo">
    <br>
    <p class="heading">Our Works</p>
    <br>
    <p style="font-size: 28px; text-align: center;">"Look what we can do together."</p>
    <br>
    <div class="wrapper">
      <div class="box"><img src="img/p1.jpeg" alt=""></div>
      <div class="box"><img src="img/p4.jpeg" alt=""></div>
      <div class="box"><img src="img/p3.jpeg" alt=""></div>
    </div>
    <br>
    <p class="heading">OUR MISSION</p>
    <p class="para" style="font-size: 28px;">
      " Our Mission is to reduce food waste and combat hunger by creating an efficient food donation management system."
    </p>

    <div class="hero-section">
      <div class="hero-content">
        <h1>Fighting Hunger Together</h1>
        <p class="impact-stat">10,000+ Meals Served Every Month</p>
        <div class="cta-buttons">
          <a href="fooddonateform.php" class="btn btn-primary">Donate Now</a>
        </div>
      </div>
    </div>

    <div class="quick-info-section">
      <div class="container">
        <div class="info-cards">
          <div class="info-card">
            <i class="fas fa-hands-helping"></i>
            <h3>How It Works</h3>
            <p>Simple 3-step donation process</p>
          </div>
          <div class="info-card">
            <i class="fas fa-box-open"></i>
            <h3>What We Accept</h3>
            <p>View accepted food items</p>
          </div>
          <div class="info-card">
            <i class="fas fa-clock"></i>
            <h3>Urgent Needs</h3>
            <p>Current requirements</p>
          </div>
        </div>
      </div>
    </div>
    
    <div class="deli" style="display: grid;">
      <p class="heading">OUR GOAL</p>
      <p class="para" style="font-size: 28px; line-height: 1.6; color: #333; font-weight: 400; text-align: center; padding: 20px; margin: 0 auto; max-width: 900px; font-family: 'Poppins', sans-serif; letter-spacing: 0.5px; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">
        "<span style="color: #2B50AA; font-style: italic;">We aim to connect individuals, businesses, and organizations with surplus food to those in need, ensuring that no edible food goes to waste.</span>"
        <br><br>
        "<span style="color: #2B50AA; font-style: italic;">Through this platform, we strive to foster a sustainable, compassionate community where food resources are shared, and the impact on both the environment and society is minimized.</span>"
      </p>
    </div>
    <br>

    <img src="img/donation.jpg" alt="" style="margin-left:auto; margin-right: auto; width: 100%; height: 700px; object-fit: cover; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
    <br>
    <br>
    <footer class="footer" style="display: flex;">
      <div class="footer-left col-md-4 col-sm-6">
        <p class="about">
          <span> About us</span>The basic concept of this project Food Donate is to collect the excess/leftover
          food from donors such as hotels, restaurants,<br> etc and distribute to the needy people.
        </p>

      </div>
      <div class="footer-center col-md-4 col-sm-6">
        <div>
          <p><span> Contact</span> </p>

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


    <script src="script.js"></script>
</body>

</html>