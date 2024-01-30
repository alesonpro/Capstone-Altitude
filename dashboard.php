<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}



// Create a connection
$conn = new mysqli("localhost", "root", "", "members");

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get the number of members
$sql = "SELECT COUNT(id) as totalMembers FROM members_list";
$result = $conn->query($sql);

// Check if the query was successful
if ($result) {
    $row = $result->fetch_assoc();
    $totalMembers = $row['totalMembers'];
} else {
    $totalMembers = "Error fetching data";
}
// Close the database connection
$conn->close();

// Create a connection to the second database
$connLogins = new mysqli("localhost", "root", "", "attendance");

// Check the connection to the second database
if ($connLogins->connect_error) {
    die("Connection to logins database failed: " . $connLogins->connect_error);
}

// Query to get login times
$sqlLogins = "SELECT time_in, COUNT(*) as loginCount FROM attendance_table GROUP BY time_in";
$resultLogins = $connLogins->query($sqlLogins);

// Check if the query to the second database was successful
$loginData = array();
if ($resultLogins) {
    while ($rowLogins = $resultLogins->fetch_assoc()) {
        $loginData[$rowLogins['time_in']] = $rowLogins['loginCount'];
    }
} else {
    $loginData['Error'] = "Error fetching login data from logins database";
}

// Close the connection to the second database
$connLogins->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+1p:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/94a2ea5975.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>


        .card-text {
            color: black; 
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
          height: 200px;
          width: 18rem;
          display: flex;
          justify-content: center;
          align-items: center;
        }

        .main-wrap {
          display: flex;
          justify-content: space-evenly;
          align-items: center;
          width: calc(130% - 350px);
          height: 50vh;
        }






    </style>

</head>
<body>
  <style>
    *{
      font-family: 'M PLUS 1p', sans-serif;
    }
  </style>
  <!-- header -->
<div class="dashboard-header">
  <div class="title">
    <h3>Altitude Gym Management</h3>
  </div>
  <div class="logout">
  <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Administrator
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
  </div>
</div>
<!-- end of header -->

<!-- body -->
<div class="body-container">
  <!-- sidenav -->
  <div class="sidenav">
      <a href="dashboard.php"><i class="fa fa-home" aria-hidden="true"></i>Home</a>
      <a href="members.php"><i class="fa fa-users"></i>Members</a>
      <a href="dues.php"><i class="fa fa-credit-card"></i>Dues</a>
      <a href="trainers.php"><i class="fa fa-user"></i>Trainers</a>
      <a href="walk-in.php"><i class='fa-solid fa-person-walking'></i>Walk-in</a>
      <a href="logs.php"><i class="fa-solid fa-right-to-bracket"></i>Logs</a>  
  </div>  
  <!-- end of sidenav -->

  <!-- main content -->
  <div class="content">
    <h3>Welcome back <?php echo $_SESSION["username"]; echo"!";?></h3>
    <hr>
    <div class="main-wrap">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Total Members</h5>
          <p class="card-text display-4" style="color: black;"><?php echo $totalMembers; ?></p>
        </div>
      </div>
      <div class="chart">
        <canvas id="loginTimesChart" height="200" width="400"></canvas>

        <script>
          var ctx = document.getElementById('loginTimesChart').getContext('2d');
          var loginTimesChart = new Chart(ctx, {
              type: 'line',
              data: {
                labels: <?php echo json_encode(array_keys($loginData)); ?>,
                datasets: [{
                  label: 'Number of Logins',
                  data: <?php echo json_encode(array_values($loginData)); ?>,
                  fill: false,
                  borderColor: 'rgba(75, 192, 192, 1)',
                  borderWidth: 1
                }]
              },
              options: {
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              }
          });
        </script>
      </div>
    </div>
    <!-- Add more content here -->

  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  
</body>
</html>