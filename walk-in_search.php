<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Walk-in Page</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400&family=Russo+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/94a2ea5975.js" crossorigin="anonymous"></script>


    <style>

        .attendance-details {
          color: black;
          display: flex;
          padding: 15px;
          margin-bottom: 10px;
          width:calc(100% - 30px);
          gap: calc(100% - 800px);
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
<form action="walk-in_search.php" method="get">
  <input type="text" name="q_l" placeholder="Search members">
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
        <h3>Walk-in</h3>
        <hr>
        <?php
// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "attendance");

// Retrieve search query
if (isset($_GET['q'])) {
    $query = $_GET['q'];
  
    // Perform search query
    $searchQuery = "SELECT * FROM walk_in WHERE name LIKE '%$query%'";
    $result = mysqli_query($connection, $searchQuery);
  
    // Display search results
    if ($result->num_rows > 0) {
      echo '<div class="attendance-list">';
      while ($row = $result->fetch_assoc()) {
        echo '<div class="attendance-details">';
        echo "<h4>Name: " . $row['name'] . "</h4>";
        echo "<h6>TIME IN: " . date("h:i A", strtotime($row['time_in'])) . "</h6>";
        echo "<h6>TIME OUT: " . date("h:i A", strtotime($row['time_out'])) . "</h6>";
        echo "</div>";
      }
      echo "</div>";
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

