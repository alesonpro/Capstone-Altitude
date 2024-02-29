<?php
// auto_update_status.php
date_default_timezone_set('Asia/Manila');

// Connect to the database
$connection = new mysqli("localhost", "root", "", "members");

// Check connection
if ($connection->connect_error) {
    die("Error: Connection error. " . $connection->connect_error);
}

// Get current date
$currentDate = date("Y-m-d");

// Select members who are past due
$query = "SELECT id FROM members_list WHERE due_date < ? AND status = 'Active'";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $currentDate);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Member is past due, set status to 'Expired'
        $memberId = $row['id'];
        $updateStatusQuery = "UPDATE members_list SET status = 'Expired' WHERE id = ?";
        $stmtUpdate = $connection->prepare($updateStatusQuery);
        $stmtUpdate->bind_param("i", $memberId);
        
        // Execute the update statement
        $stmtUpdate->execute();

        // Check for errors in the execution
        if ($stmtUpdate->error) {
            echo "Error updating status: " . $stmtUpdate->error . "\n";
        }
        
        // Close the update statement
        $stmtUpdate->close();
    }
} else {
    echo "Error fetching past due members: " . $connection->error . "\n";
}

// Close the database connection
$stmt->close();
$connection->close();

// Connect to the database
$connection = new mysqli("localhost", "root", "", "members");

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Get current date
$currentDate = date("Y-m-d");

// Update member statuses based on due dates
$updateExpiredQuery = "UPDATE members_list SET status = 'Expired' WHERE due_date < '$currentDate'";
$updateActiveQuery = "UPDATE members_list SET status = 'Active' WHERE due_date >= '$currentDate'";

if ($connection->query($updateExpiredQuery) === TRUE && $connection->query($updateActiveQuery) === TRUE) {
    // echo "Member statuses updated successfully.";
} else {
    // echo "Error updating member statuses: " . $connection->error;
}

// Close the database connection
$connection->close();
?>
