<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You won!</title>
</head>
<body>
    <h1>You won!</h1>
    <p> You now have <b><?php 

        require_once "../../server/utils/creds.php";
        require_once "../../server/utils/mysql.php";

        $creds = new Creds();
        $mysql = new MySQLDatabase($creds);
        echo $mysql->select("users", "num_wins", "WHERE user_id = '" . $_COOKIE["player_id"] . "'")[0]["num_wins"];
        ?> wins!</b></p>

    <a href="../index.php">Go back</a>
</body>
</html>