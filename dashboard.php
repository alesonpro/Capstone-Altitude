<?php
session_start();
include('auto_update_status.php');

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
$sqlLogins = "SELECT DATE_FORMAT(time_in, '%Y-%m-%d %H:%i:00') AS time_slot, COUNT(*) AS loginCount FROM attendance_table GROUP BY time_slot";

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

$connection = mysqli_connect("localhost", "root", "", "attendance");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// Calculate the threshold dates for archiving (three days ago, two days ago, and yesterday)
$three_days_ago = date('Y-m-d', strtotime('-3 days'));
$two_days_ago = date('Y-m-d', strtotime('-2 days'));
$yesterday = date('Y-m-d', strtotime('-1 day'));

// Move old records to archive table for three days ago
$query_archive_three_days_ago = "INSERT INTO archive_table (qr_content, time_in, time_out, date)
                            SELECT qr_content, time_in, time_out, date
                            FROM attendance_table
                            WHERE date = '$three_days_ago'";
$result_archive_three_days_ago = mysqli_query($connection, $query_archive_three_days_ago);

if (!$result_archive_three_days_ago) {
    echo "Error archiving old records for three days ago: " . mysqli_error($connection);
    exit();
}

// Delete archived records from the main table for three days ago
$query_delete_three_days_ago = "DELETE FROM attendance_table WHERE date = '$three_days_ago'";
$result_delete_three_days_ago = mysqli_query($connection, $query_delete_three_days_ago);

if (!$result_delete_three_days_ago) {
    echo "Error deleting old records for three days ago: " . mysqli_error($connection);
    exit();
}

// Move old records to archive table for two days ago
$query_archive_two_days_ago = "INSERT INTO archive_table (qr_content, time_in, time_out, date)
                            SELECT qr_content, time_in, time_out, date
                            FROM attendance_table
                            WHERE date = '$two_days_ago'";
$result_archive_two_days_ago = mysqli_query($connection, $query_archive_two_days_ago);

if (!$result_archive_two_days_ago) {
    echo "Error archiving old records for two days ago: " . mysqli_error($connection);
    exit();
}

// Delete archived records from the main table for two days ago
$query_delete_two_days_ago = "DELETE FROM attendance_table WHERE date = '$two_days_ago'";
$result_delete_two_days_ago = mysqli_query($connection, $query_delete_two_days_ago);

if (!$result_delete_two_days_ago) {
    echo "Error deleting old records for two days ago: " . mysqli_error($connection);
    exit();
}

// Move old records to archive table for yesterday
$query_archive_yesterday = "INSERT INTO archive_table (qr_content, time_in, time_out, date)
                            SELECT qr_content, time_in, time_out, date
                            FROM attendance_table
                            WHERE date = '$yesterday'";
$result_archive_yesterday = mysqli_query($connection, $query_archive_yesterday);

if (!$result_archive_yesterday) {
    echo "Error archiving old records for yesterday: " . mysqli_error($connection);
    exit();
}

// Delete archived records from the main table for yesterday
$query_delete_yesterday = "DELETE FROM attendance_table WHERE date = '$yesterday'";
$result_delete_yesterday = mysqli_query($connection, $query_delete_yesterday);

if (!$result_delete_yesterday) {
    echo "Error deleting old records for yesterday: " . mysqli_error($connection);
    exit();
}

// Retrieve member data for today
$query_select = "SELECT * FROM attendance_table WHERE date = CURDATE() ORDER BY date DESC";
$result_select = mysqli_query($connection, $query_select);

$MembersTimeIn = array();

if ($result_select) {
    while ($row = $result_select->fetch_assoc()) {
        $MembersTimeIn[] = $row['time_in'];
    }
} else {
    echo "Error fetching data: " . mysqli_error($connection);
}

