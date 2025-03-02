<?php
include 'connection.php'; // Include your database connection file
session_start(); // Start the session

// Check if donor is logged in using donor-specific session variable
if (!isset($_SESSION['donor_email']) || !$_SESSION['is_donor']) {
    header("Location: signin.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the new name, email, and phone number from the form
    $new_name = mysqli_real_escape_string($connection, $_POST['name']);
    $new_email = mysqli_real_escape_string($connection, $_POST['email']);
    $new_phone = mysqli_real_escape_string($connection, $_POST['phone']); // New phone number
    $current_email = $_SESSION['donor_email'];

    // Update the session variables
    $_SESSION['donor_name'] = $new_name;
    $_SESSION['donor_email'] = $new_email;
    $_SESSION['donor_phone'] = $new_phone;
    
    // Update the database
    $query = "UPDATE login SET name='$new_name', email='$new_email', phone='$new_phone' WHERE email='$current_email'"; // Added phone number to query
    $result = mysqli_query($connection, $query);

    if ($result) {
        // Optionally, you can set a success message
        $_SESSION['message'] = "Profile updated successfully.";
    } else {
        // Optionally, you can set an error message
        $_SESSION['message'] = "Error updating profile: " . mysqli_error($connection);
    }

    // Redirect back to the profile page
    header("Location: profile.php");
    exit(); // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />

  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

  <style>
      body {
          font-family: Arial, sans-serif;
          background-color: #06C167;
          margin: 0;
          padding: 20px;
      }
      h1 {
          color: #333;
          text-align: center; /* Center the heading */
      }
      form {
          background: white;
          padding: 20px;
          border-radius: 5px;
          box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
          max-width: 400px; /* Set a max width for the form */
          margin: auto; /* Center the form */
      }
      input[type="text"],
      input[type="tel"],
      input[type="email"] {
          width: 90%;
          padding: 18px; /* Increased padding for better height */
          margin: 10px 0;
          border: 1px solid #ccc;
          border-radius: 5px;
          font-size: 16px; /* Increased font size for better readability */
      }
      button {
          cursor: pointer;
          border: none;
          border-radius: 5px;
          background-color: #06C167;
          color: white;
          padding: 12px; /* Increased padding for better height */
          width: 100%;
          font-size: 20px; /* Increased font size for better readability */
          font-weight: 100;
          transition: background-color 0.3s; /* Smooth transition for hover effect */
      }
      button:hover {
          background-color: #05a054;
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
    <form action="update_profile.php" method="POST" style="margin-bottom: 10px;">
        <div style="position: relative;">
            <a href="javascript:history.back()" class="back-arrow">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <h1 style="text-align: center; margin-bottom: 20px;">Update <span style="color: #06C167;">Profile</span></h1>
        <label style="font-weight: bold; font-size: 18px;">Name</label>
        <input type="text" name="name" value="<?php echo isset($_SESSION['donor_name']) ? $_SESSION['donor_name'] : ''; ?>" required><br><br>
        
        <label style="font-weight: bold; font-size: 18px;">Phone Number</label>
        <input type="tel" name="phone" value="<?php echo isset($_SESSION['donor_phone']) ? $_SESSION['donor_phone'] : ''; ?>" required><br><br>
        
        <label style="font-weight: bold; font-size: 18px;">Email</label>
        <input type="email" name="email" value="<?php echo isset($_SESSION['donor_email']) ? $_SESSION['donor_email'] : ''; ?>" required><br><br>
        
        <button type="submit">Update</button><br><br>
        <a href="change_password.php" style="text-decoration: none;"><button type="button">Change Password</button></a>
    </form>
    
</body>
</html>