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

// Create a connection to the database
$connLogins = new mysqli("localhost", "root", "", "attendance");

// Check the connection
if ($connLogins->connect_error) {
    die("Connection to the database failed: " . $connLogins->connect_error);
}

// Query to get login times
$sqlLogins = "SELECT DATE_FORMAT(time_in, '%Y-%m-%d %H:00:00') AS time_slot, COUNT(*) AS loginCount FROM attendance_table GROUP BY time_slot";
$resultLogins = $connLogins->query($sqlLogins);

// Check if the query was successful
$loginData = array();
if ($resultLogins) {
    while ($rowLogins = $resultLogins->fetch_assoc()) {
        // Store the login count for each time slot
        $loginData[$rowLogins['time_slot']] = $rowLogins['loginCount'];
    }
} else {
    // Handle error if the query fails
    die("Error fetching login data from the database: " . $connLogins->error);
}

// Close the database connection
$connLogins->close();


$connActive = new mysqli("localhost", "root", "", "members");

// Check the connection
if ($connActive->connect_error) {
    die("Connection failed: " . $connActive->connect_error);
}

// Query to get the number of members
$sqlActive = "SELECT COUNT(*) as activeMembers FROM members_list WHERE status = 'Active'";
$resultActive = $connActive->query($sqlActive);

// Check if the query was successful
if ($resultActive) {
    $row = $resultActive->fetch_assoc();
    $activeMembers = $row['activeMembers'];
} else {
    $activeMembers = "Error fetching data";
}
// Close the database connection
$connActive->close();


$connExpired = new mysqli("localhost", "root", "", "members");

// Check the connection
if ($connExpired->connect_error) {
    die("Connection failed: " . $connExpired->connect_error);
}

// Query to get the number of members
$sqlExpired = "SELECT COUNT(*) as expiredMembers FROM members_list WHERE status = 'Expired'";
$resultActive = $connExpired->query($sqlExpired);

// Check if the query was successful
if ($resultActive) {
    $row = $resultActive->fetch_assoc();
    $expiredMembers = $row['expiredMembers'];
} else {
    $expiredMembers = "Error fetching data";
}
// Close the database connection
$connExpired->close();


$connTrainers = new mysqli("localhost", "root", "", "coaches");

// Check the connection
if ($connTrainers->connect_error) {
    die("Connection failed: " . $connTrainersconn->connect_error);
}

// Query to get the number of members
$sqlTrainers = "SELECT COUNT(id) as totalTrainers FROM trainers";
$resultTrainers = $connTrainers->query($sqlTrainers);

// Check if the query was successful
if ($resultTrainers) {
    $row = $resultTrainers->fetch_assoc();
    $totalTrainers = $row['totalTrainers'];
} else {
    $totalTrainers = "Error fetching data";
}
// Close the database connection
$connTrainers->close();

$connGender = new mysqli("localhost", "root", "", "members");

// Check the connection
if ($connGender->connect_error) {
    die("Connection failed: " . $connGender->connect_error);
}

// Query to get the count of male members
$sqlMale = "SELECT COUNT(id) as maleCount FROM members_list WHERE gender = 'Male'";
$resultMale = $connGender->query($sqlMale);

// Check if the query was successful
if ($resultMale) {
    $rowMale = $resultMale->fetch_assoc();
    $maleCount = $rowMale['maleCount'];
} else {
    $maleCount = "Error fetching male data";
}

// Query to get the count of female members
$sqlFemale = "SELECT COUNT(id) as femaleCount FROM members_list WHERE gender = 'Female'";
$resultFemale = $connGender->query($sqlFemale);

// Check if the query was successful
if ($resultFemale) {
    $rowFemale = $resultFemale->fetch_assoc();
    $femaleCount = $rowFemale['femaleCount'];
} else {
    $femaleCount = "Error fetching female data";
}

// Query to get the count of other gender members
$sqlOther = "SELECT COUNT(id) as otherCount FROM members_list WHERE gender = 'Other'";
$resultOther = $connGender->query($sqlOther);

