<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Members Page</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=M+PLUS+1p:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/94a2ea5975.js" crossorigin="anonymous"></script>
  
    <style>

    *{
      font-family: 'M PLUS 1p', sans-serif;
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
        /* border: 1px solid black; */
        color: black;
        padding: 15px;
        margin-left: 0.7rem;
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
      
      .member-details{
        display: flex;
        flex-direction: column;
        color: black;
        width: 25%;
      } 

      .member-btn{
        display: flex;
        flex-direction: row;
        vertical-align: middle;
        gap: 30px;
      } 

      .member-qr-img{
        width: 150px;
        height: 150px;
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
        width: 100%;
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
      <button class='submit' type="submit">Search</button>
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
          <div class="member-title">
            <h3>Members</h3>
          </div>
          <div class="members-form">
            <form method="post" action="filtered_data_member.php" id="filterForm">
              <select class="form-select" aria-label="Default select example" name="category" id="category" onchange="submitForm()">
                  <option value="" disabled="" selected="">Filter</option>
                  <option value="Student">Student</option>
                  <option value="Regular">Regular</option>
                  <option value="Student/Coach">Student/Coach</option>
                  <option value="Regular/Coach">Regular/Coach</option>
              </select>
            </form>

            <script>
                function submitForm() {
                  document.getElementById("filterForm").submit();
                }
            </script>
          </div>
          <div class="members-btn">
            <button class="add-btn" onclick="window.location.href='add_member.php'">Add Members</button>
          </div>
        </div>
      <div class="divider"></div>

      <?php
      // Connect to the database
      $connection = mysqli_connect("localhost", "root", "", "members");

      // Retrieve member data
      $query = "SELECT * FROM members_list ORDER BY id";
      
      $result = mysqli_query($connection, $query);

      if ($result) {
        if ($result->num_rows > 0) {
           echo '<div class="member-list">';
           while ($row = $result->fetch_assoc()) {
              echo '<div class="member-info">';
                  echo '<div class="member-details">';
                      echo "<h4>Name: " . $row['name'] . "</h4>";
                      echo "<h6>Joining Date: " . date("m-d-Y", strtotime($row['joining_date'])) . "</h6>";
                      echo "<h6>Category: " . $row['Category'] . "</h6>";
                      echo "<h6>Gender: " . $row['gender'] . "</h6>";
                  echo "</div>";

                  echo'<div class="member-btn">';
                        echo "<form class='edit' method='post' action='edit_member.php'>";
                        echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                        echo "<button type='submit' name='edit_member'><i class='fa fa-pencil' aria-hidden='true'></i></button>";
                        echo "</form>";

                        echo "<form class='delete' method='post' action=''>";
                        echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                        echo "<button type='submit' name='delete_member'><i class='fa fa-trash' aria-hidden='true'></i></button>";
                        echo "</form>";
                        
                  echo"</div>";

                  echo '<div class="member-qr">';
                        // Generate and update QR code for each member
                        $memberId = $row['id'];
                        $data = $row['name']; // You can customize this based on your needs

                        $filename = generateAndUpdateQRCode($memberId, $data, $connection);

                        // ... Display the QR code image ...
                        echo '<img class="member-qr-img" src="' . $filename . '" alt="QR Code">';
                  echo "</div>";
                        // ... Display delete and edit buttons ...
              echo "</div>";
              echo"<div class='divider'></div>";
          }
         
        } else {
            echo "<p>No members found.</p>";
        }
        mysqli_free_result($result);
    } else {
        echo "<p>Error: " . mysqli_error($connection) . "</p>";
    }

    // Close the database connection

    // Function to generate QR code and update existing member data
    function generateAndUpdateQRCode($memberId, $data, $conn) {
        include_once('C:\xampp\htdocs\Capstone-Altitude\phpqrcode\qrlib.php');

        $filename = "qrcodes/member_" . $memberId . ".png"; // Unique filename for each member
        QRcode::png($data, $filename, QR_ECLEVEL_L, 4);

        // Read the binary data of the image
        $imageData = file_get_contents($filename);

        // Escape the binary data for use in an SQL statement
        $escapedData = $conn->real_escape_string($imageData);

        // Update the existing member's row with the new QR code data
        $query = "UPDATE members_list SET qrcode = '$escapedData' WHERE id = '$memberId'";
        $result = $conn->query($query);

        if ($result) {
            // echo 'QR Code data updated successfully for member ' . $memberId;
        } else {
            // echo 'Error updating QR Code data: ' . $conn->error;
        }

        return $filename; // Return the filename for later use if needed
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

</body>
</html>
