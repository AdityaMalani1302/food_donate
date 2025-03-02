<?php
session_start();

// Check if the user is logged in using donor-specific session variable
if (!isset($_SESSION['donor_email']) || !$_SESSION['is_donor']) {
  // If form was submitted, set a session message
  if (isset($_POST['submit'])) {
    $_SESSION['error'] = "Your session has expired. Please login again to submit the donation.";
  }
  header("Location: signin.php");
  exit(); // Stop further execution
}

include 'connection.php';
include 'includes/email_utils.php';
require_once 'includes/config.php';

$emailid = $_SESSION['donor_email'];

if (isset($_POST['submit'])) {
  $errors = array();

  // Validate food name
  $foodname = trim($_POST['foodname']);
  if (empty($foodname) || strlen($foodname) < 3 || !preg_match("/^[A-Za-z\s]+$/", $foodname)) {
    $errors[] = "Please enter a valid food name";
  }

  // Validate quantity
  $quantity = trim($_POST['quantity']);
  if (!is_numeric($quantity) || $quantity <= 0) {
    $errors[] = "Please enter a valid quantity";
  }

  // Validate phone number
  $phoneno = trim($_POST['phoneno']);
  if (!preg_match("/^[0-9]{10}$/", $phoneno)) {
    $errors[] = "Please enter a valid 10-digit phone number";
  }

  // Validate address
  $address = trim($_POST['address']);
  if (empty($address) || strlen($address) < 10) {
    $errors[] = "Please enter a complete address";
  }

  // Validate name
  $name = trim($_POST['name']);
  if (empty($name) || !preg_match("/^[A-Za-z\s]+$/", $name)) {
    $errors[] = "Please enter a valid name";
  }

  // Check if any validation errors occurred
  if (empty($errors)) {
    $meal = $_POST['meal'];
    $category = $_POST['image-choice'];
    $district = $_POST['district'];

    $query = "INSERT INTO food_donations(email, food, type, category, phoneno, location, address, name, quantity) 
              VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ssssssssi", 
      $emailid, 
      $foodname, 
      $meal, 
      $category, 
      $phoneno, 
      $district, 
      $address, 
      $_SESSION['donor_name'],
      $quantity
    );
    $query_run = $stmt->execute();

    if ($query_run) {
      // Send email notification to admin
      $emailSent = sendDonationNotification(
        $_SESSION['donor_name'],
        $emailid,
        $foodname,
        $quantity,
        $category,
        $district,
        $connection
      );
      
      if (!$emailSent) {
        error_log("Failed to send donation notification email for donation by: " . $emailid);
      }
      
      $_SESSION['success'] = "Food donation submitted successfully!";
      header("location:delivery.php");
      exit();
    } else {
      $errors[] = "Database error: " . mysqli_error($connection);
    }
  }
}
?>

