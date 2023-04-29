<?php require("connect.php"); ?>
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
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
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
                </ul>
                <button type="button" class="btn btn-primary px-2 py-1" onclick="window.location.assign('login.php')">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </div>
        </div>
    </nav>
    <div class="container">
        <h2 class="my-4">Available Blood Samples</h2>
        <?php  
        //deleting expired blood samples from database before displaying
        // Get the current date and time
        $currentDate = date('Y-m-d');      
        // SQL query to delete the rows
        $sql = "DELETE FROM bloodinfo WHERE expiration_date < '$currentDate'";
        // Execute the query
        if ($conn->query($sql) !== TRUE) {
            echo "Error deleting records of expired blood. ";
        }
        // Fetch blood samples from database
        $sql = "SELECT * FROM bloodinfo ORDER BY blood_info_id DESC";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // Output table header
            echo "<table class='table'>";
            echo "<thead><tr><th>S.No</th><th>Blood Type</th><th>Hospital</th><th>Quantity (in ml)</th><th>Expiration Date</th><th>Request Blood Sample</th></tr></thead>";
            $i = 1;
            // Output table rows
            while ($row = $result->fetch_assoc()) {
                $hospital_query = "SELECT * FROM hospital WHERE hospital_id = {$row['hospital_id']};";
                $hospital_details = $conn->query($hospital_query)->fetch_assoc();
                echo "<tr>";
                echo "<td>" . $i . "</td>";
                echo "<td>" . $row["blood_type"] . "</td>";
                echo <<<details
                <td>{$hospital_details['hospital_name']}<br>{$hospital_details['address']} {$hospital_details['city']} {$hospital_details['state']} {$hospital_details['zip_code']}<br>Contact: {$hospital_details['phone_number']}</td>
details;
                echo "<td>" . $row["quantity"] . "</td>";
                echo "<td>" . $row["expiration_date"] . "</td>";
                echo "<td><button class='btn btn-danger' disabled>Request</button><br><span style='color: red; font-size:small;'>Login to Request</span></td>";
                echo "</tr>";
                $i++;
            }
            echo "</table>";
        } else {
            echo "No blood samples available.";
        }
        // Close database connection
        $conn->close();
        ?>
    </div>
    <section id="about" class="py-5 ">
        <div class="container my-5">
            <div class="row ">
                <div class="col-md-6">
                    <h2>About Blood Link</h2>
                    <p class="lead">Blood Link is a web application that helps hospitals and receivers to connect with
                        each other. Hospitals can register and add details about blood types, quantity, and expiration
                        date of blood bags. Receivers can search for blood by blood group, city, and state and request
                        for blood online.</p>
                </div>
                <div class="col-md-6">
                    <img src="logo.png" class="img-fluid">
                </div>
            </div>
        </div>
    </section>
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