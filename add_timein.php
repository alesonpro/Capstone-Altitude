<?php

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the attendance database
    $attendanceConnection = mysqli_connect("localhost", "root", "", "attendance");

    // Check attendance database connection
    if ($attendanceConnection === false) {
        die("Error: Connection error to attendance database. " . mysqli_connect_error());
    }

    // Connect to the members database
    $membersConnection = mysqli_connect("localhost", "root", "", "members");

    // Check members database connection
    if ($membersConnection === false) {
        die("Error: Connection error to members database. " . mysqli_connect_error());
    }

    // Sanitize user inputs
    $name = mysqli_real_escape_string($attendanceConnection, $_POST['qr_content']);
    $time_in = mysqli_real_escape_string($attendanceConnection, $_POST['time_in']);
    $time_out = mysqli_real_escape_string($attendanceConnection, $_POST['time_out']);

    // Check if qr_content exists in members_list table
    $checkQuery = "SELECT COUNT(*) AS count FROM members_list WHERE name = '$name'";
    $checkResult = mysqli_query($membersConnection, $checkQuery);
    $row = mysqli_fetch_assoc($checkResult);
    $count = $row['count'];

    if ($count == 0) {
        echo "<script>alert('Member not found in database.'); window.location.href='/Capstone-Altitude/add_timein.php';</script>";
        exit(); // Exit PHP script if member not found
    }

    // Insert member data into the attendance database
    if ($_POST['time_out'] === "null") {
        $insertQuery = "INSERT INTO attendance_table (qr_content, time_in, time_out) VALUES ('$name', '$time_in', NULL)";
    } else {
        $insertQuery = "INSERT INTO attendance_table (qr_content, time_in, time_out) VALUES ('$name', '$time_in', '$time_out')";
    }

    $result = mysqli_query($attendanceConnection, $insertQuery);

    if ($result) {
        echo "<script>alert('Added successfully.'); window.location.href='/Capstone-Altitude/scanner/index.html';</script>";
    } else {
        echo "Error: " . mysqli_error($attendanceConnection);
    }

    // Close the database connections
    mysqli_close($attendanceConnection);
    mysqli_close($membersConnection);
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