// Check if the query was successful
if ($resultOther) {
    $rowOther = $resultOther->fetch_assoc();
    $otherCount = $rowOther['otherCount'];
} else {
    $otherCount = "Error fetching other data";
}

// Close the database connection
$connGender->close();
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
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web\css\all.min.css">
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
        flex-direction: column;
        justify-content: space-evenly;
        align-items: center;
        width: calc(130% - 350px);
        height: 50vh;
      }

      .cards{
        display: flex;
        justify-content: space-evenly;
        align-items: center;
        text-align: center;
        padding: 20px;
        gap: 1rem;
        margin-top: 5rem;
      }

      .card{
        width: 200px;
        height: 150px;
        background-color: #740A00;
        color: white;
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
      <!-- cards parent -->
      <div class="cards">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Total Members</h5>
            <p class="card-text display-4" style="color: white;"><?php echo $totalMembers; ?></p>
          </div>
        </div>
  
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Active Members</h5>
            <p class="card-text display-4" style="color: white;"><?php echo $activeMembers; ?></p>
          </div>
        </div>
  
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Expired Members</h5>
            <p class="card-text display-4" style="color: white;"><?php echo $expiredMembers; ?></p>
          </div>
        </div>
  
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Gym Staff</h5>
            <p class="card-text display-4" style="color: white;"><?php echo $totalTrainers; ?></p>
          </div>
        </div>
      </div>
      <!-- chart parent -->
      <div class="charts">
        <div class="chart">
                  <canvas id="loginTimesChart" height="200" width="400"></canvas>
    
                  <script>
    // Your PHP login data
    var loginData = <?php echo json_encode($loginData); ?>;

    // Define time slots from 7AM to 10PM
    var timeSlots = ["7:00 AM", "8:00 AM", "9:00 AM", "10:00 AM", "11:00 AM", "12:00 PM", 
                     "1:00 PM", "2:00 PM", "3:00 PM", "4:00 PM", "5:00 PM", "6:00 PM", 
                     "7:00 PM", "8:00 PM", "9:00 PM", "10:00 PM"];

    // Initialize an array to hold login data for each time slot
    var loginDataArray = Array.from({ length: timeSlots.length }, () => 0);

    // Loop through the login data and map them to their respective time slots
    Object.keys(loginData).forEach(function(timestamp) {
        var date = new Date(timestamp);
        var hour = date.getHours();
        var minute = date.getMinutes();
        
        // Round down the minute to the nearest hour
        if (minute >= 30) {
            hour++; // Move to the next hour
        }

        // Find the index of the corresponding time slot
        var index = hour - 7;
        if (index >= 0 && index < timeSlots.length) {
            // Add the login count to the appropriate time slot
            loginDataArray[index] += loginData[timestamp];
        }
    });

    // Create the chart
    var ctx = document.getElementById('loginTimesChart').getContext('2d');
    var loginTimesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: timeSlots,
            datasets: [{
                label: 'Number of Logins',
                data: loginDataArray,
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 5 // Set the Y-axis interval to 1
                }
            }
        }
    }
});
</script>


        </div>
      </div>
      <div class="chart">
    <canvas id="genderDistributionChart" height="200" width="400"></canvas>
</div>

<script>
    // Your PHP data for the pie chart
    var genderData = {
        labels: ["Male", "Female", "Other"],
        datasets: [{
            data: [<?php echo $maleCount; ?>, <?php echo $femaleCount; ?>, <?php echo $otherCount; ?>],
            backgroundColor: [
                'rgba(54, 162, 235, 0.7)', // Blue for Male
                'rgba(255, 99, 132, 0.7)', // Red for Female
                'rgba(75, 192, 192, 0.7)', // Green for Other
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(75, 192, 192, 1)',
            ],
            borderWidth: 1
        }]
    };

    // Create the pie chart
    var ctxPie = document.getElementById('genderDistributionChart').getContext('2d');
    var genderDistributionChart = new Chart(ctxPie, {
        type: 'pie',
        data: genderData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            title: {
                display: true,
                text: 'Gender Distribution'
            }
        }
    });
</script>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  
</body>
</html>