<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Trainer</title>
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
        margin: 0;
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
    input[type='text'] {
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
    <?php
            // Connect to the database
        $connection = mysqli_connect("localhost", "root", "", "Coaches");

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_trainer'])) {
            // Sanitize user input
            $trainerId = mysqli_real_escape_string($connection, $_POST['id']);

            // Retrieve member details for editing
            $editQuery = "SELECT * FROM trainers WHERE id = '$trainerId'";
            $editResult = mysqli_query($connection, $editQuery);

            if ($editResult) {
                $editedTrainer = mysqli_fetch_assoc($editResult);

        echo "<div class='container card'>";
            echo "<h3>Update Trainer</h3>";
            echo "<form action='update_trainer.php' method='post'>";
            
            echo "<input type='hidden' name='id' value='" . $editedTrainer['id'] . "'>";
                echo "<div class='form-group'>";
                    echo "<label for='name'>Name:</label>";
                    echo "<input type='text' name='name' value='" . $editedTrainer['name'] . "' required><br>";
                    echo "</div>";

                    echo "<div class='form-group'>";
                    echo "<label for='specialty'>Specialty:</label>";
                    echo "<input type='text' name='specialty' value='" . $editedTrainer['specialty'] . "' required><br>";
                    echo "</div>";

                    echo "<div class='form-group'>";
                    echo "<label for='name'>Schedule Start:</label>";
                    echo "<input type='text' name='schedule_start' value='" . $editedTrainer['schedule_start'] . "' required><br>";
                    echo "</div>";

                    echo "<div class='form-group'>";
                    echo "<label for='name'>Schedule End:</label>";
                    echo "<input type='text' name='schedule_end' value='" . $editedTrainer['schedule_end'] . "' required><br>";
                    echo "</div>";

                    echo "<div class='form-group'>";
                    echo "<label for='name'>Time In:</label>";
                    echo "<input type='time' name='time_in' value='" . $editedTrainer['time_in'] . "' required><br>";
                    echo "</div>";

                    echo "<div class='form-group'>";
                    echo "<label for='name'>Time Out:</label>";
                    echo "<input type='time' name='time_out' value='" . $editedTrainer['time_out'] . "' required><br>";
                    echo "</div>";
                    
                    echo "<button type='submit' name='update_trainer'>Update trainer</button>";
                echo "</form>";
        echo "</div>";
                } else {
                    echo "Error: " . mysqli_error($connection);
                }
            }

            // Close the database connection
            mysqli_close($connection);
    ?>
</body>
</html>


