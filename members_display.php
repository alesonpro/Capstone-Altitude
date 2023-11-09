<?php
// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "members");

// Retrieve member data
$query = "SELECT * FROM members_list";
$result = mysqli_query($connection, $query);

// Display member data
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<p>Name: " . $row['name'] . "</p>";
        echo "<p>Email: " . $row['email'] . "</p>";
        // Add more member information as needed
        echo "<hr>";
    }
} else {
    echo "No members found.";
}

// Close the database connection
mysqli_close($connection);
?>