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
    $specialty = mysqli_real_escape_string($connection, $_POST['specialty']);
    $schedule = mysqli_real_escape_string($connection, $_POST['schedule']);
    $time_in = mysqli_real_escape_string($connection, $_POST['time_in']);
    $time_out = mysqli_real_escape_string($connection, $_POST['time_out']);



    // Insert trainer data into the database
    $insertQuery = "INSERT INTO trainers (name, specialty, schedule, time_in, time_out) VALUES ('$name', '$specialty', '$schedule', '$time_in', '$time_out')";
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

        /* Input styles */
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
</style>
<body>
   <div class="container card">
    <h3>Add Trainer</h3>
    <form method="post" action="">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required placeholder="Your Name">
        </div>
        <div class="form-group">
            <label for="specialty">Specialty:</label>
            <input type="text" id="specialty" name="specialty" placeholder="Calisthenics">
        </div>
        <div class="form-group">
            <label for="schedule">Schedule:</label>
            <input type="calendar" id="schedule" name="schedule" placeholder="Monday to Wednesday">
        </div>
        <div class="form-group">
            <label for="time_in">Time-in:</label>
            <input type="time" id="time_in" name="time_in" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="time_out">Time-out:</label>
            <input type="time" id="time_out" name="time_out" class="form-control" required>
        </div>
        <button type="submit">Add Trainer</button>
    </form>
</div>

</body>
</html>
