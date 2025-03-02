<?php
// Email utility functions for food donation system
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function getAdminEmail($connection) {
    $query = "SELECT email FROM admin LIMIT 1";
    $result = mysqli_query($connection, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        error_log("Using admin email from database: " . $row['email']);
        return $row['email'];
    }
    error_log("Using fallback admin email: " . ADMIN_EMAIL);
    return ADMIN_EMAIL; // Fallback to config value
}

function sendEmail($to, $subject, $message, $from = null) {
    try {
        $mail = new PHPMailer(true);
        
        // Server settings
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;
        
        // Recipients
        $mail->setFrom($from ? $from : SYSTEM_EMAIL);
        $mail->addAddress($to);
        if ($from) {
            $mail->addReplyTo($from);
        }
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = strip_tags($message);
        
        return $mail->send();
    } catch (Exception $e) {
        error_log("Email sending failed: " . $mail->ErrorInfo);
        return false;
    }
}

function sendDonationNotification($donorName, $donorEmail, $foodName, $quantity, $category, $location, $connection) {
    $adminEmail = getAdminEmail($connection);
    $subject = "New Food Donation Received";
    
    // Get current date and time in local timezone
    date_default_timezone_set(DEFAULT_TIMEZONE);
    $currentDateTime = date('Y-m-d H:i:s');
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { padding: 20px; max-width: 600px; margin: 0 auto; }
            .header { color: #06C167; font-size: 24px; margin-bottom: 20px; text-align: center; }
            .details { margin: 20px 0; background: #f9f9f9; padding: 20px; border-radius: 5px; }
            .details p { margin: 10px 0; }
            .footer { margin-top: 20px; font-size: 12px; color: #666; text-align: center; }
            .urgent { color: #ff4444; font-weight: bold; }
            .timestamp { color: #666; font-size: 14px; text-align: right; }
            .action-needed { background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>🎁 New Food Donation Alert! 🎁</div>
            <div class='timestamp'>Received on: $currentDateTime</div>
            
            <div class='details'>
                <h3>Donor Information:</h3>
                <p><strong>Name:</strong> $donorName</p>
                <p><strong>Email:</strong> $donorEmail</p>
                
                <h3>Donation Details:</h3>
                <p><strong>Food Item:</strong> $foodName</p>
                <p><strong>Quantity:</strong> $quantity</p>
                <p><strong>Category:</strong> $category</p>
                <p><strong>Pickup Location:</strong> $location</p>
            </div>
            
            <div class='action-needed'>
                <h3>⚡ Action Required:</h3>
                <p>Please review this donation and take appropriate action:</p>
                <ul>
                    <li>Verify the donation details</li>
                    <li>Contact the donor if needed</li>
                    <li>Arrange for pickup/delivery</li>
                    <li>Update the status in the admin panel</li>
                </ul>
            </div>
            
            <div class='footer'>
                <p>This is an automated message from " . SITE_NAME . ".</p>
                <p>Please do not reply to this email. Use the admin panel to manage donations.</p>
                <p><a href='" . SITE_URL . "/admin/donate.php'>View in Admin Panel</a></p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmail($adminEmail, $subject, $message, $donorEmail);
}

function sendContactMessageNotification($name, $email, $message, $connection) {
    $adminEmail = getAdminEmail($connection);
    $subject = "New Contact Form Message";
    
    // Get current date and time in local timezone
    date_default_timezone_set(DEFAULT_TIMEZONE);
    $currentDateTime = date('Y-m-d H:i:s');
    
    $messageBody = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { padding: 20px; max-width: 600px; margin: 0 auto; }
            .header { color: #06C167; font-size: 24px; margin-bottom: 20px; text-align: center; }
            .details { margin: 20px 0; background: #f9f9f9; padding: 20px; border-radius: 5px; }
            .message { margin: 20px 0; background: #f5f5f5; padding: 15px; border-radius: 5px; }
            .footer { margin-top: 20px; font-size: 12px; color: #666; text-align: center; }
            .timestamp { color: #666; font-size: 14px; text-align: right; }
            .action-buttons { text-align: center; margin: 20px 0; }
            .action-buttons a { 
                background: #06C167; 
                color: white; 
                padding: 10px 20px; 
                text-decoration: none; 
                border-radius: 5px; 
                margin: 0 10px; 
                display: inline-block;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>📨 New Contact Message Received!</div>
            <div class='timestamp'>Received on: $currentDateTime</div>
            
            <div class='details'>
                <h3>Sender Information:</h3>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
            </div>
            
            <div class='message'>
                <h3>Message Content:</h3>
                <p>" . nl2br(htmlspecialchars($message)) . "</p>
            </div>
            
            <div class='action-buttons'>
                <a href='mailto:$email'>Reply to Sender</a>
                <a href='" . SITE_URL . "/admin/feedback.php'>View in Admin Panel</a>
            </div>
            
            <div class='footer'>
                <p>This is an automated message from " . SITE_NAME . ".</p>
                <p>You can reply directly to the sender by clicking the Reply button above.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return sendEmail($adminEmail, $subject, $messageBody, $email);
}

function sendDonationStatusNotification($donationId, $status, $connection) {
    // Get donation and donor details
    $query = "SELECT d.*, l.name as donor_name, l.email as donor_email 
              FROM food_donations d 
              JOIN login l ON d.email = l.email 
              WHERE d.Fid = ?";
    
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $donationId);
    $stmt->execute();
    $result = $stmt->get_result();
    $donation = $result->fetch_assoc();

    if (!$donation) {
        error_log("Failed to find donation with ID: " . $donationId);
        return false;
    }

    error_log("Sending status notification to donor: " . $donation['donor_email'] . " for donation ID: " . $donationId);
    
    $subject = "Update on Your Food Donation";
    
    // Get current date and time in local timezone
    date_default_timezone_set(DEFAULT_TIMEZONE);
    $currentDateTime = date('Y-m-d H:i:s');
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { padding: 20px; max-width: 600px; margin: 0 auto; }
            .header { color: #06C167; font-size: 24px; margin-bottom: 20px; text-align: center; }
            .details { margin: 20px 0; background: #f9f9f9; padding: 20px; border-radius: 5px; }
            .details p { margin: 10px 0; }
            .status { font-size: 18px; font-weight: bold; color: #06C167; text-align: center; }
            .footer { margin-top: 20px; font-size: 12px; color: #666; text-align: center; }
            .timestamp { color: #666; font-size: 14px; text-align: right; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>🔔 Food Donation Status Update</div>
            <div class='timestamp'>Updated on: $currentDateTime</div>
            
            <div class='details'>
                <div class='status'>Your donation has been marked as: " . ucfirst($status) . "</div>
                <h3>Donation Details:</h3>
                <p><strong>Food Item:</strong> {$donation['food']}</p>
                <p><strong>Quantity:</strong> {$donation['quantity']}</p>
                <p><strong>Category:</strong> {$donation['category']}</p>
                <p><strong>Type:</strong> {$donation['type']}</p>
                <p><strong>Location:</strong> {$donation['location']}</p>
                <p><strong>Submitted on:</strong> {$donation['date']}</p>
            </div>
            
            <div class='footer'>
                <p>Thank you for your generous donation!</p>
                <p>This is an automated message from " . SITE_NAME . ".</p>
                <p>If you have any questions, please contact us through the website.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $adminEmail = getAdminEmail($connection);
    return sendEmail($donation['donor_email'], $subject, $message, $adminEmail);
}

function sendFeedbackReplyNotification($feedbackId, $reply, $connection) {
    // Get feedback and user details
    $query = "SELECT * FROM user_feedback WHERE feedback_id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $feedbackId);
    $stmt->execute();
    $result = $stmt->get_result();
    $feedback = $result->fetch_assoc();

    if (!$feedback) {
        return false;
    }

    $subject = "Response to Your Message";
    
    // Get current date and time in local timezone
    date_default_timezone_set(DEFAULT_TIMEZONE);
    $currentDateTime = date('Y-m-d H:i:s');
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { padding: 20px; max-width: 600px; margin: 0 auto; }
            .header { color: #06C167; font-size: 24px; margin-bottom: 20px; text-align: center; }
            .details { margin: 20px 0; background: #f9f9f9; padding: 20px; border-radius: 5px; }
            .message { margin: 20px 0; background: #f5f5f5; padding: 15px; border-radius: 5px; }
            .reply { background: #e8f5e9; padding: 15px; border-radius: 5px; margin: 20px 0; }
            .footer { margin-top: 20px; font-size: 12px; color: #666; text-align: center; }
            .timestamp { color: #666; font-size: 14px; text-align: right; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>📬 Response to Your Message</div>
            <div class='timestamp'>Responded on: $currentDateTime</div>
            
            <div class='details'>
                <h3>Your Original Message:</h3>
                <div class='message'>
                    <p>" . nl2br(htmlspecialchars($feedback['message'])) . "</p>
                </div>
                
                <h3>Our Response:</h3>
                <div class='reply'>
                    <p>" . nl2br(htmlspecialchars($reply)) . "</p>
                </div>
            </div>
            
            <div class='footer'>
                <p>Thank you for contacting " . SITE_NAME . "!</p>
                <p>If you have any further questions, feel free to reach out to us again.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $adminEmail = getAdminEmail($connection);
    return sendEmail($feedback['email'], $subject, $message, $adminEmail);
} 