<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,500;0,700;0,800;1,400;1,600&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.65)),
            url('img/food.jpg') no-repeat center center fixed;
        background-size: cover;
    }

    .logo {
        padding-top: 30px;
        font-size: 35px;
        text-align: center;
        color: #fff;
    }

    .login-container {
        background: rgba(255, 255, 255, 0.95);
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        text-align: center;
        margin-top: 20px;
        width: 90%;
        max-width: 400px;
        backdrop-filter: blur(5px);
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-flex-direction: column;
        -ms-flex-direction: column;
        flex-direction: column;
    }

    .para {
        font-size: 24px;
        margin-bottom: 30px;
    }

    .btn {
        display: inline-block;
        font-size: 18px;
        background: #06C167;
        padding: 12px 30px;
        text-decoration: none;
        font-weight: 500;
        margin: 10px;
        color: #fff;
        letter-spacing: 1px;
        transition: 0.2s;
        border-radius: 5px;
        width: 200px;
    }

    .btn:hover {
        letter-spacing: 2px;
        background: #059656;
    }

    .register-link {
        margin-top: 20px;
        color: #666;
        font-size: 14px;
    }

    .register-link a {
        color: #06C167;
        text-decoration: none;
        font-weight: 500;
    }

    .register-link a:hover {
        text-decoration: underline;
    }

    @media only screen and (max-width: 768px) {
        .logo {
            font-size: 28px;
            padding: 20px;
        }

        .login-container {
            padding: 30px;
            margin-top: 20px;
        }

        .para {
            font-size: 20px;
        }

        .btn {
            font-size: 16px;
            width: 180px;
        }
    }
</style>

<body>
    <p class="logo">Welcome to Food <b style="color: #06C167;">Donate</b></p>

    <div class="login-container">
        <p class="para" style="font-size: 30px;">Login as</p>
        <div class="buttons">
            <a href="home.php" class="btn">Donor Login</a>
            <a href="admin/signin.php" class="btn">Admin Login</a>
        </div>
        <p class="register-link" style="font-size: 15px;">
            Don't have a donor account? <a href="signup.php">Register Now</a>
        </p>
    </div>
</body>

</html>