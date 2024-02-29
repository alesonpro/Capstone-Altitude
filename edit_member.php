<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Members</title>
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

h3{
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
input[type="date"],
select {
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
  margin-right: 10px;
}

/* Button hover effect */
button:hover {
  background-color: black;
}

/* Adjusting anchor tag styles */
a {
  text-decoration: none;
}
</style>
<body>
<?php
// edit_member.php

// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "members");   

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_member'])) {
    // Sanitize user input
    $memberId = mysqli_real_escape_string($connection, $_POST['id']);

    // Retrieve member details for editing
    $editQuery = "SELECT * FROM members_list WHERE id = '$memberId'";
    $editResult = mysqli_query($connection, $editQuery);

    if ($editResult) {
        $editedMember = mysqli_fetch_assoc($editResult);

        // Display a form for editing member details
        echo"<div class='container'>";
            echo"<div class='card'>";
                echo"<h3 class='heading-member'>Edit Member</h3>";

                echo "<form action='update_member.php' method='post'>";
                    echo "<input type='hidden' name='id' value='" . $editedMember['id'] . "'>";

                    echo"<div class='form-group'";
                        echo "<label for='name'>Name:</label>";
                        echo "<input type='text' name='name' value='" . $editedMember['name'] . "'><br>";
                    echo"</div>";

                    echo"<div class='form-group'";
                        echo "<label for='joining_date'>Joining Date:</label>";
                        echo "<input type='date' name='joining_date' value='" . date("Y-m-d", strtotime($editedMember['joining_date'])) . "'  ><br>";
                    echo"</div>";

                    echo"<div class='form-group'";
                        echo "<label for='due_date'>New Due Date:</label>";
                        echo "<input type='date' name='due_date' value='" . date("Y-m-d", strtotime($editedMember['due_date'])) . "'  ><br>";
                    echo"</div>";

                    echo"<div class='form-group'";
                      echo "<label for='email'>Email:</label>";
                      echo "<input type='text' name='email' value='" . $editedMember['email'] . "'><br>";
                    echo"</div>";

                    

                    echo"<div class='form-group'";
                        echo "<label for='Category'>Category:</label>";
                        echo "<select name='Category' required>";
                        echo "<option value='Student' " . ($editedMember['Category'] == 'Student' ? 'selected' : '') . ">Student</option>";
                        echo "<option value='Regular' " . ($editedMember['Category'] == 'Regular' ? 'selected' : '') . ">Regular</option>";
                        echo "<option value='Student w/ Coach' " . ($editedMember['Category'] == 'Student w/ Coach' ? 'selected' : '') . ">Student w/ Coach</option>";
                        echo "<option value='Regular w/ Coach' " . ($editedMember['Category'] == 'Regular w/ Coach' ? 'selected' : '') . ">Regular w/ Coach</option>";
                        echo "</select><br>";
                    echo"</div>";
                    echo"<button type='submit' name='update_member'>Update Member</button>";
                echo "</form>";
            echo"</div>";
        echo"</div>";

    } else {
        echo "Error: " . mysqli_error($connection);
    }
}

// Close the database connection
mysqli_close($connection);
?>
</body>
</html>