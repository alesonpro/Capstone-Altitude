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
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    
    $time_in = mysqli_real_escape_string($connection, $_POST['time_in']);

    $time_out = mysqli_real_escape_string($connection, $_POST['time_out']);

    // Insert member data into the database
    $insertQuery = "INSERT INTO walk_in (name, time_in, time_out) VALUES ('$name', '$time_in', '$time_out')";
    $result = mysqli_query($connection, $insertQuery);

    if ($result) {
        echo 'alert("Walk-in added successfully.")';
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
    <h3>Add Walk-in</h3>
    <form method="post" action="">
        <label>Name:</label>
        <input type="text" name="name" required><br>

        <label>Time in:</label>
        <input type="text" name="time_in" required><br>
        <label>Time out:</label>
        <input type="text" name="time_out" required><br>

        <br>

        <button type="submit">Add Member</button>
        <!-- Add button to return to members.php -->
        <a href="walk-in.php"><button type="button">Return to Walk-in</button></a>
    </form>
</body>
</html>
