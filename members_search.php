<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Members Page</title>
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

      .member-info {
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
        width: 100px;
        height: 100px;
      }



      button {
        border-radius: 10px;
      }

      .content{
        overflow: auto;
      }

       table{
        text-align:center;
      }

      .action-parent{
        display: flex;
        align-items: center;
        margin-left: 2.3rem;
        gap: 10px;
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
            <li><a class="dropdown-item" href="login.php">Logout</a></li>
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
    <div class="members-add">
      <h3>Members</h3>
    </div>
  <div class="divider"></div>


  <?php
// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "members");

// Retrieve search query
// if (isset($_GET['q'])) {
//   $query = $_GET['q'];
if (isset($_GET['q'])) {
    $query = $_GET['q'];

  // Perform search query
  $searchQuery = "SELECT * FROM members_list WHERE name LIKE '%$query%'";
  $result = mysqli_query($connection, $searchQuery);
  

  // Display search results
 if ($result) {
        if ($result->num_rows > 0) {
           echo '<div class="member-list">';
           while ($row = $result->fetch_assoc()) {
                $membersData[] = $row; // Store each row in the array
            }
    
            echo '<table class="table table-striped">';
              echo '<thead>';
                echo '<tr>';
                echo '<th>Name</th>';
                echo '<th>Joining Date</th>';
                echo '<th>Category</th>';
                echo '<th>Gender</th>';
                echo '<th>QR</th>';
                echo '<th colspan="2">Actions</th>'; // Set colspan to 2 to accommodate the two buttons
                echo '</tr>';
              echo '</thead>';
            echo '<tbody>';
    
            foreach ($membersData as $row) {
                echo '<tr>';
                echo '<td>' . $row['name'] . '</td>';
                echo '<td>' . date("m-d-Y", strtotime($row['joining_date'])) . '</td>';
                echo '<td>' . $row['Category'] . '</td>';
                echo '<td>' . $row['gender'] . '</td>';
    
                echo '<td>';
                // Display the QR code image
                $memberId = $row['id'];
                $data = $row['name']; // You can customize this based on your needs
                $filename = generateAndUpdateQRCode($memberId, $data, $connection);
                echo '<img class="member-qr-img" src="' . $filename . '" alt="QR Code">';
                echo '</td>';
    
                echo '<td>';
                // Display edit button
                echo "<form class='edit' method='post' action='edit_member.php'>";
                echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                echo "<button type='submit' name='edit_member' style='background-color: #740A00 !important; color: #fff !important;' class='btn'><i class='fa fa-pencil' aria-hidden='true'></i></button>";
                echo "</form>";
                echo '</td>';
    
                echo '<td>';
                // Display delete button
                echo "<form class='delete' method='post' action='' onsubmit='return confirmDelete()'>";
                echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                echo "<button type='submit' name='delete_member' style='background-color: #740A00 !important; color: #fff !important;' class='btn'><i class='fa fa-trash' aria-hidden='true'></i></button>";
                echo "</form>";
                echo '</td>';
                
                // JavaScript function for confirmation
                echo "<script>
                function confirmDelete() {
                    return confirm('Are you sure you want to delete this member?');
                }
                </script>";
    
                echo '</tr>';
            }
    
            echo '</tbody>';
            echo '</table>';
              echo"<div class='divider'></div>";
          }
         
        } else {
            echo "<p>No members found.</p>";
        }
        mysqli_free_result($result);
    } else {
        echo "<p>Error: " . mysqli_error($connection) . "</p>";
    }


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
        echo "<script>alert('Member deleted successfully.');
        window.location.href = window.location.href;</script>";
        exit(); // Stop further execution
    } else {
        echo "<script>alert('Error: " . mysqli_error($connection) . "');</script>";
    }
}

exit();

// Close the database connection
mysqli_close($connection);
?>
  </div>
  <!-- end of content -->
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  
</body>
</html>

