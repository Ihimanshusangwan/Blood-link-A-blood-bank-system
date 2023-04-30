<?php
session_start();
  if(isset($_REQUEST['logout'])){
    session_unset();
    session_destroy();
  }
//prevent access of hospital page without login
  if(!isset($_SESSION['user_id'])){
    header("location:login.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Link - A Blood Bank System</title>
    <link rel="shortcut icon" href="logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
  </head>
  <body>
  <nav class="navbar navbar-expand-lg bg-body-tertiary ">
          <div class="container-fluid">
              <a class="navbar-brand" href="#">
                  <img src="logo.png" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
                  Blood Link</a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                  data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                  aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                      <li class="nav-item">
                          <a class="nav-link active" aria-current="page" href="#">Home</a>
                      </li>
                      <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                              aria-expanded="false">
                              Contact Developer
                          </a>
                          <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="https://in.linkedin.com/in/himanshu-sangwan-44b930224" target="blank">LinkedIn</a></li>
                              <li><a class="dropdown-item" href="https://www.instagram.com/ihimanshusangwan/" target="blank">Instagram</a></li>
                          
                          </ul>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link active" aria-current="page" href="#add-blood-info">Add Blood Sample</a>
                      </li>                  
                  </ul>
                  <form method = "get" action=''>
                  <button type="submit" name="logout" class="btn btn-danger px-2 py-1">
                    Logout
                  </button>
                  </form>
  
              </div>
          </div>
      </nav>
  <?php 
require("connect.php");
  $user_id = $_SESSION["user_id"];
  $sql = "SELECT hospital_id FROM hospital WHERE user_id= $user_id;";
  $result = $conn->query($sql);
  $data = $result->fetch_assoc();
  $hospital_id = $data['hospital_id'];

  // Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Initialize variables with form data
  $blood_type = $_POST['blood_type'];
  $quantity = $_POST['quantity'];
  $expiration_date = $_POST['expiration_date'];

  // Validate the input
  $errors = array();
  if (empty($blood_type)) {
      $errors[] = "Blood type is required";
  }
  if (empty($quantity)) {
      $errors[] = "Quantity is required";
  }
  if (empty($expiration_date)) {
      $errors[] = "Expiration date is required";
  }
  if (count($errors) == 0) {
      // Prepare the SQL statement
      $stmt = $conn->prepare("INSERT INTO bloodinfo (hospital_id, blood_type, quantity, expiration_date) VALUES (?, ?, ?, ?)");

      // Bind the parameters
      $stmt->bind_param("isss", $hospital_id, $blood_type, $quantity, $expiration_date);

      // Execute the statement
      if ($stmt->execute()) {
          // Record inserted successfully
          echo "Record inserted successfully";
      } else {
          // Error inserting record
          echo "Error inserting record: " . $stmt->error;
      }

      // Close the statement
      $stmt->close();
  } else {
      // Display errors
      foreach ($errors as $error) {
          echo $error . "<br>";
      }
  }
}

?>

<div class="container mt-4">
<h2>Blood Requests</h2>
<?php
// Fetch blood request from database
$sql =  "SELECT 
r.full_name,r.phone_number,b.blood_type,b.quantity,b.expiration_date,rq.request_date_time
FROM request rq
JOIN receiver r ON rq.receiver_id = r.receiver_id
JOIN bloodinfo b ON rq.blood_info_id = b.blood_info_id
JOIN hospital h ON b.hospital_id = h.hospital_id
WHERE h.hospital_id = $hospital_id ORDER BY request_id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output table header
    echo "<table class='table'>";
    echo "<thead><tr><th>S.No</th><th>Blood Type</th><th>Reciever</th><th>Quantity (in ml)</th><th>Expiration Date</th></th><th>Request Date Time</th><th>Contact</th></tr></thead>";
    $i=1;
    // Output table rows
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" .$i. "</td>";
        echo "<td>" . $row["blood_type"] . "</td>";
        echo "<td>" . $row['full_name'] . "</td>";
        echo "<td>" . $row["quantity"] . "</td>";
        echo "<td>" . $row["expiration_date"] . "</td>";
        echo "<td>" . $row["request_date_time"] . "</td>";
        echo "<td>" . $row["phone_number"] . "</td>";
        echo "</tr>";
        $i++;
    }
    echo "</table>";
} else {
    echo "No blood request available.";
}
 // Close database connection
 $conn->close();
?>
</div>
<div class="container my-5" id="add-blood-info">
    <h1 class="text-center mb-4">Add Blood Sample Details</h1>
    <form method="POST" action="">
        <div class="form-group my-2">
            <label for="blood_type">Blood Type:</label>
            <select class="form-control" id="blood_type" name="blood_type" required>
                <option value="" disabled selected>Select Blood Type</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>
        </div>
        <div class="form-group my-2">
            <label for="quantity">Quantity (in ml):</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
        </div>
        <div class="form-group my-2">
            <label for="expiration_date">Expiration date:</label>
            <input type="date" class="form-control" id="expiration_date" name="expiration_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Add</button>
    </form>
</div>

<footer class="bg-dark text-white py-2">
        <div class="container ">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; 2023 Blood Link</p>
                </div>
                <div class="col-md-6">
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <a href="https://github.com/Ihimanshusangwan" class="text-white" target="blank"><i
                                    class="fab fa-github fa-2x"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="https://in.linkedin.com/in/himanshu-sangwan-44b930224" class="text-white" target="blank"><i
                                    class="fab fa-linkedin fa-2x"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="https://www.instagram.com/ihimanshusangwan/" class="text-white" target="blank"><i
                                    class="fab fa-instagram fa-2x"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <script src="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
