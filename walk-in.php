<?php
session_start();
require('C:\xampp\htdocs\Capstone-Altitude\fpdf186\fpdf.php');

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

// Set the timezone to your desired location
date_default_timezone_set('Asia/Macao');

// Connect to the database
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
$query_archive_three_days_ago = "INSERT INTO archive_table_walk_in (name, time_in, time_out, date)
                                SELECT name, time_in, time_out, date
                                FROM walk_in
                                WHERE date = '$three_days_ago'";
$result_archive_three_days_ago = mysqli_query($connection, $query_archive_three_days_ago);

if (!$result_archive_three_days_ago) {
    echo "Error archiving old records for three days ago: " . mysqli_error($connection);
    exit();
}

// Delete archived records from the main table for three days ago
$query_delete_three_days_ago = "DELETE FROM walk_in WHERE date = '$three_days_ago'";
$result_delete_three_days_ago = mysqli_query($connection, $query_delete_three_days_ago);

if (!$result_delete_three_days_ago) {
    echo "Error deleting old records for three days ago: " . mysqli_error($connection);
    exit();
}

// Move old records to archive table for two days ago
$query_archive_two_days_ago = "INSERT INTO archive_table_walk_in (name, time_in, time_out, date)
                                SELECT name, time_in, time_out, date
                                FROM walk_in
                                WHERE date = '$two_days_ago'";
$result_archive_two_days_ago = mysqli_query($connection, $query_archive_two_days_ago);

if (!$result_archive_two_days_ago) {
    echo "Error archiving old records for two days ago: " . mysqli_error($connection);
    exit();
}

// Delete archived records from the main table for two days ago
$query_delete_two_days_ago = "DELETE FROM walk_in WHERE date = '$two_days_ago'";
$result_delete_two_days_ago = mysqli_query($connection, $query_delete_two_days_ago);

if (!$result_delete_two_days_ago) {
    echo "Error deleting old records for two days ago: " . mysqli_error($connection);
    exit();
}

// Move old records to archive table for yesterday
$query_archive_yesterday = "INSERT INTO archive_table_walk_in (name, time_in, time_out, date)
                            SELECT name, time_in, time_out, date
                            FROM walk_in
                            WHERE date = '$yesterday'";
$result_archive_yesterday = mysqli_query($connection, $query_archive_yesterday);

if (!$result_archive_yesterday) {
    echo "Error archiving old records for yesterday: " . mysqli_error($connection);
    exit();
}

// Delete archived records from the main table for yesterday
$query_delete_yesterday = "DELETE FROM walk_in WHERE date = '$yesterday'";
$result_delete_yesterday = mysqli_query($connection, $query_delete_yesterday);

if (!$result_delete_yesterday) {
    echo "Error deleting old records for yesterday: " . mysqli_error($connection);
    exit();
}

// Retrieve member data for today
$query_select = "SELECT * FROM walk_in WHERE date = CURDATE() ORDER BY date DESC";
$result_select = mysqli_query($connection, $query_select);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Walk-in Page</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+1p:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web\css\all.min.css">

    <script language="JavaScript" type="text/javascript">
      function printToPDF() {
        console.log("Print to PDF button clicked");
        // Redirect to the server-side script to generate the PDF
        window.open('generate_pdf_walk-in.php', '_blank');
      }

      function viewArchivedData() {
      // Redirect to the PHP page displaying archived data
      window.location.href = 'display_archived_walk-in.php';
    }

    // Variable to track whether an input is clicked
    var inputClicked = false;

    // Function to refresh the page every 5 seconds (5000 milliseconds)
    function autoRefresh() {
        if (!inputClicked) {
            window.location = window.location.href;
        }
    }
    setInterval(autoRefresh, 5000);

    // Event listener for input type click
    document.addEventListener('click', function(event) {
        var clickedElement = event.target;
        if (clickedElement.tagName.toLowerCase() === 'input') {
            inputClicked = true;
        } else {
            inputClicked = false;
        }
    });

    </script>

    <style>
      *{
        font-family: 'M PLUS 1p', sans-serif;
      }
      .edit {
        padding: 0;
        margin: 0
      }

      .delete {
        padding: 0;
        margin: 0;
      }

      .walkin-content{
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: black;
        
      }

      .walkin-btn{
        margin-right: 2rem;
      }

      .divider{
        margin: 0 auto;
        width: 95%;
        border-bottom: 1px solid grey;
        padding-top: 5px;
      }
    
      .attendance-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        padding-left: 30px;
        margin-bottom: 20px;
        width:calc(100% - 30px);
      }

      .attendance-details, .walk-in-time{
        color: black;
      }

      button {
        border-radius: 10px;
      }

      .walk-in-btn{
        display: flex;
        gap: 10px;
      }

      .walk-in-time{
        align-items: center;
        gap: 20px;
      }

      .content{
         overflow: auto;
      }

      table {
        text-align: center;
      }

      .edit, .delete{
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
<form class="search" action="walk-in_search.php" method="get">
  <input type="text" name="q" placeholder="Search walk-in">
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
      <div class="walkin-content">
        <h3>Walk-in</h3>
        <div class="walkin-btn">
        <button onclick="viewArchivedData()">View Archived Data</button>
          <button onclick="printToPDF()" target="_blank">Print to PDF</button>
        </div>
      </div>
      <div class="divider"></div>
        
        <?php
$query = "SELECT * FROM walk_in";
$result = mysqli_query($connection, $query);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        echo '<div class="attendance-table">';
        echo '<div class="attendance-info">';
        echo '<table class="table table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Name</th>';
        echo '<th>Time-in</th>';
        echo '<th>Time-out</th>';
        echo '<th style="text-align: center;">Actions</th>'; // Center align the header

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $row['name'] . '</td>';

            // Format time_in in AM/PM format
            $timeInFormatted = date("h:i A", strtotime($row['time_in']));
            echo '<td>' . $timeInFormatted . '</td>';

            // Check if time_out is empty before formatting and displaying
            if (!empty($row['time_out'])) {
                // Format time_out in AM/PM format
                $timeOutFormatted = date("h:i A", strtotime($row['time_out']));
                echo '<td>' . $timeOutFormatted . '</td>';
            } else {
                // Display an empty cell if time_out is empty
                echo '<td></td>';
            }

            echo '<td style="text-align: center;">'; // Center align the actions in each row

            // Container for side-by-side buttons
            echo '<div style="display: flex; justify-content: space-evenly;">';

            // Display edit button
            echo "<form class='edit' method='post' action='edit_walk-in.php'>";
            echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
            echo "<button type='submit' name='edit_walk-in' style='background-color: #740A00 !important; color: #fff !important;' class='btn'><i class='fa fa-clock' aria-hidden='true'></i></button>";
            echo "</form>";

            echo '</div>'; // End of the container

            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';

        echo "<div class='divider'></div>";

    }

    echo "</div>";

} else {
    echo "<p>No members found.</p>";
}

// Handle member deletion
if (isset($_POST['delete_member'])) {
    $memberId = mysqli_real_escape_string($connection, $_POST['id']);
    $deleteQuery = "DELETE FROM walk_in WHERE id = '$memberId'";
    $deleteResult = mysqli_query($connection, $deleteQuery);

    if ($deleteResult) {
        echo "<script>alert('Walk-in deleted successfully.');
        window.location.href = window.location.href;</script>";
        exit(); // Stop further execution
    } else {
        echo "<script>alert('Error: " . mysqli_error($connection) . "');</script>";
    }
}

// Close the database connection
mysqli_close($connection);
?>
  </div>
  <!-- end of content -->
</div>




</body>
</html>