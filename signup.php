<?php
include 'connection.php';

if (isset($_POST['sign'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];
    $errors = [];

    // Validations
    if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $errors[] = "Name must contain only letters.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
        $errors[] = "Password must be at least 8 characters long, include letters, numbers, and special characters.";
    }
    if ($password !== $confirmpassword) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $pass = password_hash($password, PASSWORD_DEFAULT);
        
        // Check if email exists using prepared statement
        $stmt = $connection->prepare("SELECT * FROM login WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $errors[] = "Account already exists";
        } else {
            // Insert new user using prepared statement
            $stmt = $connection->prepare("INSERT INTO login (name, email, password, phone) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $pass, $phone);
            
            if ($stmt->execute()) {
                header("Location: signin.php");
                exit();
            } else {
                $errors[] = "Failed to create account. Please try again.";
            }
        }
    }
}
?>




<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="css/loginstyle.css">
        <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">


    </head>

    <body>

        <div class="container">
            <div class="regform">
                <?php if (!empty($errors)): ?>
                    <div class="error-messages" style="color: red; margin-bottom: 15px; text-align: center;">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="post" class="form1">
                    <p class="logo" style="font-size: 30px;">Food <b style="color: #06C167;">Donate</b></p>

                    <p id="heading">Create your account</p>

                    <div class="input">
                        <label class="textlabel" for="name">Name</label><br>

                        <input type="text" id="name" name="name" placeholder="Enter your name" required />
                    </div>

                    <div class="input">
                        <label class="textlabel" for="phone">Phone Number</label>
                        <input type="tel" maxlength="10" pattern="[0-9]{10}" id="phone" name="phone" placeholder="Enter your phone number" required />

                    </div>
                        <div class="input">
                            <label class="textlabel" for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email" required />

                        </div>
                        <label class="textlabel" for="password">Password</label>
                        <div class="password">
                            <input type="password" name="password" id="password" placeholder="Enter your password" required />
                            <i class="uil uil-eye-slash showHidePw" id="showpassword"></i>
                        </div>

                        <label class="textlabel" for="confirmpassword">Confirm Password</label>
                        <div class="password">
                            <input type="password" name="confirmpassword" id="confirmpassword" placeholder="Confirm your password" required />
                            <i class="uil uil-eye-slash showHidePw" id="showconfirmpassword"></i>
                        </div>


                        <div class="btn">
                            <button type="submit" name="sign">Continue</button>
                        </div>
                        <p style="text-align: center; margin-top: 10px;">Already have an account? <a href="signin.php" style="color: #06C167;">Sign in</a></p>

                </form>
            </div>

        </div>


        <script src="login.js"></script>

    </body>

    </html>