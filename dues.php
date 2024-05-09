<?php
session_start();
include('auto_update_status.php');

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dues Page</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+1p:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web\css\all.min.css">

    <style>

      *{
        font-family: 'M PLUS 1p', sans-serif;
      }
      .pay{
        padding: 0;
        margin: 0;
      }

      .delete {
        padding: 0;
        margin: 0;
      }

      .edit {
        padding: 0;
        margin: 0
      }

      .member-info {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        color: black;
        padding: 15px;
        padding-left: 30px;
        margin-bottom: 20px;
        width: calc(100% - 30px);
      }

      .divider{
        margin: 0 auto;
        width: 95%;
        border-bottom: 1px solid grey;
        padding-top: 5px;
      }

      .member-details-name{
        display: flex;
        flex-direction:column;
        width: 25%;
      }

      .member-details-name, .member-details-date, .member-details-status{
        color: black;
      }

        
      .member-details{
        display: flex;
        flex-direction: column;
        color: black;
        width: 25%;
      } 

      .member-btn{
        padding-right: 20px;
      } 
      
    
      button {
        border-radius: 10px;
      }

      .content{
        overflow: auto;

      }

      select, option {
        color: black;
      }

      .members-add{
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      
      .members-add #filterForm{
        width: 13%;
      }

      table{
        text-align: center;
      }

      .btn{
        margin: 0 -10px 0 -10px;
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
<form class="search" action="dues_search.php" method="get">
  <input type="text" name="q" placeholder="Search dues">
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
  <div class="members-add">
    <h3>Dues</h3>
    <form method="post" action="filtered_data_dues.php" id="filterForm">
      <select class="form-select" aria-label="Default select example" name="status" id="status" onchange="submitForm()">
          <option value="" disabled selected>Filter</option>
          <option value="Active">Active</option>
          <option value="Expired">Expired</option>
      </select>
    </form>
    
    <div class="invisible"></div>
    <script>
        function submitForm() {
            document.getElementById("filterForm").submit();
        }
    </script>
  </div>
  
  <div class="divider"></div>
      <?php
      $status = isset($_POST['status']) ? $_POST['status'] : '';
      // Connect to the database
$connection = mysqli_connect("localhost", "root", "", "members");
date_default_timezone_set('Asia/Manila');

// Retrieve member data
$query = "SELECT * FROM members_list ORDER BY due_date DESC";
$result = mysqli_query($connection, $query);

// Get current date
$currentDate = date("Y-m-d");

if ($result) {
  if ($result->num_rows > 0) {

  echo '<table class="table table-striped">';
    echo '<thead>';
      echo '<tr>';
      echo '<th>Name</th>';
      echo '<th>Due Date</th>';
      echo '<th>Status</th>';
      echo '<th>Actions</th>';
      echo '</tr>';
    echo '</thead>';
  echo '<tbody>';

  while ($row = $result->fetch_assoc()) {
      echo '<tr>';
      echo '<td>' . $row['name'] . '</td>';
      
      // Update due date to the current date if it has expired
      $dueDate = ($row['due_date']);
      echo '<td>' . date("m-d-Y", strtotime($dueDate)) . '</td>';
      
      // Calculate the status based on the updated due date
      $status = ($dueDate >= $currentDate) ? 'Active' : 'Expired';
      echo '<td>' . $status . '</td>';
      

      echo '<td style="text-align: center;">'; // Center align the actions in each row

        // Container for side-by-side buttons
        echo '<div style="display: flex; justify-content: space-evenly;">';

          // Display edit button
          echo "<form class='edit' method='post' action='edit_dues.php'>";
          echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
          echo "<button type='submit' name='edit_dues' style='background-color: #740A00 !important; color: #fff !important;' class='btn'><i class='fa fa-pencil' aria-hidden='true'></i></button>";
          echo "</form>";

          // Display pay button
          echo "<form class='delete' method='post' action='pay_dues.php' onsubmit='return confirmPayment()'>";
          echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
          echo "<button type='submit' name='pay_dues' style='background-color: #740A00 !important; color: #fff !important;' class='btn'><i class='fa fa-money' aria-hidden='true'></i></button>";
          echo "</form>";

          
          echo "<script>
          function confirmPayment() {
              return confirm('Are you sure you want to pay this member?');
          }
          </script>";

        echo '</div>'; // End of the container
      echo '</td>';    
      echo '</tr>';
  }

  echo '</tbody>';
  echo '</table>';

    } else {
        echo "<p>No members found.</p>";
    }
    mysqli_free_result($result);
  } else {
    echo "<p>Error: " . mysqli_error($connection) . "</p>";
  }

$updateExpiredQuery = "UPDATE members_list SET status = 'Expired' WHERE due_date < '$currentDate'";
$updateActiveQuery = "UPDATE members_list SET status = 'Active' WHERE due_date >= '$currentDate'";

$connection->query($updateExpiredQuery);
$connection->query($updateActiveQuery);

// Close the database connection
$connection->close();
?>

    </div>
    <!-- end of content -->
  </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  
</body>
</html>