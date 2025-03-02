<?php
include '../connection.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_email']) || !$_SESSION['is_admin']) {
    header("Location: signin.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = mysqli_real_escape_string($connection, $_POST['current_password']);
    $new_password = mysqli_real_escape_string($connection, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($connection, $_POST['confirm_password']);
    $aid = $_SESSION['Aid'];

    // Fetch current password from database
    $query = "SELECT password FROM admin WHERE Aid='$aid'";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);

    if (password_verify($current_password, $row['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE admin SET password='$hashed_password' WHERE Aid='$aid'";
            
            if (mysqli_query($connection, $update_query)) {
                $_SESSION['message'] = "Password updated successfully!";
                header("Location: admin_profile.php");
                exit();
            }
        } else {
            $error = "New passwords do not match!";
        }
    } else {
        $error = "Current password is incorrect!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>
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

        .update-header h2 {
            color: var(--text-color);
            font-size: 28px;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
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
            padding-right: 40px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: var(--panel-color);
            color: var(--text-color);
            height: 45px;
        }

        .form-group input:focus {
            border-color: #06C167;
            box-shadow: 0 0 0 2px rgba(6, 193, 103, 0.1);
            outline: none;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: calc(50% + 10px);
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--black-light-color);
            transition: color 0.3s ease;
            z-index: 1;
        }

        .password-toggle:hover {
            color: #06C167;
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

        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
            animation: shake 0.5s ease-in-out;
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

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
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
                <a href="admin_profile.php" class="back-arrow">
                    <i class="uil uil-arrow-left"></i>
                </a>
                <h2>Update Password</h2>
                <p style="color: #06C167;">Change your password</p>
            </div>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                    <i class="uil uil-eye password-toggle" onclick="togglePassword('current_password')"></i>
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                    <i class="uil uil-eye password-toggle" onclick="togglePassword('new_password')"></i>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <i class="uil uil-eye password-toggle" onclick="togglePassword('confirm_password')"></i>
                </div>

                <?php if(isset($error)): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>

                <button type="submit" class="update-btn">Update Password</button>
            </form>
        </div>
    </section>

    <script>
        // Dark mode and sidebar toggle
        const body = document.querySelector("body"),
        modeToggle = body.querySelector(".mode-toggle");
        sidebar = body.querySelector("nav");
        sidebarToggle = body.querySelector(".sidebar-toggle");

        let getMode = localStorage.getItem("mode");
        if(getMode && getMode ==="dark"){
            body.classList.toggle("dark");
        }

        let getStatus = localStorage.getItem("status");
        if(getStatus && getStatus ==="close"){
            sidebar.classList.toggle("close");
        }

        modeToggle.addEventListener("click", () => {
            body.classList.toggle("dark");
            if(body.classList.contains("dark")){
                localStorage.setItem("mode", "dark");
            }else{
                localStorage.setItem("mode", "light");
            }
        });

        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("close");
            if(sidebar.classList.contains("close")){
                localStorage.setItem("status", "close");
            }else{
                localStorage.setItem("status", "open");
            }
        });

        // Password toggle functionality
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling;
            
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("uil-eye");
                icon.classList.add("uil-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("uil-eye-slash");
                icon.classList.add("uil-eye");
            }
        }
    </script>
</body>
</html>
