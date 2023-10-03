<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "user_login");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert user data into the database
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        // Display a success message using JavaScript alert
        echo "<script>
                alert('Registration successful.');
                window.location.href = 'login.php';
              </script>";
    } else {
        // Display an error message using JavaScript alert
        echo "<script>
                alert('Error: " . $sql . "\\n" . $conn->error . "');
                window.location.href = 'register.php';
              </script>";
    }

    $conn->close();
}
?>
