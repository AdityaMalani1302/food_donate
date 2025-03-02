<?php
session_start(); // Start the session

// Check if the user is logged in using donor-specific session variable
if (!isset($_SESSION['donor_email']) || !$_SESSION['is_donor']) {
    header("Location: signin.php"); // Redirect to login page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery</title>
    <link rel="stylesheet" href="css/home.css">
</head>

<body>
    <style>
        .itm {
            background-color: white;
            display: grid;
        }

        .itm img {
            margin-left: auto;
            margin-right: auto;
        }

        p {
            text-align: center;
            font-size: 30PX;
            color: black;
            margin-top: 50px;
        }

        a {
            text-decoration: underline;
        }

        @media (max-width: 767px) {
            .itm {
                float: left;

            }
        }
    </style>

    <div class="itm">

        <p class="heading">We will reach soon</p>
        <p>"Your donation will be immediately collected and sent to needy people "</p>
        <img src="img/namaste.gif" alt="" width="400" height="400">

        <p>Thank You for Donating <a href="home.php"></a></p>
        <p style="text-align: center;"><a href="home.php">Return to home page</a></p>

    </div>
    <br>




</body>

</html>