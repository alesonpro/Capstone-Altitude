<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400&display=swap" rel="stylesheet">
</head>
<body>
    <h2>Register</h2>
    <form action="register_process.php" method="POST">
        <div class="input-container">
            <label for="username">Username</label>
            <input class="username" type="text" name="username" id="username" required>
        </div>
        <div class="input-container">
            <label for="password">Password</label>
            <input class="password" type="password" name="password" id="password" required>
        </div>
        <div class="input-container">
            <input class="submit" type="submit" value="Register">
        </div>
    </form>
</body>
</html>

