<?php
header('Content-Type: application/json');

// Set the timezone to your desired timezone
date_default_timezone_set('Asia/Macao');

// Retrieve the QR code content from the POST request
$request_body = file_get_contents('php://input');
$data = json_decode($request_body);

if ($data && isset($data->content)) {
    $qr_content = $data->content;

    // Database connection configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "attendance";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // User is making attendance
    $attendance_time = date('h:i:s A');
    
    // Check if the user has checked in today
    $check_last_attendance_query = "SELECT * FROM attendance_table WHERE qr_content = '$qr_content' ORDER BY id DESC LIMIT 1";
    $last_attendance_result = $conn->query($check_last_attendance_query);

    if ($last_attendance_result->num_rows > 0) {
        // User has a previous attendance record
        $last_attendance = $last_attendance_result->fetch_assoc();

        if ($last_attendance['time_out'] !== null) {
            // Last attendance was a time_out, so insert a new time_in
            $insert_query = "INSERT INTO attendance_table (qr_content, time_in) VALUES ('$qr_content', '$attendance_time')";

            if ($conn->query($insert_query) === TRUE) {
                $response = ['status' => 'success', 'message' => 'Attendance recorded successfully (time_in).'];
            } else {
                $response = ['status' => 'error', 'message' => 'Error recording time_in: ' . $conn->error];
            }
        } else {
            // Last attendance was a time_in, so update the time_out
            $attendance_id = $last_attendance['id'];

            $update_time_out_query = "UPDATE attendance_table SET time_out = '$attendance_time' WHERE id = $attendance_id";

            if ($conn->query($update_time_out_query) === TRUE) {
                $response = ['status' => 'success', 'message' => 'Attendance recorded successfully (time_out).'];
            } else {
                $response = ['status' => 'error', 'message' => 'Error recording time_out: ' . $conn->error];
            }
        }
    } else {
        // User is checking in for the first time today
        $insert_query = "INSERT INTO attendance_table (qr_content, time_in) VALUES ('$qr_content', '$attendance_time')";

        if ($conn->query($insert_query) === TRUE) {
            $response = ['status' => 'success', 'message' => 'Attendance recorded successfully (time_in).'];
        } else {
            $response = ['status' => 'error', 'message' => 'Error recording time_in: ' . $conn->error];
        }
    }

    // Close the database connection
    $conn->close();

    // Send a response to the client
    echo json_encode($response);
} else {
    // Send an error response if the QR code content is not provided
    $response = ['status' => 'error', 'message' => 'Invalid QR code content.'];
    echo json_encode($response);
}
?>
