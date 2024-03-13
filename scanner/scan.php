<?php
header('Content-Type: application/json');

// Set the timezone to your desired timezone
date_default_timezone_set('Asia/Macao');

// Retrieve the QR code content from the POST request
$request_body = file_get_contents('php://input');
$data = json_decode($request_body);

// Initialize the response array
$response = ['status' => 'error', 'message' => 'Invalid request.'];

if ($data && isset($data->content)) {
    $qr_content = $data->content;

    // Database connection configuration for attendance database
    $servername_attendance = "localhost";
    $username_attendance = "root";
    $password_attendance = "";
    $dbname_attendance = "attendance";

    // Create connection to the attendance database
    $conn_attendance = new mysqli($servername_attendance, $username_attendance, $password_attendance, $dbname_attendance);

    // Check connection to the attendance database
    if ($conn_attendance->connect_error) {
        $response['message'] = "Connection to attendance database failed: " . $conn_attendance->connect_error;
    } else {
        // User is making attendance
        $attendance_datetime = date('Y-m-d H:i:s'); // Format datetime as 'YYYY-MM-DD HH:MM:SS'

        // Check if the user has checked in today
        $check_last_attendance_query = "SELECT * FROM attendance_table WHERE qr_content = '$qr_content' ORDER BY id DESC LIMIT 1";
        $last_attendance_result = $conn_attendance->query($check_last_attendance_query);

        if ($last_attendance_result === false) {
            $response['message'] = 'Error in the query: ' . $conn_attendance->error;
        } elseif ($last_attendance_result->num_rows > 0) {
            // User has a previous attendance record
            $last_attendance = $last_attendance_result->fetch_assoc();

            if ($last_attendance['time_out'] !== null) {
                // Last attendance was a time_out, so insert a new time_in
                $insert_query = "INSERT INTO attendance_table (qr_content, time_in) VALUES ('$qr_content', '$attendance_datetime')";

                if ($conn_attendance->query($insert_query) === TRUE) {
                    $response = ['status' => 'success', 'message' => 'Attendance recorded successfully (time_in).'];
                } else {
                    $response['message'] = 'Error recording time_in: ' . $conn_attendance->error;
                }
            } else {
                // Last attendance was a time_in, so update the time_out
                $attendance_id = $last_attendance['id'];

                $update_time_out_query = "UPDATE attendance_table SET time_out = '$attendance_datetime' WHERE id = $attendance_id";

                if ($conn_attendance->query($update_time_out_query) === TRUE) {
                    $response = ['status' => 'success', 'message' => 'Attendance recorded successfully (time_out).'];
                } else {
                    $response['message'] = 'Error recording time_out: ' . $conn_attendance->error;
                }
            }
        } else {
            // User is checking in for the first time today
            $insert_query = "INSERT INTO attendance_table (qr_content, time_in) VALUES ('$qr_content', '$attendance_datetime')";

            if ($conn_attendance->query($insert_query) === TRUE) {
                $response = ['status' => 'success', 'message' => 'Attendance recorded successfully (time_in).'];
            } else {
                $response['message'] = 'Error recording time_in: ' . $conn_attendance->error;
            }
        }

        // Close the attendance database connection
        $conn_attendance->close();

        // Now, establish connection to the database where the status is stored
        $servername_status = "localhost";
        $username_status = "root";
        $password_status = "";
        $dbname_status = "members";

        // Create connection to the status database
        $conn_status = new mysqli($servername_status, $username_status, $password_status, $dbname_status);

        // Check connection to the status database
        if ($conn_status->connect_error) {
            $response['message'] .= " Connection to status database failed: " . $conn_status->connect_error;
        } else {
            // Fetch the member status
            $fetch_status_query = "SELECT status FROM members_list WHERE name = '$qr_content'";
            $status_result = $conn_status->query($fetch_status_query);
            if ($status_result->num_rows > 0) {
                $status_row = $status_result->fetch_assoc();
                $member_status = $status_row['status'];
                $response['member_status'] = $member_status;
                $response['status'] = 'success';
                $response['message'] = 'Attendance recorded successfully (time_in). Status fetched successfully.';
            } else {
                $response['message'] = 'No status found for the member.';
            }

            // Close the status database connection
            $conn_status->close();
        }
    }
}

// Send a response to the client
echo json_encode($response);
?>
