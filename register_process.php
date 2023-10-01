<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "user_login");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert user data into the database
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful. <a href='login.php'>Login</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close(); 
}
?>
