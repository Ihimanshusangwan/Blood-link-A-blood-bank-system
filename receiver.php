<?php
session_start();
  if(isset($_REQUEST['logout'])){
    session_unset();
    session_destroy();
  }
//prevent access of receiver page without login
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
// blood receiving compatibility
  $compatibility = [
    "A+" => ["A+","O+","A-","O-"],
    "O+" => ["O+","O-"],
    "B+" => ["B+","O+","B-","O-"],
    "AB+" => ["A+","B+","AB+","O+","A-","B-","AB-","O-"],
    "A-" => ["A-","O-"],
    "O-" => ["O-"],
    "B-" => ["B-","O-"],
    "AB-" => ["A-","B-","AB-","O-"],
  ];
  require("connect.php");
  $user_id = $_SESSION["user_id"];
  $sql = "SELECT receiver_id,blood_group FROM receiver WHERE user_id= $user_id;";
  $data = $conn->query($sql)->fetch_assoc();
  $receiver_id = $data['receiver_id'];
  $receiver_blood_group = $data['blood_group'];

  if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_REQUEST['request'])){
      $blood_info_id = $_REQUEST['bloodId'];
      $request_date_time = date("Y/m/d H:i:s");
      $sql = "INSERT INTO request(receiver_id, blood_info_id, request_date_time) VALUES ($receiver_id, $blood_info_id, '$request_date_time');";
      if($conn->query($sql)){
          echo"Request Sent Sucessfully";
      }
      else{
          echo"Request Failed";
      }
    }
  }
?>
<div class="container">
<h2>Available Blood</h2>
<?php
// Fetch blood samples from database
$sql = "SELECT * FROM bloodinfo ORDER BY blood_info_id DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // Output table header
    echo "<table class='table'>";
    echo "<thead><tr><th>S.No</th><th>Blood Type</th><th>Hospital</th><th>Quantity (in ml)</th><th>Expiration Date</th><th>Request Blood Sample</th></tr></thead>";
    $i=1;
    // Output table rows
    while($row = $result->fetch_assoc()) {
      //getting hospital details to display in table
        $hospital_query = "SELECT * FROM hospital WHERE hospital_id = {$row['hospital_id']};";
        $hospital_details = $conn->query($hospital_query)->fetch_assoc();
        echo "<tr>";
        echo "<td>" .$i. "</td>";
        echo "<td>" . $row["blood_type"] . "</td>";
        echo <<<details
        <td>{$hospital_details['hospital_name']}<br>{$hospital_details['address']} {$hospital_details['city']} {$hospital_details['state']} {$hospital_details['zip_code']}<br>Contact: {$hospital_details['phone_number']}</td>
details;
        echo "<td>" . $row["quantity"] . "</td>";
        echo "<td>" . $row["expiration_date"] . "</td>";
        //checking blood receive compatibility
        if( in_array($row["blood_type"],$compatibility[$receiver_blood_group])){
          //checking if particular blood sample is already requested or not
            $request_query = "SELECT receiver_id,blood_info_id FROM request WHERE receiver_id = $receiver_id;";
            $request_query_result = $conn->query($request_query);
            if($request_query_result->num_rows > 0){
                $flag = 0;
                while($request_details = $request_query_result->fetch_assoc()){
                    if($row['blood_info_id'] == $request_details['blood_info_id']){
                        $flag =1;//it means sample is already requested
                    }
                }
                if($flag == 0){
                    echo "<td><form action='' method='POST'><button class='btn btn-danger' type='submit' name='request'>Request</button><input type='hidden' value={$row['blood_info_id']} name='bloodId' ></form></td>";
                }
                else{
                    echo "<td><button class='btn btn-warning' disabled>Requested</button></td>";
                }
            }
            else{
                echo "<td><form action='' method='POST'><button class='btn btn-danger' type='submit' name='request'>Request</button><input type='hidden' value={$row['blood_info_id']} name='bloodId' ></form></td>";
            }
        }
        else{
            echo "<td><button class='btn btn-danger' disabled>Request</button><br><span style='color: red; font-size: small;'>Incompatible for $receiver_blood_group</span></td>";
        }
        echo "</tr>";
        $i++;
    }
    
    // Close table
    echo "</table>";
} else {
    echo "No blood samples available.";
}
 // Close database connection
 $conn->close();
?>
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

    <!-- Bootstrap JS -->
    <script src="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>