<!-- Add this right after form starts to display errors -->
<?php
if (isset($errors) && !empty($errors)) {
  echo '<div style="color: red; margin-bottom: 15px;">';
  foreach ($errors as $error) {
    echo '<p>' . htmlspecialchars($error) . '</p>';
  }
  echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Food Donate</title>
  <link rel="stylesheet" href="css/loginstyle.css">
</head>

<body style="background-color: #06C167;">
  <div class="container">
    <!-- Add back arrow -->
    <a href="javascript:history.back()" style="position: absolute; top: 30px; left: 520px; color: #000; text-decoration: none; font-size: 24px;">
      &#8592;
    </a>
    <div class="regformf">
      <form action="" method="post" onsubmit="return validateForm()">
        <p class="logo">Food <b style="color: #06C167; ">Donate</b></p>

        <div class="input">
          <label for="foodname"> Food Name:</label>
          <input type="text" id="foodname" name="foodname" maxlength="50" required />
        </div>


        <div class="radio">
          <label for="meal">Meal type :</label>
          <br><br>

          <input type="radio" name="meal" id="veg" value="veg" required />
          <label for="veg" style="padding-right: 40px;">Veg</label>
          <input type="radio" name="meal" id="Non-veg" value="Non-veg">
          <label for="Non-veg">Non-veg</label>

        </div>
        <br>
        <div class="input">
          <label for="food">Select the Category:</label>
          <br><br>
          <div class="image-radio-group">
            <input type="radio" id="raw-food" name="image-choice" value="raw-food">
            <label for="raw-food">
              <img src="img/raw-food.png" alt="raw-food">
            </label>
            <input type="radio" id="cooked-food" name="image-choice" value="cooked-food" checked>
            <label for="cooked-food">
              <img src="img/cooked-food.png" alt="cooked-food">
            </label>
            <input type="radio" id="packed-food" name="image-choice" value="packed-food">
            <label for="packed-food">
              <img src="img/packed-food.png" alt="packed-food">
            </label>
          </div>
          <br>
        </div>
        <div class="input">
          <label for="quantity">Quantity:(number of person /kg)</label>
          <input type="number" id="quantity" name="quantity" required />
        </div>
        <b>
          <p style="text-align: center;">Contact Details</p>
        </b>

        <div class="input">
          <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['donor_name']); ?>" required />
          </div>
          <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['donor_email']); ?>" readonly>
          </div>
          <div>
            <label for="phoneno">PhoneNo:</label>
            <input type="text" id="phoneno" name="phoneno" maxlength="10" pattern="[0-9]{10}" value="<?php echo htmlspecialchars($_SESSION['donor_phone']); ?>" required />
          </div>
        </div>
        <div class="input">
          <label for="location"></label>
          <label for="district">Localities:</label>
          <select id="district" name="district" style="padding:10px;">
            <option value="adarsh-nagar">Adarsh Nagar</option>
            <option value="angol">Angol</option>
            <option value="bhagya-nagar">Bhagya Nagar</option>
            <option value="channamma-nagar">Channamma Nagar</option>
            <option value="club-road">Club Road</option>
            <option value="ganeshpur">Ganeshpur</option>
            <option value="hanuman-nagar">Hanuman Nagar</option>
            <option value="hindwadi">Hindwadi</option>
            <option value="jadhav-nagar">Jadhav Nagar</option>
            <option value="khade-bazar">Khade Bazar</option>
            <option value="khasbag">Khasbag</option>
            <option value="mahantesh-nagar">Mahantesh Nagar</option>
            <option value="mandoli-road">Mandoli Road</option>
            <option value="nehru-nagar">Nehru Nagar</option>
            <option value="peeranwadi">Peeranwadi</option>
            <option value="ramteerth-nagar">Ramteerth Nagar</option>
            <option value="rpd-cross">RPD Cross</option>
            <option value="sadashiv-nagar">Sadashiv Nagar</option>
            <option value="shahapur">Shahapur</option>
            <option value="shivabasava-nagar">Shivabasava Nagar</option>
            <option value="tilakwadi">Tilakwadi</option>
            <option value="udyambag" selected>Udyambag</option>
            <option value="vadgaon">Vadgaon</option>
            <option value="vadgaon-khurd">Vadgaon Khurd</option>
            <option value="vishweswarayya-nagar">Vishweswarayya Nagar</option>
          </select>
          <br>
          <br>

          <label for="address" style="padding-left: 10px;">Address:</label>
          <input type="text" id="address" name="address" maxlength="200" required /><br>




        </div>
        <div class="btn">
          <button type="submit" name="submit"> submit</button>

        </div>
      </form>
    </div>
  </div>

  <script>
    function validateForm() {
      // Food name validation (at least 3 characters, no numbers)
      const foodname = document.getElementById('foodname').value;
      if (!/^[A-Za-z\s]{3,}$/.test(foodname)) {
        alert('Please enter a valid food name (minimum 3 letters, no numbers)');
        return false;
      }

      // Quantity validation (positive numbers only)
      const quantity = document.getElementById('quantity').value;
      if (quantity <= 0) {
        alert('Please enter a valid quantity (greater than 0)');
        return false;
      }

      // Phone number validation
      const phone = document.getElementById('phoneno').value;
      if (!/^[0-9]{10}$/.test(phone)) {
        alert('Please enter a valid 10-digit phone number');
        return false;
      }

      // Address validation (minimum length)
      const address = document.getElementById('address').value;
      if (address.trim().length < 10) {
        alert('Please enter a complete address (minimum 10 characters)');
        return false;
      }

      // Name validation (only letters and spaces)
      const name = document.getElementById('name').value;
      if (!/^[A-Za-z\s]{3,}$/.test(name)) {
        alert('Please enter a valid name (only letters and spaces)');
        return false;
      }

      return true;
    }
  </script>
</body>

</html>