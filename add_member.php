<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
include ('C:\xampp\htdocs\Capstone-Altitude\phpqrcode\qrlib.php');

date_default_timezone_set('Asia/Manila');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $connection = mysqli_connect("localhost", "root", "", "members");

    // Check connection
    if ($connection === false) {
        die("Error: Connection error. " . mysqli_connect_error());
    }

    // Sanitize user inputs
    $name = mysqli_real_escape_string($connection, $_POST['name']);

    $joiningDate = mysqli_real_escape_string($connection, $_POST['joining_date']);

    $gender = mysqli_real_escape_string($connection, $_POST['gender']);
    
    $category = mysqli_real_escape_string($connection, $_POST['category']);

    $dueDate = mysqli_real_escape_string($connection, $_POST['initial_due_date']);

    $email = mysqli_real_escape_string($connection, $_POST['email']);

    // Set initial status based on due date
    $status = "Active";

    // Insert member data into the database
    $insertQuery = "INSERT INTO members_list (name, joining_date, gender, Category, status, due_date, email) VALUES ('$name', '$joiningDate', '$gender', '$category', '$status', '$dueDate', '$email')";
    $result = mysqli_query($connection, $insertQuery);

    if ($result) {
        echo '<script>alert("Member added successfully.")</script>';
    } else {
        echo "Error: " . mysqli_error($connection);
    }

    // Close the database connection
    mysqli_close($connection);


$email = new PHPMailer(TRUE);



if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Connect to your database
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "members";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  // Retrieve user input
  $email = $_POST['email'];

  // Retrieve ID from the database
  $sql = "SELECT id, name FROM members_list WHERE email = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $id = $row["id"];
      $name = $row["name"];

      // Check if email address is valid
      if (!empty($email)) {
          // Generate QR code
          $qrCodeData = $name;
          $qrCodeImagePath = 'qrcodes/member_' . $id . '.png';
          QRcode::png($qrCodeData, $qrCodeImagePath, QR_ECLEVEL_L, 10);

          // Send email
          $mail = new PHPMailer(TRUE);
          $mail->isSMTP();
          $mail->Host = 'smtp.gmail.com';
          $mail->SMTPAuth = true;
          $mail->Username = 'altitudegymqr@gmail.com';
          $mail->Password = 'edfu pgae futw tfxc';
          $mail->SMTPSecure = 'tls';
          $mail->Port = 587;

          $mail->setFrom('altitudegymqr@gmail.com', 'altitude gym');
          $mail->addAddress($email, $name);
          $mail->addAttachment($qrCodeImagePath, 'qr_code.png');
          $mail->isHTML(true);
          $mail->Subject = 'QR Code for ' . $name;
          $mail->Body    = '<html>
                            <head>
                              <title>QR Code Email</title>
                            </head>
                            <body style="font-family: Arial, sans-serif;">

                              <h1 style="color: #333;">Hello ' . $name . ',</h1>
                              
                              <p>Thank you for your interest in Altitude Gym. Here is your QR code for your attendance in the gym</p>
                              
                              <p style="margin-top: 20px;">If you have any questions, feel free to contact us.</p>
                              
                              <p>Best regards,<br>Altitude Gym Team</p>

                            </body>
                            </html>';

          if ($mail->send()) {
              echo '<script>alert("Email sent successfully!")</script>';
          } else {
              echo 'Error: ' . $mail->ErrorInfo;
          }
      } else {
          // echo "Invalid email address retrieved from the database.";
      }

  } else {
      echo "No records found for the provided email address.";
  }

  $conn->close();
}


}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Member</title>
</head>
<style>

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: Arial, sans-serif;
  background-color: #f4f4f4;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

h3{
    color: #740A00;
}

/* Form container */
.container {
  max-width: 400px;
}

/* Card styles */
.card {
  background-color: #fff;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Form group */
.form-group {
  margin-bottom: 15px;
}

/* Label styles */
label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

/* Input and select styles */
input[type="text"],
input[type="date"],
select {
  width: calc(100% - 12px);
  padding: 8px;
  border-radius: 4px;
  border: 1px solid #ccc;
  margin-top: 4px;
}

/* Button styles */
button {
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  color: #fff;
  background-color: #740A00;
  cursor: pointer;
  margin-right: 10px;
}

/* Button hover effect */
button:hover {
  background-color: black;
}

/* Adjusting anchor tag styles */
a {
  text-decoration: none;
}

</style>
<body>
    <div class="container">
        <div class="card">  
            <h3 class="heading-members">Add Member</h3>
            <form method="post" action="">
                <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Juan Dela Cruz" required>
                </div>

                <div class="form-group">
                <label for="joining-date">Joining Date:</label>
                <input type="date" id="joining-date" name="joining_date" required>
                </div>

                <div class="form-group">
                <label for="latest-due-date">Latest Due Date:</label>
                <input type="date" id="latest-due-date" name="initial_due_date" required>
                </div>

                <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="text" id="email" name="email" placeholder="altitude@gmail.com">
                </div>

                <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
                </div>

                <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category" required>
                    <option value="Student">Student</option>
                    <option value="Regular">Regular</option>
                    <option value="Student w/ Coach">Student w/ Coach</option>
                    <option value="Regular w/ Coach">Regular w/ Coach</option>
                </select>
                </div>

                <div class="form-group">
                <button type="submit">Add Member</button>
                <a href="members.php"><button type="button">Return to Members</button></a>
                </div>
            </form>
        </div>
    </div>

<script>
document.getElementById('name').addEventListener('input', function() {
    var inputValue = this.value;
    var words = inputValue.toLowerCase().split(' ');
    for (var i = 0; i < words.length; i++) {
        // Capitalize the first letter of each word
        words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1);
    }
    // Join the words back into a string and set it as the input value
    this.value = words.join(' ');
});
</script>
</body>
</html>