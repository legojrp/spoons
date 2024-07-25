<?php 
$player_id = $_COOKIE["player_id"];
$id = $_GET["join_code"];

require "../utils/creds.php";
require "../utils/mysql.php";

$creds = new Creds();
$mysql = new MySQLDatabase($creds);

if (!$mysql->select("game_list", "active", "WHERE join_code = '$id'")[0]) {
    echo "Game not found";
    require "../../client/join.php";
}

$mysql->update("users", array("in_game" => "$id"), "user_id = $player_id");
$mysql->insert("game_$id", array("user_id" => $player_id));

echo "joined $id";
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
                        window.location.href = "../../client/game.php?join_code=" + "<?php echo $id; ?>";
                    }
                }
            });
        }, 1000);
    </script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

</body>
</html>