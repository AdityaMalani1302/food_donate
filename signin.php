<?php
session_start();

// Initialize CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include 'connection.php';
$msg = 0;

if (isset($_POST['sign'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        error_log("Starting login process for: " . $email);
        
        // Correct MySQLi prepared statement syntax
        $sql = "SELECT * FROM login WHERE email = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        error_log("Database query result: " . print_r($result, true));

        if ($result) {
            error_log("User found, verifying password");
            
            if (password_verify($password, $result['password'])) {
                error_log("Password verified successfully");
                
                // Clear any existing session data
                session_unset();
                
                // Set session variables
                $_SESSION['donor_email'] = $result['email'];
                $_SESSION['donor_name'] = $result['name'];
                $_SESSION['donor_phone'] = $result['phone'];
                $_SESSION['donor_id'] = $result['id'];
                $_SESSION['is_donor'] = true;
                
                error_log("Session variables set: " . print_r($_SESSION, true));
                
                // Regenerate session ID
                session_regenerate_id(true);
                
                // Ensure no output has been sent
                if (headers_sent($file, $line)) {
                    error_log("Headers already sent at $file:$line");
                } else {
                    // Clear output buffer if any exists
                    if (ob_get_length()) ob_clean();
                    
                    error_log("Redirecting to home.php");
                    header("Location: home.php");
                    exit();
                }
            } else {
                error_log("Password verification failed for user: " . $email);
                $msg = 1;
            }
        } else {
            error_log("No user found with email: " . $email);
            $msg = 2;
        }
    } catch (PDOException $e) {
        error_log("Database error during login: " . $e->getMessage());
        $msg = 2;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donor Login</title>
  <link rel="stylesheet" href="css/loginstyle.css">
  <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />

  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

</head>

<body>
  <style>
    .uil {
      top: 42%;
    }
    .error {
      color: #dc3545;
      font-size: 14px;
      margin-top: 5px;
    }
  </style>
  <div class="container">
    <div class="regform">
      <form action="" method="post" class="form1">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <p class="logo" style="font-size: 30px;">Food <b style="color:#06C167; ">Donate</b></p>
        <p id="heading" style="padding-left: 1px;"> Welcome back! <img src="" alt=""> </p>

        <div class="input">
          <input type="email" 
                 placeholder="Email address" 
                 name="email" 
                 value="" 
                 required 
                 autocomplete="email"
                 aria-label="Email address"
                 pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
          />
        </div>
        <div class="password">
          <input type="password" 
                 placeholder="Password" 
                 name="password" 
                 id="password" 
                 required 
                 autocomplete="current-password"
                 aria-label="Password"
          />
          <i class="uil uil-eye-slash" id="showpassword" style="position: absolute; right: 10px; top: 45; transform: translateY(-40%); cursor: pointer;" aria-hidden="true"></i>

          <?php
          if ($msg == 1) {
            echo '<p class="error">Incorrect password, try again.</p>';
          } else if ($msg == 2) {
            echo '<p class="error">Account does not exist.</p>';
          }
          ?>
        </div>

        <div class="forgot-password">
          <a href="forgot-password.php" style="color: #06C167; font-size: 14px;">Forgot Password?</a>
        </div>

        <div class="btn">
          <button type="submit" name="sign">Sign in</button>
        </div>
        <br>
        <p style="text-align: center; margin-top: 10px;">Don't have an account? <a href="signup.php" style="color: #06C167;">Sign up</a></p>
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

    // Prevent form resubmission on page refresh
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
  </script>
  <script src="login.js"></script>
</body>

</html>