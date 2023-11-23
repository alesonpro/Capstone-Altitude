<?php
// Function to format the date as MM-DD-YYYY
function formatDate($inputDate) {
    return date("m-d-Y", strtotime($inputDate));
}

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
    
    // Format the joining date using the formatDate function
    $joiningDate = formatDate($_POST['joining_date']);

    $category = mysqli_real_escape_string($connection, $_POST['category']);

    // Insert member data into the database
    $insertQuery = "INSERT INTO members_list (name, joining_date, Category) VALUES ('$name', '$joiningDate', '$category')";
    $result = mysqli_query($connection, $insertQuery);

    if ($result) {
        echo "Member added successfully.";
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
<body>
    <h3>Add Member</h3>
    <form method="post" action="">
        <label>Name:</label>
        <input type="text" name="name" required><br>

        <label>Joining Date:</label>
        <input type="date" name="joining_date" required><br>

        <label>Category:</label>
        <select name="category" required>
            <option value="Student">Student</option>
            <option value="Regular">Regular</option>
            <option value="Regular/Coach">Regular/Coach</option>
        </select><br>

        <button type="submit">Add Member</button>
        <!-- Add button to return to members.php -->
        <a href="members.php"><button type="button">Return to Members</button></a>
    </form>
</body>
</html>
