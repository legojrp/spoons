<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <h1>Sign Up</h1>
    <form action="../../server/auth/signup.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username"><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password"><br>
        <label for="gamename">Gamename:</label>
        <input type="text" name="gamename" id="gamename"><br>
        <input type="submit" value="Sign Up">
    </form>
</body>
</html>
