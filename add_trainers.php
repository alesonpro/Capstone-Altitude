<?php

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $connection = mysqli_connect("localhost", "root", "", "coaches");

    // Check connection
    if ($connection === false) {
        die("Error: Connection error. " . mysqli_connect_error());
    }

    // Sanitize user inputs
    $name = mysqli_real_escape_string($connection, $_POST['name']);

    // Insert member data into the database
    $insertQuery = "INSERT INTO trainers (name) VALUES ('$name')";
    $result = mysqli_query($connection, $insertQuery);

    if ($result) {
        echo "<script>alert('Trainer added successfully.'); window.location.href='trainers.php';</script>";
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
    <title>Add Trainer</title>
</head>
<body>
    <h3>Add Trainer</h3>
    <form method="post" action="">
        <label>Name:</label>
        <input type="text" name="name" required><br>

        <button type="submit">Add Trainer</button>
    </form>
</body>
</html>
