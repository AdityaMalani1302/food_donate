<?php
session_start();
include("connect.php");
if (!isset($_SESSION['admin_email'])) {
    header("location:signin.php");
    exit();
}

// Handle AJAX requests for chart updates
if(isset($_GET['type']) && isset($_GET['timeFrame'])) {
    $type = $_GET['type'];
    $timeFrame = $_GET['timeFrame'];
    
    $timeCondition = "";
    switch($timeFrame) {
        case 'week':
            $timeCondition = "WHERE date >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
            break;
        case 'month':
            $timeCondition = "WHERE date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
            break;
        case 'quarter':
            $timeCondition = "WHERE date >= DATE_SUB(NOW(), INTERVAL 3 MONTH)";
            break;
        case 'halfyear':
            $timeCondition = "WHERE date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)";
            break;
        case 'year':
            $timeCondition = "WHERE date >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
            break;
    }

    $query = "";
    switch($type) {
        case 'donor':
            $query = "SELECT name, COUNT(*) as count 
                     FROM food_donations 
                     $timeCondition 
                     GROUP BY name, email";
            break;
        case 'food':
            $query = "SELECT type, COUNT(*) as count 
                     FROM food_donations 
                     $timeCondition 
                     GROUP BY type";
            break;
        case 'location':
            $query = "SELECT location, COUNT(*) as count 
                     FROM food_donations 
                     $timeCondition 
                     GROUP BY location";
            break;
    }

    $result = mysqli_query($connection, $query);
    $labels = [];
    $data = [];

    while($row = mysqli_fetch_assoc($result)) {
        $labels[] = $row[$type === 'donor' ? 'name' : ($type === 'food' ? 'type' : 'location')];
        $data[] = $row['count'];
    }

    echo json_encode(['labels' => $labels, 'data' => $data]);
    exit();
}

function getDonationData($connection, $timeFrame = 'all') {
    $timeCondition = "";
    switch($timeFrame) {
        case 'week':
            $timeCondition = "WHERE date >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
            break;
        case 'month':
            $timeCondition = "WHERE date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
            break;
        case 'quarter':
            $timeCondition = "WHERE date >= DATE_SUB(NOW(), INTERVAL 3 MONTH)";
            break;
        case 'halfyear':
            $timeCondition = "WHERE date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)";
            break;
        case 'year':
            $timeCondition = "WHERE date >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
            break;
    }

    $donor_query = "SELECT name, COUNT(*) as donation_count 
                    FROM food_donations 
                    $timeCondition 
                    GROUP BY name, email";
    
    $food_type_query = "SELECT type, COUNT(*) as type_count 
                        FROM food_donations 
                        $timeCondition 
                        GROUP BY type";
    
    $location_query = "SELECT location, COUNT(*) as location_count 
                      FROM food_donations 
                      $timeCondition 
                      GROUP BY location";

    return [
        'donor_data' => mysqli_query($connection, $donor_query),
        'food_type_data' => mysqli_query($connection, $food_type_query),
        'location_data' => mysqli_query($connection, $location_query)
    ];
}