// Close the database connection
mysqli_close($connection);
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
    
    *{
      padding: 0;
      margin: 0;
      box-sizing: border-box;
      font-family: 'M PLUS 1p', sans-serif;
    }

    .body-container{
      overflow: auto;
    }

    
    .content{
      overflow: auto;
      height: 80vh;
    }

    .child-parent{
      width: 95%;
      margin: 0 auto;
    }



    /* members css */

    .members-parent{
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: space-around;
      background-color: white;
      border-radius: 20px;
       background-color: #740a00;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
      height: 300px; 
      width: auto;
      margin-top: 10px;
    }

    .members-parent h1{
      color: white;
      text-align: center;
    }

    .members-data{
      display: flex;
      justify-content: space-between;
      text-align: center;
      align-items: center;
      gap: 1rem;
    }

    .title-left{
      color: white;
    }

    .members-card {
      background-color: white;
      color: black;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 20px;
      width: 270px;
    }
    .members-card .card-title {
      font-weight: 500;
      font-size: 1.5rem;
      margin-bottom: 10px;
    }

    .members-card .card-text {
      font-size: 3rem;
      color: black;
    }

    /* graph css */
    .graph-parent{
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 2rem;
      height: 400px; 
      width: auto;
      margin-top: 2rem;
      background-color: white;
      border-radius: 20px;
      background-color: #740a00;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
    }

    .graph-right-parent{
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 2rem;
    }
    


    </style>

</head>
<body>
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
        <div class="child-parent">
          <div class="members-parent">
            <div class="title-left">
              <h1>Members</h1>
            </div>
            <div class="title-right">
              <div class="members-data">
                <div class="members-card">
                  <h5 class="card-title">Total</h5>
                  <p class="card-text display-4"><?php echo $totalMembers; ?></p>
                </div>
                <div class="members-card">
                  <h5 class="card-title">Active</h5>
                  <p class="card-text display-4"><?php echo $activeMembers; ?></p>
                </div>
                <div class="members-card">
                  <h5 class="card-title">Expired</h5>
                  <p class="card-text display-4"><?php echo $expiredMembers; ?></p>
                </div>
              </div>
            </div>    
        </div>

        <div class="graph-parent">
            <div class="graph-left">
              <h1>Analytics</h1>
            </div>
            <div class="graph-right">
              <div class="graph-right-parent">
                <div class="chart">
                  <canvas id="loginTimesChart" height="200" width="400"></canvas>
              
                    <script>
                      Chart.defaults.backgroundColor = '#000000';
                      Chart.defaults.borderColor = '#FFFFFF';
                      Chart.defaults.color = '#FFFFFF';
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
                      var hour = date.getHours(); // Get the hour directly
                      var minute = date.getMinutes(); // Get the minute directly
                      // Round up the hour if minute is past 30
                      if (minute >= 30) {
                          hour++; // Move to the next hour
                      }
                      // Adjust the hour if it's beyond the time slots range
                      if (hour < 7) {
                          hour = 7; // Set it to the first time slot
                      } else if (hour > 22) {
                          hour = 22; // Set it to the last time slot
                      }
                      var index = hour - 7; // Calculate the index based on the hour
                      // Add the login count to the appropriate time slot
                      if (index >= 0 && index < timeSlots.length) {
                          loginDataArray[index] += parseInt(loginData[timestamp]);
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
                                borderColor: 'rgba(255, 255, 255, 1)',
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
                  'rgba(54, 162, 235, 1)', // Blue for Male
                  'rgba(255, 99, 132, 1)', // Red for Female
                  'rgba(75, 192, 192, 1)', // Green for Other
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
              </div>
        </div>

        <div class="child-parent">
          <div class="members-parent">
            <div class="title-left">
              <h1>Staff</h1>
            </div>
            <div class="title-right">
              <div class="members-data">
                <div class="members-card">
                <h5 class="card-title">Coach</h5>
                <p class="card-text display-4"><?php echo $totalTrainers; ?></p>
                </div>
            </div>    
        </div>
  </div>
  <!-- end of main content -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  
</body>
</html>