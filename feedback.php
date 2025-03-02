<?php
session_start();
include 'connection.php';
include 'includes/email_utils.php';
require_once 'includes/config.php';

if (isset($_POST['send'])) {
  $email = $_POST['email'];
  $name = $_POST['name'];
  $msg = $_POST['message'];
  $sanitized_emailid =  mysqli_real_escape_string($connection, $email);
  $sanitized_name =  mysqli_real_escape_string($connection, $name);
  $sanitized_msg =  mysqli_real_escape_string($connection, $msg);
  $query = "insert into user_feedback(name,email,message) values('$sanitized_name','$sanitized_emailid','$sanitized_msg')";
  $query_run = mysqli_query($connection, $query);
  if ($query_run) {
    // Send email notification to admin
    sendContactMessageNotification(
      $sanitized_name,
      $sanitized_emailid,
      $sanitized_msg,
      $connection
    );

    // Display thank you message with styling
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Feedback Submitted</title>
        <style>
            .thank-you-container {
                text-align: center;
                margin-top: 100px;
                font-family: Arial, sans-serif;
            }
            .thank-you-message {
                font-size: 40px;
                color: #06C167;
                margin-bottom: 20px;
            }
            .back-button {
                padding: 10px 20px;
                background-color: #06C167;
                color: white;
                text-decoration: none;
                border-radius: 5px;
            }
        </style>
    </head>
    <body>
        <div class="thank-you-container">
            <div class="thank-you-message">Thank you for your feedback!🙏</div><br>
            <a href="contact.php" class="back-button">Back to Contact Page</a>
        </div>
    </body>
    </html>
    <?php
    exit();
  } else {
    echo '<script>alert("Error: Unable to save feedback. Please try again."); window.location.href="contact.php";</script>';
  }
}
