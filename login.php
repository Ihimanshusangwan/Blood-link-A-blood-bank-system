    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Blood Link - A Blood Bank System</title>
        <link rel="shortcut icon" href="logo.png" type="image/x-icon">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <style>
        #login-card{
            background: linear-gradient(rgb(0,0,0,0.1),rgb(0,0,0,0.1)),url("logo.png");
            background-repeat: no-repeat;
            background-position: center;
        }
        </style>
    </head>
<?php
require("connect.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define variables and initialize with empty values
    $email = $password = $user_type = "";
    $email_err = $password_err = $user_type_err = "";

    // Validate email
    if (empty(trim($_POST["email"])) || !filter_var(trim($_POST["email"]),FILTER_VALIDATE_EMAIL)){
        $email_err = "Please enter your email.";
    } else {
        $email = filter_var(trim($_POST["email"]),FILTER_VALIDATE_EMAIL);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = filter_var(trim($_POST["password"]),FILTER_SANITIZE_STRING);
    }

    // Validate account type
    if (empty($_POST["user_type"])) {
        $user_type_err = "Please choose your account type.";
    } else {
        $user_type = $_POST["user_type"];
    }

    // Check for errors before preparing the SQL statement
    if (empty($email_err) && empty($password_err) && empty($user_type_err)) {
       // Prepare the SQL statement
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ? AND password = ? AND user_type = ?");
        // Bind the parameters
        $stmt->bind_param("sss", $email, $password, $user_type);
        // Execute the statement
        $stmt->execute();

        // Fetch the result
        $result = $stmt->get_result();

        // Check if the user exists
        if ($result->num_rows == 1) {
            $data =$result->fetch_assoc();
            $user_id = $data["user_id"];
            session_start();
            $_SESSION['user_id'] = $user_id;
            if($user_type =="Hospital"){
                header("location:hospital.php");
                exit();
            }
            else{
                header("location:receiver.php");
                exit();
            }    
        } else {
            echo"<script> alert('invalid Username or Password'); </script>";
        }
        // Close the statement
        $stmt->close();
    }
    else{

        echo"there was a error in validating the fields, try again....";
        exit();
    }
}
// Close the connection
$conn->close();
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
            </div>
        </div>
    </nav>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-sm-12">
                <div class="card shadow-lg p-4 " id="login-card">
                    <h1 class="text-center mb-4">Login</h1>
                    <form method="post" action="">
                        <div class="form-group my-3">
                            <label for="user_type">Choose your account type:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="user_type" id="hospital"
                                    value="Hospital" checked>
                                <label class="form-check-label" for="hospital">Hospital</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="user_type" id="receiver"
                                    value="Receiver">
                                <label class="form-check-label" for="receiver">Receiver</label>
                            </div>
                        </div>
                        <div class="form-group my-3">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group my-3">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                        <div class="text-center">
                        <a href="registration.php" class="link-primary">Don't have an account? Sign Up</a>
                        </div>
                    </form>
                </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>