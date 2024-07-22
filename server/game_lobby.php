<?php 
require_once "utils/creds.php";
require_once "utils/mysql.php";

$join_code = $_GET['join_code'];

$creds = new Creds();
$mysql = new MySQLDatabase($creds);


$players = $mysql->select("game_$join_code", "players");

echo (count($players));
