<?php 
require_once "utils/creds.php";
require_once "utils/mysql.php";

$join_code = $_GET['join_code'];
$player_id = $_GET['player_id'];

$creds = new Creds();
$mysql = new MySQLDatabase($creds);

$mysql->checkConnection();

$players = $mysql->select("game_$join_code", "players");

echo ($players);

?>
