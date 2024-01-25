<?php
date_default_timezone_set('Asia/Manila');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $connection = mysqli_connect("localhost", "root", "", "members");

    // Check connection
    if ($connection === false) {
        die("Error: Connection error. " . mysqli_connect_error());
    }

    // Sanitize user inputs
    $name = mysqli_real_escape_string($connection, $_POST['name']);

    $joiningDate = mysqli_real_escape_string($connection, $_POST['joining_date']);

    $gender = mysqli_real_escape_string($connection, $_POST['gender']);
    
    $category = mysqli_real_escape_string($connection, $_POST['category']);

    $dueDate = mysqli_real_escape_string($connection, $_POST['initial_due_date']);

    // Set initial status based on due date
    $status = "Active";

    // Insert member data into the database
    $insertQuery = "INSERT INTO members_list (name, joining_date, gender, Category, status, due_date) VALUES ('$name', '$joiningDate', '$gender', '$category', '$status', '$dueDate')";
    $result = mysqli_query($connection, $insertQuery);

    if ($result) {
        echo '<script>alert("Member added successfully.")</script>';
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
    <title>Add Member</title>
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
    <div class="container">
        <div class="card">  
            <h3 class="heading-members">Add Member</h3>
            <form method="post" action="">
                <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                <label for="joining-date">Joining Date:</label>
                <input type="date" id="joining-date" name="joining_date" required>
                </div>

                <div class="form-group">
                <label for="latest-due-date">Latest Due Date:</label>
                <input type="date" id="latest-due-date" name="initial_due_date" required>
                </div>

                <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
                </div>

                <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category" required>
                    <option value="Student">Student</option>
                    <option value="Regular">Regular</option>
                    <option value="Student/Coach">Student/Coach</option>
                    <option value="Regular/Coach">Regular/Coach</option>
                </select>
                </div>

                <div class="form-group">
                <button type="submit">Add Member</button>
                <a href="members.php"><button type="button">Return to Members</button></a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>