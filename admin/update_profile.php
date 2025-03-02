<?php
session_start();
include 'connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_email']) || !$_SESSION['is_admin']) {
    header("Location: signin.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $location = mysqli_real_escape_string($connection, $_POST['location']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);
    $aid = $_SESSION['Aid'];

    $query = "UPDATE admin SET name='$name', email='$email', location='$location', address='$address' WHERE Aid='$aid'";

    if (mysqli_query($connection, $query)) {
        // Update session variables with admin prefix
        $_SESSION['admin_name'] = $name;
        $_SESSION['admin_email'] = $email;
        $_SESSION['admin_location'] = $location;
        $_SESSION['admin_address'] = $address;

        header("Location: admin_profile.php");  // Changed to admin_profile.php
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <style>
        .update-container {
            background: var(--panel-color);
            border-radius: 15px;
            padding: 30px;
            max-width: 800px;
            margin: 20px auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in;
        }

        .update-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            padding-left: 30px;
        }

        .update-header h2 {
            color: var(--text-color);
            font-size: 28px;
            margin-bottom: 10px;
        }

        .update-header p {
            color: var(--text-color);
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 20px;
            animation: slideUp 0.5s ease-out;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--black-light-color);
            font-size: 16px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: var(--panel-color);
            color: var(--text-color);
        }

        .form-group input:focus {
            border-color: #06C167;
            box-shadow: 0 0 0 2px rgba(6, 193, 103, 0.1);
            outline: none;
        }

        .update-btn {
            background: #06C167;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
        }

        .update-btn:hover {
            background: #059656;
            transform: translateY(-2px);
        }

        .update-btn:active {
            transform: translateY(0);
        }

        .back-arrow {
            position: absolute;
            left: 0;
            top: 5px;
            font-size: 24px;
            color: var(--text-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .back-arrow i {
            transition: transform 0.3s ease;
        }

        .back-arrow:hover {
            color: #06C167;
        }

        .back-arrow:hover i {
            transform: translateX(-5px);
        }

        .button-group {
            margin-top: 20px;
            animation: slideUp 0.5s ease-out;
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

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media screen and (max-width: 768px) {
            .update-container {
                margin: 20px;
                padding: 20px;
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

        <div class="update-container">
            <div class="update-header">
                <div style="position: relative;">
                    <a href="admin_profile.php" class="back-arrow">
                        <i class="uil uil-arrow-left"></i>
                    </a>
                    <h2>Update Profile</h2>
                    <p style="color: #06C167;">Edit your information</p>
                </div>
            </div>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['admin_email']) ? htmlspecialchars($_SESSION['admin_email']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" value="<?php echo isset($_SESSION['admin_location']) ? htmlspecialchars($_SESSION['admin_location']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="<?php echo isset($_SESSION['admin_address']) ? htmlspecialchars($_SESSION['admin_address']) : ''; ?>" required>
                </div>

                <button type="submit" class="update-btn">Update Profile</button>
            </form>
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