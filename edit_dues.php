<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Due Date</title>
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

.container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center; 
  gap: 10px;
}

.left-container,
.right-container {
  max-width: 400px;
}

.card {
  background-color: #fff;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h3 {
  color: #740a00;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

input[type="date"] {
  width: calc(100% - 12px);
  padding: 8px;
  border-radius: 4px;
  border: 1px solid #ccc;
  margin-top: 4px;
}

button {
  padding: 10px 20px;
  margin-top: 10px;
  border: none;
  border-radius: 4px;
  color: #fff;
  background-color: #740a00;
  cursor: pointer;
}

button:hover {
  background-color: #5e0700;
}

</style>
<body>
<?php
// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "members");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_dues'])) {
    // Sanitize user input
    $memberId = mysqli_real_escape_string($connection, $_POST['id']);

    // Retrieve member details for editing
    $editQuery = "SELECT * FROM members_list WHERE id = '$memberId'";
    $editResult = mysqli_query($connection, $editQuery);

    if ($editResult) {
        $editedMember = mysqli_fetch_assoc($editResult);

        echo "<div class='container'>";
            echo "<div class='card'>";
                echo "<div class='left-container'>";
                    echo "<h3>Update Due Date</h3>";
                echo "</div>";

                echo "<div class='right-container'>";
                    echo "<form action='update_due_date.php' method='post'>";
                        echo "<input type='hidden' name='id' value='" . $editedMember['id'] . "'>";
                        echo "<div class='form-group'>";
                        echo "<label for='due_date'>Due Date:</label>";
                        echo "<input type='date' name='due_date' value='" . $editedMember['due_date'] . "' required><br>";
                        echo "<button type='submit' name='update_due_date'>Update Due Date</button>";
                    echo "</form>";
                echo "</div>";
            echo "</div>";
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