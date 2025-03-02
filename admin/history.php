<?php
session_start();
include("connect.php");
if (!isset($_SESSION['admin_email']) || !$_SESSION['is_admin']) {
    header("location:signin.php");
    exit();
}

// Move the SQL query to the top to get data first
$sql = "SELECT * FROM food_donations";
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <title>Document</title>
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
                <li><a href="#">
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
            <p class="logo">Your <b style="color: #06C167; ">History</b></p>
            <p class="user"></p>
        </div>
        <br>
        <br>
        <br>
        <div class="activity">
            <div class="sorting-container">
                <h3>Filter Donations:</h3>
                <div class="filter-controls">
                    <div class="filter-group">
                        <select id="nameFilter" class="filter-select">
                            <option value="">Select Name</option>
                            <?php
                            if (!empty($data)) {
                                $names = array_unique(array_column($data, 'name'));
                                foreach($names as $name) {
                                    echo "<option value='".htmlspecialchars($name)."'>".htmlspecialchars($name)."</option>";
                                }
                            }
                            ?>
                        </select>

                        <select id="foodFilter" class="filter-select">
                            <option value="">Select Food</option>
                            <?php
                            if (!empty($data)) {
                                $foods = array_unique(array_column($data, 'food'));
                                foreach($foods as $food) {
                                    echo "<option value='".htmlspecialchars($food)."'>".htmlspecialchars($food)."</option>";
                                }
                            }
                            ?>
                        </select>

                        <select id="categoryFilter" class="filter-select">
                            <option value="">Select Category</option>
                            <?php
                            if (!empty($data)) {
                                $categories = array_unique(array_column($data, 'category'));
                                foreach($categories as $category) {
                                    echo "<option value='".htmlspecialchars($category)."'>".htmlspecialchars($category)."</option>";
                                }
                            }
                            ?>
                        </select>

                        <select id="locationFilter" class="filter-select">
                            <option value="">Select Location</option>
                            <?php
                            if (!empty($data)) {
                                $locations = array_unique(array_column($data, 'location'));
                                foreach($locations as $location) {
                                    echo "<option value='".htmlspecialchars($location)."'>".htmlspecialchars($location)."</option>";
                                }
                            }
                            ?>
                        </select>

                        <input type="date" id="dateFilter" class="filter-select">
                    </div>
                    <div class="filter-actions">
                        <button onclick="resetFilters()" class="reset-btn">Reset Filters</button>
                    </div>
                </div>
            </div>
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
                                <th>Location</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($data)) {
                                foreach ($data as $row) {
                                    echo "<tr>
                                        <td data-label=\"name\">" . htmlspecialchars($row['name']) . "</td>
                                        <td data-label=\"food\">" . htmlspecialchars($row['food']) . "</td>
                                        <td data-label=\"category\">" . htmlspecialchars($row['category']) . "</td>
                                        <td data-label=\"phoneno\">" . htmlspecialchars($row['phoneno']) . "</td>
                                        <td data-label=\"date\">" . htmlspecialchars($row['date']) . "</td>
                                        <td data-label=\"Location\">" . htmlspecialchars($row['location']) . "</td>
                                        <td data-label=\"quantity\">" . htmlspecialchars($row['quantity']) . "</td>
                                    </tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
    <script src="admin.js"></script>
    <style>
    .sorting-container {
        margin: 20px 0;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .sorting-container h3 {
        margin-bottom: 15px;
        color: #333;
    }

    .filter-controls {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .filter-group {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-select {
        padding: 8px 12px;
        border: 2px solid #06C167;
        border-radius: 6px;
        background: white;
        color: #333;
        min-width: 150px;
        cursor: pointer;
    }

    .filter-select:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(6, 193, 103, 0.2);
    }

    .reset-btn {
        padding: 8px 16px;
        background: #ff4444;
        border: none;
        border-radius: 6px;
        color: white;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .reset-btn:hover {
        background: #ff0000;
    }

    .hidden-row {
        display: none;
    }
    </style>
    <script>
    function applyFilters() {
        const nameFilter = document.getElementById('nameFilter').value;
        const foodFilter = document.getElementById('foodFilter').value;
        const categoryFilter = document.getElementById('categoryFilter').value;
        const locationFilter = document.getElementById('locationFilter').value;
        const dateFilter = document.getElementById('dateFilter').value;

        const rows = document.querySelectorAll('.table tbody tr');

        rows.forEach(row => {
            const name = row.cells[0].textContent.trim();
            const food = row.cells[1].textContent.trim();
            const category = row.cells[2].textContent.trim();
            const date = row.cells[4].textContent.trim();
            const location = row.cells[5].textContent.trim();

            // Convert date strings to comparable format
            const rowDate = new Date(date).toISOString().split('T')[0];
            
            const matchesName = !nameFilter || name === nameFilter;
            const matchesFood = !foodFilter || food === foodFilter;
            const matchesCategory = !categoryFilter || category === categoryFilter;
            const matchesLocation = !locationFilter || location === locationFilter;
            const matchesDate = !dateFilter || rowDate === dateFilter;

            if (matchesName && matchesFood && matchesCategory && matchesLocation && matchesDate) {
                row.classList.remove('hidden-row');
            } else {
                row.classList.add('hidden-row');
            }
        });
    }

    function resetFilters() {
        document.getElementById('nameFilter').value = '';
        document.getElementById('foodFilter').value = '';
        document.getElementById('categoryFilter').value = '';
        document.getElementById('locationFilter').value = '';
        document.getElementById('dateFilter').value = '';

        const rows = document.querySelectorAll('.table tbody tr');
        rows.forEach(row => row.classList.remove('hidden-row'));
    }

    // Add event listeners to all filters
    document.querySelectorAll('.filter-select').forEach(filter => {
        filter.addEventListener('change', applyFilters);
    });
    </script>
</body>

</html>