<!DOCTYPE html>
<html>
<head>
<title>Blood Link - A Blood Bank System</title>
<link rel="shortcut icon" href="logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<?php
require("connect.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_type = filter_var(trim($_POST["userType"]), FILTER_SANITIZE_STRING);
    if ($user_type == "hospital") {
        // Check for empty fields
        if (empty($_POST["hospitalEmail"]) || empty($_POST["hospitalPassword"]) || empty($user_type)) {
            echo "<script>alert('Please fill all required fields.'); window.location.assign('registration.php');</script>";
            exit;
        }
        // Validate email format
        if (!filter_var($_POST["hospitalEmail"], FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Invalid email format.');  window.location.assign('registration.php');</script>";
            exit;
        }
        // Validate and sanitize input fields
        $hospital_name = filter_var(trim($_POST["hospitalName"]), FILTER_SANITIZE_STRING);
        $hospital_email = filter_var(trim($_POST["hospitalEmail"]), FILTER_SANITIZE_EMAIL);
        $hospital_password = filter_var(trim($_POST["hospitalPassword"]), FILTER_SANITIZE_STRING);
        $hospital_address = filter_var(trim($_POST["hospitalAddress"]), FILTER_SANITIZE_STRING);
        $hospital_city = filter_var(trim($_POST["hospitalCity"]), FILTER_SANITIZE_STRING);
        $hospital_state = filter_var(trim($_POST["hospitalState"]), FILTER_SANITIZE_STRING);
        $hospital_zip_code = filter_var(trim($_POST["hospitalZip"]), FILTER_SANITIZE_STRING);
        $hospital_phone_number = filter_var(trim($_POST["hospitalPhone"]), FILTER_SANITIZE_STRING);

        $sql = "SELECT * FROM user WHERE email = '$hospital_email'";
        $result = $conn->query($sql);
        // Check for error in executing the query
        if (!$result) {
            die('Error executing query');
        }
        // Check if the email already exists in the User table
        if ($result->num_rows > 0) {
            echo "<script>alert('username already exists!');  window.location.assign('registration.php');</script>";
            die();
        }

        // Insert data into user table
        $sql = "INSERT INTO user (email, password, user_type)VALUES ('$hospital_email', '$hospital_password', '$user_type')";

        if ($conn->query($sql) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
            exit;
        }
        // Get user_id from user table
        $user_id = $conn->insert_id;

        $sql = "INSERT INTO hospital (user_id, hospital_name, address, city, state, zip_code, phone_number)
                VALUES ('$user_id', '$hospital_name', '$hospital_address', '$hospital_city', '$hospital_state', '$hospital_zip_code', '$hospital_phone_number')";
    } else {
         // Check for empty fields
         if (empty($_POST["receiverEmail"]) || empty($_POST["receiverPassword"]) || empty($user_type)) {
            echo "<script>alert('Please fill all required fields.');
            window.location.assign('registration.php');</script>";
            exit;
        }
        // Validate email format
        if (!filter_var($_POST["receiverEmail"], FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Invalid email format.');  window.location.assign('registration.php');</script>";
            exit;
        }
        // Validate and sanitize input fields
        $receiver_email = filter_var(trim($_POST["receiverEmail"]), FILTER_SANITIZE_EMAIL);
        $receiver_password = filter_var(trim($_POST["receiverPassword"]), FILTER_SANITIZE_STRING);
        $receiver_full_name = filter_var(trim($_POST["receiverFullName"]), FILTER_SANITIZE_STRING);
        $receiver_address = filter_var(trim($_POST["receiverAddress"]), FILTER_SANITIZE_STRING);
        $receiver_city = filter_var(trim($_POST["receiverCity"]), FILTER_SANITIZE_STRING);
        $receiver_state = filter_var(trim($_POST["receiverState"]), FILTER_SANITIZE_STRING);
        $receiver_zip_code = filter_var(trim($_POST["receiverZip"]), FILTER_SANITIZE_STRING);
        $receiver_phone_number = filter_var(trim($_POST["receiverPhone"]), FILTER_SANITIZE_STRING);
        $receiver_blood_group = filter_var(trim($_POST["receiverBloodGroup"]), FILTER_SANITIZE_STRING);

        $sql = "SELECT * FROM user WHERE email = '$receiver_email'";
        $result = $conn->query($sql);
        // Check for error in executing the query
        if (!$result) {
            die('Error executing query');
        }
        // Check if the email already exists in the User table
        if ($result->num_rows > 0) {
            echo "<script>alert('username already exists!');  window.location.assign('registration.php');</script>";
            die();
        }

        // Insert data into user table
        $sql = "INSERT INTO user (email, password, user_type)VALUES ('$receiver_email', '$receiver_password', '$user_type')";

        if ($conn->query($sql) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
            exit;
        }
        // Get user_id from user table
        $user_id = $conn->insert_id;

        $sql = "INSERT INTO receiver (user_id, full_name, address, city, state, zip_code, phone_number, blood_group)
                VALUES ('$user_id', '$receiver_full_name', '$receiver_address', '$receiver_city', '$receiver_state', '$receiver_zip_code', '$receiver_phone_number', '$receiver_blood_group')";
    }
    // Insert data into hospital or receiver table based on user_type
    if ($conn->query($sql) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
        exit;
    }
    echo "<script>alert('Registration successful.');  window.location.assign('login.php');</script>";
    // Close database connection
    $conn->close();
}
?>
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
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Contact Developer
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="https://in.linkedin.com/in/himanshu-sangwan-44b930224">LinkedIn</a></li>
                            <li><a class="dropdown-item" href="https://www.instagram.com/ihimanshusangwan/">Instagram</a></li>                      
                        </ul>
                    </li>                
                </ul>
                <button type="button" class="btn btn-primary px-2 py-1" onclick="window.location.assign('login.php')">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </div>
        </div>
    </nav>
    <div class="container my-5">
        <h1 class="text-center mb-3">Create New Account</h1>
        <div class="card">
            <div class="card-header">
                Registration
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <!-- Account type selector -->
                    <div class="form-group my-3 my-2">
                        <label for="userType">Select account type:</label>
                        <select class="form-control" id="userType" name="userType" required>
                            <option value="" selected disabled>Select Account Type</option>
                            <option value="hospital">Hospital</option>
                            <option value="receiver">Receiver</option>
                        </select>
                    </div>

                    <!-- Hospital registration form fields -->
                    <div id="hospitalFields" class="form-group my-3 my-2" style="display:none">
                        <label for="hospitalName">Hospital name:</label>
                        <input type="text" class="form-control" id="hospitalName" name="hospitalName">

                        <label for="hospitalAddress">Address:</label>
                        <input type="text" class="form-control" id="hospitalAddress" name="hospitalAddress">

                        <label for="hospitalCity">City:</label>
                        <input type="text" class="form-control" id="hospitalCity" name="hospitalCity">

                        <label for="hospitalState">State:</label>
                        <input type="text" class="form-control" id="hospitalState" name="hospitalState">

                        <label for="hospitalZip">ZIP code:</label>
                        <input type="text" class="form-control" id="hospitalZip" name="hospitalZip">

                        <label for="hospitalPhone">Phone number:</label>
                        <input type="text" class="form-control" id="hospitalPhone" name="hospitalPhone">

                        <label for="hospitalEmail">Email address:</label>
                        <input type="email" class="form-control" id="hospitalEmail" name="hospitalEmail">

                        <label for="hospitalPassword">Password:</label>
                        <input type="password" class="form-control" id="hospitalPassword" name="hospitalPassword">

                        <label for="hospitalConfirmPassword">Confirm password:</label>
                        <input type="password" class="form-control" id="hospitalConfirmPassword"
                            name="hospitalConfirmPassword">
                    </div>

                    <!-- Receiver registration form fields -->
                    <div id="receiverFields" class="form-group my-3 my-2">
                        <label for="receiverFullName">Full name:</label>
                        <input type="text" class="form-control" id="receiverFullName" name="receiverFullName">
                        <label for="receiverBloodGroup">Blood group:</label>
                        <select class="form-control" id="receiverBloodGroup" name="receiverBloodGroup">
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                        </select>

                        <label for="receiverAddress">Address:</label>
                        <input type="text" class="form-control" id="receiverAddress" name="receiverAddress">

                        <label for="receiverCity">City:</label>
                        <input type="text" class="form-control" id="receiverCity" name="receiverCity">

                        <label for="receiverState">State:</label>
                        <input type="text" class="form-control" id="receiverState" name="receiverState">

                        <label for="receiverZip">ZIP code:</label>
                        <input type="text" class="form-control" id="receiverZip" name="receiverZip">

                        <label for="receiverPhone">Phone number:</label>
                        <input type="text" class="form-control" id="receiverPhone" name="receiverPhone">

                        <label for="receiverEmail">Email address:</label>
                        <input type="email" class="form-control" id="receiverEmail" name="receiverEmail">

                        <label for="receiverPassword">Password:</label>
                        <input type="password" class="form-control" id="receiverPassword" name="receiverPassword">

                        <label for="receiverConfirmPassword">Confirm password:</label>
                        <input type="password" class="form-control" id="receiverConfirmPassword"
                            name="receiverConfirmPassword">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Create Account</button>
                    </div>

                </form>
            </div>
        </div>
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
                            <a href="https://github.com/Ihimanshusangwan" class="text-white"><i
                                    class="fab fa-github fa-2x"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="https://in.linkedin.com/in/himanshu-sangwan-44b930224" class="text-white"><i
                                    class="fab fa-linkedin fa-2x"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="https://www.instagram.com/ihimanshusangwan/" class="text-white"><i
                                    class="fab fa-instagram fa-2x"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
        <script>
            $(document).ready(function () {
                // Show/hide registration form fields based on account type selection
                $('#userType').on('change', function () {
                    if ($(this).val() == 'hospital') {
                        $('#hospitalFields').show();
                        $('#receiverFields').hide();
                    } else if ($(this).val() == 'receiver') {
                        $('#hospitalFields').hide();
                        $('#receiverFields').show();
                    } else {
                        $('#hospitalFields').hide();
                        $('#receiverFields').hide();
                    }
                });
            });
        </script>
</div>
</body>

</html>