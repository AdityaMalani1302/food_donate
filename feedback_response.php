<?php
session_start();

// Check if the user is logged in using donor-specific session variable
if (!isset($_SESSION['donor_email']) || !$_SESSION['is_donor']) {
    header("Location: signin.php");
    exit();
}

include 'connection.php';
$donor_email = $_SESSION['donor_email'];

// Fetch feedback and responses for the current donor
$query = "SELECT * FROM user_feedback WHERE email = ? ORDER BY feedback_id DESC";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $donor_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Responses</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .feedback-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }

        .feedback-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
            transform: translateY(20px);
            opacity: 0;
            animation: slideIn 0.5s ease forwards;
        }

        @keyframes slideIn {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .feedback-header {
            background: #06C167;
            color: white;
            padding: 15px 20px;
            font-size: 1.1em;
        }

        .feedback-content {
            padding: 20px;
        }

        .message-box {
            margin-bottom: 20px;
        }

        .message-label {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .message-text {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin-top: 5px;
        }

        .admin-reply {
            border-left: 4px solid #06C167;
            margin-top: 15px;
            padding-left: 15px;
            transition: all 0.3s ease;
        }

        .reply-date {
            font-size: 0.9em;
            color: #666;
            margin-top: 5px;
        }

        .no-feedback {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .feedback-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <a href="home.php" style="text-decoration: none; color: inherit;">
                Food <b style="color: #06C167;">Donate</b>
            </a>
        </div>
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
                <li><a href="feedback_response.php" class="active">Feedback</a></li>
                <li><a href="profile.php">Profile</a></li>
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

    <div class="feedback-container">
        <h1 style="text-align: center; color: #333; margin-bottom: 30px;">Your Feedback History</h1>
        
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="feedback-card">';
                echo '<div class="feedback-header">Feedback from ' . htmlspecialchars($row['name']) . '</div>';
                echo '<div class="feedback-content">';
                
                echo '<div class="message-box">';
                echo '<div class="message-label">Your Message:</div>';
                echo '<div class="message-text">' . htmlspecialchars($row['message']) . '</div>';
                echo '</div>';

                if (!empty($row['admin_reply'])) {
                    echo '<div class="admin-reply">';
                    echo '<div class="message-label">Admin Response:</div>';
                    echo '<div class="message-text">' . htmlspecialchars($row['admin_reply']) . '</div>';
                    if (!empty($row['reply_date'])) {
                        echo '<div class="reply-date">Replied on: ' . date('F j, Y, g:i a', strtotime($row['reply_date'])) . '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="admin-reply" style="color: #666;">';
                    echo 'Waiting for admin response...';
                    echo '</div>';
                }
                
                echo '</div></div>';
            }
        } else {
            echo '<div class="no-feedback">You haven\'t submitted any feedback yet.</div>';
        }
        ?>
    </div>
</body>
</html> 