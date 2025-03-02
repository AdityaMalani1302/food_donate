<?php
session_start();
include 'connect.php';

// Check if connection is established
if (!isset($connection)) {
    die("Database connection not established. Please check connect.php");
}

// Check if admin is logged in using admin-specific session variable
if (!isset($_SESSION['admin_email']) || !$_SESSION['is_admin']) {
    header("Location: signin.php");
    exit();
}

// Always fetch the latest admin data from admin table
$email = $_SESSION['admin_email'];
$query = "SELECT * FROM admin WHERE email = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$admin_data = $result->fetch_assoc();

if (!$admin_data) {
    // If no admin data found, redirect to login
    header("Location: signin.php");
    exit();
}

// Update session data with admin information using admin-specific keys
$_SESSION['Aid'] = $admin_data['Aid'];
$_SESSION['admin_name'] = $admin_data['name'];
$_SESSION['admin_location'] = $admin_data['location'];
$_SESSION['admin_email'] = $admin_data['email'];
$_SESSION['is_admin'] = true;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <style>
        .profile-container {
            background: var(--panel-color);
            border-radius: 15px;
            padding: 30px;
            max-width: 800px;
            margin: 20px auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-header h2 {
            color: var(--text-color);
            font-size: 28px;
            margin-bottom: 10px;
        }

        .profile-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-item {
            padding: 15px;
            border-radius: 10px;
            background: var(--panel-color);
            border: 1px solid var(--border-color);
            transition: transform 0.3s ease;
        }

        .info-item:hover {
            transform: translateY(-5px);
        }

        .info-label {
            color: var(--black-light-color);
            font-size: 14px;
            margin-bottom: 5px;
        }

        .info-value {
            color: var(--text-color);
            font-size: 16px;
            font-weight: 500;
        }

        .profile-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .action-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            background: #06C167;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: #059656;
            transform: scale(1.05);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media screen and (max-width: 768px) {
            .profile-info {
                grid-template-columns: 1fr;
            }

            .profile-actions {
                flex-direction: column;
            }

            .action-btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <nav>
        <div class="logo-name">
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
                <li><a href="donate.php">
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
        </div>

        <div class="profile-container">
            <div class="profile-header">
                <h2>Profile Details</h2>
                <p style="color: #06C167;">Welcome, <?php echo $_SESSION['admin_name']; ?></p>
            </div>

            <div class="profile-info">

                <div class="info-item">
                    <div class="info-label">Admin ID</div>
                    <div class="info-value"><?php echo $_SESSION['Aid']; ?></div>
                </div>

                <div class="info-item">
                    <div class="info-label">Name</div>
                    <div class="info-value"><?php echo $_SESSION['admin_name']; ?></div>
                </div>

                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value"><?php echo $_SESSION['admin_email']; ?></div>
                </div>

                <div class="info-item">
                    <div class="info-label">Location</div>
                    <div class="info-value"><?php echo $_SESSION['admin_location']; ?></div>
                </div>
            </div>

            <div class="profile-actions">
                <a href="update_profile.php" class="action-btn">Update Profile</a>
                <a href="update_password.php" class="action-btn">Change Password</a>
            </div>
        </div>
    </section>

    <script>
        const body = document.querySelector("body"),
            modeToggle = body.querySelector(".mode-toggle");
        sidebar = body.querySelector("nav");
        sidebarToggle = body.querySelector(".sidebar-toggle");

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