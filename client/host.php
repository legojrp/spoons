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
    require_once "../server/utils/creds.php";
    require_once "../server/utils/mysql.php";

    $player_id = $_COOKIE["player_id"];

    require_once "../server/utils/gameinit.php";
    
    

    ?>
    </h2>

    <h2> Players in lobby: </h2>
    <script>
        var interval = setInterval(function(){
            $.ajax({
                url: '../server/game_lobby.php',
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