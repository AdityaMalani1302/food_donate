<?php
session_start(); // Start the session
include 'connection.php';

// Check if connection is established
if (!isset($connection)) {
    die("Database connection not established. Please check connection.php");
}

// Check if donor is logged in using donor-specific session variable
if (!isset($_SESSION['donor_email']) || !$_SESSION['is_donor']) {
    header("Location: signin.php");
    exit();
}

// Always fetch the latest donor data
$email = $_SESSION['donor_email'];
$query = "SELECT * FROM login WHERE email = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$donor_data = $result->fetch_assoc();

if (!$donor_data) {
    // If no data found, redirect to login
    header("Location: signin.php");
    exit();
}

// Update session data with donor-specific keys
$_SESSION['donor_id'] = $donor_data['id'];
$_SESSION['donor_name'] = $donor_data['name'];
$_SESSION['donor_phone'] = $donor_data['phone'];
$_SESSION['donor_email'] = $donor_data['email'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


</head>

<body>
    <header>
        <div class="logo"><a href="home.html" style="text-decoration: none; color: inherit;">Food <b style="color: #06C167;">Donate</b></a></div>
        <div class="hamburger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
        <nav class="nav-bar">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="donation.php">Donation</a></li>
                <li><a href="feedback_response.php">Feedback</a></li>
                <li><a href="profile.php" class="active">Profile</a></li>
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






    <div class="profile">
        <div class="profilebox">

            <p class="headingline" style="text-align: left;font-size:30px;">Profile</p>

            <br>
            <p style="font-size: 28px;">Welcome</p>
            <p style="color: #06C167; font-size: 25px;"><?php echo htmlspecialchars($donor_data['name']); ?></p>
            <br>
            <div class="info" style="padding-left:10px;">
                <p>Name: <?php echo htmlspecialchars($donor_data['name']); ?></p><br>
                <p>Phone: <?php echo htmlspecialchars($donor_data['phone']); ?></p><br>
                <p>Email: <?php echo htmlspecialchars($donor_data['email']); ?></p><br>

                <div style="margin-top: 6px;">
                    <a href="update_profile.php" style="display:inline-block; border-radius:5px; background-color: #06C167; color: white; padding:10px; text-align: center;">Update Profile</a><br><br>
                    <a href="logout.php" style="display:inline-block; border-radius:5px; background-color: #06C167; color: white; padding:10px; text-align: center;">Logout</a>
                </div>
            </div>
            <br>
            <br>



            <hr>
            <br>


        </div>






    </div>





</body>

</html>






















<!-- <th>Status</th> -->
<!-- . $row['status'] . "</td></tr>"; -->