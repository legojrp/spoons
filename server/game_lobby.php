<?php 
require_once "utils/creds.php";
require_once "utils/mysql.php";

$join_code = $_GET['join_code'];

$creds = new Creds();
$mysql = new MySQLDatabase($creds);


$players = $mysql->select("game_$join_code", "user_id");

echo "<ul>";
foreach ($players as $player) {
    $gamename = $mysql->select("users", "gamename", " WHERE user_id = '" . $player["user_id"] . "'")[0]["gamename"];
    echo "<li>" . $gamename . "</li>";
}
echo "</ul>";
