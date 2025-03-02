<?php
session_start();

// Initialize CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include 'connection.php';
$msg = 0;

if (isset($_POST['reset'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }

    $email = $_POST['email'];
    
    // Check if email exists in database
    $sql = "SELECT * FROM login WHERE email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Generate reset token
        $reset_token = bin2hex(random_bytes(32));
        $reset_token_hash = hash('sha256', $reset_token);
        $expiry = date('Y-m-d H:i:s', time() + 60 * 30); // 30 minutes from now

        // Store the reset token in the database
        $update_sql = "UPDATE login SET reset_token = ?, reset_token_expires = ? WHERE email = ?";
        $update_stmt = $connection->prepare($update_sql);
        $update_stmt->bind_param("sss", $reset_token_hash, $expiry, $email);
        $update_stmt->execute();

        // Send reset email (you'll need to implement this)
        // For now, we'll just show a success message
        $msg = 1; // Success
    } else {
        $msg = 2; // Email not found
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/loginstyle.css">
    <style>
        .error {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }
        .success {
            color: #28a745;
            font-size: 14px;
            margin-top: 5px;
        }
        .forgot-password-info {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .back-to-login {
            text-align: center;
            margin-top: 15px;
        }
        .back-to-login a {
            color: #06C167;
            text-decoration: none;
        }
        .back-to-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="regform">
            <form action="" method="post" class="form1">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <p class="logo" style="font-size: 30px;">Food <b style="color:#06C167;">Donate</b></p>
                <p id="heading" style="padding-left: 1px;">Reset Password</p>

                <p class="forgot-password-info">Enter your email address and we'll send you instructions to reset your password.</p>

                <div class="input">
                    <input type="email" 
                           placeholder="Email address" 
                           name="email" 
                           required 
                           autocomplete="email"
                           aria-label="Email address"
                           pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                    />
                </div>

                <?php
                if ($msg == 1) {
                    echo '<p class="success">If an account exists with this email, you will receive password reset instructions.</p>';
                } else if ($msg == 2) {
                    echo '<p class="error">No account found with this email address.</p>';
                }
                ?>

                <div class="btn">
                    <button type="submit" name="reset">Send Reset Link</button>
                </div>

                <div class="back-to-login">
                    <a href="signin.php">Back to Login</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Client-side email validation
        const emailInput = document.querySelector('input[type="email"]');
        emailInput.addEventListener('invalid', function(event) {
            if (event.target.validity.typeMismatch) {
                event.target.setCustomValidity('Please enter a valid email address');
            }
        });
        
        emailInput.addEventListener('input', function(event) {
            event.target.setCustomValidity('');
        });
    </script>
</body>
</html> 