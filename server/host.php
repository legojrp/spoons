<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>
    <h1>Host Page</h1>

    <h2>Join Code: 
    <?php 
    require_once "utils/creds.php";
    require_once "utils/mysql.php";

    $creds = new Creds();
    $mysql = new MySQLDatabase($creds);
    
    require_once "utils/idmaker.php";
    $id = idmaker();
    $mysql->insert("game_list", array("join_code" => $id, "active" => "1"));
    $mysql->createTable("game_$id", "players varchar(255) NOT NULL, player_id int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (player_id)");

    echo $id;
    ?>
    </h2>

    <h2> Players in lobby: </h2>
    <script>
        var interval = setInterval(function(){
            $.ajax({
                url: 'game_lobby.php',
                data: {join_code: "<?php echo $id; ?>"},
                type: 'GET',
                success: function(response) {
                    $('#players_in_lobby').html(response);
                }
            });
        }, 2000);
    </script>
    
    <h2 id="players_in_lobby"></h2>

    <a href="start.php?join_code=<?php echo $join_code;?>&<?php echo $player_id;?>">Start Game</a>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>



</body>
</html>