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
if ($_POST['time_out'] === "null") {
    $insertQuery = "INSERT INTO attendance_table (qr_content, time_in, time_out) VALUES ('$name', '$time_in', NULL)";
} else {
    $time_out = mysqli_real_escape_string($connection, $_POST['time_out']); // Escape the value
    $insertQuery = "INSERT INTO attendance_table (qr_content, time_in, time_out) VALUES ('$name', '$time_in', '$time_out')";
}

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
    <title>Add Logs</title>
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

h3 {
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
input[type="text"] {
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
    margin-top: 10px;
}

/* Button hover effect */
button:hover {
    background-color: black;
}

/* Adjusting anchor tag styles */
a {
    text-decoration: none;
    display: block;
    margin-top: 10px;
}

</style>
<body>
    <form class="container card" method="post" action="" id="attendanceForm">
    <h3>Add Members</h3>
    <div class="form-group">
        <label for="qr_content">Name:</label>
        <input type="text"  name="qr_content" required><br>
    </div>

    <div class="form-group">
        <label for="time_in">Time in:</label>
        <input type="datetime-local" name="time_in" required><br>
    </div>

    <div class="form-group">
        <input type="hidden" name="time_out" id="time_out_field" value="null">
    </div>

    <button type="submit">Add Member</button>
    <!-- Add button to return to logs.php -->
    <a href="/Capstone-Altitude/scanner/index.html"><button type="button">Return to Home</button></a>
</form>

<script>
    document.getElementById("attendanceForm").addEventListener("submit", function(event) {
    var timeOutField = document.getElementById("time_out_field");
    if (timeOutField.value.trim() === "") {
        timeOutField.value = "null";
    }
});
</script>
</body>
</html>


