<?php 
require_once "utils/gameutils.php";

$player_id = $_GET['player_id'];
$action = $_GET['action'];
$action_id = $_GET['action_id'];
$card_id = $_GET['card_id'];
$game_id = $_GET['game_id'];


if ($action == "cycle") {
    cycle($game_id, $player_id, $card_id);
}


function cycle ($game_id,$player_id, $card_id) {

    $game = new GameUtils($game_id);

    $game->cycle($player_id, $card_id);
}