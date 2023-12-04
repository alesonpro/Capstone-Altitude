<?php

include 'auto_update_status.php';
// pay_dues.php

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pay_dues'])) {
    // Connect to the database
    $connection = mysqli_connect("localhost", "root", "", "members");

    // Check connection
    if ($connection === false) {
        die("Error: Connection error. " . mysqli_connect_error());
    }

    // Sanitize user input
    $memberId = mysqli_real_escape_string($connection, $_POST['id']);

    // Get member data
    $query = "SELECT * FROM members_list WHERE id = '$memberId'";
    $result = mysqli_query($connection, $query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check if the member has a due date in the future
        $currentDate = $row['date_today'];
        $existingDueDate = $row['due_date'];

        if ($existingDueDate >= $currentDate) {
            // Member has a due date in the future, add 30 days to the existing due date
            $newDueDate = date('Y-m-d', strtotime($existingDueDate . ' + 30 days'));
        } else {
            // Member has no existing due date or the due date is in the past, add 30 days to the current date
            $newDueDate = date('Y-m-d', strtotime($currentDate . ' + 30 days'));
        }

        // Update due date in the database
        $updateQuery = "UPDATE members_list SET due_date = '$newDueDate' WHERE id = '$memberId'";
        $updateResult = mysqli_query($connection, $updateQuery);

        if ($updateResult) {
            // Update member status in the database
            $status = 'Active';
            $updateStatusQuery = "UPDATE members_list SET status = '$status' WHERE id = '$memberId'";
            $updateStatusResult = mysqli_query($connection, $updateStatusQuery);

            if ($updateStatusResult) {
                echo "<script>alert('Payment processed successfully. Member status updated to $status. Due date updated.'); window.location.href='dues.php'</script>";
            } else {
                echo "Error updating member status: " . mysqli_error($connection);
            }
        } else {
            echo "Error updating due date: " . mysqli_error($connection);
        }
    } else {
        echo "Error fetching member data: " . mysqli_error($connection);
    }

    // Close the database connection
    mysqli_close($connection);
}
?>
