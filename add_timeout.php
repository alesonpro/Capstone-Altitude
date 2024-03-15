<!DOCTYPE html>
<html>
<head>
    <title>Logout Form</title>
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

h3 {
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
input[type="text"] {
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
    margin-top: 10px;
}

/* Button hover effect */
button:hover {
    background-color: black;
}

/* Adjusting anchor tag styles */
a {
    text-decoration: none;
    display: block;
    margin-top: 10px;
}

</style>
</head>
<body>
    <form class="container card" method="post" action="update_timeout.php">
        <h3>Logout</h3>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" required><br>
        </div>

        <div class="form-group">
            <label for="logout_time">Logout Time:</label>
            <input type="datetime-local" name="logout_time" required><br>
        </div>

        <button type="submit" name="submit">Submit</button>
        <!-- Add button to return to logs.php -->
        <a href="/Capstone-Altitude/scanner/index.html"><button type="button">Return to Home</button></a>
    </form>
</body>
</html>
