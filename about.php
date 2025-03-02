<?php
session_start(); // Start the session
// error_log("About.php - Session data: " . print_r($_SESSION, true));

// // Check if the user is logged in
// if (!isset($_SESSION['is_donor']) || $_SESSION['is_donor'] !== true) {
//     error_log("Unauthorized access attempt to about.php");
//     header("Location: signin.php"); // Redirect to login page
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About</title>
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
        <li><a href="home.php">Home</a></li>
        <li><a href="about.php" class="active">About</a></li>
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
    hamburger.onclick = function () {
      navBar = document.querySelector(".nav-bar");
      navBar.classList.toggle("active");
    }
  </script>
  <style>
    .coverc {
      width: 100%;
      height: 400px;
      background: url('img/about3.jpg')no-repeat;
      background-size: cover;
      display: grid;
      place-items: center;
      padding-top: 8rem;

    }

    .title {
      font-size: 38px;
      text-align: center;
      align-items: center;
    }

    .para p {
      font-size: 23px;
      margin-left: 20px;
      margin-right: 20px;
    }

    @media (max-width: 767px) {
      .para p {
        font-size: 16px;
      }

      #pptslide {
        height: 200px;
        width: 300px;

      }

      #map {
        height: 200px;
        width: 300px;


      }

      #overview {
        height: 200px;
        width: 300px;
      }

      .title {
        font-size: 28px;
        margin: 10px;
        text-align: center;
        align-items: center;
      }


    }

    img {
      max-width: 100%;
      height: auto;
      object-fit: cover;
    }

    .mission-vision {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      padding: 15px;
    }
  </style>
  <br>
  <br>
  <p class="title">" Welcome to <u> Food <b style="color: #06C167;">Donate</b></u> "</p>
  <br>
  <br>
  <br>
  <p class="heading">About us</p>
  <div class="para">
    <p>We are a team of passionate individuals committed to addressing the issue of food waste in India. Our goal is to
      create a system that connects food donors with charities and NGOs, while also reducing the environmental impact of
      food waste.</p>
  </div>
  <br>

  <div class="about-section">
    <div class="mission-vision">
      <div class="mission card animate-slide-up">
        <div class="card-inner">
          <h2>Our Mission</h2>
          <p>To eliminate hunger in our community by efficiently distributing surplus food to those in need.</p>
        </div>
      </div>
      <div class="vision card animate-slide-up">
        <div class="card-inner">
          <h2>Our Vision</h2>
          <p>A community where no one goes to bed hungry.</p>
        </div>
      </div>
    </div>
  </div>

  <style>
    .card {
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      padding: 10px;
      margin: 10px;
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-10px);
    }

    .card-inner {
      text-align: center;
    }

    .animate-slide-up {
      opacity: 0;
      animation: slideUp 0.5s ease forwards;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .mission {
      animation-delay: 0.2s;
    }

    .vision {
      animation-delay: 0.4s;
    }
  </style>

    <div class="impact-metrics">
      <h2>Our Impact</h2>
      <div class="metrics-grid">
        <div class="metric">
          <span class="number">50,000+</span>
          <span class="label">Meals Served</span>
        </div>
        <div class="metric">
          <span class="number">1,000+</span>
          <span class="label">Regular Donors</span>
        </div>
        <div class="metric">
          <span class="number">100+</span>
          <span class="label">Volunteers</span>
        </div>
      </div>
    </div>
  </div>

  <div class="map" style="text-align: center; padding-bottom: 50px;">
    <p style="font-size:30px;"> Location </p>
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3838.8468822465766!2d74.51684231482711!3d15.836096489034955!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bbf67000cab25f7%3A0x1915defb54061d09!2sSri%20Takshashila%20Gurukul!5e0!3m2!1sen!2sin!4v1707397049990!5m2!1sen!2sin"
      width="777" height="473" style="border:0;" allowfullscreen="" loading="lazy"
      referrerpolicy="no-referrer-when-downgrade" id="map"></iframe>
  </div>

  
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

</body>

</html>