$timeFrame = isset($_GET['timeFrame']) ? $_GET['timeFrame'] : 'all';
$chartData = getDonationData($connection, $timeFrame);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

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

    <style>
        .charts-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            padding: 20px;
            background: #fff;
        }

        .chart-box {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            height: 450px;
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        .chart-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .display-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        .toggle-switch input:checked + .toggle-slider {
            background-color: #06C167;
        }

        .toggle-switch input:checked + .toggle-slider:before {
            transform: translateX(20px);
        }

        .time-filter {
            flex: 1;
            margin-right: 15px;
            padding: 10px;
            background: white;
            border-radius: 10px;
        }

        .time-filter select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            outline: none;
        }

        .chart-container {
            flex: 1;
            position: relative;
            width: 100%;
            height: calc(100% - 120px) !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chart-box canvas {
            max-width: 100%;
            max-height: 100%;
        }

        .chart-title {
            font-size: 1.2rem;
            color: #333;
            text-align: center;
            margin-bottom: 10px;
            padding: 10px 0;
        }

        .dash-content {
            background-color: #f8f9fa;
            padding: 20px;
        }

        .overview {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
                <li><a href="#">
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
                    <i class="uil uil-chart"></i>
                    <span class="text">Donation Analytics</span>
                </div>

                <div class="charts-container">
                    <!-- Donor Distribution Chart -->
                    <div class="chart-box">
                        <h3 class="chart-title">Donations by Donors</h3>
                        <div class="chart-controls">
                            <div class="time-filter">
                                <select onchange="updateChart('donor', this.value)">
                                    <option value="all">All Time</option>
                                    <option value="week">Last Week</option>
                                    <option value="month">Last Month</option>
                                    <option value="quarter">Last Quarter</option>
                                    <option value="halfyear">Last 6 Months</option>
                                    <option value="year">Last Year</option>
                                </select>
                            </div>
                            <div class="display-toggle">
                                <span>Numbers</span>
                                <label class="toggle-switch">
                                    <input type="checkbox" checked onchange="toggleDisplay('donor', this.checked)">
                                    <span class="toggle-slider"></span>
                                </label>
                                <span>Percentage</span>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="donorChart"></canvas>
                        </div>
                    </div>

                    <!-- Food Type Distribution Chart -->
                    <div class="chart-box">
                        <h3 class="chart-title">Types of Food Donated</h3>
                        <div class="chart-controls">
                            <div class="time-filter">
                                <select onchange="updateChart('food', this.value)">
                                    <option value="all">All Time</option>
                                    <option value="week">Last Week</option>
                                    <option value="month">Last Month</option>
                                    <option value="quarter">Last Quarter</option>
                                    <option value="halfyear">Last 6 Months</option>
                                    <option value="year">Last Year</option>
                                </select>
                            </div>
                            <div class="display-toggle">
                                <span>Numbers</span>
                                <label class="toggle-switch">
                                    <input type="checkbox" checked onchange="toggleDisplay('food', this.checked)">
                                    <span class="toggle-slider"></span>
                                </label>
                                <span>Percentage</span>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="foodTypeChart"></canvas>
                        </div>
                    </div>

                    <!-- Location Distribution Chart -->
                    <div class="chart-box">
                        <h3 class="chart-title">Donations by Location</h3>
                        <div class="chart-controls">
                            <div class="time-filter">
                                <select onchange="updateChart('location', this.value)">
                                    <option value="all">All Time</option>
                                    <option value="week">Last Week</option>
                                    <option value="month">Last Month</option>
                                    <option value="quarter">Last Quarter</option>
                                    <option value="halfyear">Last 6 Months</option>
                                    <option value="year">Last Year</option>
                                </select>
                            </div>
                            <div class="display-toggle">
                                <span>Numbers</span>
                                <label class="toggle-switch">
                                    <input type="checkbox" checked onchange="toggleDisplay('location', this.checked)">
                                    <span class="toggle-slider"></span>
                                </label>
                                <span>Percentage</span>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="locationChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="admin.js"></script>
    <script>
    // Register the plugin globally
    Chart.register(ChartDataLabels);

    // Define the data variables globally
    const initialData = {
        donorData: <?php 
            $donors = [];
            $donorCounts = [];
            while($row = mysqli_fetch_assoc($chartData['donor_data'])) {
                $donors[] = $row['name'];
                $donorCounts[] = $row['donation_count'];
            }
            echo json_encode(['labels' => $donors, 'data' => $donorCounts]);
        ?>,
        foodTypeData: <?php 
            $types = [];
            $typeCounts = [];
            while($row = mysqli_fetch_assoc($chartData['food_type_data'])) {
                $types[] = $row['type'];
                $typeCounts[] = $row['type_count'];
            }
            echo json_encode(['labels' => $types, 'data' => $typeCounts]);
        ?>,
        locationData: <?php 
            $locations = [];
            $locationCounts = [];
            while($row = mysqli_fetch_assoc($chartData['location_data'])) {
                $locations[] = $row['location'];
                $locationCounts[] = $row['location_count'];
            }
            echo json_encode(['labels' => $locations, 'data' => $locationCounts]);
        ?>
    };

    // Store display preferences
    const displayPreferences = {
        donor: true,  // Start with percentages by default
        food: true,
        location: true
    };

    function formatLabel(value, ctx, showPercentage) {
        const sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
        if (!showPercentage) {
            return value.toString();
        }
        return (value * 100 / sum).toFixed(1) + '%';
    }

    function createPieChart(canvasId, labels, data, title) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) {
            console.error(`Canvas with id ${canvasId} not found`);
            return null;
        }

        // Destroy existing chart if it exists
        const existingChart = Chart.getChart(ctx);
        if (existingChart) {
            existingChart.destroy();
        }

        const chartType = canvasId.replace('Chart', '');

        try {
            return new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                            '#FF9F40', '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'
                        ],
                        borderWidth: 1,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    layout: {
                        padding: {
                            top: 10,
                            bottom: 40,
                            left: 20,
                            right: 20
                        }
                    },
                    plugins: {
                        datalabels: {
                            formatter: (value, ctx) => formatLabel(value, ctx, displayPreferences[chartType]),
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 12
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                boxWidth: 12,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error(`Error creating chart ${canvasId}:`, error);
            return null;
        }
    }

    function toggleDisplay(chartType, showPercentage) {
        displayPreferences[chartType] = showPercentage;
        const chart = window.charts[chartType];
        if (chart) {
            chart.options.plugins.datalabels.formatter = (value, ctx) => 
                formatLabel(value, ctx, showPercentage);
            chart.update();
        }
    }

    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all charts using the initialData object
        const donorChart = createPieChart('donorChart', initialData.donorData.labels, initialData.donorData.data, 'Donations by Donors');
        const foodTypeChart = createPieChart('foodTypeChart', initialData.foodTypeData.labels, initialData.foodTypeData.data, 'Types of Food Donated');
        const locationChart = createPieChart('locationChart', initialData.locationData.labels, initialData.locationData.data, 'Donations by Location');

        // Store charts in window object for access in updateChart function
        window.charts = {
            donor: donorChart,
            food: foodTypeChart,
            location: locationChart
        };

        // Initialize all toggle switches to unchecked state
        document.querySelectorAll('.toggle-switch input').forEach(toggle => {
            toggle.checked = false;  // Start with numbers
            const chartType = toggle.closest('.chart-box').querySelector('canvas').id.replace('Chart', '');
            toggleDisplay(chartType, false);  // Initialize with numbers
        });
    });

    function updateChart(chartType, timeFrame) {
        fetch(`analytics.php?type=${chartType}&timeFrame=${timeFrame}`)
            .then(response => response.json())
            .then(data => {
                const chart = window.charts[chartType];
                if (chart) {
                    chart.data.labels = data.labels;
                    chart.data.datasets[0].data = data.data;
                    chart.update();
                }
            })
            .catch(error => console.error('Error updating chart:', error));
    }
    </script>
</body>

</html>