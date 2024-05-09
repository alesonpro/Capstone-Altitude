<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400&family=Russo+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="header">
        <div class="img">
            <a href="login.php"><img src="./img/logo.png"/></a>
        </div>

        <div class="logo-text">ALTITUDE</div>
    </div>
    
    <form action="register_process.php" method="POST">
        <a href="login.php" class="back-link"><i class="fas fa-arrow-left fa-lg"></i></a>
        <h2>Register</h2>
        <div class="input-container">
            <label for="username">Username</label>
            <input class="username" type="text" name="username" id="username" required>
            <div class="divider"></div>
            <label for="password">Password</label>
            <input class="password" type="password" name="password" id="password" required>
        </div>
        <div class="input-container">
        <div class="divider"></div>
            <input class="submit" type="submit" value="Sign up">
            
        </div>
    </form>
</body>
</html>

