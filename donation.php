<?php
session_start(); // Start the session

// Check if the user is logged in using donor-specific session variable
if (!isset($_SESSION['donor_email']) || !$_SESSION['is_donor']) {
    header("Location: signin.php"); // Redirect to login page
    exit();
}

// Database connection
include 'connection.php';  // Make sure this path is correct
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
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="donation.php" class="active">Donation</a></li>
        <li><a href="feedback_response.php">Feedback</a></li>
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

<style>
    .heading {
        color: #333;
        text-align: center;
        font-size: 28px;
        margin-bottom: 30px;
        font-weight: 600;
    }
    .heading span {
        color: #06C167;
    }
    .table-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    }
    .table-wrapper {
        overflow-x: auto;
    }
    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
        border-radius: 8px;
    }
    .table thead th {
        background: #06C167;
        color: white;
        padding: 15px;
        font-weight: 500;
        text-align: left;
        font-size: 16px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }
    .table thead th:first-child {
        border-top-left-radius: 8px;
    }
    .table thead th:last-child {
        border-top-right-radius: 8px;
    }
    .table tbody tr {
        transition: all 0.3s ease;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
    }
    .table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        color: #333;
        font-size: 15px;
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
        text-align: center;
        display: inline-block;
        min-width: 100px;
    }
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }
    .status-received {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    /* Animation for new rows */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .table tbody tr {
        animation: fadeIn 0.5s ease-out forwards;
    }
</style>

<br>

<h1 class="heading">Your Donations</h1>
<div class="table-container">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Food Item</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Date/Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!isset($connection)) {
                    echo "<tr><td colspan='6' style='text-align: center; padding: 20px;'>Database connection error</td></tr>";
                } else {
                    $email = $_SESSION['donor_email'];
                    $query = "SELECT * FROM food_donations WHERE email=? ORDER BY date DESC";
                    $stmt = $connection->prepare($query);
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if (!$result) {
                        echo "<tr><td colspan='6' style='text-align: center; padding: 20px;'>Query error: " . mysqli_error($connection) . "</td></tr>";
                    } else if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $status_class = $row['status'] == 'Received' ? 'status-received' : 'status-pending';
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['food']) . "</td>
                                    <td>" . htmlspecialchars($row['type']) . "</td>
                                    <td>" . htmlspecialchars($row['category']) . "</td>
                                    <td>" . htmlspecialchars($row['quantity']) . "</td>
                                    <td>" . date('M d, Y h:i A', strtotime($row['date'])) . "</td>
                                    <td><span class='status-badge " . $status_class . "'>" . htmlspecialchars($row['status']) . "</span></td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align: center; padding: 20px;'>No donations found for email: " . htmlspecialchars($email) . "</td></tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
