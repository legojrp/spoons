<?php 
$player_id = $_COOKIE["player_id"];
$id = $_GET["join_code"];

require_once "../utils/creds.php";
require_once "../utils/mysql.php";
require_once "../utils/gameutils.php";

$creds = new Creds();
$mysql = new MySQLDatabase($creds);
$gameutils = new GameUtils($id);

if (!$mysql->select("game_list", "active", "WHERE join_code = '$id'")[0]) {
    echo "Game not found";
    require "../../client/join.php";
}


if (isset($mysql->select("game_$id", "user_id", "WHERE user_id = $player_id")[0])) {
    echo "Already in game";
}
else {
    $gameutils->playerJoined($player_id);
    echo "joined $id";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joined Game</title>


</head>
<body>
    <script>
        var interval = setInterval(function(){
            $.ajax({
                url: '../lobby/ready.php',
                data: {join_code: "<?php echo $id; ?>"},
                type: 'GET',
                success: function(response) {
                    if (response == "true") {
                        window.location.href = "../../client/game/game.php?join_code=" + "<?php echo $id; ?>";
                    }
                }
            });
        }, 1000);
    </script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

</body>
</html>