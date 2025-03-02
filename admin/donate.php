<?php
session_start();
include("connect.php");
include("../includes/email_utils.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_email']) || !$_SESSION['is_admin']) {
    header("location:signin.php");
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['donation_id'])) {
        $donation_id = mysqli_real_escape_string($connection, $_POST['donation_id']);
        
        // Check which button was clicked and set the appropriate status
        if (isset($_POST['confirm_received'])) {
            $new_status = "Received";  // Changed to match the enum value
        } elseif (isset($_POST['mark_failed'])) {
            $new_status = "Not Received";  // Changed to match the enum value
        } else {
            $new_status = "Pending";  // Default status
        }
        
        $update_query = "UPDATE food_donations SET status = ? WHERE Fid = ?";
        
        if ($stmt = mysqli_prepare($connection, $update_query)) {
            mysqli_stmt_bind_param($stmt, "ss", $new_status, $donation_id);
            
            if (mysqli_stmt_execute($stmt)) {
                // Send email notification to donor
                if (sendDonationStatusNotification($donation_id, $new_status, $connection)) {
                    $_SESSION['success_message'] = "Donation status updated and notification sent successfully!";
                } else {
                    $_SESSION['success_message'] = "Donation status updated but failed to send notification.";
                }
            } else {
                $_SESSION['error_message'] = "Error updating status: " . mysqli_error($connection);
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['error_message'] = "Error preparing statement: " . mysqli_error($connection);
        }
        
        header("Location: donate.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Donations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="admin.css">
    
    <style>
        .status-button {
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .received {
            background-color: #d4edda;
            color: #155724;
        }

        .failed {
            background-color: #f8d7da;
            color: #721c24;
        }

        .checkmark {
            opacity: 0;
            transform: scale(0);
            animation: scale-in 0.5s ease forwards;
        }

        @keyframes scale-in {
            0% { opacity: 0; transform: scale(0); }
            100% { opacity: 1; transform: scale(1); }
        }

        .confirm-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .failed-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 5px;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .confirm-btn:hover { background-color: #218838; }
        .failed-btn:hover { background-color: #c82333; }

        .confirm-btn:disabled, .failed-btn:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .table-container {
            margin: 20px;
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .table tr:hover {
            background-color: #f5f5f5;
        }

        @media screen and (max-width: 768px) {
            .table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>

<body>
    <nav>
        <div class="logo-name">
            <div class="logo-image"></div>
            <span class="logo_name">ADMIN</span>
        </div>

        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="admin.php">
                    <i class="uil uil-estate"></i>
                    <span class="link-name">Dashboard</span>
                </a></li>
                <li><a href="analytics.php">
                    <i class="uil uil-chart"></i>
                    <span class="link-name">Analytics</span>
                </a></li>
                <li><a href="#" class="active">
                    <i class="uil uil-heart"></i>
                    <span class="link-name">Donates</span>
                </a></li>
                <li><a href="feedback.php">
                    <i class="uil uil-comments"></i>
                    <span class="link-name">Feedbacks</span>
                </a></li>
                <li><a href="history.php">
                    <i class="uil uil-history"></i>
                    <span class="link-name">History</span>
                </a></li>
                <li><a href="admin_profile.php">
                    <i class="uil uil-user"></i>
                    <span class="link-name">Profile</span>
                </a></li>
            </ul>

            <ul class="logout-mode">
                <li><a href="../logout.php">
                    <i class="uil uil-signout"></i>
                    <span class="link-name">Logout</span>
                </a></li>
                <li class="mode">
                    <a href="#">
                        <i class="uil uil-moon"></i>
                        <span class="link-name">Dark Mode</span>
                    </a>
                    <div class="mode-toggle">
                        <span class="switch"></span>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <section class="dashboard">
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>
            <p class="logo" style="position: absolute; left: 50%; transform: translateX(-50%);">Food <b style="color: #06C167;">Donate</b></p>
        </div>

        <div class="activity">
            <div class="title">
                <h2>Donations Management</h2>
            </div>

            <?php
            // Display success/error messages
            if (isset($_SESSION['success_message'])) {
                echo "<div class='alert alert-success'>" . $_SESSION['success_message'] . "</div>";
                unset($_SESSION['success_message']);
            }
            if (isset($_SESSION['error_message'])) {
                echo "<div class='alert alert-danger'>" . $_SESSION['error_message'] . "</div>";
                unset($_SESSION['error_message']);
            }

            // Fetch donations
            $sql = "SELECT *, 
                    CASE 
                        WHEN status = 'Received' THEN 1
                        WHEN status = 'Not Received' THEN 2
                        ELSE 0
                    END as status_order 
                    FROM food_donations 
                    ORDER BY status_order ASC, date DESC";
            
            $result = mysqli_query($connection, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                echo "<div class='table-container'>
                        <table class='table'>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Food</th>
                                    <th>Category</th>
                                    <th>Phone</th>
                                    <th>Date/Time</th>
                                    <th>Address</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>";

                while ($row = mysqli_fetch_assoc($result)) {
                    $status_class = strtolower(str_replace(' ', '-', $row['status'] ?? 'pending'));
                    $button_disabled = ($row['status'] == 'Received' || $row['status'] == 'Not Received') ? 'disabled' : '';
                    
                    echo "<tr>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['food']) . "</td>
                            <td>" . htmlspecialchars($row['category']) . "</td>
                            <td>" . htmlspecialchars($row['phoneno']) . "</td>
                            <td>" . htmlspecialchars($row['date']) . "</td>
                            <td>" . htmlspecialchars($row['address']) . "</td>
                            <td>" . htmlspecialchars($row['quantity']) . "</td>
                            <td>
                                <div class='status-button " . $status_class . "'>
                                    " . $row['status'] . "
                                    " . ($row['status'] == 'Received' ? '<div class="checkmark">✓</div>' : '') . "
                                </div>
                            </td>
                            <td>
                                <form method='post' style='margin:0;'>
                                    <input type='hidden' name='donation_id' value='" . $row['Fid'] . "'>
                                    <button type='submit' name='confirm_received' class='confirm-btn' " . $button_disabled . ">
                                        Confirm Received
                                    </button>
                                    <button type='submit' name='mark_failed' class='failed-btn' " . $button_disabled . ">
                                        Not Received
                                    </button>
                                </form>
                            </td>
                        </tr>";
                }

                echo "</tbody></table></div>";
            } else {
                echo "<p>No donations found.</p>";
            }
            ?>
        </div>
    </section>

    <script>
        // Dark mode toggle
        const body = document.querySelector("body");
        const modeToggle = document.querySelector(".mode-toggle");
        const sidebar = document.querySelector("nav");
        const sidebarToggle = document.querySelector(".sidebar-toggle");

        let getMode = localStorage.getItem("mode");
        if (getMode && getMode === "dark") {
            body.classList.toggle("dark");
        }

        let getStatus = localStorage.getItem("status");
        if (getStatus && getStatus === "close") {
            sidebar.classList.toggle("close");
        }

        modeToggle.addEventListener("click", () => {
            body.classList.toggle("dark");
            if (body.classList.contains("dark")) {
                localStorage.setItem("mode", "dark");
            } else {
                localStorage.setItem("mode", "light");
            }
        });

        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("close");
            if (sidebar.classList.contains("close")) {
                localStorage.setItem("status", "close");
            } else {
                localStorage.setItem("status", "open");
            }
        });
    </script>
</body>
</html>
