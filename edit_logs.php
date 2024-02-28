<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Walk-in</title>
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
    <?php
            // Connect to the database
        $connection = mysqli_connect("localhost", "root", "", "attendance");

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_logs'])) {
            // Sanitize user input
            $logId = mysqli_real_escape_string($connection, $_POST['id']);
            

            // Retrieve member details for editing
            $editQuery = "SELECT * FROM attendance_table WHERE id = '$logId'";
            $editResult = mysqli_query($connection, $editQuery);

            if ($editResult) {
                $editedLogs = mysqli_fetch_assoc($editResult);


        // Display a form for editing member details
        echo "<form action='update_logs.php' method='post'>";
        echo "<input type='hidden' name='id' value='" . $editedLogs['id'] . "'>";
            echo "<div class='form-group'>";
            echo "<label for='time_out'>Time out:</label>";
            echo "<input type='datetime-local' name='time_out' value='" . $editedLogs['time_out'] . "'><br>";
            echo "</div>";
            echo "<button type='submit' name='update_logs'>Update walk-in</button>";
        echo "</form>";

                } else {
                    echo "Error: " . mysqli_error($connection);
                }
            }

            // Close the database connection
            mysqli_close($connection);
    ?>
</body>
</html>


