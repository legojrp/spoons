<?php 

    $result = $mysql->select("users", "user_id", "WHERE username='$username' AND password='$password'");
    $player_id = $result[0]['user_id'];

    setcookie('player_id', $player_id, 0);

    $player_id_to_verify = $_COOKIE['player_id'];
    if ($player_id == $player_id_to_verify) {
        echo "success";
    }
    else {
        echo "failure";
    }
    

require_once "../../server/index.php";