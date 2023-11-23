<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $connection = mysqli_connect("localhost", "root", "", "attendance");

    // Check connection
    if ($connection === false) {
        die("Error: Connection error. " . mysqli_connect_error());
    }

    // Sanitize user inputs
    $name = mysqli_real_escape_string($connection, $_POST['qr_content']);
    
    $time_in = mysqli_real_escape_string($connection, $_POST['time_in']);

    $time_out = mysqli_real_escape_string($connection, $_POST['time_out']);

    // Insert member data into the database
    $insertQuery = "INSERT INTO attendance_table (qr_content, time_in, time_out) VALUES ('$name', '$time_in', '$time_out')";
    $result = mysqli_query($connection, $insertQuery);

    if ($result) {
        echo '<script>alert("added successfully.")</script>';
    } else {
        echo "Error: " . mysqli_error($connection);
    }

    // Close the database connection
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Walk-in</title>
</head>
<body>
    <h3>Add Members</h3>
    <form method="post" action="">
        <label>Name:</label>
        <input type="text" name="qr_content" required><br>

        <label>Time in:</label>
        <input type="text" name="time_in" required><br>
        <label>Time out:</label>
        <input type="text" name="time_out"><br>

        <br>

        <button type="submit">Add Member</button>
        <!-- Add button to return to members.php -->
        <a href="logs.php"><button type="button">Return to Logs</button></a>
    </form>
</body>
</html>
