<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logs Page</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400&family=Russo+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web\css\all.min.css">


    <style>
        .attendance-details {
          color: black;
          display: flex;
          padding: 15px;
          margin-bottom: 10px;
          width:calc(100% - 30px);
          gap: calc(100% - 800px);
        }

        
*{
  font-family: 'M PLUS 1p', sans-serif;
}
.divider{
  margin: 0 auto;
  width: 95%;
  border-bottom: 1px solid grey;
  padding-top: 5px;
}


.logs-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: black;
}


.attendance-details{
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px;
  padding-left: 30px;
  margin-bottom: 20px;
  width:calc(100% - 30px);
}

.logs-name, .logs-time{
  color: black;
}
.content{
  overflow: auto;
}

.logs-btn{
  margin-right: 2rem;
}


button {
  border-radius: 10px;  
}

table{
  text-align:center;
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
            <li><a class="dropdown-item" href="login.php">Logout</a></li>
          </ul>
        </li>
      </ul>
  </div>
</div>
<!-- end of header -->

<!-- body -->

<div class="body-container">
<form class="search" action="logs_search.php" method="get">
  <input type="text" name="q" placeholder="Search members">
  <button type="submit">Search</button>
</form>
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
        <h3>Logs</h3>
        <hr>
        <?php
// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "attendance");

// Retrieve search query
if (isset($_GET['q'])) {
    $query = $_GET['q'];
  
    // Perform search query
    $searchQuery = "SELECT * FROM attendance_table WHERE qr_content LIKE '%$query%'";
    $result = mysqli_query($connection, $searchQuery);
  
    // Display search results
    if ($result && mysqli_num_rows($result) > 0) {
      echo '<div class="attendance-table">';
      echo '<div class="container mt-4">';
      echo '    <table class="table">';
      echo '        <thead>';
      echo '            <tr>';
      echo '                <th>Name</th>';
      echo '                <th>Time-in</th>';
      echo '                <th>Time-out</th>';
      echo '                <th>Date</th>';
      echo '                <th>Actions</th>';
      echo '            </tr>';
      echo '        </thead>';
      echo '        <tbody>';
  
      // Loop through each row in the result set
      while ($row = mysqli_fetch_assoc($result)) {
          echo ' <tr>';
          echo '     <td>' . $row['qr_content'] . '</td>';
          echo '     <td>' . date("h:i A", strtotime($row['time_in'])) . '</td>';
          echo '     <td>' . ($row['time_out'] ? date("h:i A", strtotime($row['time_out'])) : 'N/A') . '</td>';
          echo '     <td>' . date("m-d-Y", strtotime($row['date'])) . '</td>';
  
          echo '<td>';
          echo "<form class='edit' method='post' action='edit_logs.php'>";
          echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
          echo "<button type='submit' name='edit_logs' style='background-color: #740A00 !important; color: #fff !important;' class='btn'><i class='fa fa-clock' aria-hidden='true'></i></button>";
          echo "</form>";
          echo '</td>';
          echo ' </tr>';
      }
  
      echo '        </tbody>';
      echo '    </table>';
      echo '</div>';
      echo '</div>';
  } else {
      echo "<p>No members found.</p>";
  }
  }
  

// Close the database connection
mysqli_close($connection);
?>
  </div>
  <!-- end of content -->
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  
</body>
</html>

