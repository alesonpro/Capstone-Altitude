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

    // Check if time_out is empty
    $time_out = !empty($_POST['time_out']) ? date("Y-m-d H:i:s", strtotime($_POST['time_out'])) : null;



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
    <!-- <h3>Add Members</h3>
    <form method="post" action="">
        <label>Name:</label>
        <input type="text" name="qr_content" required><br>

        <label>Time in:</label>
        <input type="text" name="time_in" required><br>
        <label>Time out:</label>
        <input type="text" name="time_out"><br>

        <br>

        <button type="submit">Add Member</button>
        <a href="logs.php"><button type="button">Return to Logs</button></a>
    </form> -->
    <form class="container card" method="post" action="">
        <h3>Add Members</h3>
        <div class="form-group">
            <label for="qr_content">Name:</label>
            <input type="text" name="qr_content" required><br>
        </div>

        <div class="form-group">
            <label for="time_in">Time in:</label>
            <input type="datetime-local" name="time_in" required><br>
        </div>

        <!-- <div class="form-group">
            <label for="time_out">Time out:</label>
            <input type="datetime-local" name="time_out" value="<?php echo $time_out_formatted; ?>"><br>
        </div> -->

        <button type="submit">Add Member</button>
        <!-- Add button to return to logs.php -->
        <a href="logs.php"><button type="button">Return to Logs</button></a>
    </form>
</body>
</html>
