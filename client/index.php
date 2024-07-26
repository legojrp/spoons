<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>
<body>
    <?php 
    if (isset($_COOKIE["player_id"])) {
        require "./decision.php";
    }
    else {
        echo "<h1>Not Logged In</h1>";
    echo "<a href=\"auth/login.php\">Log In</a>";
    echo "<br>";
    echo "<a href=\"auth/signup.php\">Sign Up</a>";

    }
    ?>
</body>
</html>