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

    // Insert member data into the database
    $insertQuery = "INSERT INTO walk_in (name, time_in) VALUES ('$name', '$time_in')";
    $result = mysqli_query($connection, $insertQuery);

    if ($result) {
        echo '<script>alert("Walk-in added successfully.")</script>';
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
        input[type="text"],
        input[type="time"] {
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
   <div class="container card">
        <h3>Add Walk-in</h3>
        <form method="post" action="">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Juan Dela Cruz" required><br>
            </div>
            <div class="form-group">
                <label for="time_in">Time in:</label>
                <input type="time" name="time_in" required><br>
            </div>
            <button type="submit">Add Member</button>
            <a href="/Capstone-Altitude/scanner/index.html"><button type="button">Return to Home</button></a>
        </form>
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
