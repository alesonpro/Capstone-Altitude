<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
include ('C:\xampp\htdocs\Capstone-Altitude\phpqrcode\qrlib.php');
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
    $email = $_POST['e-mail'];

    // Retrieve ID from the database
    $sql = "SELECT id, name FROM members_list WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row["id"];
        $name = $row["name"];

        // Generate QR code
        $qrCodeData = $name;
        $qrCodeImagePath = 'qrcodes/member_' . $id . '.png';
        QRcode::png($qrCodeData, $qrCodeImagePath, QR_ECLEVEL_L, 10);

        // Send email
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jay.aldrin.prado@gmail.com';
        $mail->Password = 'wauh eose ttek tgci';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
    
        $mail->setFrom('jay.aldrin.prado@gmail.com', 'Jay Aldrin Prado');
        $mail->addAddress($email, $name);
        $mail->addAttachment($qrCodeImagePath, 'qr_code.png');
        $mail->isHTML(true);
        $mail->Subject = 'QR Code for ' . $name;
        $mail->Body    = 'Please find the QR code attached.';
        
        if ($mail->send()) {
            echo '<script>alert("Email sent successfully!")</script>';
        } else {
            echo 'Error: ' . $mail->ErrorInfo;
        }

    } else {
        echo "No records found for the provided email address.";
    }

    $conn->close();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send QR Code</title>


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
</head>
<body>
    <div class="container">
        <div class="card">  
            <h3 class="heading-members">Input Member Details</h3>
            <form method="post" action="">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="e-mail">E-mail:</label>
            <input type="text" id="e-mail" name="e-mail" required>
        </div>

        <div class="form-group">
            <button type="submit">Generate QR Code and Send Email</button>
        </div>
    </form>
        </div>
    </div>
</body>
</html>