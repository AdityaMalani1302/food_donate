<?php
session_start();
include '../connection.php';
$msg = 0;
if (isset($_POST['sign'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    // Debug line - remove in production
    error_log("Attempting admin login for email: " . $email);

    $sql = "SELECT * FROM admin WHERE email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Add debug logging
            error_log("Password verified successfully for admin: " . $email);
            
            $_SESSION['admin_email'] = $email;
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_location'] = $row['location'];
            $_SESSION['Aid'] = $row['Aid'];
            $_SESSION['is_admin'] = true;
            
            // Add exit after header to ensure the script stops executing
            header("Location: admin.php");  // Note: "Location" with capital L
            exit();
        } else {
            error_log("Password verification failed for admin: " . $email);
            $msg = 1;
        }
    } else {
        echo "<h1><center>Account does not exist</center></h1>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/formstyle.css">
    <script src="signin.js" defer></script>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
   
    <title>Admin Login</title>
</head>
<body>
    <div class="container">

        <form action="" id="form" method="post">
            <span class="title">Login</span>
            <br>
            <br>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" placeholder="Enter your email">
                <div class="error"></div>
            </div>

            <label class="textlabel" for="password">Password</label>
             <div class="password">
              
                <input type="password" name="password" id="password" placeholder="Enter your password" required/>
                <i class="uil uil-eye-slash showHidePw" id="showpassword"></i>                
                <?php
                    if($msg==1){
                        echo ' <i class="bx bx-error-circle error-icon"></i>';
                        echo '<p class="error">Password don\'t match.</p>';
                    }
                    ?> 
             </div>         
            <button type="submit" name="sign">Login</button>
        </form>
    </div>
    <script src="../login.js"></script>
</body>
</html>