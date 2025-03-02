<?php
session_start();
include("connect.php");
if (!isset($_SESSION['admin_email']) || !$_SESSION['is_admin']) {
    header("location:signin.php");
    exit();
}
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!----======== CSS ======== -->
    <link rel="stylesheet" href="admin.css">

    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <title>Admin Dashboard Panel</title>

    <?php
    $connection = mysqli_connect("localhost:3306", "root", "", "food_donate");
    $db = mysqli_select_db($connection, 'food_donate');

    ?>
</head>

<body>
    <nav>
        <div class="logo-name">
            <span class="logo_name">ADMIN</span>
        </div>

        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="#">
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
                        <i class="uil uil-user "></i>
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
            <p class="logo">Food <b style="color: #06C167; ">Donate</b></p>
            <p class="user"></p>

        </div>

        <div class="dash-content">
            <div class="overview">
                <div class="title">
                    <i class="uil uil-tachometer-fast-alt"></i>
                    <span class="text">Dashboard</span>
                </div>

                <div class="boxes">
                    <div class="box box1">
                        <i class="uil uil-user"></i>
                        <span class="text">Total Donors</span>
                        <?php
                        $query = "SELECT count(*) as count FROM  login";
                        $result = mysqli_query($connection, $query);
                        $row = mysqli_fetch_assoc($result);
                        echo "<span class=\"number\">" . $row['count'] . "</span>";
                        ?>
                    </div>

                    <div class="box box2">
                        <i class="uil uil-comments"></i>
                        <span class="text">Feedbacks</span>
                        <?php
                        $query = "SELECT count(*) as count FROM  user_feedback";
                        $result = mysqli_query($connection, $query);
                        $row = mysqli_fetch_assoc($result);
                        echo "<span class=\"number\">" . $row['count'] . "</span>";
                        ?>
                    </div>

                    <div class="box box3">
                        <i class="uil uil-heart"></i>
                        <span class="text">Total Donations</span>
                        <?php
                        $query = "SELECT count(*) as count FROM food_donations";
                        $result = mysqli_query($connection, $query);
                        $row = mysqli_fetch_assoc($result);
                        echo "<span class=\"number\">" . $row['count'] . "</span>";
                        ?>
                    </div>

                </div>
            </div>

            <div class="activity">
                <div class="title">
                    <i class="uil uil-clock-three"></i>
                    <span class="text">Recent Donations</span>
                </div>
                <div class="get">
                    <?php
                    // First, let's debug by checking if we have a location value
                    echo "<!-- Debug: Location = " . (isset($_SESSION['location']) ? $_SESSION['location'] : 'not set') . " -->";
                    
                    // Let's first try without the location filter
                    $sql = "SELECT * FROM food_donations ORDER BY date DESC LIMIT 3";
                    $result = mysqli_query($connection, $sql);

                    // Check for errors
                    if (!$result) {
                        die("Error executing query: " . mysqli_error($connection));
                    }

                    // Fetch the data as an associative array
                    $data = array();
                    while ($row = mysqli_fetch_assoc($result)) {
                        $data[] = $row;
                    }

                    // Debug: Print the number of rows found
                    echo "<!-- Debug: Found " . count($data) . " donations -->";
                    ?>

                    <!-- Display the orders in an HTML table -->
                    <div class="table-container">
                        <div class="table-wrapper">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Food</th>
                                        <th>Category</th>
                                        <th>Phone No</th>
                                        <th>Date/Time</th>
                                        <th>Address</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if (empty($data)) {
                                        echo "<tr><td colspan='7' style='text-align: center;'>No recent donations found</td></tr>";
                                    } else {
                                        foreach ($data as $row) { 
                                            echo "<tr>";
                                            echo "<td data-label=\"name\">" . htmlspecialchars($row['name']) . "</td>";
                                            echo "<td data-label=\"food\">" . htmlspecialchars($row['food']) . "</td>";
                                            echo "<td data-label=\"category\">" . htmlspecialchars($row['category']) . "</td>";
                                            echo "<td data-label=\"phoneno\">" . htmlspecialchars($row['phoneno']) . "</td>";
                                            echo "<td data-label=\"date\">" . htmlspecialchars($row['date']) . "</td>";
                                            echo "<td data-label=\"Address\">" . htmlspecialchars($row['address']) . "</td>";
                                            echo "<td data-label=\"quantity\">" . htmlspecialchars($row['quantity']) . "</td>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="admin.js"></script>
</body>

</html>