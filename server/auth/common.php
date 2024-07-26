<?php 

    $result = $mysql->select("users", "user_id", "WHERE username='$username' AND password='$password'");
    $player_id = $result[0]['user_id'];

    setcookie('player_id', $player_id, 0, '/');

    $player_id_to_verify = $_COOKIE['player_id'];
    if ($player_id == $player_id_to_verify) {
        echo "success";
    }
    else {
        echo "failure";
    }
    
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Spoons</title>
    </head>
    <body>
        <h1>Hey guys! Host or Join</h1>
        <div>
            <a href="../client/host.php">Host</a>
            <a href="../client/join.php">Join</a>
        </div>
    
    </body>
    </html>
    