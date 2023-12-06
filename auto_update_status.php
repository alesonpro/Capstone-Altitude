<?php
// auto_update_status.php
date_default_timezone_set('Asia/Manila');
// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "members");

// Check connection
if ($connection === false) {
    die("Error: Connection error. " . mysqli_connect_error());
}

// Get current date
$currentDate = date("Y-m-d");

// Select members who are past due
$query = "SELECT * FROM members_list WHERE due_date < '$currentDate' AND status = 'Active'";
$result = mysqli_query($connection, $query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Member is past due, set status to 'Expired'
        $memberId = $row['id'];
        $updateStatusQuery = "UPDATE members_list SET status = 'Expired' WHERE id = '$memberId'";
        $updateStatusResult = mysqli_query($connection, $updateStatusQuery);

        if ($updateStatusResult) {
            echo "Member ID $memberId status updated to 'Expired'.\n";
        } else {
            echo "Error updating member ID $memberId status: " . mysqli_error($connection) . "\n";
        }
    }
} else {
    echo "Error fetching past due members: " . mysqli_error($connection) . "\n";
}

// Close the database connection
mysqli_close($connection);
?>
