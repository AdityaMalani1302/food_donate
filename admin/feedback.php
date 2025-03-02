<?php
session_start();
include("connect.php");
include("../includes/email_utils.php");

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
                <li><a href="#">
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
            <p class="logo">Feed<b style="color: #06C167; ">back</b></p>
            <p class="user"></p>
        </div>
        <br>
        <br>
        <br>

        <div class="activity">

            <div class="table-container">

                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>name</th>
                                <th>email</th>
                                <th>message</th>
                                <th>Reply</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Handle new replies
                            if (isset($_POST['reply']) && isset($_POST['feedback_id'])) {
                                $reply = mysqli_real_escape_string($connection, $_POST['reply']);
                                $feedback_id = mysqli_real_escape_string($connection, $_POST['feedback_id']);
                                $current_date = date('Y-m-d H:i:s');
                                
                                $update_query = "UPDATE user_feedback 
                                                SET admin_reply = ?, 
                                                    reply_date = ?, 
                                                    notification_status = 'unread' 
                                                WHERE feedback_id = ?";
                                
                                $stmt = mysqli_prepare($connection, $update_query);
                                $stmt->bind_param("ssi", $reply, $current_date, $feedback_id);
                                
                                if ($stmt->execute()) {
                                    // Send email notification to user
                                    if (sendFeedbackReplyNotification($feedback_id, $reply, $connection)) {
                                        $_SESSION['success_message'] = "Reply sent and notification delivered successfully!";
                                    } else {
                                        $_SESSION['success_message'] = "Reply saved but failed to send notification.";
                                    }
                                    echo "<script>
                                            document.querySelector('[data-feedback-id=\"$feedback_id\"]').classList.add('sent');
                                          </script>";
                                } else {
                                    echo "<script>alert('Error sending reply: " . mysqli_error($connection) . "');</script>";
                                }
                                $stmt->close();
                            }

                            // Display feedback table
                            $query = "SELECT * FROM user_feedback";
                            $result = mysqli_query($connection, $query);
                            if ($result == true) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $feedback_id = $row['email'];
                                    $is_sent = !empty($row['admin_reply']) ? 'sent' : '';
                                    
                                    echo "<tr>
                                        <td data-label=\"name\">" . $row['name'] . "</td>
                                        <td data-label=\"email\">" . $row['email'] . "</td>
                                        <td data-label=\"message\">" . $row['message'] . "</td>
                                        <td data-label=\"reply\">
                                            <form method='post' action=''>
                                                <input type='hidden' name='feedback_id' value='" . htmlspecialchars($feedback_id) . "'>
                                                <textarea name='reply' rows='2'>" . htmlspecialchars($row['admin_reply'] ?? '') . "</textarea>
                                                <button type='submit' class='reply-btn $is_sent' data-feedback-id='" . htmlspecialchars($feedback_id) . "'>
                                                    <span class='button-text'>Send Reply</span>
                                                    <span class='button-icon'>✓</span>
                                                </button>
                                            </form>
                                            " . ($row['reply_date'] ? '<div class="reply-date">Replied on: ' . $row['reply_date'] . '</div>' : '') . "
                                        </td>
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
    .reply-date {
        font-size: 12px;
        color: #666;
        margin-top: 5px;
    }

    .reply-btn {
        background-color: #06C167;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 5px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .reply-btn .button-text {
        display: inline-block;
        transition: all 0.3s ease;
    }

    .reply-btn .button-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .reply-btn.sent {
        background-color: #28a745;
        pointer-events: none;
    }

    .reply-btn.sent .button-text {
        opacity: 0;
        transform: translateY(20px);
    }

    .reply-btn.sent .button-icon {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
    }

    textarea {
        width: 100%;
        padding: 5px;
    }

    @keyframes checkmark {
        0% {
            transform: translate(-50%, -50%) scale(0);
            opacity: 0;
        }
        100% {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const button = this.querySelector('.reply-btn');
                if (!button.classList.contains('sent')) {
                    setTimeout(() => {
                        button.classList.add('sent');
                    }, 200);
                }
            });
        });
    });
    </script>
</body>

</html>