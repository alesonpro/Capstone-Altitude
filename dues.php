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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400&family=Russo+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/94a2ea5975.js" crossorigin="anonymous"></script>

    <style>
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

      .member-details {
        border-bottom: solid black;
        color: black;
        display: flex;
        padding: 15px;
        padding-left: 30px;
        margin-bottom: 20px;
        width: calc(100% - 30px);
        /* gap: 100px; */
      }

      button {
        border-radius: 10px;
      }

      .content{
        overflow: auto;

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
<form class="search" action="members_search.php" method="get">
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
  <h3>Dues</h3>
      <hr>

      <?php
      // Connect to the database
      $connection = mysqli_connect("localhost", "root", "", "members");
      date_default_timezone_set('Asia/Manila');

      // Retrieve member data
$query = "SELECT * FROM members_list ORDER BY name";
$result = mysqli_query($connection, $query);

if ($result) {
  if ($result->num_rows > 0) {
    echo '<div class="member-list">';
    while ($row = $result->fetch_assoc()) {
        echo '<div class="member-details">';
        echo "<h4>Name: " . $row['name'] . "</h4>";

        // Update due date to the current date if it has expired
        $dueDate = ($row['due_date']);
        echo "<h6>Due Date: " . date("m-d-Y", strtotime($dueDate)) . "</h6>";

        // Calculate the status based on the updated due date
        $status = (strtotime($dueDate) >= strtotime($currentDate)) ? 'Active' : 'Expired';
        echo "<h6>Status: " . $status . "</h6>";

        // Add logic to update the status in the database if it's expired
        if ($status === 'Expired') {
            $updateStatusQuery = "UPDATE members_list SET status = 'Expired' WHERE id = " . $row['id'];
            mysqli_query($connection, $updateStatusQuery);
        }

        echo "<form class='delete' method='post' action=''>";
        echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
        echo "<button type='submit' name='delete_member'><i class='fa fa-trash' aria-hidden='true'></i> Delete</button>";
        echo "</form>";

        echo "<form class='pay' method='post' action='pay_dues.php'>";
        echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
        echo "<button type='submit' name='pay_dues'><i class='fa fa-money' aria-hidden='true'></i> Pay</button>";
        echo "</form>";

        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<p>No members found.</p>";
}
mysqli_free_result($result);
} else {
    echo "<p>Error: " . mysqli_error($connection) . "</p>";
}

// Handle member deletion
if (isset($_POST['delete_member'])) {
  $memberId = mysqli_real_escape_string($connection, $_POST['id']);
  $deleteQuery = "DELETE FROM members_list WHERE id = '$memberId'";
  $deleteResult = mysqli_query($connection, $deleteQuery);

  if ($deleteResult) {
    echo "<script>alert('Member deleted successfully.');</script>";
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

  <script>
    function printToPDF() {
   // Redirect to the server-side script to generate the PDF
   window.location.href = 'generate_pdf.php';
}

  </script>
  </div>
  <!-- end of content -->
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  
</body>
</html>