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
         .delete {
        padding: 0;
        margin: 0;
      }

      .edit {
        padding: 0;
        margin: 0
      }

       .divider{
        margin: 0 auto;
        width: 95%;
        border-bottom: 1px solid grey;
        padding-top: 5px;
      }
      


      .trainer-info{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        color: black;
        padding: 15px;
        margin-left: 0.7rem;
        padding-left: 30px;
        margin-bottom: 20px;
        width: calc(100% - 30px);
      }

      .trainer-details{
        display: flex;
        flex-direction: column;
        color: black;
        width: 25%;
      } 

      .trainer-btn{
        display: flex;
        flex-direction: row;
        vertical-align: middle;
        gap: 30px;
      } 

      button {
        border-radius: 10px;
      }

      .content{
        overflow: auto;

      }

       .trainers-add{
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin:10px;
       }

       .trainers-add h3{
        color: black;
       }

       .trainers-add button{
        margin-right: 2rem;
       }

       .edit, .delete{
        margin: 0 -1px 0 -1px;
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
<form class="search" action="trainers_search.php" method="get">
  <input type="text" name="q" placeholder="Search trainers">
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
        <h3>Trainers</h3>
        <div class="divider"></div>
        <?php
// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "coaches");

// Retrieve search query
if (isset($_GET['q'])) {
    $query = $_GET['q'];
  
    // Perform search query
    $searchQuery = "SELECT * FROM trainers WHERE name LIKE '%$query%'";
    $result = mysqli_query($connection, $searchQuery);
  
    // Display search results
    if ($result) {
            if ($result->num_rows > 0) {
               echo '<div class="member-list">';
          echo '<div class="trainer-info">';
              echo '<table class="table table-striped">';
              echo '<thead>';
              echo '<tr>';
              echo '<th>Name</th>';
              echo '<th>Specialty</th>';
              echo '<th>Schedule Start</th>';
              echo '<th>Schedule End</th>';
              echo '<th>Time In</th>';
              echo '<th>Time Out</th>';
              echo '<th style="text-align: center;">Actions</th>'; // Center align the header

              while ($row = $result->fetch_assoc()) {
                  echo '<tr>';
                  echo '<td>' . $row['name'] . '</td>';
                  echo '<td>' . $row['specialty'] . '</td>';
                  echo '<td>' . $row['schedule_start'] . '</td>';
                  echo '<td>' . $row['schedule_end'] . '</td>';
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
                    echo "<form class='edit' method='post' action='edit_trainer.php'>";
                    echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                    echo "<button type='submit' name='edit_trainer' style='background-color: #740A00 !important; color: #fff !important;' class='btn'><i class='fa fa-pencil' aria-hidden='true'></i></button>";
                    echo "</form>";

                    // Display delete button
                    echo "<form class='delete' method='post' action='' onsubmit='return confirmDelete()'>";
                    echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                    echo "<button type='submit' name='delete_member' style='background-color: #740A00 !important; color: #fff !important;' class='btn'><i class='fa fa-trash' aria-hidden='true'></i></button>";
                    echo "</form>";

                    echo "<script>
                    function confirmDelete() {
                        return confirm('Are you sure you want to delete this trainer?');
                    }
                    </script>";

                  echo '</div>'; // End of the container

                  echo '</td>';
                  echo '</tr>';
              }

              echo '</tbody>';
              echo '</table>';
              echo '</div>';

                  echo"<div class='divider'></div>";

              }
              echo "</div>";
          } else {
              echo "<p>No members found.</p>";
          }
            mysqli_free_result($result);
          } else {
            echo "<p>Error: " . mysqli_error($connection) . "</p>";
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

