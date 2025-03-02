<?php
include 'connection.php'; // Include your database connection file
session_start(); // Start the session

// Check if the user is logged in (update to use donor_email)
if (!isset($_SESSION['donor_email']) || empty($_SESSION['donor_email'])) {
    header("Location: signin.php");
    exit(); // Stop further execution
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_email = $_SESSION['donor_email'];
    $current_password = mysqli_real_escape_string($connection, $_POST['current_password']);
    $new_password = mysqli_real_escape_string($connection, $_POST['new_password']);

    // Fetch the current password from the database
    $query = "SELECT password FROM login WHERE email='$current_email'";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);

    // Verify the current password
    if (password_verify($current_password, $row['password'])) {
        // Update the password in the database
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_query = "UPDATE login SET password='$hashed_password' WHERE email='$current_email'";
        if (mysqli_query($connection, $update_query)) {
            $_SESSION['message'] = "Password changed successfully.";
        } else {
            $_SESSION['message'] = "Error changing password: " . mysqli_error($connection);
        }
    } else {
        $_SESSION['message'] = "Current password is incorrect.";
    }

    // Redirect back to the update profile page
    header("Location: update_profile.php");
    exit(); // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <!-- Add Font Awesome for the eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #06C167;
            margin: 0;
            padding: 20px;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px; /* Set a max width for the form */
            margin: auto; /* Center the form */
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            font-size: 18px;
        }
        input[type="password"],
        input[type="text"] {  
            width: 85%;      /* Decreased from 91% */
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            padding-right: 40px;
        }
        button {
            cursor: pointer;
            border: none;
            border-radius: 5px;
            background-color: #06C167;
            color: white;
            padding: 12px;
            width: 98%;
            font-size: 20px;
            font-weight: 100;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #05a054;
        }
        .password-container {
            position: relative;
            width: 90%;      /* Added width constraint to container */
            margin: 0 auto;  /* Center the container */
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #06C167;  /* Changed color to match theme */
            font-size: 18px; /* Increased size */
            padding: 5px;    /* Added padding for better touch target */
            transition: color 0.3s ease; /* Smooth color transition */
        }
        .toggle-password:hover {
            color: #05a054; /* Darker shade on hover */
        }
        .back-arrow {
            position: absolute;
            top: 20px;
            left: 5px;
            font-size: 24px;
            color: black;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.3s ease;
        }
        .back-arrow:hover {
            transform: translateX(-5px);
            color: #05a054;
        }
    </style>
</head>
<body>
    <form action="change_password.php" method="POST">
        <div style="position: relative;">
            <a href="javascript:history.back()" class="back-arrow">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <h1>Change <span style="color: #06C167;">Password</span></h1><br>
        <label>Current Password</label>
        <div class="password-container">
            <input type="password" name="current_password" required>
            <i class="toggle-password fas fa-eye" onclick="togglePassword(this)"></i>
        </div><br><br>
        
        <label>New Password</label>
        <div class="password-container">
            <input type="password" name="new_password" required>
            <i class="toggle-password fas fa-eye" onclick="togglePassword(this)"></i>
        </div><br><br>
        
        <button type="submit">Update Password</button>
    </form>

    <script>
        function togglePassword(element) {
            const input = element.previousElementSibling;
            if (input.type === "password") {
                input.type = "text";
                element.classList.remove("fa-eye");
                element.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                element.classList.remove("fa-eye-slash");
                element.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>
